<?php

namespace App\Http\Controllers;

use App\Models\OilCalculation;
use App\Models\OilMasterData;
use App\Models\OilRecord;
use App\Models\BobotConfig;
use App\Models\DailyBobotAverage;
use App\Services\OilService;
use App\Exports\OlwbExport;
use App\Exports\PerformanceExport;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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
        if (!Auth::user()->can('view oil losses')) {
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
        // Allow both 'create oil results' (PPIC) and 'input oil losses' (Sampel Boy)
        if (!Auth::user()->can('create oil results') && !Auth::user()->can('input oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data oil losses.');
        }

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
        // Allow both 'create oil results' (PPIC) and 'input oil losses' (Sampel Boy)
        if (!Auth::user()->can('create oil results') && !Auth::user()->can('input oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data oil losses.');
        }

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
        // Allow both 'view oil results' (PPIC) and 'view oil losses' (Sampel Boy, others)
        if (!Auth::user()->can('view oil results') && !Auth::user()->can('view oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data oil losses.');
        }

        $oilCalculation->load(['user']);

        return view('oil.show', ['oilLoss' => $oilCalculation]);
    }

    /**
     * Show the form for editing the specified oil record.
     */
    public function edit(OilCalculation $oilCalculation)
    {
        // Allow both 'edit oil results' (PPIC) and 'edit oil losses' (PPIC)
        if (!Auth::user()->can('edit oil results') && !Auth::user()->can('edit oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk edit data oil losses.');
        }

        $kodeOptions = OilMasterData::getKodeDropdown();
        $jenisOptions = OilMasterData::getJenisDropdown();

        return view('oil.edit', [
            'oilLoss' => $oilCalculation,
            'kodeOptions' => $kodeOptions,
            'jenisOptions' => $jenisOptions,
        ]);
    }

    /**
     * Update the specified oil calculation (numeric data + related record).
     * Tanggal menggunakan created_at (tidak bisa diubah).
     */
    public function update(Request $request, OilCalculation $oilCalculation)
    {
        // Allow both 'edit oil results' (PPIC) and 'edit oil losses' (PPIC)
        if (!Auth::user()->can('edit oil results') && !Auth::user()->can('edit oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk edit data oil losses.');
        }

        $validated = $request->validate([
            // Mode 1: Non-numeric fields (optional)
            'kode' => 'nullable|string|exists:oil_master_data,kode',
            'jenis' => 'nullable|string',
            'operator' => 'nullable|string|max:255',
            'sampel_boy' => 'nullable|string|max:255',
            // Mode 2: Numeric fields
            'cawan_kosong' => 'required|numeric|min:0',
            'berat_basah' => 'required|numeric|min:0',
            'cawan_sample_kering' => 'required|numeric|min:0',
            'labu_kosong' => 'required|numeric|min:0',
            'oil_labu' => 'required|numeric|min:0',
        ]);

        try {
            // Get kode for calculation (use validated kode or existing kode)
            $kodeForCalculation = $validated['kode'] ?? $oilCalculation->kode;

            // Calculate derived values using OilService
            $result = $this->oilService->calculate([
                'cawan_kosong' => $validated['cawan_kosong'],
                'berat_basah' => $validated['berat_basah'],
                'cawan_sample_kering' => $validated['cawan_sample_kering'],
                'labu_kosong' => $validated['labu_kosong'],
                'oil_labu' => $validated['oil_labu'],
            ], $kodeForCalculation);

            // Update OilCalculation (numeric data)
            $oilCalculation->update([
                'kode' => $kodeForCalculation,
                'cawan_kosong' => $result['cawan_kosong'],
                'berat_basah' => $result['berat_basah'],
                'cawan_sample_kering' => $result['cawan_sample_kering'],
                'labu_kosong' => $result['labu_kosong'],
                'oil_labu' => $result['oil_labu'],
                'total_cawan_basah' => $result['total_cawan_basah'],
                'sampel_setelah_oven' => $result['sampel_setelah_oven'],
                'minyak' => $result['minyak'],
                'moist' => $result['moist'],
                'dmwm' => $result['dmwm'],
                'olwb' => $result['olwb'],
                'oldb' => $result['oldb'],
                'oil_losses' => $result['oil_losses'],
                'limitOLWB' => $result['limitOLWB'],
                'limitOLDB' => $result['limitOLDB'],
                'limitOL' => $result['limitOL'],
                'persen' => $result['persen'],
                'persen4' => $result['persen4'],
                'user_id' => Auth::id(),
            ]);

            // Update or create related OilRecord (non-numeric data) if provided
            if ($validated['kode'] && $validated['jenis']) {
                $recordDate = $oilCalculation->created_at->format('Y-m-d');

                $relatedRecord = OilRecord::where('kode', $oilCalculation->kode)
                    ->whereDate('created_at', $recordDate)
                    ->first();

                if ($relatedRecord) {
                    // Update existing record
                    $relatedRecord->update([
                        'kode' => $validated['kode'],
                        'jenis' => $validated['jenis'],
                        'operator' => $validated['operator'],
                        'sampel_boy' => $validated['sampel_boy'],
                        'user_id' => Auth::id(),
                    ]);
                } else {
                    // Create new record if doesn't exist
                    OilRecord::create([
                        'kode' => $validated['kode'],
                        'jenis' => $validated['jenis'],
                        'operator' => $validated['operator'],
                        'sampel_boy' => $validated['sampel_boy'],
                        'user_id' => Auth::id(),
                        'created_at' => $oilCalculation->created_at,
                    ]);
                }
            }

            // Recalculate daily bobot average for the calculation date
            $this->recalculateDailyBobotAverage($oilCalculation->created_at->format('Y-m-d'));

            return redirect()
                ->route('oil.index')
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
        // Allow both 'delete oil results' (PPIC) and 'delete oil losses' (PPIC)
        if (!Auth::user()->can('delete oil results') && !Auth::user()->can('delete oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk hapus data oil losses.');
        }

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
        // Allow both 'delete oil results' (PPIC) and 'delete oil losses' (PPIC)
        if (!Auth::user()->can('delete oil results') && !Auth::user()->can('delete oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk hapus data oil losses.');
        }

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
        // Allow both 'view oil samples' (PPIC) and 'view performance' (Asisten Lab, others)
        if (!Auth::user()->can('view oil samples') && !Auth::user()->can('view performance')) {
            abort(403, 'Anda tidak memiliki akses untuk melihat performance.');
        }

        return view('oil.report');
    }
    /**
     * Display OLWB table view with dates as rows and kode as columns.
     */
    public function olwbIndex(Request $request)
    {
        // Allow both 'view oil samples' (PPIC) and 'view olwb' (Asisten Lab, others)
        if (!Auth::user()->can('view oil samples') && !Auth::user()->can('view olwb')) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data OLWB.');
        }

        // Default date range: awal bulan sampai hari ini
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get all kode with pivot from master data (ordered)
        $allKodesData = OilMasterData::where('is_active', true)
            ->orderBy('kode')
            ->get()
            ->map(function ($masterData) {
                return [
                    'kode' => $masterData->kode,
                    'pivot' => $masterData->pivot ?: $masterData->kode,
                ];
            });

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

        return view('oil.olwb', compact('allKodesData', 'dataByDate', 'startDate', 'endDate'));
    }

    /**
     * Export OLWB data to Excel
     */
    public function exportOlwb(Request $request)
    {
        // Allow both 'view oil samples' (PPIC) and 'view olwb' (Asisten Lab, others)
        if (!Auth::user()->can('view oil samples') && !Auth::user()->can('view olwb')) {
            abort(403, 'Anda tidak memiliki akses untuk export data OLWB.');
        }

        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get all kode with pivot from master data (ordered)
        $allKodesData = OilMasterData::where('is_active', true)
            ->orderBy('kode')
            ->get()
            ->map(function ($masterData) {
                return [
                    'kode' => $masterData->kode,
                    'pivot' => $masterData->pivot ?: $masterData->kode,
                ];
            });

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

            $dataByDate[$date][$kode] = [
                'olwb' => $calc->olwb,
                'limitOLWB' => $calc->limitOLWB,
                'id' => $calc->id,
            ];
        }

        ksort($dataByDate);

        // Generate filename with date range
        $startFormatted = Carbon::parse($startDate)->format('Ymd');
        $endFormatted = Carbon::parse($endDate)->format('Ymd');
        $filename = "OLWB_{$startFormatted}_{$endFormatted}.xlsx";

        return Excel::download(new OlwbExport($dataByDate, $allKodesData, $startDate, $endDate), $filename);
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
        // Return with pivot info for header display
        $allKodesData = OilMasterData::where('is_active', true)
            ->get()
            ->filter(function ($masterData) use ($bobotConfigs) {
                // Check if jenis can be mapped to bobot config
                $jenisConfig = $this->mapJenisToConfig($masterData->jenis, $bobotConfigs);
                return $jenisConfig !== null;
            })
            ->sortBy('kode')
            ->map(function ($masterData) {
                return [
                    'kode' => $masterData->kode,
                    'pivot' => $masterData->pivot ?: $masterData->kode,
                    'jenis' => $masterData->jenis,
                ];
            })
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

            $pressScores = [];
            $clarificationScores = [];

            // Process each kode
            foreach ($allKodesData as $kodeInfo) {
                $kode = $kodeInfo['kode'];
                $jenis = $kodeInfo['jenis'];

                if (isset($calculations[$kode]) && $calculations[$kode]->olwb !== null) {
                    $olwbValue = $calculations[$kode]->olwb;

                    // Map jenis from master data to bobot config
                    $jenisConfig = $this->mapJenisToConfig($jenis, $bobotConfigs);

                    if ($jenisConfig) {
                        $bobotScore = $this->calculateBobot($olwbValue, $jenisConfig);
                    } else {
                        $bobotScore = null;
                    }

                    $dateData['kodes'][$kode] = [
                        'olwb' => $olwbValue,
                        'bobot' => $bobotScore,
                    ];

                    // Categorize by Press or Clarification
                    if ($bobotScore !== null) {
                        $jenisUpper = strtoupper($jenis);
                        if (str_contains($jenisUpper, 'PRESS')) {
                            $pressScores[] = $bobotScore;
                        } else {
                            $clarificationScores[] = $bobotScore;
                        }
                    }
                } else {
                    $dateData['kodes'][$kode] = [
                        'olwb' => null,
                        'bobot' => null,
                    ];
                }
            }

            // Calculate separate averages for Press and Clarification
            $averagePress = !empty($pressScores) ? round(array_sum($pressScores) / count($pressScores), 2) : null;
            $averageClarification = !empty($clarificationScores) ? round(array_sum($clarificationScores) / count($clarificationScores), 2) : null;

            $dateData['average_press'] = $averagePress;
            $dateData['average_clarification'] = $averageClarification;

            // Store/update daily average in database (use average of both if available)
            $overallForDb = null;
            if ($averagePress !== null && $averageClarification !== null) {
                $overallForDb = round(($averagePress + $averageClarification) / 2, 2);
            } elseif ($averagePress !== null) {
                $overallForDb = $averagePress;
            } elseif ($averageClarification !== null) {
                $overallForDb = $averageClarification;
            }

            if ($overallForDb !== null) {
                DailyBobotAverage::updateOrCreate(
                    ['date' => $date],
                    ['average_score' => $overallForDb]
                );
            }

            $reportData[] = $dateData;
        }

        // Get operator data for the same date range
        $operatorRecords = OilRecord::select('oil_records.*', 'oil_master_data.jenis')
            ->join('oil_master_data', 'oil_records.kode', '=', 'oil_master_data.kode')
            ->whereBetween('oil_records.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotNull('oil_records.operator')
            ->where('oil_records.operator', '!=', '')
            ->orderBy('oil_records.created_at', 'desc')
            ->get();

        // Group operators by Press and Clarification
        $operatorPress = $operatorRecords->filter(function ($record) {
            return str_contains(strtoupper($record->jenis), 'PRESS');
        })->unique('operator')->pluck('operator')->sort()->values();

        $operatorClarification = $operatorRecords->filter(function ($record) {
            return !str_contains(strtoupper($record->jenis), 'PRESS');
        })->unique('operator')->pluck('operator')->sort()->values();

        // Get recent activities for each operator
        $operatorActivities = $operatorRecords->groupBy('operator')->map(function ($records) {
            return [
                'total_input' => $records->count(),
                'last_input' => $records->first()->created_at,
                'jenis_types' => $records->pluck('jenis')->unique()->sort()->values(),
            ];
        });

        // Prepare dates and daily performance for operator table
        $reportDates = collect($reportData)->pluck('date');
        $dailyPerformance = collect($reportData)->keyBy('date')->map(function ($item) {
            return [
                'average_press' => $item['average_press'],
                'average_clarification' => $item['average_clarification'],
            ];
        });

        return view('oil.report', compact(
            'reportData',
            'allKodesData',
            'startDate',
            'endDate',
            'operatorPress',
            'operatorClarification',
            'operatorActivities',
            'reportDates',
            'dailyPerformance'
        ));
    }

    /**
     * Export Performance report to Excel
     */
    public function exportPerformance(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get all bobot configs
        $bobotConfigs = BobotConfig::all()->keyBy('jenis');

        // Get ALL kodes from OilMasterData that have jenis mapping to bobot configs
        $allKodesData = OilMasterData::where('is_active', true)
            ->get()
            ->filter(function ($masterData) use ($bobotConfigs) {
                $jenisConfig = $this->mapJenisToConfig($masterData->jenis, $bobotConfigs);
                return $jenisConfig !== null;
            })
            ->sortBy('kode')
            ->map(function ($masterData) {
                return [
                    'kode' => $masterData->kode,
                    'pivot' => $masterData->pivot ?: $masterData->kode,
                    'jenis' => $masterData->jenis,
                ];
            })
            ->values();

        // Get all dates in range that have calculations
        $dates = OilCalculation::selectRaw('DATE(created_at) as date')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date');

        $reportData = [];

        foreach ($dates as $date) {
            $calculations = OilCalculation::whereDate('created_at', $date)->get()->keyBy('kode');

            $dateData = [
                'date' => $date,
                'kodes' => [],
            ];

            $pressScores = [];
            $clarificationScores = [];

            foreach ($allKodesData as $kodeInfo) {
                $kode = $kodeInfo['kode'];
                $jenis = $kodeInfo['jenis'];

                if (isset($calculations[$kode]) && $calculations[$kode]->olwb !== null) {
                    $olwbValue = $calculations[$kode]->olwb;
                    $jenisConfig = $this->mapJenisToConfig($jenis, $bobotConfigs);

                    if ($jenisConfig) {
                        $bobotScore = $this->calculateBobot($olwbValue, $jenisConfig);
                    } else {
                        $bobotScore = null;
                    }

                    $dateData['kodes'][$kode] = [
                        'olwb' => $olwbValue,
                        'bobot' => $bobotScore,
                    ];

                    if ($bobotScore !== null) {
                        $jenisUpper = strtoupper($jenis);
                        if (str_contains($jenisUpper, 'PRESS')) {
                            $pressScores[] = $bobotScore;
                        } else {
                            $clarificationScores[] = $bobotScore;
                        }
                    }
                } else {
                    $dateData['kodes'][$kode] = [
                        'olwb' => null,
                        'bobot' => null,
                    ];
                }
            }

            $averagePress = !empty($pressScores) ? round(array_sum($pressScores) / count($pressScores), 2) : null;
            $averageClarification = !empty($clarificationScores) ? round(array_sum($clarificationScores) / count($clarificationScores), 2) : null;

            $dateData['average_press'] = $averagePress;
            $dateData['average_clarification'] = $averageClarification;

            $reportData[] = $dateData;
        }

        // Get operator data
        $operatorRecords = OilRecord::select('oil_records.*', 'oil_master_data.jenis')
            ->join('oil_master_data', 'oil_records.kode', '=', 'oil_master_data.kode')
            ->whereBetween('oil_records.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotNull('oil_records.operator')
            ->where('oil_records.operator', '!=', '')
            ->orderBy('oil_records.created_at', 'desc')
            ->get();

        $operatorPress = $operatorRecords->filter(function ($record) {
            return str_contains(strtoupper($record->jenis), 'PRESS');
        })->unique('operator')->pluck('operator')->sort()->values();

        $operatorClarification = $operatorRecords->filter(function ($record) {
            return !str_contains(strtoupper($record->jenis), 'PRESS');
        })->unique('operator')->pluck('operator')->sort()->values();

        $reportDates = collect($reportData)->pluck('date');
        $dailyPerformance = collect($reportData)->keyBy('date')->map(function ($item) {
            return [
                'average_press' => $item['average_press'],
                'average_clarification' => $item['average_clarification'],
            ];
        });

        // Generate filename
        $startFormatted = Carbon::parse($startDate)->format('Ymd');
        $endFormatted = Carbon::parse($endDate)->format('Ymd');
        $filename = "Performance_{$startFormatted}_{$endFormatted}.xlsx";

        return Excel::download(
            new PerformanceExport(
                $reportData,
                $allKodesData,
                $operatorPress,
                $operatorClarification,
                $reportDates,
                $dailyPerformance,
                $startDate,
                $endDate
            ),
            $filename
        );
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


