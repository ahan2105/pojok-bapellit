<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    public function query(): EloquentBuilder|QueryBuilder|Relation
    {
        return Booking::query()->with(['user:id,name,email', 'aula:id,nama'])->latest();
    }

    public function headings(): array
    {
        return [
            'No.', 'Nama Pengguna', 'Email', 'Aula', 'Tanggal Booking',
            'Sesi Waktu', 'Penanggung Jawab', 'Keperluan', 'Jumlah Peserta', 'Status',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->user?->name ?? '-',
            $booking->user?->email ?? '-',
            $booking->aula?->nama ?? '-',
            $booking->tanggal_booking?->format('d-m-Y') ?? $booking->tanggal_booking ?? '-',
            $booking->sesi_waktu ?? '-',
            $booking->nama_penanggung_jawab ?? '-',
            $booking->keperluan ?? '-',
            $booking->jumlah_peserta ?? 0,
            ucfirst((string) ($booking->status ?? '-')),
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        $lastRow = max($sheet->getHighestRow(), 1);
        $sheet->getStyle("A1:J{$lastRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A1:J{$lastRow}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("A1:J{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
        ]);
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E2:F{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("I2:J{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->freezePane('A2');
        $sheet->getRowDimension(1)->setRowHeight(32);

        foreach (['A' => 10, 'B' => 24, 'C' => 30, 'D' => 24, 'E' => 18, 'F' => 18, 'G' => 26, 'H' => 42, 'I' => 18, 'J' => 16] as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $sheet->getPageSetup()->setOrientation('landscape');
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        return [];
    }
}
