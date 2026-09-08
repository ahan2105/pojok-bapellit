@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Booking Aula')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Booking Aula</h1>
        <p class="text-sm text-gray-600">Pilih aula yang ingin dibooking</p>
    </div>

    <!-- Info -->
    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-lg">
        <p class="text-sm">📌 Silakan pilih fasilitas aula yang sesuai dengan kebutuhan acara Anda.</p>
    </div>

    <!-- Daftar Aula -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($aulas as $aula)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <!-- Foto Aula -->
                <div class="h-48 bg-gray-100 relative">
                    @php 
                        $fotos = is_array($aula->foto) ? $aula->foto : [];
                    @endphp
                    @if(!empty($fotos))
                        <img src="{{ asset('storage/' . $fotos[0]) }}" class="w-full h-full object-cover" alt="{{ $aula->nama }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Badge Status -->
                    <div class="absolute top-3 right-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $aula->status_aktif ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ $aula->status_aktif ? 'Tersedia' : 'Tidak Tersedia' }}
                        </span>
                    </div>
                </div>
                
                <!-- Info Aula -->
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $aula->nama }}</h3>
                    <p class="text-sm text-gray-600 mb-2">
                        Kapasitas: <span class="font-semibold">{{ $aula->kapasitas }}</span> Orang
                    </p>
                    
                    <!-- Deskripsi -->
                    @if($aula->deskripsi)
                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $aula->deskripsi }}</p>
                    @endif
                    
                    <!-- Fasilitas -->
                    @if(!empty($aula->fasilitas))
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach(array_slice($aula->fasilitas, 0, 3) as $fasilitas)
                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 text-xs rounded-full">
                                    {{ $fasilitas }}
                                </span>
                            @endforeach
                            @if(count($aula->fasilitas) > 3)
                                <span class="px-2 py-0.5 bg-gray-50 text-gray-500 text-xs rounded-full">
                                    +{{ count($aula->fasilitas) - 3 }}
                                </span>
                            @endif
                        </div>
                    @endif
                    
                    <a href="{{ route('booking.show', $aula->id) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                        Lihat Ketersediaan & Booking
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Aula Tersedia</h3>
                <p class="text-sm text-gray-500">Silahkan hubungi admin untuk menambahkan aula.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection