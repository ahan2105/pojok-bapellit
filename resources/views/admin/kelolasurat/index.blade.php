@php
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    $isAdmin = $user?->isAdmin() ?? false;
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.user';
@endphp

@extends($layout)

@section('title', 'Kelola Surat')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 sm:px-8 lg:px-12 py-8">

    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Surat</h1>
            <p class="text-base text-gray-600 mt-1">Daftar surat yang sudah diambil pengguna</p>
        </div>
        <a href="{{ route('admin.kelolasurat.pengaturan') }}"
            class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-base font-medium shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Pengaturan Nomor
        </a>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Total Surat</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Bulan Ini</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $statistics['bulan_ini'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Hari Ini</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $statistics['hari_ini'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Nomor Berikutnya</p>
            <p class="text-lg sm:text-xl font-bold text-indigo-600 mt-2 break-all">{{ $statistics['nomor_berikutnya'] ?? '-' }}</p>
        </div>
    </div>

    <!-- Filter & Search -->
    <form method="GET" class="flex flex-col md:flex-row gap-3 mb-6">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari no surat, isi surat, atau nama user..."
                class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:outline-none text-base bg-white shadow-sm">
        </div>
        <div class="flex gap-3">
            <button type="submit" class="flex-1 md:flex-none px-6 py-3 bg-gray-800 text-white rounded-xl hover:bg-gray-900 transition text-base font-medium">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.kelolasurat.index') }}" class="flex-1 md:flex-none px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition text-base font-medium text-center">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Tabel Surat -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Isi Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">File</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($surats as $surat)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-6 py-4 align-top">
                                <p class="font-semibold text-gray-900 text-sm sm:text-base">{{ $surat->no_surat }}</p>
                                @if($surat->no_indek)
                                    <p class="text-xs text-gray-400 mt-0.5">Indek: {{ $surat->no_indek }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-semibold text-xs">
                                            {{ strtoupper(substr($surat->user->name ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 text-sm truncate">{{ $surat->user->name ?? 'User' }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ $surat->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                @if($surat->jenis_surat)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 whitespace-nowrap">
                                        {{ $surat->jenis_surat }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top max-w-xs">
                                <p class="text-sm text-gray-700 line-clamp-2">{{ Str::limit($surat->isi_surat, 60) }}</p>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <p class="font-medium text-gray-900 text-sm whitespace-nowrap">
                                    {{ $surat->tanggal ? $surat->tanggal->format('d M Y') : $surat->created_at->format('d M Y') }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $surat->created_at->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-6 py-4 align-top">
                                @if($surat->file_surat)
                                    @php
                                        $ext = strtolower(pathinfo($surat->file_surat, PATHINFO_EXTENSION));
                                        $isPdf = $ext === 'pdf';
                                        $colorClass = $isPdf
                                            ? 'text-red-700 bg-red-50 hover:bg-red-100'
                                            : 'text-blue-700 bg-blue-50 hover:bg-blue-100';
                                        $fileLabel = $isPdf ? 'PDF' : strtoupper($ext);
                                    @endphp
                                    <a href="{{ $surat->file_url }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold {{ $colorClass }} rounded-md transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $fileLabel }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button"
                                        class="btn-detail inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-md transition"
                                        title="Detail"
                                        data-surat="{{ json_encode([
                                            'no_surat' => $surat->no_surat,
                                            'user' => $surat->user->name ?? 'User',
                                            'email' => $surat->user->email ?? '-',
                                            'jenis_surat' => $surat->jenis_surat ?? '-',
                                            'sifat_surat' => $surat->sifat_surat ?? '-',
                                            'tanggal' => $surat->tanggal ? $surat->tanggal->format('d F Y') : $surat->created_at->format('d F Y'),
                                            'asal_surat' => $surat->asal_surat ?? '-',
                                            'alamat_tujuan' => $surat->alamat_tujuan ?? '-',
                                            'isi_surat' => $surat->isi_surat,
                                            'no_indek' => $surat->no_indek ?? '-',
                                            'banyak_lampiran' => $surat->banyak_lampiran ?? 0,
                                            'keterangan' => $surat->keterangan ?? '-',
                                            'file_url' => $surat->file_url ?? ''
                                        ]) }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </button>

                                    @if($surat->file_surat)
                                        <a href="{{ route('admin.kelolasurat.download-file', $surat->id) }}"
                                            title="Download"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-md transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            Download
                                        </a>
                                    @endif

                                    <form action="{{ route('admin.kelolasurat.destroy', $surat->id) }}" method="POST" onsubmit="return confirm('Hapus surat ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-md transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-700 mb-1">Belum Ada Surat Diambil</p>
                                <p class="text-base text-gray-500">Belum ada pengguna yang mengambil surat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($surats) && method_exists($surats, 'links'))
            <div class="px-6 py-5 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-sm text-gray-500">
                    Menampilkan {{ $surats->firstItem() ?? 0 }}-{{ $surats->lastItem() ?? 0 }} dari {{ $surats->total() }} surat
                </p>
                <div>{{ $surats->links() }}</div>
            </div>
        @endif
    </div>

    <!-- ============================================ -->
    <!-- MODAL DETAIL SURAT                           -->
    <!-- ============================================ -->
    <div id="detailModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeDetail()"></div>

        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full relative z-10 max-h-[90vh] overflow-y-auto">

                <!-- Header Modal -->
                <div class="sticky top-0 bg-white flex items-center justify-between px-6 sm:px-8 py-5 border-b border-gray-200 rounded-t-2xl z-10">
                    <div class="min-w-0">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Detail Surat</h3>
                        <p class="text-sm text-gray-500 mt-1 truncate" id="modalNoSurat"></p>
                    </div>
                    <button onclick="closeDetail()"
                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Body Modal -->
                <div class="px-6 sm:px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" id="modalUser"></p>
                            <p class="text-xs text-gray-500 mt-0.5" id="modalEmail"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" id="modalTanggal"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Surat</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" id="modalJenis"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sifat Surat</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" id="modalSifat"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Asal Surat / Pengirim</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" id="modalAsal"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tujuan Surat / Penerima</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" id="modalAlamat"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">No / Indek</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" id="modalNoIndek"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Banyak Lampiran</p>
                            <p class="text-base font-semibold text-gray-900 mt-1.5" id="modalLampiran"></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Isi Surat</p>
                            <p class="text-base text-gray-800 mt-1.5 whitespace-pre-wrap leading-relaxed" id="modalIsi"></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</p>
                            <p class="text-base text-gray-800 mt-1.5 whitespace-pre-wrap" id="modalKeterangan"></p>
                        </div>
                        <div class="md:col-span-2" id="modalFileWrapper">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">File Surat</p>
                            <a id="modalFileLink" href="#" target="_blank"
                                class="inline-flex items-center gap-2 mt-1.5 px-4 py-2 text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Lihat File
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="sticky bottom-0 flex items-center justify-end gap-3 px-6 sm:px-8 py-5 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                    <button onclick="closeDetail()"
                        class="px-6 py-2.5 text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-detail').forEach(function(btn) {
            btn.addEventListener('click', function() {
                try {
                    const data = JSON.parse(this.getAttribute('data-surat'));
                    openDetail(data);
                } catch (e) {
                    console.error('Error parsing data-surat:', e);
                }
            });
        });
    });

    function openDetail(data) {
        document.getElementById('modalNoSurat').textContent = 'No: ' + (data.no_surat || '-');
        document.getElementById('modalUser').textContent = data.user || '-';
        document.getElementById('modalEmail').textContent = data.email || '-';
        document.getElementById('modalJenis').textContent = data.jenis_surat || '-';
        document.getElementById('modalSifat').textContent = data.sifat_surat || '-';
        document.getElementById('modalAsal').textContent = data.asal_surat || '-';
        document.getElementById('modalAlamat').textContent = data.alamat_tujuan || '-';
        document.getElementById('modalIsi').textContent = data.isi_surat || '-';
        document.getElementById('modalKeterangan').textContent = data.keterangan || '-';
        document.getElementById('modalTanggal').textContent = data.tanggal || '-';
        document.getElementById('modalNoIndek').textContent = data.no_indek || '-';
        document.getElementById('modalLampiran').textContent = (data.banyak_lampiran || 0) + ' lembar';

        const fileWrapper = document.getElementById('modalFileWrapper');
        const fileLink = document.getElementById('modalFileLink');

        if (data.file_url && data.file_url !== '') {
            fileWrapper.classList.remove('hidden');
            fileLink.href = data.file_url;
        } else {
            fileWrapper.classList.add('hidden');
        }

        document.getElementById('detailModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDetail() {
        document.getElementById('detailModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDetail();
    });
</script>
@endsection