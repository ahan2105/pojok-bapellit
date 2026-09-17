@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Presensi Saya')

@section('content')
<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-12 py-6 sm:py-8">

    <!-- Header + Tombol Scan -->
    <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="flex-1">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">Presensi Saya</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-2">
                Riwayat dan statistik kehadiran Anda di semua sesi absensi.
            </p>
        </div>

        {{-- ⭐ Tombol Scan Absensi --}}
        <a href="{{ route('absensi.scan-page') }}" 
           class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm sm:text-base font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition shadow-md hover:shadow-lg self-start sm:self-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
            </svg>
            Scan Absensi
        </a>
    </div>

    <!-- ===== Info Card: Cara Absen (muncul kalau belum ada riwayat) ===== -->
    @if($riwayat->total() == 0)
        <div class="mb-8 p-5 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-2xl">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-purple-900 mb-1">Cara Absen</h3>
                    <ol class="text-sm text-purple-800 space-y-1 list-decimal list-inside">
                        <li>Minta admin menampilkan <strong>QR Code</strong> sesi absensi</li>
                        <li>Klik tombol <strong>"Scan Absensi"</strong> di atas</li>
                        <li>Arahkan kamera ke QR Code atau masukkan token manual</li>
                        <li>Kehadiran dan <strong>Waktu</strong> otomatis tercatat ✅</li>
                    </ol>
                </div>
            </div>
        </div>
    @endif

    <!-- ===== Statistik Cards ===== -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
        
        <!-- Hadir -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-6">
            <div class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">
                Hadir
            </div>
            <div class="text-3xl sm:text-4xl font-bold text-green-600">{{ $totalHadir }}</div>
            <div class="text-xs sm:text-sm text-gray-500 mt-1">
                dari {{ $totalHadir + $totalTidak }} sesi diabsen
            </div>
        </div>

        <!-- Tidak Hadir -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-6">
            <div class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">
                Tidak Hadir
            </div>
            <div class="text-3xl sm:text-4xl font-bold text-red-600">{{ $totalTidak }}</div>
            <div class="text-xs sm:text-sm text-gray-500 mt-1">
                dari {{ $totalHadir + $totalTidak }} sesi diabsen
            </div>
        </div>

        <!-- Persentase -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-6">
            <div class="text-xs sm:text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">
                Persentase Kehadiran
            </div>
            <div class="text-3xl sm:text-4xl font-bold text-purple-600">{{ $persentaseHadir }}%</div>
            <div class="text-xs sm:text-sm text-gray-500 mt-1">
                tingkat kehadiran
            </div>
        </div>
    </div>

    <!-- ===== Toolbar: Search + Filter Status ===== -->
    <div class="bg-white rounded-t-2xl shadow-sm border border-gray-200 p-4 sm:p-5">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
            
            <!-- Search -->
            <form method="GET" action="{{ route('presensi.index') }}" class="flex-1 sm:max-w-md">
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}"
                           placeholder="Cari nama sesi..."
                           class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:outline-none transition">
                    
                    @if($search)
                        <a href="{{ route('presensi.index', ['status' => $filterStatus]) }}" 
                           class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition"
                           title="Hapus pencarian">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Filter Status -->
            <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1 self-start sm:self-auto">
                <a href="{{ route('presensi.index', ['search' => $search]) }}" 
                   class="px-3 sm:px-4 py-2 rounded-md text-sm font-semibold transition whitespace-nowrap
                          {{ !$filterStatus 
                              ? 'bg-white text-blue-600 shadow-sm' 
                              : 'text-gray-600 hover:text-gray-900' }}">
                    Semua
                </a>
                <a href="{{ route('presensi.index', ['search' => $search, 'status' => 'hadir']) }}" 
                   class="px-3 sm:px-4 py-2 rounded-md text-sm font-semibold transition whitespace-nowrap
                          {{ $filterStatus === 'hadir' 
                              ? 'bg-white text-green-600 shadow-sm' 
                              : 'text-gray-600 hover:text-gray-900' }}">
                    Hadir
                </a>
                <a href="{{ route('presensi.index', ['search' => $search, 'status' => 'tidak']) }}" 
                   class="px-3 sm:px-4 py-2 rounded-md text-sm font-semibold transition whitespace-nowrap
                          {{ $filterStatus === 'tidak' 
                              ? 'bg-white text-red-600 shadow-sm' 
                              : 'text-gray-600 hover:text-gray-900' }}">
                    Tidak
                </a>
            </div>
        </div>
    </div>

    <!-- ===== Riwayat Absensi ===== -->
    <div class="bg-white rounded-b-2xl shadow-sm border border-t-0 border-gray-200 overflow-hidden">
        
        {{-- DESKTOP: Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-16">NO</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">NAMA SESI</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">TANGGAL</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">WAKTU</th> <!-- ⭐ BARU -->
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">STATUS</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayat as $index => $r)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-base text-gray-600 font-medium">
                                {{ $riwayat->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-base font-semibold text-gray-800">{{ $r->sesi->nama_sesi }}</div>
                                @if($r->sesi->lokasi)
                                    <div class="text-sm text-gray-500 mt-0.5">📍 {{ $r->sesi->lokasi }}</div>
                                @endif
                                @if($r->sesi->is_default)
                                    <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                        Default
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-base text-gray-700">
                                {{ $r->sesi->tanggal->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-base text-gray-700 font-mono"> <!-- ⭐ BARU -->
                                @if($r->waktu_absen)
                                    {{ \Carbon\Carbon::parse($r->waktu_absen)->format('H:i') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($r->status_kehadiran === 'hadir')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-green-50 text-green-700">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        Hadir
                                    </span>
                                @elseif($r->status_kehadiran === 'tidak')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-red-50 text-red-700">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        Tidak Hadir
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">
                                        <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                        Belum Diabsen
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $r->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center"> <!-- ⭐ Diubah dari 5 ke 6 -->
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <p class="text-base text-gray-500">
                                    @if($search || $filterStatus)
                                        Tidak ada riwayat absen yang cocok dengan filter.
                                    @else
                                        Anda belum memiliki riwayat absensi.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE: Card List --}}
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($riwayat as $index => $r)
                <div class="p-4 hover:bg-gray-50 transition">
                    <div class="flex items-start gap-3 mb-2">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-sm font-bold text-gray-600">
                            {{ $riwayat->firstItem() + $index }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-base font-semibold text-gray-800">{{ $r->sesi->nama_sesi }}</div>
                            <div class="text-sm text-gray-500 mt-0.5 flex items-center gap-2 flex-wrap">
                                <span>📅 {{ $r->sesi->tanggal->translatedFormat('d M Y') }}</span>
                                @if($r->waktu_absen)
                                    <span class="text-gray-400">•</span>
                                    <span class="font-mono text-gray-700">🕒 {{ \Carbon\Carbon::parse($r->waktu_absen)->format('H:i') }}</span>
                                @endif
                            </div>
                            @if($r->sesi->lokasi)
                                <div class="text-sm text-gray-500 mt-0.5">📍 {{ $r->sesi->lokasi }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pl-11 flex-wrap">
                        @if($r->status_kehadiran === 'hadir')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-green-50 text-green-700">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                Hadir
                            </span>
                        @elseif($r->status_kehadiran === 'tidak')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-red-50 text-red-700">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Tidak Hadir
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-600">
                                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                Belum Diabsen
                            </span>
                        @endif
                        
                        @if($r->sesi->is_default)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                Default
                            </span>
                        @endif
                    </div>

                    @if($r->keterangan)
                        <div class="mt-2 ml-11 text-sm text-gray-600 bg-gray-50 rounded-lg p-2.5">
                            <span class="font-medium text-gray-700">Keterangan: </span>{{ $r->keterangan }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <p class="text-base text-gray-500">
                        @if($search || $filterStatus)
                            Tidak ada riwayat absen yang cocok dengan filter.
                        @else
                            Anda belum memiliki riwayat absensi.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($riwayat->hasPages())
        <div class="mt-6">
            {{ $riwayat->links() }}
        </div>
    @endif

    <!-- Total Info -->
    <div class="mt-4 text-center text-sm text-gray-500">
        Menampilkan <strong class="text-gray-700">{{ $riwayat->firstItem() ?? 0 }}</strong>-<strong class="text-gray-700">{{ $riwayat->lastItem() ?? 0 }}</strong> dari <strong class="text-gray-700">{{ $riwayat->total() }}</strong> sesi absensi.
    </div>
</div>
@endsection