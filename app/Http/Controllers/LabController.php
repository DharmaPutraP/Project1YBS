<?php

namespace App\Http\Controllers;

use App\Models\LabCalculation;
use App\Models\LabMasterData;
use App\Models\LabRecord;
use App\Services\LabService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabController extends Controller
{
    protected LabService $labService;

    public function __construct(LabService $labService)
    {
        $this->labService = $labService;
    }

    /**
     * Display a listing of lab calculations (oil loss records).
     */
    public function index(Request $request)
    {
        $query = LabCalculation::with(['user'])
            ->orderBy('analysis_date', 'desc');

        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('analysis_date', [$request->start_date, $request->end_date]);
        }

        // Check permission - if user can only view own results
        if (!Auth::user()->can('view lab results') && Auth::user()->can('view own lab results')) {
            $query->where('user_id', Auth::id());
        }

        $oilLosses = $query->paginate(15);

        // Get statistics
        $statistics = [
            'total_records' => LabCalculation::count(),
            'records_today' => LabCalculation::whereDate('created_at', today())->count(),
        ];

        return view('lab.index', compact('oilLosses', 'statistics'));
    }

    /**
     * Show the form for creating a new lab record with dual-mode input.
     */
    public function create()
    {
        $this->authorize('create lab results');

        // Get dropdown options from master data
        $kodeOptions = LabMasterData::getKodeDropdown();
        $jenisOptions = LabMasterData::getJenisDropdown();

        return view('lab.create', compact('kodeOptions', 'jenisOptions'));
    }

    /**
     * Store lab data with dual-mode input (non-numeric or numeric).
     * Tanggal dan jam otomatis dari server saat submit (mencegah manipulasi).
     */
    public function store(Request $request)
    {
        $this->authorize('create lab results');

        // Dual-mode validation:
        // Mode 1 (Non-Numeric): kode (without manual time input)
        // Mode 2 (Numeric): cawan_kosong + berat_basah
        $validated = $request->validate([
            // Mode 1 fields
            'kode' => 'nullable|string|exists:lab_master_data,kode',
            'jenis' => 'nullable|string',
            'operator' => 'nullable|string|max:255',
            'sampel_boy' => 'nullable|string|max:255',
            'parameter_lain' => 'nullable|string|max:500',
            // Mode 2 fields
            'cawan_kosong' => 'nullable|numeric|min:0',
            'berat_basah' => 'nullable|numeric|min:0',
            'cawan_sample_kering' => 'nullable|numeric|min:0',
            'labu_kosong' => 'nullable|numeric|min:0',
            'oil_labu' => 'nullable|numeric|min:0',
        ], [
            'kode.exists' => 'Kode tidak valid atau tidak ditemukan di master data',
        ]);

        try {
            // Set tanggal dan jam otomatis dari server (tidak bisa dimanipulasi user)
            $validated['analysis_date'] = now()->format('Y-m-d');
            $validated['analysis_time'] = now()->format('H:i');

            $result = $this->labService->store($validated, Auth::id());

            return redirect()
                ->route('lab.index')
                ->with('success', $result['message']);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified lab calculation with results.
     */
    public function show(LabCalculation $labCalculation)
    {
        $this->authorize('view lab results');

        $labCalculation->load(['user']);

        return view('lab.show', ['oilLoss' => $labCalculation]);
    }

    /**
     * Show the form for editing the specified lab record.
     */
    public function edit(LabCalculation $labCalculation)
    {
        $this->authorize('edit lab results');

        $kodeOptions = LabMasterData::getKodeDropdown();
        $jenisOptions = LabMasterData::getJenisDropdown();

        return view('lab.edit', [
            'oilLoss' => $labCalculation,
            'kodeOptions' => $kodeOptions,
            'jenisOptions' => $jenisOptions,
        ]);
    }

    /**
     * Update the specified lab calculation (numeric data only).
     * Non-numeric data should be managed separately via LabRecord.
     */
    public function update(Request $request, LabCalculation $labCalculation)
    {
        $this->authorize('edit lab results');

        $validated = $request->validate([
            'analysis_date' => 'required|date|before_or_equal:today',
            'cawan_kosong' => 'required|numeric|min:0',
            'berat_basah' => 'required|numeric|min:0',
            'cawan_sample_kering' => 'required|numeric|min:0',
            'labu_kosong' => 'required|numeric|min:0',
            'oil_labu' => 'required|numeric|min:0',
        ]);

        try {
            // Re-calculate and update
            $result = $this->labService->store($validated, Auth::id());

            return redirect()
                ->route('lab.show', $labCalculation->id)
                ->with('success', 'Data berhasil diupdate!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified lab calculation.
     */
    public function destroy(LabCalculation $labCalculation)
    {
        $this->authorize('delete lab results');

        $labCalculation->delete();

        return redirect()
            ->route('lab.index')
            ->with('success', 'Data berhasil dihapus!');
    }

    /**
     * Export oil loss data.
     */
    public function export(Request $request)
    {
        $this->authorize('export lab data');

        // TODO: Implement export to Excel/PDF
        return back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }
}

