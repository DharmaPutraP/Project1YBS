<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class ReportsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected Builder $query;
    protected $startDate;
    protected $endDate;
    protected $kode;

    public function __construct(Builder $query, $startDate, $endDate, $kode = null)
    {
        $this->query = $query;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->kode = $kode;
    }

    /**
     * Return data query
     */
    public function query(): Builder
    {
        return $this->query;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->getDelegate()
                    ->getStyle('A1:ZZ1000') // seluruh area
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00;(#,##0.00)');
            },
        ];
    }

    /**
     * Define column headers
     */
    public function headings(): array
    {
        return [
            'NO',
            'BULAN',
            'TANGGAL',
            'JAM',
            'TGL & JAM AKHIR INPUT',
            'TANGGAL SAMPEL',
            'KODE',
            'INPUTED BY',
            'NAMA PIVOT',
            'OPERATOR',
            'SAMPEL BOY',
            'JENIS OLAH',
            'CAWAN KOSONG',
            'BERAT SAMPEL BASAH',
            'CAWAN + BASAH',
            'CAWAN + KERING',
            'SETELAH OVEN',
            'LABU KOSONG',
            'OIL + LABU',
            'MINYAK',
            'MOIST (%)',
            'DM/WM (%)',
            'OLWB (%)',
            'LIMIT (%)',
            'OLDB (%)',
            'LIMIT (%)',
            'OIL LOSSES',
            'LIMIT',
            'PERSEN 4',
        ];
    }

    /**
     * Map data rows
     */
    public function map($row): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            Carbon::parse($row->created_at)->format('F Y'),
            Carbon::parse($row->created_at)->format('d-m-Y'),
            Carbon::parse($row->created_at)->format('H:i:s'),
            $row->updated_at ? Carbon::parse($row->updated_at)->format('d-m-Y H:i:s') : '-',
            $row->tanggal_sampel ? Carbon::parse($row->tanggal_sampel)->format('d-m-Y') : '-',
            $row->kode ?? '-',
            $row->user_name ?? '-',
            $row->pivot ?? '-',
            $row->operator ?? '-',
            $row->sampel_boy ?? '-',
            $row->jenis ?? '-',
            // Return raw numbers instead of formatted strings for Excel to format with parentheses for negatives
            $row->cawan_kosong !== null ? $row->cawan_kosong : '-',
            $row->berat_basah !== null ? $row->berat_basah : '-',
            $row->total_cawan_basah !== null ? $row->total_cawan_basah : '-',
            $row->cawan_sample_kering !== null ? $row->cawan_sample_kering : '-',
            $row->sampel_setelah_oven !== null ? $row->sampel_setelah_oven : '-',
            $row->labu_kosong !== null ? $row->labu_kosong : '-',
            $row->oil_labu !== null ? $row->oil_labu : '-',
            $row->minyak !== null ? $row->minyak : '-',
            $row->moist !== null ? $row->moist : '-',
            $row->dmwm !== null ? $row->dmwm : '-',
            $row->olwb !== null ? $row->olwb : '-',
            $row->limitOLWB === null || $row->limitOLWB == 0 ? '-' : $row->limitOLWB,
            $row->oldb !== null ? $row->oldb : '-',
            $row->limitOLDB === null || $row->limitOLDB == 0 ? '-' : $row->limitOLDB,
            $row->oil_losses !== null ? $row->oil_losses : '-',
            $row->limitOL === null || $row->limitOL == 0 ? '-' : $row->limitOL,
            $row->persen4 !== null ? $row->persen4 : '-',
        ];
    }

    /**
     * Apply styles to worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Add title and info at the top
        $sheet->insertNewRowBefore(1, 3);

        $title = 'LAPORAN DATA OIL LOSSES';
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:AC1');

        $filterInfo = 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y') .
            ' s/d ' . Carbon::parse($this->endDate)->format('d-m-Y');
        if ($this->kode) {
            $filterInfo .= ' | Kode: ' . $this->kode;
        }
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:AC2');

        // Style title
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style filter info
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style header row (now row 4 after inserting 3 rows)
        $sheet->getStyle('A4:AC4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Indigo
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Add borders to all data cells
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A4:AC' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Align numeric columns to right (columns L-AB)
        $sheet->getStyle('M5:AC' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ]);

        // Apply conditional formatting for OLWB, OLDB, and OIL LOSSES
        // Starting from row 5 (row 4 is header, rows 1-3 are title/info)
        for ($row = 5; $row <= $lastRow; $row++) {
            // OLWB (column W) vs LIMIT OLWB (column X)
            $olwbValue = $sheet->getCell('W' . $row)->getValue();
            $limitOLWBValue = $sheet->getCell('X' . $row)->getValue();

            if ($olwbValue !== '-' && $limitOLWBValue !== '-' && is_numeric($olwbValue) && is_numeric($limitOLWBValue)) {
                if ((float) $olwbValue <= (float) $limitOLWBValue) {
                    // Green: Good
                    $sheet->getStyle('W' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true], // green-600
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']], // green-100
                    ]);
                } elseif ((float) $limitOLWBValue > 0) {
                    // Red: Bad
                    $sheet->getStyle('W' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true], // red-600
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']], // red-100
                    ]);
                }
            }

            // OLDB (column Y) vs LIMIT OLDB (column Z)
            $oldbValue = $sheet->getCell('Y' . $row)->getValue();
            $limitOLDBValue = $sheet->getCell('Z' . $row)->getValue();

            if ($oldbValue !== '-' && $limitOLDBValue !== '-' && is_numeric($oldbValue) && is_numeric($limitOLDBValue)) {
                if ((float) $oldbValue <= (float) $limitOLDBValue) {
                    // Green: Good
                    $sheet->getStyle('Y' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                    ]);
                } elseif ((float) $limitOLDBValue > 0) {
                    // Red: Bad
                    $sheet->getStyle('Y' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']],
                    ]);
                }
            }

            // OIL LOSSES (column AA) vs LIMIT OL (column AB)
            $oilLossesValue = $sheet->getCell('AA' . $row)->getValue();
            $limitOLValue = $sheet->getCell('AB' . $row)->getValue();

            if ($oilLossesValue !== '-' && $limitOLValue !== '-' && is_numeric($oilLossesValue) && is_numeric($limitOLValue)) {
                if ((float) $oilLossesValue <= (float) $limitOLValue) {
                    // Green: Good
                    $sheet->getStyle('AA' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                    ]);
                } elseif ((float) $limitOLValue > 0) {
                    // Red: Bad
                    $sheet->getStyle('AA' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']],
                    ]);
                }
            }
        }

        // Apply number formatting with negative numbers in parentheses
        // Format: 0.00;(0.00) means positive shown as 0.00, negative as (0.00)
        $dataStartRow = 5;
        $dataEndRow = $sheet->getHighestRow();

        if ($dataEndRow >= $dataStartRow) {
            // 4 decimal places: columns M-T (numeric weights and oil values)
            $sheet->getStyle('M' . $dataStartRow . ':T' . $dataEndRow)->getNumberFormat()->setFormatCode('0.0000;(0.0000)');

            // 2 decimal places: columns U-AC (percentages and limits)
            $sheet->getStyle('U' . $dataStartRow . ':AC' . $dataEndRow)->getNumberFormat()->setFormatCode('0.00;(0.00)');
        }

        $sheet->freezePane('M5');

        return $sheet;
    }

    /**
     * Set column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // NO
            'B' => 15,  // BULAN
            'C' => 12,  // TANGGAL
            'D' => 10,  // JAM
            'E' => 22,  // TGL & JAM AKHIR INPUT
            'F' => 13,  // TANGGAL SAMPEL
            'G' => 12,  // KODE
            'H' => 18,  // INPUTED BY
            'I' => 15,  // NAMA PIVOT
            'J' => 15,  // OPERATOR
            'K' => 15,  // SAMPEL BOY
            'L' => 12,  // JENIS OLAH
            'M' => 14,  // CAWAN KOSONG
            'N' => 13,  // BERAT BASAH
            'O' => 14,  // CAWAN + BASAH
            'P' => 15,  // CAWAN + KERING
            'Q' => 14,  // SETELAH OVEN
            'R' => 13,  // LABU KOSONG
            'S' => 12,  // OIL + LABU
            'T' => 12,  // MINYAK
            'U' => 11,  // MOIST (%)
            'V' => 11,  // DMWM (%)
            'W' => 11,  // OLWB (%)
            'X' => 11,  // LIMIT OLWB (%)
            'Y' => 11,  // OLDB (%)
            'Z' => 11,  // LIMIT OLDB (%)
            'AA' => 12, // OIL LOSSES
            'AB' => 11, // LIMIT OL
            'AC' => 11, // PERSEN 4
        ];
    }
}
