@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $layout = ($user?->isAdmin() ?? false) ? 'layouts.admin' : 'layouts.user';

    // Map status → pesan
    $statusMessages = [
        'profile-updated'       => 'Profil berhasil diperbarui.',
        'password-updated'      => 'Password berhasil diperbarui.',
        'profile-photo-updated' => 'Foto profil berhasil diperbarui.',
    ];
    $statusMessage = $statusMessages[session('status')] ?? null;

    // Class input yang sering dipakai (DRY)
    $inputClass = 'w-full h-11 rounded-xl border border-gray-200 bg-gray-50 px-4 text-sm text-gray-700 focus:border-indigo-500 focus:ring-indigo-500';
    $inputClassWithEye = $inputClass . ' pr-12';
    $inputClassReadonly = 'w-full h-11 rounded-xl border border-gray-200 bg-gray-100 px-4 text-sm text-gray-500 cursor-not-allowed';
@endphp

@extends($layout)

@section('content')

{{-- ================= HEADER ================= --}}
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-6">
        <div class="flex items-center gap-4">
            <a href="{{ url()->previous() }}"
               class="w-10 h-10 rounded-xl border border-gray-200 flex items-center justify-center
                      text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">Pengaturan Akun</p>
                <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola informasi pribadi dan keamanan akun Anda.</p>
            </div>
        </div>
    </div>
</div>

