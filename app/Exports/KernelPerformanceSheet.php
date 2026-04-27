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
    protected $individualRecords;
    protected $operators;
    protected $reportDates;
    protected $operatorDailyPerformance;
    protected $startDate;
    protected $endDate;
    protected $office;

    public function __construct($individualRecords, $operators, $reportDates, $operatorDailyPerformance, $startDate, $endDate, $office)
    {
        $this->individualRecords       = $individualRecords;
        $this->operators               = $operators;
        $this->reportDates             = $reportDates;
        $this->operatorDailyPerformance = $operatorDailyPerformance;
        $this->startDate               = $startDate;
        $this->endDate                 = $endDate;
        $this->office                  = $office;
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
        $rows[] = ['Periode: ' . Carbon::parse($this->startDate)->format('d M Y') . ' - ' . Carbon::parse($this->endDate)->format('d M Y')];
        $rows[] = ['Office: ' . strtoupper((string) $this->office)];
        $rows[] = [''];

        // Performance detail table header (same order as web)
        $rows[] = [
            'Tanggal',
            'Jam',
            'Nama Operator',
            'Sample Boy',
            'Jenis Sampel',
            'Nilai Parameter',
            'Nilai Performance',
        ];

        foreach ($this->individualRecords as $record) {
            $rows[] = [
                Carbon::parse($record['date'])->format('d-M'),
                (string) ($record['time'] ?? '-'),
                (string) ($record['operator'] ?: '-'),
                (string) ($record['sampel_boy'] ?: '-'),
                (string) ($record['nama_sample'] ?? '-'),
                isset($record['nilai_parameter']) ? (float) $record['nilai_parameter'] : '-',
                $record['bobot'] === null ? '-' : (float) $record['bobot'],
            ];
        }

        // Add blank rows before operator table
        $rows[] = [''];
        $rows[] = ['DAFTAR OPERATOR & PERFORMANCE'];
        $rows[] = [''];

        // Header for operator table (same order as web)
        $operatorHeader = ['OPERATOR'];
        foreach ($this->reportDates as $date) {
            $operatorHeader[] = Carbon::parse($date)->format('d M');
        }
        $operatorHeader[] = 'RATA-RATA';
        $rows[] = $operatorHeader;

        foreach ($this->operators as $operator) {
            $row = [$operator];
            $grandSum = 0;
            $grandCount = 0;

            foreach ($this->reportDates as $date) {
                $stats = $this->operatorDailyPerformance[$operator][$date] ?? null;

                if ($stats && ($stats['count'] ?? 0) > 0) {
                    $avg = round(((float) $stats['sum']) / ((int) $stats['count']), 1);
                    $row[] = $avg;
                    $grandSum += (float) $stats['sum'];
                    $grandCount += (int) $stats['count'];
                } else {
                    $row[] = '-';
                }
            }

            $row[] = $grandCount > 0 ? round($grandSum / $grandCount, 2) : '-';
            $rows[] = $row;
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $detailLastColumn = 'G';
        $operatorLastColumn = $this->getColumnLetter(count($this->reportDates) + 2);
        $sheetLastColumn = $this->getColumnLetter(max(7, count($this->reportDates) + 2));

        $detailHeaderRow = 5;
        $detailStartRow = 6;
        $detailEndRow = $detailHeaderRow + count($this->individualRecords);

        $operatorTitleRow = $detailEndRow + 2;
        $operatorHeaderRow = $operatorTitleRow + 2;
        $operatorDataStartRow = $operatorHeaderRow + 1;
        $operatorDataEndRow = $operatorHeaderRow + count($this->operators);

        // Title row
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->mergeCells('A1:' . $sheetLastColumn . '1');

        // Periode row
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->mergeCells('A2:' . $sheetLastColumn . '2');

        // Office row
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->mergeCells('A3:' . $sheetLastColumn . '3');

        // Detail table header
        $sheet->getStyle('A' . $detailHeaderRow . ':' . $detailLastColumn . $detailHeaderRow)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        // Borders for detail table
        if ($detailEndRow >= $detailHeaderRow) {
            $sheet->getStyle('A' . $detailHeaderRow . ':' . $detailLastColumn . $detailEndRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);
        }

        // Alignments and number formats for detail table
        if ($detailEndRow >= $detailStartRow) {
            $sheet->getStyle('A' . $detailStartRow . ':B' . $detailEndRow)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $detailStartRow . ':E' . $detailEndRow)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('F' . $detailStartRow . ':G' . $detailEndRow)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle('F' . $detailStartRow . ':F' . $detailEndRow)
                ->getNumberFormat()->setFormatCode('0.00');

            // Light background for performance column (as on web)
            $sheet->getStyle('G' . $detailStartRow . ':G' . $detailEndRow)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
            ]);

            for ($row = $detailStartRow; $row <= $detailEndRow; $row++) {
                $value = $sheet->getCell('G' . $row)->getValue();
                if (is_numeric($value)) {
                    $this->applyBobotColor($sheet, 'G' . $row, (float) $value);
                } else {
                    $sheet->getStyle('G' . $row)->applyFromArray([
                        'font' => ['color' => ['rgb' => '6B7280'], 'bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                }
            }
        }

        // Freeze pane below detail table header
        $sheet->freezePane('A' . $detailStartRow);

        // Operator table title
        $sheet->getStyle('A' . $operatorTitleRow)->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->mergeCells('A' . $operatorTitleRow . ':' . $operatorLastColumn . $operatorTitleRow);

        // Operator table header
        $sheet->getStyle('A' . $operatorHeaderRow . ':' . $operatorLastColumn . $operatorHeaderRow)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        // Operator table data styling
        if ($operatorDataEndRow >= $operatorDataStartRow) {
            $sheet->getStyle('A' . $operatorDataStartRow . ':' . $operatorLastColumn . $operatorDataEndRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);

            $sheet->getStyle('A' . $operatorDataStartRow . ':A' . $operatorDataEndRow)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            if ($operatorLastColumn !== 'A') {
                $sheet->getStyle('B' . $operatorDataStartRow . ':' . $operatorLastColumn . $operatorDataEndRow)
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            for ($row = $operatorDataStartRow; $row <= $operatorDataEndRow; $row++) {
                for ($col = 2; $col <= count($this->reportDates) + 2; $col++) {
                    $cell = $this->getColumnLetter($col) . $row;
                    $value = $sheet->getCell($cell)->getValue();

                    if (is_numeric($value)) {
                        $this->applyBobotColor($sheet, $cell, (float) $value);
                    } else {
                        $sheet->getStyle($cell)->applyFromArray([
                            'font' => ['color' => ['rgb' => '9CA3AF']],
                        ]);
                    }
                }
            }
        }

        $sheet->getStyle('A1:' . $sheetLastColumn . max($detailEndRow, $operatorDataEndRow))
            ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return $sheet;
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 14,
            'B' => 10,
            'C' => 22,
            'D' => 20,
            'E' => 24,
            'F' => 16,
            'G' => 16,
        ];

        $totalCols = max(7, count($this->reportDates) + 2);
        for ($i = 8; $i <= $totalCols; $i++) {
            $widths[$this->getColumnLetter($i)] = 11;
        }

        return $widths;
    }

    private function applyBobotColor(Worksheet $sheet, string $cell, float $bobot): void
    {
        if ($bobot >= 90) {
            $fontColor = '166534';
            $bgColor   = 'DCFCE7';
        } elseif ($bobot >= 70) {
            $fontColor = '854D0E';
            $bgColor   = 'FEF9C3';
        } else {
            $fontColor = '991B1B';
            $bgColor   = 'FEE2E2';
        }

        $sheet->getStyle($cell)->applyFromArray([
            'font' => ['color' => ['rgb' => $fontColor], 'bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
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
