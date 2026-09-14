@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Buat Sesi Absensi Baru')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">

    <!-- Tombol Kembali -->
    <div class="mb-8">
        <a href="{{ route('admin.absensi.index') }}" 
           class="inline-flex items-center gap-2.5 px-5 py-2.5 bg-white text-gray-700 text-base font-medium rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 hover:border-indigo-300 hover:text-indigo-600 transition-all group">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Sesi
        </a>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Buat Sesi Absensi Baru</h1>
        <p class="text-base text-gray-600 mt-2">Buat sesi absensi untuk rapat, event, atau kegiatan lainnya.</p>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 md:p-10">
        <form action="{{ route('admin.absensi.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Nama Sesi -->
                <div>
                    <label class="block text-base font-semibold text-gray-800 mb-2">
                        Nama Sesi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_sesi" value="{{ old('nama_sesi') }}" 
                           placeholder="Contoh: Rapat Koordinasi, Pelatihan Digital, dll"
                           class="w-full px-4 py-3.5 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none"
                           required autofocus>
                    @error('nama_sesi') 
                        <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-base font-semibold text-gray-800 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                           class="w-full px-4 py-3.5 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none"
                           required>
                    @error('tanggal') 
                        <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Lokasi -->
                <div>
                    <label class="block text-base font-semibold text-gray-800 mb-2">
                        Lokasi <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" 
                           placeholder="Contoh: Ruang Meeting Lt. 2, Aula Utama"
                           class="w-full px-4 py-3.5 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none">
                    @error('lokasi') 
                        <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-base font-semibold text-gray-800 mb-2">
                        Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="catatan" rows="3" 
                              placeholder="Contoh: Agenda rapat, tujuan kegiatan, dll"
                              class="w-full px-4 py-3.5 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none leading-relaxed">{{ old('catatan') }}</textarea>
                    @error('catatan') 
                        <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Info Tambahan -->
                <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-indigo-700">
                        <p class="font-semibold mb-1">Sistem akan otomatis:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>Membuat sesi absensi baru</li>
                            <li>Menambahkan semua user aktif sebagai peserta</li>
                            <li>Status awal semua peserta = belum diabsen</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-10 pt-7 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('admin.absensi.index') }}" 
                   class="px-6 py-3.5 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-7 py-3.5 text-base font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Buat Sesi Absensi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection