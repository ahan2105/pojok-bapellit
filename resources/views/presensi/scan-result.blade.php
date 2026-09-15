@php
    $userAuth = Auth::user();
    $isAdmin = $userAuth && ($userAuth->role === 'admin' || $userAuth->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', $title ?? 'Hasil Scan')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center">

        <!-- Icon Status -->
        <div class="mx-auto w-20 h-20 rounded-full flex items-center justify-center mb-6 
                    {{ $status === 'success' ? 'bg-green-100' : ($status === 'error' ? 'bg-red-100' : 'bg-blue-100') }}">
            @if($status === 'success')
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @elseif($status === 'error')
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @else
                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @endif
        </div>

        <!-- Judul & Pesan -->
        <h1 class="text-2xl font-bold {{ $status === 'success' ? 'text-green-700' : ($status === 'error' ? 'text-red-700' : 'text-blue-700') }} mb-3">
            {{ $title }}
        </h1>
        <p class="text-gray-600 text-sm mb-6">{{ $message }}</p>

        <!-- Info Sesi -->
        @if(isset($sesi))
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left text-sm">
                <div class="flex justify-between py-1">
                    <span class="text-gray-500">Sesi:</span>
                    <span class="font-semibold text-gray-800">{{ $sesi->nama_sesi }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-500">Tanggal:</span>
                    <span class="font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('d F Y') }}
                    </span>
                </div>
                @if(isset($waktu))
                    <div class="flex justify-between py-1">
                        <span class="text-gray-500">Waktu:</span>
                        <span class="font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($waktu)->format('H:i:s') }} WIB
                        </span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Tombol Aksi -->
        <div class="flex flex-col gap-2">
            <a href="{{ route('presensi.index') }}" 
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">
                Lihat Riwayat Presensi
            </a>
            <a href="{{ route('absensi.scan-page') }}" 
               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200">
                Scan Lagi
            </a>
        </div>
    </div>
</div>
@endsection