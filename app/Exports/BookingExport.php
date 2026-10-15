<?php

namespace App\Exports;

use App\Models\Booking;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingExport implements FromArray, WithColumnWidths, WithEvents, WithStyles
{
    use Exportable;

    protected array $data = [];

    protected int $headerRow = 0;

    protected int $firstDataRow = 0;

    protected int $lastDataRow = 0;

    public function __construct()
    {
        $this->buildData();
    }

    private function buildData(): void
    {
        $tahun = Carbon::now()->format('Y');

        // BARIS 1: Judul Utama
        $this->data[] = ["RIWAYAT BOOKING AULA BAPPELITBANGDA {$tahun}"];

        // BARIS 2: Sub judul
        $this->data[] = ['Dicetak pada: '.Carbon::now()->translatedFormat('d F Y, H:i').' WIB'];

        // BARIS 3: Kosong (spacer)
        $this->data[] = ['', '', '', '', '', '', '', '', '', ''];

        // BARIS 4: Kosong lagi (spacer kedua biar ada jarak lebih lega)
        $this->data[] = ['', '', '', '', '', '', '', '', '', ''];

        // BARIS 5: Header Kolom
        $this->data[] = [
            'No.',
            'Nama Pengguna',
            'Email',
            'Aula',
            'Tanggal Booking',
            'Sesi Waktu',
            'Penanggung Jawab',
            'Keperluan',
            'Jumlah Peserta',
            'Status',
        ];

        // ⭐ Hitung headerRow setelah header ditambahkan
        $this->headerRow = count($this->data); // = 5

        // BARIS DATA
        $bookings = Booking::with(['user:id,name,email', 'aula:id,nama'])
            ->latest()
            ->get();

        $this->firstDataRow = count($this->data) + 1; // = 6

        foreach ($bookings as $index => $b) {
            $this->data[] = [
                $index + 1,
                $b->user?->name ?? '-',
                $b->user?->email ?? '-',
                $b->aula?->nama ?? '-',
                $this->formatTanggal($b->tanggal_booking),
                $b->sesi_waktu ?? '-',
                $b->nama_penanggung_jawab ?? '-',
                $b->keperluan ?? '-',
                $b->jumlah_peserta ?? 0,
                ucfirst((string) ($b->status ?? '-')),
            ];
        }

        $this->lastDataRow = count($this->data);

        // Kalau tidak ada data, tetap ada baris kosong biar border tetap kelihatan
        if ($this->firstDataRow > $this->lastDataRow) {
            $this->data[] = ['-', '-', '-', '-', '-', '-', '-', '-', '-', '-'];
            $this->lastDataRow = count($this->data);
        }
    }

    private function formatTanggal($value): string
    {
        if (empty($value)) {
            return '-';
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('d-m-Y');
        }

        try {
            return Carbon::parse($value)->format('d-m-Y');
        } catch (\Exception $e) {
            return (string) $value;
        }
    }

    public function array(): array
    {
        return $this->data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 24,
            'C' => 30,
            'D' => 24,
            'E' => 18,
            'F' => 18,
            'G' => 26,
            'H' => 42,
            'I' => 14,
            'J' => 14,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['name' => 'Arial', 'bold' => true, 'size' => 16],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
            2 => [
                'font' => ['name' => 'Arial', 'italic' => true, 'size' => 11],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
            $this->headerRow => [
                'font' => ['name' => 'Arial', 'bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
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

                // ⭐ FONT DEFAULT
                $sheet->getStyle("A1:J{$highestRow}")->getFont()
                    ->setName('Arial')
                    ->setSize(11);

                // ⭐ WRAPTEXT GLOBAL
                $sheet->getStyle("A1:J{$highestRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_TOP)
                    ->setWrapText(true)
                    ->setShrinkToFit(false);

                // ⭐ MERGE JUDUL
                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:J2');

                // ⭐ STYLE JUDUL UTAMA
                $sheet->getStyle('A1')->getFont()
                    ->setName('Arial')
                    ->setBold(true)
                    ->setSize(16);
                $sheet->getStyle('A1:J1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                // ⭐ STYLE SUB JUDUL
                $sheet->getStyle('A2:J2')->getFont()
                    ->setName('Arial')
                    ->setItalic(true)
                    ->setSize(11);
                $sheet->getStyle('A2:J2')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                // ⭐ STYLE HEADER KOLOM
                $sheet->getStyle("A{$this->headerRow}:J{$this->headerRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                // ⭐ ROW HEIGHTS
                $sheet->getRowDimension(1)->setRowHeight(34);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(8);  // spacer kecil
                $sheet->getRowDimension(4)->setRowHeight(8);  // spacer kecil
                $sheet->getRowDimension($this->headerRow)->setRowHeight(40);

                // ⭐ STYLE DATA ROWS
                for ($row = $this->firstDataRow; $row <= $this->lastDataRow; $row++) {
                    $sheet->getStyle("A{$row}:J{$row}")->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                    ]);

                    // Kolom A (No.), E-F (Tanggal & Sesi), I-J (Peserta & Status) → center
                    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("E{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("I{$row}:J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Auto row height
                    $nameLen = mb_strlen((string) $sheet->getCell("B{$row}")->getValue());
                    $emailLen = mb_strlen((string) $sheet->getCell("C{$row}")->getValue());
                    $aulaLen = mb_strlen((string) $sheet->getCell("D{$row}")->getValue());
                    $pjLen = mb_strlen((string) $sheet->getCell("G{$row}")->getValue());
                    $keperluanLen = mb_strlen((string) $sheet->getCell("H{$row}")->getValue());

                    $estLines = max(
                        1,
                        (int) ceil($nameLen / 24),
                        (int) ceil($emailLen / 30),
                        (int) ceil($aulaLen / 24),
                        (int) ceil($pjLen / 26),
                        (int) ceil($keperluanLen / 42)
                    );
                    $sheet->getRowDimension($row)->setRowHeight(max(24, min(90, $estLines * 16)));
                }

                // ⭐ FREEZE PANE di bawah header
                $sheet->freezePane("A{$this->firstDataRow}");

                // ⭐ PAGE SETUP
                $sheet->getPageSetup()->setOrientation('landscape');
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}