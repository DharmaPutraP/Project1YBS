<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OilMasterData;
use App\Models\BobotConfig;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        /**
         * ======================================================
         * FILTER PARAM
         * ======================================================
         */
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $kode = $request->input('kode');

        // Office filter: user dengan office hanya boleh lihat office-nya sendiri
        $userOffice = Auth()->user()->office;
        $officeFilter = $userOffice ?: $request->input('office', 'YBS');

        [$startDateTime, $endDateTime] = $this->resolveProductionRange($startDate, $endDate);

        /**
         * ======================================================
         * 1️⃣ CALCULATION + RECORD
         * ======================================================
         */
        $calculationWithRecord = DB::table('oil_calculations as c')
            ->join('oil_records as r', function ($join) {
                $join->on('c.kode', '=', 'r.kode')
                    ->on('c.user_id', '=', 'r.user_id')
                    ->on('c.created_at', '=', 'r.created_at')
                    ->whereNull('r.deleted_at');
            })
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'c.user_id')
                    ->whereNull('u.deleted_at');
            })
            ->whereNull('c.deleted_at')
            ->where('c.status', 'complete')
            ->whereBetween('c.created_at', [$startDateTime, $endDateTime])
            ->when($kode, fn($q) => $q->where('c.kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('c.office', $officeFilter))
            ->select([
                DB::raw('1 as priority'),
                'c.created_at',
                'c.kode',
                'c.office',
                'u.name as user_name',

                'r.pivot',
                'r.operator',
                'r.sampel_boy',
                'r.jenis',

                'c.cawan_kosong',
                'c.berat_basah',
                'c.total_cawan_basah',
                'c.cawan_sample_kering',
                'c.sampel_setelah_oven',
                'c.labu_kosong',
                'c.oil_labu',
                'c.minyak',
                'c.moist',
                'c.dmwm',
                'c.olwb',
                'c.limitOLWB',
                'c.oldb',
                'c.limitOLDB',
                'c.oil_losses',
                'c.limitOL',
                'c.persen4',
            ]);

        /**
         * ======================================================
         * 2️⃣ CALCULATION ONLY
         * ======================================================
         */
        $calculationOnly = DB::table('oil_calculations as c')
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'c.user_id')
                    ->whereNull('u.deleted_at');
            })
            ->whereNull('c.deleted_at')
            ->where('c.status', 'complete')
            ->whereBetween('c.created_at', [$startDateTime, $endDateTime])
            ->when($kode, fn($q) => $q->where('c.kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('c.office', $officeFilter))
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('oil_records as r')
                    ->whereColumn('r.kode', 'c.kode')
                    ->whereColumn('r.user_id', 'c.user_id')
                    ->whereColumn('r.created_at', 'c.created_at')
                    ->whereNull('r.deleted_at');
            })
            ->select([
                DB::raw('2 as priority'),
                'c.created_at',
                'c.kode',
                'c.office',
                'u.name as user_name',

                DB::raw('NULL as pivot'),
                DB::raw('NULL as operator'),
                DB::raw('NULL as sampel_boy'),
                DB::raw('NULL as jenis'),

                'c.cawan_kosong',
                'c.berat_basah',
                'c.total_cawan_basah',
                'c.cawan_sample_kering',
                'c.sampel_setelah_oven',
                'c.labu_kosong',
                'c.oil_labu',
                'c.minyak',
                'c.moist',
                'c.dmwm',
                'c.olwb',
                'c.limitOLWB',
                'c.oldb',
                'c.limitOLDB',
                'c.oil_losses',
                'c.limitOL',
                'c.persen4',
            ]);

        /**
         * ======================================================
         * 3️⃣ RECORD ONLY
         * ======================================================
         */
        $recordOnly = DB::table('oil_records as r')
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'r.user_id')
                    ->whereNull('u.deleted_at');
            })
            ->whereNull('r.deleted_at')
            ->whereBetween('r.created_at', [$startDateTime, $endDateTime])
            ->when($kode, fn($q) => $q->where('r.kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('r.office', $officeFilter))
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('oil_calculations as c')
                    ->whereColumn('c.kode', 'r.kode')
                    ->whereColumn('c.user_id', 'r.user_id')
                    ->whereColumn('c.created_at', 'r.created_at')
                    ->whereNull('c.deleted_at');
            })
            ->select([
                DB::raw('3 as priority'),
                'r.created_at',
                'r.kode',
                'r.office',
                'u.name as user_name',

                'r.pivot',
                'r.operator',
                'r.sampel_boy',
                'r.jenis',

                DB::raw('NULL as cawan_kosong'),
                DB::raw('NULL as berat_basah'),
                DB::raw('NULL as total_cawan_basah'),
                DB::raw('NULL as cawan_sample_kering'),
                DB::raw('NULL as sampel_setelah_oven'),
                DB::raw('NULL as labu_kosong'),
                DB::raw('NULL as oil_labu'),
                DB::raw('NULL as minyak'),
                DB::raw('NULL as moist'),
                DB::raw('NULL as dmwm'),
                DB::raw('NULL as olwb'),
                DB::raw('NULL as limitOLWB'),
                DB::raw('NULL as oldb'),
                DB::raw('NULL as limitOLDB'),
                DB::raw('NULL as oil_losses'),
                DB::raw('NULL as limitOL'),
                DB::raw('NULL as persen4'),
            ]);

        /**
         * ======================================================
         * 4️⃣ UNION + SORT
         * ======================================================
         */
        $unionQuery = $calculationWithRecord
            ->unionAll($calculationOnly)
            ->unionAll($recordOnly);

        /**
         * ======================================================
         * 5️⃣ FORMAT NUMBERS & PAGINATION
         * ======================================================
         */
        $perPage = 25;

        $reports = DB::query()
            ->fromSub($unionQuery, 'reports')
            ->orderBy('created_at', 'asc')
            ->orderBy('kode', 'asc')
            ->orderBy('priority', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // Keep formatted fields compatible with existing Blade templates.
        $reports->getCollection()->transform(function ($report) {
            $report->cawan_kosong_fmt = $this->formatNumber($report->cawan_kosong, 4);
            $report->berat_basah_fmt = $this->formatNumber($report->berat_basah, 4);
            $report->total_cawan_basah_fmt = $this->formatNumber($report->total_cawan_basah, 4);
            $report->cawan_sample_kering_fmt = $this->formatNumber($report->cawan_sample_kering, 4);
            $report->sampel_setelah_oven_fmt = $this->formatNumber($report->sampel_setelah_oven, 4);
            $report->labu_kosong_fmt = $this->formatNumber($report->labu_kosong, 4);
            $report->oil_labu_fmt = $this->formatNumber($report->oil_labu, 4);
            $report->minyak_fmt = $this->formatNumber($report->minyak, 4);
            $report->moist_fmt = $this->formatNumber($report->moist, 2);
            $report->dmwm_fmt = $this->formatNumber($report->dmwm, 2);
            $report->olwb_fmt = $this->formatNumber($report->olwb, 2);
            $report->limitOLWB_fmt = ($report->limitOLWB === null || $report->limitOLWB == 0)
                ? '-'
                : $this->formatNumber($report->limitOLWB, 2);
            $report->oldb_fmt = $this->formatNumber($report->oldb, 2);
            $report->limitOLDB_fmt = ($report->limitOLDB === null || $report->limitOLDB == 0)
                ? '-'
                : $this->formatNumber($report->limitOLDB, 2);
            $report->oil_losses_fmt = $this->formatNumber($report->oil_losses, 2);
            $report->limitOL_fmt = ($report->limitOL === null || $report->limitOL == 0)
                ? '-'
                : $this->formatNumber($report->limitOL, 2);
            $report->persen4_fmt = ($report->persen4 === null || $report->persen4 == 0)
                ? '-'
                : $this->formatNumber($report->persen4, 2);

            return $report;
        });

        /**
         * ======================================================
         * 6️⃣ KODE OPTIONS
         * ======================================================
         */
        $kodeOptions = OilMasterData::getKodeDropdown();

        return view('reports.index', [
            'calculations' => $reports,
            'kodeOptions' => $kodeOptions,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'officeFilter' => $officeFilter,
        ]);
    }

    /**
     * Map jenis to bobot config (same logic as OilController)
     */
    private function mapJenisToConfig($jenis, $bobotConfigs)
    {
        $jenisUpper = strtoupper(trim($jenis));

        foreach ($bobotConfigs as $configJenis => $config) {
            $configJenisUpper = strtoupper($configJenis);
            if ($jenisUpper === $configJenisUpper) {
                return $config;
            }
            if (str_contains($jenisUpper, $configJenisUpper) || str_contains($configJenisUpper, $jenisUpper)) {
                return $config;
            }
        }

        return null;
    }

    /**
     * Calculate bobot score (same logic as OilController)
     */
    private function calculateBobot($olwbValue, $jenisConfig)
    {
        if ($olwbValue === null) {
            return null;
        }

        $limits = [
            100 => $jenisConfig->limit_100,
            90 => $jenisConfig->limit_90,
            80 => $jenisConfig->limit_80,
            70 => $jenisConfig->limit_70,
            60 => $jenisConfig->limit_60,
            50 => $jenisConfig->limit_50,
        ];

        if ($olwbValue <= $limits[100])
            return 100;
        if ($olwbValue <= $limits[90])
            return 90;
        if ($olwbValue <= $limits[80])
            return 80;
        if ($olwbValue <= $limits[70])
            return 70;
        if ($olwbValue <= $limits[60])
            return 60;
        if ($olwbValue <= $limits[50])
            return 50;

        return 0;
    }
    /**
     * Export reports to Excel
     */
    public function export(Request $request)
    {
        // Same filter logic as index()
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $kode = $request->input('kode');

        // Office filter: user dengan office hanya boleh lihat office-nya sendiri
        $userOffice = Auth()->user()->office;
        $officeFilter = $userOffice ?: $request->input('office', 'YBS');

        [$startDateTime, $endDateTime] = $this->resolveProductionRange($startDate, $endDate);

        $calculationWithRecord = DB::table('oil_calculations as c')
            ->join('oil_records as r', function ($join) {
                $join->on('c.kode', '=', 'r.kode')
                    ->on('c.user_id', '=', 'r.user_id')
                    ->on('c.created_at', '=', 'r.created_at')
                    ->whereNull('r.deleted_at');
            })
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'c.user_id')
                    ->whereNull('u.deleted_at');
            })
            ->whereNull('c.deleted_at')
            ->where('c.status', 'complete')
            ->whereBetween('c.created_at', [$startDateTime, $endDateTime])
            ->when($kode, fn($q) => $q->where('c.kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('c.office', $officeFilter))
            ->select([
                DB::raw('1 as priority'),
                'c.created_at',
                'c.kode',
                'c.office',
                'u.name as user_name',
                'r.pivot',
                'r.operator',
                'r.sampel_boy',
                'r.jenis',
                'c.cawan_kosong',
                'c.berat_basah',
                'c.total_cawan_basah',
                'c.cawan_sample_kering',
                'c.sampel_setelah_oven',
                'c.labu_kosong',
                'c.oil_labu',
                'c.minyak',
                'c.moist',
                'c.dmwm',
                'c.olwb',
                'c.limitOLWB',
                'c.oldb',
                'c.limitOLDB',
                'c.oil_losses',
                'c.limitOL',
                'c.persen4',
            ]);

        $calculationOnly = DB::table('oil_calculations as c')
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'c.user_id')
                    ->whereNull('u.deleted_at');
            })
            ->whereNull('c.deleted_at')
            ->where('c.status', 'complete')
            ->whereBetween('c.created_at', [$startDateTime, $endDateTime])
            ->when($kode, fn($q) => $q->where('c.kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('c.office', $officeFilter))
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('oil_records as r')
                    ->whereColumn('r.kode', 'c.kode')
                    ->whereColumn('r.user_id', 'c.user_id')
                    ->whereColumn('r.created_at', 'c.created_at')
                    ->whereNull('r.deleted_at');
            })
            ->select([
                DB::raw('2 as priority'),
                'c.created_at',
                'c.kode',
                'c.office',
                'u.name as user_name',
                DB::raw('NULL as pivot'),
                DB::raw('NULL as operator'),
                DB::raw('NULL as sampel_boy'),
                DB::raw('NULL as jenis'),
                'c.cawan_kosong',
                'c.berat_basah',
                'c.total_cawan_basah',
                'c.cawan_sample_kering',
                'c.sampel_setelah_oven',
                'c.labu_kosong',
                'c.oil_labu',
                'c.minyak',
                'c.moist',
                'c.dmwm',
                'c.olwb',
                'c.limitOLWB',
                'c.oldb',
                'c.limitOLDB',
                'c.oil_losses',
                'c.limitOL',
                'c.persen4',
            ]);

        $recordOnly = DB::table('oil_records as r')
            ->leftJoin('users as u', function ($join) {
                $join->on('u.id', '=', 'r.user_id')
                    ->whereNull('u.deleted_at');
            })
            ->whereNull('r.deleted_at')
            ->whereBetween('r.created_at', [$startDateTime, $endDateTime])
            ->when($kode, fn($q) => $q->where('r.kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('r.office', $officeFilter))
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('oil_calculations as c')
                    ->whereColumn('c.kode', 'r.kode')
                    ->whereColumn('c.user_id', 'r.user_id')
                    ->whereColumn('c.created_at', 'r.created_at')
                    ->whereNull('c.deleted_at');
            })
            ->select([
                DB::raw('3 as priority'),
                'r.created_at',
                'r.kode',
                'u.name as user_name',
                'r.pivot',
                'r.operator',
                'r.sampel_boy',
                'r.jenis',
                DB::raw('NULL as cawan_kosong'),
                DB::raw('NULL as berat_basah'),
                DB::raw('NULL as total_cawan_basah'),
                DB::raw('NULL as cawan_sample_kering'),
                DB::raw('NULL as sampel_setelah_oven'),
                DB::raw('NULL as labu_kosong'),
                DB::raw('NULL as oil_labu'),
                DB::raw('NULL as minyak'),
                DB::raw('NULL as moist'),
                DB::raw('NULL as dmwm'),
                DB::raw('NULL as olwb'),
                DB::raw('NULL as limitOLWB'),
                DB::raw('NULL as oldb'),
                DB::raw('NULL as limitOLDB'),
                DB::raw('NULL as oil_losses'),
                DB::raw('NULL as limitOL'),
                DB::raw('NULL as persen4'),
            ]);

        $unionQuery = $calculationWithRecord
            ->unionAll($calculationOnly)
            ->unionAll($recordOnly);

        $exportQuery = DB::query()
            ->fromSub($unionQuery, 'reports')
            ->orderBy('created_at', 'asc')
            ->orderBy('kode', 'asc')
            ->orderBy('priority', 'asc');

        $filename = 'Laporan_Oil_Losses_' .
            Carbon::parse($startDate)->format('Ymd') . '_' .
            Carbon::parse($endDate)->format('Ymd');

        if ($kode) {
            $filename .= '_' . $kode;
        }

        $filename .= '.xlsx';

        return Excel::download(
            new ReportsExport($exportQuery, $startDate, $endDate, $kode),
            $filename
        );
    }

    /**
     * Format number with parentheses for negative values
     */
    private function formatNumber($value, $decimals = 2)
    {
        if ($value === null) {
            return '-';
        }

        if ($value < 0) {
            return '(' . number_format(abs($value), $decimals) . ')';
        }

        return number_format($value, $decimals);
    }

    private function resolveProductionRange(string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate)->setTime(7, 0, 0);
        $end = Carbon::parse($endDate)->addDay()->setTime(6, 59, 59);

        return [
            $start->format('Y-m-d H:i:s'),
            $end->format('Y-m-d H:i:s'),
        ];
    }
}