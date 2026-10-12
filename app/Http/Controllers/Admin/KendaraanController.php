<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\PajakKendaraan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KendaraanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Kendaraan::query()->with(['pajakTahunanAktif', 'pajakLimaTahunanAktif']);
        $query->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->string('search')->toString();
            $query->where(function ($query) use ($search) {
                $query->where('plat_nomor', 'like', "%{$search}%")
                    ->orWhere('nomor_rangka', 'like', "%{$search}%")
                    ->orWhere('pemakai', 'like', "%{$search}%");
            });
        });
        $query->when($request->filled('unit_kerja'), fn ($query) => $query->where('unit_kerja', $request->input('unit_kerja')));
        $query->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')));

        $kendaraan = $query->get()->sortBy(function (Kendaraan $item): array {
            $days = collect([$item->pajakTahunanAktif?->sisa_hari, $item->pajakLimaTahunanAktif?->sisa_hari])->filter(fn ($day) => $day !== null)->min();

            return [$days === null ? 1 : 0, $days ?? PHP_INT_MAX];
        })->values();

        return view('admin.kendaraan.index', compact('kendaraan'));
    }

    public function create(): View
    {
        return view('admin.kendaraan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateVehicle($request);
        $taxData = $this->validateTaxDates($request);

        $kendaraan = DB::transaction(function () use ($data, $taxData, $request): Kendaraan {
            $data['foto'] = $this->storePhoto($request);
            $kendaraan = Kendaraan::create($data);
            foreach (['tahunan', '5_tahunan'] as $jenis) {
                PajakKendaraan::create(['kendaraan_id' => $kendaraan->id, 'jenis_pajak' => $jenis, 'tanggal_jatuh_tempo' => $taxData[$jenis], 'status' => 'belum_bayar']);
            }

            return $kendaraan;
        });

        return redirect()->route('admin.kendaraan.show', $kendaraan)->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function show(int $id): View
    {
        $kendaraan = Kendaraan::with(['pajakTahunanAktif', 'pajakLimaTahunanAktif', 'pajakKendaraan'])->findOrFail($id);

        return view('admin.kendaraan.show', compact('kendaraan'));
    }

    public function edit(int $id): View
    {
        $kendaraan = Kendaraan::with(['pajakTahunanAktif', 'pajakLimaTahunanAktif'])->findOrFail($id);

        return view('admin.kendaraan.edit', compact('kendaraan'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $data = $this->validateVehicle($request, $kendaraan->id);
        $taxData = $this->validateTaxDates($request);

        DB::transaction(function () use ($kendaraan, $data, $taxData, $request): void {
            if ($request->hasFile('foto')) {
                if ($kendaraan->foto) {
                    Storage::disk('public')->delete($kendaraan->foto);
                }
                $data['foto'] = $this->storePhoto($request);
            }
            $kendaraan->update($data);
            foreach ($taxData as $jenis => $tanggal) {
                $pajak = $kendaraan->pajakKendaraan()->where('jenis_pajak', $jenis)->where('status', 'belum_bayar')->latest('tanggal_jatuh_tempo')->first();
                if ($pajak) {
                    $pajak->update(['tanggal_jatuh_tempo' => $tanggal]);
                } else {
                    $kendaraan->pajakKendaraan()->create(['jenis_pajak' => $jenis, 'tanggal_jatuh_tempo' => $tanggal, 'status' => 'belum_bayar']);
                }
            }
        });

        return redirect()->route('admin.kendaraan.show', $kendaraan)->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $kendaraan = Kendaraan::findOrFail($id);
        if ($kendaraan->foto) {
            Storage::disk('public')->delete($kendaraan->foto);
        }
        $kendaraan->delete();

        return redirect()->route('admin.kendaraan.index')->with('success', 'Kendaraan berhasil dihapus.');
    }

    public function tandaiBayar(int $pajakId): RedirectResponse
    {
        DB::transaction(function () use ($pajakId): void {
            $pajak = PajakKendaraan::lockForUpdate()->findOrFail($pajakId);
            if ($pajak->status === 'sudah_bayar') {
                abort(422, 'Pajak ini sudah ditandai bayar.');
            }
            $pajak->update(['status' => 'sudah_bayar', 'tanggal_bayar' => now()->toDateString()]);
            $pajak->kendaraan->pajakKendaraan()->create(['jenis_pajak' => $pajak->jenis_pajak, 'tanggal_jatuh_tempo' => $pajak->tanggal_jatuh_tempo->copy()->addYears($pajak->jenis_pajak === 'tahunan' ? 1 : 5), 'status' => 'belum_bayar']);
        });

        return back()->with('success', 'Pajak ditandai sudah bayar dan periode berikutnya dibuat.');
    }

    private function validateVehicle(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'plat_nomor' => ['required', 'string', 'max:255', 'unique:kendaraan,plat_nomor'.($ignoreId ? ','.$ignoreId : '')],
            'merk' => ['nullable', 'string', 'max:255'],
            'nomor_rangka' => ['nullable', 'string', 'max:255'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'pemakai' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:15360'],
            'status' => ['nullable', 'in:aktif,dijual,rusak'],
            'catatan' => ['nullable', 'string'],
        ]);
    }

    private function validateTaxDates(Request $request): array
    {
        $validated = $request->validate([
            'tanggal_jatuh_tempo_tahunan' => ['required', 'date'],
            'tanggal_jatuh_tempo_5_tahunan' => ['required', 'date'],
        ]);

        return [
            'tahunan' => $validated['tanggal_jatuh_tempo_tahunan'],
            '5_tahunan' => $validated['tanggal_jatuh_tempo_5_tahunan'],
        ];
    }

    private function storePhoto(Request $request): ?string
    {
        return $request->hasFile('foto') ? $request->file('foto')->store('kendaraan', 'public') : null;
    }

    public function dismissAlert(): RedirectResponse
    {
        session(['tax_alert_dismissed' => true]);

        return back();
    }
}
