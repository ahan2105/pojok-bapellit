@php
    $userAuth = Auth::user();
    $isAdmin = $userAuth && ($userAuth->role === 'admin' || $userAuth->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Kelola Pegawai')

@section('content')
<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-12 py-6 sm:py-8">

    <!-- Tombol HOME -->
    <div class="mb-6 sm:mb-8">
        <a href="{{ route('admin.absensi.index') }}" 
           class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-white text-gray-700 text-sm sm:text-base font-medium rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 hover:border-indigo-300 hover:text-indigo-600 transition-all group">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 group-hover:text-indigo-600 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            HOME
        </a>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="flex-1">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">
                Kelola Pegawai
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-2">
                Daftar pegawai yang terdaftar sebagai peserta absensi.
            </p>
        </div>

        <!-- Tombol ke Kelola Akun -->
        <a href="{{ route('admin.kelolaakun.index') }}" 
           class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 bg-indigo-600 text-white text-sm sm:text-base font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm flex-shrink-0 self-start">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="hidden sm:inline">Tambah / Ubah Pegawai</span>
            <span class="sm:hidden">Tambah</span>
        </a>
    </div>

    <!-- Info Banner -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-sm sm:text-base text-blue-800">
            <p class="font-semibold mb-1">Pegawai yang tampil di sini otomatis ikut absensi.</p>
            <p class="text-blue-700">Kriteria: user dengan <strong>status "aktif"</strong>. Untuk menambah/menghapus pegawai, buka halaman <strong>Kelola Akun</strong>.</p>
        </div>
    </div>

    <!-- Toolbar: Search + Filter Bidang -->
    <div class="bg-white rounded-t-2xl shadow-sm border border-gray-200 p-4 sm:p-5">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
            
            <!-- Search -->
            <form method="GET" action="{{ route('admin.pegawai.index') }}" class="flex-1 sm:max-w-md">
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}"
                           placeholder="Cari nama atau email..."
                           class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:outline-none transition">
                    
                    @if($search)
                        <a href="{{ route('admin.pegawai.index', ['bidang' => $filterBidang]) }}" 
                           class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition"
                           title="Hapus pencarian">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Filter Bidang -->
            <form method="GET" action="{{ route('admin.pegawai.index') }}" class="w-full sm:w-56 flex-shrink-0">
                @if($search)
                    <input type="hidden" name="search" value="{{ $search }}">
                @endif
                <select name="bidang" 
                        onchange="this.form.submit()"
                        class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:outline-none transition cursor-pointer">
                    <option value="">Semua Bidang</option>
                    @foreach($bidangList as $b)
                        <option value="{{ $b }}" {{ $filterBidang === $b ? 'selected' : '' }}>
                            {{ $b }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- ===== Tabel Pegawai ===== -->
    <div class="bg-white rounded-b-2xl shadow-sm border border-t-0 border-gray-200 overflow-hidden">
        
        {{-- DESKTOP: Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-16">NO</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">NAMA PEGAWAI</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">ASAL BIDANG</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">TOTAL ABSEN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pegawai as $index => $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-base text-gray-600 font-medium">
                                {{ $pegawai->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-base font-semibold text-gray-800">{{ $p->name }}</div>
                                <div class="text-sm text-gray-500">{{ $p->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-base text-gray-600">{{ $p->bidang ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-indigo-50 text-indigo-700">
                                    {{ $p->absensi_details_count }} sesi
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-base text-gray-500">
                                    @if($search || $filterBidang)
                                        Tidak ada pegawai yang cocok dengan filter.
                                    @else
                                        Belum ada pegawai aktif. Tambah di halaman Kelola Akun.
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
            @forelse($pegawai as $index => $p)
                <div class="p-4 hover:bg-gray-50 transition">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-bold text-indigo-700">
                            {{ $pegawai->firstItem() + $index }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-base font-semibold text-gray-800">{{ $p->name }}</div>
                            <div class="text-sm text-gray-500 mt-0.5">{{ $p->email }}</div>
                            
                            @if($p->bidang)
                                <div class="mt-1.5 text-sm text-gray-600">
                                    {{ $p->bidang }}
                                </div>
                            @endif

                            <div class="mt-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                    {{ $p->absensi_details_count }} sesi diabsen
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-base text-gray-500">
                        @if($search || $filterBidang)
                            Tidak ada pegawai yang cocok dengan filter.
                        @else
                            Belum ada pegawai aktif. Tambah di halaman Kelola Akun.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($pegawai->hasPages())
        <div class="mt-6">
            {{ $pegawai->links() }}
        </div>
    @endif

    <!-- Total Info -->
    <div class="mt-4 text-center text-sm text-gray-500">
        Total <strong class="text-gray-700">{{ $pegawai->total() }}</strong> pegawai aktif terdaftar sebagai peserta absensi.
    </div>
</div>
@endsection