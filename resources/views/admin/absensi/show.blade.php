@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Absensi - ' . $sesi->nama_sesi)

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-12 py-8">

    <!-- Tombol HOME (Kembali) -->
    <div class="mb-8">
        <a href="{{ route('admin.absensi.index') }}" 
           class="inline-flex items-center gap-2.5 px-5 py-2.5 bg-white text-gray-700 text-base font-medium rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 hover:border-indigo-300 hover:text-indigo-600 transition-all group">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            HOME
        </a>
    </div>

    <!-- Flash Message -->
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

    <!-- ===== JUDUL + TOMBOL AKSI UTAMA ===== -->
    <div class="mb-6 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">
        
        <!-- KIRI: Judul -->
        <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                Absensi Peserta - {{ $sesi->nama_sesi }}
            </h1>
            <p class="text-base text-gray-600 mt-2 flex items-center flex-wrap gap-x-2 gap-y-1">
                <span>📅 {{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d F Y') }}</span>
                @if($sesi->lokasi)
                    <span>·  {{ $sesi->lokasi }}</span>
                @endif
                @if($sesi->is_default)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                        Default
                    </span>
                @endif
                @if($sesi->is_locked)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                         Terkunci
                    </span>
                @endif
            </p>
        </div>

        <!-- KANAN: Tombol Aksi Utama -->
        <div class="flex items-center gap-2 flex-wrap justify-end flex-shrink-0">
            @if(!$sesi->is_locked)
                <button type="button" 
                        onclick="confirmLock()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Kunci Absen
                </button>

                <button type="submit" form="form-absensi"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Absen
                </button>
            @endif

            <a href="{{ route('admin.absensi.export', $sesi->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel
            </a>

            {{-- ⭐ Tombol QR Code — hanya muncul kalau sesi belum dikunci --}}
            @if(!$sesi->is_locked)
                <button type="button" 
                        onclick="openQrModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                    QR Code
                </button>
            @endif
        </div>
    </div>

    <!-- Toolbar: Tab + Search -->
    <div class="bg-white rounded-t-2xl shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row md:items-center gap-4">
        <!-- Search -->
        <form method="GET" action="{{ route('admin.absensi.show', $sesi->id) }}" class="flex-1 md:max-w-md md:ml-auto">
            <div class="relative">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Cari nama peserta..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            </div>
        </form>
    </div>

    <!-- Tabel Absensi -->
    <form id="form-absensi" action="{{ route('admin.absensi.update-kehadiran', $sesi->id) }}" method="POST" onsubmit="rememberAbsensiScroll()">
        @csrf
        <div id="autosave-status" class="hidden px-4 py-2 text-xs font-semibold text-gray-500 bg-gray-50 border-b border-gray-200"></div>
        
        <div class="bg-white rounded-b-2xl shadow-sm border border-t-0 border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-16 text-center">NO</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">NAMA PESERTA</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">ASAL BIDANG</th>
                            
                            <th class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider flex-shrink-0">STATUS KEHADIRAN</span>
                                    
                                    @if(!$sesi->is_locked)
                                        <div class="flex items-center gap-5">
                                            <label class="inline-flex items-center gap-1.5 cursor-pointer group" title="Tandai semua HADIR">
                                                <input type="radio" 
                                                       name="bulk_action" 
                                                       value="hadir"
                                                       id="bulk-hadir"
                                                       onclick="hadirkanSemua()"
                                                       class="w-4 h-4 text-green-600 focus:ring-green-500 cursor-pointer">
                                                <span class="text-sm font-semibold text-green-600 group-hover:text-green-700">
                                                    Hadir Semua
                                                </span>
                                            </label>

                                            <label class="inline-flex items-center gap-1.5 cursor-pointer group" title="Tandai semua TIDAK HADIR">
                                                <input type="radio" 
                                                       name="bulk_action" 
                                                       value="tidak"
                                                       id="bulk-tidak"
                                                       onclick="tidakHadirSemua()"
                                                       class="w-4 h-4 text-red-600 focus:ring-red-500 cursor-pointer">
                                                <span class="text-sm font-semibold text-red-600 group-hover:text-red-700">
                                                    Tidak Hadir Semua
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </th>

                            <!-- KOLOM BARU: WAKTU ABSEN -->
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-48">WAKTU ABSEN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $groupedRaw = collect($peserta)->groupBy(function ($p) {
                                $b = trim(strtoupper($p->bidang ?? ''));
                                $b = preg_replace('/\s+/', ' ', $b);
                                return $b === '' ? 'TANPA BIDANG' : $b;
                            });

                            $kepalaBadan = $groupedRaw->pull('KEPALA BADAN', collect());
                            $tanpaBidang = $groupedRaw->pull('TANPA BIDANG', collect());
                            $bidangLainnya = $groupedRaw->sortKeys();

                            $groupedPeserta = collect();
                            
                            if ($kepalaBadan->isNotEmpty()) {
                                $groupedPeserta->put('KEPALA BADAN', $kepalaBadan);
                            }
                            
                            foreach ($bidangLainnya as $bidang => $users) {
                                $groupedPeserta->put($bidang, $users);
                            }
                            
                            if ($tanpaBidang->isNotEmpty()) {
                                $groupedPeserta->put('TANPA BIDANG', $tanpaBidang);
                            }

                            $globalNo = 1;
                        @endphp

                        @forelse($groupedPeserta as $bidang => $groupUsers)
                            <tr class="bg-gradient-to-r from-blue-50 to-indigo-50 border-y border-blue-100">
                                <td colspan="5" class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                                        <span class="text-sm font-bold text-blue-900 tracking-wide">
                                            @if($bidang === 'TANPA BIDANG')
                                                BELUM MEMILIKI BIDANG
                                            @else
                                                {{ ucwords(strtolower(str_replace('_', ' ', $bidang))) }}
                                            @endif
                                        </span>
                                        <span class="text-xs font-semibold text-blue-700 bg-white/70 px-2.5 py-0.5 rounded-full border border-blue-100">
                                            {{ $groupUsers->count() }} Orang
                                        </span>
                                    </div>
                                </td>
                            </tr>

                            @foreach($groupUsers as $p)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-5 text-base text-gray-600 font-medium align-top text-center">{{ $globalNo++ }}</td>
                                    
                                    <td class="px-6 py-5 align-top">
                                        <div class="text-base font-semibold text-gray-800">{{ $p->name }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-5 text-base text-gray-600 align-top">{{ $p->bidang ?? '-' }}</td>
                                    
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-3">
                                            
                                            <div class="flex items-center gap-6">
                                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" 
                                                           name="kehadiran[{{ $p->id }}]" 
                                                           value="hadir"
                                                           id="hadir-{{ $p->id }}"
                                                           {{ $p->status_kehadiran === 'hadir' ? 'checked' : '' }}
                                                           onchange="toggleKeterangan({{ $p->id }})"
                                                           class="w-4 h-4 text-green-600 focus:ring-green-500 cursor-pointer"
                                                           @if($sesi->is_locked) disabled @endif>
                                                    <span class="text-base font-medium {{ $p->status_kehadiran === 'hadir' ? 'text-green-600' : 'text-gray-600' }}">
                                                        Hadir
                                                    </span>
                                                </label>

                                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" 
                                                           name="kehadiran[{{ $p->id }}]" 
                                                           value="tidak"
                                                           id="tidak-{{ $p->id }}"
                                                           {{ $p->status_kehadiran === 'tidak' ? 'checked' : '' }}
                                                           onchange="toggleKeterangan({{ $p->id }})"
                                                           class="w-4 h-4 text-red-600 focus:ring-red-500 cursor-pointer"
                                                           @if($sesi->is_locked) disabled @endif>
                                                    <span class="text-base font-medium {{ $p->status_kehadiran === 'tidak' ? 'text-red-600' : 'text-gray-600' }}">
                                                        Tidak
                                                    </span>
                                                </label>
                                            </div>

                                            <input type="text" 
                                                   name="keterangan[{{ $p->id }}]" 
                                                   id="keterangan-{{ $p->id }}"
                                                   value="{{ $p->keterangan }}"
                                                   placeholder="Keterangan (alasan tidak hadir)..."
                                                   {{ $p->status_kehadiran === 'tidak' && !$sesi->is_locked ? '' : 'disabled' }}
                                                   class="w-full max-w-md px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none disabled:bg-gray-100 disabled:cursor-not-allowed disabled:text-gray-400">
                                        </div>
                                    </td>

                                    <!-- KOLOM BARU: INPUT WAKTU ABSEN -->
                                    <td class="px-6 py-5 align-top">
                                        <input type="time" 
                                               name="waktu_absen[{{ $p->id }}]" 
                                               id="waktu-{{ $p->id }}"
                                             value="{{ isset($p->waktu_absen) && $p->waktu_absen ? \Carbon\Carbon::parse($p->waktu_absen)->format('H:i') : '' }}"
                                               {{ !$sesi->is_locked ? '' : 'disabled' }}
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none disabled:bg-gray-100 disabled:cursor-not-allowed disabled:text-gray-400">
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-base text-gray-500">
                                        @if($search)
                                            Tidak ada peserta dengan nama "{{ $search }}".
                                        @else
                                            Belum ada peserta aktif yang terdaftar.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <!-- Info Terkunci -->
    @if($sesi->is_locked)
        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <p class="text-base text-red-700">
                <strong>Absensi ini sudah dikunci.</strong> Data tidak bisa diubah lagi.
            </p>
        </div>
    @endif
</div>

<style>
    /* Responsif khusus HP; tampilan desktop tetap menggunakan class yang ada. */
    @media (max-width: 767px) {
        #form-absensi {
            display: block;
            width: 100%;
            max-width: 100vw;
            margin-left: -0.5rem;
            margin-right: -0.5rem;
            overflow: hidden;
        }

        #form-absensi > .bg-white {
            display: block;
            width: 100%;
            max-width: 100%;
            overflow: hidden;
        }

        #form-absensi .overflow-x-auto {
            display: block;
            width: 100%;
            max-width: 100%;
            overflow-x: scroll !important;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            touch-action: pan-x;
            overscroll-behavior-x: contain;
            scrollbar-width: auto;
            scrollbar-color: #64748b #e2e8f0;
        }

        #form-absensi table {
            display: table;
            width: 920px !important;
            min-width: 920px !important;
            max-width: none !important;
            table-layout: fixed;
        }

        #form-absensi th,
        #form-absensi td {
            padding: 0.75rem 0.875rem;
            white-space: normal;
        }

        #form-absensi th:nth-child(1),
        #form-absensi td:nth-child(1) {
            width: 44px;
        }

        #form-absensi th:nth-child(2) {
            min-width: 150px;
        }

        #form-absensi th:nth-child(3) {
            min-width: 130px;
        }

        #form-absensi th:nth-child(4) {
            min-width: 260px;
        }

        #form-absensi th:nth-child(5),
        #form-absensi td:nth-child(5) {
            min-width: 120px;
        }

        #form-absensi td:nth-child(4) .flex.items-center.gap-6 {
            gap: 1rem;
        }

        #form-absensi td:nth-child(4) input[type="text"] {
            max-width: 100%;
        }

        #form-absensi td:nth-child(5) input[type="time"] {
            width: 7rem;
        }

        /* Toolbar judul dan tombol utama */
        .max-w-screen-2xl > .mb-6.flex {
            gap: 1rem;
        }

        .max-w-screen-2xl > .mb-6.flex > .flex.items-center.gap-2 {
            width: 100%;
            justify-content: stretch;
        }

        .max-w-screen-2xl > .mb-6.flex > .flex.items-center.gap-2 > * {
            flex: 1 1 auto;
            justify-content: center;
            min-width: 0;
        }

        .max-w-screen-2xl > .mb-6.flex > .flex.items-center.gap-2 > a:first-of-type {
            flex: 1 1 100%;
        }

        /* Toolbar status bulk agar tidak terlalu padat */
        #form-absensi th:nth-child(4) > div {
            align-items: flex-start;
            flex-direction: column;
            gap: 0.75rem;
        }

        #form-absensi th:nth-child(4) > div > div {
            gap: 1rem;
        }
    }
