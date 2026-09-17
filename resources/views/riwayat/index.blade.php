@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Riwayat Booking Saya')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-12 py-8" x-data="riwayatManager()">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            {{ $isAdmin ? 'Riwayat & Log Aktivitas' : 'Riwayat Booking Saya' }}
        </h1>
        <p class="text-base text-gray-500 mt-2 leading-relaxed max-w-3xl">
            {{ $isAdmin 
                ? 'Kelola dan pantau seluruh riwayat pengajuan booking aula dalam satu panel kendali terpadu.' 
                : 'Lihat dan pantau seluruh riwayat pengajuan booking aula yang pernah Anda lakukan.' }}
        </p>
    </div>

    <!-- Tab Navigation (Hanya Booking Aula) -->
    <div class="border-b border-gray-200 mb-6">
        <div class="flex gap-8">
            <button class="pb-3 text-base font-semibold text-indigo-600 border-b-2 border-indigo-600">
                Booking Aula
            </button>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <!-- Search -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" 
                x-model="searchQuery"
                placeholder="Cari riwayat booking..." 
                class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none text-base bg-white shadow-sm">
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aula</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemohon</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keperluan</th>
                        <th class="px-6 py-5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
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
                            $statusConfig = [
                                'pending' => ['label' => 'Menunggu', 'dot' => 'bg-yellow-500', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-700'],
                                'approved' => ['label' => 'Disetujui', 'dot' => 'bg-green-500', 'bg' => 'bg-green-50', 'text' => 'text-green-700'],
                                'rejected' => ['label' => 'Ditolak', 'dot' => 'bg-red-500', 'bg' => 'bg-red-50', 'text' => 'text-red-700'],
                                'canceled' => ['label' => 'Dibatalkan', 'dot' => 'bg-red-500', 'bg' => 'bg-red-50', 'text' => 'text-red-700'],
                                'completed' => ['label' => 'Selesai', 'dot' => 'bg-slate-400', 'bg' => 'bg-slate-100', 'text' => 'text-slate-700'],
                            ];
                            $status = $statusConfig[$booking->status] ?? ['label' => $booking->status, 'dot' => 'bg-gray-400', 'bg' => 'bg-gray-50', 'text' => 'text-gray-700'];
                            
                            // Initial untuk avatar
                            $userInitial = strtoupper(substr($booking->user->name ?? 'U', 0, 1));
                        @endphp
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <!-- AULA -->
                            <td class="px-6 py-5">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-base">{{ $booking->aula->nama ?? 'Aula' }}</p>
                                        <p class="text-sm text-gray-400 mt-0.5">{{ $booking->aula->lokasi ?? 'Lokasi belum diisi' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- PEMOHON -->
                            <td class="px-6 py-5">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-semibold text-sm">{{ $userInitial }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-base">{{ $booking->user->name ?? 'User' }}</p>
                                        <p class="text-sm text-gray-400 mt-0.5">{{ $booking->nama_penanggung_jawab ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- JADWAL -->
                            <td class="px-6 py-5">
                                <p class="font-semibold text-gray-900 text-base">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</p>
                                <p class="text-sm text-gray-400 mt-0.5">{{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}</p>
                            </td>

                            <!-- KEPERLUAN -->
                            <td class="px-6 py-5">
                                <p class="font-semibold text-gray-900 text-base">{{ $booking->keperluan }}</p>
                            </td>

                            <!-- STATUS -->
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium {{ $status['bg'] }} {{ $status['text'] }}">
                                    <span class="w-2 h-2 rounded-full {{ $status['dot'] }}"></span>
                                    {{ $status['label'] }}
                                </span>
                            </td>

                            <!-- AKSI -->
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" 
                                        @click="openDetail({
                                            id: {{ $booking->id }},
                                            user: '{{ addslashes($booking->user->name ?? 'User') }}',
                                            email: '{{ addslashes($booking->user->email ?? '-') }}',
                                            aula: '{{ addslashes($booking->aula->nama ?? 'Aula') }}',
                                            lokasi: '{{ addslashes($booking->aula->lokasi ?? '-') }}',
                                            tanggal: '{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d F Y') }}',
                                            sesi: '{{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}',
                                            peserta: '{{ $booking->jumlah_peserta }} Orang',
                                            penanggung_jawab: '{{ addslashes($booking->nama_penanggung_jawab) }}',
                                            keperluan: '{{ addslashes($booking->keperluan) }}',
                                            catatan: '{{ addslashes($booking->catatan ?? '') }}',
                                            status: '{{ $booking->status }}',
                                            status_label: '{{ $status['label'] }}',
                                            status_dot: '{{ $status['dot'] }}',
                                            status_bg: '{{ $status['bg'] }}',
                                            status_text: '{{ $status['text'] }}'
                                        })"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </button>

                                    @if($booking->status == 'pending')
                                        <form action="{{ route('riwayat.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-100 hover:bg-red-100 rounded-lg transition">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-700 mb-1">Belum Ada Riwayat Booking</p>
                                <p class="text-base text-gray-500 mb-6">Anda belum pernah melakukan booking aula.</p>
                                <a href="{{ route('booking.index') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-base font-medium">
                                    Booking Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($bookings) && method_exists($bookings, 'links'))
            <div class="px-6 py-5 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Menampilkan {{ $bookings->firstItem() ?? 0 }}-{{ $bookings->lastItem() ?? 0 }} dari {{ $bookings->total() }} pengajuan
                </p>
                <div>
                    {{ $bookings->links() }}
                </div>
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
        
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeDetail()"></div>
        
        <div class="flex items-center justify-center min-h-screen p-6">
            <div x-show="showDetailModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.away="closeDetail()"
                 class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full relative z-10 max-h-[90vh] overflow-y-auto">
                
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-8 py-6 border-b border-gray-200">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            Detail Booking <span x-text="'#' + detail.id"></span>
                        </h3>
                        <span class="inline-flex items-center gap-2 mt-2 px-3 py-1.5 rounded-full text-sm font-medium"
                              :class="detail.status_bg + ' ' + detail.status_text">
                            <span class="w-2 h-2 rounded-full" :class="detail.status_dot"></span>
                            <span x-text="detail.status_label"></span>
                        </span>
                    </div>
                    <button @click="closeDetail()" 
                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Body Modal -->
                <div class="px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">User</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.user"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5 break-all" x-text="detail.email"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Aula</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.aula"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Lokasi</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.lokasi"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.tanggal"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sesi</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.sesi"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Peserta</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.peserta"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Penanggung Jawab</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.penanggung_jawab"></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Keperluan</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.keperluan"></p>
                        </div>
                        <div class="md:col-span-2" x-show="detail.catatan && detail.catatan !== ''">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" x-text="detail.catatan"></p>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="flex items-center justify-end gap-3 px-8 py-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                    <button @click="closeDetail()" 
                        class="px-6 py-2.5 text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function riwayatManager() {
        return {
            searchQuery: '',
            showDetailModal: false,
            detail: {
                id: '',
                user: '',
                email: '',
                aula: '',
                lokasi: '',
                tanggal: '',
                sesi: '',
                peserta: '',
                penanggung_jawab: '',
                keperluan: '',
                catatan: '',
                status: '',
                status_label: '',
                status_dot: '',
                status_bg: '',
                status_text: ''
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