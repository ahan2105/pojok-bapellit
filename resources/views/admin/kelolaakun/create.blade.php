@php
    $userAuth = Auth::user();
    $isAdmin = $userAuth && ($userAuth->role === 'admin' || $userAuth->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
    
    $isEdit = $user !== null;
    $title = $isEdit ? 'Edit Akun' : 'Tambah Akun Baru';
    $subtitle = $isEdit 
        ? 'Ubah data pengguna dan hak akses mereka di dalam sistem.' 
        : 'Tambahkan pengguna baru dan atur hak akses mereka di dalam sistem.';
    $action = $isEdit 
        ? route('admin.kelolaakun.update', $user->id) 
        : route('admin.kelolaakun.store');
@endphp

@extends($layout)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex items-center mb-10">
        <a href="{{ route('admin.kelolaakun.index') }}" class="p-3 bg-white rounded-full shadow-sm mr-5 hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800">{{ $title }}</h1>
            <p class="text-gray-500 text-base mt-2">{{ $subtitle }}</p>
        </div>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-xl shadow-sm p-8 md:p-10 border border-gray-100">
        
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-base">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ $action }}" method="POST">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- Baris 1: Nama & WhatsApp -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" 
                           value="{{ old('name', $user->name ?? '') }}" 
                           placeholder="Masukkan nama lengkap"
                           class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3.5 text-base">
                    @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">No. WhatsApp</label>
                    <input type="text" name="whatsapp" 
                           value="{{ old('whatsapp', $user->whatsapp ?? '') }}" 
                           placeholder="0812xxxxxxx"
                           class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3.5 text-base">
                    @error('whatsapp') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Baris 2: Username & NIP -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" 
                           value="{{ old('username', $user->username ?? '') }}" 
                           placeholder="Masukkan username"
                           class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3.5 text-base">
                    @error('username') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">NIP</label>
                    <input type="text" name="nip" 
                           value="{{ old('nip', $user->nip ?? '') }}" 
                           placeholder="Nomor Induk Pegawai (tidak harus )"
                           class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3.5 text-base">
                    @error('nip') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Baris 3: Email & Role -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">
                        Email Aktif <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" 
                           value="{{ old('email', $user->email ?? '') }}" 
                           placeholder="example@domain.com"
                           class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3.5 text-base">
                    @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">
                        Peran / Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" 
                            class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3.5 text-base">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user"  {{ old('role', $user->role ?? '') == 'user'  ? 'selected' : '' }}>User</option>
                    </select>
                    @error('role') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Baris 4: Bidang & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">
                        Asal Bidang / Bagian <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="bidang" 
                           value="{{ old('bidang', $user->bidang ?? '') }}" 
                           placeholder="masukan bidang pegawai"
                           class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3.5 text-base">
                    @error('bidang') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">
                        Status Akun <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3.5 text-base">
                        <option value="aktif"    {{ old('status', $user->status ?? 'aktif') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $user->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Baris 5: Password & Konfirmasi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
                <!-- Password -->
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">
                        Password
                        @if($isEdit)
                            <span class="text-sm text-gray-400 font-normal">(kosongkan jika tidak ingin ubah)</span>
                        @else
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password"
                               name="password" 
                               placeholder="Minimal 8 karakter"
                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3.5 pr-12 text-base">
                        <!-- Ikon Mata -->
                        <button type="button" 
                                onclick="togglePassword('password', 'eye-open-1', 'eye-closed-1')"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eye-open-1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eye-closed-1" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" 
                               id="password_confirmation"
                               name="password_confirmation" 
                               placeholder="Ulangi password"
                               class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-3.5 pr-12 text-base">
                        <!-- Ikon Mata -->
                        <button type="button" 
                                onclick="togglePassword('password_confirmation', 'eye-open-2', 'eye-closed-2')"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eye-open-2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eye-closed-2" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.kelolaakun.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium text-base hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold text-base hover:bg-blue-700 transition-colors">
                    {{ $isEdit ? 'Update Akun' : 'Simpan Akun' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword(inputId, eyeOpenId, eyeClosedId) {
        const input = document.getElementById(inputId);
        const eyeOpen = document.getElementById(eyeOpenId);
        const eyeClosed = document.getElementById(eyeClosedId);

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>
@endsection