@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
@endphp

<footer class="border-t border-gray-200 bg-white mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ═══════════════════════════════════════════ --}}
        {{-- BARIS UTAMA: Brand + Menu                        --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">

            {{-- KOLOM 1: BRAND --}}
            <div class="lg:col-span-1">
                <h3 class="text-lg font-bold text-indigo-600">
                    Pojok Bapelit
                </h3>
                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                    Sistem informasi Bappeda untuk booking aula, pengambilan nomor surat, dan presensi pegawai.
                </p>

                {{-- Tombol Tanya Asisten AI — di dalam kolom brand --}}
                <button type="button"
                        onclick="openChatbot()"
                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700 transition hover:bg-indigo-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Tanya Asisten AI
                </button>
            </div>

            {{-- KOLOM 2: MENU ADMIN (khusus admin) --}}
            @auth
                @if($isAdmin)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">
                            Menu Admin
                        </h3>
                        <ul class="mt-3 space-y-2 text-sm">
                            <li>
                                <a href="{{ route('admin.aula.index') }}"
                                   class="inline-flex items-center gap-2 text-gray-600 transition hover:text-indigo-600 {{ request()->routeIs('admin.aula.*') ? 'text-indigo-600 font-semibold' : '' }}">
                                    Kelola Aula
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.kelolabooking.index') }}"
                                   class="inline-flex items-center gap-2 text-gray-600 transition hover:text-indigo-600 {{ request()->routeIs('admin.kelolabooking.*') ? 'text-indigo-600 font-semibold' : '' }}">
                                    Kelola Booking
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.kelolasurat.index') }}"
                                   class="inline-flex items-center gap-2 text-gray-600 transition hover:text-indigo-600 {{ request()->routeIs('admin.kelolasurat.*') ? 'text-indigo-600 font-semibold' : '' }}">
                                    Kelola Surat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.kelolaakun.index') }}"
                                   class="inline-flex items-center gap-2 text-gray-600 transition hover:text-indigo-600 {{ request()->routeIs('admin.kelolaakun.*') ? 'text-indigo-600 font-semibold' : '' }}">
                                    Kelola Akun
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.absensi.index') }}"
                                   class="inline-flex items-center gap-2 text-gray-600 transition hover:text-indigo-600 {{ request()->routeIs('admin.absensi.*') ? 'text-indigo-600 font-semibold' : '' }}">
                                    Kelola Absensi
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
            @endauth

            {{-- KOLOM 3: MENU USER (semua role yang login) --}}
            @auth
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">
                        Menu
                    </h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li>
                            <a href="{{ route('booking.index') }}"
                               class="inline-flex items-center gap-2 text-gray-600 transition hover:text-indigo-600 {{ request()->routeIs('booking.*') ? 'text-indigo-600 font-semibold' : '' }}">
                                Booking Aula
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('riwayat.index') }}"
                               class="inline-flex items-center gap-2 text-gray-600 transition hover:text-indigo-600 {{ request()->routeIs('riwayat.*') ? 'text-indigo-600 font-semibold' : '' }}">
                                Riwayat Booking
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('surat.index') }}"
                               class="inline-flex items-center gap-2 text-gray-600 transition hover:text-indigo-600 {{ request()->routeIs('surat.*') ? 'text-indigo-600 font-semibold' : '' }}">
                                Ambil Surat
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('presensi.index') }}"
                               class="inline-flex items-center gap-2 text-gray-600 transition hover:text-indigo-600 {{ request()->routeIs('presensi.*') ? 'text-indigo-600 font-semibold' : '' }}">
                                Presensi Saya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('absensi.scan-page') }}"
                               class="inline-flex items-center gap-2 text-gray-600 transition hover:text-purple-600 {{ request()->routeIs('absensi.scan*') ? 'text-purple-600 font-semibold' : '' }}">
                                Scan Absensi
                            </a>
                        </li>
                    </ul>
                </div>
            @endauth

            {{-- KOLOM 4: AKUN & BANTUAN --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">
                    Akun & Bantuan
                </h3>
                <ul class="mt-3 space-y-2 text-sm">

                    @auth
                        <li>
                            <a href="{{ route('akun.edit') }}"
                               class="inline-flex items-center gap-2 text-gray-600 transition hover:text-indigo-600 {{ request()->routeIs('akun.*') ? 'text-indigo-600 font-semibold' : '' }}">
                                Akun Profil
                            </a>
                        </li>
                        <li>
                            <button type="button" onclick="confirmLogout()"
                                    class="inline-flex items-center gap-2 text-red-600 transition hover:text-red-700">
                                Log Out
                            </button>
                        </li>
                    @endauth

                    <li>
                        <a href="#" class="inline-flex items-center gap-2 text-gray-600 transition hover:text-indigo-600">
                            Bantuan
                        </a>
                    </li>

                    <li>
                        <span class="inline-flex items-center gap-2 text-gray-500">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-green-500"></span>
                            </span>
                            Sistem Aktif
                        </span>
                    </li>

                </ul>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- BARIS BAWAH: Copyright                           --}}
        {{-- ═══════════════════════════════════════════ --}}
        <div class="mt-8 border-t border-gray-100 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs text-gray-400">
                &copy; {{ date('Y') }} Bappeda. All rights reserved.
            </p>
            <p class="text-xs text-gray-400">
                Pojok Bapelit v1.0.0
            </p>
        </div>

    </div>
</footer>