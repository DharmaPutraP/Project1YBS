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

class KernelRippleMillExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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

    // 17 columns  A..Q
    public function headings(): array
    {
        return [
            'NO', 'BULAN', 'TANGGAL', 'JAM', 'KODE', 'NAMA SAMPLE',
            'INPUTED BY', 'JENIS', 'OPERATOR', 'SAMPEL BOY',
            'BERAT SAMPEL (g)',
            'BERAT NUT UTUH (g)', 'BERAT NUT PECAH (g)',
            'SAMPLE NUT UTUH (%)', 'SAMPLE NUT PECAH (%)',
            'EFFICIENCY (%)', 'LIMIT EFFICIENCY',
        ];
    }

    public function map($row): array
    {
        static $counter = 0;
        $counter++;

        $master = $this->masterData[$row->kode] ?? null;

        $effVal  = (float) ($row->efficiency ?? 0);
        $limOp   = $row->limit_operator ?? null;
        $limV    = $row->limit_value !== null ? (float) $row->limit_value : null;
        $effLimit = $limV !== null ? (($limOp === 'le') ? '<= ' : '> ') . number_format($limV, 4) . '%' : '-';

        $beratUtuh  = (float) ($row->berat_nut_utuh  ?? 0);
        $beratPecah = (float) ($row->berat_nut_pecah ?? 0);
        $beratSampel = (float) ($row->berat_sampel ?? 0);
        $pctUtuh  = $beratSampel > 0 ? round($beratUtuh  / $beratSampel * 100, 2) : 0;
        $pctPecah = $beratSampel > 0 ? round($beratPecah / $beratSampel * 100, 2) : 0;

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
            $beratSampel,
            $beratUtuh,
            $beratPecah,
            $pctUtuh,
            $pctPecah,
            round($effVal, 4),
            $effLimit,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->insertNewRowBefore(1, 3);

        $sheet->setCellValue('A1', 'LAPORAN DATA RIPPLE MILL');
        $sheet->mergeCells('A1:Q1');

        $filterInfo = 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y')
            . ' s/d ' . Carbon::parse($this->endDate)->format('d-m-Y');
        if ($this->kode) {
            $filterInfo .= ' | Kode: ' . $this->kode;
        }
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:Q2');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A4:Q4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        $sheet->getStyle('A4:Q' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        ]);
        $sheet->getStyle('K5:Q' . $lastRow)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);

        // Efficiency = column P (16), limit = Q (17)
        for ($r = 5; $r <= $lastRow; $r++) {
            $eff    = $sheet->getCell('P' . $r)->getValue();
            $effLim = $sheet->getCell('Q' . $r)->getValue();
            if (is_numeric($eff) && $effLim !== '-') {
                preg_match('/([<>]=?)\s*([\d.]+)/', (string) $effLim, $m);
                if (count($m) === 3) {
                    $op  = trim($m[1]);
                    $lim = (float) $m[2];
                    $ok  = ($op === '<=' || $op === '=<') ? $eff <= $lim : $eff > $lim;
                    $sheet->getStyle('P' . $r)->applyFromArray($ok ? [
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
