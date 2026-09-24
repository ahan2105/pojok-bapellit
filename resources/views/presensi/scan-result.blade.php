@php
    $userAuth = Auth::user();
    $isAdmin = $userAuth && ($userAuth->role === 'admin' || $userAuth->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', $title ?? 'Hasil Scan')

@section('content')
<div class="max-w-md mx-auto px-4 py-10 sm:py-16">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- ⭐ Banner Status --}}
        <div class="h-2 
            {{ $status === 'success' ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 
               ($status === 'error' ? 'bg-gradient-to-r from-red-400 to-rose-500' : 
               'bg-gradient-to-r from-blue-400 to-indigo-500') }}">
        </div>

        <div class="p-6 sm:p-8 text-center">

            <!-- Icon Status -->
            <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center mb-5 relative
                        {{ $status === 'success' ? 'bg-green-100' : ($status === 'error' ? 'bg-red-100' : 'bg-blue-100') }}">
                
                @if($status === 'success')
                    {{-- Ping animation --}}
                    <span class="absolute inset-0 rounded-full bg-green-400 opacity-40 animate-ping"></span>
                    <svg class="w-10 h-10 text-green-600 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                @elseif($status === 'error')
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                @else
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
            </div>

            <!-- Badge Status -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide mb-3
                        {{ $status === 'success' ? 'bg-green-100 text-green-700' : 
                           ($status === 'error' ? 'bg-red-100 text-red-700' : 
                           'bg-blue-100 text-blue-700') }}">
                @if($status === 'success')
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Berhasil
                @elseif($status === 'error')
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Gagal
                @else
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Info
                @endif
            </div>

            <!-- Judul -->
            <h1 class="text-2xl font-bold mb-3
                       {{ $status === 'success' ? 'text-green-700' : ($status === 'error' ? 'text-red-700' : 'text-blue-700') }}">
                {{ $title }}
            </h1>

            <!-- Pesan -->
            <p class="text-gray-600 text-sm leading-relaxed mb-6">{{ $message }}</p>

            <!-- Info Sesi -->
            @if(isset($sesi))
                <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl p-4 mb-6 text-left border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Detail Sesi
                    </p>

                    <div class="space-y-2.5">
                        {{-- Nama Sesi --}}
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-gray-500">Nama Sesi</p>
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $sesi->nama_sesi }}</p>
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-gray-500">Tanggal</p>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('l, d F Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- Waktu (kalau ada) --}}
                        @if(isset($waktu))
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-gray-500">Waktu Absen</p>
                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ \Carbon\Carbon::parse($waktu)->format('H:i:s') }} WIB
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Lokasi (kalau ada) --}}
                        @if($sesi->lokasi)
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-gray-500">Lokasi</p>
                                    <p class="text-sm font-semibold text-gray-800">{{ $sesi->lokasi }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- ⭐ Info Tambahan untuk "Tidak Terdaftar" --}}
            @if($status === 'error' && isset($sesi) && str_contains($message, 'tidak terdaftar'))
                <div class="mb-6 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2 text-left">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-xs text-amber-700 leading-relaxed">
                        <strong class="block mb-1">Kenapa saya tidak terdaftar?</strong>
                        Admin mungkin tidak memilih Anda saat membuat sesi ini. Hubungi admin (IT Bappelitbangda) untuk didaftarkan.
                    </div>
                </div>
            @endif

            <!-- Tombol Aksi -->
            <div class="flex flex-col gap-2">
                <a href="{{ route('presensi.index') }}" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Lihat Riwayat Presensi
                </a>
                <a href="{{ route('absensi.scan-page') }}" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Scan Lagi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection