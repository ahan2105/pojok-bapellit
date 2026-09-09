@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Tambah Aula Baru')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.aula.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-indigo-600 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Aula
        </a>
    </div>

    <!-- Header Judul -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Aula Baru</h1>
        <p class="text-sm text-gray-600">Isi informasi lengkap untuk menambahkan aula baru.</p>
    </div>

    <!-- Form Tambah Aula -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
        <form action="{{ route('admin.aula.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- KOLOM KIRI: FOTO AULA -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Foto Aula</label>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-500 transition bg-gray-50 relative cursor-pointer flex flex-col items-center justify-center min-h-[240px]">
                        <input type="file" name="foto[]" multiple accept="image/png, image/jpeg, image/webp" class="absolute inset-0 opacity-0 cursor-pointer" id="foto-input">
                        <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-700">Drag & drop foto aula atau klik untuk upload</p>
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP (Maks. 5MB, Maks 3 Foto)</p>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mt-4" id="preview-container">
                        <div class="h-20 border rounded-lg bg-gray-50 flex items-center justify-center overflow-hidden relative border-gray-200 shadow-inner foto-slot">
                            <div class="text-gray-300 text-xl font-bold flex items-center justify-center w-full h-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="h-20 border rounded-lg bg-gray-50 flex items-center justify-center overflow-hidden relative border-gray-200 shadow-inner foto-slot">
                            <div class="text-gray-300 text-xl font-bold flex items-center justify-center w-full h-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="h-20 border rounded-lg bg-gray-50 flex items-center justify-center overflow-hidden relative border-gray-200 shadow-inner foto-slot">
                            <div class="text-gray-300 text-xl font-bold flex items-center justify-center w-full h-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: INPUT INFORMASI -->
                <div class="space-y-5">
                    <!-- Nama Aula -->
                    <div>
                        <label for="nama" class="block text-sm font-bold text-gray-800 mb-1">Nama Aula</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" required>
                        @error('nama')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label for="kapasitas" class="block text-sm font-bold text-gray-800 mb-1">Kapasitas</label>
                        <div class="relative">
                            <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm pr-16" min="1" required>
                            <span class="absolute right-4 top-2.5 text-xs text-gray-400 font-medium">Orang</span>
                        </div>
                        @error('kapasitas')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-bold text-gray-800 mb-1">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informasi Tambahan -->
                    <div>
                        <label for="informasi_tambahan" class="block text-sm font-bold text-gray-800 mb-1">Informasi Tambahan</label>
                        <textarea id="informasi_tambahan" name="informasi_tambahan" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">{{ old('informasi_tambahan') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Contoh: Tersedia parkir VIP, Wi-Fi, dll. (pisahkan dengan enter)</p>
                        @error('informasi_tambahan')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label for="lokasi" class="block text-sm font-bold text-gray-800 mb-1">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" 
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" 
                            placeholder="Contoh: Gedung A, Lantai 2">
                        @error('lokasi')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fasilitas -->
                    <div x-data="facilityManager([])">
                        <label class="block text-sm font-bold text-gray-800 mb-1">Fasilitas</label>
                        <div class="flex flex-wrap gap-2 p-2 border border-gray-300 rounded-lg bg-white min-h-[46px] items-center">
                            <template x-for="(fac, fIndex) in facilities" :key="fIndex">
                                <span class="inline-flex items-center bg-indigo-50 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-indigo-100">
                                    <span x-text="fac"></span>
                                    <button type="button" @click="removeFacility(fIndex)" class="ml-1.5 text-indigo-400 hover:text-indigo-600">&times;</button>
                                </span>
                            </template>
                            <input type="text" @keydown.enter.prevent="addFacility($event)" placeholder="Tambah fasilitas..." class="flex-1 min-w-[120px] text-xs outline-none px-1 py-1">
                        </div>
                        <input type="hidden" name="fasilitas" :value="JSON.stringify(facilities)">
                        @error('fasilitas')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- FOOTER: STATUS & TOMBOL AKSI -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status_aktif" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">
                        Status: <span class="text-green-600 font-semibold">Aktif (Bisa dibooking)</span>
                    </span>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.aula.index') }}" class="px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition">Batal</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">Simpan Aula</button>
                </div>
            </div>

        </form>
    </div>

</div>

<script>
    // Preview Foto - Support Multiple Files
    document.addEventListener('DOMContentLoaded', function() {
        const fotoInput = document.getElementById('foto-input');
        if (fotoInput) {
            fotoInput.addEventListener('change', function(e) {
                const files = e.target.files;
                const container = document.getElementById('preview-container');
                if (!container) return;
                
                const slots = container.querySelectorAll('.foto-slot');
                
                // Reset semua slot
                const emptyHtml = `<div class="text-gray-300 text-xl font-bold flex items-center justify-center w-full h-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>`;
                
                for (let i = 0; i < slots.length; i++) {
                    slots[i].innerHTML = emptyHtml;
                }
                
                // Preview max 3 files
                const maxPreview = Math.min(files.length, 3);
                for (let i = 0; i < maxPreview; i++) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        slots[i].innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover">`;
                    }
                    reader.readAsDataURL(files[i]);
                }
                
                // Peringatan jika lebih dari 3
                if (files.length > 3 && typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maksimal 3 Foto',
                        text: 'Anda memilih ' + files.length + ' file. Hanya 3 foto pertama yang akan diupload.',
                        confirmButtonColor: '#f59e0b',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });

    function facilityManager(initialFacilities) {
        return {
            facilities: Array.isArray(initialFacilities) ? initialFacilities : [],
            addFacility(e) {
                let val = e.target.value.trim();
                if (val && !this.facilities.includes(val)) {
                    this.facilities.push(val);
                    e.target.value = '';
                }
            },
            removeFacility(index) {
                this.facilities.splice(index, 1);
            }
        }
    }
</script>
@endsection