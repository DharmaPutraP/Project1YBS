<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    public function index()
    {
        /**
         * 1️⃣ CALCULATION + RECORD
         * prioritas = 1
         */
        $calculationWithRecord = DB::table('oil_calculations as c')
            ->join('oil_records as r', function ($join) {
                $join->on('c.kode', '=', 'r.kode')
                    ->on('c.user_id', '=', 'r.user_id')
                    ->on('c.created_at', '=', 'r.created_at');
            })
            ->leftJoin('users as u', 'u.id', '=', 'c.user_id')
            ->whereNull('c.deleted_at')
            ->whereNull('r.deleted_at')
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
         * 2️⃣ CALCULATION ONLY
         * prioritas = 2
         */
        $calculationOnly = DB::table('oil_calculations as c')
            ->leftJoin('users as u', 'u.id', '=', 'c.user_id')
            ->whereNull('c.deleted_at')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('oil_records as r')
                    ->whereColumn('c.kode', 'r.kode')
                    ->whereColumn('c.user_id', 'r.user_id')
                    ->whereColumn('c.created_at', 'r.created_at')
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
         * 3️⃣ RECORD ONLY
         * prioritas = 3
         */
        $recordOnly = DB::table('oil_records as r')
            ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
            ->whereNull('r.deleted_at')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
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
         * 4️⃣ UNION + ORDERING
         */
        $allReports = $calculationWithRecord
            ->unionAll($calculationOnly)
            ->unionAll($recordOnly)
            ->orderBy('kode', 'asc')
            ->orderBy('priority', 'asc')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();

        /**
         * 5️⃣ MANUAL PAGINATION
         */
        $perPage = 50;
        $page = request()->get('page', 1);
        $total = count($allReports);
        $items = array_slice($allReports, ($page - 1) * $perPage, $perPage);

        $reports = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('reports.index', [
            'calculations' => $reports
        ]);
    }
}