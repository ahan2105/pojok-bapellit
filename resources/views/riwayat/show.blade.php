@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Detail Booking #' . $booking->id)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Tombol Kembali -->
    <a href="{{ route('riwayat.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-indigo-600 mb-6">
        ← Kembali ke Riwayat
    </a>

    <!-- Detail Booking -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-start mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Detail Booking #{{ $booking->id }}</h1>
            <span class="px-3 py-1 rounded-full text-sm font-medium 
                {{ $booking->status == 'approved' ? 'bg-green-100 text-green-800' : 
                   ($booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                   ($booking->status == 'canceled' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800')) }}">
                {{ $booking->status == 'approved' ? 'Disetujui' : 
                   ($booking->status == 'pending' ? 'Menunggu' : 
                   ($booking->status == 'canceled' ? 'Dibatalkan' : ucfirst($booking->status))) }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4">
            @if($isAdmin)
                <div>
                    <p class="text-sm text-gray-500">User</p>
                    <p class="font-semibold">{{ $booking->user->name ?? 'User' }}</p>
                </div>
            @endif
            <div>
                <p class="text-sm text-gray-500">Aula</p>
                <p class="font-semibold">{{ $booking->aula->nama }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Tanggal</p>
                <p class="font-semibold">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Sesi</p>
                <p class="font-semibold">
                    @php
                        $sesiLabels = [
                            'pagi' => 'Pagi (08:00 - 12:00)',
                            'siang' => 'Siang (13:00 - 17:00)',
                            'seharian' => 'Seharian (08:00 - 17:00)'
                        ];
                    @endphp
                    {{ $sesiLabels[$booking->sesi_waktu] ?? $booking->sesi_waktu }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Jumlah Peserta</p>
                <p class="font-semibold">{{ $booking->jumlah_peserta }} Orang</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500">Penanggung Jawab</p>
                <p class="font-semibold">{{ $booking->nama_penanggung_jawab }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500">Keperluan</p>
                <p class="font-semibold">{{ $booking->keperluan }}</p>
            </div>
            @if($booking->catatan)
            <div class="col-span-2">
                <p class="text-sm text-gray-500">Catatan</p>
                <p class="font-semibold">{{ $booking->catatan }}</p>
            </div>
            @endif
            
            @if($booking->status == 'pending')
                <div class="col-span-2 mt-4 pt-4 border-t">
                    <form action="{{ route('riwayat.cancel', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                            ❌ Batalkan Booking
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection