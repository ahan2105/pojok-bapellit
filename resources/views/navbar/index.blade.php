@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
@endphp

<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- KIRI: Logo -->
            <div class="flex items-center">
                <span class="text-xl font-bold text-indigo-600">
                    Pojok Bapelit
                </span>
            </div>
            
            <!-- TENGAH: Menu Utama (User & Admin) -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="{{ route('booking.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium {{ request()->routeIs('booking.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">
                    Booking Aula
                </a>
                <a href="{{ route('riwayat.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium {{ request()->routeIs('riwayat.*') ? 'text-indigo-600 border-b-2 border-indigo-600' : '' }}">
                    Riwayat Booking Saya
                </a>
            </div>
            
            <!-- KANAN: Dropdown Profil -->
            <div class="flex items-center">
                @auth
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    
                    @if($isAdmin)
                        <!-- ADMIN: Tombol Garis Tiga -->
                        <button @click="open = !open" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    @else
                        <!-- USER BIASA: Tombol Nama -->
                        <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none py-2 px-3 rounded-md hover:bg-gray-50">
                            <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    @endif
                    
                    <!-- DROPDOWN -->
                    <div x-show="open" class="absolute right-0 mt-2 w-60 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
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
                                <div class="px-4 pt-2 pb-1 text-xs font-semibold text-indigo-600">MENU ADMIN</div>
                                <a href="{{ route('admin.aula.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    🏛️ Kelola Aula
                                </a>
                                <a href="{{ route('admin.kelolabooking.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    📊 Kelola Booking
                                </a>
                                {{-- 
                                <a href="{{ route('admin.surat.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    📄 Agenda Surat
                                </a>
                                <a href="{{ route('admin.akun.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    👤 Kelola Akun
                                </a>
                                <a href="{{ route('admin.absensi.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    📋 Kelola Absensi
                                </a>
                                --}}
                                <div class="border-t my-1"></div>
                            @endif

                            <!-- MENU USER -->
                            <div class="px-4 pt-2 pb-1 text-xs font-semibold text-gray-400">MENU USER</div>
                            <a href="{{ route('booking.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                📋 Booking Aula
                            </a>
                            <a href="{{ route('riwayat.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                📜 Riwayat Booking
                            </a>

                            @if(!$isAdmin)
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    👤 Profile
                                </a>
                            @endif

                            <div class="border-t my-1"></div>

                            <!-- LOGOUT -->
                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" onclick="confirmLogout()" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    🚪 Log Out
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