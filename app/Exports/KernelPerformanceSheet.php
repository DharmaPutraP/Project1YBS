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

class KernelPerformanceSheet implements FromArray, WithStyles, WithTitle, WithColumnWidths, WithStrictNullComparison
{
    protected $reportData;
    protected $allKodesData;
    protected $operators;
    protected $reportDates;
    protected $dailyPerformance;
    protected $startDate;
    protected $endDate;

    public function __construct($reportData, $allKodesData, $operators, $reportDates, $dailyPerformance, $startDate, $endDate)
    {
        $this->reportData       = $reportData;
        $this->allKodesData     = $allKodesData;
        $this->operators        = $operators;
        $this->reportDates      = $reportDates;
        $this->dailyPerformance = $dailyPerformance;
        $this->startDate        = $startDate;
        $this->endDate          = $endDate;
    }

    public function title(): string
    {
        return 'Performance Kernel';
    }

    public function array(): array
    {
        $rows = [];

        // Title
        $rows[] = ['LAPORAN PERFORMANCE KERNEL LOSSES'];
        $rows[] = ['Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y') . ' s/d ' . Carbon::parse($this->endDate)->format('d-m-Y')];
        $rows[] = [''];

        // Header
        $header = ['TANGGAL'];
        foreach ($this->allKodesData as $kodeInfo) {
            $header[] = $kodeInfo['kode'];
        }
        $header[] = 'AVG BOBOT';
        $rows[] = $header;

        // Data rows — 2 rows per date (Kernel Losses + Bobot)
        foreach ($this->reportData as $dateData) {
            // Row 1: Kernel Losses %
            $lossesRow = [Carbon::parse($dateData['date'])->format('d/m/Y')];
            foreach ($this->allKodesData as $kodeInfo) {
                $kode = $kodeInfo['kode'];
                $kl   = $dateData['kodes'][$kode]['kernel_losses'] ?? null;
                $lossesRow[] = $kl !== null ? round($kl, 2) : '-';
            }
            $lossesRow[] = '';
            $rows[] = $lossesRow;

            // Row 2: Bobot Scores
            $bobotRow = ['Bobot'];
            foreach ($this->allKodesData as $kodeInfo) {
                $kode  = $kodeInfo['kode'];
                $bobot = $dateData['kodes'][$kode]['bobot'] ?? null;
                $bobotRow[] = ($bobot !== null && $bobot !== '') ? (float) $bobot : '-';
            }
            $bobotRow[] = $dateData['average_bobot'] !== null ? (float) $dateData['average_bobot'] : '-';
            $rows[] = $bobotRow;
        }

        // Add blank rows before operator table
        $rows[] = [''];
        $rows[] = [''];

        // ========================================
        // SECTION 2: OPERATOR PERFORMANCE TABLE
        // ========================================

        $rows[] = ['DAFTAR OPERATOR & PERFORMANCE'];
        $rows[] = [''];

        // Header for Operator Table
        $operatorHeader = ['OPERATOR'];
        foreach ($this->reportDates as $date) {
            $operatorHeader[] = Carbon::parse($date)->format('d M');
        }
        $rows[] = $operatorHeader;

        // Operator Section Header
        $sectionRow = ['OPERATOR KERNEL'];
        for ($i = 1; $i < count($operatorHeader); $i++) {
            $sectionRow[] = '';
        }
        $rows[] = $sectionRow;

        // Operator Data
        foreach ($this->operators as $operator) {
            $row = [$operator];
            foreach ($this->reportDates as $date) {
                $performance = $this->dailyPerformance[$date]['average_bobot'] ?? null;
                $row[] = $performance !== null ? ($performance / 100) : '-';
            }
            $rows[] = $row;
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $kodeCount  = count($this->allKodesData);
        $lastColumn = $this->getColumnLetter($kodeCount + 2); // +2 for date + AVG BOBOT

        // Title row
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->mergeCells('A1:' . $lastColumn . '1');

        // Periode row
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->mergeCells('A2:' . $lastColumn . '2');

        // Header row (row 4)
        $headerRow = 4;
        $sheet->getStyle('A' . $headerRow . ':' . $lastColumn . $headerRow)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        // Data rows styling + conditional coloring
        $dataRows  = count($this->reportData) * 2;
        $endRow    = $headerRow + $dataRows;

        if ($endRow > $headerRow) {
            $sheet->getStyle('A' . $headerRow . ':' . $lastColumn . $endRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);
        }

        $rowNum = $headerRow + 1;
        $bobotEndCol = $this->getColumnLetter($kodeCount + 1);
        $avgCol      = $this->getColumnLetter($kodeCount + 2);

        foreach ($this->reportData as $dateData) {
            // OLWB (losses) row — date label bold
            $sheet->getStyle('A' . $rowNum)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFFF']],
            ]);
            // Number format for losses row
            $sheet->getStyle('B' . $rowNum . ':' . $bobotEndCol . $rowNum)
                ->getNumberFormat()->setFormatCode('0.00;(0.00);0.00');
            $rowNum++;

