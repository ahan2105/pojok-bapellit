<?php

namespace App\Exports;
use App\Models\AbsensiSesi;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiSesiExport implements FromArray, WithStyles, WithColumnWidths, WithEvents
{
    protected AbsensiSesi $sesi;
    protected array $data = [];
    protected array $groupHeaderRows = [];
    protected array $dataRows = [];

    public function __construct(AbsensiSesi $sesi)
    {
        $this->sesi = $sesi;
        $this->buildData();
    }

    private function buildData(): void
    {
        $sesi = $this->sesi;

        // ⭐ AMBIL TAHUN DARI TANGGAL SESI (DINAMIS)
        $tahun = Carbon::parse($sesi->tanggal)->format('Y');

        // BARIS 1: Judul Utama dengan tahun dinamis (merge A-H)
        $this->data[] = ["DAFTAR HADIR PEGAWAI ASN BAPPELITBANGDA {$tahun}"];
        
        // BARIS 2: Nama Sesi (merge A-H)
        $sessionTitle = $sesi->is_default ? 'ICE BREAKING' : strtoupper($sesi->nama_sesi);
        $this->data[] = [$sessionTitle];
        
        // BARIS 3: Kosong
        $this->data[] = ['', '', '', '', '', '', '', ''];

        // BARIS 4: HARI
        $hari = Carbon::parse($sesi->tanggal)->translatedFormat('l');
        $this->data[] = ['HARI', '', ':', $hari, '', '', '', ''];

        // BARIS 5: TANGGAL
        $tanggal = Carbon::parse($sesi->tanggal)->translatedFormat('d F Y');
        $this->data[] = ['TANGGAL', '', ':', $tanggal, '', '', '', ''];

        // BARIS 6-7: Kosong
        $this->data[] = ['', '', '', '', '', '', '', ''];
        $this->data[] = ['', '', '', '', '', '', '', ''];

        // BARIS 8: Header Kolom
        $this->data[] = [
            'NO',
            'NO',
            'NAMA',
            'GOL',
            'NIP/ NI PPPK/ NI PPPK PW',
            'JABATAN',
            'STATUS KEHADIRAN',
            'KETERANGAN',
        ];

        // Spacer sebelum data
        $this->data[] = ['', '', '', '', '', '', '', ''];

        // ⭐========================================================
        // ⭐ Ambil HANYA peserta yang terdaftar di sesi ini
        // ⭐========================================================
        $peserta = User::join('absensi_detail', function ($join) use ($sesi) {
                $join->on('users.id', '=', 'absensi_detail.user_id')
                     ->where('absensi_detail.absensi_sesi_id', '=', $sesi->id);
            })
            ->select(
                'users.*',
                'absensi_detail.status_kehadiran',
                'absensi_detail.keterangan'
            )
            ->orderBy('users.name')
            ->get();

        // ⭐ 1. Group per Bidang dengan normalisasi string yang KUAT
        $groupedRaw = $peserta->groupBy(function ($p) {
            $b = trim(strtoupper($p->bidang ?? ''));
            $b = preg_replace('/\s+/', ' ', $b);
            return $b === '' ? 'TANPA BIDANG' : $b;
        });

        // ⭐ 2. Pisahkan prioritas
        $kepalaBadan = $groupedRaw->pull('KEPALA BADAN', collect());
        $tanpaBidang = $groupedRaw->pull('TANPA BIDANG', collect());
        
        // ⭐ 3. Sisa bidang
        $bidangLainnya = $groupedRaw->sortKeys();

        // ⭐ 4. Gabungkan kembali
        $groupedPeserta = collect();
        
        if ($kepalaBadan->isNotEmpty()) {
            $groupedPeserta->put('KEPALA BADAN', $kepalaBadan);
        }
        
        foreach ($bidangLainnya as $bidang => $users) {
            $groupedPeserta->put($bidang, $users);
        }
        
        if ($tanpaBidang->isNotEmpty()) {
            $groupedPeserta->put('TANPA BIDANG', $tanpaBidang);
        }

        $noGlobal = 1;

        // ⭐ 5. Proses data peserta
        foreach ($groupedPeserta as $bidang => $groupPeserta) {
            $noBidang = 1;

            if ($bidang !== 'KEPALA BADAN') {
                $this->data[] = [strtoupper($bidang), '', '', '', '', '', '', ''];
                $this->groupHeaderRows[] = count($this->data);
            }

            foreach ($groupPeserta as $p) {
                $statusText = match (strtolower($p->status_kehadiran ?? '')) {
                    'hadir' => 'Hadir',
                    'tidak' => 'Tidak Hadir',
                    default => '-',
                };

                $this->data[] = [
                    $noGlobal,
                    $noBidang,
                    $p->name,
                    $p->golongan ?? '',
                    $p->nip ?? '',
                    $p->jabatan ?? '',
                    $statusText,
                    $p->keterangan ?? '',
                ];

                $this->dataRows[] = count($this->data);
                $noGlobal++;
                $noBidang++;
            }
        }

        // ⭐========================================================
        // ⭐ AMBIL DATA KEPALA BADAN LANGSUNG DARI DATABASE
        // ⭐ (meskipun Kepala Badan tidak ikut jadi peserta sesi ini)
        // ⭐========================================================
        $kepalaUser = User::where('status', 'aktif')
            ->where(function ($q) {
                $q->where('bidang', 'KEPALA BADAN')
                  ->orWhere('bidang', 'like', 'KEPALA BADAN%');
            })
            ->orderBy('id')
            ->first();

        // Fallback: cari via jabatan kalau via bidang tidak ketemu
        if (!$kepalaUser) {
            $kepalaUser = User::where('status', 'aktif')
                ->where('jabatan', 'like', '%Kepala Badan%')
                ->orderBy('id')
                ->first();
        }

        $namaKepala = $kepalaUser->name ?? '........................................';
        $nipKepala  = $kepalaUser && $kepalaUser->nip
            ? 'NIP. ' . $kepalaUser->nip
            : 'NIP. ................................';

        // FOOTER - Tambah spacer
        $this->data[] = ['', '', '', '', '', '', '', ''];
        $this->data[] = ['', '', '', '', '', '', '', ''];
        $this->data[] = ['', '', '', '', '', '', '', ''];
        
        // Tanggal dan TTD
        $currentDate = Carbon::now()->translatedFormat('d F Y');
        $this->data[] = ['', '', '', '', 'Singaparna, ' . $currentDate, '', '', ''];
        $this->data[] = ['', '', '', '', 'Mengetahui', '', '', ''];
        $this->data[] = ['', '', '', '', 'Kepala Badan Perencanaan Pembangunan,', '', '', ''];
        $this->data[] = ['', '', '', '', 'Penelitian dan Pengembangan Daerah Kabupaten Tasikmalaya', '', '', ''];
        $this->data[] = ['', '', '', '', '', '', '', ''];
        $this->data[] = ['', '', '', '', '', '', '', ''];
        $this->data[] = ['', '', '', '', '', '', '', ''];
        $this->data[] = ['', '', '', '', $namaKepala, '', '', ''];
        $this->data[] = ['', '', '', '', $nipKepala, '', '', ''];
    }

    public function array(): array
    {
        return $this->data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 5,
            'C' => 35,
            'D' => 8,
            'E' => 25,
            'F' => 30,
            'G' => 20,
            'H' => 25,
        ];
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
            8 => [
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9E1F2'],
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Merge Header
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');
                $sheet->mergeCells('A4:B4');
                $sheet->mergeCells('D4:H4');
                $sheet->mergeCells('A5:B5');
                $sheet->mergeCells('D5:H5');

                // Row Heights
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(2)->setRowHeight(22);
                $sheet->getRowDimension(4)->setRowHeight(20);
                $sheet->getRowDimension(5)->setRowHeight(20);
                $sheet->getRowDimension(8)->setRowHeight(25);

                // Style Group Headers
                foreach ($this->groupHeaderRows as $rowIndex) {
                    $sheet->mergeCells("A{$rowIndex}:H{$rowIndex}");
                    $sheet->getStyle("A{$rowIndex}")->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F2F2F2'],
                        ],
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                    ]);
                    $sheet->getRowDimension($rowIndex)->setRowHeight(20);
                }

                // Style Data Rows
                foreach ($this->dataRows as $rowIndex) {
                    $sheet->getStyle("A{$rowIndex}:H{$rowIndex}")->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                    
                    $sheet->getStyle("A{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("G{$rowIndex}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    
                    $sheet->getRowDimension($rowIndex)->setRowHeight(25);

                    // Warna conditional berdasarkan data DB asli
                    $statusValue = $sheet->getCell("G{$rowIndex}")->getValue();
                    if ($statusValue === 'Hadir') {
                        $sheet->getStyle("G{$rowIndex}")->getFont()
                            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('10B981'))
                            ->setBold(true);
                    } elseif ($statusValue === 'Tidak Hadir') {
                        $sheet->getStyle("G{$rowIndex}")->getFont()
                            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('DC2626'))
                            ->setBold(true);
                    }
                }

                // Footer - Merge untuk TTD
                $footerStart = $highestRow - 8;
                for ($i = $footerStart; $i <= $highestRow; $i++) {
                    if ($i > 0) {
                        $sheet->mergeCells("E{$i}:H{$i}");
                        $sheet->getStyle("E{$i}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // ⭐ BOLD + UNDERLINE HANYA untuk nama pejabat
                // ⭐ NIP sengaja TIDAK di-underline
                $namaRow = $highestRow - 1;
                $nipRow  = $highestRow;

                if ($namaRow > 0) {
                    // Nama: bold + underline
                    $sheet->getStyle("E{$namaRow}")->getFont()
                        ->setBold(true)
                        ->setUnderline(\PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_SINGLE);
                }

                if ($nipRow > 0) {
                    // NIP: bold tanpa underline (explicitly clear)
                    $sheet->getStyle("E{$nipRow}")->getFont()
                        ->setBold(true)
                        ->setUnderline(\PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_NONE);
                }
            },
        ];
    }
}