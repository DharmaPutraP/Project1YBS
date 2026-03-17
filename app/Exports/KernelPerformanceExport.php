<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KernelPerformanceExport implements WithMultipleSheets
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

    public function sheets(): array
    {
        return [
            new KernelPerformanceSheet(
                $this->reportData,
                $this->allKodesData,
                $this->operators,
                $this->reportDates,
                $this->dailyPerformance,
                $this->startDate,
                $this->endDate
            ),
        ];
    }
}
