@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Kelola Absensi')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-12 py-8">

    <!-- Header + Tombol Aksi -->
    <div class="mb-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Kelola Absensi</h1>
            <p class="text-base text-gray-600 mt-2">Daftar semua sesi absensi — Ice Breaking, rapat, event, dll.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap self-start lg:self-center">
            {{-- Tombol Kelola Pegawai --}}
            <a href="{{ route('admin.pegawai.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 bg-white text-gray-700 text-base font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 hover:border-indigo-300 hover:text-indigo-600 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Kelola Pegawai
            </a>

            {{-- Tombol Buat Sesi Baru --}}
            <a href="{{ route('admin.absensi.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 text-white text-base font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Sesi Baru
            </a>
        </div>
    </div>

    <!-- Filter Tab (3 tab) -->
    <div class="flex items-center gap-2 mb-6 flex-wrap">
        <a href="{{ route('admin.absensi.index', array_merge(request()->query(), ['filter' => 'hari_ini'])) }}" 
           class="px-5 py-2.5 rounded-lg text-base font-semibold transition
                  {{ $filter === 'hari_ini' 
                      ? 'bg-blue-600 text-white shadow-sm' 
                      : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
            Hari Ini
        </a>
        
        <a href="{{ route('admin.absensi.index', array_merge(request()->query(), ['filter' => 'ice_breaking'])) }}" 
           class="px-5 py-2.5 rounded-lg text-base font-semibold transition
                  {{ $filter === 'ice_breaking' 
                      ? 'bg-indigo-600 text-white shadow-sm' 
                      : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
            Ice Breaking
        </a>
        
        <a href="{{ route('admin.absensi.index', array_merge(request()->query(), ['filter' => 'semua'])) }}" 
           class="px-5 py-2.5 rounded-lg text-base font-semibold transition
                  {{ $filter === 'semua' 
                      ? 'bg-blue-600 text-white shadow-sm' 
                      : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
            Semua
        </a>
    </div>

    <!-- ⭐ Toolbar Rekap: Filter Periode + Export Excel -->
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-5 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            
            <!-- Kiri: Info + Filter Periode -->
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-base font-bold text-emerald-800">Rekap & Export Excel</h3>
                </div>
                <p class="text-sm text-emerald-700">
                    Pilih periode lalu klik <strong>Export Rekap</strong> untuk download data dalam format Excel.
                </p>
            </div>

            <!-- Kanan: Filter Periode + Tombol Export -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-shrink-0">
                
                {{-- Filter Periode (Toggle Button) --}}
                <div class="flex items-center gap-1 bg-white rounded-lg p-1 border border-emerald-200">
                    <button type="button" 
                            onclick="setPeriode('minggu')"
                            id="btn-periode-minggu"
                            class="px-4 py-2 rounded-md text-sm font-semibold transition
                                   {{ request('periode', 'bulan') === 'minggu' 
                                       ? 'bg-emerald-500 text-white shadow-sm' 
                                       : 'text-gray-700 hover:bg-gray-100' }}">
                        Mingguan
                    </button>
                    <button type="button" 
                            onclick="setPeriode('bulan')"
                            id="btn-periode-bulan"
                            class="px-4 py-2 rounded-md text-sm font-semibold transition
                                   {{ request('periode', 'bulan') === 'bulan' 
                                       ? 'bg-emerald-500 text-white shadow-sm' 
                                       : 'text-gray-700 hover:bg-gray-100' }}">
                        Bulanan
                    </button>
                    <button type="button" 
                            onclick="setPeriode('tahun')"
                            id="btn-periode-tahun"
                            class="px-4 py-2 rounded-md text-sm font-semibold transition
                                   {{ request('periode') === 'tahun' 
                                       ? 'bg-emerald-500 text-white shadow-sm' 
                                       : 'text-gray-700 hover:bg-gray-100' }}">
                        Tahunan
                    </button>
                </div>

                {{-- Tombol Export Rekap --}}
                <a href="#" 
                   id="btn-export-rekap"
                   onclick="exportRekap(event)"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Rekap
                </a>
            </div>
        </div>

        <!-- Info Periode Aktif -->
        <div class="mt-4 pt-4 border-t border-emerald-200/50 flex items-center gap-2 text-sm text-emerald-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Periode aktif: <strong id="periode-label">
                @if(request('periode') === 'minggu')
                    Mingguan (7 hari terakhir)
                @elseif(request('periode') === 'tahun')
                    Tahunan (tahun {{ date('Y') }})
                @else
                    Bulanan ({{ now()->translatedFormat('F Y') }})
                @endif
            </strong></span>
        </div>
    </div>

    <!-- Flash Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-base text-green-700 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-base text-red-700 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Daftar Sesi -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sesiList as $s)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition flex flex-col">
                
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 flex-wrap">
                            {{ $s->nama_sesi }}
                            @if($s->is_default)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                    Default
                                </span>
                            @endif
                        </h3>
                        <p class="text-sm text-gray-500 mt-1.5">
                            📅 {{ $s->tanggal->translatedFormat('d F Y') }}
                        </p>
                        @if($s->lokasi)
                            <p class="text-sm text-gray-500 mt-1">📍 {{ $s->lokasi }}</p>
                        @endif
                    </div>

                    @if($s->is_locked)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 flex-shrink-0">
                            🔒 Terkunci
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-4 py-4 border-y border-gray-100 my-4">
                    <div class="text-center flex-1">
                        <div class="text-2xl font-bold text-green-600">{{ $s->jumlah_hadir }}</div>
                        <div class="text-sm text-gray-500 font-medium mt-1">Hadir</div>
                    </div>
                    <div class="w-px h-10 bg-gray-200"></div>
                    <div class="text-center flex-1">
                        <div class="text-2xl font-bold text-red-600">{{ $s->jumlah_tidak }}</div>
                        <div class="text-sm text-gray-500 font-medium mt-1">Tidak Hadir</div>
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-auto">
                    <a href="{{ route('admin.absensi.show', $s->id) }}" 
                       class="flex-1 text-center px-4 py-3 bg-indigo-600 text-white text-base font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Buka Absensi
                    </a>

                    @if(!$s->is_default)
                        <form action="{{ route('admin.absensi.destroy', $s->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menghapus sesi &quot;{{ $s->nama_sesi }}&quot;?')"
                                    class="px-4 py-3 bg-red-50 text-red-700 text-base font-semibold rounded-lg hover:bg-red-100 transition"
                                    title="Hapus sesi">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <h3 class="text-2xl font-semibold text-gray-700 mb-2">
                    @if($filter === 'ice_breaking')
                        Belum Ada Sesi Ice Breaking
                    @elseif($filter === 'hari_ini')
                        Belum Ada Sesi Absensi Hari Ini
                    @else
                        Belum Ada Sesi Absensi
                    @endif
                </h3>
                <p class="text-base text-gray-500 mb-6">
                    @if($filter === 'ice_breaking')
                        Sesi Ice Breaking akan otomatis dibuat setiap hari kerja (Senin-Jumat).
                    @else
                        Mulai dengan membuat sesi absensi baru.
                    @endif
                </p>
                <a href="{{ route('admin.absensi.create') }}" 
                   class="inline-flex items-center gap-2 px-7 py-3 bg-indigo-600 text-white text-base font-semibold rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Sesi Baru
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($sesiList->hasPages())
        <div class="mt-8">
            {{ $sesiList->links() }}
        </div>
    @endif
</div>

<!-- ⭐ SweetAlert2 & Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentPeriode = "{{ request('periode', 'bulan') }}";

    // Update URL saat periode berubah
    function setPeriode(periode) {
        const url = new URL(window.location.href);
        url.searchParams.set('periode', periode);
        window.location.href = url.toString();
    }

    // Handle tombol Export Rekap
    function exportRekap(event) {
        event.preventDefault();

        const labels = {
            minggu: 'Mingguan (7 hari terakhir)',
            bulan:  'Bulanan (bulan ini)',
            tahun:  'Tahunan (tahun ini)'
        };

        Swal.fire({
            title: 'Export Rekap Excel?',
            html: `
                <p class="text-base text-gray-700 mb-2">Data yang akan di-export:</p>
                <p class="text-lg font-bold text-emerald-600">${labels[currentPeriode]}</p>
                <p class="text-sm text-gray-500 mt-3">File Excel akan otomatis ter-download.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Export!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke route export rekap
                window.location.href = `{{ route('admin.absensi.export-rekap') }}?periode=${currentPeriode}`;
            }
        });
    }
</script>
@endsection