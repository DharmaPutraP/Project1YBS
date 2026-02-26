<?php

namespace App\Http\Controllers;

use App\Models\OilCalculation;
use App\Models\OilMasterData;
use App\Models\OilRecord;
use App\Models\BobotConfig;
use App\Models\DailyBobotAverage;
use App\Services\OilService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OilController extends Controller
{
    protected OilService $oilService;

    public function __construct(OilService $oilService)
    {
        $this->oilService = $oilService;
    }

    /**
     * Display a listing of lab calculations (oil loss records).
     */
    public function index(Request $request)
    {
        // Default filter date: hari ini untuk start dan end
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Query for Mode 2: Lab Calculations (numeric data)
        $calculationsQuery = OilCalculation::with(['user'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        // Query for Mode 1: Lab Records (non-numeric data)
        $recordsQuery = OilRecord::with(['user'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        // Filter by kode if provided (optional)
        if ($request->filled('kode')) {
            $calculationsQuery->where('kode', $request->kode);
            $recordsQuery->where('kode', $request->kode);
        }

        // Check permission - if user can only view own results
        if (!Auth::user()->can('view oil results') && Auth::user()->can('view own oil results')) {
            $calculationsQuery->where('user_id', Auth::id());
            $recordsQuery->where('user_id', Auth::id());
        }

        // Get statistics based on FILTERED queries (before pagination)
        $totalCalculations = (clone $calculationsQuery)->count();
        $totalRecords = (clone $recordsQuery)->count();

        $statistics = [
            'total_records' => $totalCalculations + $totalRecords,
            'records_today' => OilCalculation::whereDate('created_at', today())->count() + OilRecord::whereDate('created_at', today())->count(),
            'calculations_count' => $totalCalculations,
            'records_count' => $totalRecords,
        ];

        // Paginate results
        $oilLosses = $calculationsQuery->paginate(15, ['*'], 'calculations');
        $oilRecords = $recordsQuery->paginate(15, ['*'], 'records');

        // Preserve query parameters in pagination links
        $oilLosses->appends($request->only(['start_date', 'end_date', 'kode']));
        $oilRecords->appends($request->only(['start_date', 'end_date', 'kode']));

        // Get all kode options for filter dropdown
        $kodeOptions = OilMasterData::getKodeDropdown();

        return view('oil.index', compact('oilLosses', 'oilRecords', 'statistics', 'kodeOptions', 'startDate', 'endDate'));
    }

    /**
     * Show the form for creating a new lab record with dual-mode input.
     */
    public function create()
    {
        $this->authorize('create lab results');

        // Get dropdown options from master data
        $kodeOptions = OilMasterData::getKodeDropdown();
        $jenisOptions = OilMasterData::getJenisDropdown();

        return view('oil.create', compact('kodeOptions', 'jenisOptions'));
    }

    /**
     * Store lab data with dual-mode input (non-numeric or numeric).
     * Tanggal dan jam otomatis dari created_at timestamp.
     */
    public function store(Request $request)
    {
        $this->authorize('create oil results');

        // Custom validation: Jika 1 field di mode diisi, semua field di mode tersebut WAJIB diisi
        $mode1Fields = ['kode', 'jenis', 'operator', 'sampel_boy'];
        $mode2Fields = ['kode_mode2', 'cawan_kosong', 'berat_basah', 'cawan_sample_kering', 'labu_kosong', 'oil_labu'];

        // Cek apakah ada field Mode 1 yang diisi
        $mode1HasAnyValue = collect($mode1Fields)->contains(fn($field) => $request->filled($field));

        // Cek apakah ada field Mode 2 yang diisi
        $mode2HasAnyValue = collect($mode2Fields)->contains(fn($field) => $request->filled($field));

        // Validate fields
        $validated = $request->validate([
            // Mode 1 fields - required jika salah satu field mode 1 diisi
            'kode' => $mode1HasAnyValue ? 'required|string|exists:oil_master_data,kode' : 'nullable|string|exists:oil_master_data,kode',
            'jenis' => $mode1HasAnyValue ? 'required|string' : 'nullable|string',
            'operator' => $mode1HasAnyValue ? 'required|string|max:255' : 'nullable|string|max:255',
            'sampel_boy' => $mode1HasAnyValue ? 'required|string|max:255' : 'nullable|string|max:255',
            'parameter_lain' => 'nullable|string|max:500',

            // Mode 2 fields - required jika salah satu field mode 2 diisi
            'kode_mode2' => $mode2HasAnyValue ? 'required|string|exists:oil_master_data,kode' : 'nullable|string|exists:oil_master_data,kode',
            'cawan_kosong' => $mode2HasAnyValue ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'berat_basah' => $mode2HasAnyValue ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'cawan_sample_kering' => $mode2HasAnyValue ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'labu_kosong' => $mode2HasAnyValue ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'oil_labu' => $mode2HasAnyValue ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
        ], [
            // Mode 1 error messages
            'kode.required' => 'Kode wajib diisi jika Anda mengisi Mode Non-Angka',
            'kode.exists' => 'Kode tidak valid atau tidak ditemukan di master data',
            'jenis.required' => 'Jenis wajib diisi jika Anda mengisi Mode Non-Angka',
            'operator.required' => 'Operator wajib diisi jika Anda mengisi Mode Non-Angka',
            'sampel_boy.required' => 'Sampel Boy wajib diisi jika Anda mengisi Mode Non-Angka',

            // Mode 2 error messages
            'kode_mode2.required' => 'Kode wajib diisi jika Anda mengisi Mode Angka',
            'kode_mode2.exists' => 'Kode tidak valid atau tidak ditemukan di master data',
            'cawan_kosong.required' => 'Cawan Kosong wajib diisi jika Anda mengisi Mode Angka',
            'berat_basah.required' => 'Berat Basah wajib diisi jika Anda mengisi Mode Angka',
            'cawan_sample_kering.required' => 'Cawan + Sample Kering wajib diisi jika Anda mengisi Mode Angka',
            'labu_kosong.required' => 'Labu Kosong wajib diisi jika Anda mengisi Mode Angka',
            'oil_labu.required' => 'Oil + Labu wajib diisi jika Anda mengisi Mode Angka',
        ]);

        try {
            // Tanggal dan jam otomatis dari created_at (tidak perlu set manual)
            $result = $this->oilService->store($validated, Auth::id());

            // Recalculate daily bobot average for today
            $this->recalculateDailyBobotAverage(now()->format('Y-m-d'));

            return redirect()
                ->route('oil.index')
                ->with('success', $result['message']);

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified oil calculation with results.
     */
    public function show(OilCalculation $oilCalculation)
    {
        $this->authorize('view oil results');

        $oilCalculation->load(['user']);

        return view('oil.show', ['oilLoss' => $oilCalculation]);
    }

    /**
     * Show the form for editing the specified oil record.
     */
    public function edit(OilCalculation $oilCalculation)
    {
        $this->authorize('edit oil results');

        $kodeOptions = OilMasterData::getKodeDropdown();
        $jenisOptions = OilMasterData::getJenisDropdown();

        return view('oil.edit', [
            'oilLoss' => $oilCalculation,
            'kodeOptions' => $kodeOptions,
            'jenisOptions' => $jenisOptions,
        ]);
    }

    /**
     * Update the specified oil calculation (numeric data only).
     * Non-numeric data should be managed separately via OilRecord.
     * Tanggal menggunakan created_at (tidak bisa diubah).
     */
    public function update(Request $request, OilCalculation $oilCalculation)
    {
        $this->authorize('edit oil results');

        $validated = $request->validate([
            'cawan_kosong' => 'required|numeric|min:0',
            'berat_basah' => 'required|numeric|min:0',
            'cawan_sample_kering' => 'required|numeric|min:0',
            'labu_kosong' => 'required|numeric|min:0',
            'oil_labu' => 'required|numeric|min:0',
        ]);

        try {
            // Re-calculate and update (tanggal tetap menggunakan created_at yang ada)
            $result = $this->oilService->store($validated, Auth::id());

            // Recalculate daily bobot average for the calculation date
            $this->recalculateDailyBobotAverage($oilCalculation->created_at->format('Y-m-d'));

            return redirect()
                ->route('oil.show', $oilCalculation->id)
                ->with('success', 'Data berhasil diupdate!');

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified oil calculation.
     */
    public function destroy(OilCalculation $oilCalculation)
    {
        $this->authorize('delete oil results');

        $calculationDate = $oilCalculation->created_at->format('Y-m-d');
        $oilCalculation->delete();

        // Recalculate daily bobot average after deletion
        $this->recalculateDailyBobotAverage($calculationDate);

        return redirect()
            ->route('oil.index')
            ->with('success', 'Data perhitungan berhasil dihapus!');
    }

    /**
     * Remove the specified oil record (soft delete for non-numeric data).
     */
    public function destroyRecord(OilRecord $oilRecord)
    {
        $this->authorize('delete oil results');

        $oilRecord->delete(); // Soft delete karena model menggunakan SoftDeletes trait

        return redirect()
            ->route('oil.index')
            ->with('success', 'Data non-angka berhasil dihapus!');
    }

    /**
     * Export oil loss data.
     */
    public function export(Request $request)
    {
        $this->authorize('export oil data');

        // TODO: Implement export to Excel/PDF
        return back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }

    public function report(Request $request)
    {
        $this->authorize('view oil samples');

        return view('oil.report');
    }
    /**
     * Display OLWB table view with dates as rows and kode as columns.
     */
    public function olwbIndex(Request $request)
    {
        $this->authorize('view oil samples');

        // Default date range: awal bulan sampai hari ini
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get all unique kode from master data (ordered)
        $allKodes = OilMasterData::orderBy('kode')->pluck('kode')->toArray();

        // Get oil calculations data within date range
        $calculations = OilCalculation::whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ])
            ->orderBy('created_at', 'asc')
            ->get();

        // Group data by date and kode
        $dataByDate = [];
        foreach ($calculations as $calc) {
            $date = $calc->created_at->format('Y-m-d');
            $kode = $calc->kode;

            if (!isset($dataByDate[$date])) {
                $dataByDate[$date] = [];
            }

            // Store OLWB value for this date and kode
            $dataByDate[$date][$kode] = [
                'olwb' => $calc->olwb,
                'limitOLWB' => $calc->limitOLWB,
                'id' => $calc->id,
            ];
        }

        // Sort dates
        ksort($dataByDate);

        return view('oil.olwb', compact('allKodes', 'dataByDate', 'startDate', 'endDate'));
    }

    /**
     * Display bobot report with performance calculations
     */
    public function reportIndex(Request $request)
    {
        // Default filter date: hari ini untuk start dan end
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get all bobot configs
        $bobotConfigs = BobotConfig::all()->keyBy('jenis');

        // Get ALL kodes from OilMasterData that have jenis mapping to bobot configs
        // Regardless whether they have data in calculations or not
        $allKodes = OilMasterData::where('is_active', true)
            ->get()
            ->filter(function ($masterData) use ($bobotConfigs) {
                // Check if jenis can be mapped to bobot config
                $jenisConfig = $this->mapJenisToConfig($masterData->jenis, $bobotConfigs);
                return $jenisConfig !== null;
            })
            ->pluck('kode')
            ->sort()
            ->values();

        // Get all dates in range that have calculations
        $dates = OilCalculation::selectRaw('DATE(created_at) as date')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date');

        $reportData = [];

        foreach ($dates as $date) {
            // Get all calculations for this date
            $calculations = OilCalculation::whereDate('created_at', $date)->get()->keyBy('kode');

            $dateData = [
                'date' => $date,
                'kodes' => [],
            ];

            // Process each kode
            foreach ($allKodes as $kode) {
                $masterData = OilMasterData::where('kode', $kode)->first();

                if (isset($calculations[$kode]) && $calculations[$kode]->olwb !== null) {
                    $olwbValue = $calculations[$kode]->olwb;

                    // Map jenis from master data to bobot config
                    $jenisConfig = null;
                    if ($masterData) {
                        $jenisConfig = $this->mapJenisToConfig($masterData->jenis, $bobotConfigs);
                    }

                    if ($jenisConfig) {
                        $bobotScore = $this->calculateBobot($olwbValue, $jenisConfig);
                    } else {
                        $bobotScore = null;
                    }

                    $dateData['kodes'][$kode] = [
                        'olwb' => $olwbValue,
                        'bobot' => $bobotScore,
                    ];
                } else {
                    $dateData['kodes'][$kode] = [
                        'olwb' => null,
                        'bobot' => null,
                    ];
                }
            }

            // Calculate average bobot for this date
            // Filter only null values, keep 0 (0 is valid poor performance score)
            $bobotValues = collect($dateData['kodes'])
                ->pluck('bobot')
                ->filter(function ($value) {
                    return $value !== null;
                });
            $averageBobot = $bobotValues->isNotEmpty() ? round($bobotValues->avg(), 2) : null;
            $dateData['average_bobot'] = $averageBobot;

            // Store/update daily average in database
            if ($averageBobot !== null) {
                DailyBobotAverage::updateOrCreate(
                    ['date' => $date],
                    ['average_score' => $averageBobot]
                );
            }

            $reportData[] = $dateData;
        }

        return view('oil.report', compact('reportData', 'allKodes', 'startDate', 'endDate'));
    }

    /**
     * Map jenis from OilMasterData to BobotConfig
     * Uses case-insensitive matching with name variations
     */
    private function mapJenisToConfig($jenis, $bobotConfigs)
    {
        $jenisUpper = strtoupper(trim($jenis));

        // Direct mapping with case-insensitive comparison
        foreach ($bobotConfigs as $configJenis => $config) {
            $configJenisUpper = strtoupper($configJenis);

            // Exact match
            if ($jenisUpper === $configJenisUpper) {
                return $config;
            }

            // Pattern matching for variations
            if ($jenisUpper === 'FEED DECANTER' && str_contains($configJenisUpper, 'FEED')) {
                return $config;
            }
            if ($jenisUpper === 'BUNCH PRESS' && str_contains($configJenisUpper, 'BUNCH PRESS')) {
                return $config;
            }
            if ($jenisUpper === 'PRESS' && $configJenisUpper === 'PRESS') {
                return $config;
            }
            if ($jenisUpper === 'EFFLUENT' && $configJenisUpper === 'EFFLUENT') {
                return $config;
            }
        }

        return null;
    }

    /**
     * Calculate bobot score based on OLWB value and limits
     */
    private function calculateBobot($olwbValue, $config)
    {
        // Check from highest bobot to lowest
        if ($olwbValue <= $config->limit_100) {
            return 100;
        } elseif ($olwbValue <= $config->limit_90) {
            return 90;
        } elseif ($olwbValue <= $config->limit_80) {
            return 80;
        } elseif ($olwbValue <= $config->limit_70) {
            return 70;
        } elseif ($olwbValue <= $config->limit_60) {
            return 60;
        } elseif ($olwbValue <= $config->limit_50) {
            return 50;
        } else {
            return 0; // Below all thresholds
        }
    }

    /**
     * Recalculate and update daily bobot average for a specific date
     */
    private function recalculateDailyBobotAverage($date)
    {
        // Get all bobot configs
        $bobotConfigs = BobotConfig::all()->keyBy('jenis');

        // Get all calculations for this date
        $calculations = OilCalculation::whereDate('created_at', $date)->get();

        if ($calculations->isEmpty()) {
            // No data for this date, delete the average record if exists
            DailyBobotAverage::where('date', $date)->delete();
            return;
        }

        $bobotScores = [];

        foreach ($calculations as $calc) {
            if ($calc->olwb === null)
                continue;

            // Get master data to retrieve jenis
            $masterData = OilMasterData::where('kode', $calc->kode)->first();
            if (!$masterData)
                continue;

            // Map jenis to bobot config
            $jenisConfig = $this->mapJenisToConfig($masterData->jenis, $bobotConfigs);
            if (!$jenisConfig)
                continue;

            // Calculate bobot score
            $bobotScore = $this->calculateBobot($calc->olwb, $jenisConfig);

            // Include ALL bobot scores including 0 (0 means poor performance, still valid)
            $bobotScores[] = $bobotScore;
        }

        // Calculate and store average
        if (!empty($bobotScores)) {
            $averageBobot = round(array_sum($bobotScores) / count($bobotScores), 2);
            DailyBobotAverage::updateOrCreate(
                ['date' => $date],
                ['average_score' => $averageBobot]
            );
        } else {
            // No valid bobot scores, delete the average record if exists
            DailyBobotAverage::where('date', $date)->delete();
        }
    }
}


