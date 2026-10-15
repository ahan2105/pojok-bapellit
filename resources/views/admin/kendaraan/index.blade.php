@php
    $isAdmin = Auth::user()?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp
@extends($layout)
@section('title', 'Kendaraan Dinas')
@section('content')

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .gradient-bg::before {
        content: '';
        position: absolute;@php
    $isAdmin = Auth::user()?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp
@extends($layout)
@section('title', 'Kendaraan Dinas')
@section('content')

<style>
    /* ===== HERO ===== */
    .kendaraan-hero {
        background: linear-gradient(135deg, #0f2d5e 0%, #1a4a8a 60%, #1e5799 100%);
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
    }
    .kendaraan-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background-image:
            radial-gradient(circle at 80% 20%, rgba(234,179,8,0.18) 0%, transparent 50%),
            radial-gradient(circle at 10% 80%, rgba(255,255,255,0.06) 0%, transparent 40%);
    }
    .kendaraan-hero > * { position: relative; z-index: 1; }

    /* Tombol tambah di hero */
    .kendaraan-hero a.btn-hero-add,
    .kendaraan-hero a.btn-hero-add:link,
    .kendaraan-hero a.btn-hero-add:visited,
    .kendaraan-hero a.btn-hero-add:hover,
    .kendaraan-hero a.btn-hero-add:focus,
    .kendaraan-hero a.btn-hero-add:active {
        background-color: #facc15 !important;
        background-image: linear-gradient(135deg, #facc15 0%, #eab308 100%) !important;
        color: #1e3a8a !important;
        text-decoration: none !important;
        border: none !important;
        box-shadow: 0 10px 20px -5px rgba(250, 204, 21, 0.45) !important;
        transition: all 0.2s ease;
    }
    .kendaraan-hero a.btn-hero-add:hover {
        background-image: linear-gradient(135deg, #fde047 0%, #facc15 100%) !important;
        transform: translateY(-2px);
    }
    .kendaraan-hero a.btn-hero-add svg {
        color: #1e3a8a !important;
        stroke: #1e3a8a !important;
    }

    /* ===== STAT CARDS ===== */
    .stat-card-v2 {
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 24px -10px rgba(15, 45, 94, 0.25);
        transition: transform .18s ease;
    }
    .stat-card-v2:hover { transform: translateY(-3px); }
    .stat-card-v2::after {
        content: '';
        position: absolute;
        right: -20px;
        top: -20px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }
    .stat-total    { background: linear-gradient(135deg, #0f2d5e, #1a4a8a); }
    .stat-urgent   { background: linear-gradient(135deg, #b91c1c, #dc2626); }
    .stat-active   { background: linear-gradient(135deg, #16a34a, #15803d); }
    .stat-unit     { background: linear-gradient(135deg, #1d4ed8, #2563eb); }

    /* ===== CARD UMUM ===== */
    .card-clean {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 12px -6px rgba(15, 45, 94, 0.08);
    }

    /* ===== VEHICLE CARD ===== */
    .vehicle-card {
        transition: all 0.25s ease;
    }
    .vehicle-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px -12px rgba(15, 45, 94, 0.18);
    }

    /* ===== BADGE STATUS ===== */
    .badge-aktif    { background: #dcfce7; color: #15803d; }
    .badge-nonaktif { background: #f1f5f9; color: #475569; }

    /* ===== SEARCH INPUT ===== */
    .search-input {
        transition: all 0.2s ease;
    }
    .search-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }
</style>

<div class="min-h-screen pb-12">

    {{-- ===== HERO ===== --}}
    <div class="kendaraan-hero text-white px-6 sm:px-8 py-6 mb-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center flex-shrink-0 ring-1 ring-white/20">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-semibold tracking-widest text-yellow-400 uppercase leading-none mb-1">MONITORING ASET</p>
                    <h1 class="text-xl sm:text-2xl font-extrabold leading-tight">Kendaraan Dinas</h1>
                    <p class="text-xs sm:text-sm text-blue-200 mt-0.5">Kelola dan pantau pajak kendaraan dengan mudah.</p>
                </div>
            </div>

            @if($isAdmin)
            <a href="{{ route('admin.kendaraan.create') }}"
               class="btn-hero-add inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kendaraan
            </a>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- ===== STATS ===== --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="stat-card-v2 stat-total">
                <p class="text-[10px] font-bold tracking-widest uppercase opacity-90">Total Kendaraan</p>
                <p class="text-3xl font-extrabold mt-2">{{ $kendaraan->count() }}</p>
                <p class="text-xs opacity-75 mt-1">unit terdaftar</p>
            </div>
            <div class="stat-card-v2 stat-urgent">
                <p class="text-[10px] font-bold tracking-widest uppercase opacity-90">Pajak Mendesak</p>
                <p class="text-3xl font-extrabold mt-2">
                    {{ $kendaraan->filter(fn($k) => ($k->pajakTahunanAktif?->sisa_hari ?? 999) <= 7 || ($k->pajakLimaTahunanAktif?->sisa_hari ?? 999) <= 7)->count() }}
                </p>
                <p class="text-xs opacity-75 mt-1">≤ 7 hari lagi</p>
            </div>
            <div class="stat-card-v2 stat-active">
                <p class="text-[10px] font-bold tracking-widest uppercase opacity-90">Aktif</p>
                <p class="text-3xl font-extrabold mt-2">{{ $kendaraan->where('status', 'aktif')->count() }}</p>
                <p class="text-xs opacity-75 mt-1">siap digunakan</p>
            </div>
            <div class="stat-card-v2 stat-unit">
                <p class="text-[10px] font-bold tracking-widest uppercase opacity-90">Unit Kerja</p>
                <p class="text-3xl font-extrabold mt-2">{{ $kendaraan->pluck('unit_kerja')->filter()->unique()->count() }}</p>
                <p class="text-xs opacity-75 mt-1">unit berbeda</p>
            </div>
        </div>

        {{-- ===== LEGEND ===== --}}
        <div class="card-clean p-5 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-bold text-gray-800">Keterangan Status Pajak</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                <div class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-600"></span>
                    <span class="text-xs font-semibold text-red-700">≤ 7 hari</span>
                </div>
                <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                    <span class="text-xs font-semibold text-amber-700">8-30 hari</span>
                </div>
                <div class="flex items-center gap-2 bg-blue-50 border border-blue-200 rounded-lg px-3 py-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <span class="text-xs font-semibold text-blue-700">31-60 hari</span>
                </div>
                <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    <span class="text-xs font-semibold text-emerald-700">Sudah Bayar</span>
                </div>
                <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-600"></span>
                    <span class="text-xs font-semibold text-gray-700">> 60 hari</span>
                </div>
            </div>
        </div>

        {{-- ===== SEARCH & FILTER ===== --}}
        <form method="GET" class="card-clean p-5 mb-6">
            <div class="grid md:grid-cols-4 gap-3">
                <div class="md:col-span-2 relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input name="search" value="{{ request('search') }}" placeholder="Cari plat nomor, pemakai..."
                           class="search-input w-full pl-9 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:outline-none">
                </div>
                <input name="unit_kerja" value="{{ request('unit_kerja') }}" placeholder="Unit kerja"
                       class="search-input px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:outline-none">
                <select name="status"
                        class="search-input px-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:outline-none">
                    <option value="">Semua status</option>
                    @foreach(['aktif','tidak dipakai','rusak'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 mt-3">
                <button class="inline-flex items-center justify-center gap-2 flex-1 px-5 py-2.5 rounded-lg text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('kendaraan.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
            </div>
        </form>

        {{-- ===== VEHICLE CARDS ===== --}}
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6" style="align-items: start;">
            @forelse($kendaraan as $item)
            <div class="vehicle-card card-clean overflow-hidden flex flex-col">

                {{-- Image Header --}}
                <div class="relative h-52 bg-gradient-to-br from-slate-700 via-slate-600 to-slate-800 overflow-hidden flex-shrink-0">
                    @if($item->foto_url)
                        <img src="{{ $item->foto_url }}"
                             class="w-full h-full object-cover transition-transform duration-500"
                             alt="{{ $item->plat_nomor }}">
                        <button type="button"
                                onclick="openPreview('{{ $item->foto_url }}', '{{ $item->plat_nomor }}')"
                                class="absolute inset-0 flex items-end justify-end p-3 bg-black/0 hover:bg-black/20 transition-all group/img">
                            <span class="opacity-0 group-hover/img:opacity-100 bg-black/70 text-white text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1 transition-opacity">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                </svg>
                                Preview
                            </span>
                        </button>
                    @else
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Status badge --}}
                    <div class="absolute top-3 right-3 pointer-events-none">
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold shadow-sm
                            {{ $item->status === 'aktif' ? 'bg-emerald-500 text-white' : 'bg-gray-600 text-white' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-5 flex flex-col flex-1">

                    <a href="{{ route($isAdmin ? 'admin.kendaraan.show' : 'kendaraan.show', $item) }}"
                       class="block mb-4 hover:opacity-80 transition-opacity">
                        <span class="inline-block px-2.5 py-0.5 bg-indigo-50 text-indigo-700 rounded-md text-[10px] font-bold tracking-wider uppercase mb-1.5">
                            Kendaraan Dinas
                        </span>
                        <h2 class="text-xl font-bold text-gray-900 leading-tight">{{ $item->plat_nomor }}</h2>
                        <p class="text-gray-500 text-sm mt-0.5">{{ $item->merk ?: '—' }}</p>
                    </a>

                    {{-- Unit Kerja --}}
                    <div class="bg-slate-50 border border-slate-100 rounded-lg px-4 py-3 mb-4">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Unit Kerja</p>
                        <p class="font-bold text-gray-800 text-sm">{{ $item->unit_kerja ?: 'Belum ditentukan' }}</p>
                    </div>

                    {{-- Pajak badges --}}
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        @foreach([['Pajak Tahunan', $item->pajakTahunanAktif], ['Pajak 5 Tahunan', $item->pajakLimaTahunanAktif]] as [$label, $pajak])
                        <div class="rounded-lg p-3 border border-gray-200 bg-gray-50">
                            <p class="text-[10px] text-gray-500 mb-1.5 font-semibold uppercase tracking-wider">{{ $label }}</p>
                            @if($pajak)
                                @include('kendaraan.partials.badge-pajak', ['pajak' => $pajak])
                            @else
                                <span class="text-xs text-gray-400">Belum ada data</span>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- Action buttons --}}
                    <div class="mt-auto flex gap-2">
                        <a href="{{ route($isAdmin ? 'admin.kendaraan.show' : 'kendaraan.show', $item) }}"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Detail
                        </a>
                        @if($isAdmin)
                        <a href="{{ route('admin.kendaraan.edit', $item) }}"
                           class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <button type="button"
                                onclick="confirmDelete({{ $item->id }}, '{{ $item->plat_nomor }}')"
                                class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-lg text-sm font-bold text-white bg-red-600 hover:bg-red-700 transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.kendaraan.destroy', $item) }}" method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="md:col-span-2 xl:col-span-3 card-clean p-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 mb-4">
                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                    </svg>
                </div>
                <p class="text-base font-bold text-gray-700 mb-1">Belum ada data kendaraan</p>
                <p class="text-sm text-gray-500">Tambahkan kendaraan baru untuk memulai.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ===== IMAGE PREVIEW MODAL ===== --}}
    <div id="image-preview-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/90 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-4xl">
            <button onclick="closePreview()"
                    class="absolute -top-12 right-0 w-10 h-10 flex items-center justify-center rounded-full bg-white/20 text-white hover:bg-red-500 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img id="preview-img" src="" alt="" class="w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl">
            <p id="preview-caption" class="mt-4 text-center text-white font-bold text-lg"></p>
        </div>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ── Image Preview ────────────────────────────────────
    function openPreview(url, caption) {
        document.getElementById('preview-img').src = url;
        document.getElementById('preview-caption').textContent = caption;
        const modal = document.getElementById('image-preview-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closePreview() {
        const modal = document.getElementById('image-preview-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.getElementById('image-preview-modal').addEventListener('click', function(e) {
        if (e.target === this) closePreview();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePreview();
    });

    // ── Delete Confirm ───────────────────────────────────
    function confirmDelete(id, platNomor) {
        Swal.fire({
            title: 'Hapus Kendaraan?',
            html: `Yakin ingin menghapus kendaraan <strong>${platNomor}</strong>?<br><small class="text-gray-500">Semua data pajak terkait juga akan terhapus.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
@endsection
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .vehicle-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .vehicle-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }
    
    .vehicle-card:hover::before {
        left: 100%;
    }
    
    .vehicle-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .badge-status {
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    .search-box {
        background: white;
        border-radius: 50px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }
    
    .search-box:focus-within {
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
        transform: translateY(-2px);
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.3s;
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }
</style>

<div class="min-h-screen pb-12">
    <!-- Hero Section -->
    <div class="gradient-bg text-white py-12 mb-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <div class="flex items-center gap-3 justify-center md:justify-start mb-3">
                        <div class="bg-white/20 backdrop-blur-sm p-3 rounded-2xl">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium opacity-90">MONITORING ASET</p>
                            <h1 class="text-4xl font-bold">Kendaraan Dinas</h1>
                        </div>
                    </div>
                    <p class="text-lg opacity-90">Kelola dan pantau pajak kendaraan dengan mudah</p>
                </div>
                
                @if($isAdmin)
                <a href="{{ route('admin.kendaraan.create') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-purple-600 rounded-full font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Kendaraan</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Stats Cards -->
        <div class="grid md:grid-cols-4 gap-4 mb-8">
            <div class="stat-card">
                <div class="relative z-10">
                    <p class="text-sm opacity-80 mb-1">Total Kendaraan</p>
                    <p class="text-3xl font-bold">{{ $kendaraan->count() }}</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="relative z-10">
                    <p class="text-sm opacity-80 mb-1">Pajak Mendesak</p>
                    <p class="text-3xl font-bold">{{ $kendaraan->filter(fn($k) => ($k->pajakTahunanAktif?->sisa_hari ?? 999) <= 7 || ($k->pajakLimaTahunanAktif?->sisa_hari ?? 999) <= 7)->count() }}</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="relative z-10">
                    <p class="text-sm opacity-80 mb-1">Aktif</p>
                    <p class="text-3xl font-bold">{{ $kendaraan->where('status', 'aktif')->count() }}</p>
                </div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="relative z-10">
                    <p class="text-sm opacity-80 mb-1">Unit Kerja</p>
                    <p class="text-3xl font-bold">{{ $kendaraan->pluck('unit_kerja')->filter()->unique()->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="glass-card rounded-3xl p-6 mb-6 animate-fade-in">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-gradient-to-br from-purple-500 to-pink-500 p-2 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-lg font-bold text-gray-800">Keterangan Status Pajak</p>
            </div>
            <div class="grid md:grid-cols-5 gap-3">
                <div class="flex items-center gap-2 bg-red-50 border-2 border-red-200 rounded-xl px-4 py-2">
                    <span class="w-3 h-3 rounded-full bg-red-600"></span>
                    <span class="text-sm font-semibold text-red-700">≤ 7 hari</span>
                </div>
                <div class="flex items-center gap-2 bg-amber-50 border-2 border-amber-200 rounded-xl px-4 py-2">
                    <span class="w-3 h-3 rounded-full bg-amber-600"></span>
                    <span class="text-sm font-semibold text-amber-700">8-30 hari</span>
                </div>
                <div class="flex items-center gap-2 bg-blue-50 border-2 border-blue-200 rounded-xl px-4 py-2">
                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                    <span class="text-sm font-semibold text-blue-700">31-60 hari</span>
                </div>
                <div class="flex items-center gap-2 bg-emerald-50 border-2 border-emerald-200 rounded-xl px-4 py-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                    <span class="text-sm font-semibold text-emerald-700">Sudah Bayar</span>
                </div>
                <div class="flex items-center gap-2 bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-2">
                    <span class="w-3 h-3 rounded-full bg-gray-600"></span>
                    <span class="text-sm font-semibold text-gray-700">> 60 hari</span>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <form method="GET" class="glass-card rounded-3xl p-6 mb-8">
            <div class="grid md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <div class="search-box">
                        <input name="search" value="{{ request('search') }}" placeholder=" Cari plat nomor, pemakai..." class="w-full px-6 py-4 rounded-full border-0 focus:outline-none focus:ring-0">
                    </div>
                </div>
                <input name="unit_kerja" value="{{ request('unit_kerja') }}" placeholder="Unit kerja" class="px-6 py-4 rounded-2xl border-2 border-gray-200 focus:border-purple-500 focus:ring-0 transition">
                <select name="status" class="px-6 py-4 rounded-2xl border-2 border-gray-200 focus:border-purple-500 focus:ring-0 transition">
                    <option value="">Semua status</option>
                    @foreach(['aktif','tidak dipakai','rusak'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 mt-4">
                <button class="btn-gradient text-white px-8 py-3 rounded-full font-bold flex items-center gap-2 flex-1 justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('kendaraan.index') }}" class="bg-white border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-full font-bold hover:bg-gray-50 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
            </div>
        </form>

        <!-- Vehicle Cards -->
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6" style="align-items: start;">
            @forelse($kendaraan as $item)
            <div class="vehicle-card glass-card rounded-3xl overflow-hidden flex flex-col">
                <!-- Image Header -->
                <div class="relative h-52 bg-gradient-to-br from-purple-400 via-pink-400 to-blue-400 overflow-hidden flex-shrink-0">
                    @if($item->foto_url)
                        <img src="{{ $item->foto_url }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             alt="{{ $item->plat_nomor }}">
                        <!-- tombol zoom -->
                        <button type="button"
                                onclick="openPreview('{{ $item->foto_url }}', '{{ $item->plat_nomor }}')"
                                class="absolute inset-0 flex items-end justify-end p-3 bg-black/0 hover:bg-black/20 transition-all group/img">
                            <span class="opacity-0 group-hover/img:opacity-100 bg-black/70 text-white text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1 transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                </svg>
                                Preview
                            </span>
                        </button>
                    @else
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-8xl">🚗</span>
                        </div>
                    @endif

                    <!-- Status badge -->
                    <div class="absolute top-3 right-3 pointer-events-none">
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold shadow-lg
                            {{ $item->status === 'aktif' ? 'bg-emerald-500 text-white' : 'bg-gray-600 text-white' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex flex-col flex-1">
                    <!-- Label + Judul -->
                    <a href="{{ route($isAdmin ? 'admin.kendaraan.show' : 'kendaraan.show', $item) }}" class="block mb-4 hover:opacity-80 transition-opacity">
                        <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold mb-2">
                            KENDARAAN DINAS
                        </span>
                        <h2 class="text-xl font-bold text-gray-900 leading-tight">{{ $item->plat_nomor }}</h2>
                        <p class="text-gray-500 text-sm mt-0.5">{{ $item->merk ?: '—' }}</p>
                    </a>

                    <!-- Unit Kerja -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl px-4 py-3 mb-4">
                        <p class="text-xs text-gray-500 mb-0.5">Unit Kerja</p>
                        <p class="font-bold text-gray-900 text-sm">{{ $item->unit_kerja ?: 'Belum ditentukan' }}</p>
                    </div>

                    <!-- Pajak badges -->
                    <div class="grid grid-cols-2 gap-2 mb-5">
                        @foreach([['Pajak Tahunan', $item->pajakTahunanAktif], ['Pajak 5 Tahunan', $item->pajakLimaTahunanAktif]] as [$label, $pajak])
                        <div class="rounded-2xl p-3 border-2 border-gray-100 bg-gray-50">
                            <p class="text-xs text-gray-500 mb-1.5 font-semibold">{{ $label }}</p>
                            @if($pajak)
                                @include('kendaraan.partials.badge-pajak', ['pajak' => $pajak])
                            @else
                                <span class="text-xs text-gray-400">Belum ada data</span>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Action buttons — selalu di bawah -->
                    <div class="mt-auto flex gap-2">
                        <a href="{{ route($isAdmin ? 'admin.kendaraan.show' : 'kendaraan.show', $item) }}"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:opacity-90 active:scale-95"
                           style="background: linear-gradient(135deg,#667eea,#764ba2);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Detail
                        </a>
                        @if($isAdmin)
                        <a href="{{ route('admin.kendaraan.edit', $item) }}"
                           class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:opacity-90 active:scale-95"
                           style="background: linear-gradient(135deg,#3b82f6,#1d4ed8);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <button
                            type="button"
                            onclick="confirmDelete({{ $item->id }}, '{{ $item->plat_nomor }}')"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-2xl text-sm font-bold text-white transition-all hover:opacity-90 active:scale-95"
                            style="background: linear-gradient(135deg,#ef4444,#b91c1c);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.kendaraan.destroy', $item) }}" method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="md:col-span-2 xl:col-span-3 glass-card rounded-3xl p-16 text-center">
                <div class="text-6xl mb-4">🚗</div>
                <p class="text-xl font-bold text-gray-600 mb-2">Belum ada data kendaraan</p>
                <p class="text-gray-500">Tambahkan kendaraan baru untuk memulai</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="image-preview-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/90 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-4xl">
            <!-- Tombol X -->
            <button onclick="closePreview()"
                    class="absolute -top-12 right-0 w-10 h-10 flex items-center justify-center rounded-full bg-white/20 text-white hover:bg-red-500 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img id="preview-img" src="" alt="" class="w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl">
            <p id="preview-caption" class="mt-4 text-center text-white font-bold text-lg"></p>
        </div>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ── Image Preview ────────────────────────────────────
    function openPreview(url, caption) {
        document.getElementById('preview-img').src = url;
        document.getElementById('preview-caption').textContent = caption;
        const modal = document.getElementById('image-preview-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closePreview() {
        const modal = document.getElementById('image-preview-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Tutup modal kalau klik backdrop
    document.getElementById('image-preview-modal').addEventListener('click', function(e) {
        if (e.target === this) closePreview();
    });

    // Tutup modal pakai Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePreview();
    });

    // ── Delete Confirm ───────────────────────────────────
    function confirmDelete(id, platNomor) {
        Swal.fire({
            title: 'Hapus Kendaraan?',
            html: `Yakin ingin menghapus kendaraan <strong>${platNomor}</strong>?<br><small class="text-gray-500">Semua data pajak terkait juga akan terhapus.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
@endsection
