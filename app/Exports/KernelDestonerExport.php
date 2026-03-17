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

class KernelDestonerExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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

    public function collection(): Collection
    {
        return collect($this->data);
    }

    // 22 columns  A..V
    public function headings(): array
    {
        return [
            'NO', 'BULAN', 'TANGGAL', 'JAM', 'KODE', 'NAMA SAMPLE',
            'INPUTED BY', 'JENIS', 'OPERATOR', 'SAMPEL BOY',
            'BERAT SAMPEL (g)', 'KONVERSI KG', 'TIME',
            'RASIO JAM/KG',
            'BERAT NUT (g)', 'PERSEN NUT (%)',
            'BERAT KERNEL (g)', 'PERSEN KERNEL (%)',
            'TOTAL LOSSES KERNEL',
            'LOSS KERNEL/JAM',
            'LOSS KERNEL/TBS (%)', 'LIMIT',
        ];
    }

    public function map($row): array
    {
        static $counter = 0;
        $counter++;

        $master = $this->masterData[$row->kode] ?? null;

        $lossVal = (float) ($row->loss_kernel_tbs ?? 0);
        $limOp   = $row->limit_operator ?? null;
        $limV    = $row->limit_value !== null ? (float) $row->limit_value : null;
        $limStr  = $limV !== null ? (($limOp === 'le') ? '<= ' : '> ') . number_format($limV, 4) . '%' : '-';

        return [
            $counter,
            Carbon::parse($row->created_at)->format('F Y'),
            Carbon::parse($row->created_at)->format('d-m-Y'),
            Carbon::parse($row->created_at)->format('H:i:s'),
            $row->kode ?? '-',
            $master ? $master->nama_sample : '-',
            data_get($row, 'user.name', '-'),
            $row->jenis ?? '-',
            $row->operator ?? '-',
            $row->sampel_boy ?? '-',
            (float) ($row->berat_sampel ?? 0),
            (float) ($row->konversi_kg ?? 0),
            $row->time ?? '-',
            (float) ($row->rasio_jam_kg ?? 0),
            (float) ($row->berat_nut ?? 0),
            (float) ($row->persen_nut ?? 0),
            (float) ($row->berat_kernel ?? 0),
            (float) ($row->persen_kernel ?? 0),
            (float) ($row->total_losses_kernel ?? 0),
            (float) ($row->loss_kernel_jam ?? 0),
            round($lossVal, 8),
            $limStr,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->insertNewRowBefore(1, 3);

        $sheet->setCellValue('A1', 'LAPORAN DATA DESTONER');
        $sheet->mergeCells('A1:V1');

        $filterInfo = 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y')
            . ' s/d ' . Carbon::parse($this->endDate)->format('d-m-Y');
        if ($this->kode) {
            $filterInfo .= ' | Kode: ' . $this->kode;
        }
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:V2');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A4:V4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        $sheet->getStyle('A4:V' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        ]);
        $sheet->getStyle('K5:V' . $lastRow)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);

        // Loss Kernel/TBS = column U (21), limit = V (22)
        for ($r = 5; $r <= $lastRow; $r++) {
            $loss    = $sheet->getCell('U' . $r)->getValue();
            $limCell = $sheet->getCell('V' . $r)->getValue();
            if (is_numeric($loss) && $limCell !== '-') {
                preg_match('/([<>]=?)\s*([\d.]+)/', (string) $limCell, $m);
                if (count($m) === 3) {
                    $op  = trim($m[1]);
                    $lim = (float) $m[2];
                    // For destoner losses: "le" (<=) means IN limit (good = green)
                    $ok  = ($op === '<=' || $op === '=<') ? $loss <= $lim : $loss > $lim;
                    $sheet->getStyle('U' . $r)->applyFromArray($ok ? [
                        'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                    ] : [
                        'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']],
                    ]);
                }
            }
        }

        return [];
    }
}
