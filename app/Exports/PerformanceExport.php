<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PerformanceExport implements WithMultipleSheets
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
     * Return array of sheets
     */
    public function sheets(): array
    {
        $sheets = [];

        // Sheet 1: Performance Table
        $sheets[] = new PerformanceSheet(
            $this->reportData,
            $this->allKodesData,
            $this->operatorPress,
            $this->operatorClarification,
            $this->reportDates,
            $this->dailyPerformance,
            $this->startDate,
            $this->endDate
        );

        return $sheets;
    }
}
