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
    private string $selectedDate;
    private string $selectedOffice;

    public function __construct(array $rows, string $selectedDate, string $selectedOffice)
    {
        $this->rows = $rows;
        $this->selectedDate = $selectedDate;
        $this->selectedOffice = $selectedOffice;
    }

    public function collection(): Collection
    {
        return collect($this->rows);
    }

    public function headings(): array
    {
        if ($this->isSunOffice()) {
            return [
                'Tanggal',
                'Tim',
                'Jam Mulai Proses',
                'Jam Akhir Proses',
                'Total Hours',
                'Nama Sampel Boy',
                'Fiber Cyclone',
                'LTDS',
                'Claybath Wet Shell',
                'Inlet Kernel Silo',
                'Outlet Kernel Silo',
                'Press',
                'Eficiency',
                'Destoner',
                'FFA dan Moisture',
                'Spintest COT',
                'Underflow CST (CST 1 & CST 2)',
                'Feed Decanter (GEA1, GEA2)',
                'Light Phase (GEA1, GEA2)',
                'Sterilizer 1-4',
                'Perf Total',
            ];
        }

        return [
            'Tanggal',
            'Tim',
            'Jam Mulai Proses',
            'Jam Akhir Proses',
            'Total Hours',
            'Nama Sampel Boy',
            'Fiber Cyclone',
            'LTDS',
            'Claybath Wet Shell',
            'Inlet Kernel Silo',
            'Outlet Kernel Silo',
            'Press',
            'Eficiency',
            'Destoner',
            'FFA dan Moisture',
            'Spintest COT',
            'Underflow CST (CST 1 & CST 2)',
            'Feed Decanter (Alfa Laval 1, Alfa Laval 2, GEA, Flottweg)',
            'Light Phase (Alfa Laval 1, Alfa Laval 2, GEA, Flottweg)',
            'Sterilizer 1-8',
            'COT (COT IN & COT 2)',
            'CST',
            'FD (FD1-4)',
            'HP (HP 1-4)',
            'SD (SD1-4)',
            'HPL (HPL1-3)',
            'FE',
            'FBP (FBP1-5)',
            'FP (FP1-9)',
            'Perf Total',
        ];
    }

    public function map($row): array
    {
        if ($this->isSunOffice()) {
            return [
                $row['tanggal'] ?? '-',
                $row['team_name'] ?? '-',
                $row['jam_mulai'] ?? '-',
                $row['jam_akhir'] ?? '-',
                $row['total_hours'] ?? '-',
                $row['nama_sampel_boy'] ?? '-',
                $row['fibre_cyclone'] ?? '0/0',
                $row['ltds'] ?? '0/0',
                $row['claybath_wet_shell'] ?? '0/0',
                $row['inlet_kernel_silo'] ?? '0/0',
                $row['outlet_kernel_silo'] ?? '0/0',
                $row['press'] ?? '0/0',
                $row['eficiency'] ?? '0/0',
                $row['destoner'] ?? '0/0',
                $row['ffa_moisture'] ?? '0/0',
                $row['spintest_cot'] ?? '0/0',
                $row['underflow_cst'] ?? '0/0',
                $row['feed_decanter'] ?? '0/0',
                $row['light_phase'] ?? '0/0',
                $row['sterilizer'] ?? '0/0',
                $row['perf_total'] ?? '-',
            ];
        }

        return [
            $row['tanggal'] ?? '-',
            $row['team_name'] ?? '-',
            $row['jam_mulai'] ?? '-',
            $row['jam_akhir'] ?? '-',
            $row['total_hours'] ?? '-',
            $row['nama_sampel_boy'] ?? '-',
            $row['fibre_cyclone'] ?? '0/0',
            $row['ltds'] ?? '0/0',
            $row['claybath_wet_shell'] ?? '0/0',
            $row['inlet_kernel_silo'] ?? '0/0',
            $row['outlet_kernel_silo'] ?? '0/0',
            $row['press'] ?? '0/0',
            $row['eficiency'] ?? '0/0',
            $row['destoner'] ?? '0/0',
            $row['ffa_moisture'] ?? '0/0',
            $row['spintest_cot'] ?? '0/0',
            $row['underflow_cst'] ?? '0/0',
            $row['feed_decanter'] ?? '0/0',
            $row['light_phase'] ?? '0/0',
            $row['sterilizer'] ?? '0/0',
            $row['cot'] ?? '0/0',
            $row['cst'] ?? '0/0',
            $row['fd'] ?? '0/0',
            $row['hp'] ?? '0/0',
            $row['sd'] ?? '0/0',
            $row['hpl'] ?? '0/0',
            $row['fe'] ?? '0/0',
            $row['fbp'] ?? '0/0',
            $row['fp'] ?? '0/0',
            $row['perf_total'] ?? '-',
        ];
    }

    private function isSunOffice(): bool
    {
        return strtoupper(trim($this->selectedOffice)) === 'SUN';
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
