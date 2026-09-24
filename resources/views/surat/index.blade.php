@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
    
    // Logic Foto Profil User Login
    $myProfilePhoto = null;
    if ($user && $user->profile_photo) {
        $myProfilePhoto = asset('storage/' . $user->profile_photo);
    }
    $myInitial = strtoupper(substr($user->name ?? 'U', 0, 1));
@endphp

@extends($layout)

@section('title', 'Ambil Surat')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-12 py-8">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Ambil Surat</h1>
            <p class="text-base text-gray-600 mt-2">Lengkapi formulir di bawah ini untuk mendaftarkan surat baru</p>
        </div>

        <!-- Avatar User Login -->
        <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 self-start md:self-auto">
            @if($myProfilePhoto)
                <img src="{{ $myProfilePhoto }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
            @else
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-inner">
                    {{ $myInitial }}
                </div>
            @endif
            <div class="pr-2 hidden sm:block">
                <p class="text-sm font-bold text-gray-900 leading-tight">{{ $user->name }}</p>
                <p class="text-xs text-gray-500">{{ $user->bidang ?? 'User' }}</p>
            </div>
        </div>
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
                        <p class="text-sm font-semibold text-white/80 uppercase tracking-wider">Nomor Surat</p>
                        <p id="display-nomor-surat" class="text-3xl md:text-4xl font-bold text-white mt-2 break-all">{{ $nomorTersedia }}</p>
                        <p class="text-sm text-white/80 mt-3 flex items-start gap-1.5">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Nomor otomatis dari sistem, tidak dapat diubah.</span>
                        </p>
                    </div>
                </div>

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
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="id_surat" id="input-id-surat" value="">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jenis Surat -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">Jenis Surat</label>
                            <input type="text" name="jenis_surat" id="input-jenis-surat" value="{{ old('jenis_surat') }}" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" 
                                placeholder="Masukkan jenis surat">
                            @error('jenis_surat') <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- Sifat Surat -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">Sifat Surat</label>
                            <input type="text" name="sifat_surat" id="input-sifat-surat" value="{{ old('sifat_surat') }}" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" 
                                placeholder="Masukkan sifat surat">
                            @error('sifat_surat') <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nomor Surat (AUTO, READONLY) -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">Nomor Surat</label>
                            <input type="text" id="input-nomor-surat" value="{{ $nomorTersedia }}" readonly name="no_surat_hidden"
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-200 bg-gray-100 text-gray-700 font-semibold text-base cursor-not-allowed">
                            <p class="text-sm text-gray-400 mt-1.5">Nomor otomatis dari sistem</p>
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">Tanggal Surat</label>
                            <input type="date" name="tanggal" id="input-tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base">
                            @error('tanggal') <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- No/Indek -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">No/Indek</label>
                            <input type="text" name="no_indek" id="input-no-indek" value="{{ old('no_indek') }}" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" 
                                placeholder="Masukkan nomor/induk">
                            @error('no_indek') <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- Tujuan Surat / Penerima -->
                        <div>
                            <label class="block text-base font-semibold text-gray-800 mb-2">Tujuan Surat / Penerima</label>
                            <input type="text" name="alamat_tujuan" id="input-alamat-tujuan" value="{{ old('alamat_tujuan') }}" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base" 
                                placeholder="Masukkan instansi penerima">
                            @error('alamat_tujuan') <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- Isi Surat (WAJIB) -->
                        <div class="md:col-span-2">
                            <label class="block text-base font-semibold text-gray-800 mb-2">Isi Surat <span class="text-red-500">*</span></label>
                            <textarea name="isi_surat" id="input-isi-surat" rows="5" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base leading-relaxed" 
                                placeholder="Masukkan isi atau ringkasan surat" required>{{ old('isi_surat') }}</textarea>
                            @error('isi_surat') <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- Lampiran (WAJIB) -->
                        <div class="md:col-span-2">
                            <label class="block text-base font-semibold text-gray-800 mb-2">Lampiran (Digital Scan) <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:border-indigo-500 hover:bg-indigo-50/30 transition bg-gray-50 relative cursor-pointer" id="dropzone">
                                <input type="file" name="file_surat" accept=".pdf,.doc,.docx,.xls,.xlsx" 
                                    class="absolute inset-0 opacity-0 cursor-pointer" id="fileInput" onchange="updateFileName(this)">
                                <div id="uploadIcon">
                                    <svg class="w-14 h-14 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <p class="text-base font-semibold text-gray-700" id="fileNameDisplay">
                                    <span class="text-indigo-600">Klik untuk unggah</span> atau seret file ke sini
                                </p>
                                <p class="text-sm text-gray-400 mt-1.5">PDF, DOC, DOCX, XLS, XLSX (Maks. 5MB)</p>
                            </div>
                            @error('file_surat') <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <!-- Keterangan Tambahan -->
                        <div class="md:col-span-2">
                            <label class="block text-base font-semibold text-gray-800 mb-2">Keterangan Tambahan</label>
                            <textarea name="keterangan" id="input-keterangan" rows="3" 
                                class="w-full px-4 py-3.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-base leading-relaxed" 
                                placeholder="Tambahkan catatan tambahan jika diperlukan...">{{ old('keterangan') }}</textarea>
                            @error('keterangan') <p class="text-sm text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="mt-9 pt-7 border-t border-gray-200 flex items-center justify-end gap-3">
                        <button type="button" onclick="resetFormMode()" class="px-6 py-3 text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition">Batal Edit</button>
                        <button type="button" onclick="confirmSubmit()" class="inline-flex items-center gap-2 px-7 py-3 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span id="btn-submit-text">Simpan Data</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- RIWAYAT SURAT DENGAN FILTER                   -->
    <!-- ============================================ -->
    <div class="mt-14">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Riwayat Surat</h2>
                <p class="text-base text-gray-600 mt-1.5">Daftar surat yang pernah diambil</p>
            </div>
            
            <!-- Filter Tabs -->
            <div class="flex bg-gray-100 p-1 rounded-xl self-start">
                <a href="{{ route('surat.index', ['filter' => 'mine']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $filter === 'mine' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Surat Saya
                </a>
                <a href="{{ route('surat.index', ['filter' => 'all']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $filter === 'all' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Semua Surat
                </a>
            </div>
        </div>

        <!-- DESKTOP TABLE VIEW -->
        <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="px-6 py-5 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">No Surat</th>
                        @if($filter === 'all')
                        <th class="px-6 py-5 text-left text-sm font-semibold text-gray-500 uppercase tracking-wider">Pemilik</th>
                        @endif
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
                                <!-- NOMOR SURAT MERAH -->
                                <p class="font-bold text-red-600 text-base">{{ $surat->no_surat }}</p>
                                @if($surat->no_indek)
                                    <p class="text-sm text-gray-400 mt-0.5">Indek: {{ $surat->no_indek }}</p>
                                @endif
                            </td>
                            
                            @if($filter === 'all')
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2.5">
                                    @if($surat->user?->profile_photo)
                                        <img src="{{ asset('storage/' . $surat->user->profile_photo) }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                            {{ strtoupper(substr($surat->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $surat->user->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-500">{{ $surat->user->bidang ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            @endif

                            <td class="px-6 py-5">
                                @if($surat->jenis_surat)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-indigo-50 text-indigo-700">{{ $surat->jenis_surat }}</span>
                                @else
                                    <span class="text-base text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-base text-gray-700">{{ Str::limit($surat->isi_surat, 50) }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="font-medium text-gray-900 text-base">{{ $surat->tanggal ? $surat->tanggal->format('d M Y') : $surat->created_at->format('d M Y') }}</p>
                                <p class="text-sm text-gray-400 mt-0.5">{{ $surat->created_at->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-6 py-5">
                                @if($surat->file_surat)
                                    @php
                                        $colorMap = ['red' => 'text-red-700 bg-red-50', 'blue' => 'text-blue-700 bg-blue-50', 'emerald' => 'text-emerald-700 bg-emerald-50', 'gray' => 'text-gray-700 bg-gray-100'];
                                        $colorClass = $colorMap[$surat->file_color] ?? $colorMap['gray'];
                                    @endphp
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium {{ $colorClass }} rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        {{ $surat->file_label }}
                                    </span>
                                @else
                                    <span class="text-base text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($surat->user_id === Auth::id())
                                        <button type="button" onclick='editSurat(@json($surat))' class="inline-flex items-center gap-2 px-4 py-2.5 text-base font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit
                                        </button>
                                    @endif

                                    @if($surat->file_surat)
                                        <a href="{{ route('surat.download', $surat->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-base font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Download
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $filter === 'all' ? 7 : 6 }}" class="px-6 py-20 text-center">
                                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <p class="text-lg font-medium text-gray-700 mb-1">Belum Ada Data Surat</p>
                                <p class="text-base text-gray-500">{{ $filter === 'all' ? 'Belum ada surat yang terdaftar di sistem.' : 'Anda belum pernah mengambil surat.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if(isset($surats) && method_exists($surats, 'links'))
                <div class="px-6 py-5 border-t border-gray-100 flex items-center justify-between bg-gray-50">
                    <p class="text-base text-gray-600">Menampilkan <span class="font-semibold text-gray-800">{{ $surats->firstItem() ?? 0 }}</span>-<span class="font-semibold text-gray-800">{{ $surats->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-800">{{ $surats->total() }}</span> surat</p>
                    <div>{{ $surats->links() }}</div>
                </div>
            @endif
        </div>

        <!-- MOBILE CARD VIEW (Responsif) -->
        <div class="md:hidden space-y-4">
            @forelse($surats as $surat)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <!-- Header Card: Identitas User + No Surat -->
                    <div class="flex items-start justify-between mb-4 pb-3 border-b border-gray-50">
                        <div class="flex items-center gap-3 min-w-0">
                            @if($surat->user?->profile_photo)
                                <img src="{{ asset('storage/' . $surat->user->profile_photo) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 flex-shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600 flex-shrink-0">
                                    {{ strtoupper(substr($surat->user->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $surat->user->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $surat->user->bidang ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-3">
                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wide">No. Surat</p>
                            <p class="font-bold text-red-600 text-base leading-tight">{{ $surat->no_surat }}</p>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="space-y-2.5 mb-4 text-sm">
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Jenis</span>
                            <span class="font-medium text-gray-900 text-right max-w-[60%] truncate">{{ $surat->jenis_surat ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="text-gray-500">Tanggal</span>
                            <span class="font-medium text-gray-900">{{ $surat->tanggal ? $surat->tanggal->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="py-2 border-b border-gray-50">
                            <span class="text-gray-500 block text-xs mb-1">Isi Ringkas:</span>
                            <p class="text-gray-700 leading-snug">{{ Str::limit($surat->isi_surat, 80) }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons Mobile -->
                    <div class="flex gap-2 pt-1">
                        @if($surat->user_id === Auth::id())
                            <button type="button" onclick='editSurat(@json($surat))' class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </button>
                        @else
                            <div class="flex-1"></div> <!-- Spacer agar tombol download tetap rata kanan jika edit tidak ada -->
                        @endif

                        @if($surat->file_surat)
                            <a href="{{ route('surat.download', $surat->id) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Unduh
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-10 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <p class="text-lg font-medium text-gray-700 mb-1">Belum Ada Data Surat</p>
                    <p class="text-sm text-gray-500">{{ $filter === 'all' ? 'Belum ada surat yang terdaftar di sistem.' : 'Anda belum pernah mengambil surat.' }}</p>
                </div>
            @endforelse

            @if(isset($surats) && method_exists($surats, 'links'))
                <div class="pt-4">{{ $surats->links() }}</div>
            @endif
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentAction = "{{ route('surat.store') }}";
    const defaultNomor = "{{ $nomorTersedia }}";

    function updateFileName(input) {
        const display = document.getElementById('fileNameDisplay');
        const icon = document.getElementById('uploadIcon');
        const dropzone = document.getElementById('dropzone');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const size = (file.size / 1024 / 1024).toFixed(2);
            
            display.innerHTML = `<span class="text-indigo-600 font-semibold flex items-center justify-center gap-2 text-base"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>${file.name}</span><span class="block text-sm text-gray-400 mt-1">${size} MB</span>`;
            icon.innerHTML = `<svg class="w-14 h-14 text-indigo-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>`;
            dropzone.classList.add('border-indigo-500', 'bg-indigo-50');
            dropzone.classList.remove('border-gray-300', 'bg-gray-50');
        }
    }

    function confirmSubmit() {
        const form = document.getElementById('formSurat');
        const isiSurat = form.querySelector('textarea[name="isi_surat"]').value.trim();
        const fileInput = form.querySelector('input[name="file_surat"]');
        
        if (!isiSurat) { Swal.fire({ icon: 'warning', title: 'Isi Surat Belum Diisi', text: 'Silakan isi surat terlebih dahulu.', confirmButtonColor: '#4f46e5' }); return; }

        const isEditMode = document.getElementById('input-id-surat').value !== "";
        if (!isEditMode && (!fileInput.files || !fileInput.files[0])) { Swal.fire({ icon: 'warning', title: 'Lampiran Belum Diupload', text: 'Silakan upload file lampiran terlebih dahulu.', confirmButtonColor: '#4f46e5' }); return; }
        
        const title = isEditMode ? 'Update Data Surat?' : 'Simpan Data Surat?';
        const htmlContent = isEditMode ? `<p class="text-sm text-gray-500 mt-2">Perubahan akan disimpan permanen.</p>` : `<p class="text-base text-gray-600">Nomor surat yang akan digunakan:</p><p class="text-xl font-bold text-indigo-600 mt-2">${defaultNomor}</p><p class="text-sm text-gray-500 mt-3">Pastikan data sudah benar. Nomor akan langsung terpakai.</p>`;

        Swal.fire({ title: title, html: htmlContent, icon: 'question', showCancelButton: true, confirmButtonColor: '#4f46e5', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Lanjutkan!', cancelButtonText: 'Batal' }).then((result) => {
            if (result.isConfirmed) {
                form.action = currentAction;
                Swal.fire({ title: 'Memproses...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                form.submit();
            }
        });
    }

    function editSurat(surat) {
        document.getElementById('formSurat').scrollIntoView({ behavior: 'smooth' });
        document.getElementById('input-jenis-surat').value = surat.jenis_surat || '';
        document.getElementById('input-sifat-surat').value = surat.sifat_surat || '';
        document.getElementById('input-nomor-surat').value = surat.no_surat;
        document.getElementById('input-tanggal').value = surat.tanggal ? surat.tanggal.split('T')[0] : '';
        document.getElementById('input-no-indek').value = surat.no_indek || '';
        document.getElementById('input-alamat-tujuan').value = surat.alamat_tujuan || '';
        document.getElementById('input-isi-surat').value = surat.isi_surat || '';
        document.getElementById('input-keterangan').value = surat.keterangan || '';
        
        document.getElementById('input-id-surat').value = surat.id;
        document.getElementById('form-method').value = "PUT";
        currentAction = `/surat/${surat.id}`;
        document.getElementById('btn-submit-text').innerText = "Update Perubahan";
        
        const display = document.getElementById('fileNameDisplay');
        if (surat.file_surat) {
            display.innerHTML = `<span class="text-gray-600 font-semibold flex items-center justify-center gap-2 text-base"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>File saat ini: ${surat.file_label}</span><span class="block text-xs text-indigo-500 mt-1">(Upload file baru jika ingin mengganti)</span>`;
        }

        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Mode Edit Aktif', showConfirmButton: false, timer: 2000 });
    }

    function resetFormMode() {
        document.getElementById('formSurat').reset();
        document.getElementById('input-id-surat').value = "";
        document.getElementById('form-method').value = "POST";
        document.getElementById('input-nomor-surat').value = defaultNomor;
        document.getElementById('btn-submit-text').innerText = "Simpan Data";
        currentAction = "{{ route('surat.store') }}";
        const display = document.getElementById('fileNameDisplay');
        display.innerHTML = `<span class="text-indigo-600">Klik untuk unggah</span> atau seret file ke sini`;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    const dropzone = document.getElementById('dropzone');
    ['dragenter', 'dragover'].forEach(eventName => { dropzone.addEventListener(eventName, (e) => { e.preventDefault(); dropzone.classList.add('border-indigo-500', 'bg-indigo-50'); }); });
    ['dragleave', 'drop'].forEach(eventName => { dropzone.addEventListener(eventName, (e) => { e.preventDefault(); if (!document.getElementById('fileInput').files.length) { dropzone.classList.remove('border-indigo-500', 'bg-indigo-50'); dropzone.classList.add('border-gray-300', 'bg-gray-50'); } }); });
</script>
@endsection