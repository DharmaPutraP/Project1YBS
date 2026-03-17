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

class KernelQwtExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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

    // Total 27 columns  A..AA
    public function headings(): array
    {
        return [
            'NO', 'BULAN', 'TANGGAL', 'JAM', 'KODE', 'NAMA SAMPLE',
            'INPUTED BY', 'JENIS', 'OPERATOR', 'SAMPEL BOY',
            'SAMPEL SETELAH KUARTER',
            'BERAT NUT UTUH (g)', 'BERAT NUT PECAH (g)',
            'BERAT KERNEL UTUH (g)', 'BERAT KERNEL PECAH (g)',
            'BERAT CANGKANG (g)', 'BERAT BATU (g)', 'BERAT FIBER (g)', 'BERAT BROKEN NUT (g)',
            'TOTAL BERAT NUT (g)',
            'BN/TN (%)', 'LIMIT BN/TN',
            'MOISTURE (%)', 'LIMIT MOISTURE',
            'AMPERE SCREW', 'TEKANAN HYDRAULIC', 'KECEPATAN SCREW',
        ];
    }

    public function map($row): array
    {
        static $counter = 0;
        $counter++;

        $master = $this->masterData[$row->kode] ?? null;

        $bntnVal   = (float) ($row->bn_tn ?? 0);
        $moistVal  = (float) ($row->moisture ?? 0);
        $bntnLimOp = $row->bn_tn_limit_operator ?? null;
        $bntnLimV  = $row->bn_tn_limit_value !== null ? (float) $row->bn_tn_limit_value : null;
        $moistLimOp = $row->moist_limit_operator ?? null;
        $moistLimV  = $row->moist_limit_value !== null ? (float) $row->moist_limit_value : null;

        $bntnLimit  = $bntnLimV  !== null ? (($bntnLimOp  === 'le') ? '<= ' : '> ') . number_format($bntnLimV, 4)  . '%' : '-';
        $moistLimit = $moistLimV !== null ? (($moistLimOp === 'le') ? '<= ' : '> ') . number_format($moistLimV, 4) . '%' : '-';

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
            (float) ($row->sampel_setelah_kuarter ?? 0),
            (float) ($row->berat_nut_utuh ?? 0),
            (float) ($row->berat_nut_pecah ?? 0),
            (float) ($row->berat_kernel_utuh ?? 0),
            (float) ($row->berat_kernel_pecah ?? 0),
            (float) ($row->berat_cangkang ?? 0),
            (float) ($row->berat_batu ?? 0),
            (float) ($row->berat_fiber ?? 0),
            (float) ($row->berat_broken_nut ?? 0),
            (float) ($row->total_berat_nut ?? 0),
            round($bntnVal, 4),
            $bntnLimit,
            round($moistVal, 4),
            $moistLimit,
            (float) ($row->ampere_screw ?? 0),
            (float) ($row->tekanan_hydraulic ?? 0),
            (float) ($row->kecepatan_screw ?? 0),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->insertNewRowBefore(1, 3);

        $sheet->setCellValue('A1', 'LAPORAN DATA QWT FIBRE PRESS');
        $sheet->mergeCells('A1:AA1');

        $filterInfo = 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y')
            . ' s/d ' . Carbon::parse($this->endDate)->format('d-m-Y');
        if ($this->kode) {
            $filterInfo .= ' | Kode: ' . $this->kode;
        }
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:AA2');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A4:AA4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        $sheet->getStyle('A4:AA' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        ]);
        $sheet->getStyle('K5:AA' . $lastRow)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);

        // BN/TN = column U (21), limit = V (22)
        // Moisture = column W (23), limit = X (24)
        for ($r = 5; $r <= $lastRow; $r++) {
            $bntn    = $sheet->getCell('U' . $r)->getValue();
            $bntnLim = $sheet->getCell('V' . $r)->getValue();
            if (is_numeric($bntn) && $bntnLim !== '-') {
                preg_match('/([<>]=?)\s*([\d.]+)/', (string) $bntnLim, $m);
                if (count($m) === 3) {
                    $op  = trim($m[1]);
                    $lim = (float) $m[2];
                    $ok  = ($op === '<=' || $op === '=<') ? $bntn <= $lim : $bntn > $lim;
                    $sheet->getStyle('U' . $r)->applyFromArray($ok ? [
                        'font' => ['color' => ['rgb' => '16a34a'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dcfce7']],
                    ] : [
                        'font' => ['color' => ['rgb' => 'dc2626'], 'bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fee2e2']],
                    ]);
                }
            }

            $moist    = $sheet->getCell('W' . $r)->getValue();
            $moistLim = $sheet->getCell('X' . $r)->getValue();
            if (is_numeric($moist) && $moistLim !== '-') {
                preg_match('/([<>]=?)\s*([\d.]+)/', (string) $moistLim, $m);
                if (count($m) === 3) {
                    $op  = trim($m[1]);
                    $lim = (float) $m[2];
                    $ok  = ($op === '<=' || $op === '=<') ? $moist <= $lim : $moist > $lim;
                    $sheet->getStyle('W' . $r)->applyFromArray($ok ? [
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
