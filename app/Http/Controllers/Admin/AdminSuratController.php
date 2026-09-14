<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminSuratController extends Controller
{
    /**
     * Daftar surat yang sudah diambil user
     */
    public function index(Request $request)
    {
        $query = Surat::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('no_indek', 'like', "%{$search}%")
                  ->orWhere('isi_surat', 'like', "%{$search}%")
                  ->orWhere('jenis_surat', 'like', "%{$search}%")
                  ->orWhere('asal_surat', 'like', "%{$search}%")
                  ->orWhere('alamat_tujuan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $surats = $query->orderBy('created_at', 'desc')->paginate(15);

        // Split ke variabel biar IDE tidak rewel
        $pengaturan = PengaturanSurat::getSetting();
        $nomorBerikutnya = $pengaturan->generateNomor(1);

        $statistics = [
            'total' => Surat::count(),
            'bulan_ini' => Surat::whereMonth('created_at', date('m'))
                                 ->whereYear('created_at', date('Y'))
                                 ->count(),
            'hari_ini' => Surat::whereDate('created_at', today())->count(),
            'nomor_berikutnya' => $nomorBerikutnya,
        ];

        return view('admin.kelolasurat.index', compact('surats', 'statistics'));
    }

    /**
     * Form edit surat (admin bisa edit nomor dll)
     */
    public function edit(int $id)
    {
        $surat = Surat::with('user')->findOrFail($id);
        return view('admin.kelolasurat.edit', compact('surat'));
    }

    /**
     * Update surat (admin edit)
     */
    public function update(Request $request, int $id)
    {
        $surat = Surat::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'no_surat' => 'required|string|max:255|unique:surats,no_surat,' . $id,
            'jenis_surat' => 'nullable|string|max:100',
            'sifat_surat' => 'nullable|string|max:100',              // ← ganti dari 'in:...'
            'tanggal' => 'nullable|date',
            'asal_surat' => 'nullable|string|max:255',
            'alamat_tujuan' => 'nullable|string|max:255',
            'isi_surat' => 'required|string',
            'banyak_lampiran' => 'nullable|integer|min:0',
            'no_indek' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $surat->update([
                'no_surat' => $request->input('no_surat'),
                'jenis_surat' => $request->input('jenis_surat'),
                'sifat_surat' => $request->input('sifat_surat'),
                'tanggal' => $request->input('tanggal'),
                'asal_surat' => $request->input('asal_surat'),
                'alamat_tujuan' => $request->input('alamat_tujuan'),
                'isi_surat' => $request->input('isi_surat'),
                'banyak_lampiran' => $request->input('banyak_lampiran') ?? 0,
                'no_indek' => $request->input('no_indek'),
                'keterangan' => $request->input('keterangan'),
            ]);

            return redirect()->route('admin.kelolasurat.index')
                ->with('success', 'Surat berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus surat
     */
    public function destroy(int $id)
    {
        try {
            $surat = Surat::findOrFail($id);

            if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
                Storage::disk('public')->delete($surat->file_surat);
            }

            $surat->delete();

            return redirect()->route('admin.kelolasurat.index')
                ->with('success', 'Surat berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Download file dari user (nama file asli)
     */
    public function downloadFile(int $id)
    {
        $surat = Surat::findOrFail($id);

        if (!$surat->file_surat || !Storage::disk('public')->exists($surat->file_surat)) {
            return redirect()->back()->with('error', 'File tidak tersedia.');
        }

        $path = Storage::disk('public')->path($surat->file_surat);

        // Pakai nama file asli kalau ada
        $filename = $surat->file_surat_original_name
            ?? (str_replace('/', '-', $surat->no_surat) . '.' . pathinfo($surat->file_surat, PATHINFO_EXTENSION));

        return response()->download($path, $filename);
    }

    /**
     * Halaman pengaturan nomor surat
     */
    public function pengaturan()
    {
        $pengaturan = PengaturanSurat::getSetting();
        $previewNomor = $pengaturan->generateNomor(1);

        return view('admin.kelolasurat.pengaturan', compact('pengaturan', 'previewNomor'));
    }

    /**
     * Update pengaturan (hanya nomor terakhir)
     */
    public function updatePengaturan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_terakhir' => 'required|integer|min:0',
        ], [
            'nomor_terakhir.required' => 'Nomor terakhir wajib diisi',
            'nomor_terakhir.integer' => 'Nomor terakhir harus berupa angka',
            'nomor_terakhir.min' => 'Nomor terakhir minimal 0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $pengaturan = PengaturanSurat::getSetting();

            $pengaturan->update([
                'nomor_terakhir' => $request->input('nomor_terakhir'),
            ]);

            // Ambil ulang & generate preview
            $pengaturanBaru = PengaturanSurat::getSetting();
            $nomorBerikutnya = $pengaturanBaru->generateNomor(1);

            return redirect()->route('admin.kelolasurat.pengaturan')
                ->with('success', 'Pengaturan berhasil disimpan! Surat berikutnya: ' . $nomorBerikutnya);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}