{{-- ================= CONTENT ================= --}}
<main class="bg-slate-50 min-h-[calc(100vh-150px)]">
    <div class="max-w-6xl mx-auto px-6 lg:px-8 py-8">

        {{-- SUCCESS BANNER (Untuk Profil & Foto) --}}
        @if(session('status') && session('status') !== 'password-updated')
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ $statusMessage }}</span>
            </div>
        @endif

        {{-- ERROR BANNER (Fallback jika JS mati) --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-red-700">
                        <p class="font-semibold mb-1">Periksa kembali data Anda.</p>
                        <ul class="list-disc ml-4 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ============ PROFILE CARD ============ --}}
            <aside class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="h-24 bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-500"></div>

                <div class="px-6 pb-6">
                    {{-- FOTO --}}
                    <form action="{{ route('akun.photo.update') }}" method="POST"
                          enctype="multipart/form-data" id="photo-form" class="-mt-14">
                        @csrf
                        <div class="relative w-28 h-28 mx-auto">
                            <div class="w-28 h-28 rounded-full bg-white p-1.5 shadow-lg">
                                <div class="w-full h-full rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                             alt="Foto Profil" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-14 h-14 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            <label for="profile_photo"
                                   class="absolute right-0 bottom-0 w-9 h-9 rounded-full bg-indigo-600 text-white
                                          border-4 border-white flex items-center justify-center shadow-md
                                          hover:bg-indigo-700 cursor-pointer transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h3l2-3h8l2 3h3v12H3V7z"/>
                                    <circle cx="12" cy="13" r="3" stroke-width="2"/>
                                </svg>
                            </label>

                            <input type="file" id="profile_photo" name="profile_photo"
                                   accept="image/jpeg,image/png,image/webp" class="hidden"
                                   onchange="document.getElementById('photo-form').submit()">
                        </div>
                    </form>

                    {{-- NAMA + ROLE --}}
                    <div class="text-center mt-4">
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <span class="inline-flex items-center mt-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wide">
                            {{ $user->role ?? 'USER' }}
                        </span>
                    </div>

                    {{-- TOMBOL UBAH FOTO --}}
                    <label for="profile_photo"
                           class="mt-5 w-full h-10 rounded-xl border border-gray-200 text-sm font-semibold
                                  text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition
                                  flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.232 5.232l3.536 3.536M4 20h4l10.5-10.5a2.121 2.121 0 00-3-3L5 17v3z"/>
                        </svg>
                        Ubah Foto
                    </label>

                    <div class="my-6 border-t border-gray-100"></div>

                    {{-- INFO SINGKAT --}}
                    <div class="space-y-4">
                        @php
                            $infos = [
                                ['label' => 'Email', 'value' => $user->email ?? '-',
                                 'icon' => 'M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                                ['label' => 'No. WhatsApp', 'value' => $user->whatsapp ?? $user->phone ?? $user->no_whatsapp ?? '-',
                                 'icon' => 'M3 5a2 2 0 012-2h2.28a2 2 0 011.94 1.515L10 7a2 2 0 01-.55 1.93l-1.27 1.27a16 16 0 006.62 6.62l1.27-1.27A2 2 0 0118 15l2.485.78A2 2 0 0122 17.72V20a2 2 0 01-2 2C10.06 22 2 13.94 2 4a2 2 0 012-2h1z'],
                                ['label' => 'NIP / NI PPPK', 'value' => $user->nip ?? '-',
                                 'icon' => 'M10 6H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-5M14 4h6v6M14 4l8 8'],
                                ['label' => 'Jabatan', 'value' => $user->jabatan ?? '-',
                                 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                                ['label' => 'Golongan', 'value' => $user->golongan ?? '-',
                                 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                                ['label' => 'Bidang', 'value' => $user->bidang ?? '-',
                                 'icon' => 'M4 6h16M4 12h16M4 18h16'],
                            ];
                        @endphp

                        @foreach($infos as $info)
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-400">{{ $info['label'] }}</p>
                                    <p class="text-sm font-medium text-gray-700 truncate">{{ $info['value'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>

            {{-- ============ RIGHT CONTENT ============ --}}
            <section class="lg:col-span-2 space-y-6">

                {{-- ===== INFORMASI PRIBADI ===== --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden"
                     x-data="formBidang('{{ old('bidang', $user->bidang ?? '') }}')">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7-7h14a7 7 0 00-7 7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-gray-900">Informasi Pribadi</h2>
                            <p class="text-xs text-gray-400">Perbarui data dasar akun Anda.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('akun.update') }}" class="p-6">
                        @csrf @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Nama (readonly) --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap
                                    <span class="text-xs text-gray-400 font-normal">(tidak dapat diubah)</span>
                                </label>
                                <input type="text" value="{{ $user->name }}" readonly
                                       class="{{ $inputClassReadonly }}">
                            </div>
{{-- Email (editable) --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Aktif</label>
    <input type="email" name="email"
           value="{{ old('email', $user->email) }}" required
           placeholder="example@domain.com"
           class="{{ $inputClass }}">
    @error('email') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
</div>
                            {{-- NIP --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">NIP / NI PPPK</label>
                                <input type="text" name="nip"
                                       value="{{ old('nip', $user->nip ?? '') }}"
                                       placeholder="Contoh: 19780712 200701 2 016"
                                       class="{{ $inputClass }}">
                                @error('nip') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- No WhatsApp --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">No. WhatsApp</label>
                                <input type="text" name="whatsapp"
                                       value="{{ old('whatsapp', $user->whatsapp ?? $user->phone ?? $user->no_whatsapp ?? '') }}"
                                       placeholder="0812xxxxxxx"
                                       class="{{ $inputClass }}">
                                @error('whatsapp') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Jabatan --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jabatan <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <input type="text" name="jabatan"
                                       value="{{ old('jabatan', $user->jabatan ?? '') }}"
                                       placeholder="Contoh: Kepala Bidang, Perencana Ahli Muda"
                                       class="{{ $inputClass }}">
                                @error('jabatan') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Golongan --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Golongan</label>
                                <input type="text" name="golongan"
                                       value="{{ old('golongan', $user->golongan ?? '') }}"
                                       placeholder="Contoh: IV/c, III/d, IX"
                                       class="{{ $inputClass }}">
                                @error('golongan') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Bidang (Dropdown + Manual) --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Asal Bidang / Bagian <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                                </label>

                                {{-- Dropdown Bidang --}}
                                <select x-model="selected"
                                        @change="handleChange()"
                                        class="{{ $inputClass }}">
                                    <option value="">-- Pilih Bidang --</option>
                                    <option value="KEPALA BADAN">Kepala Badan</option>
                                    <option value="SEKRETARIAT">Sekretariat</option>
                                    <option value="BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM">Bidang Perekonomian dan Sumber Daya Alam</option>
                                    <option value="BIDANG INFRASTRUKTUR DAN KEWILAYAHAN">Bidang Infrastruktur dan Kewilayahan</option>
                                    <option value="BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA">Bidang Pemerintahan dan Pembangunan Manusia</option>
                                    <option value="BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI">Bidang Perencanaan Pengendalian dan Evaluasi</option>
                                    <option value="BIDANG PENELITIAN DAN PENGEMBANGAN">Bidang Penelitian dan Pengembangan</option>
                                    <option value="__lainnya__">✏️ Lainnya (isi manual)...</option>
                                </select>

                                {{-- Input Manual --}}
                                <div x-show="isLainnya" x-cloak class="mt-3">
                                    <input type="text"
                                           x-model="customBidang"
                                           placeholder="Ketik nama bidang manual..."
                                           class="w-full h-11 rounded-xl border border-indigo-300 bg-indigo-50/30 px-4 text-sm text-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                                    <p class="text-xs text-gray-500 mt-1.5">Isi nama bidang dengan lengkap</p>
                                </div>

                                {{-- Hidden input yang dikirim ke controller --}}
                                <input type="hidden" name="bidang" :value="finalBidang">

                                @error('bidang') <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Role (readonly) --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Role
                                    <span class="text-xs text-gray-400 font-normal">(tidak dapat diubah)</span>
                                </label>
                                <input type="text" value="{{ $user->role ?? 'user' }}" readonly
                                       class="{{ $inputClassReadonly }} uppercase">
                            </div>

                            {{-- Status (readonly) --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Akun
                                    <span class="text-xs text-gray-400 font-normal">(tidak dapat diubah)</span>
                                </label>
                                <input type="text" value="{{ $user->status ?? 'aktif' }}" readonly
                                       class="{{ $inputClassReadonly }} capitalize">
                            </div>
                        </div>

                        <div class="mt-6 pt-5 border-t border-gray-100 flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600
                                           text-white text-sm font-semibold hover:bg-indigo-700
                                           focus:ring-4 focus:ring-indigo-100 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ===== PASSWORD ===== --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m0-8V7m0 0a4 4 0 018 0v4a4 4 0 01-4 4h-8a4 4 0 01-4-4V7a4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-gray-900">Keamanan Akun</h2>
                            <p class="text-xs text-gray-400">Gunakan password yang kuat dan mudah Anda ingat.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}" class="p-6" x-data="{ show: {} }">
                        @csrf @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @php
                                $pwdFields = [
                                    ['id' => 'current_password', 'name' => 'current_password', 'label' => 'Password Saat Ini', 'wrap' => 'md:col-span-2'],
                                    ['id' => 'password', 'name' => 'password', 'label' => 'Password Baru', 'wrap' => ''],
                                    ['id' => 'password_confirmation', 'name' => 'password_confirmation', 'label' => 'Konfirmasi Password', 'wrap' => ''],
                                ];
                            @endphp

                            @foreach($pwdFields as $f)
                                <div class="{{ $f['wrap'] }}">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ $f['label'] }}</label>
                                    <div class="relative">
                                        <input :type="show['{{ $f['id'] }}'] ? 'text' : 'password'"
                                               id="{{ $f['id'] }}" name="{{ $f['name'] }}" required
                                               class="{{ $inputClassWithEye }}">

                                        <button type="button" @click="show['{{ $f['id'] }}'] = !show['{{ $f['id'] }}']"
                                                class="absolute inset-y-0 right-0 px-4 text-gray-400 hover:text-indigo-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </button>
                                    </div>

                                    @if($f['id'] === 'current_password')
                                        @error('current_password')
                                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-5 border-t border-gray-100 flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900
                                           text-white text-sm font-semibold hover:bg-gray-800
                                           focus:ring-4 focus:ring-gray-200 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m0-8V7m0 0a4 4 0 018 0v4a4 4 0 01-4 4h-8a4 4 0 01-4-4V7a4 4 0 018 0z"/>
                                </svg>
                                Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>

                {{-- INFO --}}
                <div class="flex items-start gap-3 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4 text-sm text-blue-700">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M18 10A8 8 0 11.001 10 8 8 0 0118 10zm-8-3a1 1 0 10-2 0 1 1 0 002 0zm-1 2a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z"
                              clip-rule="evenodd"/>
                    </svg>
                    <p>Pastikan informasi akun yang Anda masukkan sudah benar. Data tertentu yang bersifat administratif
                       dapat memerlukan verifikasi dari tim IT Bappelitbangda.</p>
                </div>
            </section>
        </div>
    </div>
</main>

{{-- ================= FOOTER ================= --}}
<footer class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-6 flex flex-col md:flex-row items-center justify-between gap-3">
        <p class="text-xs text-gray-400">© {{ date('Y') }} Bappelitbangda · Booking Aula</p>
        <p class="text-xs text-gray-400">Sistem Booking Aula & Absensi</p>
    </div>
</footer>

{{-- ================= SWEETALERT NOTIFIKASI ================= --}}

{{-- 1. Error: Password Saat Ini Salah --}}
@if($errors->has('current_password'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Password Saat Ini Salah',
            text: @json($errors->first('current_password')),
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Coba Lagi'
        });
    });
</script>
@endif

{{-- 2. Error: Password Baru Bermasalah --}}
@if($errors->has('password'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Password Baru Bermasalah',
            text: @json($errors->first('password')),
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Coba Lagi'
        });
    });
</script>
@endif

{{-- 3. Error dari session error_password (fallback) --}}
@if(session('error_password'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal Memperbarui Password',
            text: @json(session('error_password')),
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Coba Lagi'
        });
    });
</script>
@endif

{{-- 4. Sukses: Password Diperbarui --}}
@if(session('status') === 'password-updated')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Password akun Anda telah berhasil diperbarui.',
            confirmButtonColor: '#4f46e5',
            timer: 3000,
            timerProgressBar: true
        });
    });
