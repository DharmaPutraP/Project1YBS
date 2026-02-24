<?php

namespace App\Http\Controllers;

use App\Models\OilLoss;
use App\Services\OilLossService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabController extends Controller
{
    protected OilLossService $oilLossService;

    public function __construct(OilLossService $oilLossService)
    {
        $this->oilLossService = $oilLossService;
    }

    /**
     * Display a listing of oil loss records.
     */
    public function index(Request $request)
    {
        $query = OilLoss::with(['user', 'approver'])
            ->orderBy('analysis_date', 'desc')
            ->orderBy('analysis_time', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->status($request->status);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->dateBetween($request->start_date, $request->end_date);
        }

        // Check permission - if user can only view own results
        if (!Auth::user()->can('view lab results') && Auth::user()->can('view own lab results')) {
            $query->where('user_id', Auth::id());
        }

        $oilLosses = $query->paginate(15);

        // Get statistics
        $statistics = [
            'total_records' => OilLoss::count(),
            'pending_approval' => OilLoss::status('submitted')->count(),
            'approved_today' => OilLoss::status('approved')
                ->whereDate('approved_at', today())
                ->count(),
        ];

        return view('lab.index', compact('oilLosses', 'statistics'));
    }

    /**
     * Show the form for creating a new oil loss record.
     */
    public function create()
    {
        $this->authorize('create lab results');

        return view('lab.create', [
            'standardOER' => OilLossService::getStandardOER(),
            'standardKER' => OilLossService::getStandardKER(),
        ]);
    }

    /**
     * Store a newly created oil loss record.
     */
    public function store(Request $request)
    {
        $this->authorize('create lab results');

        $validated = $request->validate([
            'analysis_date' => 'required|date|before_or_equal:today',
            'analysis_time' => 'required|date_format:H:i',
            'tbs_weight' => 'required|numeric|min:0.01',
            'moisture_content' => 'nullable|numeric|min:0|max:100',
            'ffa_content' => 'nullable|numeric|min:0|max:100',
            'cpo_produced' => 'required|numeric|min:0',
            'kernel_produced' => 'nullable|numeric|min:0',
            'batch_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ], [
            'analysis_date.required' => 'Tanggal analisa harus diisi',
            'analysis_date.before_or_equal' => 'Tanggal analisa tidak boleh di masa depan',
            'analysis_time.required' => 'Jam analisa harus diisi',
            'tbs_weight.required' => 'Berat TBS harus diisi',
            'tbs_weight.min' => 'Berat TBS harus lebih dari 0',
            'cpo_produced.required' => 'Jumlah CPO yang dihasilkan harus diisi',
        ]);

        try {
            $oilLoss = $this->oilLossService->store($validated, Auth::id());

            return redirect()
                ->route('lab.show', $oilLoss->id)
                ->with('success', 'Data oil losses berhasil disimpan dan menunggu approval!');

        } catch (\InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified oil loss record.
     */
    public function show(OilLoss $oilLoss)
    {
        $this->authorize('view lab results');

        $oilLoss->load(['user', 'approver']);

        return view('lab.show', compact('oilLoss'));
    }

    /**
     * Show the form for editing the specified oil loss record.
     */
    public function edit(OilLoss $oilLoss)
    {
        $this->authorize('edit lab results');

        if (!$oilLoss->canBeEdited()) {
            return redirect()
                ->route('lab.show', $oilLoss->id)
                ->with('error', 'Data yang sudah diapprove tidak dapat diedit.');
        }

        return view('lab.edit', [
            'oilLoss' => $oilLoss,
            'standardOER' => OilLossService::getStandardOER(),
            'standardKER' => OilLossService::getStandardKER(),
        ]);
    }

    /**
     * Update the specified oil loss record.
     */
    public function update(Request $request, OilLoss $oilLoss)
    {
        $this->authorize('edit lab results');

        $validated = $request->validate([
            'analysis_date' => 'required|date|before_or_equal:today',
            'analysis_time' => 'required|date_format:H:i',
            'tbs_weight' => 'required|numeric|min:0.01',
            'moisture_content' => 'nullable|numeric|min:0|max:100',
            'ffa_content' => 'nullable|numeric|min:0|max:100',
            'cpo_produced' => 'required|numeric|min:0',
            'kernel_produced' => 'nullable|numeric|min:0',
            'batch_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->oilLossService->update($oilLoss, $validated);

            return redirect()
                ->route('lab.show', $oilLoss->id)
                ->with('success', 'Data oil losses berhasil diupdate!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified oil loss record.
     */
    public function destroy(OilLoss $oilLoss)
    {
        $this->authorize('delete lab results');

        if (!$oilLoss->canBeEdited()) {
            return back()->with('error', 'Data yang sudah diapprove tidak dapat dihapus.');
        }

        $oilLoss->delete();

        return redirect()
            ->route('lab.index')
            ->with('success', 'Data oil losses berhasil dihapus!');
    }

    /**
     * Approve oil loss record.
     */
    public function approve(OilLoss $oilLoss)
    {
        $this->authorize('approve lab results');

        try {
            $this->oilLossService->approve($oilLoss, Auth::id());

            return back()->with('success', 'Data oil losses berhasil diapprove!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject oil loss record.
     */
    public function reject(Request $request, OilLoss $oilLoss)
    {
        $this->authorize('reject lab results');

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            $this->oilLossService->reject($oilLoss, $validated['reason']);

            return back()->with('success', 'Data oil losses berhasil direject!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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

