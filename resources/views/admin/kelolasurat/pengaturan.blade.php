@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Pengaturan Nomor Surat')

@section('content')
<div class="max-w-3xl mx-auto px-6 sm:px-8 py-8">
    
    <!-- Breadcrumb -->
    <a href="{{ route('admin.kelolasurat.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-indigo-600 transition mb-6">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan Nomor Surat</h1>
        <p class="text-sm text-gray-600 mt-1">Atur nomor surat yang akan tersedia untuk user.</p>
    </div>

    <!-- Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-sm text-blue-800">
        <p class="font-semibold mb-1">Cara Kerja:</p>
        <ol class="list-decimal list-inside space-y-1 text-blue-700">
            <li>Isi <strong>Nomor Terakhir</strong> dengan nomor surat terakhir yang sudah dipakai</li>
            <li>Nomor berikutnya akan otomatis tersedia untuk user (tidak dapat diubah user)</li>
            <li>Setelah user submit, nomor otomatis naik ke berikutnya</li>
        </ol>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
        <form action="{{ route('admin.kelolasurat.pengaturan.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <!-- Nomor Terakhir -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1">Nomor Terakhir yang Sudah Dipakai *</label>
                    <input type="number" name="nomor_terakhir" id="nomorTerakhirInput"
                        value="{{ old('nomor_terakhir', $pengaturan->getCounter()) }}" 
                        min="0"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-lg font-semibold" 
                        placeholder="Contoh: 2396" required>
                    <p class="text-xs text-gray-400 mt-2">
                        Contoh: kalau surat terakhir bernomor <strong>2396</strong>, 
                        maka user berikutnya akan otomatis mendapatkan nomor <strong>2397</strong>.
                    </p>
                    @error('nomor_terakhir')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview -->
                <div class="p-5 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl shadow-sm">
                    <p class="text-xs font-semibold text-white/80 uppercase tracking-wider mb-2">Nomor Surat Berikutnya</p>
                    <p class="text-3xl font-bold text-white" id="previewNomor">{{ $previewNomor }}</p>
                    <p class="text-xs text-white/70 mt-2">Nomor ini akan muncul di form user berikutnya.</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.kelolasurat.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    const nomorTerakhirInput = document.getElementById('nomorTerakhirInput');
    const previewEl = document.getElementById('previewNomor');

    function generatePreview() {
        const nomorTerakhir = parseInt(nomorTerakhirInput.value) || 0;
        const nextNomor = nomorTerakhir + 1;
        previewEl.textContent = String(nextNomor);
    }

    nomorTerakhirInput.addEventListener('input', generatePreview);
</script>
@endsection