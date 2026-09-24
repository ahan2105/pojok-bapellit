@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Buat Sesi Absensi Baru')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Buat Sesi Absensi Baru</h1>
        <p class="text-base text-gray-600 mt-2">Buat sesi absensi untuk rapat, event, atau kegiatan lainnya.</p>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 md:p-10">
        <form action="{{ route('admin.absensi.store') }}" method="POST"
              x-data="filterPeserta({{ json_encode($bidangList ?? []) }}, {{ json_encode($jabatanList ?? []) }}, {{ json_encode($userList ?? []) }})">
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
            </div>

            <!-- ============================================ -->
            <!-- PILIH PESERTA -->
            <!-- ============================================ -->
            <div class="mt-8 pt-8 border-t-2 border-dashed border-gray-200">
                <div class="mb-5">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Pilih Peserta
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        <strong>Default: tidak ada peserta.</strong> Centang bidang, jabatan, atau nama yang <strong>akan ikut</strong> sesi ini.
                    </p>
                </div>

                {{-- Info: berapa user yang akan ikut --}}
                <div class="mb-5 p-4 rounded-xl border-2 flex items-center gap-3 transition-colors"
                     :class="jumlahPesertaAkhir > 0 ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200'">
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors"
                         :class="jumlahPesertaAkhir > 0 ? 'text-emerald-600' : 'text-red-600'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm transition-colors"
                         :class="jumlahPesertaAkhir > 0 ? 'text-emerald-700' : 'text-red-700'">
                        <strong x-text="jumlahPesertaAkhir"></strong> user akan masuk sebagai peserta sesi ini.
                        <span x-show="jumlahPesertaAkhir === 0" class="block text-xs mt-0.5">
                            Minimal pilih 1 bidang, jabatan, atau nama.
                        </span>
                    </div>
                </div>

                <div class="space-y-6">

                    <!-- ============================================ -->
                    <!-- Pilih Bidang (CHECKBOX LIST) -->
                    <!-- ============================================ -->
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                </svg>
                                Bidang
                                <span class="text-xs font-normal text-gray-400">
                                    (<span x-text="pilihBidang.length"></span> dipilih)
                                </span>
                            </label>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        @click="pilihSemuaBidang()"
                                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                                    Pilih Semua
                                </button>
                                <span class="text-gray-300">|</span>
                                <button type="button"
                                        @click="pilihBidang = []"
                                        class="text-xs font-semibold text-gray-500 hover:text-gray-700 hover:underline">
                                    Kosongkan
                                </button>
                            </div>
                        </div>

                        @if(count($bidangList ?? []) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1.5 max-h-48 overflow-y-auto pr-1">
                                @foreach($bidangList as $bidang)
                                    <label class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-indigo-50 cursor-pointer transition-colors">
                                        <input type="checkbox"
                                               value="{{ $bidang }}"
                                               x-model="pilihBidang"
                                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700 flex-1 truncate">{{ $bidang }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-gray-500 text-center py-3">
                                Tidak ada bidang tersedia
                            </div>
                        @endif

                        {{-- Hidden input array --}}
                        <template x-for="bidang in pilihBidang" :key="'hb-' + bidang">
                            <input type="hidden" name="pilih_bidang[]" :value="bidang">
                        </template>
                    </div>

                    <!-- ============================================ -->
                    <!-- Pilih Jabatan (CHECKBOX LIST) -->
                    <!-- ============================================ -->
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Jabatan
                                <span class="text-xs font-normal text-gray-400">
                                    (<span x-text="pilihJabatan.length"></span> dipilih)
                                </span>
                            </label>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        @click="pilihSemuaJabatan()"
                                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                                    Pilih Semua
                                </button>
                                <span class="text-gray-300">|</span>
                                <button type="button"
                                        @click="pilihJabatan = []"
                                        class="text-xs font-semibold text-gray-500 hover:text-gray-700 hover:underline">
                                    Kosongkan
                                </button>
                            </div>
                        </div>

                        @if(count($jabatanList ?? []) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1.5 max-h-48 overflow-y-auto pr-1">
                                @foreach($jabatanList as $jabatan)
                                    <label class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-indigo-50 cursor-pointer transition-colors">
                                        <input type="checkbox"
                                               value="{{ $jabatan }}"
                                               x-model="pilihJabatan"
                                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700 flex-1 truncate">{{ $jabatan }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-gray-500 text-center py-3">
                                Tidak ada jabatan tersedia
                            </div>
                        @endif

                        {{-- Hidden input array --}}
                        <template x-for="jabatan in pilihJabatan" :key="'hj-' + jabatan">
                            <input type="hidden" name="pilih_jabatan[]" :value="jabatan">
                        </template>
                    </div>

                    <!-- ============================================ -->
                    <!-- Pilih Nama Spesifik (INCLUDE — SEARCH + CHIPS) -->
                    <!-- ============================================ -->
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-bold text-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Tambah Nama Spesifik
                                <span class="text-xs font-normal text-gray-400">
                                    (<span x-text="pilihUsers.length"></span> dipilih)
                                </span>
                            </label>
                        </div>

                        {{-- Input pencarian nama --}}
                        <div class="relative">
                            <input type="text"
                                   x-model="searchNama"
                                   @input="showSuggestions = true"
                                   @click="showSuggestions = true"
                                   @click.outside="showSuggestions = false"
                                   placeholder="Ketik nama untuk menambah peserta..."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none pr-10">

                            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>

                            {{-- Suggestions dropdown --}}
                            <div x-show="showSuggestions && filteredUsers.length > 0"
                                 x-cloak
                                 x-transition
                                 class="absolute z-30 left-0 right-0 mt-1 max-h-60 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-lg">
                                <template x-for="user in filteredUsers.slice(0, 15)" :key="user.id">
                                    <button type="button"
                                            @click="addPilihUser(user)"
                                            class="w-full text-left px-4 py-2.5 hover:bg-indigo-50 border-b border-gray-100 last:border-0 flex items-center justify-between gap-2">
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-semibold text-gray-800 truncate" x-text="user.name"></div>
                                            <div class="text-xs text-gray-500 truncate">
                                                <span x-text="user.jabatan || '-'"></span>
                                                <span> · </span>
                                                <span x-text="user.bidang || '-'"></span>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </template>
                            </div>

                            {{-- No result --}}
                            <div x-show="showSuggestions && searchNama.length > 0 && filteredUsers.length === 0"
                                 x-cloak
                                 class="absolute z-30 left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg p-3">
                                <p class="text-sm text-gray-500 text-center">Tidak ada user yang cocok</p>
                            </div>
                        </div>

                        {{-- Chips user terpilih --}}
                        <div x-show="pilihUsers.length > 0" x-cloak class="mt-3">
                            <div class="flex flex-wrap gap-2">
                                <template x-for="user in pilihUsers" :key="user.id">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full text-sm font-medium">
                                        <span x-text="user.name"></span>
                                        <button type="button"
                                                @click="removePilihUser(user.id)"
                                                class="text-indigo-400 hover:text-indigo-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                            </div>
                        </div>

                        {{-- Hidden input: daftar ID user yang dipilih --}}
                        <template x-for="user in pilihUsers" :key="'hidden-' + user.id">
                            <input type="hidden" name="pilih_user_ids[]" :value="user.id">
                        </template>
                    </div>

                    <!-- ============================================ -->
                    <!-- KECUALIKAN NAMA (EXCLUDE — PRIORITAS TERTINGGI) -->
                    <!-- ============================================ -->
                    <div class="border-2 border-red-200 bg-red-50/30 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-bold text-red-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Kecualikan Nama
                                <span class="text-xs font-normal text-red-500">
                                    (<span x-text="excludedUsers.length"></span> dikecualikan)
                                </span>
                            </label>
                        </div>

                        <p class="text-xs text-red-600 mb-3 leading-relaxed">
                            <strong>Prioritas tertinggi.</strong> User yang dikecualikan di sini akan <strong>dikeluarkan</strong> dari peserta, meskipun bidang/jabatannya sudah dipilih di atas.
                        </p>

                        {{-- Input pencarian nama untuk exclude --}}
                        <div class="relative">
                            <input type="text"
                                   x-model="searchExcludeNama"
                                   @input="showExcludeSuggestions = true"
                                   @click="showExcludeSuggestions = true"
                                   @click.outside="showExcludeSuggestions = false"
                                   placeholder="Ketik nama untuk dikecualikan..."
                                   class="w-full px-4 py-3 border border-red-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:outline-none pr-10 bg-white">

                            <svg class="w-4 h-4 text-red-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>

                            {{-- Suggestions dropdown --}}
                            <div x-show="showExcludeSuggestions && filteredExcludeUsers.length > 0"
                                 x-cloak
                                 x-transition
                                 class="absolute z-30 left-0 right-0 mt-1 max-h-60 overflow-y-auto bg-white border border-red-200 rounded-lg shadow-lg">
                                <template x-for="user in filteredExcludeUsers.slice(0, 15)" :key="user.id">
                                    <button type="button"
                                            @click="addExcludeUser(user)"
                                            class="w-full text-left px-4 py-2.5 hover:bg-red-50 border-b border-gray-100 last:border-0 flex items-center justify-between gap-2">
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-semibold text-gray-800 truncate" x-text="user.name"></div>
                                            <div class="text-xs text-gray-500 truncate">
                                                <span x-text="user.jabatan || '-'"></span>
                                                <span> · </span>
                                                <span x-text="user.bidang || '-'"></span>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                </template>
                            </div>

                            {{-- No result --}}
                            <div x-show="showExcludeSuggestions && searchExcludeNama.length > 0 && filteredExcludeUsers.length === 0"
                                 x-cloak
                                 class="absolute z-30 left-0 right-0 mt-1 bg-white border border-red-200 rounded-lg shadow-lg p-3">
                                <p class="text-sm text-gray-500 text-center">Tidak ada user yang cocok</p>
                            </div>
                        </div>

                        {{-- Chips user yang dikecualikan --}}
                        <div x-show="excludedUsers.length > 0" x-cloak class="mt-3">
                            <div class="flex flex-wrap gap-2">
                                <template x-for="user in excludedUsers" :key="'ex-' + user.id">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-100 text-red-700 border border-red-300 rounded-full text-sm font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span x-text="user.name"></span>
                                        <button type="button"
                                                @click="removeExcludeUser(user.id)"
                                                class="text-red-400 hover:text-red-600 ml-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                            </div>
                        </div>

                        {{-- Hidden input: daftar ID user yang dikecualikan --}}
                        <template x-for="user in excludedUsers" :key="'hidden-ex-' + user.id">
                            <input type="hidden" name="exclude_user_ids[]" :value="user.id">
                        </template>
                    </div>

                </div>
            </div>

            <!-- Info Tambahan -->
            <div class="mt-8 p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-indigo-700">
                    <p class="font-semibold mb-1">Sistem akan otomatis:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        <li>Membuat sesi absensi baru</li>
                        <li>Menambahkan user yang <strong>dipilih</strong> (dari bidang, jabatan, atau nama)</li>
                        <li><strong>Menghapus user</strong> yang ada di daftar "Kecualikan Nama" — <em>prioritas tertinggi</em></li>
                        <li><strong>Generate QR Code unik</strong> untuk sesi ini</li>
                    </ul>
                    <p class="mt-2 text-xs text-indigo-600">
                        💡 Filter bidang/jabatan/nama bersifat <strong>union</strong> (gabungan). Tapi <strong>exclude nama</strong> selalu <strong>memotong</strong> hasilnya.
                    </p>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-10 pt-7 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('admin.absensi.index') }}" 
                   class="px-6 py-3.5 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        :disabled="jumlahPesertaAkhir === 0"
                        :class="jumlahPesertaAkhir === 0
                            ? 'bg-gray-300 cursor-not-allowed'
                            : 'bg-indigo-600 hover:bg-indigo-700'"
                        class="inline-flex items-center gap-2 px-7 py-3.5 text-base font-semibold text-white rounded-lg transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span x-text="jumlahPesertaAkhir === 0 ? 'Pilih Peserta Dulu' : 'Buat Sesi Absensi'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterPeserta(bidangList = [], jabatanList = [], userList = []) {
        return {
            // Data master
            bidangList: bidangList,
            jabatanList: jabatanList,
            userList: userList,

            // Include (default: kosong = tidak ada yang ikut)
            pilihBidang: [],
            pilihJabatan: [],
            pilihUsers: [],

            // ⭐ Exclude (prioritas tertinggi)
            excludedUsers: [],

            // Search state — include
            searchNama: '',
            showSuggestions: false,

            // Search state — exclude
            searchExcludeNama: '',
            showExcludeSuggestions: false,

            // ===== Hitung total user yang akan ikut =====
            get jumlahPesertaAkhir() {
                const pesertaIds = new Set();

                // Dari bidang
                if (this.pilihBidang.length > 0) {
                    this.userList.forEach(u => {
                        if (this.pilihBidang.includes(u.bidang)) {
                            pesertaIds.add(u.id);
                        }
                    });
                }

                // Dari jabatan
                if (this.pilihJabatan.length > 0) {
                    this.userList.forEach(u => {
                        if (this.pilihJabatan.includes(u.jabatan)) {
                            pesertaIds.add(u.id);
                        }
                    });
                }

                // Dari nama spesifik (include)
                this.pilihUsers.forEach(u => {
                    pesertaIds.add(u.id);
                });

                // ⭐ Kurangi yang dikecualikan (prioritas tertinggi)
                this.excludedUsers.forEach(u => {
                    pesertaIds.delete(u.id);
                });

                return pesertaIds.size;
            },

            // ===== Pilih semua bidang =====
            pilihSemuaBidang() {
                this.pilihBidang = [...this.bidangList];
            },

            // ===== Pilih semua jabatan =====
            pilihSemuaJabatan() {
                this.pilihJabatan = [...this.jabatanList];
            },

            // ===== Suggestions user include =====
            get filteredUsers() {
                if (!this.searchNama || this.searchNama.length < 1) return [];
                const q = this.searchNama.toLowerCase();
                const selectedIds = this.pilihUsers.map(u => u.id);
                const excludedIds = this.excludedUsers.map(u => u.id);
                return this.userList.filter(u => {
                    if (selectedIds.includes(u.id)) return false;
                    if (excludedIds.includes(u.id)) return false;
                    return u.name.toLowerCase().includes(q) ||
                           (u.jabatan && u.jabatan.toLowerCase().includes(q)) ||
                           (u.bidang && u.bidang.toLowerCase().includes(q));
                });
            },

            // ===== Suggestions user exclude =====
            get filteredExcludeUsers() {
                if (!this.searchExcludeNama || this.searchExcludeNama.length < 1) return [];
                const q = this.searchExcludeNama.toLowerCase();
                const excludedIds = this.excludedUsers.map(u => u.id);
                return this.userList.filter(u => {
                    if (excludedIds.includes(u.id)) return false;
                    return u.name.toLowerCase().includes(q) ||
                           (u.jabatan && u.jabatan.toLowerCase().includes(q)) ||
                           (u.bidang && u.bidang.toLowerCase().includes(q));
                });
            },

            // ===== Add user include =====
            addPilihUser(user) {
                if (!this.pilihUsers.find(u => u.id === user.id)) {
                    this.pilihUsers.push(user);
                }
                // Kalau user ini ada di exclude, hapus dari exclude
                this.excludedUsers = this.excludedUsers.filter(u => u.id !== user.id);

                this.searchNama = '';
                this.showSuggestions = false;
            },

            removePilihUser(id) {
                this.pilihUsers = this.pilihUsers.filter(u => u.id !== id);
            },

            // ===== Add user exclude =====
            addExcludeUser(user) {
                if (!this.excludedUsers.find(u => u.id === user.id)) {
                    this.excludedUsers.push(user);
                }
                // Kalau user ini ada di include, hapus dari include
                this.pilihUsers = this.pilihUsers.filter(u => u.id !== user.id);

                this.searchExcludeNama = '';
                this.showExcludeSuggestions = false;
            },

            removeExcludeUser(id) {
                this.excludedUsers = this.excludedUsers.filter(u => u.id !== id);
            }
        }
    }
</script>
@endsection