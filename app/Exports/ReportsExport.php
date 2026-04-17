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
            'JAM AKHIR INPUT',
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
            $row->updated_at ? Carbon::parse($row->updated_at)->format('H:i:s') : '-',
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
        $sheet->mergeCells('A1:AB1');

        $filterInfo = 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y') .
            ' s/d ' . Carbon::parse($this->endDate)->format('d-m-Y');
        if ($this->kode) {
            $filterInfo .= ' | Kode: ' . $this->kode;
        }
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:AB2');

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
        $sheet->getStyle('A4:AB4')->applyFromArray([
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
        $sheet->getStyle('A4:AB' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Align numeric columns to right (columns L-AB)
        $sheet->getStyle('L5:AB' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ]);

        // Apply conditional formatting for OLWB, OLDB, and OIL LOSSES
        // Starting from row 5 (row 4 is header, rows 1-3 are title/info)
        for ($row = 5; $row <= $lastRow; $row++) {
            // OLWB (column V) vs LIMIT OLWB (column W)
            $olwbValue = $sheet->getCell('V' . $row)->getValue();
            $limitOLWBValue = $sheet->getCell('W' . $row)->getValue();

            if ($olwbValue !== '-' && $limitOLWBValue !== '-' && is_numeric($olwbValue) && is_numeric($limitOLWBValue)) {
                if ((float) $olwbValue <= (float) $limitOLWBValue) {
                    // Green: Good
                    $sheet->getStyle('V' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true], // green-600
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']], // green-100
                    ]);
                } elseif ((float) $limitOLWBValue > 0) {
                    // Red: Bad
                    $sheet->getStyle('V' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true], // red-600
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']], // red-100
                    ]);
                }
            }

            // OLDB (column X) vs LIMIT OLDB (column Y)
            $oldbValue = $sheet->getCell('X' . $row)->getValue();
            $limitOLDBValue = $sheet->getCell('Y' . $row)->getValue();

            if ($oldbValue !== '-' && $limitOLDBValue !== '-' && is_numeric($oldbValue) && is_numeric($limitOLDBValue)) {
                if ((float) $oldbValue <= (float) $limitOLDBValue) {
                    // Green: Good
                    $sheet->getStyle('X' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                    ]);
                } elseif ((float) $limitOLDBValue > 0) {
                    // Red: Bad
                    $sheet->getStyle('X' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']],
                    ]);
                }
            }

            // OIL LOSSES (column Z) vs LIMIT OL (column AA)
            $oilLossesValue = $sheet->getCell('Z' . $row)->getValue();
            $limitOLValue = $sheet->getCell('AA' . $row)->getValue();

            if ($oilLossesValue !== '-' && $limitOLValue !== '-' && is_numeric($oilLossesValue) && is_numeric($limitOLValue)) {
                if ((float) $oilLossesValue <= (float) $limitOLValue) {
                    // Green: Good
                    $sheet->getStyle('Z' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                    ]);
                } elseif ((float) $limitOLValue > 0) {
                    // Red: Bad
                    $sheet->getStyle('Z' . $row)->applyFromArray([
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
            // 4 decimal places: columns L-Q (cawan_kosong, cawan_sample_basah, cawan_sample_kering, sampel_setelah_oven, labu_kosong, oil_labu)
            $sheet->getStyle('L' . $dataStartRow . ':Q' . $dataEndRow)->getNumberFormat()->setFormatCode('0.0000;(0.0000)');

            // 4 decimal places: columns R-S (oil_labu, minyak)
            $sheet->getStyle('R' . $dataStartRow . ':S' . $dataEndRow)->getNumberFormat()->setFormatCode('0.0000;(0.0000)');

            // 2 decimal places: columns T-AB (moist, dmwm, olwb, limitOLWB, oldb, limitOLDB, oil_losses, limitOL, persen4)
            $sheet->getStyle('T' . $dataStartRow . ':AB' . $dataEndRow)->getNumberFormat()->setFormatCode('0.00;(0.00)');
        }

        $sheet->freezePane('L5');

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
            'E' => 16,  // JAM AKHIR INPUT
            'F' => 12,  // KODE
            'G' => 18,  // INPUTED BY
            'H' => 15,  // NAMA PIVOT
            'I' => 15,  // OPERATOR
            'J' => 15,  // SAMPEL BOY
            'K' => 12,  // JENIS OLAH
            'L' => 14,  // CAWAN KOSONG
            'M' => 13,  // BERAT BASAH
            'N' => 14,  // CAWAN + BASAH
            'O' => 15,  // CAWAN + KERING
            'P' => 14,  // SETELAH OVEN
            'Q' => 13,  // LABU KOSONG
            'R' => 12,  // OIL + LABU
            'S' => 12,  // MINYAK
            'T' => 11,  // MOIST (%)
            'U' => 11,  // DM/WM (%)
            'V' => 11,  // OLWB (%)
            'W' => 11,  // LIMIT OLWB (%)
            'X' => 11,  // OLDB (%)
            'Y' => 11,  // LIMIT OLDB (%)
            'Z' => 12,  // OIL LOSSES
            'AA' => 11, // LIMIT OL
            'AB' => 11, // PERSEN 4
        ];
    }
}
