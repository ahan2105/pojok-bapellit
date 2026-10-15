@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Kelola Data Aula')

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

    /* Tombol Tambah di hero */
    .bapelit-hero a.btn-hero-add,
    .bapelit-hero a.btn-hero-add:link,
    .bapelit-hero a.btn-hero-add:visited,
    .bapelit-hero a.btn-hero-add:hover,
    .bapelit-hero a.btn-hero-add:focus,
    .bapelit-hero a.btn-hero-add:active {
        background-color: #facc15 !important;
        background-image: linear-gradient(135deg, #facc15 0%, #eab308 100%) !important;
        color: #1e3a8a !important;
        text-decoration: none !important;
        border: none !important;
        box-shadow: 0 10px 20px -5px rgba(250, 204, 21, 0.45) !important;
        position: relative;
        z-index: 10;
        transition: all 0.2s ease;
    }
    .bapelit-hero a.btn-hero-add:hover {
        background-image: linear-gradient(135deg, #fde047 0%, #facc15 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 14px 26px -6px rgba(250, 204, 21, 0.6) !important;
    }
    .bapelit-hero a.btn-hero-add svg {
        color: #1e3a8a !important;
        stroke: #1e3a8a !important;
    }

    /* Tab aula aktif */
    .tab-aula {
        transition: all .18s ease;
    }
    .tab-aula-active {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        box-shadow: 0 8px 18px -6px rgba(79, 70, 229, 0.55) !important;
        transform: translateY(-1px);
    }

    /* Dropzone */
    .dropzone-box { transition: all .15s ease; }
    .dropzone-box:hover { transform: translateY(-2px); }
    .foto-slot img { width: 100%; height: 100%; object-fit: cover; }

    [x-cloak] { display: none !important; }
</style>

<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-10 py-6" x-data="aulaManager()">

    {{-- ===== HERO ===== --}}
    <div class="bapelit-hero text-white rounded-2xl px-6 sm:px-8 py-6 mb-6 shadow-lg">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center flex-shrink-0 ring-1 ring-white/20">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                  
                    <h1 class="text-xl sm:text-2xl font-extrabold leading-tight">Kelola Data Aula</h1>
                    <p class="text-xs sm:text-sm text-blue-200 mt-0.5">Perbarui informasi dan fasilitas aula dengan mudah.</p>
                </div>
            </div>
            <a href="{{ route('admin.aula.create') }}"
               class="btn-hero-add inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Aula Baru
            </a>
        </div>
    </div>

    {{-- ===== TAB NAVIGASI AULA ===== --}}
    <div class="mb-6">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Pilih Aula</p>
        <div class="flex items-center gap-2 overflow-x-auto pb-2">
            @forelse($aulas as $index => $item)
                <button @click="activeTab = {{ $index }}"
                    :class="activeTab === {{ $index }} ? 'tab-aula-active' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50 hover:border-gray-300'"
                    class="tab-aula px-5 py-2.5 rounded-xl border-2 text-sm font-semibold whitespace-nowrap focus:outline-none">
                    <span class="inline-flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full"
                              :class="activeTab === {{ $index }} ? 'bg-yellow-400' : 'bg-gray-300'"></span>
                        {{ $item->nama }}
                    </span>
                </button>
            @empty
                <div class="text-gray-500 text-sm py-2">Belum ada data aula. Silakan tambahkan aula baru.</div>
            @endforelse
        </div>
    </div>

    {{-- ===== KONTEN FORM ===== --}}
    @forelse($aulas as $index => $item)
    <div x-show="activeTab === {{ $index }}" x-cloak
         class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Sub-header aula aktif --}}
        <div class="px-6 sm:px-8 py-4 bg-gradient-to-r from-indigo-50 to-white border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-semibold text-indigo-500 uppercase tracking-widest">Sedang Diedit</p>
                    <p class="text-base font-bold text-gray-800">{{ $item->nama }}</p>
                </div>
            </div>
            <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold
                         {{ $item->status_aktif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $item->status_aktif ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>

        <form action="{{ route('admin.aula.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- ===== KOLOM KIRI: FOTO ===== --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-bold text-gray-800">Foto Aula</label>
                        <span class="text-xs text-gray-400">Maks. 3 foto</span>
                    </div>

                    @php
                        $fotos = is_array($item->foto) ? $item->foto : [];
                        $existingCount = count($fotos);
                    @endphp

                    <div id="dropzone-{{ $index }}"
                         data-aula-id="{{ $item->id }}"
                         data-existing-count="{{ $existingCount }}"
                         class="dropzone-box border-2 border-dashed border-gray-300 rounded-xl relative cursor-pointer flex items-center justify-center min-h-[240px] bg-gray-50 hover:border-indigo-500 overflow-hidden">

                        <input type="file" name="foto[]" multiple accept="image/png, image/jpeg, image/webp"
                               class="absolute inset-0 opacity-0 cursor-pointer z-10" id="foto-input-{{ $index }}">

                        <div id="dropzone-content-{{ $index }}" class="w-full h-full flex flex-col items-center justify-center text-center p-8 pointer-events-none">
                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-700">Drag & drop atau klik untuk upload</p>
                            <p class="text-xs text-gray-400 mt-1.5">JPG, PNG, WEBP (Maks. 5MB)</p>
                        </div>
                    </div>

                    {{-- Slot Foto --}}
                    <div class="grid grid-cols-3 gap-3 mt-4" id="slot-row-{{ $index }}">
                        @for($i = 0; $i < 3; $i++)
                            <div class="h-24 border-2 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden relative border-gray-200 foto-slot"
                                 id="slot-{{ $index }}-{{ $i }}">
                                @if(isset($fotos[$i]) && !empty($fotos[$i]))
                                    <img src="{{ asset('storage/' . $fotos[$i]) }}" class="w-full h-full object-cover" alt="Foto Aula {{ $i+1 }}">
                                    <button type="button"
                                        class="btn-delete-existing absolute top-0 right-0 bg-red-600 hover:bg-red-700 text-white text-xs w-6 h-6 rounded-bl-lg leading-6 font-bold z-10"
                                        data-aula-id="{{ $item->id }}" data-photo-index="{{ $i }}" data-tab-index="{{ $index }}"
                                        title="Hapus foto ini">&times;</button>
                                @else
                                    <div class="text-gray-300 flex items-center justify-center w-full h-full">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>

                    <p class="text-xs text-gray-400 mt-3 flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-sm bg-green-500"></span>
                        Foto baru belum disimpan — klik <strong class="text-gray-600">Simpan Perubahan</strong>
                    </p>
                </div>

                {{-- ===== KOLOM KANAN: INFORMASI ===== --}}
                <div class="space-y-5">

                    {{-- Nama Aula --}}
                    <div>
                        <label for="nama_{{ $item->id }}" class="block text-sm font-bold text-gray-800 mb-2">Nama Aula</label>
                        <input type="text" id="nama_{{ $item->id }}" name="nama" value="{{ old('nama', $item->nama) }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm" required>
                        @error('nama')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Kapasitas --}}
                    <div>
                        <label for="kapasitas_{{ $item->id }}" class="block text-sm font-bold text-gray-800 mb-2">Kapasitas</label>
                        <div class="relative">
                            <input type="number" id="kapasitas_{{ $item->id }}" name="kapasitas" value="{{ old('kapasitas', $item->kapasitas) }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm pr-20" min="1" required>
                            <span class="absolute right-4 top-2.5 text-xs text-gray-400 font-medium">Orang</span>
                        </div>
                        @error('kapasitas')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="deskripsi_{{ $item->id }}" class="block text-sm font-bold text-gray-800 mb-2">Deskripsi</label>
                        <textarea id="deskripsi_{{ $item->id }}" name="deskripsi" rows="3"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm leading-relaxed">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                        @error('deskripsi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Informasi Tambahan --}}
                    <div>
                        <label for="informasi_tambahan_{{ $item->id }}" class="block text-sm font-bold text-gray-800 mb-2">Informasi Tambahan</label>
                        <textarea id="informasi_tambahan_{{ $item->id }}" name="informasi_tambahan" rows="3"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm leading-relaxed">{{ old('informasi_tambahan', $item->informasi_tambahan) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1.5">Contoh: Parkir VIP, Wi-Fi, dll.</p>
                        @error('informasi_tambahan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Lokasi --}}
                    <div>
                        <label for="lokasi_{{ $item->id }}" class="block text-sm font-bold text-gray-800 mb-2">Lokasi</label>
                        <input type="text" id="lokasi_{{ $item->id }}" name="lokasi" value="{{ old('lokasi', $item->lokasi) }}"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm"
                            placeholder="Contoh: Gedung A, Lantai 2">
                        @error('lokasi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Fasilitas --}}
                    <div x-data="facilityManager({{ json_encode(is_array($item->fasilitas) ? $item->fasilitas : []) }})">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Fasilitas</label>
                        <div class="flex flex-wrap gap-2 p-3 border border-gray-300 rounded-lg bg-white min-h-[52px] items-center focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition">
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
                    </div>

                </div>
            </div>

            {{-- ===== FOOTER: STATUS & AKSI ===== --}}
            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                {{-- Toggle Status --}}
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status_aktif" value="1" {{ $item->status_aktif ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">
                        Status:
                        <span class="{{ $item->status_aktif ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                            {{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </span>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center gap-2 flex-wrap justify-end">
                    <a href="{{ route('admin.aula.index') }}"
                       class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        Batal
                    </a>
                    <button type="button" @click="confirmDelete({{ $item->id }}, '{{ $item->nama }}')"
                        class="px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                        Hapus
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>

        </form>
    </div>
    @empty
    {{-- ===== EMPTY STATE ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-indigo-50 mb-5">
            <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-1">Belum Ada Data Aula</h3>
        <p class="text-sm text-gray-500 mb-6">Mulai dengan menambahkan aula baru untuk dikelola.</p>
        <a href="{{ route('admin.aula.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Aula Baru
        </a>
    </div>
    @endforelse

    {{-- Form Delete (Hidden) --}}
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                       || document.querySelector('input[name="_token"]')?.value;
        const fotoData = {};

        const emptySlotHtml = `<div class="text-gray-300 flex items-center justify-center w-full h-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>`;

        const defaultDropzoneHtml = `
            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            <p class="text-sm font-semibold text-gray-700">Drag & drop atau klik untuk upload</p>
            <p class="text-xs text-gray-400 mt-1.5">JPG, PNG, WEBP (Maks. 5MB)</p>
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
                        Swal.fire({ icon: 'warning', title: 'Slot Foto Penuh', text: 'Total foto sudah mencapai 3.', confirmButtonColor: '#f59e0b' });
                    }
                    input.value = '';
                    return;
                }

                const filesToAdd = newFiles.slice(0, sisaSlot);
                if (newFiles.length > filesToAdd.length && typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: 'Maksimal 3 Foto', text: 'Hanya ' + filesToAdd.length + ' foto ditambahkan.', confirmButtonColor: '#f59e0b' });
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
                        <span class="absolute top-3 left-3 bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-full z-20">Belum disimpan</span>
                        <button type="button" class="btn-remove-big absolute top-3 right-3 bg-red-600 hover:bg-red-700 text-white text-base w-8 h-8 rounded-full leading-8 font-bold z-20">&times;</button>
                        <span class="absolute bottom-3 left-3 right-3 text-center text-xs bg-black/60 text-white rounded-lg px-3 py-1.5 z-20">Klik untuk tambah foto lagi</span>
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
                            <span class="absolute bottom-0 left-0 right-0 bg-green-600 text-white text-[10px] text-center font-semibold py-1">Baru</span>
                            <button type="button" class="btn-remove-overflow absolute top-0 right-0 bg-red-600 hover:bg-red-700 text-white text-xs w-6 h-6 rounded-bl-lg leading-6 font-bold" data-overflow-index="${overflowIdx}">&times;</button>
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

                    Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });

                    fetch(`/admin/aula/${aulaId}/delete-photo`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ index: photoIndex, _method: 'DELETE' })
                    })
                    .then(response => {
                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.indexOf("application/json") !== -1) return response.json();
                        throw new TypeError("Server tidak mengembalikan JSON!");
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Foto berhasil dihapus', timer: 1500, showConfirmButton: false })
                                .then(() => { window.location.reload(); });
                        } else {
                            Swal.fire('Gagal!', data.message || 'Gagal menghapus foto', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error detail:', error);
                        Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
                    });
                });
            });
        });
    });
</script>
@endsection 