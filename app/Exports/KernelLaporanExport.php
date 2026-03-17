<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class KernelLaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $masterData;
    protected $startDate;
    protected $endDate;
    protected $kode;

    public function __construct($data, $masterData, string $startDate, string $endDate, ?string $kode = null)
    {
        $this->data       = $data;
        $this->masterData = $masterData;
        $this->startDate  = $startDate;
        $this->endDate    = $endDate;
        $this->kode       = $kode;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'NO',
            'BULAN',
            'TANGGAL',
            'JAM',
            'KODE',
            'NAMA SAMPLE',
            'INPUTED BY',
            'SUMBER DATA',
            'JENIS',
            'OPERATOR',
            'SAMPEL BOY',
            'BERAT SAMPEL (g)',
            'NUT UTUH - NUT (g)',
            'NUT UTUH - KERNEL (g)',
            'NUT PECAH - NUT (g)',
            'NUT PECAH - KERNEL (g)',
            'KERNEL UTUH (g)',
            'KERNEL PECAH (g)',
            'KTS NUT UTUH (%)',
            'KTS NUT PECAH (%)',
            'KERNEL UTUH/SAMPEL (%)',
            'KERNEL PECAH/SAMPEL (%)',
            'KERNEL LOSSES (%)',
            'LIMIT',
        ];
    }

    public function map($row): array
    {
        static $counter = 0;
        $counter++;

        $master     = $this->masterData[$row->kode] ?? null;
        $lossPercent = $row->kernel_losses !== null ? round($row->kernel_losses * 100, 4) : '-';

        return [
            $counter,
            Carbon::parse($row->created_at)->format('F Y'),
            Carbon::parse($row->created_at)->format('d-m-Y'),
            Carbon::parse($row->created_at)->format('H:i:s'),
            $row->kode ?? '-',
            $master ? $master->nama_sample : '-',
            data_get($row, 'user.name', '-'),
            $row->source_module ?? 'Kernel Losses',
            $row->jenis ?? '-',
            $row->operator ?? '-',
            $row->sampel_boy ?? '-',
            $row->berat_sampel !== null ? (float) $row->berat_sampel : '-',
            $row->nut_utuh_nut !== null ? (float) $row->nut_utuh_nut : '-',
            $row->nut_utuh_kernel !== null ? (float) $row->nut_utuh_kernel : '-',
            $row->nut_pecah_nut !== null ? (float) $row->nut_pecah_nut : '-',
            $row->nut_pecah_kernel !== null ? (float) $row->nut_pecah_kernel : '-',
            $row->kernel_utuh !== null ? (float) $row->kernel_utuh : '-',
            $row->kernel_pecah !== null ? (float) $row->kernel_pecah : '-',
            $row->kernel_to_sampel_nut_utuh !== null ? (float) $row->kernel_to_sampel_nut_utuh : '-',
            $row->kernel_to_sampel_nut_pecah !== null ? (float) $row->kernel_to_sampel_nut_pecah : '-',
            $row->kernel_utuh_to_sampel !== null ? (float) $row->kernel_utuh_to_sampel : '-',
            $row->kernel_pecah_to_sampel !== null ? (float) $row->kernel_pecah_to_sampel : '-',
            $lossPercent,
            $master ? $master->limit_label : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Insert 3 rows at top for title + period info
        $sheet->insertNewRowBefore(1, 3);

        $sheet->setCellValue('A1', 'LAPORAN DATA KERNEL LOSSES');
        $sheet->mergeCells('A1:X1');

        $filterInfo = 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y')
            . ' s/d ' . Carbon::parse($this->endDate)->format('d-m-Y');
        if ($this->kode) {
            $filterInfo .= ' | Kode: ' . $this->kode;
        }
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:X2');

        // Title style
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Period info style
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Header row (row 4 after inserting 3)
        $sheet->getStyle('A4:X4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        $lastRow = $sheet->getHighestRow();

        // All data borders
        $sheet->getStyle('A4:X' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        ]);

        // Right-align numeric columns L-X
        $sheet->getStyle('L5:X' . $lastRow)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);

        // Conditional color for KERNEL LOSSES column (V = col 22)
        for ($row = 5; $row <= $lastRow; $row++) {
            $kodeVal = $sheet->getCell('E' . $row)->getValue();
            $lossVal = $sheet->getCell('W' . $row)->getValue();

            if ($kodeVal && isset($this->masterData[$kodeVal]) && is_numeric($lossVal)) {
                $master   = $this->masterData[$kodeVal];
                $exceeded = $master->isExceeded((float) $lossVal);

                $sheet->getStyle('W' . $row)->applyFromArray($exceeded ? [
                    'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']],
                ] : [
                    'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                ]);
            }
        }

        return [];
    }
}
