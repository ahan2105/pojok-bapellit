@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp
@extends($layout)

@section('title', 'Ambil Surat')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-12 py-8">
    
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Ambil Surat</h1>
        <p class="text-base text-gray-600 mt-2">Lengkapi formulir di bawah ini untuk mendaftarkan surat baru</p>
    </div>

    <!-- ============================================ -->
    <!-- FORM AMBIL SURAT                              -->
    <!-- ============================================ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        
        <!-- KIRI: Info Nomor Surat -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-7 shadow-lg sticky top-6">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white/80 uppercase tracking-wider">Nomor Surat Otomatis</p>
                        <p class="text-3xl md:text-4xl font-bold text-white mt-2 break-all">{{ $nomorTersedia }}</p>
                        <p class="text-sm text-white/80 mt-3 flex items-start gap-1.5">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Nomor otomatis dari sistem, tidak dapat diubah.</span>
                        </p>
                    </div>
                </div>

                <!-- Info Peringatan -->
                <div class="mt-6 pt-5 border-t border-white/20">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="text-sm text-white/90 leading-relaxed">
                            Nomor surat akan <strong>langsung terpakai</strong> setelah Anda submit.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- KANAN: Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-7 md:p-9">
                <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data" id="formSurat">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Jenis Surat (MANUAL INPUT) -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">Jenis Surat</label>
                            <input type="text" name="jenis_surat" value="{{ old('jenis_surat') }}" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" 
                                placeholder="Masukkan jenis surat">
                            @error('jenis_surat')
                                <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sifat Surat (MANUAL INPUT) -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">Sifat Surat</label>
                            <input type="text" name="sifat_surat" value="{{ old('sifat_surat') }}" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" 
                                placeholder="Masukkan sifat surat">
                            @error('sifat_surat')
                                <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Surat (AUTO, READONLY) -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">Nomor Surat</label>
                            <input type="text" value="{{ $nomorTersedia }}" readonly
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-200 bg-gray-100 text-gray-700 font-semibold text-base cursor-not-allowed">
                            <p class="text-sm text-gray-400 mt-1.5">Nomor otomatis dari sistem</p>
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">Tanggal Surat</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base">
                            @error('tanggal')
                                <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No/Indek -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">No/Indek</label>
                            <input type="text" name="no_indek" value="{{ old('no_indek') }}" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" 
                                placeholder="Masukkan nomor/induk">
                            @error('no_indek')
                                <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tujuan Surat / Penerima -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">Tujuan Surat / Penerima</label>
                            <input type="text" name="alamat_tujuan" value="{{ old('alamat_tujuan') }}" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" 
                                placeholder="Masukkan instansi penerima">
                            @error('alamat_tujuan')
                                <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Isi Surat (WAJIB) -->
                        <div class="md:col-span-2">
                            <label class="block text-base font-semibold text-gray-800 mb-2">
                                Isi Surat <span class="text-red-500">*</span>
                            </label>
                            <textarea name="isi_surat" rows="5" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base leading-relaxed" 
                                placeholder="Masukkan isi atau ringkasan surat" required>{{ old('isi_surat') }}</textarea>
                            @error('isi_surat')
                                <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lampiran (WAJIB) -->
                        <div class="md:col-span-2">
                            <label class="block text-base font-semibold text-gray-800 mb-2">
                                Lampiran (Digital Scan) <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:border-indigo-500 hover:bg-indigo-50/30 transition bg-gray-50 relative cursor-pointer" id="dropzone">
                                <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" 
                                    class="absolute inset-0 opacity-0 cursor-pointer" 
                                    id="fileInput"
                                    onchange="updateFileName(this)"
                                    required>
                                <div id="uploadIcon">
                                    <svg class="w-14 h-14 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <p class="text-base font-semibold text-gray-700" id="fileNameDisplay">
                                    <span class="text-indigo-600">Klik untuk unggah</span> atau seret file ke sini
                                </p>
                                <p class="text-sm text-gray-400 mt-1.5">PDF, JPG atau PNG (Maks. 5MB)</p>
                            </div>
                            @error('file_surat')
                                <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan Tambahan -->
                        <div class="md:col-span-2">
                            <label class="block text-base font-semibold text-gray-800 mb-2">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="3" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base leading-relaxed" 
                                placeholder="Tambahkan catatan tambahan jika diperlukan...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="mt-9 pt-7 border-t border-gray-200 flex items-center justify-end gap-3">
                        <button type="reset" 
                            class="px-6 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition">
                            Batal
                        </button>
                        <button type="button" onclick="confirmSubmit()" 
                            class="inline-flex items-center gap-2 px-7 py-3 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- LIST SURAT YANG SUDAH DIAMBIL                 -->
    <!-- ============================================ -->
    <div class="mt-14">
        <div class="mb-6">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Riwayat Surat Saya</h2>
            <p class="text-base text-gray-600 mt-1.5">Daftar surat yang pernah Anda ambil</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-6 py-5 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">No Surat</th>
                            <th class="px-6 py-5 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-5 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Isi Surat</th>
                            <th class="px-6 py-5 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-5 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">File</th>
                            <th class="px-6 py-5 text-right text-sm font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($surats as $surat)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-gray-900 text-base">{{ $surat->no_surat }}</p>
                                    @if($surat->no_indek)
                                        <p class="text-sm text-gray-400 mt-0.5">Indek: {{ $surat->no_indek }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    @if($surat->jenis_surat)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-indigo-50 text-indigo-700">
                                            {{ $surat->jenis_surat }}
                                        </span>
                                    @else
                                        <span class="text-base text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-base text-gray-700">{{ Str::limit($surat->isi_surat, 50) }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-medium text-gray-900 text-base">
                                        {{ $surat->tanggal ? $surat->tanggal->format('d M Y') : $surat->created_at->format('d M Y') }}
                                    </p>
                                    <p class="text-sm text-gray-400 mt-0.5">{{ $surat->created_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-6 py-5">
                                    @if($surat->file_surat)
                                        @php
                                            $ext = strtolower(pathinfo($surat->file_surat, PATHINFO_EXTENSION));
                                            $fileColor = $ext === 'pdf' ? 'red' : 'blue';
                                            $fileLabel = $ext === 'pdf' ? 'PDF' : strtoupper($ext);
                                        @endphp
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-{{ $fileColor }}-700 bg-{{ $fileColor }}-50 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $fileLabel }}
                                        </span>
                                    @else
                                        <span class="text-base text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($surat->file_surat)
                                            <a href="{{ route('surat.download', $surat->id) }}" 
                                                class="inline-flex items-center gap-2 px-4 py-2.5 text-base font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition">
                                                Download
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-700 mb-1">Belum Ada Riwayat Surat</p>
                                    <p class="text-base text-gray-500">Ambil surat pertama Anda dengan mengisi form di atas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($surats) && method_exists($surats, 'links'))
                <div class="px-6 py-5 border-t border-gray-100 flex items-center justify-between bg-gray-50">
                    <p class="text-base text-gray-600">
                        Menampilkan <span class="font-semibold text-gray-800">{{ $surats->firstItem() ?? 0 }}</span>-<span class="font-semibold text-gray-800">{{ $surats->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-800">{{ $surats->total() }}</span> surat
                    </p>
                    <div>
                        {{ $surats->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateFileName(input) {
        const display = document.getElementById('fileNameDisplay');
        const icon = document.getElementById('uploadIcon');
        const dropzone = document.getElementById('dropzone');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const size = (file.size / 1024 / 1024).toFixed(2);
            
            display.innerHTML = `
                <span class="text-indigo-600 font-semibold flex items-center justify-center gap-2 text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    ${file.name}
                </span>
                <span class="block text-sm text-gray-400 mt-1">${size} MB</span>
            `;
            
            icon.innerHTML = `
                <svg class="w-14 h-14 text-indigo-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            `;
            
            dropzone.classList.add('border-indigo-500', 'bg-indigo-50');
            dropzone.classList.remove('border-gray-300', 'bg-gray-50');
        }
    }

    function confirmSubmit() {
        const form = document.getElementById('formSurat');
        const isiSurat = form.querySelector('textarea[name="isi_surat"]').value.trim();
        const fileInput = form.querySelector('input[name="file_surat"]');
        
        if (!isiSurat) {
            Swal.fire({
                icon: 'warning',
                title: 'Isi Surat Belum Diisi',
                text: 'Silakan isi surat terlebih dahulu.',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }
        
        if (!fileInput.files || !fileInput.files[0]) {
            Swal.fire({
                icon: 'warning',
                title: 'Lampiran Belum Diupload',
                text: 'Silakan upload file lampiran terlebih dahulu.',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }
        
        Swal.fire({
            title: 'Simpan Data Surat?',
            html: `
                <p class="text-base text-gray-600">Nomor surat yang akan digunakan:</p>
                <p class="text-xl font-bold text-indigo-600 mt-2">{{ $nomorTersedia }}</p>
                <p class="text-sm text-gray-500 mt-3">Pastikan data sudah benar. Nomor akan langsung terpakai.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                form.submit();
            }
        });
    }

    // Drag & drop effect
    const dropzone = document.getElementById('dropzone');
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropzone.classList.add('border-indigo-500', 'bg-indigo-50');
        });
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            if (!document.getElementById('fileInput').files.length) {
                dropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
                dropzone.classList.add('border-gray-300', 'bg-gray-50');
            }
        });
    });
</script>
@endsection