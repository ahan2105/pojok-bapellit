@php
    $isAdmin = Auth::user()?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp
@extends($layout)
@section('title', ' Kendaraan Dinas')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8" x-data="vehicleImagePreview()">
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div><p class="text-sm font-semibold text-indigo-600">MONITORING ASET</p><h1 class="mt-1 text-3xl font-bold text-gray-900">Kendaraan Dinas</h1><p class="mt-2 text-gray-500">Pantau masa berlaku pajak kendaraan kantor.</p></div>
        @if($isAdmin)<a href="{{ route('admin.kendaraan.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 font-semibold text-white shadow hover:bg-indigo-700">+ Tambah Kendaraan</a>@endif
    </div>
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"><p class="mb-3 text-sm font-bold text-slate-700">Keterangan warna pajak</p><div class="flex flex-wrap gap-2 text-xs font-semibold"><span class="border border-red-700 bg-red-600 px-3 py-1.5 text-white">Merah: jatuh tempo ≤ 7 hari atau terlambat</span><span class="border border-amber-200 bg-amber-50 px-3 py-1.5 text-amber-700">Kuning: jatuh tempo 8–30 hari</span><span class="border border-blue-200 bg-blue-50 px-3 py-1.5 text-blue-700">Biru: jatuh tempo 31–60 hari</span><span class="border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-emerald-700">Hijau: sudah dibayar</span><span class="border border-gray-200 bg-gray-50 px-3 py-1.5 text-gray-500">Abu-abu: jatuh tempo lebih dari 60 hari</span></div></div>
    <form method="GET" class="bg-white rounded-2xl border p-4 mb-6 grid md:grid-cols-4 gap-3">
        <input name="search" value="{{ request('search') }}" placeholder="Cari plat, nomor rangka, atau pemakai..." class="rounded-xl border-gray-300 md:col-span-2">
        <input name="unit_kerja" value="{{ request('unit_kerja') }}" placeholder="Unit kerja" class="rounded-xl border-gray-300">
        <select name="status" class="rounded-xl border-gray-300"><option value="">Semua status</option>@foreach(['aktif','tidak dipakai','rusak'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
        <button class="rounded-xl bg-gray-900 px-4 py-2 text-white font-semibold">Terapkan Filter</button><a href="{{ route('kendaraan.index') }}" class="rounded-xl border px-4 py-2 text-center font-semibold text-gray-600">Reset</a>
    </form>
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">@forelse($kendaraan as $item)
        <a href="{{ route($isAdmin ? 'admin.kendaraan.show' : 'kendaraan.show', $item) }}" class="group bg-white rounded-2xl border overflow-hidden shadow-sm hover:shadow-lg transition">
            <div class="h-24 bg-gradient-to-br from-indigo-100 to-slate-100 flex items-center justify-center relative">@if($item->foto_url)<button type="button" @click.prevent="openPreview('{{ $item->foto_url }}', '{{ $item->plat_nomor }}')" class="h-full w-full cursor-zoom-in" aria-label="Preview foto {{ $item->plat_nomor }}"><img src="{{ $item->foto_url }}" class="w-full h-full object-cover" alt="{{ $item->plat_nomor }}"></button>@else<span class="text-4xl">🚙</span>@endif</div>
            <div class="p-5"><div class="flex justify-between items-start"><div><h2 class="text-xl font-bold text-gray-900">{{ $item->plat_nomor }}</h2><p class="text-gray-500">{{ $item->merk ?: 'Merk belum diisi' }}</p></div><span class="text-xs px-2.5 py-1 rounded-full bg-{{ $item->status === 'aktif' ? 'emerald' : 'gray' }}-100 text-{{ $item->status === 'aktif' ? 'emerald' : 'gray' }}-700">{{ ucfirst($item->status) }}</span></div><p class="mt-4 text-sm text-gray-500">{{ $item->unit_kerja ?: 'Unit kerja belum diisi' }}</p><div class="mt-4 grid grid-cols-2 gap-2 text-xs">@foreach([['Pajak Tahunan',$item->pajakTahunanAktif],['Pajak 5 Tahunan',$item->pajakLimaTahunanAktif]] as [$label,$pajak])<div class="rounded-xl bg-gray-50 p-3"><p class="text-gray-500">{{ $label }}</p>@if($pajak) @include('kendaraan.partials.badge-pajak', ['pajak' => $pajak]) @else <span class="text-gray-500">Belum ada data</span> @endif</div>@endforeach</div></div>
        </a>
    @empty<div class="md:col-span-2 xl:col-span-3 bg-white rounded-2xl p-12 text-center text-gray-500">Belum ada data kendaraan.</div>@endforelse</div>

    <div x-show="isOpen" x-cloak @keydown.escape.window="closePreview()" @click.self="closePreview()" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" role="dialog" aria-modal="true">
        <div class="relative w-full max-w-4xl rounded-2xl bg-black/20 p-3">
            <button type="button" @click="closePreview()" class="absolute right-5 top-5 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-black/75 text-3xl leading-none text-white shadow-lg ring-2 ring-white/80 transition hover:bg-red-600" aria-label="Tutup preview">&times;</button>
            <img :src="previewUrl" :alt="previewAlt" class="mx-auto max-h-[80vh] max-w-full rounded-xl object-contain shadow-2xl">
            <p class="mt-3 text-center font-semibold text-white" x-text="previewAlt"></p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function vehicleImagePreview() {
        return {
            isOpen: false,
            previewUrl: '',
            previewAlt: '',
            openPreview(url, alt) { this.previewUrl = url; this.previewAlt = alt; this.isOpen = true; },
            closePreview() { this.isOpen = false; this.previewUrl = ''; this.previewAlt = ''; },
        };
    }
</script>
@endpush
@endsection
