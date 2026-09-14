<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiDetail;
use App\Models\AbsensiSesi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Daftar semua sesi absensi
     * Filter: hari_ini, ice_breaking, semua
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'hari_ini');
        $search = $request->input('search');

        $query = AbsensiSesi::with('creator')
            ->withCount([
                'details as jumlah_hadir' => fn($q) => $q->where('status_kehadiran', 'hadir'),
                'details as jumlah_tidak' => fn($q) => $q->where('status_kehadiran', 'tidak'),
            ]);

        // Filter logic (3 filter: hari_ini, ice_breaking, semua)
        if ($filter === 'hari_ini') {
            $query->whereDate('tanggal', today());
        } elseif ($filter === 'ice_breaking') {
            $query->where(function ($q) {
                $q->where('nama_sesi', 'like', '%ice%')
                  ->orWhere('nama_sesi', 'like', '%breaking%');
            });
        }
        // 'semua' → tanpa filter tambahan

        if ($search) {
            $query->where('nama_sesi', 'like', "%{$search}%");
        }

        $sesiList = $query->orderByDesc('tanggal')
                         ->orderByDesc('created_at')
                         ->paginate(9)
                         ->withQueryString();

        return view('admin.absensi.index', compact('sesiList', 'filter', 'search'));
    }

    /**
     * Form buat sesi baru
     */
    public function create()
    {
        return view('admin.absensi.create');
    }

    /**
     * Simpan sesi baru + generate detail absensi untuk semua user aktif
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sesi' => 'required|string|max:255',
            'tanggal'   => 'required|date',
            'lokasi'    => 'nullable|string|max:255',
            'catatan'   => 'nullable|string',
        ]);

        $sesi = AbsensiSesi::create([
            'nama_sesi'  => $validated['nama_sesi'],
            'tanggal'    => $validated['tanggal'],
            'lokasi'     => $validated['lokasi'] ?? null,
            'catatan'    => $validated['catatan'] ?? null,
            'is_default' => false,
            'is_locked'  => false,
            'created_by' => Auth::id(),
        ]);

        // Generate detail untuk semua user aktif saat sesi dibuat
        $users = User::where('status', 'aktif')->get();
        foreach ($users as $u) {
            AbsensiDetail::create([
                'absensi_sesi_id'  => $sesi->id,
                'user_id'          => $u->id,
                'status_kehadiran' => null,
            ]);
        }

        return redirect()
            ->route('admin.absensi.show', $sesi->id)
            ->with('success', "Sesi \"{$sesi->nama_sesi}\" berhasil dibuat dengan {$users->count()} peserta.");
    }

    /**
     * Halaman absensi 1 sesi
     *
     * ⚠️ Behavior berbeda berdasarkan status locked:
     * - Sesi LOCKED    → tampilkan SEMUA user yang punya record (termasuk yang sudah nonaktif)
     *                    Tujuan: data historis tidak hilang saat di-export
     * - Sesi BELUM LOCKED → tampilkan user aktif saja
     *                    Tujuan: user yang resign tidak ikut absen yang belum finalized
     */
    public function show(Request $request, int $id)
    {
        $sesi = AbsensiSesi::with('creator')->findOrFail($id);
        $search = $request->input('search');

        if ($sesi->is_locked) {
            // 🔒 SESI LOCKED: ambil dari absensi_detail
            // Termasuk user yang sudah nonaktif → data historis tetap terjaga
            $peserta = AbsensiDetail::with('user')
                ->where('absensi_sesi_id', $id)
                ->when($search, function ($q, $search) {
                    return $q->whereHas('user', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
                })
                ->whereHas('user') // cegah error kalau user-nya sudah dihapus permanen
                ->get()
                ->map(function ($detail) {
                    return (object) [
                        'id'               => $detail->user->id,
                        'name'             => $detail->user->name,
                        'bidang'           => $detail->user->bidang,
                        'status'           => $detail->user->status, // ⭐ untuk badge "Nonaktif"
                        'detail_id'        => $detail->id,
                        'status_kehadiran' => $detail->status_kehadiran,
                        'keterangan'       => $detail->keterangan,
                    ];
                })
                ->sortBy('name')
                ->values();
        } else {
            // 🔓 SESI BELUM LOCKED: hanya user aktif
            $peserta = User::leftJoin('absensi_detail', function ($join) use ($id) {
                    $join->on('users.id', '=', 'absensi_detail.user_id')
                         ->where('absensi_detail.absensi_sesi_id', '=', $id);
                })
                ->where('users.status', 'aktif')
                ->when($search, function ($q, $search) {
                    return $q->where('users.name', 'like', "%{$search}%");
                })
                ->select(
                    'users.id',
                    'users.name',
                    'users.bidang',
                    'users.status',
                    'absensi_detail.id as detail_id',
                    'absensi_detail.status_kehadiran',
                    'absensi_detail.keterangan'
                )
                ->orderBy('users.name')
                ->get();
        }

        return view('admin.absensi.show', compact('sesi', 'peserta', 'search'));
    }

    /**
     * Update kehadiran peserta
     */
    public function updateKehadiran(Request $request, int $id)
    {
        $sesi = AbsensiSesi::findOrFail($id);

        if ($sesi->is_locked) {
            return redirect()
                ->route('admin.absensi.show', $id)
                ->with('error', 'Absensi sudah dikunci, tidak bisa diubah!');
        }

        $request->validate([
            'kehadiran'   => 'required|array',
            'kehadiran.*' => 'nullable|in:hadir,tidak',
            'keterangan'  => 'nullable|array',
        ]);

        $totalDiupdate = 0;
        foreach ($request->kehadiran as $userId => $status) {
            if (!$status) continue;

            $keterangan = $status === 'tidak'
                ? ($request->keterangan[$userId] ?? null)
                : null;

            AbsensiDetail::updateOrCreate(
                [
                    'absensi_sesi_id' => $id,
                    'user_id'         => $userId,
                ],
                [
                    'status_kehadiran' => $status,
                    'keterangan'       => $keterangan,
                    'waktu_absen'      => now(),
                ]
            );

            $totalDiupdate++;
        }

        return redirect()
            ->route('admin.absensi.show', $id)
            ->with('success', "Absensi berhasil disimpan! {$totalDiupdate} peserta telah diabsen.");
    }

    /**
     * Kunci absensi
     */
    public function lock(int $id)
    {
        $sesi = AbsensiSesi::findOrFail($id);

        if ($sesi->is_locked) {
            return redirect()
                ->route('admin.absensi.show', $id)
                ->with('error', 'Absensi sudah terkunci sebelumnya.');
        }

        $sesi->update(['is_locked' => true]);

        return redirect()
            ->route('admin.absensi.show', $id)
            ->with('success', "Absensi \"{$sesi->nama_sesi}\" berhasil dikunci.");
    }

    /**
     * Export Excel per sesi (placeholder)
     */
    public function exportSesi(int $id)
    {
        $sesi = AbsensiSesi::findOrFail($id);

        return redirect()
            ->route('admin.absensi.show', $id)
            ->with('error', 'Fitur Export Excel belum tersedia. Silakan install package maatwebsite/excel dulu.');
    }

    /**
     * ⭐ Export Rekap Excel — berdasarkan periode (minggu/bulan/tahun)
     */
    public function exportRekap(Request $request)
    {
        $periode = $request->input('periode', 'bulan');

        $now = now();
        if ($periode === 'minggu') {
            $start = $now->copy()->subDays(6)->startOfDay();
            $end = $now->copy()->endOfDay();
            $label = 'Mingguan (' . $start->format('d M') . ' - ' . $end->format('d M Y') . ')';
        } elseif ($periode === 'tahun') {
            $start = $now->copy()->startOfYear();
            $end = $now->copy()->endOfYear();
            $label = 'Tahunan (' . $now->year . ')';
        } else {
            $start = $now->copy()->startOfMonth();
            $end = $now->copy()->endOfMonth();
            $label = 'Bulanan (' . $now->translatedFormat('F Y') . ')';
        }

        // Ambil data sesi dalam periode tersebut
        $sesiList = AbsensiSesi::with(['details.user'])
            ->whereBetween('tanggal', [$start, $end])
            ->orderByDesc('tanggal')
            ->get();

        // TODO: Setelah install maatwebsite/excel, generate file Excel di sini
        // return Excel::download(new RekapAbsensiExport($sesiList, $label), 'rekap-absensi.xlsx');

        return redirect()
            ->route('admin.absensi.index')
            ->with('error', "Fitur Export Rekap ({$label}) belum tersedia. Silakan install package maatwebsite/excel dulu.");
    }

    /**
     * Hapus sesi absensi
     */
    public function destroy(int $id)
    {
        $sesi = AbsensiSesi::findOrFail($id);

        if ($sesi->is_default) {
            return redirect()
                ->route('admin.absensi.index')
                ->with('error', 'Sesi default (Ice Breaking) tidak bisa dihapus!');
        }

        $namaSesi = $sesi->nama_sesi;
        $sesi->delete();

        return redirect()
            ->route('admin.absensi.index')
            ->with('success', "Sesi \"{$namaSesi}\" berhasil dihapus.");
    }

    /**
     * ⭐ Kelola Pegawai — daftar user yang bisa diabsen
     * Kriteria peserta absensi: user dengan status 'aktif'
     */
    public function pegawai(Request $request)
    {
        $search = $request->input('search');
        $filterBidang = $request->input('bidang');

        $pegawai = User::where('status', 'aktif')
            ->withCount('absensiDetails')
            ->when($search, function ($q, $search) {
                return $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($filterBidang, function ($q, $bidang) {
                return $q->where('bidang', $bidang);
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $bidangList = User::where('status', 'aktif')
            ->whereNotNull('bidang')
            ->distinct()
            ->orderBy('bidang')
            ->pluck('bidang');

        return view('admin.absensi.pegawai', compact('pegawai', 'search', 'filterBidang', 'bidangList'));
    }
}