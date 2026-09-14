<?php

namespace App\Http\Controllers;

use App\Models\AbsensiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    /**
     * Halaman Presensi Saya — riwayat absen user yang login
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $filterStatus = $request->input('status'); // hadir / tidak / kosong

        // Ambil semua detail absensi user ini + info sesinya
        $query = AbsensiDetail::with('sesi')
            ->where('user_id', $user->id)
            ->whereHas('sesi') // pastikan sesi-nya masih ada (tidak dihapus)
            ->when($search, function ($q, $search) {
                return $q->whereHas('sesi', function ($sub) use ($search) {
                    $sub->where('nama_sesi', 'like', "%{$search}%");
                });
            })
            ->when($filterStatus, function ($q, $filterStatus) {
                return $q->where('status_kehadiran', $filterStatus);
            });

        // Riwayat absensi (pagination)
        $riwayat = $query->orderByDesc('created_at')
                         ->paginate(15)
                         ->withQueryString();

        // Statistik kehadiran
        $totalSesi = AbsensiDetail::where('user_id', $user->id)
            ->whereHas('sesi')
            ->count();

        $totalHadir = AbsensiDetail::where('user_id', $user->id)
            ->whereHas('sesi')
            ->where('status_kehadiran', 'hadir')
            ->count();

        $totalTidak = AbsensiDetail::where('user_id', $user->id)
            ->whereHas('sesi')
            ->where('status_kehadiran', 'tidak')
            ->count();

        $totalBelumDiabsen = AbsensiDetail::where('user_id', $user->id)
            ->whereHas('sesi')
            ->whereNull('status_kehadiran')
            ->count();

        // Persentase kehadiran (dari sesi yang sudah diabsen, tidak termasuk yang belum)
        $totalDiabsen = $totalHadir + $totalTidak;
        $persentaseHadir = $totalDiabsen > 0
            ? round(($totalHadir / $totalDiabsen) * 100, 1)
            : 0;

        // Statistik bulan ini
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $hadirBulanIni = AbsensiDetail::where('user_id', $user->id)
            ->whereHas('sesi', function ($q) use ($bulanIni, $tahunIni) {
                $q->whereMonth('tanggal', $bulanIni)
                  ->whereYear('tanggal', $tahunIni);
            })
            ->where('status_kehadiran', 'hadir')
            ->count();

        return view('presensi.index', compact(
            'riwayat',
            'search',
            'filterStatus',
            'totalSesi',
            'totalHadir',
            'totalTidak',
            'totalBelumDiabsen',
            'persentaseHadir',
            'hadirBulanIni'
        ));
    }
}   