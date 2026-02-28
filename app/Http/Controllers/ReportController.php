<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\OilMasterData;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        /**
         * ======================================================
         * FILTER PARAM
         * ======================================================
         */
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));
        $kode      = $request->input('kode');

        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime   = $endDate . ' 23:59:59';

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
            ->whereBetween('c.created_at', [$startDateTime, $endDateTime])
            ->when($kode, fn ($q) => $q->where('c.kode', $kode))
            ->select([
                DB::raw('1 as priority'),
                'c.created_at',
                'c.kode',
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
            ->whereBetween('c.created_at', [$startDateTime, $endDateTime])
            ->when($kode, fn ($q) => $q->where('c.kode', $kode))
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
            ->when($kode, fn ($q) => $q->where('r.kode', $kode))
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

        /**
         * ======================================================
         * 4️⃣ UNION + SORT
         * ======================================================
         */
        $allReports = $calculationWithRecord
            ->unionAll($calculationOnly)
            ->unionAll($recordOnly)
            ->orderByRaw('DATE(created_at) ASC')
            ->orderBy('kode', 'ASC')
            ->orderBy('priority', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->get()
            ->toArray();

        /**
         * ======================================================
         * 5️⃣ PAGINATION
         * ======================================================
         */
        $perPage = 50;
        $page = $request->get('page', 1);
        $total = count($allReports);

        $items = array_slice($allReports, ($page - 1) * $perPage, $perPage);

        $reports = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        /**
         * ======================================================
         * 6️⃣ KODE OPTIONS
         * ======================================================
         */
        $kodeOptions = OilMasterData::getKodeDropdown();

        return view('reports.index', [
            'calculations' => $reports,
            'kodeOptions'  => $kodeOptions,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
        ]);
    }
}