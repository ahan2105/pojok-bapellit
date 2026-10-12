@php
    $isAdmin = Auth::user()?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp
@extends($layout)
@section('title', 'Detail Kendaraan')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
<a href="{{ route($isAdmin ? 'admin.kendaraan.index' : 'kendaraan.index') }}" class="text-indigo-600 font-semibold">← Kembali ke daftar kendaraan</a>
<div class="mt-6 bg-white rounded-2xl border shadow-sm overflow-hidden"><div class="h-56 bg-gradient-to-br from-indigo-100 to-slate-100 flex items-center justify-center">@if($kendaraan->foto_url)<img src="{{ $kendaraan->foto_url }}" class="w-full h-full object-cover" alt="{{ $kendaraan->plat_nomor }}">@else<span class="text-7xl">🚙</span>@endif</div><div class="p-6 md:p-8"><div class="flex flex-col md:flex-row justify-between gap-4"><div><p class="text-sm text-indigo-600 font-semibold">KENDARAAN DINAS</p><h1 class="text-3xl font-bold text-gray-900 mt-1">{{ $kendaraan->plat_nomor }}</h1><p class="text-gray-500 mt-1">{{ $kendaraan->merk ?: 'Merk belum diisi' }}</p></div><div class="flex gap-2">@if($isAdmin)<a href="{{ route('admin.kendaraan.edit', $kendaraan) }}" class="rounded-xl bg-indigo-600 text-white px-4 py-2 font-semibold">Edit</a><form action="{{ route('admin.kendaraan.destroy', $kendaraan) }}" method="POST" onsubmit="return confirm('Hapus kendaraan ini?')">@csrf @method('DELETE')<button class="rounded-xl bg-red-50 text-red-600 px-4 py-2 font-semibold">Hapus</button></form>@endif</div></div><div class="grid md:grid-cols-3 gap-4 mt-8"><div><p class="text-sm text-gray-500">Nomor Rangka</p><p class="font-semibold">{{ $kendaraan->nomor_rangka ?: '-' }}</p></div><div><p class="text-sm text-gray-500">Unit Kerja</p><p class="font-semibold">{{ $kendaraan->unit_kerja ?: '-' }}</p></div><div><p class="text-sm text-gray-500">Pemakai</p><p class="font-semibold">{{ $kendaraan->pemakai ?: '-' }}</p></div><div><p class="text-sm text-gray-500">Status</p><p class="font-semibold">{{ ucfirst($kendaraan->status) }}</p></div></div><div class="grid md:grid-cols-2 gap-5 mt-8">@foreach([['Pajak Tahunan',$kendaraan->pajakTahunanAktif],['Pajak 5 Tahunan',$kendaraan->pajakLimaTahunanAktif]] as [$label,$pajak])<div class="rounded-2xl border p-5"><h2 class="font-bold text-lg">{{ $label }}</h2>@if($pajak)<div class="mt-3">@include('kendaraan.partials.badge-pajak', ['pajak' => $pajak])</div><p class="text-sm text-gray-500 mt-2">Jatuh tempo: {{ $pajak->tanggal_jatuh_tempo->format('d M Y') }}</p>@if($isAdmin)<form action="{{ route('admin.kendaraan.tandai-bayar', $pajak) }}" method="POST" class="mt-4" onsubmit="confirmMarkAsPaid(event, '{{ $label }}', '{{ $kendaraan->plat_nomor }}')">@csrf<button class="rounded-xl bg-emerald-600 text-white px-4 py-2 font-semibold">Tandai Sudah Bayar</button></form>@endif @else<p class="text-gray-500 mt-3">Belum ada pajak aktif.</p>@endif</div>@endforeach</div>@if($kendaraan->catatan)<div class="mt-8 rounded-xl bg-gray-50 p-4"><p class="text-sm text-gray-500">Catatan</p><p class="mt-1">{{ $kendaraan->catatan }}</p></div>@endif</div></div></div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmMarkAsPaid(event, taxLabel, plateNumber) {
        event.preventDefault();
        const form = event.currentTarget;
        Swal.fire({
            title: 'Konfirmasi pembayaran',
            html: `Yakin pajak <strong>${taxLabel}</strong> kendaraan <strong>${plateNumber}</strong> sudah dibayar?<br><small>Periode berikutnya akan dibuat otomatis.</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, sudah dibayar',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    }
</script>
@endpush
