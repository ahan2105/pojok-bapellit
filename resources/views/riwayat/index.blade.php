@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Riwayat Booking aula')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-12 py-8" x-data="riwayatManager()" x-init="initPage()">
    
    <!-- Header -->
    <div class="mb-8" x-show="showPage" 
         x-transition:enter="transition ease-out duration-700" 
         x-transition:enter-start="opacity-0 -translate-x-8" 
         x-transition:enter-end="opacity-100 translate-x-0">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            {{ $isAdmin ? 'Riwayat & Log Aktivitas' : 'Riwayat Booking Saya' }}
        </h1>
        <p class="text-base text-gray-500 mt-2 leading-relaxed max-w-3xl">
            {{ $isAdmin 
                ? 'Kelola dan pantau seluruh riwayat pengajuan booking aula dalam satu panel kendali terpadu.' 
                : 'Lihat dan pantau seluruh riwayat pengajuan booking aula yang pernah Anda lakukan.' }}
        </p>
    </div>



    <!-- Filter & Search Bar -->
    <div class="flex flex-col md:flex-row gap-4 mb-8" x-show="showPage" 
         x-transition:enter="transition ease-out duration-700 delay-200" 
         x-transition:enter-start="opacity-0 translate-y-4" 
         x-transition:enter-end="opacity-100 translate-y-0">
        <!-- Search -->
        <div class="relative flex-1 group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-500">
                <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" 
                x-model="searchQuery"
                placeholder="Cari riwayat booking..." 
                class="w-full pl-11 pr-4 py-3 rounded-2xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:outline-none text-sm bg-white shadow-sm hover:shadow-md transition-all duration-300 placeholder-gray-400">
        </div>
    </div>

    <!-- ============================================ -->
    <!-- DESKTOP TABLE VIEW (Hidden on Mobile)        -->
    <!-- ============================================ -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200/80 overflow-hidden hidden md:block"
         x-show="showPage" 
         x-transition:enter="transition ease-out duration-700 delay-300" 
         x-transition:enter-start="opacity-0 translate-y-8" 
         x-transition:enter-end="opacity-100 translate-y-0">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-widest">Aula</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-widest">Pemohon</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-widest">Jadwal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-widest">Keperluan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/80">
                    @forelse($bookings as $booking)
                        @php
                            $sesiLabels = [
                                'pagi' => 'Pagi (08:00-12:00)',
                                'siang' => 'Siang (13:00-17:00)',
                                'seharian' => 'Seharian (08:00-17:00)'
                            ];
                            $statusConfig = [
                                'pending' => ['label' => 'Menunggu', 'dot' => 'bg-amber-500', 'bg' => 'bg-amber-50', 'text' => 'text-amber-700 ring-1 ring-inset ring-amber-600/20'],
                                'approved' => ['label' => 'Disetujui', 'dot' => 'bg-emerald-500', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700 ring-1 ring-inset ring-emerald-600/20'],
                                'rejected' => ['label' => 'Ditolak', 'dot' => 'bg-rose-500', 'bg' => 'bg-rose-50', 'text' => 'text-rose-700 ring-1 ring-inset ring-rose-600/20'],
                                'canceled' => ['label' => 'Dibatalkan', 'dot' => 'bg-rose-500', 'bg' => 'bg-rose-50', 'text' => 'text-rose-700 ring-1 ring-inset ring-rose-600/20'],
                                'completed' => ['label' => 'Selesai', 'dot' => 'bg-slate-400', 'bg' => 'bg-slate-50', 'text' => 'text-slate-700 ring-1 ring-inset ring-slate-600/20'],
                            ];
                            $status = $statusConfig[$booking->status] ?? ['label' => $booking->status, 'dot' => 'bg-gray-400', 'bg' => 'bg-gray-50', 'text' => 'text-gray-700 ring-1 ring-inset ring-gray-600/20'];
                            
                            $aulaFoto = null;
                            if ($booking->aula && !empty($booking->aula->foto_urls)) {
                                $aulaFoto = $booking->aula->foto_urls[0];
                            }
                            $aulaImageFallback = 'https://ui-avatars.com/api/?name='.urlencode($booking->aula->nama ?? 'Aula').'&background=e0e7ff&color=4f46e5&size=128&font-size=0.33';
                            $aulaImage = $aulaFoto ?: $aulaImageFallback;

                            $userAvatarFallback = 'https://ui-avatars.com/api/?name='.urlencode($booking->user->name ?? 'User').'&background=random&color=fff&size=128';
                            $userAvatar = ($booking->user && $booking->user->profile_photo) 
                                ? asset('storage/' . $booking->user->profile_photo) 
                                : $userAvatarFallback;
                            
                            $searchString = strtolower(($booking->aula->nama ?? '') . ' ' . ($booking->user->name ?? '') . ' ' . ($booking->keperluan ?? ''));
                        @endphp
                        <tr x-show="matchesSearch('{{ addslashes($searchString) }}')"
                            x-transition:enter="transition ease-out duration-700 delay-500"
                            x-transition:enter-start="opacity-0 translate-y-8"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex items-start gap-3">
                                    <img src="{{ $aulaImage }}" alt="Foto Aula" class="w-10 h-10 rounded-xl object-cover flex-shrink-0 ring-1 ring-inset ring-indigo-500/20 shadow-sm" loading="lazy">
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm md:text-base">{{ $booking->aula->nama ?? 'Aula' }}</p>
                                        <p class="text-xs md:text-sm text-gray-500 mt-0.5">{{ $booking->aula->lokasi ?? 'Lokasi belum diisi' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-start gap-3">
                                    <img src="{{ $userAvatar }}" alt="Avatar User" class="w-10 h-10 rounded-full object-cover shadow-sm flex-shrink-0" loading="lazy">
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm md:text-base">{{ $booking->user->name ?? 'User' }}</p>
                                        <p class="text-xs md:text-sm text-gray-500 mt-0.5">{{ $booking->nama_penanggung_jawab ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-semibold text-gray-900 text-sm md:text-base">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</p>
                                <p class="text-xs md:text-sm text-gray-500 mt-0.5">{{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-medium text-gray-700 text-sm line-clamp-2 max-w-xs">{{ $booking->keperluan }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }}"></span>
                                    {{ $status['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">
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
                                        class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 rounded-lg transition-colors focus:ring-2 focus:ring-indigo-500/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </button>

                                    @if($booking->status == 'pending')
                                        <form action="{{ route('riwayat.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 border border-rose-100 hover:bg-rose-100 rounded-lg transition-colors focus:ring-2 focus:ring-rose-500/20">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-24 text-center">
                                <div class="w-24 h-24 mx-auto mb-6 bg-slate-50 rounded-full flex items-center justify-center ring-8 ring-slate-50/50">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-xl font-bold text-gray-900 mb-2">Belum Ada Riwayat Booking</p>
                                <p class="text-base text-gray-500 mb-8 max-w-sm mx-auto">Anda belum pernah melakukan booking aula. Silakan buat pengajuan booking pertama Anda.</p>
                                <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 hover:shadow-lg transition-all text-base font-semibold shadow-indigo-500/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
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
    <!-- MOBILE CARD VIEW (Hidden on Desktop)         -->
    <!-- ============================================ -->
    <div class="md:hidden space-y-4"
         x-show="showPage" 
         x-transition:enter="transition ease-out duration-700 delay-300" 
         x-transition:enter-start="opacity-0 translate-y-8" 
         x-transition:enter-end="opacity-100 translate-y-0">
        @forelse($bookings as $booking)
            @php
                $sesiLabels = [
                    'pagi' => 'Pagi (08:00-12:00)',
                    'siang' => 'Siang (13:00-17:00)',
                    'seharian' => 'Seharian (08:00-17:00)'
                ];
                $statusConfig = [
                    'pending' => ['label' => 'Menunggu', 'dot' => 'bg-amber-500', 'bg' => 'bg-amber-50', 'text' => 'text-amber-700 ring-1 ring-inset ring-amber-600/20'],
                    'approved' => ['label' => 'Disetujui', 'dot' => 'bg-emerald-500', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700 ring-1 ring-inset ring-emerald-600/20'],
                    'rejected' => ['label' => 'Ditolak', 'dot' => 'bg-rose-500', 'bg' => 'bg-rose-50', 'text' => 'text-rose-700 ring-1 ring-inset ring-rose-600/20'],
                    'canceled' => ['label' => 'Dibatalkan', 'dot' => 'bg-rose-500', 'bg' => 'bg-rose-50', 'text' => 'text-rose-700 ring-1 ring-inset ring-rose-600/20'],
                    'completed' => ['label' => 'Selesai', 'dot' => 'bg-slate-500', 'bg' => 'bg-slate-50', 'text' => 'text-slate-700 ring-1 ring-inset ring-slate-600/20'],
                ];
                $status = $statusConfig[$booking->status] ?? ['label' => $booking->status, 'dot' => 'bg-gray-400', 'bg' => 'bg-gray-50', 'text' => 'text-gray-700 ring-1 ring-inset ring-gray-600/20'];
                
                $aulaFoto = null;
                if ($booking->aula && !empty($booking->aula->foto_urls)) {
                    $aulaFoto = $booking->aula->foto_urls[0];
                }
                $aulaImageFallback = 'https://ui-avatars.com/api/?name='.urlencode($booking->aula->nama ?? 'Aula').'&background=e0e7ff&color=4f46e5&size=128&font-size=0.33';
                $aulaImage = $aulaFoto ?: $aulaImageFallback;

                $userAvatarFallback = 'https://ui-avatars.com/api/?name='.urlencode($booking->user->name ?? 'User').'&background=random&color=fff&size=128';
                $userAvatar = ($booking->user && $booking->user->profile_photo) 
                    ? asset('storage/' . $booking->user->profile_photo) 
                    : $userAvatarFallback;
                
                $searchString = strtolower(($booking->aula->nama ?? '') . ' ' . ($booking->user->name ?? '') . ' ' . ($booking->keperluan ?? ''));
            @endphp
            
            <div x-show="matchesSearch('{{ addslashes($searchString) }}')"
                 x-transition:enter="transition ease-out duration-700 delay-500"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-5 hover:shadow-md transition-shadow duration-300">
                <!-- Header Card: Aula + Status -->
                <div class="flex items-start justify-between gap-3 mb-5">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="{{ $aulaImage }}" alt="Foto Aula" class="w-11 h-11 rounded-xl object-cover flex-shrink-0 ring-1 ring-inset ring-indigo-500/20 shadow-sm" loading="lazy">
                        <div class="min-w-0">
                            <p class="font-bold text-gray-900 text-base truncate">{{ $booking->aula->nama ?? 'Aula' }}</p>
                            <p class="text-xs text-gray-500 truncate mt-0.5">{{ $booking->aula->lokasi ?? 'Lokasi belum diisi' }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold flex-shrink-0 {{ $status['bg'] }} {{ $status['text'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }}"></span>
                        {{ $status['label'] }}
                    </span>
                </div>

                <!-- Info Grid -->
                <div class="space-y-3 mb-4">
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Tanggal</span>
                        <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Sesi</span>
                        <span class="text-sm font-medium text-gray-700">{{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Peserta</span>
                        <span class="text-sm font-medium text-gray-700">{{ $booking->jumlah_peserta }} Orang</span>
                    </div>
                    <div class="py-2 border-b border-gray-50">
                        <span class="text-xs font-semibold text-gray-500 uppercase block mb-1">Keperluan</span>
                        <p class="text-sm text-gray-900 leading-relaxed">{{ $booking->keperluan }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 pt-1">
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
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-indigo-700 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 rounded-xl transition-colors focus:ring-2 focus:ring-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Detail
                    </button>

                    @if($booking->status == 'pending')
                        <form action="{{ route('riwayat.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-rose-700 bg-rose-50 border border-rose-100 hover:bg-rose-100 rounded-xl transition-colors focus:ring-2 focus:ring-rose-500/20">
                                Batalkan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/80 p-10 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                <div class="w-20 h-20 mx-auto mb-5 bg-slate-50 rounded-full flex items-center justify-center ring-8 ring-slate-50/50">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-lg font-bold text-gray-900 mb-1">Belum Ada Riwayat</p>
                <p class="text-sm text-gray-500 mb-6">Anda belum pernah melakukan booking aula.</p>
                <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center gap-2 w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all text-sm font-semibold shadow-md shadow-indigo-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Booking Sekarang
                </a>
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
        
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeDetail()"></div>
        
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
                <div class="px-6 sm:px-8 py-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
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
                        <div class="sm:col-span-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Keperluan</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5 leading-relaxed" x-text="detail.keperluan"></p>
                        </div>
                        <div class="sm:col-span-2" x-show="detail.catatan && detail.catatan !== ''">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5 leading-relaxed" x-text="detail.catatan"></p>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="flex items-center justify-end gap-3 px-6 sm:px-8 py-5 sm:py-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl sticky bottom-0">
                    <button @click="closeDetail()" 
                        class="w-full sm:w-auto px-6 py-2.5 text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition">
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
            searchQuery: null,
            showPage: false,
            initPage() {
                setTimeout(() => {
                    this.showPage = true;
                    // Trigger enter animation on rows after page wrapper is visible
                    setTimeout(() => {
                        this.searchQuery = '';
                    }, 50);
                }, 50);
            },
            matchesSearch(text) {
                if (this.searchQuery === null) return false;
                if (this.searchQuery.trim() === '') return true;
                return text.includes(this.searchQuery.toLowerCase().trim());
            },
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