@extends('layouts.admin')

@section('title', 'Dashboard - Admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
      
    </div>

    <!-- Grid Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Users Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Pengguna</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUsers }}</p>
                    <p class="text-xs text-gray-400 mt-2">
                        <span class="text-green-600 font-semibold">{{ $activePegawai }}</span> aktif
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Bookings Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Booking</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalBookings }}</p>
                    <p class="text-xs text-gray-400 mt-2">
                        <span class="text-yellow-600 font-semibold">{{ $pendingBookings }}</span> menunggu
                    </p>
                </div>
                <div class="bg-amber-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Aula Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Aula</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalAula }}</p>
                    <p class="text-xs text-gray-400 mt-2">
                        <span class="text-green-600 font-semibold">{{ $activeAula }}</span> aktif
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Absensi Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Absensi</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalAbsensi }}</p>
                    <p class="text-xs text-gray-400 mt-2">
                        <span class="text-green-600 font-semibold">{{ $hadir }}</span> hadir
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Booking Status Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Booking</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Pending</span>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">{{ $pendingBookings }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Approved</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">{{ $approvedBookings }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Completed</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">{{ $completedBookings }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Rejected</span>
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">{{ $rejectedBookings }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Cancelled</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">{{ $cancelledBookings }}</span>
                </div>
            </div>
        </div>

        <!-- Absensi Status Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Absensi</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Hadir</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">{{ $hadir }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Tidak Hadir</span>
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">{{ $tidak }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Belum Diabsen</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">{{ $belumAbsen }}</span>
                </div>
            </div>
        </div>

        <!-- Account Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Akun</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Admin</span>
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold">{{ $totalAdmins }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Pegawai Aktif</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">{{ $activePegawai }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Akun Nonaktif</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">{{ $inactiveAccounts }}</span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t">
                    <span class="text-sm font-medium text-gray-900">Total</span>
                    <span class="px-3 py-1 bg-gray-200 text-gray-900 rounded-full text-sm font-semibold">{{ $totalUsers }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Recent & Top -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Booking Terbaru</h3>
                <a href="{{ route('admin.kelolabooking.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200">
                        <tr>
                            <th class="text-left py-2 px-3 font-semibold text-gray-700">Pengguna</th>
                            <th class="text-left py-2 px-3 font-semibold text-gray-700">Aula</th>
                            <th class="text-left py-2 px-3 font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-3 text-gray-900">{{ $booking->user->name }}</td>
                                <td class="py-3 px-3 text-gray-600">{{ $booking->aula->nama ?? 'N/A' }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusBadgeClasses($booking->status) }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 px-3 text-center text-gray-500">Tidak ada booking terbaru</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Aulas -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Aula Paling Sering Dibook</h3>
                <a href="{{ route('admin.aula.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="space-y-3">
                @forelse($topAula as $index => $aula)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $aula->nama }}</p>
                                <p class="text-xs text-gray-500">Kapasitas: {{ $aula->kapasitas }} orang</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                            {{ $aula->bookings_count }} booking
                        </span>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">Tidak ada aula</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Booking Trend Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Trend (12 Bulan)</h3>
            <canvas id="bookingTrendChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Booking Status Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Booking</h3>
            <canvas id="bookingStatusChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Absensi Distribution Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Absensi</h3>
            <canvas id="absensiChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- User Distribution Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Pengguna</h3>
            <canvas id="userChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
    // Chart Colors Palette
    const colors = {
        primary: '#4f46e5',
        success: '#10b981',
        warning: '#f59e0b',
        danger: '#ef4444',
        info: '#3b82f6',
        secondary: '#8b5cf6'
    };

    // ===== BOOKING TREND CHART =====
    const bookingTrendCtx = document.getElementById('bookingTrendChart').getContext('2d');
    new Chart(bookingTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($bookingTrend)) !!},
            datasets: [{
                label: 'Booking',
                data: {!! json_encode(array_values($bookingTrend)) !!},
                borderColor: colors.primary,
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // ===== BOOKING STATUS CHART =====
    const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
    new Chart(bookingStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Approved', 'Completed', 'Rejected', 'Cancelled'],
            datasets: [{
                data: [
                    {{ $pendingBookings ?? 0 }},
                    {{ $approvedBookings ?? 0 }},
                    {{ $completedBookings ?? 0 }},
                    {{ $rejectedBookings ?? 0 }},
                    {{ $cancelledBookings ?? 0 }}
                ],
                backgroundColor: [
                    '#fbbf24',
                    '#60a5fa',
                    '#34d399',
                    '#f87171',
                    '#d1d5db'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 12 },
                        padding: 15,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // ===== ABSENSI DISTRIBUTION CHART =====
    const absensiCtx = document.getElementById('absensiChart').getContext('2d');
    new Chart(absensiCtx, {
        type: 'pie',
        data: {
            labels: ['Hadir', 'Tidak Hadir', 'Belum Diabsen'],
            datasets: [{
                data: [
                    {{ $hadir ?? 0 }},
                    {{ $tidak ?? 0 }},
                    {{ $belumAbsen ?? 0 }}
                ],
                backgroundColor: [
                    '#10b981',
                    '#ef4444',
                    '#9ca3af'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 12 },
                        padding: 15,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // ===== USER DISTRIBUTION CHART =====
    const userCtx = document.getElementById('userChart').getContext('2d');
    new Chart(userCtx, {
        type: 'bar',
        data: {
            labels: ['Admin', 'Pegawai Aktif', 'Nonaktif'],
            datasets: [{
                label: 'Jumlah Pengguna',
                data: [
                    {{ $totalAdmins ?? 0 }},
                    {{ $activePegawai ?? 0 }},
                    {{ $inactiveAccounts ?? 0 }}
                ],
                backgroundColor: [
                    colors.primary,
                    colors.success,
                    colors.warning
                ],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>

@endsection
