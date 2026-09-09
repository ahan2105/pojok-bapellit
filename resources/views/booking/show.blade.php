@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
    
    // Ambil bulan & tahun sekarang
    $bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : date('n');
    $tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
    
    // Jika bulan < 1 atau > 12
    if ($bulan < 1) { $bulan = 12; $tahun--; }
    if ($bulan > 12) { $bulan = 1; $tahun++; }
    
    // Nama bulan
    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    // Jumlah hari dalam bulan
    $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
    
    // Hari pertama (0 = Minggu)
    $hariPertama = date('w', strtotime("$tahun-$bulan-01"));
    
    // Booking yang sudah ada (dari database)
    $bookedDates = \App\Models\Booking::where('aula_id', $aula->id)
        ->whereMonth('tanggal_booking', $bulan)
        ->whereYear('tanggal_booking', $tahun)
        ->whereIn('status', ['pending', 'approved'])
        ->pluck('tanggal_booking')
        ->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        })
        ->toArray();
@endphp

@extends($layout)

@section('title', 'Detail & Booking - ' . $aula->nama)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('booking.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-indigo-600 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Aula
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- ============================================ -->
        <!-- KOLOM KIRI: INFORMASI AULA                    -->
        <!-- ============================================ -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Header Aula -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $aula->nama }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Kapasitas Maksimal: <span class="font-semibold text-gray-700">{{ $aula->kapasitas }}</span> Orang</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $aula->status_aktif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $aula->status_aktif ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </div>
            </div>

            <!-- Foto Aula -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Galeri Foto</h2>
                <div class="grid grid-cols-3 gap-3">
                    @php $fotos = is_array($aula->foto) ? $aula->foto : []; @endphp
                    @for($i = 0; $i < 3; $i++)
                        <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden">
                            @if(isset($fotos[$i]) && !empty($fotos[$i]))
                                <img src="{{ asset('storage/' . $fotos[$i]) }}" class="w-full h-full object-cover" alt="Foto Aula {{ $i+1 }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-3">Deskripsi Aula</h2>
                <p class="text-gray-700 leading-relaxed">{{ $aula->deskripsi ?? 'Belum ada deskripsi untuk aula ini.' }}</p>
                
                <!-- Informasi Tambahan (dari database) -->
                @if($aula->informasi_tambahan || $aula->lokasi)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        @if($aula->informasi_tambahan)
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">INFORMASI TAMBAHAN</h3>
                            <ul class="text-sm text-gray-600 space-y-1">
                                @php
                                    $informasiList = explode("\n", $aula->informasi_tambahan);
                                @endphp
                                @foreach($informasiList as $info)
                                    @if(trim($info))
                                        <li>• {{ trim($info) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                        @if($aula->lokasi)
                            <p class="text-sm text-gray-600 mt-2"><strong>LOKASI:</strong> {{ $aula->lokasi }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Fasilitas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-3">Fasilitas Lengkap</h2>
                @if(!empty($aula->fasilitas))
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($aula->fasilitas as $fasilitas)
                            <div class="flex items-center space-x-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-indigo-600">✓</span>
                                <span class="text-sm font-medium text-gray-700">{{ $fasilitas }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Belum ada fasilitas yang ditambahkan.</p>
                @endif
            </div>
        </div>

        <!-- ============================================ -->
        <!-- KOLOM KANAN: FORM BOOKING + KALENDER         -->
        <!-- ============================================ -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Booking Aula</h2>
                
                @if(!$aula->status_aktif)
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                        Maaf, aula ini sedang tidak tersedia untuk dibooking.
                    </div>
                @else
                    <form action="{{ route('booking.store') }}" method="POST" id="booking-form">
                        @csrf
                        <input type="hidden" name="aula_id" value="{{ $aula->id }}">
                        <input type="hidden" name="tanggal_booking" id="tanggal_booking_hidden" value="">

                        <!-- Nama Penanggung Jawab -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Penanggung Jawab</label>
                            <input type="text" name="nama_penanggung_jawab" value="{{ old('nama_penanggung_jawab', Auth::user()->name) }}" 
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" 
                                required>
                        </div>

                        <!-- Keperluan -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Keperluan / Nama Acara</label>
                            <input type="text" name="keperluan" value="{{ old('keperluan') }}" 
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" 
                                placeholder="Contoh: Rapat Koordinasi" required>
                        </div>

                        <!-- Jumlah Peserta -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah Peserta</label>
                            <div class="relative">
                                <input type="number" name="jumlah_peserta" value="{{ old('jumlah_peserta', 1) }}" 
                                    min="1" max="{{ $aula->kapasitas }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm pr-16" 
                                    required>
                                <span class="absolute right-4 top-2.5 text-xs text-gray-400 font-medium">Orang</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Maksimal {{ $aula->kapasitas }} orang</p>
                        </div>

                        <!-- ============================================ -->
                        <!-- KALENDER INTERAKTIF                         -->
                        <!-- ============================================ -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Tanggal & Waktu</label>
                            
                            <!-- Navigasi Bulan -->
                            <div class="flex items-center justify-between mb-3">
                                <a href="?bulan={{ $bulan - 1 }}&tahun={{ $tahun }}" 
                                    class="p-2 rounded-lg hover:bg-gray-100 transition text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </a>
                                <span class="text-lg font-semibold text-gray-900">{{ $namaBulan[$bulan] }} {{ $tahun }}</span>
                                <a href="?bulan={{ $bulan + 1 }}&tahun={{ $tahun }}" 
                                    class="p-2 rounded-lg hover:bg-gray-100 transition text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>

                            <!-- Header Hari -->
                            <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-500 mb-1">
                                <div>M</div><div>S</div><div>S</div><div>R</div><div>K</div><div>J</div><div>S</div>
                            </div>

                            <!-- Grid Tanggal -->
                            <div class="grid grid-cols-7 gap-1">
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
                                        class="aspect-square rounded-lg text-sm font-medium transition
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

                            <!-- Legend -->
                            <div class="flex items-center justify-center gap-4 mt-3 text-xs">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-indigo-600"></span>
                                    <span class="text-gray-600">Dipilih</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                    <span class="text-gray-600">Terbooking</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full border border-gray-300 bg-white"></span>
                                    <span class="text-gray-600">Tersedia</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sesi Waktu -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Sesi Waktu</label>
                            <div class="space-y-2">
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="sesi_waktu" value="pagi" class="w-4 h-4 text-indigo-600" required>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-700">Sesi Pagi</p>
                                        <p class="text-xs text-gray-400">08:00 - 12:00 WIB</p>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="sesi_waktu" value="siang" class="w-4 h-4 text-indigo-600">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-700">Sesi Siang</p>
                                        <p class="text-xs text-gray-400">13:00 - 17:00 WIB</p>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="sesi_waktu" value="seharian" class="w-4 h-4 text-indigo-600">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-700">Sehari Penuh</p>
                                        <p class="text-xs text-gray-400">08:00 - 17:00 WIB</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Catatan (Opsional)</label>
                            <textarea name="catatan" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" placeholder="Tambahkan catatan jika diperlukan...">{{ old('catatan') }}</textarea>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="w-full px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                            Booking Sekarang
                        </button>

                        <p class="text-xs text-gray-400 text-center mt-3">
                            Dengan mengikuti, Anda menyetujui syarat & ketentuan peraturan yang berlaku.
                        </p>
                    </form>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
    function selectDate(element, date) {
        // Hapus selected dari semua tanggal
        document.querySelectorAll('.grid-cols-7 button[data-date]').forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white', 'ring-2', 'ring-indigo-300');
            if (!btn.disabled && !btn.classList.contains('bg-red-500')) {
                btn.classList.add('hover:bg-gray-100', 'text-gray-700');
            }
        });
        
        // Tambahkan selected ke yang dipilih
        element.classList.add('bg-indigo-600', 'text-white', 'ring-2', 'ring-indigo-300');
        element.classList.remove('hover:bg-gray-100', 'text-gray-700');
        
        // Set hidden input
        document.getElementById('tanggal_booking_hidden').value = date;
    }

    // Cek ketersediaan saat sesi berubah
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
        
        // Pilih tanggal pertama yang tersedia secara default
        const firstAvailable = document.querySelector('.grid-cols-7 button:not([disabled])');
        if (firstAvailable) {
            firstAvailable.click();
        }
    });
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