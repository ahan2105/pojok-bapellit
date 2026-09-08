@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', $isAdmin ? 'Semua Riwayat Booking' : 'Riwayat Booking Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $isAdmin ? 'Semua Riwayat Booking' : 'Riwayat Booking Saya' }}
            </h1>
            <p class="text-sm text-gray-600">
                {{ $isAdmin ? 'Daftar semua booking dari semua pengguna' : 'Daftar semua booking yang pernah Anda lakukan' }}
            </p>
        </div>
        
        @if($isAdmin)
            <a href="{{ route('admin.kelolabooking.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                📋 Kelola Booking
            </a>
        @endif
    </div>

    <!-- Tabel Riwayat -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">ID</th>
                        @if($isAdmin)
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">User</th>
                        @endif
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Aula</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Sesi</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Keperluan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-600">#{{ $booking->id }}</td>
                            @if($isAdmin)
                                <td class="px-4 py-3 text-gray-800">{{ $booking->user->name ?? 'User' }}</td>
                            @endif
                            <td class="px-4 py-3 text-gray-800">{{ $booking->aula->nama ?? 'Aula' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                @php
                                    $sesiLabels = [
                                        'pagi' => 'Pagi (08:00-12:00)',
                                        'siang' => 'Siang (13:00-17:00)',
                                        'seharian' => 'Seharian (08:00-17:00)'
                                    ];
                                @endphp
                                {{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ Str::limit($booking->keperluan, 30) }}</td>
                            <td class="px-4 py-3">
                                @php
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
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$booking->status] ?? $booking->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-1">
                                    <a href="{{ route('riwayat.show', $booking->id) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded transition" title="Detail">
                                        👁️
                                    </a>
                                    
                                    @if($booking->status == 'pending')
                                        <form action="{{ route('riwayat.cancel', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded transition" title="Batalkan">
                                                ❌
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 8 : 7 }}" class="px-4 py-8 text-center text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm">Belum ada riwayat booking</p>
                                <a href="{{ route('booking.index') }}" class="text-sm text-indigo-600 hover:underline mt-2 inline-block">Booking sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($bookings) && method_exists($bookings, 'links'))
            <div class="p-4 border-t">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection