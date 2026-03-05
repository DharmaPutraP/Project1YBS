<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class PerformanceSheet implements FromArray, WithStyles, WithTitle, WithColumnWidths, WithStrictNullComparison
{
    protected $reportData;
    protected $allKodesData;
    protected $operatorPress;
    protected $operatorClarification;
    protected $reportDates;
    protected $dailyPerformance;
    protected $startDate;
    protected $endDate;

    public function __construct(
        $reportData,
        $allKodesData,
        $operatorPress,
        $operatorClarification,
        $reportDates,
        $dailyPerformance,
        $startDate,
        $endDate
    ) {
        $this->reportData = $reportData;
        $this->allKodesData = $allKodesData;
        $this->operatorPress = $operatorPress;
        $this->operatorClarification = $operatorClarification;
        $this->reportDates = $reportDates;
        $this->dailyPerformance = $dailyPerformance;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Sheet title
     */
    public function title(): string
    {
        return 'Performance';
    }

    /**
     * Return array data for sheet
     */
    public function array(): array
    {
        $rows = [];

        // Title
        $rows[] = ['LAPORAN PERFORMANCE'];
        $rows[] = ['Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y') . ' s/d ' . Carbon::parse($this->endDate)->format('d-m-Y')];
        $rows[] = ['']; // Blank row

        // ========================================
        // SECTION 1: PERFORMANCE TABLE
        // ========================================

        // Header for Performance Table
        $header = ['TANGGAL'];
        foreach ($this->allKodesData as $kodeInfo) {
            $header[] = $kodeInfo['pivot'];
        }
        $header[] = 'AVG PRESS';
        $header[] = 'AVG CLARIF';
        $rows[] = $header;

        // Data rows for Performance Table - 2 rows per date (OLWB + Bobot)
        foreach ($this->reportData as $dateData) {
            // Row 1: OLWB Values
            $olwbRow = [Carbon::parse($dateData['date'])->format('d/m/Y')];

            foreach ($this->allKodesData as $kodeInfo) {
                $kode = $kodeInfo['kode'];
                $olwb = $dateData['kodes'][$kode]['olwb'] ?? null;
                $olwbRow[] = $olwb !== null ? $olwb : '-';
            }

            // Empty cells for AVG columns in OLWB row
            $olwbRow[] = '';
            $olwbRow[] = '';

            $rows[] = $olwbRow;

            // Row 2: Bobot Scores
            $bobotRow = ['Bobot'];

            foreach ($this->allKodesData as $kodeInfo) {
                $kode = $kodeInfo['kode'];
                $bobot = $dateData['kodes'][$kode]['bobot'] ?? null;
                // Ensure 0 is treated as a valid number, not falsy
                $bobotRow[] = ($bobot !== null && $bobot !== '') ? (float) $bobot : '-';
            }

            // AVG columns in Bobot row
            $bobotRow[] = $dateData['average_press'] !== null ? (float) $dateData['average_press'] : '-';
            $bobotRow[] = $dateData['average_clarification'] !== null ? (float) $dateData['average_clarification'] : '-';

            $rows[] = $bobotRow;
        }

        // Add blank rows before operator table
        $rows[] = [''];
        $rows[] = [''];

        // ========================================
        // SECTION 2: OPERATOR PERFORMANCE TABLE
        // ========================================

        $rows[] = ['DAFTAR OPERATOR & PERFORMANCE'];
        $rows[] = ['']; // Blank row

        // Header for Operator Table
        $operatorHeader = ['OPERATOR'];
        foreach ($this->reportDates as $date) {
            $operatorHeader[] = Carbon::parse($date)->format('d M');
        }
        $rows[] = $operatorHeader;

        // Operator Clarification Section Header
        $clarificationRow = ['OPERATOR CLARIFICATION'];
        for ($i = 1; $i < count($operatorHeader); $i++) {
            $clarificationRow[] = '';
        }
        $rows[] = $clarificationRow;

        // Operator Clarification Data
        foreach ($this->operatorClarification as $operator) {
            $row = [$operator];

            foreach ($this->reportDates as $date) {
                $performance = $this->dailyPerformance[$date]['average_clarification'] ?? null;
                // Return as decimal for percentage format (already in percent, so divide by 100)
                $row[] = $performance !== null ? ($performance / 100) : '-';
            }

            $rows[] = $row;
        }

        // Blank row separator between sections
        $rows[] = [''];

        // Operator Press Section Header
        $pressRow = ['OPERATOR PRESS'];
        for ($i = 1; $i < count($operatorHeader); $i++) {
            $pressRow[] = '';
        }
        $rows[] = $pressRow;

        // Operator Press Data
        foreach ($this->operatorPress as $operator) {
            $row = [$operator];

            foreach ($this->reportDates as $date) {
                $performance = $this->dailyPerformance[$date]['average_press'] ?? null;
                // Return as decimal for percentage format (already in percent, so divide by 100)
                $row[] = $performance !== null ? ($performance / 100) : '-';
            }

            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Apply styles
     */
    public function styles(Worksheet $sheet)
    {
        $lastColumn = $this->getColumnLetter(count($this->allKodesData) + 3); // +3 for date + 2 averages
        $operatorLastColumn = $this->getColumnLetter(count($this->reportDates) + 1); // +1 for operator name

        // Style title (row 1)
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->mergeCells('A1:' . $lastColumn . '1');

        // Style filter info (row 2)
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->mergeCells('A2:' . $lastColumn . '2');

        // ========================================
        // PERFORMANCE TABLE STYLES
        // ========================================

        $performanceHeaderRow = 4;
        $performanceDataRows = count($this->reportData) * 2; // 2 rows per date (OLWB + Bobot)
        $performanceEndRow = $performanceHeaderRow + $performanceDataRows;

        // Style Performance Table Header
        $sheet->getStyle('A' . $performanceHeaderRow . ':' . $lastColumn . $performanceHeaderRow)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        // Borders for Performance Table data
        if ($performanceEndRow > $performanceHeaderRow) {
            $sheet->getStyle('A' . $performanceHeaderRow . ':' . $lastColumn . $performanceEndRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);
        }

        // Apply conditional formatting for bobot scores in Performance Table
        $rowNum = $performanceHeaderRow + 1;
        foreach ($this->reportData as $dateData) {
            // Style OLWB row (date row)
            $sheet->getStyle('A' . $rowNum)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFFF']],
            ]);
            $rowNum++;

            // Style Bobot row and apply conditional colors
            $sheet->getStyle('A' . $rowNum)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '4F46E5']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
            ]);

            $colNum = 2; // Start from column B (A is "Bobot" label)

            foreach ($this->allKodesData as $kodeInfo) {
                $kode = $kodeInfo['kode'];
                $bobot = $dateData['kodes'][$kode]['bobot'] ?? null;

                if ($bobot !== null) {
                    $cellAddress = $this->getColumnLetter($colNum) . $rowNum;

                    // Color based on score
                    if ($bobot >= 90) {
                        // Green: Excellent
                        $sheet->getStyle($cellAddress)->applyFromArray([
                            'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                        ]);
                    } elseif ($bobot >= 70) {
                        // Yellow: Good
                        $sheet->getStyle($cellAddress)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'ca8a04'], 'bold' => true],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fef9c3']],
                        ]);
                    } else {
                        // Red: Poor
                        $sheet->getStyle($cellAddress)->applyFromArray([
                            'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']],
                        ]);
                    }
                }
                $colNum++;
            }

            // Apply colors to average columns (in Bobot row)
            $avgPressCol = $this->getColumnLetter(count($this->allKodesData) + 2);
            $avgClarifCol = $this->getColumnLetter(count($this->allKodesData) + 3);

            if ($dateData['average_press'] !== null) {
                $avgPress = $dateData['average_press'];
                $cellAddress = $avgPressCol . $rowNum;

                if ($avgPress >= 90) {
                    $sheet->getStyle($cellAddress)->applyFromArray([
                        'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                    ]);
                } elseif ($avgPress >= 70) {
                    $sheet->getStyle($cellAddress)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'ca8a04'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fef9c3']],
                    ]);
                } else {
                    $sheet->getStyle($cellAddress)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']],
                    ]);
                }
            }

            if ($dateData['average_clarification'] !== null) {
                $avgClarif = $dateData['average_clarification'];
                $cellAddress = $avgClarifCol . $rowNum;

                if ($avgClarif >= 90) {
                    $sheet->getStyle($cellAddress)->applyFromArray([
                        'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                    ]);
                } elseif ($avgClarif >= 70) {
                    $sheet->getStyle($cellAddress)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'ca8a04'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fef9c3']],
                    ]);
                } else {
                    $sheet->getStyle($cellAddress)->applyFromArray([
                        'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']],
                    ]);
                }
            }

            $rowNum++;
        }

        // ========================================
        // OPERATOR TABLE STYLES
        // ========================================

        $operatorTitleRow = $performanceEndRow + 3;
        $operatorHeaderRow = $operatorTitleRow + 2;
        $clarificationHeaderRow = $operatorHeaderRow + 1;
        $clarificationDataStartRow = $clarificationHeaderRow + 1;
        $clarificationEndRow = $clarificationHeaderRow + count($this->operatorClarification);
        $pressHeaderRow = $clarificationEndRow + 2; // +2 for blank row separator
        $pressDataStartRow = $pressHeaderRow + 1;
        $pressEndRow = $pressHeaderRow + count($this->operatorPress);

        // Style Operator Table Title
        $sheet->getStyle('A' . $operatorTitleRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->mergeCells('A' . $operatorTitleRow . ':' . $operatorLastColumn . $operatorTitleRow);

        // Style Operator Table Header
        $sheet->getStyle('A' . $operatorHeaderRow . ':' . $operatorLastColumn . $operatorHeaderRow)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6b7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        // Style Clarification Section Header
        $sheet->getStyle('A' . $clarificationHeaderRow . ':' . $operatorLastColumn . $clarificationHeaderRow)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '16a34a']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        $sheet->mergeCells('A' . $clarificationHeaderRow . ':' . $operatorLastColumn . $clarificationHeaderRow);

        // Borders for Clarification data
        if ($clarificationEndRow > $clarificationHeaderRow) {
            $sheet->getStyle('A' . $clarificationDataStartRow . ':' . $operatorLastColumn . $clarificationEndRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);
        }

        // Style Press Section Header
        $sheet->getStyle('A' . $pressHeaderRow . ':' . $operatorLastColumn . $pressHeaderRow)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3b82f6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        $sheet->mergeCells('A' . $pressHeaderRow . ':' . $operatorLastColumn . $pressHeaderRow);

        // Borders for Press data
        if ($pressEndRow > $pressHeaderRow) {
            $sheet->getStyle('A' . $pressDataStartRow . ':' . $operatorLastColumn . $pressEndRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);
        }

        // ========================================
        // NUMBER FORMATTING
        // ========================================

        // Format OLWB and Bobot rows differently
        $rowNum = $performanceHeaderRow + 1;
        $bobotEndCol = $this->getColumnLetter(count($this->allKodesData) + 1);
        $avgPressCol = $this->getColumnLetter(count($this->allKodesData) + 2);
        $avgClarifCol = $this->getColumnLetter(count($this->allKodesData) + 3);

        foreach ($this->reportData as $dateData) {
            // OLWB row - 2 decimals with parentheses
            $sheet->getStyle('B' . $rowNum . ':' . $bobotEndCol . $rowNum)
                ->getNumberFormat()->setFormatCode('0.00;(0.00);0.00');
            $rowNum++;

            // Bobot row - 1 decimal with parentheses (explicit format for zero)
            $sheet->getStyle('B' . $rowNum . ':' . $bobotEndCol . $rowNum)
                ->getNumberFormat()->setFormatCode('0.0;(0.0);0.0');

            // AVG columns in Bobot row - 1 decimal with parentheses (explicit format for zero)
            $sheet->getStyle($avgPressCol . $rowNum . ':' . $avgClarifCol . $rowNum)
                ->getNumberFormat()->setFormatCode('0.0;(0.0);0.0');

            $rowNum++;
        }

        // Format operator performance as percentage with 1 decimal
        if ($clarificationEndRow > $clarificationHeaderRow) {
            $sheet->getStyle('B' . $clarificationDataStartRow . ':' . $operatorLastColumn . $clarificationEndRow)
                ->getNumberFormat()->setFormatCode('0.0%;(0.0%);0.0%');
        }

        if ($pressEndRow > $pressHeaderRow) {
            $sheet->getStyle('B' . $pressDataStartRow . ':' . $operatorLastColumn . $pressEndRow)
                ->getNumberFormat()->setFormatCode('0.0%;(0.0%);0.0%');
        }

        // Freeze panes
        $sheet->freezePane('A' . ($performanceHeaderRow + 1));

        return $sheet;
    }

    /**
     * Set column widths
     */
    public function columnWidths(): array
    {
        $widths = ['A' => 15]; // Date/Operator column

        $columns = range('B', $this->getColumnLetter(max(count($this->allKodesData) + 3, count($this->reportDates) + 1)));
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