</script>
@endif

{{-- 5. Sukses: Profil Diperbarui --}}
@if(session('status') === 'profile-updated')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Profil Anda telah berhasil diperbarui.',
            confirmButtonColor: '#4f46e5',
            timer: 3000,
            timerProgressBar: true
        });
    });
</script>
@endif

{{-- 6. Sukses: Foto Profil Diperbarui --}}
@if(session('status') === 'profile-photo-updated')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Foto profil Anda telah berhasil diperbarui.',
            confirmButtonColor: '#4f46e5',
            timer: 3000,
            timerProgressBar: true
        });
    });
</script>
@endif

{{-- ================= ALPINE JS: FORM BIDANG ================= --}}
<script>
    function formBidang(initialBidang = '') {
        const listBidang = [
            'KEPALA BADAN',
            'SEKRETARIAT',
            'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM',
            'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN',
            'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA',
            'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI',
            'BIDANG PENELITIAN DAN PENGEMBANGAN',
        ];

        const isInList = listBidang.includes(initialBidang);

        return {
            selected: isInList ? initialBidang : (initialBidang ? '__lainnya__' : ''),
            customBidang: isInList ? '' : (initialBidang || ''),

            get isLainnya() {
                return this.selected === '__lainnya__';
            },

            get finalBidang() {
                return this.isLainnya ? this.customBidang : this.selected;
            },

            handleChange() {
                if (!this.isLainnya) {
                    this.customBidang = '';
                }
            },
        };
    }
</script>

@endsection