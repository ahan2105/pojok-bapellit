@php
    $user = Auth::user();
    $isAdmin = $user && ($user->role === 'admin' || $user->is_admin === true);
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- ===== HEADER ===== -->
    <div class="mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Kelola Akun Pengguna</h1>
        <p class="text-gray-500 text-base mt-2">Manajemen data akses dan profil pengguna sistem.</p>
    </div>

    <!-- ===== ACTION BAR (Search & Tambah) ===== -->
    <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4 mb-8">
        
        <!-- Form Pencarian -->
        <form action="{{ route('admin.kelolaakun.index') }}" method="GET" class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input 
                type="text" 
                name="search" 
                value="{{ $search ?? '' }}" 
                placeholder="Cari nama, email, atau NIP..." 
                class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm"
            >
        </form>

        <!-- Tombol Tambah -->
        <a href="{{ route('admin.kelolaakun.create') }}" 
           class="px-6 py-3 bg-blue-600 text-white text-base font-semibold rounded-lg hover:bg-blue-700 flex items-center justify-center gap-2 shadow-sm transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Akun Baru
        </a>
    </div>

    <!-- ===== TABEL PENGGUNA ===== -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-sm font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-200 bg-gray-50">
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">Bagian</th>
                        <th class="px-6 py-4">Peran</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $u)
                    <tr class="hover:bg-gray-50 transition-colors">
                        
                        <!-- Kolom Pengguna -->
                        <td class="px-6 py-5">
                            <div class="text-base font-semibold text-gray-800">{{ $u->name }}</div>
                            <div class="text-sm text-gray-500 mt-0.5">{{ $u->email }}</div>
                        </td>
                        
                        <!-- Kolom Bagian -->
                        <td class="px-6 py-5 text-base text-gray-700">
                            {{ $u->bidang ?? '-' }}
                        </td>
                        
                        <!-- Kolom Peran (Role) -->
                        <td class="px-6 py-5">
                            @if($u->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-50 text-blue-600">
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                                    {{ ucfirst($u->role ?? 'User') }}
                                </span>
                            @endif
                        </td>
                        
                        <!-- Kolom Status -->
                        <td class="px-6 py-5">
                            @if($u->status === 'aktif')
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium bg-green-50 text-green-700">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium bg-red-50 text-red-700">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        
                        <!-- Kolom Aksi -->
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center gap-3">
                                
                                <!-- Tombol Edit -->
                                <a href="{{ route('admin.kelolaakun.edit', $u->id) }}" 
                                   class="p-2.5 text-gray-500 hover:text-blue-600 bg-white border border-gray-200 rounded-lg hover:border-blue-300 transition-colors"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                                
                                <!-- Tombol Hapus -->
                                <form id="delete-form-{{ $u->id }}" 
                                      action="{{ route('admin.kelolaakun.destroy', $u->id) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="confirmDelete({{ $u->id }}, '{{ $u->name }}')"
                                            class="p-2.5 text-gray-500 hover:text-red-600 bg-white border border-gray-200 rounded-lg hover:border-red-300 transition-colors"
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-gray-500 text-base">
                                @if($search ?? false)
                                    Tidak ada pengguna dengan kata kunci "{{ $search }}".
                                @else
                                    Belum ada data pengguna.
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ===== FOOTER TABEL (Pagination) ===== -->
        <div class="px-6 py-5 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4 bg-gray-50">
            
            <!-- Info Jumlah Data -->
            <div class="text-base text-gray-600">
                Menampilkan <span class="font-semibold text-gray-800">{{ $users->firstItem() ?? 0 }}</span> - <span class="font-semibold text-gray-800">{{ $users->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-800">{{ $users->total() }}</span> akun
            </div>
            
            <!-- Custom Pagination -->
            @if($users->hasPages())
            <div class="flex items-center gap-2">
                
                {{-- Tombol Previous --}}
                @if ($users->onFirstPage())
                    <span class="px-4 py-2 text-base text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">&lt;</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="px-4 py-2 text-base text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100">&lt;</a>
                @endif

                {{-- Nomor Halaman --}}
                @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if ($page == $users->currentPage())
                        <span class="px-4 py-2 text-base text-white bg-blue-600 border border-blue-600 rounded-lg font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-4 py-2 text-base text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Tombol Next --}}
                @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="px-4 py-2 text-base text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100">&gt;</a>
                @else
                    <span class="px-4 py-2 text-base text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">&gt;</span>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<!-- ===== SWEETALERT UNTUK HAPUS ===== -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus akun ini?',
            html: `Akun <strong>${name}</strong> akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection