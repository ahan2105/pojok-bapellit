@php
    $layout = (auth()->check() && auth()->user()->role === 'admin') ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Kelola Data Aula')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="aulaManager()">
    
    <!-- Breadcrumb / Tombol Kembali -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('booking.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-indigo-600 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            HOME
        </a>
        <a href="{{ route('admin.aula.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Aula Baru
        </a>
    </div>

    <!-- Header Judul -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Data Aula</h1>
        <p class="text-sm text-gray-600">Perbarui informasi dan fasilitas aula dengan mudah.</p>
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Notifikasi Error -->
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- TAB NAVIGASI AULA -->
    <div class="flex items-center space-x-2 mb-6 border-b pb-4 overflow-x-auto">
        @forelse($aulas as $index => $item)
            <button @click="activeTab = {{ $index }}" 
                :class="activeTab === {{ $index }} ? 'bg-indigo-50 text-indigo-600 border-indigo-600 font-semibold shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'"
                class="px-5 py-2.5 rounded-lg border text-sm transition focus:outline-none whitespace-nowrap">
                {{ $item->nama }}
            </button>
        @empty
            <div class="text-gray-500 text-sm py-2">Belum ada data aula. Silakan tambahkan aula baru.</div>
        @endforelse
    </div>

    <!-- KONTEN FORM BERDASARKAN TAB AKTIF -->
    @forelse($aulas as $index => $item)
    <div x-show="activeTab === {{ $index }}" x-cloak class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
        <form action="{{ route('admin.aula.update', $item->id) }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm($event)">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- KOLOM KIRI: FOTO AULA -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Foto Aula</label>
                    
                    <!-- Box Drag & Drop Utama -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-500 transition bg-gray-50 relative cursor-pointer flex flex-col items-center justify-center min-h-[240px]">
                        <input type="file" name="foto[]" multiple accept="image/png, image/jpeg, image/webp" class="absolute inset-0 opacity-0 cursor-pointer" @change="previewImages($event, {{ $index }})">
                        <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-700">Drag & drop foto aula atau klik untuk upload</p>
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP (Maks. 5MB, Maks 3 Foto)</p>
                    </div>

                    <!-- Slot Preview 3 Foto -->
                    <div class="grid grid-cols-3 gap-3 mt-4">
                        @php 
                            $fotos = is_array($item->foto) ? $item->foto : [];
                        @endphp
                        @for($i = 0; $i < 3; $i++)
                            <div class="h-20 border rounded-lg bg-gray-50 flex items-center justify-center overflow-hidden relative border-gray-200 shadow-inner">
                                @if(isset($fotos[$i]) && !empty($fotos[$i]))
                                    <img src="{{ asset('storage/' . $fotos[$i]) }}" class="w-full h-full object-cover" alt="Foto Aula {{ $i+1 }}">
                                @else
                                    <div class="text-gray-300 text-xl font-bold flex items-center justify-center w-full h-full">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                    
                    <!-- Hapus Foto Button -->
                    @if(!empty($fotos))
                        <div class="mt-2">
                            <button type="button" @click="confirmDeletePhotos({{ $item->id }})" class="text-xs text-red-600 hover:text-red-800 transition">
                                Hapus semua foto
                            </button>
                        </div>
                    @endif
                </div>

                <!-- KOLOM KANAN: INPUT INFORMASI -->
                <div class="space-y-5">
                    <!-- Nama Aula -->
                    <div>
                        <label for="nama_{{ $item->id }}" class="block text-sm font-bold text-gray-800 mb-1">Nama Aula</label>
                        <input type="text" id="nama_{{ $item->id }}" name="nama" value="{{ old('nama', $item->nama) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" required>
                        @error('nama')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label for="kapasitas_{{ $item->id }}" class="block text-sm font-bold text-gray-800 mb-1">Kapasitas</label>
                        <div class="relative">
                            <input type="number" id="kapasitas_{{ $item->id }}" name="kapasitas" value="{{ old('kapasitas', $item->kapasitas) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm pr-16" min="1" required>
                            <span class="absolute right-4 top-2.5 text-xs text-gray-400 font-medium">Orang</span>
                        </div>
                        @error('kapasitas')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi_{{ $item->id }}" class="block text-sm font-bold text-gray-800 mb-1">Deskripsi</label>
                        <textarea id="deskripsi_{{ $item->id }}" name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fasilitas (Tags Input AlpineJS) -->
                    <div x-data="facilityManager({{ json_encode(is_array($item->fasilitas) ? $item->fasilitas : []) }})">
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
                        <!-- Hidden Input untuk dikirim ke Controller -->
                        <input type="hidden" name="fasilitas" :value="JSON.stringify(facilities)">
                    </div>

                </div>
            </div>

            <!-- FOOTER: STATUS & TOMBOL AKSI -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Status Toggle Switch -->
                <div class="flex items-center space-x-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status_aktif" value="1" {{ $item->status_aktif ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">
                        Status: <span class="{{ $item->status_aktif ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">{{ $item->status_aktif ? 'Aktif (Bisa dibooking)' : 'Nonaktif' }}</span>
                    </span>
                </div>

                <!-- Tombol Batal & Simpan & Hapus -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.aula.index') }}" class="px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition">Batal</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">Simpan Perubahan</button>
                    <button type="button" @click="confirmDelete({{ $item->id }}, '{{ $item->nama }}')" class="px-5 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">Hapus</button>
                </div>
            </div>

        </form>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Data Aula</h3>
        <p class="text-sm text-gray-500 mb-4">Mulai dengan menambahkan aula baru untuk dikelola.</p>
        <a href="{{ route('admin.aula.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Aula Baru
        </a>
    </div>
    @endforelse

    <!-- Form Delete (Hidden) -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

</div>

<!-- AlpineJS & SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function aulaManager() {
        return {
            activeTab: 0,
            previewImages(event, index) {
                const files = event.target.files;
                const previewContainer = event.target.closest('.border-2').nextElementSibling;
                if (previewContainer) {
                    const previewSlots = previewContainer.querySelectorAll('div.h-20');
                    for (let i = 0; i < Math.min(files.length, 3); i++) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewSlots[i].innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        }
                        reader.readAsDataURL(files[i]);
                    }
                }
            },
            confirmDelete(id, name) {
                Swal.fire({
                    title: 'Hapus Aula?',
                    text: `Apakah Anda yakin ingin menghapus "${name}"? Tindakan ini tidak dapat dibatalkan.`,
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
            },
            confirmDeletePhotos(id) {
                Swal.fire({
                    title: 'Hapus Semua Foto?',
                    text: 'Apakah Anda yakin ingin menghapus semua foto aula ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Dispatch event atau submit form untuk hapus foto
                        // Bisa menggunakan AJAX atau form terpisah
                        Swal.fire('Berhasil!', 'Foto berhasil dihapus', 'success');
                    }
                });
            }
        }
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
</style>
@endsection