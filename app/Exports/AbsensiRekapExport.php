<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiRekapExport implements FromArray, WithStyles, WithColumnWidths, WithEvents
{
    protected Collection $sesiList;
    protected Carbon $tanggalDari;
    protected Carbon $tanggalKe;
    protected array $excludeIds; // Tambahan: ID sesi yang dikecualikan

    protected array $data = [];
    protected array $groupHeaderRows = [];
    protected array $dataRows = [];

    // Info grup tanggal untuk merge horizontal
    protected array $tanggalGroups = [];
    
    // Teks header kolom sesi (untuk hitung lebar kolom dinamis)
    protected array $sesiKolomText = [];

    protected int $footerStartRow = 0;
    protected int $totalKolom = 0;
    protected int $kolomMulaiSesi = 7; // Kolom G
    protected int $jumlahSesi = 0;

    // PERBAIKAN CONSTRUCTOR: Menerima excludeIds
    public function __construct(Collection $sesiList, Carbon $tanggalDari, Carbon $tanggalKe, array $excludeIds = [])
    {
        $this->sesiList    = $sesiList;
        $this->tanggalDari = $tanggalDari;
        $this->tanggalKe   = $tanggalKe;
        $this->excludeIds  = $excludeIds;
        $this->buildData();
    }

    private function buildData(): void
    {
        // Urutkan sesi berdasarkan tanggal
        $sesiList = $this->sesiList = $this->sesiList->sortBy(fn($s) => $s->tanggal)->values();
        
        // PERBAIKAN LOGIKA: Filter sesi yang ada di excludeIds
        if (!empty($this->excludeIds)) {
            $sesiList = $sesiList->filter(fn($s) => !in_array($s->id, $this->excludeIds))->values();
        }

        $this->jumlahSesi = $sesiList->count();

        // Total kolom = 6 (statis) + N (sesi) + 1 (keterangan) + 2 (rekap hadir/tidak)
        $kolomMulaiSesi  = 7;
        $kolomKeterangan = $kolomMulaiSesi + max(0, $this->jumlahSesi);
        $kolomHadir      = $kolomKeterangan + 1;
        $kolomTidak      = $kolomKeterangan + 2;
        
        $this->kolomMulaiSesi = $kolomMulaiSesi;
        $this->totalKolom     = $kolomTidak;

        $tahun = $this->tanggalKe->format('Y');

        // --- BARIS 1: JUDUL UTAMA ---
        $row1 = ["DAFTAR HADIR PEGAWAI ASN BAPPELITBANGDA {$tahun}"];
        $this->data[] = array_pad($row1, $this->totalKolom, '');

        // --- BARIS 2: PERIODE ---
        $periode = "REKAP PERIODE: "
            . strtoupper($this->tanggalDari->translatedFormat('d F Y'))
            . " — "
            . strtoupper($this->tanggalKe->translatedFormat('d F Y'));
        $row2 = [$periode];
        $this->data[] = array_pad($row2, $this->totalKolom, '');

        // Spacer (Baris 3, 4, 5 kosong)
        $this->data[] = array_fill(0, $this->totalKolom, '');
        $this->data[] = array_fill(0, $this->totalKolom, '');
        $this->data[] = array_fill(0, $this->totalKolom, '');

        // --- HEADER TABEL (Baris 6 & 7) ---
        $headerRow1 = ['NO', 'NO', 'NAMA', 'GOL', 'NIP/ NI PPPK/ NI PPPK PW', 'JABATAN'];
        $headerRow2 = ['', '', '', '', '', ''];

        $i = 0;
        $colOffset = 0;

        while ($i < $this->jumlahSesi) {
            $currentDateStr = $sesiList[$i]->tanggal->format('d/m');

            // Hitung berapa sesi dalam tanggal yang sama
            $j = $i;
            while ($j < $this->jumlahSesi && $sesiList[$j]->tanggal->format('d/m') === $currentDateStr) {
                $j++;
            }
            $span = $j - $i;

            $this->tanggalGroups[] = [
                'startCol' => $this->kolomMulaiSesi + $colOffset,
                'span'     => $span,
                'date'     => $currentDateStr,
            ];

            for ($k = 0; $k < $span; $k++) {
                // Baris 6 SELALU diisi Tanggal (hanya di kolom pertama grup)
                $headerRow1[] = ($k === 0) ? $currentDateStr : '';

                // Baris 7 SELALU diisi Nama Sesi (dari database)
                $namaSesiRaw = $sesiList[$i + $k]->nama_sesi ?? null;
                $namaSesi = ($namaSesiRaw !== null && $namaSesiRaw !== '')
                    ? $namaSesiRaw
                    : ('Sesi ' . ($k + 1));

                $headerRow2[] = $namaSesi;
                $this->sesiKolomText[] = $namaSesi;
            }

            $colOffset += $span;
            $i = $j;
        }

        // Tambahkan Header Keterangan & Rekap Kehadiran di Ujung Kanan
        $headerRow1[] = 'KETERANGAN';
        $headerRow1[] = 'JUMLAH HADIR';
        $headerRow1[] = 'JUMLAH TIDAK HADIR';

        $headerRow2[] = '';
        $headerRow2[] = '';
        $headerRow2[] = '';

        $this->data[] = array_pad($headerRow1, $this->totalKolom, ''); // Baris 6
        $this->data[] = array_pad($headerRow2, $this->totalKolom, ''); // Baris 7

        // Spacer sebelum data
        $this->data[] = array_fill(0, $this->totalKolom, '');

        // ===================================================================
        // LOGIKA PENGAMBILAN PESERTA (FIXED: SEMUA USER AKTIF MUNCUL)
        // ===================================================================
        $sesiIds = $sesiList->pluck('id');
        
        // 1. Ambil SEMUA USER AKTIF sebagai basis data utama
        $allUsers = User::where('status', 'aktif')
            ->orderBy('name')
            ->get(['id', 'name', 'golongan', 'nip', 'jabatan', 'bidang']);

        // 2. Map absensi per user per sesi
        $absensiMap = DB::table('absensi_detail')
            ->whereIn('absensi_sesi_id', $sesiIds)
            ->get()
            ->groupBy('user_id')
            ->map(fn($items) => $items->keyBy('absensi_sesi_id'));

        // 3. Map target peserta per sesi (Untuk membedakan '-' dan 'o')
        $targetSesiMap = DB::table('absensi_detail')
            ->whereIn('absensi_sesi_id', $sesiIds)
            ->select('absensi_sesi_id', 'user_id')
            ->get()
            ->groupBy('absensi_sesi_id')
            ->map(fn($items) => $items->pluck('user_id')->flip());

        // Grouping Bidang (DINAMIS & OTOMATIS)
        $groupedRaw = $allUsers->groupBy(function ($p) {
            $b = trim(strtoupper($p->bidang ?? ''));
            $b = preg_replace('/\s+/', ' ', $b);
            return $b === '' ? 'TANPA BIDANG' : $b;
        });

        // Pisahkan KEPALA BADAN agar selalu di paling atas
        $kepalaBadanGroup = $groupedRaw->pull('KEPALA BADAN', collect());
        
        // Sort sisa bidang secara alfabetis (A-Z) agar rapi & dinamis
        $sortedOtherGroups = $groupedRaw->sortKeys();

        // Gabungkan kembali: Kepala Badan di depan, sisanya urut A-Z
        $groupedPeserta = collect();
        
        if ($kepalaBadanGroup->isNotEmpty()) {
            $groupedPeserta->put('KEPALA BADAN', $kepalaBadanGroup);
        }
        
        foreach ($sortedOtherGroups as $bidang => $users) {
            $groupedPeserta->put($bidang, $users);
        }

        $noGlobal = 1;
        foreach ($groupedPeserta as $bidang => $groupPeserta) {
            $noBidang = 1;
            
            // Header Bidang (kecuali Kepala Badan tidak pakai baris header terpisah)
            if ($bidang !== 'KEPALA BADAN') {
                $row = [strtoupper($bidang)];
                $this->data[] = array_pad($row, $this->totalKolom, '');
                $this->groupHeaderRows[] = count($this->data);
            }

            foreach ($groupPeserta as $p) {
                $row = [$noGlobal, $noBidang, $p->name, $p->golongan ?? '', $p->nip ?? '', $p->jabatan ?? ''];
                $keteranganParts = [];
                
                // Variabel penghitung kehadiran
                $countHadir = 0;
                $countTidak = 0;
                $hasSesiInPeriod = false;

                foreach ($sesiList as $sesi) {
                    // Cek apakah user ini termasuk target peserta di sesi ini
                    $isTarget = isset($targetSesiMap[$sesi->id][$p->id]);
                    
                    if (!$isTarget) {
                        // User TIDAK ADA di sesi ini → beri tanda 'o'
                        $row[] = 'o';
                        continue;
                    }

                    $hasSesiInPeriod = true;
                    
                    // Cek apakah user ini punya record absen di sesi ini
                    $detail = $absensiMap[$p->id][$sesi->id] ?? null;
                    
                    if (!$detail) {
                        // User terdaftar di sesi ini TAPI belum isi absen → beri tanda '-'
                        $row[] = '-';
                    } elseif ($detail->status_kehadiran === 'hadir') {
                        $row[] = '✓';
                        $countHadir++;
                    } elseif ($detail->status_kehadiran === 'tidak') {
                        $row[] = '✗';
                        $countTidak++;
                        
                        // Simpan keterangan jika ada
                        if (!empty($detail->keterangan)) {
                            $tgl = Carbon::parse($sesi->tanggal)->format('d/m');
                            $keteranganParts[] = "{$tgl}: {$detail->keterangan}";
                        }
                    } else {
                        // Status lain dianggap belum absen
                        $row[] = '-';
                    }
                }

                // SKIP: Jika user tidak punya sesi sama sekali di periode ini, lewati
                if (!$hasSesiInPeriod) {
                    continue;
                }

                // Tambahkan kolom keterangan terlebih dahulu
                $row[] = !empty($keteranganParts) ? implode(' | ', $keteranganParts) : '';
                
                // Tambahkan kolom rekap jumlah di PALING KANAN
                $row[] = $countHadir;
                $row[] = $countTidak;
                
                $this->data[] = array_pad($row, $this->totalKolom, '');
                $this->dataRows[] = count($this->data);
                
                $noGlobal++;
                $noBidang++;
            }
        }

        // --- FOOTER ---
        // Cari Kepala Badan untuk tanda tangan
        $kepalaUser = User::where('status', 'aktif')
            ->where(function ($q) { 
                $q->where('bidang', 'KEPALA BADAN')
                  ->orWhere('bidang', 'like', 'KEPALA BADAN%')
                  ->orWhere('jabatan', 'like', '%Kepala Badan%'); 
            })
            ->orderBy('id')->first();

        $namaKepala = $kepalaUser->name ?? '........................................';
        $nipKepala  = ($kepalaUser && $kepalaUser->nip) ? 'NIP. ' . $kepalaUser->nip : 'NIP. ................................';

        // Spacer Footer
        $this->data[] = array_fill(0, $this->totalKolom, '');
        $this->data[] = array_fill(0, $this->totalKolom, '');
        $this->data[] = array_fill(0, $this->totalKolom, '');

        $currentDate = Carbon::now()->translatedFormat('d F Y');
        
        // Posisi footer di tengah tabel (Kolom E-H seperti AbsensiSesiExport)
        // Index 4 = Kolom E
        $footerColIdx = 4; 

        $footerRows = [
            'Singaparna, ' . $currentDate,
            'Mengetahui',
            'Kepala Badan Perencanaan Pembangunan,',
            'Penelitian dan Pengembangan Daerah Kabupaten Tasikmalaya',
            '', // Spacer
            '', // Spacer
            '', // Spacer
            $namaKepala,
            $nipKepala,
        ];

        $this->footerStartRow = count($this->data) + 1;
        foreach ($footerRows as $text) {
            $row = array_fill(0, $this->totalKolom, '');
            $row[$footerColIdx] = $text;
            $this->data[] = $row;
        }
    }

    public function array(): array { return $this->data; }

    public function columnWidths(): array
    {
        // Lebar Fixed & Lega sesuai referensi AbsensiSesiExport
        $widths = [
            'A' => 5,   // NO Global
            'B' => 5,   // NO Bidang
            'C' => 35,  // NAMA (Lega agar tidak kepotong)
            'D' => 8,   // GOL
            'E' => 28,  // NIP
            'F' => 32,  // JABATAN (Lega agar tidak kepotong)
        ];

        // Lebar kolom sesi dinamis berdasarkan teks header (Nama Sesi)
        for ($i = 0; $i < $this->jumlahSesi; $i++) {
            $col  = Coordinate::stringFromColumnIndex($this->kolomMulaiSesi + $i);
            $text = $this->sesiKolomText[$i] ?? '';
            $len  = mb_strlen((string) $text);
            // Minimal 10, maksimal 22, ditambah padding 5
            $widths[$col] = max(10, min(22, $len + 5));
        }

        // Lebar kolom KETERANGAN disesuaikan agar muat teks panjang
        $widths[Coordinate::stringFromColumnIndex($this->totalKolom - 2)] = 30; 
        
        // Lebar kolom Rekap Kehadiran disesuaikan agar muat teks "JUMLAH HADIR"
        $widths[Coordinate::stringFromColumnIndex($this->totalKolom - 1)] = 16; // JUMLAH HADIR
        $widths[Coordinate::stringFromColumnIndex($this->totalKolom)] = 18;     // JUMLAH TIDAK HADIR

        return $widths;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            // Style untuk Header Tabel (Baris 6 & 7)
            6 => [
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],
            7 => [
                'font' => ['bold' => true, 'size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $lastColLetter = Coordinate::stringFromColumnIndex($this->totalKolom);

                // ===================================================================
                // SOLUSI GARIS TENGAH / SPLIT VIEW
                // ===================================================================
                $sheet->setShowGridlines(false);

                // 1. HAPUS FREEZE PANE (Ini penyebab garis tengah/split view)
                // Kita ganti dengan "Rows to Repeat at Top" yang lebih halus.
                $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 7);
                
                // Set Print Area natural (tanpa fit to width/orientation)
                $sheet->getPageSetup()->setPrintArea("A1:{$lastColLetter}{$highestRow}");

                // 2. MERGE JUDUL & INFO
                $sheet->mergeCells("A1:{$lastColLetter}1");
                $sheet->mergeCells("A2:{$lastColLetter}2");

                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(2)->setRowHeight(22);

                // Tinggi baris header tabel
                $sheet->getRowDimension(6)->setRowHeight(30);
                $sheet->getRowDimension(7)->setRowHeight(36);

                // Warna Background Header Tabel (Biru Muda)
                $headerRange = "A6:{$lastColLetter}7";
                $sheet->getStyle($headerRange)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9E1F2'],
                    ],
                ]);

                // ===================================================================
                // 3. MERGE KOLOM TANGGAL & SESI
                // ===================================================================
                foreach ($this->tanggalGroups as $group) {
                    $startLetter = Coordinate::stringFromColumnIndex($group['startCol']);

                    if ($group['span'] > 1) {
                        // MULTI SESI: Merge tanggal di baris 6 secara horizontal
                        $endLetter = Coordinate::stringFromColumnIndex($group['startCol'] + $group['span'] - 1);
                        $sheet->mergeCells("{$startLetter}6:{$endLetter}6");
                        $sheet->getStyle("{$startLetter}6:{$endLetter}6")->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    } 
                    // SINGLE SESI: Tidak di-merge vertikal, biarkan 2 baris terpisah (Tanggal atas, Nama Sesi bawah)
                }

                // Merge kolom label statis (NO, NAMA, dll) vertikal baris 6:7
                foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $col) {
                    $sheet->mergeCells("{$col}6:{$col}7");
                }

                // Merge KETERANGAN vertikal baris 6:7
                $colKet = Coordinate::stringFromColumnIndex($this->totalKolom - 2);
                $sheet->mergeCells("{$colKet}6:{$colKet}7");

                // Merge Header Rekap Kehadiran (JUMLAH HADIR & TIDAK HADIR) vertikal baris 6:7
                $colHadir = Coordinate::stringFromColumnIndex($this->totalKolom - 1);
                $colTidak = Coordinate::stringFromColumnIndex($this->totalKolom);
                
                $sheet->mergeCells("{$colHadir}6:{$colHadir}7");
                $sheet->mergeCells("{$colTidak}6:{$colTidak}7");

                // 4. STYLE GROUP HEADERS (BIDANG)
                foreach ($this->groupHeaderRows as $rowIndex) {
                    $sheet->mergeCells("A{$rowIndex}:{$lastColLetter}{$rowIndex}");
                    $sheet->getStyle("A{$rowIndex}")->applyFromArray([
                        'font' => [
                            'name' => 'Arial',       // Font Arial
                            'bold' => true, 
                            'size' => 12
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER, 
                            'vertical' => Alignment::VERTICAL_CENTER
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID, 
                            'startColor' => ['rgb' => 'F2F2F2']
                        ],
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                        ],
                    ]);
                    $sheet->getRowDimension($rowIndex)->setRowHeight(20);
                }

                // 5. STYLE DATA ROWS
                foreach ($this->dataRows as $rowIndex) {
                    // Border semua sel
                    $sheet->getStyle("A{$rowIndex}:{$lastColLetter}{$rowIndex}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);

                    // Alignment spesifik per kolom
                    $sheet->getStyle("A{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Styling tanda kehadiran (✓ / ✗ / - / o) - TANPA WARNA
                    for ($i = 0; $i < $this->jumlahSesi; $i++) {
                        $colLetter = Coordinate::stringFromColumnIndex($this->kolomMulaiSesi + $i);
                        $cell = "{$colLetter}{$rowIndex}";
                        $value = $sheet->getCell($cell)->getValue();

                        $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        
                        // Semua tanda menggunakan font default (Hitam), Bold untuk simbol utama
                        if ($value === '✓' || $value === '✗') {
                            $sheet->getStyle($cell)->getFont()
                                ->setBold(true)
                                ->setSize(12);
                        } elseif ($value === '-') {
                            $sheet->getStyle($cell)->getFont()
                                ->setBold(true)
                                ->setSize(11);
                        } elseif ($value === 'o') {
                            $sheet->getStyle($cell)->getFont()
                                ->setBold(false)
                                ->setSize(10);
                        }
                    }

                    // Styling Khusus Kolom Rekap Jumlah (Background Abu-abu Muda + Border Jelas)
                    $sheet->getStyle("{$colHadir}{$rowIndex}:{$colTidak}{$rowIndex}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E2E8F0'],
                        ],
                        'font' => ['bold' => true, 'color' => ['rgb' => '1E293B']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                        ],
                    ]);

                    // Keterangan: Wrap Text & Tinggi Dinamis
                    $sheet->getStyle("{$colKet}{$rowIndex}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                        ->setWrapText(true);
                    $sheet->getStyle("{$colKet}{$rowIndex}")->getFont()->setSize(9);

                    $ketVal = (string) $sheet->getCell("{$colKet}{$rowIndex}")->getValue();
                    if ($ketVal !== '') {
                        // Estimasi tinggi baris berdasarkan panjang teks
                        $estLines = max(1, (int) ceil(mb_strlen($ketVal) / 40));
                        $sheet->getRowDimension($rowIndex)->setRowHeight(max(25, $estLines * 13));
                    } else {
                        $sheet->getRowDimension($rowIndex)->setRowHeight(25);
                    }
                }

                // 6. FOOTER STYLING (Posisi Tengah Tabel E-H)
                $footerColLetter = 'E'; 
                $footerStart = $this->footerStartRow;

                // Merge dan center text footer dari E sampai kolom terakhir
                for ($i = $footerStart; $i <= $highestRow; $i++) {
                    $sheet->mergeCells("{$footerColLetter}{$i}:{$lastColLetter}{$i}");
                    $sheet->getStyle("{$footerColLetter}{$i}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Bold & Underline untuk Nama Kepala
                $namaRow = $highestRow - 1;
                $nipRow  = $highestRow;
                
                if ($namaRow >= $footerStart) {
                    $sheet->getStyle("{$footerColLetter}{$namaRow}")->getFont()
                        ->setBold(true)
                        ->setUnderline(\PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_SINGLE);
                }
                
                if ($nipRow >= $footerStart) {
                    $sheet->getStyle("{$footerColLetter}{$nipRow}")->getFont()
                        ->setBold(true)
                        ->setUnderline(\PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_NONE);
                }
            },
        ];
    }
}