<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PerformanceSampelBoyExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private array $rows;
    private array $detailCodeHeaders;
    private string $selectedDate;
    private string $selectedOffice;

    public function __construct(array $rows, array $detailCodeHeaders, string $selectedDate, string $selectedOffice)
    {
        $this->rows = $rows;
        $this->detailCodeHeaders = $detailCodeHeaders;
        $this->selectedDate = $selectedDate;
        $this->selectedOffice = $selectedOffice;
    }

    public function collection(): Collection
    {
        return collect($this->rows);
    }

    public function headings(): array
    {
        $headings = [
            'Tanggal',
            'Tim',
            'Jam Mulai Proses',
            'Jam Akhir Proses',
            'Jam Awal Break Time',
            'Jam Akhir Break Time',
            'Total Hours',
            'Nama Sampel Boy',
            'Fiber Cyclone',
            'LTDS',
            'Claybath Wet Shell',
            'Inlet Kernel Silo',
            'Outlet Kernel Silo',
            'Press',
            'Eficiency',
            'Perf Total',
        ];

        foreach ($this->detailCodeHeaders as $detailCode) {
            $headings[] = (string) ($detailCode['label'] ?? $detailCode['code'] ?? '');
        }

        $headings[] = 'Perf Total Detail';

        return $headings;
    }

    public function map($row): array
    {
        $mapped = [
            $row['tanggal'] ?? '-',
            $row['team_name'] ?? '-',
            $row['jam_mulai'] ?? '-',
            $row['jam_akhir'] ?? '-',
            $row['downtime_awal'] ?? '-',
            $row['downtime_akhir'] ?? '-',
            $row['total_hours'] ?? '-',
            $row['nama_sampel_boy'] ?? '-',
            $row['fibre_cyclone'] ?? '0/0',
            $row['ltds'] ?? '0/0',
            $row['claybath_wet_shell'] ?? '0/0',
            $row['inlet_kernel_silo'] ?? '0/0',
            $row['outlet_kernel_silo'] ?? '0/0',
            $row['press'] ?? '0/0',
            $row['eficiency'] ?? '0/0',
            $row['perf_total'] ?? '-',
        ];

        foreach ($this->detailCodeHeaders as $detailCode) {
            $code = (string) ($detailCode['code'] ?? '');
            $mapped[] = $row['detail_values'][$code] ?? '0/0';
        }

        $mapped[] = $row['detail_perf_total'] ?? '-';

        return $mapped;
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->insertNewRowBefore(1, 2);
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        $sheet->setCellValue('A1', 'PERFORMANCE SAMPEL BOY');
        $sheet->mergeCells('A1:' . $lastColumn . '1');

        $sheet->setCellValue(
            'A2',
            'Tanggal: ' . $this->selectedDate . ' | Office: ' . strtoupper($this->selectedOffice)
        );
        $sheet->mergeCells('A2:' . $lastColumn . '2');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle('A3:' . $lastColumn . '3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle('A3:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        return [];
    }
}
