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
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class OilController extends Controller
{
    use LogsActivity;

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

        // Office filter dengan default value
        // Default: user's office (jika ada), atau 'YBS' (jika user->office = NULL)
        $officeFilter = $request->input('office', Auth::user()->office ?? 'YBS');

        // Query for Mode 2: Lab Calculations (numeric data)
        // Multi-tenancy: filter by selected office or user's office
        $calculationsQuery = OilCalculation::with(['user']);

        // Apply office filter jika bukan 'all'
        if ($officeFilter !== 'all') {
            $calculationsQuery->where('office', $officeFilter);
        }

        $calculationsQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        // Query for Mode 1: Lab Records (non-numeric data)
        // Multi-tenancy: filter by selected office or user's office
        $recordsQuery = OilRecord::with(['user']);

        // Apply office filter jika bukan 'all'
        if ($officeFilter !== 'all') {
            $recordsQuery->where('office', $officeFilter);
        }

        $recordsQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
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

        // Statistics query
        $todayCalculations = OilCalculation::whereDate('created_at', today());
        $todayRecords = OilRecord::whereDate('created_at', today());

        // Apply office filter to today's statistics
        if ($officeFilter !== 'all') {
            $todayCalculations->where('office', $officeFilter);
            $todayRecords->where('office', $officeFilter);
        }

        $statistics = [
            'total_records' => $totalCalculations + $totalRecords,
            'records_today' => $todayCalculations->count() + $todayRecords->count(),
            'calculations_count' => $totalCalculations,
            'records_count' => $totalRecords,
        ];

        // Paginate results
        $oilLosses = $calculationsQuery->paginate(15, ['*'], 'calculations');
        $oilRecords = $recordsQuery->paginate(15, ['*'], 'records');

        // Preserve query parameters in pagination links
        $oilLosses->appends($request->only(['start_date', 'end_date', 'kode', 'office']));
        $oilRecords->appends($request->only(['start_date', 'end_date', 'kode', 'office']));

        // Get all kode options for filter dropdown
        $kodeOptions = OilMasterData::getKodeDropdown();

        return view('oil.index', compact('oilLosses', 'oilRecords', 'statistics', 'kodeOptions', 'startDate', 'endDate', 'officeFilter'));
    }

    /**
     * Show the form for creating a new lab record with dual-mode input.
     */
    public function create()
    {
        // Allow both 'create oil results' (PPIC) and 'input oil losses' (Sampel Boy)
        if (!Auth::user()->can('create oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data oil losses.');
        }

        // Get dropdown options from master data
        $kodeOptions = OilMasterData::getKodeDropdown();
        $jenisOptions = OilMasterData::getJenisDropdown();
        $operatorOptions = $this->getOperatorOptionsByOffice(Auth::user()->office);

        return view('oil.create', compact('kodeOptions', 'jenisOptions', 'operatorOptions'));
    }

    /**
     * Store lab data with dual-mode input (non-numeric or numeric).
     * Tanggal dan jam otomatis dari created_at timestamp.
     */
    public function store(Request $request)
    {
        // Allow both 'create oil results' (PPIC) and 'input oil losses' (Sampel Boy)
        if (!Auth::user()->can('create oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data oil losses.');
        }

        // Validate: User MUST have office assigned to input data
        if (!Auth::user()->office) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda harus memiliki Office yang ditentukan untuk dapat input data.'], 422);
            }
            return back()
                ->withInput()
                ->with('error', 'Anda harus memiliki Office yang ditentukan untuk dapat input data. Silakan hubungi Administrator.');
        }

        // Custom validation: Jika 1 field di mode diisi, semua field di mode tersebut WAJIB diisi
        // HANYA cek field yang user input manual (kode & operator)
        // Jenis dan Sampel Boy diabaikan karena punya default value
        $mode1UserFields = ['kode', 'operator'];
        $mode2Fields = ['kode_mode2', 'cawan_kosong', 'berat_basah', 'cawan_sample_kering', 'labu_kosong', 'oil_labu'];

        // Cek apakah ada field Mode 1 yang user isi manual (kode atau operator)
        $mode1HasAnyValue = collect($mode1UserFields)->contains(fn($field) => $request->filled($field));

        // Cek apakah ada field Mode 2 yang diisi
        $mode2HasAnyValue = collect($mode2Fields)->contains(fn($field) => $request->filled($field));

        // Validate fields
        $validated = $request->validate([
            // Mode 1 fields - kode & operator required jika salah satunya diisi
            'kode' => $mode1HasAnyValue ? 'required|string|exists:oil_master_data,kode' : 'nullable|string|exists:oil_master_data,kode',
            'operator' => $mode1HasAnyValue ? 'required|string|max:255' : 'nullable|string|max:255',
            // Jenis dan sampel_boy selalu nullable karena punya default value
            'jenis' => 'nullable|string',
            'sampel_boy' => 'nullable|string|max:255',
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
            'kode.required' => 'Kode wajib diisi jika Anda mengisi Operator',
            'kode.exists' => 'Kode tidak valid atau tidak ditemukan di master data',
            'operator.required' => 'Operator wajib diisi jika Anda mengisi Kode',

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
            // Pass user's office for multi-tenancy
            $result = $this->oilService->store($validated, Auth::id(), Auth::user()->office);

            // Recalculate daily bobot average for today
            $this->recalculateDailyBobotAverage(now()->format('Y-m-d'));

            // Log activity
            if (isset($result['results'])) {
                $loggedModel = null;
                $kode = null;
                $mode = [];

                if (isset($result['results']['mode1'])) {
                    $loggedModel = $result['results']['mode1'];
                    $kode = $loggedModel->kode;
                    $mode[] = 'non-numeric';
                }

                if (isset($result['results']['mode2'])) {
                    $loggedModel = $result['results']['mode2'];
                    $kode = $loggedModel->kode;
                    $mode[] = 'numeric';
                }

                if ($loggedModel && $kode) {
                    $this->logCreate(
                        $loggedModel,
                        "Input data oil losses untuk kode {$kode}",
                        ['mode' => implode(' + ', $mode)]
                    );
                }
            }

            $proofData = $this->buildSuccessProofData(
                $result['results']['mode1'] ?? null,
                $result['results']['mode2'] ?? null,
                $result['message']
            );

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'proof' => $proofData,
                ]);
            }

            return redirect()
                ->route('oil.index')
                ->with('success', $result['message'])
                ->with('success_proof', $proofData);

        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
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
        if (!Auth::user()->can('view oil losses')) {
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
        if (!Auth::user()->can('edit oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk edit data oil losses.');
        }

        $kodeOptions = OilMasterData::getKodeDropdown();
        $jenisOptions = OilMasterData::getJenisDropdown();
        $operatorOptions = $this->getOperatorOptionsByOffice($oilCalculation->office);

        return view('oil.edit', [
            'oilLoss' => $oilCalculation,
            'kodeOptions' => $kodeOptions,
            'jenisOptions' => $jenisOptions,
            'operatorOptions' => $operatorOptions,
        ]);
    }

    /**
     * Update the specified oil calculation (numeric data + related record).
     * Tanggal menggunakan created_at (tidak bisa diubah).
     */
    public function update(Request $request, OilCalculation $oilCalculation)
    {
        // Allow both 'edit oil results' (PPIC) and 'edit oil losses' (PPIC)
        if (!Auth::user()->can('edit oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk edit data oil losses.');
        }

        $validated = $request->validate([
            // Mode 1: Non-numeric fields (optional)
            'kode' => 'nullable|string|exists:oil_master_data,kode',
            'jenis' => 'nullable|string',
            'operator' => $this->getOperatorValidationRules($oilCalculation->office),
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

            // Log activity
            $this->logActivity(
                'update',
                "Update data oil losses kode {$oilCalculation->kode}",
                $oilCalculation
            );

            $proofData = $this->buildSuccessProofData(
                $relatedRecord ?? null,
                $oilCalculation,
                'Data berhasil diupdate!'
            );

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil diupdate!',
                    'proof' => $proofData,
                ]);
            }

            return redirect()
                ->route('oil.index')
                ->with('success', 'Data berhasil diupdate!')
                ->with('success_proof', $proofData);

        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
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
        if (!Auth::user()->can('delete oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk hapus data oil losses.');
        }

        $calculationDate = $oilCalculation->created_at->format('Y-m-d');
        $kode = $oilCalculation->kode;
        $oilCalculationId = $oilCalculation->id;

        // Log before delete
        $this->logDelete(
            $oilCalculation,
            "Hapus data oil losses kode {$kode} (ID: {$oilCalculationId})"
        );

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
        if (!Auth::user()->can('delete oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk hapus data oil losses.');
        }

        $kode = $oilRecord->kode;
        $oilRecordId = $oilRecord->id;

        // Log before delete
        $this->logDelete(
            $oilRecord,
            "Hapus data oil record (non-angka) kode {$kode} (ID: {$oilRecordId})"
        );

        $oilRecord->delete(); // Soft delete karena model menggunakan SoftDeletes trait

        return redirect()
            ->route('oil.index')
            ->with('success', 'Data non-angka berhasil dihapus!');
    }

    /**
     * Show the form for editing a non-numeric lab record.
     */
    public function editRecord(OilRecord $oilRecord)
    {
        if (!Auth::user()->can('edit oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk edit data.');
        }

        // Get dropdown options
        $kodeOptions = OilMasterData::getKodeDropdown();
        $jenisOptions = OilMasterData::getJenisDropdown();
        $operatorOptions = $this->getOperatorOptionsByOffice($oilRecord->office);

        return view('oil.edit-record', compact('oilRecord', 'kodeOptions', 'jenisOptions', 'operatorOptions'));
    }

    /**
     * Update a non-numeric lab record.
     */
    public function updateRecord(Request $request, OilRecord $oilRecord)
    {
        if (!Auth::user()->can('edit oil losses')) {
            abort(403, 'Anda tidak memiliki akses untuk edit data.');
        }

        $validated = $request->validate([
            'kode' => 'required|string|exists:oil_master_data,kode',
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($oilRecord->office, true),
            'sampel_boy' => 'nullable|string|max:255',
        ], [
            'kode.required' => 'Kode wajib diisi',
            'kode.exists' => 'Kode tidak valid atau tidak ditemukan di master data',
            'jenis.required' => 'Jenis wajib diisi',
            'operator.required' => 'Operator wajib diisi',
            'operator.in' => 'Operator tidak sesuai dengan daftar office.',
        ]);

        try {
            // Update the record
            $oilRecord->update([
                'kode' => $validated['kode'],
                'jenis' => $validated['jenis'],
                'operator' => $validated['operator'],
                'sampel_boy' => $validated['sampel_boy'] ?? $oilRecord->sampel_boy,
                // Don't update user_id - keep original creator
            ]);

            // Recalculate daily bobot average for the calculation date
            $this->recalculateDailyBobotAverage($oilRecord->created_at->format('Y-m-d'));

            // Log activity
            $this->logActivity(
                'update',
                "Update data jenis sampel kode {$oilRecord->kode}",
                $oilRecord
            );

            $proofData = $this->buildSuccessProofData(
                $oilRecord,
                null,
                'Data jenis sampel berhasil diupdate!'
            );

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data jenis sampel berhasil diupdate!',
                    'proof' => $proofData,
                ]);
            }

            return redirect()
                ->route('oil.index')
                ->with('success', 'Data jenis sampel berhasil diupdate!')
                ->with('success_proof', $proofData);

        } catch (Exception $e) {
            Log::error('Update OilRecord Error: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal update data: ' . $e->getMessage()], 500);
            }
            return back()
                ->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
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
        if (!Auth::user()->can('view performance oil losses')) {
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
        if (!Auth::user()->can('view olwb')) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data OLWB.');
        }

        // Default date range: awal bulan sampai hari ini
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Office filter dengan default value
        $officeFilter = $request->input('office', Auth::user()->office ?? 'YBS');

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
        $calculationsQuery = OilCalculation::whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ]);

        // Apply office filter jika bukan 'all'
        if ($officeFilter !== 'all') {
            $calculationsQuery->where('office', $officeFilter);
        }

        $calculations = $calculationsQuery->orderBy('created_at', 'asc')->get();

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

        return view('oil.olwb', compact('allKodesData', 'dataByDate', 'startDate', 'endDate', 'officeFilter'));
    }

    /**
     * Export OLWB data to Excel
     */
    public function exportOlwb(Request $request)
    {
        // Allow both 'view oil samples' (PPIC) and 'view olwb' (Asisten Lab, others)
        if (!Auth::user()->can('view olwb') && !Auth::user()->can('export olwb reports')) {
            abort(403, 'Anda tidak memiliki akses untuk export data OLWB.');
        }

        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Office filter dengan default value
        $officeFilter = $request->input('office', Auth::user()->office ?? 'YBS');

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
        $calculationsQuery = OilCalculation::whereBetween('created_at', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ]);

        // Apply office filter jika bukan 'all'
        if ($officeFilter !== 'all') {
            $calculationsQuery->where('office', $officeFilter);
        }

        $calculations = $calculationsQuery->orderBy('created_at', 'asc')->get();

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

        // Office filter dengan default value
        $officeFilter = $request->input('office', Auth::user()->office ?? 'YBS');

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
        $datesQuery = OilCalculation::selectRaw('DATE(created_at) as date')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Apply office filter jika bukan 'all'
        if ($officeFilter !== 'all') {
            $datesQuery->where('office', $officeFilter);
        }

        $dates = $datesQuery->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date');

        // OPTIMIZATION: Load ALL calculations in ONE query instead of query per date
        $allCalculationsQuery = OilCalculation::selectRaw('*, DATE(created_at) as calc_date')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($officeFilter !== 'all') {
            $allCalculationsQuery->where('office', $officeFilter);
        }

        // Group calculations by date in memory (no repeated queries)
        $calculationsByDate = $allCalculationsQuery->get()
            ->groupBy('calc_date')
            ->map(fn($calcs) => $calcs->keyBy('kode'));

        $reportData = [];

        foreach ($dates as $date) {
            // Get calculations from cached data (no query)
            $calculations = $calculationsByDate->get($date, collect());

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

        // Get operator data for the same date range with optimized join
        // Use LEFT JOIN and select only needed columns for better performance
        $operatorRecordsQuery = OilRecord::select('oil_records.id', 'oil_records.kode', 'oil_records.operator', 'oil_records.created_at', 'oil_master_data.jenis')
            ->leftJoin('oil_master_data', 'oil_records.kode', '=', 'oil_master_data.kode')
            ->whereBetween('oil_records.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotNull('oil_records.operator')
            ->where('oil_records.operator', '!=', '');

        // Apply office filter jika bukan 'all'
        if ($officeFilter !== 'all') {
            $operatorRecordsQuery->where('oil_records.office', $officeFilter);
        }

        $operatorRecords = $operatorRecordsQuery->orderBy('oil_records.created_at', 'desc')->get();

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
            'officeFilter',
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

        // Office filter dengan default value
        $officeFilter = $request->input('office', Auth::user()->office ?? 'YBS');

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
        $datesQuery = OilCalculation::selectRaw('DATE(created_at) as date')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Apply office filter jika bukan 'all'
        if ($officeFilter !== 'all') {
            $datesQuery->where('office', $officeFilter);
        }

        $dates = $datesQuery->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date');

        // OPTIMIZATION: Load ALL calculations in ONE query instead of query per date
        $allCalculationsForExport = OilCalculation::selectRaw('*, DATE(created_at) as calc_date')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($officeFilter !== 'all') {
            $allCalculationsForExport->where('office', $officeFilter);
        }

        // Group calculations by date in memory (no repeated queries)
        $calculationsByDateForExport = $allCalculationsForExport->get()
            ->groupBy('calc_date')
            ->map(fn($calcs) => $calcs->keyBy('kode'));

        $reportData = [];

        foreach ($dates as $date) {
            // Get calculations from cached data (no query)
            $calculations = $calculationsByDateForExport->get($date, collect());

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

        // Get all calculations for this date with master data eager loaded to prevent N+1
        $calculations = OilCalculation::whereDate('created_at', $date)
            ->with(['masterData:id,kode,jenis'])
            ->get();

        if ($calculations->isEmpty()) {
            // No data for this date, delete the average record if exists
            DailyBobotAverage::where('date', $date)->delete();
            return;
        }

        // Pre-load all master data in one query to avoid N+1
        $kodes = $calculations->pluck('kode')->unique();
        $masterDataCache = OilMasterData::whereIn('kode', $kodes)
            ->get()
            ->keyBy('kode');

        $bobotScores = [];

        foreach ($calculations as $calc) {
            if ($calc->olwb === null)
                continue;

            // Get master data from cache (no query)
            $masterData = $masterDataCache->get($calc->kode);
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

    /**
     * Build flash payload for proof modal after create/update.
     */
    private function buildSuccessProofData(?OilRecord $mode1Record, ?OilCalculation $mode2Calculation, string $message): array
    {
        $kodes = collect([
            $mode1Record?->kode,
            $mode2Calculation?->kode,
        ])->filter()->unique()->values();

        $masterDataByKode = OilMasterData::whereIn('kode', $kodes)
            ->get(['kode', 'pivot'])
            ->keyBy('kode');

        return [
            'message' => $message,
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'active_tab' => $mode2Calculation && !$mode1Record ? 'calculations' : 'records',
            'mode1' => $mode1Record ? $this->formatMode1ProofData($mode1Record, $masterDataByKode) : null,
            'mode2' => $mode2Calculation ? $this->formatMode2ProofData($mode2Calculation, $masterDataByKode) : null,
        ];
    }

    /**
     * Format non-numeric record data for success proof modal.
     */
    private function formatMode1ProofData(OilRecord $record, $masterDataByKode): array
    {
        $masterData = $masterDataByKode->get($record->kode);

        return [
            'tanggal_input' => $record->created_at->format('d/m/Y H:i:s'),
            'kode_label' => OilMasterData::getKodeDisplay($record->kode, $record->pivot ?: $masterData?->pivot),
            'jenis' => $record->jenis,
            'operator' => $record->operator,
            'sampel_boy' => $record->sampel_boy,
            'input_by' => $record->user?->name,
            'office' => $record->office,
        ];
    }

    /**
     * Format numeric calculation data for success proof modal.
     */
    private function formatMode2ProofData(OilCalculation $calculation, $masterDataByKode): array
    {
        $masterData = $masterDataByKode->get($calculation->kode);

        return [
            'tanggal_input' => $calculation->created_at->format('d/m/Y H:i:s'),
            'kode_label' => OilMasterData::getKodeDisplay($calculation->kode, $masterData?->pivot),
            'kode' => $calculation->kode,
            'cawan_kosong' => $calculation->cawan_kosong,
            'berat_basah' => $calculation->berat_basah,
            'cawan_sample_kering' => $calculation->cawan_sample_kering,
            'labu_kosong' => $calculation->labu_kosong,
            'oil_labu' => $calculation->oil_labu,
            'moist' => $calculation->moist,
            'dmwm' => $calculation->dmwm,
            'olwb' => $calculation->olwb,
            'oldb' => $calculation->oldb,
            'oil_losses' => $calculation->oil_losses,
            'limitOLWB' => $calculation->limitOLWB,
            'limitOLDB' => $calculation->limitOLDB,
            'limitOL' => $calculation->limitOL,
            'input_by' => $calculation->user?->name,
            'office' => $calculation->office,
        ];
    }

    /**
     * Get operator options by office.
     */
    private function getOperatorOptionsByOffice(?string $office): array
    {
        return match ($office) {
            'SUN' => [
                'Ramanda',
                'Agus Prawitno Sitohang',
                'Rizky Setiawan',
                'Andri Kaswari',
                'Dedi Prastiawan',
                'Ahmad Prawira Putra Pohan',
            ],
            default => [],
        };
    }

    /**
     * Build validation rules for operator by office.
     */
    private function getOperatorValidationRules(?string $office, bool $required = false): array
    {
        $rules = [$required ? 'required' : 'nullable', 'string', 'max:255'];
        $operatorOptions = $this->getOperatorOptionsByOffice($office);

        if (!empty($operatorOptions)) {
            $rules[] = Rule::in($operatorOptions);
        }

        return $rules;
    }
}


