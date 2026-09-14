@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Tambah Surat')

@section('content')
<div class="max-w-4xl mx-auto px-6 sm:px-8 py-8">
    
    <!-- Breadcrumb -->
    <a href="{{ route('admin.kelolasurat.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-indigo-600 transition mb-6">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Kelola Surat
    </a>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Surat</h1>
        <p class="text-base text-gray-600 mt-1">Lengkapi formulir di bawah untuk menambahkan surat baru</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
        <form action="{{ route('admin.kelolasurat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Jenis Surat -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Jenis Surat</label>
                    <select name="jenis_surat" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm bg-white">
                        <option value="">-- Pilih jenis surat --</option>
                        <option value="Surat Tugas" {{ old('jenis_surat') == 'Surat Tugas' ? 'selected' : '' }}>Surat Tugas</option>
                        <option value="Surat Undangan" {{ old('jenis_surat') == 'Surat Undangan' ? 'selected' : '' }}>Surat Undangan</option>
                        <option value="Surat Edaran" {{ old('jenis_surat') == 'Surat Edaran' ? 'selected' : '' }}>Surat Edaran</option>
                        <option value="Surat Keterangan" {{ old('jenis_surat') == 'Surat Keterangan' ? 'selected' : '' }}>Surat Keterangan</option>
                        <option value="Surat Permohonan" {{ old('jenis_surat') == 'Surat Permohonan' ? 'selected' : '' }}>Surat Permohonan</option>
                        <option value="Surat Pengantar" {{ old('jenis_surat') == 'Surat Pengantar' ? 'selected' : '' }}>Surat Pengantar</option>
                        <option value="Surat Keputusan" {{ old('jenis_surat') == 'Surat Keputusan' ? 'selected' : '' }}>Surat Keputusan</option>
                        <option value="Lainnya" {{ old('jenis_surat') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis_surat')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sifat Surat -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Sifat Surat</label>
                    <select name="sifat_surat" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm bg-white">
                        <option value="">-- Pilih sifat surat --</option>
                        <option value="Biasa" {{ old('sifat_surat') == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="Penting" {{ old('sifat_surat') == 'Penting' ? 'selected' : '' }}>Penting</option>
                        <option value="Segera" {{ old('sifat_surat') == 'Segera' ? 'selected' : '' }}>Segera</option>
                        <option value="Rahasia" {{ old('sifat_surat') == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                    </select>
                    @error('sifat_surat')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Surat (Auto) -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Nomor Surat</label>
                    <input type="text" name="no_surat" value="{{ old('no_surat', $nomorTersedia ?? '') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm font-semibold" 
                        required>
                    <p class="text-xs text-gray-400 mt-1">Nomor otomatis dari sistem, bisa diubah manual</p>
                    @error('no_surat')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Surat -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Tanggal Surat</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
                    @error('tanggal')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Asal Surat / Pengirim -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Asal Surat / Pengirim</label>
                    <input type="text" name="asal_surat" value="{{ old('asal_surat') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" 
                        placeholder="Masukkan instansi pengirim">
                    @error('asal_surat')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tujuan Surat / Penerima -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Tujuan Surat / Penerima</label>
                    <input type="text" name="alamat_tujuan" value="{{ old('alamat_tujuan') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" 
                        placeholder="Masukkan instansi penerima">
                    @error('alamat_tujuan')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No Indek -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">No/Indek</label>
                    <input type="text" name="no_indek" value="{{ old('no_indek') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" 
                        placeholder="Contoh: B/500.10.20.2/2397">
                    @error('no_indek')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Banyak Lampiran -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Banyak Lampiran</label>
                    <div class="relative">
                        <input type="number" name="banyak_lampiran" value="{{ old('banyak_lampiran', 0) }}" 
                            min="0"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm pr-20">
                        <span class="absolute right-4 top-2.5 text-xs text-gray-400 font-medium">Lembar</span>
                    </div>
                    @error('banyak_lampiran')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Isi Surat (WAJIB) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">
                        Isi Surat <span class="text-red-500">*</span>
                    </label>
                    <textarea name="isi_surat" rows="5" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" 
                        placeholder="Tuliskan isi/ringkasan surat..." required>{{ old('isi_surat') }}</textarea>
                    @error('isi_surat')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lampiran File -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Lampiran (Digital Scan)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-indigo-500 transition bg-gray-50 relative cursor-pointer">
                        <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" 
                            class="absolute inset-0 opacity-0 cursor-pointer"
                            onchange="updateFileName(this)"
                            id="fileInput">
                        <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-700" id="fileNameDisplay">
                            <span class="text-indigo-600">Klik untuk unggah</span> atau seret file ke sini
                        </p>
                        <p class="text-xs text-gray-400 mt-1">PDF, JPG atau PNG (Maks. 5MB)</p>
                    </div>
                    @error('file_surat')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="3" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm" 
                        placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.kelolasurat.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Surat
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function updateFileName(input) {
        const display = document.getElementById('fileNameDisplay');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const size = (file.size / 1024 / 1024).toFixed(2);
            display.innerHTML = `
                <span class="text-indigo-600 font-semibold flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    ${file.name}
                </span>
                <span class="block text-xs text-gray-400 mt-1">${size} MB</span>
            `;
        }
    }
</script>
@endsection