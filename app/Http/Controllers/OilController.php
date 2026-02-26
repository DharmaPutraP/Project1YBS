<?php

namespace App\Http\Controllers;

use App\Models\OilCalculation;
use App\Models\OilMasterData;
use App\Models\OilRecord;
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
        // Query for Mode 2: Lab Calculations (numeric data)
        $calculationsQuery = OilCalculation::with(['user'])
            ->orderBy('created_at', 'desc');

        // Query for Mode 1: Lab Records (non-numeric data)
        $recordsQuery = OilRecord::with(['user'])
            ->orderBy('created_at', 'desc');

        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $calculationsQuery->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
            $recordsQuery->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
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
        $oilLosses->appends($request->only(['start_date', 'end_date']));
        $oilRecords->appends($request->only(['start_date', 'end_date']));

        return view('oil.index', compact('oilLosses', 'oilRecords', 'statistics'));
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

        $oilCalculation->delete();

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
}

