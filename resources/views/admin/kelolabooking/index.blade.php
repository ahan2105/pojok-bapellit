@extends('layouts.admin')

@section('title', 'Kelola Booking')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Booking</h1>
            <p class="text-sm text-gray-600">Kelola semua booking dari pengguna</p>
        </div>
        <a href="{{ route('booking.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
            📅 Booking Aula
        </a>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Total Booking</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Menunggu</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $statistics['pending'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Disetujui</p>
            <p class="text-2xl font-bold text-green-600">{{ $statistics['approved'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Hari Ini</p>
            <p class="text-2xl font-bold text-blue-600">{{ $statistics['today'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Tabel Semua Booking -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">User</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Aula</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Sesi</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-600">#{{ $booking->id }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $booking->user->name ?? 'User' }}</td>
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
                                    @if($booking->status == 'pending')
                                        <form action="{{ route('admin.booking.approve', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-green-600 hover:bg-green-50 rounded transition" title="Setujui">
                                                ✅
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.booking.reject', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded transition" title="Tolak">
                                                ❌
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($booking->status == 'approved')
                                        <form action="{{ route('admin.booking.complete', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition" title="Selesaikan">
                                                ✔️
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('admin.kelolabooking.detail', $booking->id) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded transition" title="Detail">
                                        👁️
                                    </a>
                                    
                                    <form action="{{ route('admin.booking.destroy', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus booking ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded transition" title="Hapus">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data booking
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