            // Bobot row — label style
            $sheet->getStyle('A' . $rowNum)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '4F46E5']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
            ]);

            // Color each bobot cell
            $colNum = 2;
            foreach ($this->allKodesData as $kodeInfo) {
                $kode  = $kodeInfo['kode'];
                $bobot = $dateData['kodes'][$kode]['bobot'] ?? null;

                if ($bobot !== null) {
                    $cellAddress = $this->getColumnLetter($colNum) . $rowNum;
                    $this->applyBobotColor($sheet, $cellAddress, $bobot);
                }
                $colNum++;
            }

            // Color AVG BOBOT cell
            if ($dateData['average_bobot'] !== null) {
                $this->applyBobotColor($sheet, $avgCol . $rowNum, $dateData['average_bobot']);
            }

            // Number format for bobot row
            $sheet->getStyle('B' . $rowNum . ':' . $avgCol . $rowNum)
                ->getNumberFormat()->setFormatCode('0.0;(0.0);0.0');

            $rowNum++;
        }

        // Freeze pane below header
        $sheet->freezePane('A' . ($headerRow + 1));

        // ========================================
        // OPERATOR TABLE STYLES
        // ========================================

        $operatorLastColumn = $this->getColumnLetter(count($this->reportDates) + 1);

        $operatorTitleRow      = $endRow + 3;
        $operatorHeaderRow     = $operatorTitleRow + 2;
        $sectionHeaderRow      = $operatorHeaderRow + 1;
        $operatorDataStartRow  = $sectionHeaderRow + 1;
        $operatorEndRow        = $sectionHeaderRow + count($this->operators);

        // Style Operator Table Title
        $sheet->getStyle('A' . $operatorTitleRow)->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->mergeCells('A' . $operatorTitleRow . ':' . $operatorLastColumn . $operatorTitleRow);

        // Style Operator Table Header
        $sheet->getStyle('A' . $operatorHeaderRow . ':' . $operatorLastColumn . $operatorHeaderRow)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6b7280']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        // Style Section Header
        $sheet->getStyle('A' . $sectionHeaderRow . ':' . $operatorLastColumn . $sectionHeaderRow)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        $sheet->mergeCells('A' . $sectionHeaderRow . ':' . $operatorLastColumn . $sectionHeaderRow);

        // Borders + percentage format for operator data
        if ($operatorEndRow > $sectionHeaderRow) {
            $sheet->getStyle('A' . $operatorDataStartRow . ':' . $operatorLastColumn . $operatorEndRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);
            $sheet->getStyle('B' . $operatorDataStartRow . ':' . $operatorLastColumn . $operatorEndRow)
                ->getNumberFormat()->setFormatCode('0.0%;(0.0%);0.0%');
        }

        return $sheet;
    }

    public function columnWidths(): array
    {
        $widths = ['A' => 15];
        $totalCols = max(count($this->allKodesData) + 2, count($this->reportDates) + 1);

        for ($i = 2; $i <= $totalCols; $i++) {
            $widths[$this->getColumnLetter($i)] = 11;
        }

        return $widths;
    }

    private function applyBobotColor(Worksheet $sheet, string $cell, float $bobot): void
    {
        if ($bobot >= 90) {
            $fontColor = '16a34a';
            $bgColor   = 'dcfce7';
        } elseif ($bobot >= 70) {
            $fontColor = 'ca8a04';
            $bgColor   = 'fef9c3';
        } else {
            $fontColor = 'dc2626';
            $bgColor   = 'fee2e2';
        }

        $sheet->getStyle($cell)->applyFromArray([
            'font' => ['color' => ['rgb' => $fontColor], 'bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
        ]);
    }

    private function getColumnLetter(int $columnNumber): string
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
