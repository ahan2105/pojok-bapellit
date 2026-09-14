@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Kelola Data Aula')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-12 py-8" x-data="aulaManager()">

    <!-- Tombol Tambah (di kanan) -->
    <div class="mb-8 flex justify-end">
        <a href="{{ route('admin.aula.create') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white text-base font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Aula Baru
        </a>
    </div>

    <!-- Header Judul -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Kelola Data Aula</h1>
        <p class="text-base text-gray-600 mt-2">Perbarui informasi dan fasilitas aula dengan mudah.</p>
    </div>

    <!-- TAB NAVIGASI AULA -->
    <div class="flex items-center space-x-2 mb-8 border-b border-gray-200 pb-5 overflow-x-auto">
        @forelse($aulas as $index => $item)
            <button @click="activeTab = {{ $index }}"
                :class="activeTab === {{ $index }} ? 'bg-indigo-50 text-indigo-600 border-indigo-600 font-semibold shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'"
                class="px-6 py-3 rounded-lg border-2 text-base transition focus:outline-none whitespace-nowrap">
                {{ $item->nama }}
            </button>
        @empty
            <div class="text-gray-500 text-base py-3">Belum ada data aula. Silakan tambahkan aula baru.</div>
        @endforelse
    </div>

    <!-- KONTEN FORM BERDASARKAN TAB AKTIF -->
    @forelse($aulas as $index => $item)
    <div x-show="activeTab === {{ $index }}" x-cloak class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 md:p-10">
        <form action="{{ route('admin.aula.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                <!-- KOLOM KIRI: FOTO AULA -->
                <div>
                    <label class="block text-base font-bold text-gray-800 mb-3">Foto Aula</label>

                    @php
                        $fotos = is_array($item->foto) ? $item->foto : [];
                        $existingCount = count($fotos);
                    @endphp

                    <!-- Box Drag & Drop Utama = sekaligus preview foto BARU pertama -->
                    <div id="dropzone-{{ $index }}"
                         data-aula-id="{{ $item->id }}"
                         data-existing-count="{{ $existingCount }}"
                         class="dropzone-box border-2 border-dashed border-gray-300 rounded-xl relative cursor-pointer flex items-center justify-center min-h-[260px] bg-gray-50 hover:border-indigo-500 transition overflow-hidden">

                        <input type="file" name="foto[]" multiple accept="image/png, image/jpeg, image/webp"
                               class="absolute inset-0 opacity-0 cursor-pointer z-10" id="foto-input-{{ $index }}">

                        <div id="dropzone-content-{{ $index }}" class="w-full h-full flex flex-col items-center justify-center text-center p-10 pointer-events-none">
                            <svg class="w-14 h-14 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-base font-semibold text-gray-700">Drag & drop foto aula atau klik untuk upload</p>
                            <p class="text-sm text-gray-400 mt-2">Format: JPG, PNG, WEBP (Maks. 5MB, total maks 3 foto)</p>
                        </div>
                    </div>

                    <!-- Baris slot foto -->
                    <div class="grid grid-cols-3 gap-4 mt-5" id="slot-row-{{ $index }}">
                        @for($i = 0; $i < 3; $i++)
                            <div class="h-28 border-2 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden relative border-gray-200 shadow-inner foto-slot" id="slot-{{ $index }}-{{ $i }}">
                                @if(isset($fotos[$i]) && !empty($fotos[$i]))
                                    <img src="{{ asset('storage/' . $fotos[$i]) }}" class="w-full h-full object-cover" alt="Foto Aula {{ $i+1 }}">
                                    <button type="button"
                                        class="btn-delete-existing absolute top-0 right-0 bg-red-600 hover:bg-red-700 text-white text-sm w-7 h-7 rounded-bl-lg leading-7 font-bold"
                                        data-aula-id="{{ $item->id }}" data-photo-index="{{ $i }}" data-tab-index="{{ $index }}"
                                        title="Hapus foto ini">&times;</button>
                                @else
                                    <div class="text-gray-300 flex items-center justify-center w-full h-full">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                    <p class="text-sm text-gray-400 mt-3 flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-sm bg-green-500"></span>
                        = foto baru, belum disimpan (klik "Simpan Perubahan" untuk menyimpan)
                    </p>
                </div>

                <!-- KOLOM KANAN: INPUT INFORMASI -->
                <div class="space-y-6">
                    <!-- Nama Aula -->
                    <div>
                        <label for="nama_{{ $item->id }}" class="block text-base font-bold text-gray-800 mb-2">Nama Aula</label>
                        <input type="text" id="nama_{{ $item->id }}" name="nama" value="{{ old('nama', $item->nama) }}" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" required>
                        @error('nama')
                            <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label for="kapasitas_{{ $item->id }}" class="block text-base font-bold text-gray-800 mb-2">Kapasitas</label>
                        <div class="relative">
                            <input type="number" id="kapasitas_{{ $item->id }}" name="kapasitas" value="{{ old('kapasitas', $item->kapasitas) }}" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base pr-20" min="1" required>
                            <span class="absolute right-4 top-3.5 text-sm text-gray-400 font-medium">Orang</span>
                        </div>
                        @error('kapasitas')
                            <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi_{{ $item->id }}" class="block text-base font-bold text-gray-800 mb-2">Deskripsi</label>
                        <textarea id="deskripsi_{{ $item->id }}" name="deskripsi" rows="4" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base leading-relaxed">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informasi Tambahan -->
                    <div>
                        <label for="informasi_tambahan_{{ $item->id }}" class="block text-base font-bold text-gray-800 mb-2">Informasi Tambahan</label>
                        <textarea id="informasi_tambahan_{{ $item->id }}" name="informasi_tambahan" rows="5" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base leading-relaxed">{{ old('informasi_tambahan', $item->informasi_tambahan) }}</textarea>
                        <p class="text-sm text-gray-400 mt-2">Contoh: Tersedia parkir VIP, Wi-Fi, dll. (pisahkan dengan enter)</p>
                        @error('informasi_tambahan')
                            <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label for="lokasi_{{ $item->id }}" class="block text-base font-bold text-gray-800 mb-2">Lokasi</label>
                        <input type="text" id="lokasi_{{ $item->id }}" name="lokasi" value="{{ old('lokasi', $item->lokasi) }}" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" 
                            placeholder="Contoh: Gedung A, Lantai 2">
                        @error('lokasi')
                            <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fasilitas (Tags Input AlpineJS) -->
                    <div x-data="facilityManager({{ json_encode(is_array($item->fasilitas) ? $item->fasilitas : []) }})">
                        <label class="block text-base font-bold text-gray-800 mb-2">Fasilitas</label>
                        <div class="flex flex-wrap gap-2.5 p-3 border border-gray-300 rounded-lg bg-white min-h-[54px] items-center">
                            <template x-for="(fac, fIndex) in facilities" :key="fIndex">
                                <span class="inline-flex items-center bg-indigo-50 text-indigo-700 text-sm font-semibold px-3 py-1.5 rounded-full border border-indigo-100">
                                    <span x-text="fac"></span>
                                    <button type="button" @click="removeFacility(fIndex)" class="ml-2 text-indigo-400 hover:text-indigo-600 text-lg leading-none">&times;</button>
                                </span>
                            </template>
                            <input type="text" @keydown.enter.prevent="addFacility($event)" 
                                placeholder="Tambah fasilitas lalu tekan Enter..." 
                                class="flex-1 min-w-[160px] text-base outline-none px-1.5 py-1.5">
                        </div>
                        <input type="hidden" name="fasilitas" :value="JSON.stringify(facilities)">
                    </div>

                </div>
            </div>

            <!-- FOOTER: STATUS & TOMBOL AKSI -->
            <div class="mt-10 pt-7 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-5">
                <!-- Status Toggle Switch -->
                <div class="flex items-center space-x-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status_aktif" value="1" {{ $item->status_aktif ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                    <span class="text-base font-medium text-gray-700">
                        Status: <span class="{{ $item->status_aktif ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">{{ $item->status_aktif ? 'Aktif (Bisa dibooking)' : 'Nonaktif' }}</span>
                    </span>
                </div>

                <!-- Tombol Batal & Simpan & Hapus -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.aula.index') }}" 
                       class="px-6 py-3 text-base font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit" 
                        class="px-7 py-3 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                        Simpan Perubahan
                    </button>
                    <button type="button" @click="confirmDelete({{ $item->id }}, '{{ $item->nama }}')" 
                        class="px-6 py-3 text-base font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                        Hapus
                    </button>
                </div>
            </div>

        </form>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
        <svg class="w-20 h-20 text-gray-300 mx-auto mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <h3 class="text-2xl font-semibold text-gray-700 mb-2">Belum Ada Data Aula</h3>
        <p class="text-base text-gray-500 mb-6">Mulai dengan menambahkan aula baru untuk dikelola.</p>
        <a href="{{ route('admin.aula.create') }}" 
           class="inline-flex items-center gap-2 px-7 py-3 bg-indigo-600 text-white text-base font-semibold rounded-lg hover:bg-indigo-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function aulaManager() {
        return {
            activeTab: 0,
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

    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const fotoData = {};

        const emptySlotHtml = `<div class="text-gray-300 flex items-center justify-center w-full h-full">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>`;

        const defaultDropzoneHtml = `
            <svg class="w-14 h-14 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            <p class="text-base font-semibold text-gray-700">Drag & drop foto aula atau klik untuk upload</p>
            <p class="text-sm text-gray-400 mt-2">Format: JPG, PNG, WEBP (Maks. 5MB, total maks 3 foto)</p>
        `;

        document.querySelectorAll('input[id^="foto-input-"]').forEach(function (input) {
            const index = input.id.replace('foto-input-', '');
            fotoData[index] = new DataTransfer();

            const dropzone = document.getElementById('dropzone-' + index);
            const existingCount = parseInt(dropzone.getAttribute('data-existing-count'), 10) || 0;

            input.addEventListener('change', function (e) {
                const newFiles = Array.from(e.target.files);
                const sisaSlot = 3 - existingCount - fotoData[index].items.length;

                if (sisaSlot <= 0) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Slot Foto Penuh',
                            text: 'Total foto (tersimpan + baru) sudah mencapai 3. Hapus salah satu dulu untuk menambah.',
                            confirmButtonColor: '#f59e0b'
                        });
                    }
                    input.value = '';
                    return;
                }

                const filesToAdd = newFiles.slice(0, sisaSlot);
                if (newFiles.length > filesToAdd.length && typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maksimal 3 Foto',
                        text: 'Hanya ' + filesToAdd.length + ' foto yang ditambahkan (sisa slot terbatas).',
                        confirmButtonColor: '#f59e0b'
                    });
                }

                filesToAdd.forEach(file => fotoData[index].items.add(file));
                input.files = fotoData[index].files;

                renderAll(index, input, existingCount);
            });
        });

        function renderAll(index, input, existingCount) {
            const dropzone = document.getElementById('dropzone-' + index);
            const dropContent = document.getElementById('dropzone-content-' + index);
            const newFiles = Array.from(fotoData[index].files);

            if (newFiles.length > 0) {
                dropzone.classList.remove('border-gray-300', 'border-dashed');
                dropzone.classList.add('border-green-500', 'border-solid');

                const reader = new FileReader();
                reader.onload = function (e) {
                    dropContent.innerHTML = `
                        <img src="${e.target.result}" class="absolute inset-0 w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-green-600 text-white text-sm font-semibold px-3 py-1 rounded-full z-20">Belum disimpan</span>
                        <button type="button" class="btn-remove-big absolute top-3 right-3 bg-red-600 hover:bg-red-700 text-white text-base w-8 h-8 rounded-full leading-8 font-bold z-20">&times;</button>
                        <span class="absolute bottom-3 left-3 right-3 text-center text-sm bg-black/60 text-white rounded-lg px-3 py-2 z-20">Klik untuk tambah foto lagi</span>
                    `;
                    dropContent.classList.remove('pointer-events-none');
                    dropContent.querySelector('.btn-remove-big').addEventListener('click', function (ev) {
                        ev.stopPropagation();
                        removeNewFile(index, 0, input, existingCount);
                    });
                };
                reader.readAsDataURL(newFiles[0]);
            } else {
                dropzone.classList.add('border-gray-300', 'border-dashed');
                dropzone.classList.remove('border-green-500', 'border-solid');
                dropContent.classList.add('pointer-events-none');
                dropContent.innerHTML = defaultDropzoneHtml;
            }

            const overflow = newFiles.slice(1);
            for (let i = 0; i < 3; i++) {
                const slot = document.getElementById('slot-' + index + '-' + i);
                if (!slot) continue;

                if (i < existingCount) continue;

                const overflowIdx = i - existingCount;
                slot.classList.remove('border-gray-200');

                if (overflow[overflowIdx]) {
                    slot.classList.add('border-green-500');
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        slot.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                            <span class="absolute bottom-0 left-0 right-0 bg-green-600 text-white text-xs text-center font-semibold py-1">Baru</span>
                            <button type="button" class="btn-remove-overflow absolute top-0 right-0 bg-red-600 hover:bg-red-700 text-white text-sm w-7 h-7 rounded-bl-lg leading-7 font-bold" data-overflow-index="${overflowIdx}">&times;</button>
                        `;
                        slot.querySelector('.btn-remove-overflow').addEventListener('click', function () {
                            removeNewFile(index, overflowIdx + 1, input, existingCount);
                        });
                    };
                    reader.readAsDataURL(overflow[overflowIdx]);
                } else {
                    slot.classList.add('border-gray-200');
                    slot.innerHTML = emptySlotHtml;
                }
            }
        }

        function removeNewFile(index, fileArrayIndex, input, existingCount) {
            const dt = new DataTransfer();
            Array.from(fotoData[index].files).forEach((f, idx) => {
                if (idx !== fileArrayIndex) dt.items.add(f);
            });
            fotoData[index] = dt;
            input.files = dt.files;
            renderAll(index, input, existingCount);
        }

        document.querySelectorAll('.btn-delete-existing').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const aulaId = this.getAttribute('data-aula-id');
                const photoIndex = this.getAttribute('data-photo-index');

                Swal.fire({
                    title: 'Hapus Foto Ini?',
                    text: 'Foto akan langsung dihapus dari server.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    fetch(`/admin/aula/${aulaId}/delete-photo`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ index: photoIndex })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil!', 'Foto berhasil dihapus', 'success')
                                .then(() => window.location.reload());
                        } else {
                            Swal.fire('Gagal!', data.message || 'Gagal menghapus foto', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
                    });
                });
            });
        });
    });
</script>

<style>
    [x-cloak] { display: none !important; }
    .dropzone-box { transition: border-color .15s ease; }
    .foto-slot img { width: 100%; height: 100%; object-fit: cover; }
</style>
@endsection