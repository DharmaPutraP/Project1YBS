<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KernelPerformanceExport implements WithMultipleSheets
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

    public function sheets(): array
    {
        return [
            new KernelPerformanceSheet(
                $this->individualRecords,
                $this->operators,
                $this->reportDates,
                $this->operatorDailyPerformance,
                $this->startDate,
                $this->endDate,
                $this->office
            ),
        ];
    }
}
