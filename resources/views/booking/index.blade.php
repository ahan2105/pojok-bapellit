@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Booking Aula')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-12 py-8">
    
    <!-- Header - Tengah -->
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Booking Aula</h1>
        <p class="text-base text-gray-500 mt-2">Pilih Aula yang Ingin Dibooking</p>
    </div>

    <!-- Info - Tengah -->
    <div class="bg-white shadow-sm border border-gray-100 p-5 mb-8 rounded-xl flex items-start max-w-3xl mx-auto">
        <svg class="w-6 h-6 mr-3 mt-0.5 flex-shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-base text-gray-600">Silakan pilih fasilitas aula yang sesuai dengan kebutuhan acara Anda.</p>
    </div>

    <!-- Daftar Aula -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($aulas as $aula)
            @php 
                $fotos = is_array($aula->foto) ? $aula->foto : [];
                $totalFoto = count($fotos);
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 group flex flex-col">
                <!-- Foto Aula - Carousel -->
                <div class="relative h-56 bg-gray-100 overflow-hidden" x-data="{ currentSlide: 0, totalSlides: {{ $totalFoto > 0 ? $totalFoto : 1 }} }">
                    <!-- Slide Container -->
                    <div class="w-full h-full relative">
                        @if($totalFoto > 0)
                            @foreach($fotos as $index => $foto)
                                <div x-show="currentSlide === {{ $index }}" 
                                     x-transition:enter.duration.300ms 
                                     class="absolute inset-0 w-full h-full">
                                    <img src="{{ asset('storage/' . $foto) }}" 
                                        class="w-full h-full object-cover" 
                                        alt="{{ $aula->nama }}">
                                </div>
                            @endforeach
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Tombol Navigasi -->
                    @if($totalFoto > 1)
                        <button @click="currentSlide = (currentSlide - 1 + totalSlides) % totalSlides" 
                            class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-9 h-9 flex items-center justify-center transition z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button @click="currentSlide = (currentSlide + 1) % totalSlides" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-9 h-9 flex items-center justify-center transition z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    @endif

                    <!-- Dot Indikator -->
                    @if($totalFoto > 1)
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                            @for($i = 0; $i < $totalFoto; $i++)
                                <button @click="currentSlide = {{ $i }}" 
                                    class="w-2 h-2 rounded-full transition"
                                    :class="currentSlide === {{ $i }} ? 'bg-white' : 'bg-white/50'">
                                </button>
                            @endfor
                        </div>
                    @endif
                    
                    <!-- Badge Status -->
                    <div class="absolute top-4 right-4 z-10">
                        <span class="px-3 py-1.5 rounded-full text-sm font-semibold shadow-md {{ $aula->status_aktif ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ $aula->status_aktif ? 'Tersedia' : 'Tidak Tersedia' }}
                        </span>
                    </div>
                </div>
                
                <!-- Info Aula -->
                <div class="p-6 flex flex-col flex-1">
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $aula->nama }}</h3>

                    <div class="mb-3">
                        <span class="inline-flex items-center gap-1.5 text-base text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Kapasitas: <span class="font-semibold text-gray-800">{{ $aula->kapasitas }}</span> Orang
                        </span>
                    </div>
                    
                    <!-- Deskripsi -->
                    @if($aula->deskripsi)
                        <p class="text-base text-gray-600 mb-5 line-clamp-3 leading-relaxed flex-1">{{ $aula->deskripsi }}</p>
                    @else
                        <p class="text-base text-gray-400 mb-5 line-clamp-3 flex-1">Tidak ada deskripsi</p>
                    @endif
                    
                    <!-- Tombol Booking -->
                    @if($aula->status_aktif)
                        <a href="{{ route('booking.show', $aula->id) }}" 
                            class="block w-full text-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition text-base font-semibold">
                            Lihat Ketersediaan & Booking
                        </a>
                    @else
                        <button disabled 
                            class="block w-full text-center px-5 py-3 bg-gray-200 text-gray-500 rounded-lg text-base font-semibold cursor-not-allowed">
                            Tidak Tersedia
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20">
                <svg class="w-28 h-28 text-gray-300 mx-auto mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-2xl font-semibold text-gray-700 mb-2">Belum Ada Aula Tersedia</h3>
                <p class="text-base text-gray-500">Silahkan hubungi admin untuk menambahkan aula.</p>
                @if($isAdmin)
                    <a href="{{ route('admin.aula.create') }}" class="inline-block mt-5 px-7 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-base font-semibold">
                        Tambah Aula Sekarang
                    </a>
                @endif
            </div>
        @endforelse
    </div>

</div>
@endsection