@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('content')
<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    
    <!-- ===== HEADER ===== -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-xl sm:text-3xl font-bold text-gray-800">Kelola Akun Pengguna</h1>
        <p class="text-sm text-gray-500 mt-1 sm:mt-2">Manajemen data akses, profil, dan hak pengguna sistem.</p>
    </div>

    <!-- ===== ACTION BAR ===== -->
    <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form id="filterForm" action="{{ route('admin.kelolaakun.index') }}" method="GET" class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pencarian</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau NIP..." 
                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                    </div>
                </div>
                <div class="w-full sm:w-48 lg:w-64">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Bidang</label>
                    <select name="bidang" onchange="this.form.submit()" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white cursor-pointer">
                        <option value="">Semua Bidang</option>
                        @if(isset($bidangList) && count($bidangList) > 0)
                            @foreach($bidangList as $b)
                                @php $cleanBidang = strtoupper(trim($b)); $displayBidang = ucwords(strtolower(str_replace('_', ' ', $cleanBidang))); @endphp
                                <option value="{{ $cleanBidang }}" {{ request('bidang') == $cleanBidang ? 'selected' : '' }}>{{ $displayBidang }}</option>
                            @endforeach
                        @else
                            <option value="KEPALA BADAN" {{ request('bidang') == 'KEPALA BADAN' ? 'selected' : '' }}>Kepala Badan</option>
                            <option value="SEKRETARIAT" {{ request('bidang') == 'SEKRETARIAT' ? 'selected' : '' }}>Sekretariat</option>
                            <option value="BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM" {{ request('bidang') == 'BIDANG PEREKONOMIAN DAN SUMBER DAYA ALAM' ? 'selected' : '' }}>Bidang Perekonomian dan SDA</option>
                            <option value="BIDANG INFRASTRUKTUR DAN KEWILAYAHAN" {{ request('bidang') == 'BIDANG INFRASTRUKTUR DAN KEWILAYAHAN' ? 'selected' : '' }}>Bidang Infrastruktur dan Kewilayahan</option>
                            <option value="BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA" {{ request('bidang') == 'BIDANG PEMERINTAHAN DAN PEMBANGUNAN MANUSIA' ? 'selected' : '' }}>Bidang Pemerintahan dan SDM</option>
                            <option value="BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI" {{ request('bidang') == 'BIDANG PERENCANAAN PENGENDALIAN DAN EVALUASI' ? 'selected' : '' }}>Bidang Perencanaan, Pengendalian dan Evaluasi</option>
                            <option value="BIDANG PENELITIAN DAN PENGEMBANGAN" {{ request('bidang') == 'BIDANG PENELITIAN DAN PENGEMBANGAN' ? 'selected' : '' }}>Bidang Penelitian dan Pengembangan</option>
                        @endif
                    </select>
                </div>
                <div class="w-full sm:w-48 lg:w-64">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Jabatan</label>
                    <select name="jabatan" onchange="this.form.submit()" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white cursor-pointer">
                        <option value="">Semua Jabatan</option>
                        @foreach($jabatanList as $j)
                            <option value="{{ $j }}" {{ request('jabatan') == $j ? 'selected' : '' }}>{{ Str::limit($j, 40) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 sm:flex-none px-5 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Cari
                </button>
                @if(request('bidang') || request('jabatan') || request('search'))
                    <a href="{{ route('admin.kelolaakun.index') }}" class="px-4 py-3 bg-gray-100 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-200 flex items-center justify-center gap-2" title="Reset">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Reset
                    </a>
                @endif
                <a href="{{ route('admin.kelolaakun.create') }}" class="flex-1 sm:flex-none sm:ml-auto px-5 py-3 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 shadow-sm flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Tambah Akun
                </a>
            </div>
        </form>
    </div>

    @php
        $groupedRaw = $users->getCollection()->groupBy(function ($u) {
            $b = trim(strtoupper($u->bidang ?? ''));
            $b = preg_replace('/\s+/', ' ', $b);
            return $b === '' ? 'TANPA BIDANG' : $b;
        });
        $semuaBidang = $groupedRaw->keys()->toArray();
        $kepalaBadan = null; $sekretariat = null; $tanpaBidang = null; $bidangLainnya = [];
        foreach ($semuaBidang as $bidang) {
            if ($bidang === 'KEPALA BADAN') $kepalaBadan = $bidang;
            elseif ($bidang === 'SEKRETARIAT') $sekretariat = $bidang;
            elseif ($bidang === 'TANPA BIDANG') $tanpaBidang = $bidang;
            else $bidangLainnya[] = $bidang;
        }
        $urutanBidang = [];
        if ($kepalaBadan) $urutanBidang[] = $kepalaBadan;
        if ($sekretariat) $urutanBidang[] = $sekretariat;
        foreach ($bidangLainnya as $b) $urutanBidang[] = $b;
        if ($tanpaBidang) $urutanBidang[] = $tanpaBidang;
        $grouped = collect($urutanBidang)->mapWithKeys(fn($bidang) => [$bidang => $groupedRaw->get($bidang, collect())])->filter(fn($items) => $items->isNotEmpty());
        $globalNo = $users->firstItem() ?? 1;
    @endphp

    <!-- ===== MOBILE CARDS (HP) ===== -->
    <div class="lg:hidden space-y-6">
        @forelse($grouped as $bidang => $groupUsers)
            <div>
                <div class="flex items-center gap-2 mb-3 px-1">
                    <span class="w-2 h-2 rounded-full bg-blue-600 shrink-0"></span>
                    <span class="text-xs font-bold text-blue-900 tracking-wide flex-1 leading-tight">
                        @if($bidang === 'TANPA BIDANG') BELUM MEMILIKI BIDANG @else {{ ucwords(strtolower(str_replace('_', ' ', $bidang))) }} @endif
                    </span>
                    <span class="text-[11px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100 whitespace-nowrap">{{ $groupUsers->count() }} Orang</span>
                </div>
                <div class="space-y-3">
                    @foreach($groupUsers as $u)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                        <div class="flex gap-3">
                            @if($u->profile_photo)
                                <button type="button" onclick="previewPhoto('{{ asset('storage/' . $u->profile_photo) }}', '{{ addslashes($u->name) }}')" class="shrink-0">
                                    <img src="{{ asset('storage/' . $u->profile_photo) }}" alt="Foto {{ $u->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow ring-1 ring-gray-200">
                                </button>
                            @else
                                <button type="button" onclick="previewInitial('{{ addslashes($u->name) }}')" class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold text-sm shadow shrink-0">{{ strtoupper(substr($u->name, 0, 1)) }}</button>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-bold text-gray-800 leading-tight truncate">{{ $u->name }}</div>
                                <div class="text-xs text-gray-500 truncate flex items-center gap-1 mt-0.5"><svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> {{ $u->email ?? ($u->username ? '@'.$u->username : '-') }}</div>
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    @if($u->role === 'admin')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700 border border-blue-200">Admin</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-600 border">{{ ucfirst($u->role ?? 'User') }}</span>
                                    @endif
                                    @if($u->status === 'aktif')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-50 text-red-700 border border-red-100"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif</span>
                                    @endif
                                    @if($u->golongan)<span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-100">{{ $u->golongan }}</span>@endif
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-gray-50 rounded-xl px-3 py-2 border border-gray-100">
                                <div class="text-[10px] font-semibold text-gray-400 uppercase">NIP / PPPK</div>
                                <div class="font-mono font-medium text-gray-700 truncate">{{ $u->nip ?: '-' }}</div>
                            </div>
                            <div class="bg-gray-50 rounded-xl px-3 py-2 border border-gray-100">
                                <div class="text-[10px] font-semibold text-gray-400 uppercase">Jabatan</div>
                                <div class="font-medium text-gray-700 leading-tight line-clamp-2">{{ $u->jabatan ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('admin.kelolaakun.edit', $u->id) }}" class="flex-1 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl text-center hover:bg-blue-700 flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Edit
                            </a>
                            <form id="delete-form-m-{{ $u->id }}" action="{{ route('admin.kelolaakun.destroy', $u->id) }}" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $u->id }}, '{{ addslashes($u->name) }}')" class="w-full py-2.5 bg-white border border-red-200 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-10 text-center">
                <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3"><svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
                <p class="text-sm text-gray-500 font-medium">Belum ada data pengguna.</p>
            </div>
        @endforelse
    </div>

    <!-- ===== DESKTOP TABLE ===== -->
    <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 bg-gray-50">
                        <th class="px-4 py-4 w-14 text-center">No</th>
                        <th class="px-4 py-4 w-16 text-center">Foto</th>
                        <th class="px-4 py-4">Nama Pengguna</th>
                        <th class="px-4 py-4">NIP / PPPK</th>
                        <th class="px-4 py-4">Jabatan</th>
                        <th class="px-4 py-4 text-center">Gol</th>
                        <th class="px-4 py-4 text-center">Peran</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($grouped as $bidang => $groupUsers)
                        <tr class="bg-gradient-to-r from-blue-50 to-indigo-50 border-y border-blue-100">
                            <td colspan="9" class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                                    <span class="text-sm font-bold text-blue-900 tracking-wide">@if($bidang === 'TANPA BIDANG') BELUM MEMILIKI BIDANG @else {{ ucwords(strtolower(str_replace('_', ' ', $bidang))) }} @endif</span>
                                    <span class="text-xs font-semibold text-blue-700 bg-white/70 px-2.5 py-0.5 rounded-full border border-blue-100">{{ $groupUsers->count() }} Orang</span>
                                </div>
                            </td>
                        </tr>
                        @foreach($groupUsers as $u)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-4 py-4 text-sm font-semibold text-gray-500 text-center">{{ $globalNo++ }}</td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center">
                                    @if($u->profile_photo)
                                        <button type="button" onclick="previewPhoto('{{ asset('storage/' . $u->profile_photo) }}', '{{ addslashes($u->name) }}')" class="rounded-full overflow-hidden ring-2 ring-transparent hover:ring-indigo-400 transition-all cursor-zoom-in"><img src="{{ asset('storage/' . $u->profile_photo) }}" alt="Foto {{ $u->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow ring-1 ring-gray-200"></button>
                                    @else
                                        <button type="button" onclick="previewInitial('{{ addslashes($u->name) }}')" class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold text-xs shadow ring-1 ring-gray-200">{{ strtoupper(substr($u->name, 0, 1)) }}</button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-bold text-gray-800">{{ $u->name }}</div>
                                <div class="text-xs text-gray-500 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> {{ Str::limit($u->email ?? ($u->username ? '@'.$u->username : '-'), 28) }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs text-gray-600 font-mono">{{ $u->nip ?: '-' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700 max-w-[180px] truncate" title="{{ $u->jabatan }}">{{ $u->jabatan ?: '-' }}</td>
                            <td class="px-4 py-4 text-center">@if($u->golongan)<span class="inline-flex px-2 py-1 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">{{ $u->golongan }}</span>@else<span class="text-gray-300">-</span>@endif</td>
                            <td class="px-4 py-4 text-center">@if($u->role === 'admin')<span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">Admin</span>@else<span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border">{{ ucfirst($u->role ?? 'User') }}</span>@endif</td>
                            <td class="px-4 py-4 text-center">@if($u->status === 'aktif')<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif</span>@else<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif</span>@endif</td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.kelolaakun.edit', $u->id) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-lg border border-blue-100 hover:border-blue-600 shadow-sm transition-all" title="Edit"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></a>
                                    <form id="delete-form-{{ $u->id }}" action="{{ route('admin.kelolaakun.destroy', $u->id) }}" method="POST" class="inline-flex">@csrf @method('DELETE')<button type="button" onclick="confirmDelete({{ $u->id }}, '{{ addslashes($u->name) }}')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-lg border border-red-100 hover:border-red-600 shadow-sm transition-all" title="Hapus"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @empty
                    <tr><td colspan="9" class="px-6 py-16 text-center text-gray-500">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-600">Menampilkan <span class="font-bold text-gray-800">{{ $users->firstItem() }}</span> - <span class="font-bold text-gray-800">{{ $users->lastItem() }}</span> dari <span class="font-bold text-gray-800">{{ $users->total() }}</span> akun</div>
            <div class="flex items-center gap-1">
                @if ($users->onFirstPage())<span class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">&lsaquo;</span>@else<a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100">&lsaquo;</a>@endif
                @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                    @if ($page == $users->currentPage())<span class="px-3 py-2 text-sm text-white bg-blue-600 border border-blue-600 rounded-lg font-bold">{{ $page }}</span>@else<a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100">{{ $page }}</a>@endif
                @endforeach
                @if ($users->hasMorePages())<a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100">&rsaquo;</a>@else<span class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">&rsaquo;</span>@endif
            </div>
        </div>
        @endif
    </div>

    @if($users->hasPages())
    <div class="lg:hidden mt-4 flex flex-col items-center gap-3">
        <div class="text-xs text-gray-500">{{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} akun</div>
        <div class="flex items-center gap-1">
            @if ($users->onFirstPage())<span class="px-4 py-2.5 text-sm text-gray-400 bg-white border border-gray-200 rounded-xl cursor-not-allowed">‹</span>@else<a href="{{ $users->previousPageUrl() }}" class="px-4 py-2.5 text-sm text-gray-700 bg-white border border-gray-200 rounded-xl">‹</a>@endif
            @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                @if ($page == $users->currentPage())<span class="px-4 py-2.5 text-sm text-white bg-blue-600 rounded-xl font-bold">{{ $page }}</span>@else<a href="{{ $url }}" class="px-4 py-2.5 text-sm text-gray-700 bg-white border border-gray-200 rounded-xl">{{ $page }}</a>@endif
            @endforeach
            @if ($users->hasMorePages())<a href="{{ $users->nextPageUrl() }}" class="px-4 py-2.5 text-sm text-gray-700 bg-white border border-gray-200 rounded-xl">›</a>@else<span class="px-4 py-2.5 text-sm text-gray-400 bg-white border border-gray-200 rounded-xl cursor-not-allowed">›</span>@endif
        </div>
    </div>
    @endif
</div>

<div id="photoModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4" onclick="if(event.target === this) closePhoto()">
    <button type="button" onclick="closePhoto()" class="absolute top-4 right-4 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center backdrop-blur-md border border-white/20 z-10" aria-label="Tutup"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <div class="max-w-3xl max-h-[85vh] flex flex-col items-center" onclick="event.stopPropagation()">
        <img id="previewImg" src="" alt="Preview" class="max-w-full max-h-[70vh] rounded-2xl shadow-2xl object-contain bg-white">
        <p id="previewName" class="mt-4 text-white text-lg font-semibold drop-shadow-lg text-center"></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function previewPhoto(url, name) {
        const modal = document.getElementById('photoModal'); const img = document.getElementById('previewImg'); const nameEl = document.getElementById('previewName');
        img.src = url; img.style.display = 'block'; nameEl.textContent = name;
        modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden';
    }
    function previewInitial(name) {
        const modal = document.getElementById('photoModal'); const img = document.getElementById('previewImg'); const nameEl = document.getElementById('previewName');
        img.style.display = 'none';
        nameEl.innerHTML = `<div class="flex flex-col items-center"><div class="w-32 h-32 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold text-5xl shadow-2xl">${name.charAt(0).toUpperCase()}</div><p class="mt-4 text-white text-lg font-semibold drop-shadow-lg">${name}</p><p class="mt-1 text-white/60 text-sm">Belum mengunggah foto profil</p></div>`;
        modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden';
    }
    function closePhoto() { const modal = document.getElementById('photoModal'); modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; }
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closePhoto(); });
    function confirmDelete(id, name) {
        Swal.fire({ title: 'Hapus Akun?', html: `Anda akan menghapus akun <strong>${name}</strong> secara permanen.`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Hapus Permanen', cancelButtonText: 'Batal', reverseButtons: true }).then((result) => {
            if (result.isConfirmed) { Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } }); const f = document.getElementById('delete-form-' + id) || document.getElementById('delete-form-m-' + id); if(f) f.submit(); }
        });
    }
</script>
@endsection