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

class KernelRekapExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $dataByDate;
    protected $allKodes;
    protected $startDate;
    protected $endDate;
    protected $columnGroups;
    protected $flatColumns;

    public function __construct(array $dataByDate, $allKodes, string $startDate, string $endDate, array $columnGroups = [])
    {
        $this->dataByDate = $dataByDate;
        $this->allKodes = $allKodes;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->columnGroups = $columnGroups;

        $this->flatColumns = [];
        foreach ($columnGroups as $group) {
            foreach ($group['columns'] as $col) {
                $this->flatColumns[] = $col;
            }
        }
    }

    public function collection(): Collection
    {
        $rows = [];
        $columns = !empty($this->flatColumns) ? $this->flatColumns : collect($this->allKodes)->map(fn($k) => ['kode' => $k['kode']])->toArray();

        foreach ($this->dataByDate as $date => $kodeData) {
            $row = [Carbon::parse($date)->format('d/m/Y')];
            foreach ($columns as $col) {
                $kode = $col['kode'];
                $row[] = isset($kodeData[$kode]) ? round($kodeData[$kode]['avg_losses'] * 100, 4) : null;
            }
            $rows[] = $row;
        }

        return collect($rows);
    }

    public function headings(): array
    {
        $headers = ['TANGGAL'];

        if (!empty($this->flatColumns)) {
            foreach ($this->flatColumns as $col) {
                $headers[] = $col['label'];
            }
        } else {
            foreach ($this->allKodes as $kodeInfo) {
                $headers[] = $kodeInfo['kode'] . ' - ' . $kodeInfo['nama_sample'];
            }
        }

        return $headers;
    }

    public function styles(Worksheet $sheet)
    {
        $totalCols = (!empty($this->flatColumns) ? count($this->flatColumns) : count($this->allKodes)) + 1;
        $lastColumn = $this->getColumnLetter($totalCols);
        $hasGroups = !empty($this->columnGroups);
        $insertRows = $hasGroups ? 4 : 3;

        $sheet->insertNewRowBefore(1, $insertRows);

        $sheet->setCellValue('A1', 'REKAP RATA-RATA KERNEL LOSSES (%)');
        $sheet->mergeCells('A1:' . $lastColumn . '1');

        $sheet->setCellValue(
            'A2',
            'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y') .
            ' s/d ' . Carbon::parse($this->endDate)->format('d-m-Y')
        );
        $sheet->mergeCells('A2:' . $lastColumn . '2');

        // Title styling
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Period info styling
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $groupColors = [
            'KERNEL LOSSES' => 'F59E0B',
            '%Dirty' => 'FB923C',
            '%Moist' => '60A5FA',
            'BN / TN' => '4ADE80',
            'Efficiency' => 'A78BFA',
            'DESTONER 1' => 'F472B6',
            'DESTONER 2' => 'FB7185',
        ];

        if ($hasGroups) {
            $groupHeaderRow = $insertRows;
            $subHeaderRow = $insertRows + 1;
            $dataStartRow = $insertRows + 2;

            // Merge Tanggal across both header rows
            $sheet->setCellValue('A' . $groupHeaderRow, 'TANGGAL');
            $sheet->mergeCells('A' . $groupHeaderRow . ':A' . $subHeaderRow);
            $sheet->getStyle('A' . $groupHeaderRow . ':A' . $subHeaderRow)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
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

            // Write group headers with merged cells
            $colOffset = 2;
            foreach ($this->columnGroups as $group) {
                $colCount = count($group['columns']);
                $startCol = $this->getColumnLetter($colOffset);
                $endCol = $this->getColumnLetter($colOffset + $colCount - 1);

                $sheet->setCellValue($startCol . $groupHeaderRow, $group['name']);
                if ($colCount > 1) {
                    $sheet->mergeCells($startCol . $groupHeaderRow . ':' . $endCol . $groupHeaderRow);
                }

                $bgColor = $groupColors[$group['name']] ?? '4F46E5';
                $sheet->getStyle($startCol . $groupHeaderRow . ':' . $endCol . $groupHeaderRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $bgColor],
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

                $colOffset += $colCount;
            }
            $sheet->getRowDimension($groupHeaderRow)->setRowHeight(25);

            // Sub-header row styling
            $sheet->getStyle('B' . $subHeaderRow . ':' . $lastColumn . $subHeaderRow)->applyFromArray([
                'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
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
            $sheet->getRowDimension($subHeaderRow)->setRowHeight(35);
        } else {
            $dataStartRow = 5;

            $sheet->getStyle('A4:' . $lastColumn . '4')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
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
            $sheet->getRowDimension(4)->setRowHeight(30);
        }

        $lastRow = $sheet->getHighestRow();

        // Borders for all data cells
        if ($lastRow >= $dataStartRow) {
            $sheet->getStyle('A' . $dataStartRow . ':' . $lastColumn . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);

            $sheet->getStyle('B' . $dataStartRow . ':' . $lastColumn . $lastRow)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
        }

        // Conditional coloring
        $columns = !empty($this->flatColumns) ? $this->flatColumns : collect($this->allKodes)->map(fn($k) => ['kode' => $k['kode']])->toArray();

        $rowNum = $dataStartRow;
        foreach ($this->dataByDate as $date => $kodeData) {
            $colNum = 2;
            foreach ($columns as $col) {
                $kode = $col['kode'];
                if (isset($kodeData[$kode])) {
                    $pct = $kodeData[$kode]['avg_losses'] * 100;
                    $limOp = $kodeData[$kode]['limit_operator'];
                    $limVal = $kodeData[$kode]['limit_value'];

                    if ($limVal !== null) {
                        $isGood = match ($limOp) {
                            'lt' => $pct < $limVal,
                            'ge' => $pct >= $limVal,
                            'gt' => $pct > $limVal,
                            default => $pct <= $limVal,
                        };
                        $cell = $this->getColumnLetter($colNum) . $rowNum;

                        if ($isGood) {
                            $sheet->getStyle($cell)->applyFromArray([
                                'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                            ]);
                        } else {
                            $sheet->getStyle($cell)->applyFromArray([
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

        // Number format: 4 decimal places
        $dataEndRow = $dataStartRow - 1 + count($this->dataByDate);
        if ($dataEndRow >= $dataStartRow) {
            $sheet->getStyle('B' . $dataStartRow . ':' . $lastColumn . $dataEndRow)
                ->getNumberFormat()
                ->setFormatCode('0.0000');
        }

        $sheet->freezePane('B' . $dataStartRow);

        return $sheet;
    }

    public function columnWidths(): array
    {
        $widths = ['A' => 12];
        $count = !empty($this->flatColumns) ? count($this->flatColumns) : count($this->allKodes);
        for ($i = 2; $i <= $count + 1; $i++) {
            $widths[$this->getColumnLetter($i)] = 16;
        }
        return $widths;
    }

    private function getColumnLetter(int $n): string
    {
        $letter = '';
        while ($n > 0) {
            $rem = ($n - 1) % 26;
            $letter = chr(65 + $rem) . $letter;
            $n = (int) (($n - 1) / 26);
        }
        return $letter;
    }
}