</style>

{{-- ⭐ Include Modal QR — hanya kalau sesi belum dikunci --}}
@if(!$sesi->is_locked)
    @include('admin.absensi.partials.qr-modal')
@endif

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let autosaveTimer = null;
    let autosaveRequest = null;

    function setAutosaveStatus(message, isError = false) {
        const status = document.getElementById('autosave-status');
        if (!status) return;
        status.textContent = message;
        status.className = `px-4 py-2 text-xs font-semibold border-b ${isError ? 'text-red-600 bg-red-50 border-red-200' : 'text-gray-500 bg-gray-50 border-gray-200'}`;
        status.classList.remove('hidden');
    }

    function autosaveAbsensi() {
        const form = document.getElementById('form-absensi');
        if (!form) return;

        if (autosaveRequest) {
            autosaveRequest.abort();
        }

        setAutosaveStatus('Menyimpan perubahan...');
        autosaveRequest = new AbortController();

        autosaveRequest.promise = fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            signal: autosaveRequest.signal,
        }).then(response => {
            if (!response.ok) throw new Error('Gagal menyimpan');
            setAutosaveStatus('Perubahan tersimpan otomatis.');
            setTimeout(() => document.getElementById('autosave-status')?.classList.add('hidden'), 1800);
        }).catch(error => {
            if (error.name !== 'AbortError') {
                setAutosaveStatus('Perubahan belum tersimpan. Gunakan tombol Simpan Absen.', true);
            }
        });
    }

    function scheduleAutosave() {
        clearTimeout(autosaveTimer);
        autosaveTimer = setTimeout(autosaveAbsensi, 350);
    }

    // ===== Toggle keterangan & JAM OTOMATIS =====
    function toggleKeterangan(userId) {
        const radios = document.querySelectorAll(`input[name="kehadiran[${userId}]"]`);
        const keterangan = document.getElementById(`keterangan-${userId}`);
        const waktuInput = document.getElementById(`waktu-${userId}`);

        let selected = null;
        radios.forEach(radio => {
            if (radio.checked) {
                selected = radio.value;
            }
        });

        if (selected === 'tidak') {
            keterangan.disabled = false;
            if (waktuInput) {
                waktuInput.value = '';
            }
            scheduleAutosave();
            return;
        }

        if (selected === 'hadir') {
            keterangan.disabled = true;
            keterangan.value = '';

            if (waktuInput && !waktuInput.value) {
                const now = new Date();
                waktuInput.value = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
            }
            scheduleAutosave();
            return;
        }

        if (waktuInput) {
            waktuInput.value = '';
        }

        scheduleAutosave();
    }

    // ===== Bulk: Hadirkan semua =====
    function hadirkanSemua() {
        const pesertaIds = @json($peserta->pluck('id')->toArray());

        Swal.fire({
            title: 'Hadirkan Semua Peserta?',
            html: `Semua <strong>${pesertaIds.length} peserta</strong> akan ditandai <strong class="text-green-600">Hadir</strong>.<br><span class="text-sm text-gray-500">Jam absen akan diisi otomatis dengan waktu saat ini.</span>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hadirkan Semua',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const currentTime = `${hours}:${minutes}`;

                pesertaIds.forEach(id => {
                    const radioHadir = document.getElementById(`hadir-${id}`);
                    const radioTidak = document.getElementById(`tidak-${id}`);
                    const keterangan = document.getElementById(`keterangan-${id}`);
                    const waktuInput = document.getElementById(`waktu-${id}`);

                    if (radioHadir) {
                        radioHadir.checked = true;
                        radioHadir.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    if (keterangan) {
                        keterangan.disabled = true;
                        keterangan.value = '';
                    }
                    if (waktuInput && !waktuInput.value) {
                        waktuInput.value = currentTime;
                    }
                    if (radioTidak) radioTidak.checked = false;
                });

                scheduleAutosave();

                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Semua peserta telah ditandai Hadir. Klik "Simpan Absen" untuk menyimpan.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                document.getElementById('bulk-hadir').checked = false;
            }
        });
    }

    // ===== Bulk: Tidak hadir semua =====
    function tidakHadirSemua() {
        const pesertaIds = @json($peserta->pluck('id')->toArray());

        Swal.fire({
            title: 'Tandai Semua Tidak Hadir?',
            html: `Semua <strong>${pesertaIds.length} peserta</strong> akan ditandai <strong class="text-red-600">Tidak Hadir</strong>.<br><span class="text-sm text-gray-500">Jangan lupa isi keterangan untuk masing-masing peserta.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Tandai Tidak Hadir',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                pesertaIds.forEach(id => {
                    const radioHadir = document.getElementById(`hadir-${id}`);
                    const radioTidak = document.getElementById(`tidak-${id}`);
                    const keterangan = document.getElementById(`keterangan-${id}`);
                    const waktuInput = document.getElementById(`waktu-${id}`);

                    if (radioTidak) {
                        radioTidak.checked = true;
                        radioTidak.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    if (keterangan) {
                        keterangan.disabled = false;
                    }
                    if (waktuInput) {
                        waktuInput.value = '';
                    }
                    if (radioHadir) radioHadir.checked = false;
                });

                scheduleAutosave();

                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Semua peserta ditandai Tidak Hadir dan disimpan otomatis.',
                    icon: 'success',
                    timer: 2500,
                    showConfirmButton: false
                });
            } else {
                document.getElementById('bulk-tidak').checked = false;
            }
        });
    }

    // ===== Konfirmasi kunci =====
    function confirmLock() {
        Swal.fire({
            title: 'Kunci Absensi?',
            html: `Setelah dikunci, data absensi <strong>{{ $sesi->nama_sesi }}</strong> tidak bisa diubah lagi.<br><span class="text-sm text-gray-500">QR Code juga tidak akan bisa dipakai lagi.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Kunci!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.absensi.lock", $sesi->id) }}';
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Cegah browser melakukan restorasi scroll kedua yang menyebabkan halaman sempat meloncat.
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

    // Pertahankan posisi scroll setelah simpan absensi.
    function rememberAbsensiScroll() {
        sessionStorage.setItem('absensi-scroll-position', String(window.scrollY));
    }

    function restoreAbsensiScroll() {
        const savedScrollPosition = sessionStorage.getItem('absensi-scroll-position');

        if (savedScrollPosition === null) {
            return;
        }

        const restore = () => {
            window.scrollTo(0, Number(savedScrollPosition));
        };

        restore();
        window.requestAnimationFrame(restore);
        setTimeout(restore, 50);
        sessionStorage.removeItem('absensi-scroll-position');
    }

    restoreAbsensiScroll();

    document.addEventListener('DOMContentLoaded', function() {
        restoreAbsensiScroll();

        const bulkHadir = document.getElementById('bulk-hadir');
        const bulkTidak = document.getElementById('bulk-tidak');
        
        if (bulkHadir) {
            bulkHadir.addEventListener('click', function() {
                setTimeout(() => { this.checked = false; }, 100);
            });
        }
        
        if (bulkTidak) {
            bulkTidak.addEventListener('click', function() {
                setTimeout(() => { this.checked = false; }, 100);
            });
        }
    });
</script>
@endsection