@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
    
    // Ambil bulan & tahun sekarang
    $bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : date('n');
    $tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
    
    if ($bulan < 1) { $bulan = 12; $tahun--; }
    if ($bulan > 12) { $bulan = 1; $tahun++; }
    
    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
    $hariPertama = date('w', strtotime("$tahun-$bulan-01"));
    
    $bookedDates = \App\Models\Booking::where('aula_id', $aula->id)
        ->whereMonth('tanggal_booking', $bulan)
        ->whereYear('tanggal_booking', $tahun)
        ->whereIn('status', ['pending', 'approved'])
        ->pluck('tanggal_booking')
        ->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        })
        ->toArray();

    // ⭐ Ambil semua foto yang ada (buat lightbox)
    $fotos = is_array($aula->foto) ? array_values(array_filter($aula->foto)) : [];
    $fotoUrls = array_map(fn($f) => asset('storage/' . $f), $fotos);
@endphp

@extends($layout)

@section('title', 'Detail & Booking - ' . $aula->nama)

@section('content')
<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-12 py-6 sm:py-8">
    
    <!-- Tombol Kembali -->
    <div class="mb-6 sm:mb-8">
        <a href="{{ route('booking.index') }}" 
           class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-white text-gray-700 text-sm sm:text-base font-medium rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 hover:border-indigo-300 hover:text-indigo-600 transition-all group">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 group-hover:text-indigo-600 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="hidden sm:inline">Kembali ke Daftar Aula</span>
            <span class="sm:hidden">Kembali</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        
        <!-- KOLOM KIRI -->
        <div class="lg:col-span-2 space-y-6 sm:space-y-8">
            
            <!-- Header Aula -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 leading-tight">{{ $aula->nama }}</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-2">
                            Kapasitas Maksimal: 
                            <span class="font-semibold text-gray-700">{{ $aula->kapasitas }}</span> Orang
                        </p>
                    </div>
                    <span class="inline-flex self-start px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-sm sm:text-base font-semibold {{ $aula->status_aktif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $aula->status_aktif ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- FOTO AULA + LIGHTBOX                         -->
            <!-- ============================================ -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-8">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-5">
                    Galeri Foto
                    @if(count($fotoUrls) > 0)
                        <span class="text-sm font-normal text-gray-400 ml-2">(klik untuk memperbesar)</span>
                    @endif
                </h2>

                @if(count($fotoUrls) > 0)
                    {{-- Grid responsif: 1 kolom HP, 2 tablet, 3 desktop --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                        @foreach($fotoUrls as $i => $url)
                            <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden cursor-pointer group relative"
                                 onclick="openLightbox({{ $i }})">
                                <img src="{{ $url }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                     alt="Foto Aula {{ $i+1 }}"
                                     loading="lazy">
                                {{-- Overlay zoom --}}
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all flex items-center justify-center">
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2 bg-black/60 text-white px-3 py-1.5 rounded-full text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                        Perbesar
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Placeholder kalau tidak ada foto --}}
                    <div class="grid grid-cols-3 gap-4">
                        @for($i = 0; $i < 3; $i++)
                            <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden">
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-400 text-center mt-4">Belum ada foto untuk aula ini.</p>
                @endif
            </div>

            <!-- Deskripsi -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-8">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Deskripsi Aula</h2>
                <p class="text-sm sm:text-base text-gray-700 leading-relaxed">{{ $aula->deskripsi ?? 'Belum ada deskripsi untuk aula ini.' }}</p>
                
                @if($aula->informasi_tambahan || $aula->lokasi)
                    <div class="mt-5 sm:mt-6 p-4 sm:p-5 bg-gray-50 rounded-xl border border-gray-200">
                        @if($aula->informasi_tambahan)
                            <h3 class="text-xs sm:text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">Informasi Tambahan</h3>
                            <ul class="text-sm sm:text-base text-gray-600 space-y-1.5">
                                @php $informasiList = explode("\n", $aula->informasi_tambahan); @endphp
                                @foreach($informasiList as $info)
                                    @if(trim($info))
                                        <li class="flex items-start gap-2">
                                            <span class="text-indigo-500 mt-0.5">•</span>
                                            <span>{{ trim($info) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                        @if($aula->lokasi)
                            <p class="text-sm sm:text-base text-gray-600 mt-3">
                                <strong class="text-gray-800">Lokasi:</strong> {{ $aula->lokasi }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Fasilitas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-8">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-5">Fasilitas Lengkap</h2>
                @if(!empty($aula->fasilitas))
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                        @foreach($aula->fasilitas as $fasilitas)
                            <div class="flex items-center gap-2 sm:gap-3 p-3 sm:p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs sm:text-sm font-bold flex-shrink-0">✓</span>
                                <span class="text-sm sm:text-base font-medium text-gray-700">{{ $fasilitas }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm sm:text-base text-gray-500">Belum ada fasilitas yang ditambahkan.</p>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: FORM BOOKING -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-7 lg:sticky lg:top-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-5 sm:mb-6">Booking Aula</h2>
                
                @if(!$aula->status_aktif)
                    <div class="p-4 sm:p-5 bg-red-50 border border-red-200 rounded-xl text-sm sm:text-base text-red-700">
                        Maaf, aula ini sedang tidak tersedia untuk dibooking.
                    </div>
                @else
                    <form action="{{ route('booking.store') }}" method="POST" id="booking-form">
                        @csrf
                        <input type="hidden" name="aula_id" value="{{ $aula->id }}">
                        <input type="hidden" name="tanggal_booking" id="tanggal_booking_hidden" value="">

                        <!-- Nama Penanggung Jawab -->
                        <div class="mb-4 sm:mb-5">
                            <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-2">Nama Penanggung Jawab</label>
                            <input type="text" name="nama_penanggung_jawab" value="{{ old('nama_penanggung_jawab', Auth::user()->name) }}" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" 
                                required>
                        </div>

                        <!-- Keperluan -->
                        <div class="mb-4 sm:mb-5">
                            <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-2">Keperluan / Nama Acara</label>
                            <input type="text" name="keperluan" value="{{ old('keperluan') }}" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" 
                                placeholder="Contoh: Rapat Koordinasi" required>
                        </div>

                        <!-- Jumlah Peserta -->
                        <div class="mb-4 sm:mb-5">
                            <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-2">Jumlah Peserta</label>
                            <div class="relative">
                                <input type="number" name="jumlah_peserta" value="{{ old('jumlah_peserta', 1) }}" 
                                    min="1" max="{{ $aula->kapasitas }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base pr-20" 
                                    required>
                                <span class="absolute right-4 top-3.5 text-sm text-gray-400 font-medium">Orang</span>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-400 mt-1.5">Maksimal {{ $aula->kapasitas }} orang</p>
                        </div>

                        <!-- KALENDER -->
                        <div class="mb-4 sm:mb-5">
                            <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-3">Pilih Tanggal & Waktu</label>
                            
                            <div class="flex items-center justify-between mb-4">
                                <a href="?bulan={{ $bulan - 1 }}&tahun={{ $tahun }}" 
                                    class="p-2 sm:p-2.5 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-gray-600 border border-gray-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </a>
                                <span class="text-base sm:text-lg font-bold text-gray-900">{{ $namaBulan[$bulan] }} {{ $tahun }}</span>
                                <a href="?bulan={{ $bulan + 1 }}&tahun={{ $tahun }}" 
                                    class="p-2 sm:p-2.5 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-gray-600 border border-gray-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>

                            <div class="grid grid-cols-7 gap-1 sm:gap-1.5 text-center text-xs sm:text-sm font-semibold text-gray-500 mb-2">
                                <div>M</div><div>S</div><div>S</div><div>R</div><div>K</div><div>J</div><div>S</div>
                            </div>

                            <div class="grid grid-cols-7 gap-1 sm:gap-1.5">
                                @php
                                    $today = date('Y-m-d');
                                    $selectedDate = old('tanggal_booking', date('Y-m-d'));
                                @endphp
                                
                                @for($i = 0; $i < $hariPertama; $i++)
                                    <div class="aspect-square"></div>
                                @endfor
                                
                                @for($day = 1; $day <= $jumlahHari; $day++)
                                    @php
                                        $dateString = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
                                        $isToday = $dateString === $today;
                                        $isBooked = in_array($dateString, $bookedDates);
                                        $isPast = $dateString < $today;
                                        $isSelected = $dateString === $selectedDate;
                                    @endphp
                                    <button type="button"
                                        data-date="{{ $dateString }}"
                                        class="aspect-square rounded-lg text-sm sm:text-base font-semibold transition
                                            {{ $isPast ? 'text-gray-300 cursor-not-allowed' : '' }}
                                            {{ $isBooked ? 'bg-red-500 text-white cursor-not-allowed' : '' }}
                                            {{ $isSelected && !$isBooked && !$isPast ? 'bg-indigo-600 text-white ring-2 ring-indigo-300' : '' }}
                                            {{ !$isPast && !$isBooked && !$isSelected ? 'hover:bg-gray-100 text-gray-700' : '' }}
                                        "
                                        {{ $isPast || $isBooked ? 'disabled' : '' }}
                                        onclick="selectDate(this, '{{ $dateString }}')">
                                        {{ $day }}
                                    </button>
                                @endfor
                            </div>

                            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-5 mt-4 text-xs sm:text-sm">
                                <div class="flex items-center gap-1.5 sm:gap-2">
                                    <span class="w-3 h-3 sm:w-3.5 sm:h-3.5 rounded-full bg-indigo-600"></span>
                                    <span class="text-gray-600">Dipilih</span>
                                </div>
                                <div class="flex items-center gap-1.5 sm:gap-2">
                                    <span class="w-3 h-3 sm:w-3.5 sm:h-3.5 rounded-full bg-red-500"></span>
                                    <span class="text-gray-600">Terbooking</span>
                                </div>
                                <div class="flex items-center gap-1.5 sm:gap-2">
                                    <span class="w-3 h-3 sm:w-3.5 sm:h-3.5 rounded-full border-2 border-gray-300 bg-white"></span>
                                    <span class="text-gray-600">Tersedia</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sesi Waktu -->
                        <div class="mb-4 sm:mb-5">
                            <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-3">Pilih Sesi Waktu</label>
                            <div class="space-y-2.5">
                                <label class="flex items-center p-3 sm:p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="sesi_waktu" value="pagi" class="w-5 h-5 text-indigo-600" required>
                                    <div class="ml-3 sm:ml-4">
                                        <p class="text-sm sm:text-base font-semibold text-gray-800">Sesi Pagi</p>
                                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">08:00 - 12:00 WIB</p>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 sm:p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="sesi_waktu" value="siang" class="w-5 h-5 text-indigo-600">
                                    <div class="ml-3 sm:ml-4">
                                        <p class="text-sm sm:text-base font-semibold text-gray-800">Sesi Siang</p>
                                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">13:00 - 17:00 WIB</p>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 sm:p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="sesi_waktu" value="seharian" class="w-5 h-5 text-indigo-600">
                                    <div class="ml-3 sm:ml-4">
                                        <p class="text-sm sm:text-base font-semibold text-gray-800">Sehari Penuh</p>
                                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">08:00 - 17:00 WIB</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4 sm:mb-5">
                            <label class="block text-sm sm:text-base font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="catatan" rows="3" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base leading-relaxed" 
                                placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan') }}</textarea>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" 
                            class="w-full px-6 py-3.5 bg-indigo-600 text-white text-base font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                            Booking Sekarang
                        </button>

                        <p class="text-xs sm:text-sm text-gray-400 text-center mt-4">
                            Dengan mengikuti, Anda menyetujui syarat & ketentuan peraturan yang berlaku.
                        </p>
                    </form>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- ============================================================ -->
<!-- ⭐ LIGHTBOX — untuk preview foto galeri                      -->
<!-- ============================================================ -->
@if(count($fotoUrls) > 0)
<div id="lightbox" 
     class="hidden fixed inset-0 z-[100] items-center justify-center bg-black/90 backdrop-blur-sm p-4"
     onclick="closeLightbox(event)">
    
    {{-- Tombol Close --}}
    <button type="button" 
            onclick="closeLightbox(event, true)"
            class="absolute top-4 right-4 z-10 p-3 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-full transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    {{-- Counter --}}
    <div id="lightbox-counter" 
         class="absolute top-4 left-4 z-10 px-3 py-1.5 bg-white/10 text-white text-sm font-medium rounded-full">
        1 / {{ count($fotoUrls) }}
    </div>

    {{-- Tombol Prev --}}
    @if(count($fotoUrls) > 1)
    <button type="button" 
            onclick="prevImage(event)"
            class="absolute left-2 sm:left-4 z-10 p-3 sm:p-4 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-full transition">
        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    {{-- Tombol Next --}}
    <button type="button" 
            onclick="nextImage(event)"
            class="absolute right-2 sm:right-4 z-10 p-3 sm:p-4 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-full transition">
        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
    @endif

    {{-- Gambar --}}
    <img id="lightbox-img" 
         src="" 
         alt="Preview Foto" 
         class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl cursor-zoom-in transition-transform duration-300"
         onclick="toggleZoom(event)">

    {{-- Hint --}}
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10 px-4 py-2 bg-white/10 text-white/80 text-xs sm:text-sm rounded-full text-center">
        Klik gambar untuk zoom · Klik area hitam untuk tutup · ESC untuk keluar
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ===== Tanggal Booking =====
    function selectDate(element, date) {
        document.querySelectorAll('.grid-cols-7 button[data-date]').forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white', 'ring-2', 'ring-indigo-300');
            if (!btn.disabled && !btn.classList.contains('bg-red-500')) {
                btn.classList.add('hover:bg-gray-100', 'text-gray-700');
            }
        });
        
        element.classList.add('bg-indigo-600', 'text-white', 'ring-2', 'ring-indigo-300');
        element.classList.remove('hover:bg-gray-100', 'text-gray-700');
        
        document.getElementById('tanggal_booking_hidden').value = date;
    }

    // ===== Cek Ketersediaan =====
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalInput = document.getElementById('tanggal_booking_hidden');
        const sesiInputs = document.querySelectorAll('input[name="sesi_waktu"]');
        const aulaId = {{ $aula->id }};
        
        function checkAvailability() {
            const tanggal = tanggalInput.value;
            let sesi = null;
            sesiInputs.forEach(input => {
                if (input.checked) sesi = input.value;
            });
            
            if (!tanggal || !sesi) return;
            
            fetch('{{ route("booking.check-availability") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    aula_id: aulaId,
                    tanggal_booking: tanggal,
                    sesi_waktu: sesi
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Tersedia',
                        text: data.message || 'Aula sudah dibooking pada tanggal dan sesi tersebut.',
                        confirmButtonColor: '#f59e0b',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(() => {});
        }
        
        sesiInputs.forEach(input => {
            input.addEventListener('change', checkAvailability);
        });
        
        const firstAvailable = document.querySelector('.grid-cols-7 button:not([disabled])');
        if (firstAvailable) {
            firstAvailable.click();
        }
    });

    // ============================================================
    // ⭐ LIGHTBOX GALLERY
    // ============================================================
    @if(count($fotoUrls) > 0)
        const lightboxImages = @json($fotoUrls);
        let currentImageIndex = 0;
        let isZoomed = false;

        function openLightbox(index) {
            currentImageIndex = index;
            updateLightboxImage();
            
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox(event, forceClose = false) {
            if (forceClose) {
                actuallyClose();
                return;
            }
            if (isZoomed) {
                toggleZoom(event, true);
                return;
            }
            if (event.target.id === 'lightbox') {
                actuallyClose();
            }
        }

        function actuallyClose() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            document.body.style.overflow = '';
            
            isZoomed = false;
            const img = document.getElementById('lightbox-img');
            img.classList.remove('scale-150', 'cursor-zoom-out');
            img.classList.add('cursor-zoom-in');
        }

        function updateLightboxImage() {
            const img = document.getElementById('lightbox-img');
            img.src = lightboxImages[currentImageIndex];
            
            isZoomed = false;
            img.classList.remove('scale-150', 'cursor-zoom-out');
            img.classList.add('cursor-zoom-in');
            
            document.getElementById('lightbox-counter').textContent = 
                (currentImageIndex + 1) + ' / ' + lightboxImages.length;
        }

        function nextImage(event) {
            event.stopPropagation();
            currentImageIndex = (currentImageIndex + 1) % lightboxImages.length;
            updateLightboxImage();
        }

        function prevImage(event) {
            event.stopPropagation();
            currentImageIndex = (currentImageIndex - 1 + lightboxImages.length) % lightboxImages.length;
            updateLightboxImage();
        }

        function toggleZoom(event, forceUnzoom = false) {
            event.stopPropagation();
            const img = document.getElementById('lightbox-img');
            
            if (forceUnzoom || isZoomed) {
                img.classList.remove('scale-150', 'cursor-zoom-out');
                img.classList.add('cursor-zoom-in');
                isZoomed = false;
            } else {
                img.classList.add('scale-150', 'cursor-zoom-out');
                img.classList.remove('cursor-zoom-in');
                isZoomed = true;
            }
        }

        document.addEventListener('keydown', function(e) {
            const lightbox = document.getElementById('lightbox');
            if (!lightbox || lightbox.classList.contains('hidden')) return;

            if (e.key === 'Escape') {
                actuallyClose();
            } else if (e.key === 'ArrowRight') {
                nextImage(e);
            } else if (e.key === 'ArrowLeft') {
                prevImage(e);
            }
        });
    @endif
</script>

<style>
    .grid-cols-7 button {
        transition: all 0.15s ease;
    }
    .grid-cols-7 button:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
    .grid-cols-7 button:not(:disabled):hover {
        transform: scale(1.05);
    }
    .grid-cols-7 button.bg-indigo-600:hover {
        transform: scale(1.05);
    }
</style>
@endsection