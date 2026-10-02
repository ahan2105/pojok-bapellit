<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiDetail;
use App\Models\AbsensiSesi;
use App\Models\Aula;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get badge classes for booking status
     */
    private function getStatusBadgeClasses(string $status): string
    {
        return match ($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Tampilkan dashboard admin dengan statistik
     */
    public function index()
    {
        // ===== STATISTIK PENGGUNA =====
        $totalUsers = User::count();
        $totalAdmins = User::where('is_admin', true)->orWhere('role', 'admin')->count();
        $activePegawai = User::where('status', 'aktif')->where(function ($q) {
            $q->where('is_admin', false)->where('role', '!=', 'admin');
        })->count();
        $inactiveAccounts = User::where('status', 'nonaktif')->count();

        // ===== STATISTIK BOOKING =====
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();
        $rejectedBookings = Booking::where('status', 'rejected')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        // ===== STATISTIK AULA =====
        $totalAula = Aula::count();
        $activeAula = Aula::where('status_aktif', true)->count();
        $inactiveAula = Aula::where('status_aktif', false)->count();

        // ===== STATISTIK ABSENSI =====
        $totalAbsensiSesi = AbsensiSesi::count();
        $totalAbsensi = AbsensiDetail::count();
        $hadir = AbsensiDetail::where('status_kehadiran', 'hadir')->count();
        $tidak = AbsensiDetail::where('status_kehadiran', 'tidak')->count();
        $belumAbsen = AbsensiDetail::whereNull('status_kehadiran')->count();
        // Placeholder untuk compatibility dengan view
        $izin = 0;
        $sakit = 0;
        $alpa = 0;

        // ===== STATISTIK BOOKING BULAN INI =====
        $bookingThisMonth = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // ===== BOOKING BERDASARKAN STATUS (untuk chart) =====
        $bookingByStatus = Booking::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // ===== BOOKING TREND - 12 BULAN TERAKHIR =====
        $bookingTrend = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Booking::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $bookingTrend[$date->format('M Y')] = $count;
        }

        // ===== ABSENSI DISTRIBUTION =====
        $absensiDistribution = [
            'Hadir' => $hadir,
            'Tidak Hadir' => $tidak,
            'Belum Diabsen' => $belumAbsen,
        ];

        // ===== USER DISTRIBUTION =====
        $userDistribution = [
            'Admin' => $totalAdmins,
            'Pegawai Aktif' => $activePegawai,
            'Nonaktif' => $inactiveAccounts,
        ];

        // ===== BOOKING TERBARU =====
        $recentBookings = Booking::with(['user', 'aula'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ===== AULA PALING SERING DIBOOK =====
        $topAula = Aula::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        // Add badge class helper to view
        $statusBadgeClasses = function (string $status): string {
            return match ($status) {
                'pending' => 'bg-yellow-100 text-yellow-800',
                'approved' => 'bg-blue-100 text-blue-800',
                'completed' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
                'cancelled' => 'bg-gray-100 text-gray-800',
                default => 'bg-gray-100 text-gray-800',
            };
        };

        return view('admin.dashboard.index', compact(
            'totalUsers',
            'totalAdmins',
            'activePegawai',
            'inactiveAccounts',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'rejectedBookings',
            'completedBookings',
            'cancelledBookings',
            'totalAula',
            'activeAula',
            'inactiveAula',
            'totalAbsensiSesi',
            'totalAbsensi',
            'hadir',
            'tidak',
            'belumAbsen',
            'izin',
            'sakit',
            'alpa',
            'bookingThisMonth',
            'bookingByStatus',
            'bookingTrend',
            'absensiDistribution',
            'userDistribution',
            'recentBookings',
            'topAula',
            'statusBadgeClasses'
        ));
    }
}
