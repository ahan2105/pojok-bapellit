@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
@endphp

<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- KIRI: Logo -->
            <div class="flex items-center">
                <a href="{{ route('booking.index') }}" class="text-xl font-bold text-indigo-600">
                    SI
                </a>
            </div>
            
            <!-- TENGAH: Menu Utama -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="{{ route('booking.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium {{ request()->routeIs('booking.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">
                    Booking Aula
                </a>
                <a href="{{ route('riwayat.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium {{ request()->routeIs('riwayat.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">
                    Riwayat Booking Aula
                </a>
                <a href="{{ route('surat.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium {{ request()->routeIs('surat.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">
                    Ambil Surat
                </a>
            </div>
            
            <!-- KANAN: Dropdown Profil -->
            <div class="flex items-center">
                @auth
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    
                    @if($isAdmin)
                        <button @click="open = !open" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    @else
                        <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none py-2 px-3 rounded-md hover:bg-gray-50 transition-colors">
                            <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    @endif
                    
                    <!-- DROPDOWN -->
                    <div x-show="open" x-cloak 
                         x-transition:enter="transition ease-out duration-150" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100" 
                         class="absolute right-0 mt-2 w-64 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            
                            <!-- Profile Info -->
                            <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                <div class="font-medium">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                @if($isAdmin)
                                    <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold text-white bg-red-600 rounded-full">Admin</span>
                                @endif
                            </div>

                            <!-- MENU ADMIN -->
                            @if($isAdmin)
                                <div class="px-4 pt-2 pb-1 text-xs font-semibold text-indigo-600 tracking-wider">MENU ADMIN</div>
                                
                                <!-- Kelola Aula -->
                                <a href="{{ route('admin.aula.index') }}" 
                                   class="group flex items-center gap-3 px-4 py-2.5 text-sm border-l-2 transition-all
                                          {{ request()->routeIs('admin.aula.*') 
                                              ? 'bg-indigo-50 text-indigo-600 border-indigo-600 font-semibold' 
                                              : 'text-gray-700 border-transparent hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-600' }}">
                                    <svg class="w-4 h-4 transition-colors {{ request()->routeIs('admin.aula.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Kelola Aula
                                </a>
                                
                                <!-- Kelola Booking -->
                                <a href="{{ route('admin.kelolabooking.index') }}" 
                                   class="group flex items-center gap-3 px-4 py-2.5 text-sm border-l-2 transition-all
                                          {{ request()->routeIs('admin.kelolabooking.*') 
                                              ? 'bg-indigo-50 text-indigo-600 border-indigo-600 font-semibold' 
                                              : 'text-gray-700 border-transparent hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-600' }}">
                                    <svg class="w-4 h-4 transition-colors {{ request()->routeIs('admin.kelolabooking.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Kelola Booking
                                </a>
                                
                                <!-- Kelola Surat -->
                                <a href="{{ route('admin.kelolasurat.index') }}" 
                                   class="group flex items-center gap-3 px-4 py-2.5 text-sm border-l-2 transition-all
                                          {{ request()->routeIs('admin.kelolasurat.*') 
                                              ? 'bg-indigo-50 text-indigo-600 border-indigo-600 font-semibold' 
                                              : 'text-gray-700 border-transparent hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-600' }}">
                                    <svg class="w-4 h-4 transition-colors {{ request()->routeIs('admin.kelolasurat.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Kelola Surat
                                </a>
                                
                                <!-- Kelola Akun -->
                                <a href="{{ route('admin.kelolaakun.index') }}" 
                                   class="group flex items-center gap-3 px-4 py-2.5 text-sm border-l-2 transition-all
                                          {{ request()->routeIs('admin.kelolaakun.*') 
                                              ? 'bg-indigo-50 text-indigo-600 border-indigo-600 font-semibold' 
                                              : 'text-gray-700 border-transparent hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-600' }}">
                                    <svg class="w-4 h-4 transition-colors {{ request()->routeIs('admin.kelolaakun.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Kelola Akun
                                </a>

                                <!-- ⭐ Kelola Absensi (route yang benar) -->
                                <a href="{{ route('admin.absensi.index') }}" 
                                   class="group flex items-center gap-3 px-4 py-2.5 text-sm border-l-2 transition-all
                                          {{ request()->routeIs('admin.absensi.*') 
                                              ? 'bg-indigo-50 text-indigo-600 border-indigo-600 font-semibold' 
                                              : 'text-gray-700 border-transparent hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-600' }}">
                                    <svg class="w-4 h-4 transition-colors {{ request()->routeIs('admin.absensi.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    Kelola Absensi
                                </a>
                                
                                <div class="border-t my-1"></div>
                            @endif

                            <!-- MENU USER -->
                            <div class="px-4 pt-2 pb-1 text-xs font-semibold text-gray-400 tracking-wider">MENU USER</div>
                            
                            <a href="{{ route('booking.index') }}" 
                               class="group flex items-center gap-3 px-4 py-2.5 text-sm border-l-2 transition-all
                                      {{ request()->routeIs('booking.*') 
                                          ? 'bg-indigo-50 text-indigo-600 border-indigo-600 font-semibold' 
                                          : 'text-gray-700 border-transparent hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-600' }}">
                                <svg class="w-4 h-4 transition-colors {{ request()->routeIs('booking.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Booking Aula
                            </a>
                            
                            <a href="{{ route('riwayat.index') }}" 
                               class="group flex items-center gap-3 px-4 py-2.5 text-sm border-l-2 transition-all
                                      {{ request()->routeIs('riwayat.*') 
                                          ? 'bg-indigo-50 text-indigo-600 border-indigo-600 font-semibold' 
                                          : 'text-gray-700 border-transparent hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-600' }}">
                                <svg class="w-4 h-4 transition-colors {{ request()->routeIs('riwayat.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Riwayat Booking
                            </a>
                            
                            <a href="{{ route('surat.index') }}" 
                               class="group flex items-center gap-3 px-4 py-2.5 text-sm border-l-2 transition-all
                                      {{ request()->routeIs('surat.*') 
                                          ? 'bg-indigo-50 text-indigo-600 border-indigo-600 font-semibold' 
                                          : 'text-gray-700 border-transparent hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-600' }}">
                                <svg class="w-4 h-4 transition-colors {{ request()->routeIs('surat.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Ambil Surat
                            </a>

                            @if(!$isAdmin)
                                <a href="#" 
                                   class="group flex items-center gap-3 px-4 py-2.5 text-sm border-l-2 border-transparent text-gray-700 hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-600 transition-all">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </a>
                            @endif

                            <div class="border-t my-1"></div>

                            <!-- LOGOUT -->
                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" onclick="confirmLogout()" 
                                        class="group flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-all">
                                    <svg class="w-4 h-4 text-red-400 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
            
        </div>
    </div>
</nav>

<!-- AlpineJS & SweetAlert2 -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Yakin ingin log out?',
            text: "Sesi Anda akan diakhiri.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Log out!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        })
    }
</script>