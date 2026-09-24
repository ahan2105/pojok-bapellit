@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Kelola Absensi')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-12 py-8">

    <div class="mb-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Kelola Absensi</h1>
            <p class="text-base text-gray-600 mt-2">Daftar semua sesi absensi — rapat, event, dll.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap self-start lg:self-center">
            <a href="{{ route('admin.pegawai.index') }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-white text-gray-700 text-base font-semibold rounded-xl border border-gray-300 hover:bg-gray-50 hover:border-indigo-300 hover:text-indigo-600 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Kelola Pegawai
            </a>

            <!-- Tombol Hapus Terpilih (Hidden by default) -->
            <button type="button"
                    id="btn-bulk-delete"
                    onclick="confirmBulkDelete()"
                    class="hidden inline-flex items-center gap-2 px-5 py-3 bg-red-600 text-white text-base font-semibold rounded-xl hover:bg-red-700 transition shadow-sm animate-pulse">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus Terpilih (<span id="selected-count">0</span>)
            </button>

            <a href="{{ route('admin.absensi.create') }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 text-white text-base font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Sesi Baru
            </a>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('admin.absensi.index', array_merge(request()->except('page'), ['filter' => 'hari_ini'])) }}"
               class="px-5 py-2.5 rounded-xl text-base font-semibold transition
                      {{ $filter === 'hari_ini'
                          ? 'bg-blue-600 text-white shadow-sm'
                          : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                Hari Ini
            </a>

            <a href="{{ route('admin.absensi.index', array_merge(request()->except('page'), ['filter' => 'semua'])) }}"
               class="px-5 py-2.5 rounded-xl text-base font-semibold transition
                      {{ $filter === 'semua'
                          ? 'bg-blue-600 text-white shadow-sm'
                          : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                Semua
            </a>
        </div>

        <form method="GET"
              action="{{ route('admin.absensi.index') }}"
              class="flex-1 sm:max-w-md sm:ml-auto">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <div class="relative">
                <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama sesi..."
                       class="w-full pl-11 pr-10 py-2.5 border border-gray-300 rounded-xl text-base bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none shadow-sm">

                @if($search)
                    <a href="{{ route('admin.absensi.index', ['filter' => $filter]) }}"
                       class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center transition"
                       title="Hapus pencarian">
                        <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Bulk Action Toolbar (Checkbox Master) -->
    @if($sesiList->count() > 0)
    <div class="mb-4 flex items-center justify-between bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center gap-3">
            <input type="checkbox" 
                   id="check-all" 
                   class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 cursor-pointer"
                   onchange="toggleCheckAll(this)">
            <label for="check-all" class="text-sm font-semibold text-gray-700 cursor-pointer select-none">
                Pilih Semua Sesi di Halaman Ini
            </label>
        </div>
        <span class="text-xs text-gray-500">
            Centang sesi yang ingin dihapus, lalu klik tombol <strong>Hapus Terpilih</strong> di atas.
        </span>
    </div>
    @endif

    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-5 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">

            <div class="flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-base font-bold text-emerald-800">Rekap & Export Excel</h3>
                </div>
                <p class="text-sm text-emerald-700">
                    Pilih <strong>rentang tanggal</strong> lalu klik <strong>Export Rekap</strong> untuk download data dalam format Excel.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-2 flex-shrink-0">
                <div>
                    <label class="block text-xs font-semibold text-emerald-700 mb-1.5">Dari Tanggal</label>
                    <input type="date"
                           id="tanggal-dari"
                           value="{{ request('tanggal_dari', now()->startOfMonth()->format('Y-m-d')) }}"
                           class="px-3 py-2.5 border border-emerald-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-emerald-700 mb-1.5">Ke Tanggal</label>
                    <input type="date"
                           id="tanggal-ke"
                           value="{{ request('tanggal_ke', now()->format('Y-m-d')) }}"
                           class="px-3 py-2.5 border border-emerald-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none">
                </div>

                <a href="#"
                   id="btn-export-rekap"
                   onclick="exportRekap(event)"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition shadow-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Rekap
                </a>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-emerald-200/50 flex items-center gap-2 text-sm text-emerald-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Rentang aktif: <strong id="periode-label">
                {{ \Carbon\Carbon::parse(request('tanggal_dari', now()->startOfMonth()))->translatedFormat('d M Y') }} —
                {{ \Carbon\Carbon::parse(request('tanggal_ke', now()))->translatedFormat('d M Y') }}
            </strong></span>
        </div>
    </div>

    @if(isset($sesiRekap) && $sesiRekap->count() > 0)
        @php
            $totalHadirPreview  = $sesiRekap->sum(fn($s) => $s->details->where('status_kehadiran', 'hadir')->count());
            $totalTidakPreview  = $sesiRekap->sum(fn($s) => $s->details->where('status_kehadiran', 'tidak')->count());
            $totalSemuaDetail   = $sesiRekap->sum(fn($s) => $s->details->count());
            $totalBelumPreview  = max(0, $totalSemuaDetail - $totalHadirPreview - $totalTidakPreview);
        @endphp

        <div class="mb-8 bg-white rounded-2xl shadow-sm border border-emerald-200 overflow-hidden">

            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-5 py-4 border-b border-emerald-200">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-emerald-900">
                                Preview Sesi yang Akan Di-rekap
                            </h3>
                            <p class="text-xs text-emerald-700">
                                {{ $sesiRekap->count() }} sesi ditemukan dalam rentang ini
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('admin.absensi.index', ['filter' => $filter]) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-50 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tutup Preview
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-3 divide-x divide-gray-100 border-b border-gray-100">
                <div class="p-4 text-center">
                    <div class="text-2xl font-extrabold text-green-600">{{ $totalHadirPreview }}</div>
                    <div class="text-xs text-gray-500 font-semibold uppercase tracking-wide mt-1">Total Hadir</div>
                </div>
                <div class="p-4 text-center">
                    <div class="text-2xl font-extrabold text-red-600">{{ $totalTidakPreview }}</div>
                    <div class="text-xs text-gray-500 font-semibold uppercase tracking-wide mt-1">Total Tidak Hadir</div>
                </div>
                <div class="p-4 text-center">
                    <div class="text-2xl font-extrabold text-amber-600">{{ $totalBelumPreview }}</div>
                    <div class="text-xs text-gray-500 font-semibold uppercase tracking-wide mt-1">Total Belum Absen</div>
                </div>
            </div>

            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @foreach($sesiRekap as $sr)
                    @php
                        $hadir  = $sr->details->where('status_kehadiran', 'hadir')->count();
                        $tidak  = $sr->details->where('status_kehadiran', 'tidak')->count();
                        $total  = $sr->details->count();
                        $belum  = max(0, $total - $hadir - $tidak);
                    @endphp
                    <div class="px-5 py-3.5 hover:bg-emerald-50/30 transition">
                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex flex-col items-center justify-center text-white shadow-sm">
                                    <span class="text-[10px] font-bold uppercase leading-none opacity-90">
                                        {{ $sr->tanggal->translatedFormat('M') }}
                                    </span>
                                    <span class="text-xl font-extrabold leading-tight">
                                        {{ $sr->tanggal->format('d') }}
                                    </span>
                                    <span class="text-[9px] font-semibold leading-none opacity-80">
                                        {{ $sr->tanggal->format('Y') }}
                                    </span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-bold text-gray-900 truncate">
                                        {{ $sr->nama_sesi }}
                                    </h4>
                                    <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 flex-wrap">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $sr->tanggal->translatedFormat('l') }}
                                        </span>
                                        @if($sr->lokasi)
                                            <span class="flex items-center gap-1 truncate">
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                </svg>
                                                <span class="truncate">{{ $sr->lokasi }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0 flex-wrap">
                                <div class="flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 border border-green-200 rounded-lg">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    <span class="text-xs font-bold text-green-700">{{ $hadir }} Hadir</span>
                                </div>
                                <div class="flex items-center gap-1.5 px-2.5 py-1.5 bg-red-50 border border-red-200 rounded-lg">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    <span class="text-xs font-bold text-red-700">{{ $tidak }} Tidak</span>
                                </div>
                                <div class="flex items-center gap-1.5 px-2.5 py-1.5 bg-amber-50 border border-amber-200 rounded-lg">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    <span class="text-xs font-bold text-amber-700">{{ $belum }} Belum</span>
                                </div>

                                <a href="{{ route('admin.absensi.show', $sr->id) }}"
                                   class="p-2 text-emerald-600 hover:bg-emerald-100 rounded-lg transition"
                                   title="Buka Sesi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif(request()->filled('tanggal_dari') && request()->filled('tanggal_ke'))
        <div class="mb-8 p-5 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="text-sm text-amber-700">
                <p class="font-bold mb-0.5">Tidak ada sesi dalam rentang ini</p>
                <p>Tidak ditemukan sesi absensi antara
                   <strong>{{ \Carbon\Carbon::parse(request('tanggal_dari'))->translatedFormat('d F Y') }}</strong> —
                   <strong>{{ \Carbon\Carbon::parse(request('tanggal_ke'))->translatedFormat('d F Y') }}</strong>.
                   Coba ubah rentang tanggal.
                </p>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-base text-green-700 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-base text-red-700 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Daftar Sesi Absensi
        </h2>
        <span class="text-sm text-gray-500">
            Total: <strong class="text-gray-700">{{ $sesiList->total() }}</strong> sesi
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($sesiList as $s)
            @php
                $totalPeserta = $s->details_count ?? 0;
                $belumAbsen   = max(0, $totalPeserta - $s->jumlah_hadir - $s->jumlah_tidak);
                $persenHadir  = $totalPeserta > 0 ? round(($s->jumlah_hadir / $totalPeserta) * 100) : 0;
            @endphp

            <div class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-xl hover:border-indigo-300 hover:-translate-y-0.5 transition-all duration-300 flex flex-col relative">

                <!-- Checkbox Selector -->
                <div class="absolute top-3 left-3 z-10">
                    <input type="checkbox" 
                           class="sesi-checkbox w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 cursor-pointer bg-white/90 backdrop-blur-sm" 
                           value="{{ $s->id }}"
                           data-name="{{ addslashes($s->nama_sesi) }}"
                           onchange="updateSelectedCount()">
                </div>

                <div class="h-1.5 bg-gradient-to-r
                            {{ $s->is_locked
                                ? 'from-red-400 via-rose-500 to-red-500'
                                : ($s->is_default
                                    ? 'from-indigo-400 via-violet-500 to-purple-500'
                                    : 'from-blue-400 via-indigo-500 to-violet-500') }}">
                </div>

                <div class="p-5 flex-1 flex flex-col">

                    <div class="flex items-start justify-between gap-3 mb-4 mt-2">

                        <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 flex flex-col items-center justify-center text-white shadow-lg group-hover:scale-105 transition-transform relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                            <span class="text-[10px] font-bold uppercase leading-none opacity-90 relative z-10">
                                {{ $s->tanggal->translatedFormat('M') }}
                            </span>
                            <span class="text-2xl font-extrabold leading-tight relative z-10">
                                {{ $s->tanggal->format('d') }}
                            </span>
                            <span class="text-[9px] font-semibold leading-none opacity-70 relative z-10">
                                {{ $s->tanggal->translatedFormat('D') }}
                            </span>
                        </div>

                        <div class="flex flex-col items-end gap-1.5">
                            @if($s->is_locked)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-200">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Terkunci
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            @endif

                            @if($s->is_default)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase tracking-wide">
                                    Default
                                </span>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-base font-bold text-gray-900 leading-snug mb-3 line-clamp-2 min-h-[2.75rem]">
                        {{ $s->nama_sesi }}
                    </h3>

                    <div class="space-y-2 mb-4 text-xs text-gray-500">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">{{ $s->tanggal->translatedFormat('l, d F Y') }}</span>
                        </div>
                        @if($s->lokasi)
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="truncate font-medium">{{ $s->lokasi }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4 p-3 rounded-xl bg-gradient-to-br from-slate-50 to-gray-50 border border-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wide">Kehadiran</span>
                            <span class="text-xs font-extrabold
                                {{ $persenHadir >= 70 ? 'text-green-600' : ($persenHadir >= 40 ? 'text-amber-600' : 'text-red-600') }}">
                                {{ $persenHadir }}%
                            </span>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500
                                {{ $persenHadir >= 70
                                    ? 'bg-gradient-to-r from-green-400 to-emerald-500'
                                    : ($persenHadir >= 40
                                        ? 'bg-gradient-to-r from-amber-400 to-orange-500'
                                        : 'bg-gradient-to-r from-red-400 to-rose-500') }}"
                                 style="width: {{ $persenHadir }}%">
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-2 text-[10px]">
                            <span class="font-semibold text-green-600">{{ $s->jumlah_hadir }} hadir</span>
                            <span class="font-semibold text-red-600">{{ $s->jumlah_tidak }} tidak</span>
                            <span class="font-semibold text-amber-600">{{ $belumAbsen }} belum</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-auto">
                        <a href="{{ route('admin.absensi.show', $s->id) }}"
                           class="group/btn flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Buka
                            <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>

                        <!-- Tombol Hapus Individual (Sekarang bisa untuk Default juga) -->
                        <form action="{{ route('admin.absensi.destroy', $s->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    onclick="confirmDelete({{ $s->id }}, '{{ addslashes($s->nama_sesi) }}')"
                                    class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 hover:scale-105 transition border border-red-100"
                                    title="Hapus sesi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
                <div class="w-20 h-20 mx-auto mb-5 rounded-full bg-gray-50 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-700 mb-2">
                    @if($search)
                        Tidak Ada Sesi dengan Nama "{{ $search }}"
                    @elseif($filter === 'hari_ini')
                        Belum Ada Sesi Absensi Hari Ini
                    @else
                        Belum Ada Sesi Absensi
                    @endif
                </h3>
                <p class="text-base text-gray-500 mb-6">
                    @if($search)
                        Coba kata kunci lain atau <a href="{{ route('admin.absensi.index', ['filter' => $filter]) }}" class="text-indigo-600 hover:underline font-semibold">hapus pencarian</a>.
                    @else
                        Mulai dengan membuat sesi absensi baru.
                    @endif
                </p>
                @if(!$search)
                    <a href="{{ route('admin.absensi.create') }}"
                       class="inline-flex items-center gap-2 px-7 py-3 bg-indigo-600 text-white text-base font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Sesi Baru
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    @if($sesiList->hasPages())
        <div class="mt-8">
            {{ $sesiList->links() }}
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- Logic Bulk Delete ---
    let selectedIds = new Set();

    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.sesi-checkbox:checked');
        selectedIds.clear();
        
        checkboxes.forEach(cb => {
            selectedIds.add(cb.value);
        });

        const count = selectedIds.size;
        const btn = document.getElementById('btn-bulk-delete');
        const countSpan = document.getElementById('selected-count');
        
        countSpan.textContent = count;
        
        if (count > 0) {
            btn.classList.remove('hidden');
        } else {
            btn.classList.add('hidden');
        }
    }

    function toggleCheckAll(source) {
        const checkboxes = document.querySelectorAll('.sesi-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = source.checked;
        });
        updateSelectedCount();
    }

    function confirmBulkDelete() {
        if (selectedIds.size === 0) return;

        Swal.fire({
            title: 'Hapus Sesi Terpilih?',
            html: `Anda akan menghapus <strong>${selectedIds.size} sesi</strong> secara permanen.<br><span class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Convert Set to Array
                const ids = Array.from(selectedIds);
                
                // Show loading
                Swal.fire({
                    title: 'Sedang Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request to a new bulk delete route or loop individual deletes
                // For simplicity and safety, let's assume we have a bulk route or we handle it via standard form submission logic
                // Since standard Laravel doesn't have bulk delete by default in this context, 
                // we will redirect to a custom endpoint or use fetch.
                
                // Option: Use fetch to call a bulk delete endpoint
                fetch('{{ route("admin.absensi.bulk-destroy") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Sesi terpilih telah dihapus.',
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Gagal menghapus');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat menghapus.',
                        confirmButtonColor: '#dc2626'
                    });
                });
            }
        });
    }

    // --- Existing Logic ---
    document.addEventListener('DOMContentLoaded', function() {
        const dariInput = document.getElementById('tanggal-dari');
        const keInput = document.getElementById('tanggal-ke');
        const label = document.getElementById('periode-label');

        function updateLabel() {
            const dari = dariInput.value;
            const ke = keInput.value;
            if (dari && ke) {
                const d1 = new Date(dari);
                const d2 = new Date(ke);
                const opts = { day: 'numeric', month: 'short', year: 'numeric' };
                label.textContent = d1.toLocaleDateString('id-ID', opts) + ' — ' + d2.toLocaleDateString('id-ID', opts);
            }
        }

        function applyDateRange() {
            const dari = dariInput.value;
            const ke = keInput.value;
            if (!dari || !ke) return;
            if (new Date(dari) > new Date(ke)) return;

            const url = new URL(window.location.href);
            url.searchParams.set('tanggal_dari', dari);
            url.searchParams.set('tanggal_ke', ke);
            window.location.href = url.toString();
        }

        dariInput.addEventListener('change', function() {
            updateLabel();
            applyDateRange();
        });

        keInput.addEventListener('change', function() {
            updateLabel();
            applyDateRange();
        });
    });

    function exportRekap(event) {
        event.preventDefault();

        const dari = document.getElementById('tanggal-dari').value;
        const ke = document.getElementById('tanggal-ke').value;

        if (!dari || !ke) {
            Swal.fire({
                icon: 'warning',
                title: 'Tanggal Belum Lengkap',
                text: 'Silakan pilih tanggal dari dan ke tanggal.',
                confirmButtonColor: '#10b981'
            });
            return;
        }

        if (new Date(dari) > new Date(ke)) {
            Swal.fire({
                icon: 'error',
                title: 'Tanggal Tidak Valid',
                text: 'Tanggal "dari" tidak boleh lebih besar dari "ke".',
                confirmButtonColor: '#10b981'
            });
            return;
        }

        const d1 = new Date(dari).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const d2 = new Date(ke).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

        Swal.fire({
            title: 'Export Rekap Excel?',
            html: `
                <p class="text-base text-gray-700 mb-2">Data yang akan di-export:</p>
                <p class="text-lg font-bold text-emerald-600">${d1} — ${d2}</p>
                <p class="text-sm text-gray-500 mt-3">File Excel akan otomatis ter-download.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Export!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `{{ route('admin.absensi.export-rekap') }}?tanggal_dari=${dari}&tanggal_ke=${ke}`;
            }
        });
    }

    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Sesi?',
            html: `Sesi <strong>"${nama}"</strong> akan dihapus permanen.<br><span class="text-sm text-gray-500">Semua data absensi di dalamnya juga akan terhapus.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/absensi/${id}`;
                form.innerHTML = '@csrf' + '<input type="hidden" name="_method" value="DELETE">';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection