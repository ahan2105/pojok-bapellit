@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Tambah Aula Baru')

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

    /* Tombol Kembali glassmorphism */
    .bapelit-hero a.btn-hero-back,
    .bapelit-hero a.btn-hero-back:link,
    .bapelit-hero a.btn-hero-back:visited,
    .bapelit-hero a.btn-hero-back:hover,
    .bapelit-hero a.btn-hero-back:focus,
    .bapelit-hero a.btn-hero-back:active {
        background-color: rgba(255,255,255,0.12) !important;
        color: #ffffff !important;
        text-decoration: none !important;
        border: 1px solid rgba(255,255,255,0.25) !important;
        backdrop-filter: blur(8px);
        transition: all 0.2s ease;
    }
    .bapelit-hero a.btn-hero-back:hover {
        background-color: rgba(255,255,255,0.2) !important;
        border-color: rgba(250, 204, 21, 0.5) !important;
        transform: translateX(-3px);
    }
    .bapelit-hero a.btn-hero-back svg {
        color: #ffffff !important;
        stroke: #ffffff !important;
    }

    /* Dropzone */
    .dropzone-add {
        transition: all .18s ease;
    }
    .dropzone-add:hover {
        transform: translateY(-2px);
        border-color: #6366f1;
    }

    .foto-slot img { width: 100%; height: 100%; object-fit: cover; }

    /* Focus ring pada input fasilitas */
    .facility-wrapper:focus-within {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    [x-cloak] { display: none !important; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- ===== HERO ===== --}}
    <div class="bapelit-hero text-white rounded-2xl px-6 sm:px-8 py-6 mb-6 shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center flex-shrink-0 ring-1 ring-white/20">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                 
                    <h1 class="text-xl sm:text-2xl font-extrabold leading-tight">Tambah Aula Baru</h1>
                    <p class="text-xs sm:text-sm text-blue-200 mt-0.5">Isi informasi lengkap untuk menambahkan aula baru.</p>
                </div>
            </div>
            <a href="{{ route('admin.aula.index') }}"
               class="btn-hero-back inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- ===== FORM CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Sub-header --}}
        <div class="px-6 sm:px-8 py-4 bg-gradient-to-r from-indigo-50 to-white border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-semibold text-indigo-500 uppercase tracking-widest">Formulir Aula</p>
                    <p class="text-base font-bold text-gray-800">Data Aula Baru</p>
                </div>
            </div>
            <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Aktif
            </span>
        </div>

        <form action="{{ route('admin.aula.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- ===== KOLOM KIRI: FOTO ===== --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-bold text-gray-800">Foto Aula</label>
                        <span class="text-xs text-gray-400">Maks. 3 foto</span>
                    </div>

                    <div class="dropzone-add border-2 border-dashed border-gray-300 rounded-xl p-8 text-center bg-gray-50 relative cursor-pointer flex flex-col items-center justify-center min-h-[240px]">
                        <input type="file" name="foto[]" multiple accept="image/png, image/jpeg, image/webp" class="absolute inset-0 opacity-0 cursor-pointer" id="foto-input">
                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-700">Drag & drop foto aula atau klik untuk upload</p>
                        <p class="text-xs text-gray-400 mt-1.5">JPG, PNG, WEBP (Maks. 5MB)</p>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mt-4" id="preview-container">
                        <div class="h-24 border-2 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden relative border-gray-200 shadow-inner foto-slot">
                            <div class="text-gray-300 flex items-center justify-center w-full h-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="h-24 border-2 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden relative border-gray-200 shadow-inner foto-slot">
                            <div class="text-gray-300 flex items-center justify-center w-full h-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="h-24 border-2 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden relative border-gray-200 shadow-inner foto-slot">
                            <div class="text-gray-300 flex items-center justify-center w-full h-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 mt-3 flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-sm bg-indigo-500"></span>
                        Foto yang diupload akan muncul di preview di atas
                    </p>
                </div>

                {{-- ===== KOLOM KANAN: INFORMASI ===== --}}
                <div class="space-y-5">

                    {{-- Nama Aula --}}
                    <div>
                        <label for="nama" class="block text-sm font-bold text-gray-800 mb-2">Nama Aula <span class="text-red-500">*</span></label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm transition"
                            placeholder="Contoh: Aula Utama" required>
                        @error('nama')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Kapasitas --}}
                    <div>
                        <label for="kapasitas" class="block text-sm font-bold text-gray-800 mb-2">Kapasitas <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm pr-20 transition"
                                placeholder="100" min="1" required>
                            <span class="absolute right-4 top-2.5 text-xs text-gray-400 font-medium">Orang</span>
                        </div>
                        @error('kapasitas')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="deskripsi" class="block text-sm font-bold text-gray-800 mb-2">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm leading-relaxed transition"
                            placeholder="Deskripsi singkat tentang aula...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Informasi Tambahan --}}
                    <div>
                        <label for="informasi_tambahan" class="block text-sm font-bold text-gray-800 mb-2">Informasi Tambahan</label>
                        <textarea id="informasi_tambahan" name="informasi_tambahan" rows="3"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm leading-relaxed transition"
                            placeholder="Contoh: Tersedia parkir VIP, Wi-Fi, dll.">{{ old('informasi_tambahan') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1.5">Contoh: Tersedia parkir VIP, Wi-Fi, dll. (pisahkan dengan enter)</p>
                        @error('informasi_tambahan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Lokasi --}}
                    <div>
                        <label for="lokasi" class="block text-sm font-bold text-gray-800 mb-2">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm transition"
                            placeholder="Contoh: Gedung A, Lantai 2">
                        @error('lokasi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Fasilitas --}}
                    <div x-data="facilityManager([])">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Fasilitas</label>
                        <div class="facility-wrapper flex flex-wrap gap-2 p-3 border border-gray-300 rounded-lg bg-white min-h-[52px] items-center transition">
                            <template x-for="(fac, fIndex) in facilities" :key="fIndex">
                                <span class="inline-flex items-center bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-indigo-100">
                                    <span x-text="fac"></span>
                                    <button type="button" @click="removeFacility(fIndex)" class="ml-1.5 text-indigo-400 hover:text-indigo-600 text-base leading-none">&times;</button>
                                </span>
                            </template>
                            <input type="text" @keydown.enter.prevent="addFacility($event)"
                                placeholder="Tambah fasilitas + Enter..."
                                class="flex-1 min-w-[140px] text-sm outline-none px-1.5 py-1.5 bg-transparent">
                        </div>
                        <input type="hidden" name="fasilitas" :value="JSON.stringify(facilities)">
                        @error('fasilitas')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>
            </div>

            {{-- ===== FOOTER: STATUS & AKSI ===== --}}
            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                {{-- Toggle Status --}}
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status_aktif" value="1" checked class="sr-only peer">
                        <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">
                        Status:
                        <span class="text-green-600 font-semibold">Aktif (Bisa dibooking)</span>
                    </span>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center gap-2 flex-wrap justify-end">
                    <a href="{{ route('admin.aula.index') }}"
                       class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Aula
                    </button>
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

                const emptyHtml = `<div class="text-gray-300 flex items-center justify-center w-full h-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>`;

                for (let i = 0; i < slots.length; i++) {
                    slots[i].innerHTML = emptyHtml;
                    slots[i].classList.remove('border-indigo-500');
                    slots[i].classList.add('border-gray-200');
                }

                const maxPreview = Math.min(files.length, 3);
                for (let i = 0; i < maxPreview; i++) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        slots[i].innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover">`;
                        slots[i].classList.remove('border-gray-200');
                        slots[i].classList.add('border-indigo-500');
                    }
                    reader.readAsDataURL(files[i]);
                }

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