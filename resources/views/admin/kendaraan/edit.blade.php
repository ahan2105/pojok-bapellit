@extends('layouts.admin')
@section('title', 'Edit Kendaraan')
@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8"><a href="{{ route('admin.kendaraan.show', $kendaraan) }}" class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">← Kembali ke detail kendaraan</a><p class="mt-6 text-sm font-bold uppercase tracking-wider text-indigo-600">Perbarui data aset</p><h1 class="mt-2 text-3xl font-bold text-slate-900">Edit Kendaraan <span class="text-indigo-600">{{ $kendaraan->plat_nomor }}</span></h1><p class="mt-2 text-slate-500">Perbarui informasi kendaraan dan masa berlaku pajaknya.</p></div>
    <form action="{{ route('admin.kendaraan.update', $kendaraan) }}" method="POST" enctype="multipart/form-data" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:p-8">@csrf @method('PUT') @include('admin.kendaraan._form', ['kendaraan' => $kendaraan])<div class="mt-8 flex justify-end border-t border-slate-100 pt-6"><button class="rounded-xl bg-indigo-600 px-6 py-3 font-semibold text-white shadow-sm transition hover:bg-indigo-700">Simpan Perubahan</button></div></form>
</div>
@endsection
