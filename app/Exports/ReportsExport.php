<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Conditional;

class ReportsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths, ShouldQueue, WithCustomChunkSize
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

    public function query(): Builder
    {
        return $this->query;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function headings(): array
    {
        return [
            'NO', 'BULAN', 'TANGGAL', 'JAM', 'TGL & JAM AKHIR INPUT', 'TANGGAL SAMPEL',
            'KODE', 'INPUTED BY', 'NAMA PIVOT', 'OPERATOR', 'SAMPEL BOY', 'JENIS OLAH',
            'CAWAN KOSONG', 'BERAT SAMPEL BASAH', 'CAWAN + BASAH', 'CAWAN + KERING',
            'SETELAH OVEN', 'LABU KOSONG', 'OIL + LABU', 'MINYAK', 'MOIST (%)',
            'DM/WM (%)', 'OLWB (%)', 'LIMIT (%)', 'OLDB (%)', 'LIMIT (%)',
            'OIL LOSSES', 'LIMIT', 'PERSEN', 'PERSEN 4',
        ];
    }

    public function map($row): array
    {
        static $counter = 0;
        $counter++;

        $created_ts = strtotime($row->created_at);
        $updated_ts = $row->updated_at ? strtotime($row->updated_at) : null;
        $sampel_ts = $row->tanggal_sampel ? strtotime($row->tanggal_sampel) : null;

        return [
            $counter,
            $created_ts ? date('F Y', $created_ts) : '-',
            $created_ts ? date('d-m-Y', $created_ts) : '-',
            $created_ts ? date('H:i:s', $created_ts) : '-',
            $updated_ts ? date('d-m-Y H:i:s', $updated_ts) : '-',
            $sampel_ts ? date('d-m-Y', $sampel_ts) : '-',
            $row->kode ?? '-',
            $row->user_name ?? '-',
            $row->pivot ?? '-',
            $row->operator ?? '-',
            $row->sampel_boy ?? '-',
            $row->jenis ?? '-',
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
            $row->persen !== null ? $row->persen : '-',
            $row->persen4 !== null ? $row->persen4 : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->insertNewRowBefore(1, 3);
        $lastRow = $sheet->getHighestRow();

        $title = 'LAPORAN DATA OIL LOSSES';
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:AD1');

        $filterInfo = 'Periode: ' . date('d-m-Y', strtotime($this->startDate)) .
            ' s/d ' . date('d-m-Y', strtotime($this->endDate));
        if ($this->kode) {
            $filterInfo .= ' | Kode: ' . $this->kode;
        }
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:AD2');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle('A4:AD4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        if ($lastRow >= 5) {
            $sheet->getStyle("M5:T$lastRow")->getNumberFormat()->setFormatCode('0.0000;(0.0000)');
            $sheet->getStyle("U5:AD$lastRow")->getNumberFormat()->setFormatCode('0.00;(0.00)');

            $this->applyConditionalFormatting($sheet, 'W', 'X', $lastRow);
            $this->applyConditionalFormatting($sheet, 'Y', 'Z', $lastRow);
            $this->applyConditionalFormatting($sheet, 'AA', 'AB', $lastRow);
        }

        $sheet->freezePane('M5');

        return $sheet;
    }

    private function applyConditionalFormatting(Worksheet $sheet, string $valCol, string $limCol, int $lastRow)
    {
        $range = "{$valCol}5:{$valCol}{$lastRow}";

        $condGood = new Conditional();
        $condGood->setConditionType(Conditional::CONDITION_EXPRESSION);
        $condGood->addCondition("=AND(ISNUMBER({$valCol}5), ISNUMBER({$limCol}5), {$valCol}5<={$limCol}5)");
        $condGood->getStyle()->getFont()->getColor()->setARGB('FF16A34A');
        $condGood->getStyle()->getFont()->setBold(true);
        $condGood->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');

        $condBad = new Conditional();
        $condBad->setConditionType(Conditional::CONDITION_EXPRESSION);
        $condBad->addCondition("=AND(ISNUMBER({$valCol}5), ISNUMBER({$limCol}5), {$limCol}5>0, {$valCol}5>{$limCol}5)");
        $condBad->getStyle()->getFont()->getColor()->setARGB('FFDC2626');
        $condBad->getStyle()->getFont()->setBold(true);
        $condBad->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');

        $conditionalStyles = $sheet->getStyle($range)->getConditionalStyles();
        $conditionalStyles[] = $condGood;
        $conditionalStyles[] = $condBad;
        $sheet->getStyle($range)->setConditionalStyles($conditionalStyles);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   'B' => 15,  'C' => 12,  'D' => 10,  'E' => 22,  'F' => 13,
            'G' => 12,  'H' => 18,  'I' => 15,  'J' => 15,  'K' => 15,  'L' => 12,
            'M' => 14,  'N' => 13,  'O' => 14,  'P' => 15,  'Q' => 14,  'R' => 13,
            'S' => 12,  'T' => 12,  'U' => 11,  'V' => 11,  'W' => 11,  'X' => 11,
            'Y' => 11,  'Z' => 11,  'AA' => 12, 'AB' => 11, 'AC' => 11, 'AD' => 11,
        ];
    }
}
