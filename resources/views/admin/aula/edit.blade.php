@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Edit Data Aula')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Tombol Kembali -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.aula.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-indigo-600 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            KEMBALI KE KELOLA AULA
        </a>
    </div>

    <!-- Header Judul -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Aula: {{ $aula->nama }}</h1>
        <p class="text-sm text-gray-600">Perbarui informasi, foto, dan fasilitas aula.</p>
    </div>

    <!-- Form Edit Aula -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
        <form action="{{ route('admin.aula.update', $aula->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- KOLOM KIRI: FOTO AULA -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Foto Aula (Maks. 3)</label>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-indigo-500 transition bg-gray-50 relative cursor-pointer flex flex-col items-center justify-center min-h-[180px] mb-4">
                        <input type="file" name="foto[]" multiple accept="image/png, image/jpeg, image/webp" class="absolute inset-0 opacity-0 cursor-pointer" id="foto-input">
                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-xs font-medium text-gray-700">Klik atau drag untuk tambah foto baru</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Foto baru akan ditambahkan ke yang sudah ada (Maks 3)</p>
                    </div>

                    <!-- Preview Foto Saat Ini -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Foto Saat Ini:</label>
                        <div class="grid grid-cols-3 gap-2" id="preview-container">
                            @php 
                                $fotos = is_array($aula->foto) ? $aula->foto : [];
                            @endphp
                            @for($i = 0; $i < 3; $i++)
                                <div class="h-20 border rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden relative border-gray-200 foto-slot">
                                    @if(isset($fotos[$i]) && !empty($fotos[$i]))
                                        <img src="{{ asset('storage/' . $fotos[$i]) }}" class="w-full h-full object-cover" alt="Foto Aula {{ $i+1 }}">
                                        <button type="button" onclick="deleteSinglePhoto({{ $aula->id }}, {{ $i }})" 
                                            class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-700 transition shadow-lg z-10">
                                            ✕
                                        </button>
                                    @else
                                        <div class="flex flex-col items-center text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-[10px]">Kosong</span>
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: INPUT INFORMASI -->
                <div class="space-y-5">
                    <!-- Nama Aula -->
                    <div>
                        <label for="nama" class="block text-sm font-bold text-gray-800 mb-1">Nama Aula</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $aula->nama) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" required>
                        @error('nama')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label for="kapasitas" class="block text-sm font-bold text-gray-800 mb-1">Kapasitas</label>
                        <div class="relative">
                            <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $aula->kapasitas) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm pr-16" min="1" required>
                            <span class="absolute right-4 top-2.5 text-xs text-gray-400 font-medium">Orang</span>
                        </div>
                        @error('kapasitas')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-bold text-gray-800 mb-1">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">{{ old('deskripsi', $aula->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informasi Tambahan -->
                    <div>
                        <label for="informasi_tambahan" class="block text-sm font-bold text-gray-800 mb-1">Informasi Tambahan</label>
                        <textarea id="informasi_tambahan" name="informasi_tambahan" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">{{ old('informasi_tambahan', $aula->informasi_tambahan) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Contoh: Tersedia parkir VIP, Wi-Fi, dll. (pisahkan dengan enter)</p>
                        @error('informasi_tambahan')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label for="lokasi" class="block text-sm font-bold text-gray-800 mb-1">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $aula->lokasi) }}" 
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" 
                            placeholder="Contoh: Gedung A, Lantai 2">
                        @error('lokasi')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fasilitas (Tags Input AlpineJS) -->
                    <div x-data="facilityManager({{ json_encode(is_array($aula->fasilitas) ? $aula->fasilitas : []) }})">
                        <label class="block text-sm font-bold text-gray-800 mb-1">Fasilitas</label>
                        <div class="flex flex-wrap gap-2 p-2 border border-gray-300 rounded-lg bg-white min-h-[46px] items-center">
                            <template x-for="(fac, fIndex) in facilities" :key="fIndex">
                                <span class="inline-flex items-center bg-indigo-50 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-indigo-100">
                                    <span x-text="fac"></span>
                                    <button type="button" @click="removeFacility(fIndex)" class="ml-1.5 text-indigo-400 hover:text-indigo-600">&times;</button>
                                </span>
                            </template>
                            <input type="text" @keydown.enter.prevent="addFacility($event)" placeholder="Tambah fasilitas lalu enter..." class="flex-1 min-w-[140px] text-xs outline-none px-1 py-1">
                        </div>
                        <!-- Hidden Input untuk dikirim ke Controller -->
                        <input type="hidden" name="fasilitas" :value="JSON.stringify(facilities)">
                        @error('fasilitas')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- FOOTER: STATUS & TOMBOL AKSI -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Status Toggle Switch -->
                <div class="flex items-center space-x-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status_aktif" value="1" {{ $aula->status_aktif ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">
                        Status: <span class="{{ $aula->status_aktif ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">{{ $aula->status_aktif ? 'Aktif (Bisa dibooking)' : 'Nonaktif' }}</span>
                    </span>
                </div>

                <!-- Tombol Batal & Simpan & Hapus -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.aula.index') }}" class="px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition">Batal</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">Perbarui Aula</button>
                    <button type="button" onclick="confirmDelete({{ $aula->id }}, '{{ $aula->nama }}')" class="px-5 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">Hapus</button>
                </div>
            </div>

        </form>
    </div>

</div>

<!-- Form Delete (Hidden) -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                
                // Cek berapa slot yang sudah terisi
                let filledSlots = 0;
                for (let i = 0; i < slots.length; i++) {
                    if (slots[i].querySelector('img')) {
                        filledSlots++;
                    }
                }
                
                // Hitung total foto setelah upload
                const totalAfterUpload = filledSlots + files.length;
                if (totalAfterUpload > 3) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maksimal 3 Foto',
                        text: 'Total foto (yang sudah ada + yang baru) tidak boleh lebih dari 3.',
                        confirmButtonColor: '#f59e0b',
                        confirmButtonText: 'OK'
                    });
                    fotoInput.value = '';
                    return;
                }
                
                // Preview file baru ke slot kosong
                let slotIndex = 0;
                for (let i = 0; i < slots.length; i++) {
                    if (!slots[i].querySelector('img')) {
                        slotIndex = i;
                        break;
                    }
                }
                
                for (let i = 0; i < Math.min(files.length, 3 - filledSlots); i++) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const currentSlot = slots[slotIndex + i];
                        currentSlot.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover">`;
                    }
                    reader.readAsDataURL(files[i]);
                }
            });
        }
    });

    // ⭐ Delete Single Photo (VERSI DIPERBAIKI)
    function deleteSinglePhoto(aulaId, photoIndex) {
        Swal.fire({
            title: 'Hapus Foto?',
            text: 'Apakah Anda yakin ingin menghapus foto ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading agar user tahu proses berjalan
                Swal.fire({
                    title: 'Menghapus...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }
                });

                // Ambil CSRF token dengan aman (fallback ke input _token jika meta tidak ada di layout)
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                               || document.querySelector('input[name="_token"]')?.value;

                fetch(`/admin/aula/${aulaId}/delete-photo`, {
                    method: 'POST', // Gunakan POST + spoofing agar lebih stabil di semua server
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest', // ⭐ INI KUNCINYA! Agar Laravel tahu ini request AJAX
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        index: photoIndex,
                        _method: 'DELETE' // ⭐ Method spoofing untuk route DELETE
                    })
                })
                .then(response => {
                    // Cek apakah respon benar-benar JSON (bukan HTML redirect error)
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        return response.json();
                    } else {
                        throw new TypeError("Server tidak mengembalikan JSON!");
                    }
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Foto berhasil dihapus',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload(); // Refresh halaman agar UI sinkron
                        });
                    } else {
                        Swal.fire('Gagal!', data.message || 'Gagal menghapus foto', 'error');
                    }
                })
                .catch(error => {
                    console.error('Fetch error detail:', error); // Lihat di console browser jika masih error
                    Swal.fire('Error!', 'Terjadi kesalahan pada server. Cek console untuk detail.', 'error');
                });
            }
        });
    }

    // Confirm Delete Aula
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Aula?',
            html: `Apakah Anda yakin ingin menghapus <strong>"${name}"</strong>?<br>Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form');
                form.action = `/admin/aula/${id}`;
                form.submit();
            }
        });
    }

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

<style>
    [x-cloak] { display: none !important; }
    
    .foto-slot {
        position: relative;
    }
    
    .foto-slot img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endsection