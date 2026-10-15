@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Kelola Booking')

@section('content')

<style>
    .bapelit-hero {
        background: linear-gradient(135deg, #0f2d5e 0%, #1a4a8a 60%, #1e5799 100%);
        position: relative;
        overflow: hidden;
    }
    .bapelit-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background-image: radial-gradient(circle at 80% 20%, rgba(234,179,8,0.18) 0%, transparent 50%),
                          radial-gradient(circle at 10% 80%, rgba(255,255,255,0.06) 0%, transparent 40%);
    }
    .bapelit-hero > * { position: relative; z-index: 1; }

    /* Tombol hero */
    .bapelit-hero a.btn-hero-export,
    .bapelit-hero a.btn-hero-export:link,
    .bapelit-hero a.btn-hero-export:visited,
    .bapelit-hero a.btn-hero-export:hover,
    .bapelit-hero a.btn-hero-export:focus,
    .bapelit-hero a.btn-hero-export:active {
        background-color: rgba(255,255,255,0.14) !important;
        color: #ffffff !important;
        text-decoration: none !important;
        border: 1px solid rgba(255,255,255,0.28) !important;
        backdrop-filter: blur(8px);
        transition: all 0.2s ease;
    }
    .bapelit-hero a.btn-hero-export:hover {
        background-color: rgba(16,185,129,0.25) !important;
        border-color: rgba(16,185,129,0.6) !important;
        transform: translateY(-2px);
    }
    .bapelit-hero a.btn-hero-export svg { color: #ffffff !important; stroke: #ffffff !important; }

    .bapelit-hero a.btn-hero-add,
    .bapelit-hero a.btn-hero-add:link,
    .bapelit-hero a.btn-hero-add:visited,
    .bapelit-hero a.btn-hero-add:hover,
    .bapelit-hero a.btn-hero-add:focus,
    .bapelit-hero a.btn-hero-add:active {
        background-color: #facc15 !important;
        background-image: linear-gradient(135deg, #facc15 0%, #eab308 100%) !important;
        color: #1e3a8a !important;
        text-decoration: none !important;
        border: none !important;
        box-shadow: 0 10px 20px -5px rgba(250, 204, 21, 0.45) !important;
        transition: all 0.2s ease;
    }
    .bapelit-hero a.btn-hero-add:hover {
        background-image: linear-gradient(135deg, #fde047 0%, #facc15 100%) !important;
        transform: translateY(-2px);
    }
    .bapelit-hero a.btn-hero-add svg { color: #1e3a8a !important; stroke: #1e3a8a !important; }

    /* Tombol Bulk Delete */
    .btn-bulk-delete {
        background-color: #dc2626 !important;
        color: #ffffff !important;
        box-shadow: 0 8px 18px -6px rgba(220, 38, 38, 0.5);
        animation: pulse-soft 2s infinite;
    }
    .btn-bulk-delete:hover { background-color: #b91c1c !important; }
    @keyframes pulse-soft {
        0%, 100% { box-shadow: 0 8px 18px -6px rgba(220, 38, 38, 0.5); }
        50% { box-shadow: 0 8px 24px -4px rgba(220, 38, 38, 0.75); }
    }

    /* Kartu statistik berwarna */
    .stat-card {
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 24px -10px rgba(15, 45, 94, 0.25);
        transition: transform .18s ease;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card::after {
        content: '';
        position: absolute;
        right: -20px;
        top: -20px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }
    .stat-total  { background: linear-gradient(135deg, #0f2d5e, #1a4a8a); }
    .stat-pending{ background: linear-gradient(135deg, #b45309, #d97706); }
    .stat-approve{ background: linear-gradient(135deg, #16a34a, #15803d); }
    .stat-today  { background: linear-gradient(135deg, #1d4ed8, #2563eb); }

    /* Tabel */
    .booking-table thead th {
        background-color: #f8fafc;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 20px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    .booking-table tbody td {
        padding: 14px 20px;
        font-size: 14px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .booking-table tbody tr:hover { background-color: #f8fafc; }
    .booking-table .row-checkbox {
        width: 18px;
        height: 18px;
        accent-color: #4f46e5;
        cursor: pointer;
    }

    /* Status badge */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }
    .badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: currentColor;
    }
    .badge-pending   { background: #fef3c7; color: #b45309; }
    .badge-approved  { background: #dcfce7; color: #15803d; }
    .badge-rejected  { background: #fee2e2; color: #b91c1c; }
    .badge-canceled  { background: #f1f5f9; color: #475569; }
    .badge-completed { background: #dbeafe; color: #1d4ed8; }

    /* Tombol aksi kecil */
    .btn-mini {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all .15s ease;
    }
    .btn-mini-approve  { background: #dcfce7; color: #15803d; }
    .btn-mini-approve:hover  { background: #16a34a; color: #ffffff; }
    .btn-mini-reject   { background: #fee2e2; color: #b91c1c; }
    .btn-mini-reject:hover   { background: #dc2626; color: #ffffff; }
    .btn-mini-complete { background: #dbeafe; color: #1d4ed8; }
    .btn-mini-complete:hover { background: #2563eb; color: #ffffff; }
    .btn-mini-detail   { background: #e0e7ff; color: #4338ca; }
    .btn-mini-detail:hover   { background: #4f46e5; color: #ffffff; }
    .btn-mini-delete   { background: #fee2e2; color: #b91c1c; }
    .btn-mini-delete:hover   { background: #dc2626; color: #ffffff; }

    /* Modal */
    .modal-header {
        background: linear-gradient(135deg, #0f2d5e 0%, #1a4a8a 100%);
        color: #ffffff;
    }

    [x-cloak] { display: none !important; }
</style>

<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-10 py-6" x-data="kelolaBookingManager()">

    {{-- ===== HERO ===== --}}
    <div class="bapelit-hero text-white rounded-2xl px-6 sm:px-8 py-6 mb-6 shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center flex-shrink-0 ring-1 ring-white/20">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                 M9 5a2 2 0 002 2h2a2 2 0 002-2
                                 M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-semibold tracking-widest text-yellow-400 uppercase leading-none mb-1">ADMIN PANEL</p>
                    <h1 class="text-xl sm:text-2xl font-extrabold leading-tight">Kelola Booking</h1>
                    <p class="text-xs sm:text-sm text-blue-200 mt-0.5">Kelola semua booking aula dari pengguna.</p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Tombol Bulk Delete --}}
                <button type="button"
                        id="btn-bulk-delete-booking"
                        onclick="confirmBulkDeleteBooking()"
                        class="btn-bulk-delete hidden items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus (<span id="selected-booking-count">0</span>)
                </button>

                <a href="{{ route('admin.kelolabooking.export') }}"
                   class="btn-hero-export inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.414 4.414A2 2 0 0119 9.414V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </a>

                <a href="{{ route('booking.index') }}"
                   class="btn-hero-add inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Booking Aula
                </a>
            </div>
        </div>
    </div>

    {{-- ===== STATISTIK ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card stat-total">
            <p class="text-[10px] font-bold tracking-widest uppercase opacity-90">Total Booking</p>
            <p class="text-3xl font-extrabold mt-2">{{ $statistics['total'] ?? 0 }}</p>
            <p class="text-xs opacity-75 mt-1">semua data</p>
        </div>
        <div class="stat-card stat-pending">
            <p class="text-[10px] font-bold tracking-widest uppercase opacity-90">Menunggu</p>
            <p class="text-3xl font-extrabold mt-2">{{ $statistics['pending'] ?? 0 }}</p>
            <p class="text-xs opacity-75 mt-1">perlu ditinjau</p>
        </div>
        <div class="stat-card stat-approve">
            <p class="text-[10px] font-bold tracking-widest uppercase opacity-90">Disetujui</p>
            <p class="text-3xl font-extrabold mt-2">{{ $statistics['approved'] ?? 0 }}</p>
            <p class="text-xs opacity-75 mt-1">siap digunakan</p>
        </div>
        <div class="stat-card stat-today">
            <p class="text-[10px] font-bold tracking-widest uppercase opacity-90">Hari Ini</p>
            <p class="text-3xl font-extrabold mt-2">{{ $statistics['today'] ?? 0 }}</p>
            <p class="text-xs opacity-75 mt-1">booking baru</p>
        </div>
    </div>

    {{-- ===== TOOLBAR PILIH SEMUA ===== --}}
    @if(isset($bookings) && $bookings->count() > 0)
    <div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center gap-3">
            <input type="checkbox"
                   id="check-all-bookings"
                   class="row-checkbox"
                   onchange="toggleCheckAllBookings(this)">
            <label for="check-all-bookings" class="text-sm font-semibold text-gray-700 cursor-pointer select-none">
                Pilih Semua Booking di Halaman Ini
            </label>
        </div>
        <span class="text-xs text-gray-500">
            Centang booking, lalu klik <strong class="text-red-600">Hapus</strong>
        </span>
    </div>
    @endif

    {{-- ===== DESKTOP TABLE ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hidden md:block">
        <div class="overflow-x-auto">
            <table class="w-full booking-table">
                <thead>
                    <tr>
                        <th class="w-12">
                            <input type="checkbox" class="row-checkbox" disabled>
                        </th>
                        <th class="w-12">No.</th>
                        <th>User</th>
                        <th>Aula</th>
                        <th>Tanggal</th>
                        <th>Diajukan</th>
                        <th>Sesi</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
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
                            $statusClass = [
                                'pending' => 'badge-pending',
                                'approved' => 'badge-approved',
                                'rejected' => 'badge-rejected',
                                'canceled' => 'badge-canceled',
                                'completed' => 'badge-completed'
                            ];
                            $statusLabels = [
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'canceled' => 'Dibatalkan',
                                'completed' => 'Selesai'
                            ];
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox"
                                       class="booking-checkbox row-checkbox"
                                       value="{{ $booking->id }}"
                                       onchange="updateSelectedBookingCount()">
                            </td>
                            <td class="text-gray-500 font-medium">{{ $bookings->firstItem() + $loop->index }}</td>
                            <td>
                                <p class="font-semibold text-gray-900">{{ $booking->user->name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->user->email ?? '-' }}</p>
                            </td>
                            <td class="font-semibold text-gray-900">{{ $booking->aula->nama ?? 'Aula' }}</td>
                            <td class="text-gray-700">
                                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}
                            </td>
                            <td class="text-gray-700 text-xs">
                                {{ $booking->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                            <td class="text-gray-700">{{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}</td>
                            <td>
                                <span class="badge {{ $statusClass[$booking->status] ?? 'badge-canceled' }}">
                                    {{ $statusLabels[$booking->status] ?? $booking->status }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    @if($booking->status == 'pending')
                                        <form action="{{ route('admin.kelolabooking.approve', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-mini btn-mini-approve">Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.kelolabooking.reject', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-mini btn-mini-reject">Tolak</button>
                                        </form>
                                    @endif

                                    @if($booking->status == 'approved')
                                        <form action="{{ route('admin.kelolabooking.complete', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-mini btn-mini-complete">Selesai</button>
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
                                            status_class: '{{ $statusClass[$booking->status] ?? 'badge-canceled' }}',
                                            approve_url: '{{ route('admin.kelolabooking.approve', $booking->id) }}',
                                            reject_url: '{{ route('admin.kelolabooking.reject', $booking->id) }}',
                                            complete_url: '{{ route('admin.kelolabooking.complete', $booking->id) }}',
                                            destroy_url: '{{ route('admin.kelolabooking.destroy', $booking->id) }}'
                                        })"
                                        class="btn-mini btn-mini-detail">
                                        Detail
                                    </button>

                                    <form action="{{ route('admin.kelolabooking.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus booking ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-mini btn-mini-delete">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 mb-4">
                                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-base font-semibold text-gray-700 mb-1">Belum Ada Data Booking</p>
                                <p class="text-sm text-gray-500">Belum ada pengguna yang melakukan booking.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($bookings) && method_exists($bookings, 'links'))
            <div class="p-5 border-t border-gray-100 bg-gray-50/50">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    {{-- ===== MOBILE CARD VIEW ===== --}}
    <div class="md:hidden space-y-4">
        @forelse($bookings as $booking)
            @php
                $sesiLabels = [
                    'pagi' => 'Pagi (08:00-12:00)',
                    'siang' => 'Siang (13:00-17:00)',
                    'seharian' => 'Seharian (08:00-17:00)'
                ];
                $statusClass = [
                    'pending' => 'badge-pending',
                    'approved' => 'badge-approved',
                    'rejected' => 'badge-rejected',
                    'canceled' => 'badge-canceled',
                    'completed' => 'badge-completed'
                ];
                $statusLabels = [
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'canceled' => 'Dibatalkan',
                    'completed' => 'Selesai'
                ];
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 relative">
                <div class="absolute top-4 right-4">
                    <input type="checkbox"
                           class="booking-checkbox row-checkbox"
                           value="{{ $booking->id }}"
                           onchange="updateSelectedBookingCount()">
                </div>

                <div class="pr-8 mb-3">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-xs font-bold text-gray-400">No. {{ $bookings->firstItem() + $loop->index }}</span>
                        <span class="badge {{ $statusClass[$booking->status] ?? 'badge-canceled' }}">
                            {{ $statusLabels[$booking->status] ?? $booking->status }}
                        </span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $booking->aula->nama ?? 'Aula' }}</h3>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $booking->user->name ?? 'User' }}</p>
                </div>

                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex justify-between py-1.5 border-b border-gray-100">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-gray-100">
                        <span class="text-gray-500">Diajukan</span>
                        <span class="font-semibold text-gray-900">{{ $booking->created_at?->format('d/m/Y H:i') ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-gray-100">
                        <span class="text-gray-500">Sesi</span>
                        <span class="font-semibold text-gray-900 text-right">{{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}</span>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-gray-500">Peserta</span>
                        <span class="font-semibold text-gray-900">{{ $booking->jumlah_peserta }} Orang</span>
                    </div>
                </div>

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
                            status_class: '{{ $statusClass[$booking->status] ?? 'badge-canceled' }}',
                            approve_url: '{{ route('admin.kelolabooking.approve', $booking->id) }}',
                            reject_url: '{{ route('admin.kelolabooking.reject', $booking->id) }}',
                            complete_url: '{{ route('admin.kelolabooking.complete', $booking->id) }}',
                            destroy_url: '{{ route('admin.kelolabooking.destroy', $booking->id) }}'
                        })"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Detail
                    </button>

                    <div class="grid grid-cols-2 gap-2">
                        @if($booking->status == 'pending')
                            <form action="{{ route('admin.kelolabooking.approve', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 text-xs font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('admin.kelolabooking.reject', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                                    Tolak
                                </button>
                            </form>
                        @endif

                        @if($booking->status == 'approved')
                            <form action="{{ route('admin.kelolabooking.complete', $booking->id) }}" method="POST" class="col-span-2">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
                                    Selesaikan
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.kelolabooking.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus booking ini?')" class="col-span-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2 text-xs font-bold text-red-700 bg-red-50 border border-red-100 hover:bg-red-100 rounded-lg transition">
                                Hapus Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 mb-4">
                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-base font-semibold text-gray-700 mb-1">Belum Ada Data Booking</p>
                <p class="text-sm text-gray-500">Belum ada pengguna yang melakukan booking.</p>
            </div>
        @endforelse

        @if(isset($bookings) && method_exists($bookings, 'links'))
            <div class="pt-4">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    {{-- ===== MODAL DETAIL ===== --}}
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
                 class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full relative z-10 max-h-[90vh] overflow-y-auto mx-2">

                {{-- Header Modal --}}
                <div class="modal-header flex items-center justify-between px-6 sm:px-8 py-5 sticky top-0 z-10 rounded-t-2xl">
                    <div>
                        <p class="text-[10px] font-semibold tracking-widest text-yellow-400 uppercase">Detail Booking</p>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-white mt-0.5">
                            #<span x-text="detail.id"></span>
                        </h3>
                        <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full text-xs font-bold bg-white/15 text-white backdrop-blur"
                              x-text="detail.status_label"></span>
                    </div>
                    <button @click="closeDetail()"
                        class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="px-6 sm:px-8 py-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div class="sm:col-span-2 flex items-center gap-3 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                            <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wide">Pemohon</p>
                                <p class="text-base font-bold text-gray-900" x-text="detail.user"></p>
                                <p class="text-xs text-gray-500 break-all" x-text="detail.email"></p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Aula</p>
                            <p class="text-base font-semibold text-gray-900 mt-1" x-text="detail.aula"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</p>
                            <p class="text-base font-semibold text-gray-900 mt-1" x-text="detail.tanggal"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sesi</p>
                            <p class="text-base font-semibold text-gray-900 mt-1" x-text="detail.sesi"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah Peserta</p>
                            <p class="text-base font-semibold text-gray-900 mt-1" x-text="detail.peserta"></p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Penanggung Jawab</p>
                            <p class="text-base font-semibold text-gray-900 mt-1" x-text="detail.penanggung_jawab"></p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Keperluan</p>
                            <p class="text-base text-gray-800 mt-1 leading-relaxed bg-gray-50 p-3 rounded-lg" x-text="detail.keperluan"></p>
                        </div>
                        <div class="sm:col-span-2" x-show="detail.catatan && detail.catatan !== ''">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Catatan</p>
                            <p class="text-base text-gray-800 mt-1 leading-relaxed bg-yellow-50 border border-yellow-100 p-3 rounded-lg" x-text="detail.catatan"></p>
                        </div>
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="flex items-center justify-end gap-3 px-6 sm:px-8 py-5 border-t border-gray-200 bg-gray-50 rounded-b-2xl flex-wrap sticky bottom-0">
                    <button @click="closeDetail()"
                        class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition">
                        Tutup
                    </button>

                    <template x-if="detail.status === 'pending'">
                        <div class="flex gap-3 w-full sm:w-auto">
                            <form :action="detail.approve_url" method="POST" class="inline flex-1 sm:flex-none">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                                    Setujui
                                </button>
                            </form>
                            <form :action="detail.reject_url" method="POST" class="inline flex-1 sm:flex-none">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </template>

                    <template x-if="detail.status === 'approved'">
                        <form :action="detail.complete_url" method="POST" class="inline w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
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
    // --- Bulk Delete ---
    let selectedBookingIds = new Set();

    function updateSelectedBookingCount() {
        const checkboxes = document.querySelectorAll('.booking-checkbox:checked');
        selectedBookingIds.clear();
        checkboxes.forEach(cb => selectedBookingIds.add(cb.value));

        const count = selectedBookingIds.size;
        const btn = document.getElementById('btn-bulk-delete-booking');
        const countSpan = document.getElementById('selected-booking-count');

        countSpan.textContent = count;

        if (count > 0) {
            btn.style.display = 'inline-flex';
        } else {
            btn.style.display = 'none';
        }
    }

    function toggleCheckAllBookings(source) {
        const checkboxes = document.querySelectorAll('.booking-checkbox');
        checkboxes.forEach(cb => cb.checked = source.checked);
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
                    didOpen: () => { Swal.showLoading(); }
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
                        }).then(() => { location.reload(); });
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

    // --- Modal Detail ---
    function kelolaBookingManager() {
        return {
            showDetailModal: false,
            detail: {
                id: '', user: '', email: '', aula: '', tanggal: '', sesi: '',
                peserta: '', penanggung_jawab: '', keperluan: '', catatan: '',
                status: '', status_label: '', status_class: '',
                approve_url: '', reject_url: '', complete_url: '', destroy_url: ''
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
@endsection