@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Kelola Booking')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-12 py-8" x-data="kelolaBookingManager()">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Booking</h1>
            <p class="text-base text-gray-600 mt-1">Kelola semua booking dari pengguna</p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Tombol Bulk Delete (Hidden by default) -->
            <button type="button"
                    id="btn-bulk-delete-booking"
                    onclick="confirmBulkDeleteBooking()"
                    class="hidden inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-base font-medium shadow-sm animate-pulse">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus Terpilih (<span id="selected-booking-count">0</span>)
            </button>

            <a href="{{ route('booking.index') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-base font-medium shadow-sm">
                Booking Aula
            </a>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Booking</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Menunggu</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $statistics['pending'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Disetujui</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $statistics['approved'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Hari Ini</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $statistics['today'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Toolbar Pilih Semua -->
    @if(isset($bookings) && $bookings->count() > 0)
    <div class="mb-4 flex items-center justify-between bg-white p-3 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center gap-3">
            <input type="checkbox" 
                   id="check-all-bookings" 
                   class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 cursor-pointer"
                   onchange="toggleCheckAllBookings(this)">
            <label for="check-all-bookings" class="text-sm font-semibold text-gray-700 cursor-pointer select-none">
                Pilih Semua Booking di Halaman Ini
            </label>
        </div>
        <span class="text-xs text-gray-500 hidden sm:block">
            Centang booking yang ingin dihapus, lalu klik tombol <strong>Hapus Terpilih</strong> di atas.
        </span>
    </div>
    @endif

    <!-- ============================================ -->
    <!-- DESKTOP TABLE VIEW (Hidden on Mobile)        -->
    <!-- ============================================ -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hidden md:block">
        <div class="overflow-x-auto">
            <table class="w-full text-base">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700 w-10">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" disabled>
                        </th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">User</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Aula</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Sesi</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        @php
                            $sesiLabels = [
                                'pagi' => 'Pagi (08:00-12:00)',
                                'siang' => 'Siang (13:00-17:00)',
                                'seharian' => 'Seharian (08:00-17:00)'
                            ];
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'canceled' => 'bg-gray-100 text-gray-800',
                                'completed' => 'bg-blue-100 text-blue-800'
                            ];
                            $statusLabels = [
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'canceled' => 'Dibatalkan',
                                'completed' => 'Selesai'
                            ];
                        @endphp
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" 
                                       class="booking-checkbox w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 cursor-pointer" 
                                       value="{{ $booking->id }}"
                                       onchange="updateSelectedBookingCount()">
                            </td>
                            <td class="px-6 py-4 text-gray-700">#{{ $booking->id }}</td>
                            <td class="px-6 py-4 text-gray-900 font-medium">{{ $booking->user->name ?? 'User' }}</td>
                            <td class="px-6 py-4 text-gray-900 font-medium">{{ $booking->aula->nama ?? 'Aula' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$booking->status] ?? $booking->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2 flex-wrap">
                                    @if($booking->status == 'pending')
                                        <form action="{{ route('admin.kelolabooking.approve', $booking->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition">
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.kelolabooking.reject', $booking->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition">
                                                Tolak
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($booking->status == 'approved')
                                        <form action="{{ route('admin.kelolabooking.complete', $booking->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition">
                                                Selesaikan
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <button type="button" 
                                        @click="openDetail({
                                            id: {{ $booking->id }},
                                            user: '{{ addslashes($booking->user->name ?? 'User') }}',
                                            email: '{{ addslashes($booking->user->email ?? '-') }}',
                                            aula: '{{ addslashes($booking->aula->nama ?? 'Aula') }}',
                                            tanggal: '{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d F Y') }}',
                                            sesi: '{{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}',
                                            peserta: '{{ $booking->jumlah_peserta }} Orang',
                                            penanggung_jawab: '{{ addslashes($booking->nama_penanggung_jawab) }}',
                                            keperluan: '{{ addslashes($booking->keperluan) }}',
                                            catatan: '{{ addslashes($booking->catatan ?? '') }}',
                                            status: '{{ $booking->status }}',
                                            status_label: '{{ $statusLabels[$booking->status] ?? $booking->status }}',
                                            status_color: '{{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}',
                                            approve_url: '{{ route('admin.kelolabooking.approve', $booking->id) }}',
                                            reject_url: '{{ route('admin.kelolabooking.reject', $booking->id) }}',
                                            complete_url: '{{ route('admin.kelolabooking.complete', $booking->id) }}',
                                            destroy_url: '{{ route('admin.kelolabooking.destroy', $booking->id) }}'
                                        })"
                                        class="px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200 rounded-md transition">
                                        Detail
                                    </button>
                                    
                                    <form action="{{ route('admin.kelolabooking.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus booking ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 rounded-md transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-gray-500">
                                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-700 mb-1">Belum Ada Data Booking</p>
                                <p class="text-base text-gray-500">Belum ada pengguna yang melakukan booking.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($bookings) && method_exists($bookings, 'links'))
            <div class="p-6 border-t">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    <!-- ============================================ -->
    <!-- MOBILE CARD VIEW (Hidden on Desktop)         -->
    <!-- ============================================ -->
    <div class="md:hidden space-y-4">
        @forelse($bookings as $booking)
            @php
                $sesiLabels = [
                    'pagi' => 'Pagi (08:00-12:00)',
                    'siang' => 'Siang (13:00-17:00)',
                    'seharian' => 'Seharian (08:00-17:00)'
                ];
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'approved' => 'bg-green-100 text-green-800',
                    'rejected' => 'bg-red-100 text-red-800',
                    'canceled' => 'bg-gray-100 text-gray-800',
                    'completed' => 'bg-blue-100 text-blue-800'
                ];
                $statusLabels = [
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'canceled' => 'Dibatalkan',
                    'completed' => 'Selesai'
                ];
            @endphp
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 relative">
                <!-- Checkbox Mobile -->
                <div class="absolute top-4 right-4">
                    <input type="checkbox" 
                           class="booking-checkbox w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 cursor-pointer" 
                           value="{{ $booking->id }}"
                           onchange="updateSelectedBookingCount()">
                </div>

                <!-- Header Card -->
                <div class="pr-8 mb-3">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-bold text-gray-400">#{{ $booking->id }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$booking->status] ?? $booking->status }}
                        </span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $booking->aula->nama ?? 'Aula' }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $booking->user->name ?? 'User' }}</p>
                </div>

                <!-- Info Grid -->
                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="text-gray-500">Sesi</span>
                        <span class="font-medium text-gray-900">{{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="text-gray-500">Peserta</span>
                        <span class="font-medium text-gray-900">{{ $booking->jumlah_peserta }} Orang</span>
                    </div>
                </div>

                <!-- Action Buttons Mobile -->
                <div class="flex flex-col gap-2 pt-2">
                    <button type="button" 
                        @click="openDetail({
                            id: {{ $booking->id }},
                            user: '{{ addslashes($booking->user->name ?? 'User') }}',
                            email: '{{ addslashes($booking->user->email ?? '-') }}',
                            aula: '{{ addslashes($booking->aula->nama ?? 'Aula') }}',
                            tanggal: '{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d F Y') }}',
                            sesi: '{{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}',
                            peserta: '{{ $booking->jumlah_peserta }} Orang',
                            penanggung_jawab: '{{ addslashes($booking->nama_penanggung_jawab) }}',
                            keperluan: '{{ addslashes($booking->keperluan) }}',
                            catatan: '{{ addslashes($booking->catatan ?? '') }}',
                            status: '{{ $booking->status }}',
                            status_label: '{{ $statusLabels[$booking->status] ?? $booking->status }}',
                            status_color: '{{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}',
                            approve_url: '{{ route('admin.kelolabooking.approve', $booking->id) }}',
                            reject_url: '{{ route('admin.kelolabooking.reject', $booking->id) }}',
                            complete_url: '{{ route('admin.kelolabooking.complete', $booking->id) }}',
                            destroy_url: '{{ route('admin.kelolabooking.destroy', $booking->id) }}'
                        })"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat Detail
                    </button>

                    <div class="grid grid-cols-2 gap-2">
                        @if($booking->status == 'pending')
                            <form action="{{ route('admin.kelolabooking.approve', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('admin.kelolabooking.reject', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                                    Tolak
                                </button>
                            </form>
                        @endif
                        
                        @if($booking->status == 'approved')
                            <form action="{{ route('admin.kelolabooking.complete', $booking->id) }}" method="POST" class="col-span-2">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                    Selesaikan
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.kelolabooking.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus booking ini?')" class="col-span-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2 text-xs font-medium text-red-700 bg-red-50 border border-red-100 hover:bg-red-100 rounded-lg transition">
                                Hapus Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-lg font-medium text-gray-700 mb-1">Belum Ada Data Booking</p>
                <p class="text-sm text-gray-500">Belum ada pengguna yang melakukan booking.</p>
            </div>
        @endforelse

        <!-- Pagination Mobile -->
        @if(isset($bookings) && method_exists($bookings, 'links'))
            <div class="pt-4">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    <!-- ============================================ -->
    <!-- MODAL DETAIL BOOKING                         -->
    <!-- ============================================ -->
    <div x-show="showDetailModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeDetail()"></div>
        
        <!-- Modal Content -->
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div x-show="showDetailModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.away="closeDetail()"
                 class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full relative z-10 max-h-[90vh] overflow-y-auto mx-2">
                
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 sm:px-8 py-5 sm:py-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">
                            Detail Booking <span x-text="'#' + detail.id"></span>
                        </h3>
                        <span class="inline-block mt-2 px-4 py-1.5 rounded-full text-sm font-semibold"
                              :class="detail.status_color"
                              x-text="detail.status_label"></span>
                    </div>
                    <button @click="closeDetail()" 
                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Body Modal -->
                <div class="px-6 sm:px-8 py-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">User</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.user"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5 break-all" x-text="detail.email"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Aula</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.aula"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.tanggal"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sesi</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.sesi"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah Peserta</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.peserta"></p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Penanggung Jawab</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.penanggung_jawab"></p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Keperluan</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5 leading-relaxed" x-text="detail.keperluan"></p>
                        </div>
                        <div class="sm:col-span-2" x-show="detail.catatan && detail.catatan !== ''">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Catatan</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5 leading-relaxed" x-text="detail.catatan"></p>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal - Tombol Aksi -->
                <div class="flex items-center justify-end gap-3 px-6 sm:px-8 py-5 sm:py-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex-wrap sticky bottom-0">
                    <button @click="closeDetail()" 
                        class="w-full sm:w-auto px-6 py-2.5 text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition">
                        Tutup
                    </button>
                    
                    <!-- Tombol Aksi dinamis berdasarkan status -->
                    <template x-if="detail.status === 'pending'">
                        <div class="flex gap-3 w-full sm:w-auto">
                            <form :action="detail.approve_url" method="POST" class="inline flex-1 sm:flex-none">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 text-base font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                                    Setujui
                                </button>
                            </form>
                            <form :action="detail.reject_url" method="POST" class="inline flex-1 sm:flex-none">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 text-base font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </template>
                    
                    <template x-if="detail.status === 'approved'">
                        <form :action="detail.complete_url" method="POST" class="inline w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                Selesaikan
                            </button>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- Logic Bulk Delete Booking ---
    let selectedBookingIds = new Set();

    function updateSelectedBookingCount() {
        const checkboxes = document.querySelectorAll('.booking-checkbox:checked');
        selectedBookingIds.clear();
        
        checkboxes.forEach(cb => {
            selectedBookingIds.add(cb.value);
        });

        const count = selectedBookingIds.size;
        const btn = document.getElementById('btn-bulk-delete-booking');
        const countSpan = document.getElementById('selected-booking-count');
        
        countSpan.textContent = count;
        
        if (count > 0) {
            btn.classList.remove('hidden');
        } else {
            btn.classList.add('hidden');
        }
    }

    function toggleCheckAllBookings(source) {
        const checkboxes = document.querySelectorAll('.booking-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = source.checked;
        });
        updateSelectedBookingCount();
    }

    function confirmBulkDeleteBooking() {
        if (selectedBookingIds.size === 0) return;

        Swal.fire({
            title: 'Hapus Booking Terpilih?',
            html: `Anda akan menghapus <strong>${selectedBookingIds.size} booking</strong> secara permanen.<br><span class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const ids = Array.from(selectedBookingIds);
                
                Swal.fire({
                    title: 'Sedang Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('{{ route("admin.kelolabooking.bulk-destroy") }}', {
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
                            text: data.message || 'Booking terpilih telah dihapus.',
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

    // --- Logic Modal Detail ---
    function kelolaBookingManager() {
        return {
            showDetailModal: false,
            detail: {
                id: '',
                user: '',
                email: '',
                aula: '',
                tanggal: '',
                sesi: '',
                peserta: '',
                penanggung_jawab: '',
                keperluan: '',
                catatan: '',
                status: '',
                status_label: '',
                status_color: '',
                approve_url: '',
                reject_url: '',
                complete_url: '',
                destroy_url: ''
            },
            openDetail(data) {
                this.detail = data;
                this.showDetailModal = true;
                document.body.style.overflow = 'hidden';
            },
            closeDetail() {
                this.showDetailModal = false;
                document.body.style.overflow = '';
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection