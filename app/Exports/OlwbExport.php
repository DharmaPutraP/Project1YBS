<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class OlwbExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $dataByDate;
    protected $allKodes;
    protected $startDate;
    protected $endDate;

    public function __construct($dataByDate, $allKodes, $startDate, $endDate)
    {
        $this->dataByDate = $dataByDate;
        $this->allKodes = $allKodes; // Now contains array of ['kode', 'pivot']
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Return data collection as array
     */
    public function collection()
    {
        $rows = [];

        foreach ($this->dataByDate as $date => $kodeData) {
            $row = [Carbon::parse($date)->format('d/m/Y')];

            foreach ($this->allKodes as $kodeInfo) {
                $kode = $kodeInfo['kode'];
                if (isset($kodeData[$kode])) {
                    $olwb = $kodeData[$kode]['olwb'];
                    // Return raw numbers for Excel to format
                    $row[] = $olwb !== null ? $olwb : '-';
                } else {
                    $row[] = '-';
                }
            }

            $rows[] = $row;
        }

        return collect($rows);
    }

    /**
     * Define column headers
     */
    public function headings(): array
    {
        $headers = ['TANGGAL'];

        foreach ($this->allKodes as $kodeInfo) {
            $headers[] = $kodeInfo['pivot'];
        }

        return $headers;
    }

    /**
     * Apply styles to worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Add title and info at the top
        $sheet->insertNewRowBefore(1, 3);

        $title = 'DATA OLWB (OIL LOSSES WET BASIS)';
        $sheet->setCellValue('A1', $title);
        $lastColumn = $this->getColumnLetter(count($this->allKodes) + 1);
        $sheet->mergeCells('A1:' . $lastColumn . '1');

        $filterInfo = 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y') .
            ' s/d ' . Carbon::parse($this->endDate)->format('d-m-Y');
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:' . $lastColumn . '2');

        // Style title
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style filter info
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Style header row (row 4)
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A4:' . $lastColumn . '4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
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
        $sheet->getStyle('A4:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Align numeric columns to center (all columns except date)
        if ($lastRow > 4) {
            $sheet->getStyle('B5:' . $lastColumn . $lastRow)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
        }

        // Apply conditional formatting for OLWB values
        $dataArray = $this->dataByDate;
        $rowNum = 5; // Start from row 5 (after title, filter, blank, header)

        foreach ($dataArray as $date => $kodeData) {
            $colNum = 2; // Start from column B (column A is date)

            foreach ($this->allKodes as $kode) {
                if (isset($kodeData[$kode])) {
                    $olwb = $kodeData[$kode]['olwb'];
                    $limit = $kodeData[$kode]['limitOLWB'];

                    if ($olwb !== null && $limit > 0) {
                        $cellAddress = $this->getColumnLetter($colNum) . $rowNum;

                        // Determine if value is good based on kode
                        if ($kode == 'COT IN') {
                            $isGood = $olwb > $limit;
                        } else {
                            $isGood = $olwb <= $limit;
                        }

                        if ($isGood) {
                            // Green: Good
                            $sheet->getStyle($cellAddress)->applyFromArray([
                                'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                            ]);
                        } else {
                            // Red: Bad
                            $sheet->getStyle($cellAddress)->applyFromArray([
                                'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true],
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']],
                            ]);
                        }
                    }
                }
                $colNum++;
            }
            $rowNum++;
        }

        // Apply number formatting with negative numbers in parentheses for all data columns
        $dataStartRow = 5;
        $dataEndRow = 4 + count($this->dataByDate);
        $lastCol = $this->getColumnLetter(count($this->allKodes) + 1); // +1 for date column

        if ($dataEndRow >= $dataStartRow) {
            // Apply 2 decimal format to all OLWB columns (B to last column)
            $sheet->getStyle('B' . $dataStartRow . ':' . $lastCol . $dataEndRow)->getNumberFormat()->setFormatCode('0.00;(0.00)');
        }

        // Freeze first column and header row
        $sheet->freezePane('B5');

        return $sheet;
    }

    /**
     * Set column widths
     */
    public function columnWidths(): array
    {
        $widths = ['A' => 12]; // Date column

        $columns = range('B', $this->getColumnLetter(count($this->allKodes) + 1));
        foreach ($columns as $col) {
            $widths[$col] = 11;
        }

        return $widths;
    }

    /**
     * Helper to get Excel column letter from number
     */
    private function getColumnLetter($columnNumber)
    {
        $letter = '';
        while ($columnNumber > 0) {
            $temp = ($columnNumber - 1) % 26;
            $letter = chr($temp + 65) . $letter;
            $columnNumber = ($columnNumber - $temp - 1) / 26;
        }
        return $letter;
    }
}
