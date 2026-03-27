<?php

namespace App\Http\Controllers;

use App\Exports\KernelDestonerExport;
use App\Exports\KernelDirtMoistExport;
use App\Exports\KernelLaporanExport;
use App\Exports\KernelPerformanceExport;
use App\Exports\KernelQwtExport;
use App\Exports\KernelRekapExport;
use App\Exports\KernelRippleMillExport;
use App\Models\KernelBobotConfig;
use App\Models\KernelCalculation;
use App\Models\KernelDirtMoistCalculation;
use App\Models\KernelMasterData;
use App\Models\KernelQwt;
use App\Models\KernelDestoner;
use App\Models\KernelRippleMill;
use App\Models\KernelRecord;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class KernelController extends Controller
{
    use LogsActivity;

    private array $pengulanganColumnCache = [];

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $officeFilter = $this->resolveOfficeFilter($request);

        $calculationsQuery = KernelCalculation::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        $this->applyOfficeFilter($calculationsQuery, $officeFilter);

        if ($request->filled('kode')) {
            $calculationsQuery->where('kode', $request->kode);
        }

        if (!Auth::user()->can('view kernel losses')) {
            $calculationsQuery->where('user_id', Auth::id());
        }

        $totalCalculations = (clone $calculationsQuery)->count();

        $statistics = [
            'total_records' => $totalCalculations,
            'records_today' => KernelCalculation::whereDate('created_at', today())
                ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
                ->count(),
            'calculations_count' => $totalCalculations,
        ];

        $kernelCalculations = $calculationsQuery->paginate(15, ['*'], 'calculations');

        $kernelCalculations->appends($request->only(['start_date', 'end_date', 'kode', 'office']));

        $kodeOptions = KernelMasterData::getKodeDropdown();
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        return view('kernel.index', compact(
            'kernelCalculations',
            'statistics',
            'kodeOptions',
            'masterData',
            'startDate',
            'endDate',
            'officeFilter'
        ));
    }

    public function create()
    {
        if (!Auth::user()->can('create kernel losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data kernel losses.');
        }

        $kodeOptions = $this->getKernelLossesKodeOptions();
        $jenisOptions = KernelRecord::getJenisOptions();

        $kernelLimitMap = KernelMasterData::where('is_active', true)
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->kode => [
                        'operator' => $item->limit_operator,
                        'value' => (float) $item->limit_value,
                    ],
                ];
            })
            ->toArray();

        $operatorOptions = $this->getOperatorOptionsByOffice($this->getUserOffice(), 'kernel');

        return view('kernel.create', compact('kodeOptions', 'jenisOptions', 'kernelLimitMap', 'operatorOptions'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('create kernel losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data kernel losses.');
        }

        $userOffice = $this->getUserOffice();
        if (!$userOffice) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda harus memiliki Office yang ditentukan untuk dapat input data.'], 422);
            }
            return back()
                ->withInput()
                ->with('error', 'Anda harus memiliki Office yang ditentukan untuk dapat input data. Silakan hubungi Administrator.');
        }

        $validated = $request->validate([
            'kode' => ['required', 'string', Rule::in(array_keys($this->getKernelLossesKodeOptions()))],
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($userOffice, true, 'kernel'),
            'sampel_boy' => 'required|string|max:255',
            'pengulangan' => 'nullable|boolean',
            'parameter_lain' => 'nullable|string|max:500',
            'berat_sampel' => 'required|numeric|min:0',
            'nut_utuh_nut' => 'required|numeric|min:0',
            'nut_utuh_kernel' => 'required|numeric|min:0',
            'nut_pecah_nut' => 'required|numeric|min:0',
            'nut_pecah_kernel' => 'required|numeric|min:0',
            'kernel_utuh' => 'required|numeric|min:0',
            'kernel_pecah' => 'required|numeric|min:0',
        ], [
            'kode.required' => 'Kode wajib dipilih.',
            'kode.in' => 'Kode tidak valid untuk input Kernel Losses.',
            'jenis.required' => 'Jenis wajib dipilih.',
            'operator.required' => 'Operator wajib diisi.',
            'operator.in' => 'Operator tidak sesuai dengan daftar office.',
            'sampel_boy.required' => 'Sampel Boy wajib diisi.',
            'berat_sampel.required' => 'Berat Sampel wajib diisi.',
            'nut_utuh_nut.required' => 'Nut Utuh - Nut wajib diisi.',
            'nut_utuh_kernel.required' => 'Nut Utuh - Kernel wajib diisi.',
            'nut_pecah_nut.required' => 'Nut Pecah - Nut wajib diisi.',
            'nut_pecah_kernel.required' => 'Nut Pecah - Kernel wajib diisi.',
            'kernel_utuh.required' => 'Kernel Utuh wajib diisi.',
            'kernel_pecah.required' => 'Kernel Pecah wajib diisi.',
        ]);

        $isPengulangan = (bool) $request->boolean('pengulangan');
        $this->validatePengulanganWindow(
            KernelCalculation::class,
            $validated['kode'],
            $userOffice,
            $isPengulangan,
            'Kernel Losses'
        );

        // Save calculation record (all ratios stored as percentages ×100)
        $beratSampel = $validated['berat_sampel'];
        $ktsNutUtuh = $beratSampel > 0 ? round(($validated['nut_utuh_kernel'] / $beratSampel) * 100, 6) : 0;
        $ktsNutPecah = $beratSampel > 0 ? round(($validated['nut_pecah_kernel'] / $beratSampel) * 100, 6) : 0;
        $kernelUtuhToSampel = $beratSampel > 0 ? round(($validated['kernel_utuh'] / $beratSampel) * 100, 6) : 0;
        $kernelPecahToSampel = $beratSampel > 0 ? round(($validated['kernel_pecah'] / $beratSampel) * 100, 6) : 0;
        $kernelLosses = ($ktsNutUtuh + $ktsNutPecah + $kernelUtuhToSampel + $kernelPecahToSampel) / 100;

        $kernelCalculation = KernelCalculation::create([
            'user_id' => Auth::id(),
            'office' => $userOffice,
            'rounded_time' => $this->getRoundedTime(),
            'kode' => $validated['kode'],
            'jenis' => $validated['jenis'],
            'operator' => $validated['operator'],
            'sampel_boy' => $validated['sampel_boy'],
            'pengulangan' => $isPengulangan,
            'berat_sampel' => $beratSampel,
            'nut_utuh_nut' => $validated['nut_utuh_nut'],
            'nut_utuh_kernel' => $validated['nut_utuh_kernel'],
            'nut_pecah_nut' => $validated['nut_pecah_nut'],
            'nut_pecah_kernel' => $validated['nut_pecah_kernel'],
            'kernel_utuh' => $validated['kernel_utuh'],
            'kernel_pecah' => $validated['kernel_pecah'],
            'kernel_to_sampel_nut_utuh' => $ktsNutUtuh,
            'kernel_to_sampel_nut_pecah' => $ktsNutPecah,
            'kernel_utuh_to_sampel' => $kernelUtuhToSampel,
            'kernel_pecah_to_sampel' => $kernelPecahToSampel,
            'kernel_losses' => $kernelLosses,
        ]);

        $this->logCreate(
            $kernelCalculation,
            "Input data kernel losses untuk kode {$kernelCalculation->kode}",
            ['module' => 'kernel_losses', 'office' => $userOffice]
        );

        $message = 'Data Kernel Losses berhasil disimpan.';
        $proofData = $this->buildKernelLossesProofData($kernelCalculation, $message);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'proof' => $proofData]);
        }

        if ($request->input('_action') === 'save_and_add') {
            return redirect()->route('kernel.create')->with('success', $message);
        }

        return redirect()->route('kernel.index')
            ->with('success', $message)
            ->with('success_proof', $proofData);
    }

    public function edit(KernelCalculation $kernelCalculation)
    {
        $this->ensurePpicRole();

        $kodeOptions = $this->getKernelLossesKodeOptions();
        $jenisOptions = KernelRecord::getJenisOptions();
        $operatorOptions = $this->getOperatorOptionsByOffice($kernelCalculation->office, 'kernel');

        return view('kernel.edit', compact('kernelCalculation', 'kodeOptions', 'jenisOptions', 'operatorOptions'));
    }

    public function update(Request $request, KernelCalculation $kernelCalculation)
    {
        $this->ensurePpicRole();

        $oldAttributes = $kernelCalculation->getOriginal();

        $validated = $request->validate([
            'kode' => ['required', 'string', Rule::in(array_keys($this->getKernelLossesKodeOptions()))],
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($kernelCalculation->office, true, 'kernel'),
            'sampel_boy' => 'required|string|max:255',
            'berat_sampel' => 'required|numeric|min:0',
            'nut_utuh_nut' => 'required|numeric|min:0',
            'nut_utuh_kernel' => 'required|numeric|min:0',
            'nut_pecah_nut' => 'required|numeric|min:0',
            'nut_pecah_kernel' => 'required|numeric|min:0',
            'kernel_utuh' => 'required|numeric|min:0',
            'kernel_pecah' => 'required|numeric|min:0',
        ]);

        $beratSampel = $validated['berat_sampel'];
        $ktsNutUtuh = $beratSampel > 0 ? round(($validated['nut_utuh_kernel'] / $beratSampel) * 100, 6) : 0;
        $ktsNutPecah = $beratSampel > 0 ? round(($validated['nut_pecah_kernel'] / $beratSampel) * 100, 6) : 0;
        $kernelUtuhToSampel = $beratSampel > 0 ? round(($validated['kernel_utuh'] / $beratSampel) * 100, 6) : 0;
        $kernelPecahToSampel = $beratSampel > 0 ? round(($validated['kernel_pecah'] / $beratSampel) * 100, 6) : 0;
        $kernelLosses = ($ktsNutUtuh + $ktsNutPecah + $kernelUtuhToSampel + $kernelPecahToSampel) / 100;

        $kernelCalculation->update([
            'user_id' => Auth::id(),
            'kode' => $validated['kode'],
            'jenis' => $validated['jenis'],
            'operator' => $validated['operator'],
            'sampel_boy' => $validated['sampel_boy'],
            'berat_sampel' => $beratSampel,
            'nut_utuh_nut' => $validated['nut_utuh_nut'],
            'nut_utuh_kernel' => $validated['nut_utuh_kernel'],
            'nut_pecah_nut' => $validated['nut_pecah_nut'],
            'nut_pecah_kernel' => $validated['nut_pecah_kernel'],
            'kernel_utuh' => $validated['kernel_utuh'],
            'kernel_pecah' => $validated['kernel_pecah'],
            'kernel_to_sampel_nut_utuh' => $ktsNutUtuh,
            'kernel_to_sampel_nut_pecah' => $ktsNutPecah,
            'kernel_utuh_to_sampel' => $kernelUtuhToSampel,
            'kernel_pecah_to_sampel' => $kernelPecahToSampel,
            'kernel_losses' => $kernelLosses,
        ]);

        $this->logUpdate(
            $kernelCalculation,
            $oldAttributes,
            "Update data kernel losses kode {$kernelCalculation->kode}",
            ['module' => 'kernel_losses', 'office' => $kernelCalculation->office]
        );

        return redirect()->route('kernel.index')->with('success', 'Data Kernel Losses berhasil diperbarui.');
    }

    public function dirtMoistIndex(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $officeFilter = $this->resolveOfficeFilter($request);

        $query = KernelDirtMoistCalculation::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        $this->applyOfficeFilter($query, $officeFilter);

        if ($request->filled('kode')) {
            $query->where('kode', $request->kode);
        }

        if (!Auth::user()->can('view kernel losses')) {
            $query->where('user_id', Auth::id());
        }

        $totalRows = (clone $query)->count();

        $statistics = [
            'total_records' => $totalRows,
            'records_today' => KernelDirtMoistCalculation::whereDate('created_at', today())
                ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
                ->count(),
            'calculations_count' => $totalRows,
        ];

        $dirtMoistCalculations = $query->paginate(15, ['*'], 'calculations');
        $dirtMoistCalculations->appends($request->only(['start_date', 'end_date', 'kode', 'office']));

        $kodeOptions = $this->getDirtMoistKodeOptions();
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        return view('kernel.dirt-moist.index', compact(
            'dirtMoistCalculations',
            'statistics',
            'kodeOptions',
            'masterData',
            'startDate',
            'endDate',
            'officeFilter'
        ));
    }

    public function dirtMoistCreate()
    {
        if (!Auth::user()->can('create kernel losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data dirt & moist.');
        }

        $kodeOptions = $this->getDirtMoistKodeOptions();
        $jenisOptions = KernelRecord::getJenisOptions();
        $kernelLimitMap = $this->getDirtMoistLimitMap();
        $operatorOptions = $this->getOperatorOptionsByOffice($this->getUserOffice(), 'dirt_moist');

        return view('kernel.dirt-moist.create', compact('kodeOptions', 'jenisOptions', 'kernelLimitMap', 'operatorOptions'));
    }

    public function dirtMoistStore(Request $request)
    {
        if (!Auth::user()->can('create kernel losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data dirt & moist.');
        }

        $userOffice = $this->getUserOffice();
        if (!$userOffice) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda harus memiliki Office yang ditentukan untuk dapat input data.'], 422);
            }
            return back()
                ->withInput()
                ->with('error', 'Anda harus memiliki Office yang ditentukan untuk dapat input data. Silakan hubungi Administrator.');
        }

        $validated = $request->validate([
            'kode' => 'required|string',
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($userOffice, true, 'dirt_moist'),
            'sampel_boy' => 'nullable|string|max:255',
            'pengulangan' => 'nullable|boolean',
            'berat_sampel' => 'required|numeric|gt:0',
            'berat_dirty' => 'required|numeric|min:0',
            'moist_percent' => 'required|numeric|min:0',
        ], [
            'kode.required' => 'Kode wajib dipilih.',
            'jenis.required' => 'Jenis wajib dipilih.',
            'operator.required' => 'Operator wajib diisi.',
            'operator.in' => 'Operator tidak sesuai dengan daftar office.',
            'berat_sampel.required' => 'Berat sampel wajib diisi.',
            'berat_sampel.gt' => 'Berat sampel harus lebih dari 0.',
            'berat_dirty.required' => 'Berat dirty wajib diisi.',
            'moist_percent.required' => 'Moist wajib diisi.',
        ]);

        $isPengulangan = (bool) $request->boolean('pengulangan');
        $this->validatePengulanganWindow(
            KernelDirtMoistCalculation::class,
            $validated['kode'],
            $userOffice,
            $isPengulangan,
            'Dirt & Moist'
        );

        $dirtyToSampel = round(($validated['berat_dirty'] / $validated['berat_sampel']) * 100, 6);

        $limitMap = $this->getDirtMoistLimitMap();
        $limitConfig = $limitMap[$validated['kode']] ?? ['dirty' => null, 'moist' => null];
        $dirtyLimitOperator = data_get($limitConfig, 'dirty.operator');
        $dirtyLimitValue = data_get($limitConfig, 'dirty.value');
        $moistLimitOperator = data_get($limitConfig, 'moist.operator');
        $moistLimitValue = data_get($limitConfig, 'moist.value');

        $dirtMoistCalculation = KernelDirtMoistCalculation::create([
            'user_id' => Auth::id(),
            'office' => $userOffice,
            'rounded_time' => $this->getRoundedTime(),
            'kode' => $validated['kode'],
            'jenis' => $validated['jenis'],
            'operator' => $validated['operator'],
            'sampel_boy' => $validated['sampel_boy'] ?? Auth::user()->name,
            'pengulangan' => $isPengulangan,
            'berat_sampel' => $validated['berat_sampel'],
            'berat_dirty' => $validated['berat_dirty'],
            'dirty_to_sampel' => $dirtyToSampel,
            'moist_percent' => $validated['moist_percent'],
            'dirty_limit_operator' => $dirtyLimitOperator,
            'dirty_limit_value' => $dirtyLimitValue,
            'moist_limit_operator' => $moistLimitOperator,
            'moist_limit_value' => $moistLimitValue,
        ]);

        $this->logCreate(
            $dirtMoistCalculation,
            "Input data dirt & moist untuk kode {$dirtMoistCalculation->kode}",
            ['module' => 'dirt_moist', 'office' => $userOffice]
        );

        $message = 'Data dirt & moist berhasil disimpan.';
        $proofData = $this->buildDirtMoistProofData($dirtMoistCalculation, $message);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'proof' => $proofData]);
        }

        if ($request->input('_action') === 'save_and_add') {
            return redirect()->route('kernel.dirt-moist.create')->with('success', $message);
        }

        return redirect()->route('kernel.dirt-moist.index')
            ->with('success', $message)
            ->with('success_proof', $proofData);
    }

    public function dirtMoistEdit(KernelDirtMoistCalculation $dirtMoistCalculation)
    {
        $this->ensurePpicRole();

        $kodeOptions = $this->getDirtMoistKodeOptions();
        $jenisOptions = KernelRecord::getJenisOptions();
        $operatorOptions = $this->getOperatorOptionsByOffice($dirtMoistCalculation->office, 'dirt_moist');

        return view('kernel.dirt-moist.edit', compact('dirtMoistCalculation', 'kodeOptions', 'jenisOptions', 'operatorOptions'));
    }

    public function dirtMoistUpdate(Request $request, KernelDirtMoistCalculation $dirtMoistCalculation)
    {
        $this->ensurePpicRole();

        $oldAttributes = $dirtMoistCalculation->getOriginal();

        $validated = $request->validate([
            'kode' => 'required|string',
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($dirtMoistCalculation->office, true, 'dirt_moist'),
            'sampel_boy' => 'nullable|string|max:255',
            'berat_sampel' => 'required|numeric|gt:0',
            'berat_dirty' => 'required|numeric|min:0',
            'moist_percent' => 'required|numeric|min:0',
        ]);

        $dirtyToSampel = round(($validated['berat_dirty'] / $validated['berat_sampel']) * 100, 6);
        $limitMap = $this->getDirtMoistLimitMap();
        $limitConfig = $limitMap[$validated['kode']] ?? ['dirty' => null, 'moist' => null];

        $dirtMoistCalculation->update([
            'user_id' => Auth::id(),
            'kode' => $validated['kode'],
            'jenis' => $validated['jenis'],
            'operator' => $validated['operator'],
            'sampel_boy' => $validated['sampel_boy'] ?? $dirtMoistCalculation->sampel_boy,
            'berat_sampel' => $validated['berat_sampel'],
            'berat_dirty' => $validated['berat_dirty'],
            'dirty_to_sampel' => $dirtyToSampel,
            'moist_percent' => $validated['moist_percent'],
            'dirty_limit_operator' => data_get($limitConfig, 'dirty.operator'),
            'dirty_limit_value' => data_get($limitConfig, 'dirty.value'),
            'moist_limit_operator' => data_get($limitConfig, 'moist.operator'),
            'moist_limit_value' => data_get($limitConfig, 'moist.value'),
        ]);

        $this->logUpdate(
            $dirtMoistCalculation,
            $oldAttributes,
            "Update data dirt & moist kode {$dirtMoistCalculation->kode}",
            ['module' => 'dirt_moist', 'office' => $dirtMoistCalculation->office]
        );

        return redirect()->route('kernel.dirt-moist.index')->with('success', 'Data Dirt & Moist berhasil diperbarui.');
    }

    public function dirtMoistDestroy(KernelDirtMoistCalculation $dirtMoistCalculation)
    {
        $this->ensurePpicRole();

        $this->logDelete(
            $dirtMoistCalculation,
            "Hapus data dirt & moist kode {$dirtMoistCalculation->kode}",
            ['module' => 'dirt_moist', 'office' => $dirtMoistCalculation->office]
        );

        $dirtMoistCalculation->delete();

        return back()->with('success', 'Data Dirt & Moist berhasil dihapus.');
    }

    public function qwtIndex(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $officeFilter = $this->resolveOfficeFilter($request);

        $query = KernelQwt::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        $this->applyOfficeFilter($query, $officeFilter);

        if ($request->filled('kode')) {
            $query->where('kode', $request->kode);
        }

        if (!Auth::user()->can('view kernel losses')) {
            $query->where('user_id', Auth::id());
        }

        $totalRows = (clone $query)->count();

        $statistics = [
            'total_records' => $totalRows,
            'records_today' => KernelQwt::whereDate('created_at', today())
                ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
                ->count(),
            'calculations_count' => $totalRows,
        ];

        $kernelQwtRows = $query->paginate(15, ['*'], 'calculations');
        $kernelQwtRows->appends($request->only(['start_date', 'end_date', 'kode', 'office']));

        $kodeOptions = $this->getQwtKodeOptions();
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        return view('kernel.qwt.index', compact(
            'kernelQwtRows',
            'statistics',
            'kodeOptions',
            'masterData',
            'startDate',
            'endDate',
            'officeFilter'
        ));
    }

    public function qwtCreate()
    {
        if (!Auth::user()->can('create kernel losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data QWT Fibre Press.');
        }

        $kodeOptions = $this->getQwtKodeOptions();
        $jenisOptions = KernelRecord::getJenisOptions();
        $kernelLimitMap = $this->getQwtLimitMap();
        $operatorOptions = $this->getOperatorOptionsByOffice($this->getUserOffice(), 'qwt');

        return view('kernel.qwt.create', compact('kodeOptions', 'jenisOptions', 'kernelLimitMap', 'operatorOptions'));
    }

    public function qwtStore(Request $request)
    {
        if (!Auth::user()->can('create kernel losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data QWT Fibre Press.');
        }

        $userOffice = $this->getUserOffice();
        if (!$userOffice) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda harus memiliki Office yang ditentukan untuk dapat input data.'], 422);
            }
            return back()
                ->withInput()
                ->with('error', 'Anda harus memiliki Office yang ditentukan untuk dapat input data. Silakan hubungi Administrator.');
        }

        $validated = $request->validate([
            'kode' => 'required|string',
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($userOffice, true, 'qwt'),
            'sampel_boy' => 'required|string|max:255',
            'pengulangan' => 'nullable|boolean',
            'sampel_setelah_kuarter' => 'required|numeric|gt:0',
            'berat_nut_utuh' => 'required|numeric|min:0',
            'berat_nut_pecah' => 'required|numeric|min:0',
            'berat_kernel_utuh' => 'required|numeric|min:0',
            'berat_kernel_pecah' => 'required|numeric|min:0',
            'berat_cangkang' => 'required|numeric|min:0',
            'berat_batu' => 'required|numeric|min:0',
            'moisture' => 'required|numeric|min:0',
            'ampere_screw' => 'required|numeric|min:0',
            'tekanan_hydraulic' => 'required|numeric|min:0',
            'kecepatan_screw' => 'required|numeric|min:0',
        ], [
            'kode.required' => 'Kode wajib dipilih.',
            'jenis.required' => 'Jenis wajib dipilih.',
            'operator.required' => 'Operator wajib diisi.',
            'operator.in' => 'Operator tidak sesuai dengan daftar office.',
            'sampel_boy.required' => 'Sampel Boy wajib diisi.',
            'sampel_setelah_kuarter.required' => 'Sampel setelah kuarter wajib diisi.',
            'sampel_setelah_kuarter.gt' => 'Sampel setelah kuarter harus lebih dari 0.',
            'berat_nut_utuh.required' => 'Berat nut utuh wajib diisi.',
            'berat_nut_pecah.required' => 'Berat nut pecah wajib diisi.',
            'berat_kernel_utuh.required' => 'Berat kernel utuh wajib diisi.',
            'berat_kernel_pecah.required' => 'Berat kernel pecah wajib diisi.',
            'berat_cangkang.required' => 'Berat cangkang wajib diisi.',
            'berat_batu.required' => 'Berat batu wajib diisi.',
            'moisture.required' => 'Moisture wajib diisi.',
            'ampere_screw.required' => 'Ampere screw wajib diisi.',
            'tekanan_hydraulic.required' => 'Tekanan hydraulic wajib diisi.',
            'kecepatan_screw.required' => 'Kecepatan screw wajib diisi.',
        ]);

        $isPengulangan = (bool) $request->boolean('pengulangan');
        $this->validatePengulanganWindow(
            KernelQwt::class,
            $validated['kode'],
            $userOffice,
            $isPengulangan,
            'QWT Fibre Press'
        );

        $totalBeratNut = round(
            $validated['berat_nut_utuh']
            + $validated['berat_nut_pecah']
            + $validated['berat_kernel_utuh']
            + $validated['berat_kernel_pecah']
            + $validated['berat_cangkang'],
            6
        );

        $beratFiber = round($validated['sampel_setelah_kuarter'] - $totalBeratNut, 6);
        $beratBrokenNut = round(
            $validated['berat_nut_pecah']
            + $validated['berat_kernel_utuh']
            + $validated['berat_kernel_pecah']
            + $validated['berat_cangkang'],
            6
        );
        $bnTn = $totalBeratNut > 0 ? round(($beratBrokenNut / $totalBeratNut) * 100, 6) : 0;

        $limitMap = $this->getQwtLimitMap();
        $kode = $validated['kode'];

        $kernelQwt = KernelQwt::create([
            'user_id' => Auth::id(),
            'office' => $userOffice,
            'rounded_time' => $this->getRoundedTime(),
            'kode' => $kode,
            'jenis' => $validated['jenis'],
            'operator' => $validated['operator'],
            'sampel_boy' => $validated['sampel_boy'],
            'pengulangan' => $isPengulangan,
            'sampel_setelah_kuarter' => $validated['sampel_setelah_kuarter'],
            'berat_nut_utuh' => $validated['berat_nut_utuh'],
            'berat_nut_pecah' => $validated['berat_nut_pecah'],
            'berat_kernel_utuh' => $validated['berat_kernel_utuh'],
            'berat_kernel_pecah' => $validated['berat_kernel_pecah'],
            'berat_cangkang' => $validated['berat_cangkang'],
            'berat_batu' => $validated['berat_batu'],
            'berat_fiber' => $beratFiber,
            'berat_broken_nut' => $beratBrokenNut,
            'total_berat_nut' => $totalBeratNut,
            'bn_tn' => $bnTn,
            'moisture' => $validated['moisture'],
            'ampere_screw' => $validated['ampere_screw'],
            'tekanan_hydraulic' => $validated['tekanan_hydraulic'],
            'kecepatan_screw' => $validated['kecepatan_screw'],
            'bn_tn_limit_operator' => data_get($limitMap, $kode . '.bn_tn.operator', 'le'),
            'bn_tn_limit_value' => data_get($limitMap, $kode . '.bn_tn.value'),
            'moist_limit_operator' => data_get($limitMap, $kode . '.moist.operator', 'le'),
            'moist_limit_value' => data_get($limitMap, $kode . '.moist.value'),
        ]);

        $this->logCreate(
            $kernelQwt,
            "Input data QWT Fibre Press untuk kode {$kernelQwt->kode}",
            ['module' => 'qwt', 'office' => $userOffice]
        );

        $message = 'Data QWT Fibre Press berhasil disimpan.';
        $proofData = $this->buildQwtProofData($kernelQwt, $message);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'proof' => $proofData]);
        }

        if ($request->input('_action') === 'save_and_add') {
            return redirect()->route('kernel.qwt.create')->with('success', $message);
        }

        return redirect()->route('kernel.qwt.index')
            ->with('success', $message)
            ->with('success_proof', $proofData);
    }

    public function qwtEdit(KernelQwt $kernelQwt)
    {
        $this->ensurePpicRole();

        $kodeOptions = $this->getQwtKodeOptions();
        $jenisOptions = KernelRecord::getJenisOptions();
        $operatorOptions = $this->getOperatorOptionsByOffice($kernelQwt->office, 'qwt');

        return view('kernel.qwt.edit', compact('kernelQwt', 'kodeOptions', 'jenisOptions', 'operatorOptions'));
    }

    public function qwtUpdate(Request $request, KernelQwt $kernelQwt)
    {
        $this->ensurePpicRole();

        $oldAttributes = $kernelQwt->getOriginal();

        $validated = $request->validate([
            'kode' => 'required|string',
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($kernelQwt->office, true, 'qwt'),
            'sampel_boy' => 'required|string|max:255',
            'sampel_setelah_kuarter' => 'required|numeric|gt:0',
            'berat_nut_utuh' => 'required|numeric|min:0',
            'berat_nut_pecah' => 'required|numeric|min:0',
            'berat_kernel_utuh' => 'required|numeric|min:0',
            'berat_kernel_pecah' => 'required|numeric|min:0',
            'berat_cangkang' => 'required|numeric|min:0',
            'berat_batu' => 'required|numeric|min:0',
            'moisture' => 'required|numeric|min:0',
            'ampere_screw' => 'required|numeric|min:0',
            'tekanan_hydraulic' => 'required|numeric|min:0',
            'kecepatan_screw' => 'required|numeric|min:0',
        ]);

        $totalBeratNut = round(
            $validated['berat_nut_utuh']
            + $validated['berat_nut_pecah']
            + $validated['berat_kernel_utuh']
            + $validated['berat_kernel_pecah']
            + $validated['berat_cangkang'],
            6
        );

        $beratFiber = round($validated['sampel_setelah_kuarter'] - $totalBeratNut, 6);
        $beratBrokenNut = round(
            $validated['berat_nut_pecah']
            + $validated['berat_kernel_utuh']
            + $validated['berat_kernel_pecah']
            + $validated['berat_cangkang'],
            6
        );
        $bnTn = $totalBeratNut > 0 ? round(($beratBrokenNut / $totalBeratNut) * 100, 6) : 0;

        $limitMap = $this->getQwtLimitMap();
        $kode = $validated['kode'];

        $kernelQwt->update([
            'user_id' => Auth::id(),
            'kode' => $kode,
            'jenis' => $validated['jenis'],
            'operator' => $validated['operator'],
            'sampel_boy' => $validated['sampel_boy'],
            'sampel_setelah_kuarter' => $validated['sampel_setelah_kuarter'],
            'berat_nut_utuh' => $validated['berat_nut_utuh'],
            'berat_nut_pecah' => $validated['berat_nut_pecah'],
            'berat_kernel_utuh' => $validated['berat_kernel_utuh'],
            'berat_kernel_pecah' => $validated['berat_kernel_pecah'],
            'berat_cangkang' => $validated['berat_cangkang'],
            'berat_batu' => $validated['berat_batu'],
            'berat_fiber' => $beratFiber,
            'berat_broken_nut' => $beratBrokenNut,
            'total_berat_nut' => $totalBeratNut,
            'bn_tn' => $bnTn,
            'moisture' => $validated['moisture'],
            'ampere_screw' => $validated['ampere_screw'],
            'tekanan_hydraulic' => $validated['tekanan_hydraulic'],
            'kecepatan_screw' => $validated['kecepatan_screw'],
            'bn_tn_limit_operator' => data_get($limitMap, $kode . '.bn_tn.operator', 'le'),
            'bn_tn_limit_value' => data_get($limitMap, $kode . '.bn_tn.value'),
            'moist_limit_operator' => data_get($limitMap, $kode . '.moist.operator', 'le'),
            'moist_limit_value' => data_get($limitMap, $kode . '.moist.value'),
        ]);

        $this->logUpdate(
            $kernelQwt,
            $oldAttributes,
            "Update data QWT Fibre Press kode {$kernelQwt->kode}",
            ['module' => 'qwt', 'office' => $kernelQwt->office]
        );

        return redirect()->route('kernel.qwt.index')->with('success', 'Data QWT Fibre Press berhasil diperbarui.');
    }

    public function qwtDestroy(KernelQwt $kernelQwt)
    {
        $this->ensurePpicRole();

        $this->logDelete(
            $kernelQwt,
            "Hapus data QWT Fibre Press kode {$kernelQwt->kode}",
            ['module' => 'qwt', 'office' => $kernelQwt->office]
        );

        $kernelQwt->delete();

        return back()->with('success', 'Data QWT Fibre Press berhasil dihapus.');
    }

    public function rippleMillIndex(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $officeFilter = $this->resolveOfficeFilter($request);

        $query = KernelRippleMill::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        $this->applyOfficeFilter($query, $officeFilter);

        if ($request->filled('kode')) {
            $query->where('kode', $request->kode);
        }

        if (!Auth::user()->can('view kernel losses')) {
            $query->where('user_id', Auth::id());
        }

        $totalRows = (clone $query)->count();

        $statistics = [
            'total_records' => $totalRows,
            'records_today' => KernelRippleMill::whereDate('created_at', today())
                ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
                ->count(),
            'calculations_count' => $totalRows,
        ];

        $rippleMillRows = $query->paginate(15, ['*'], 'calculations');
        $rippleMillRows->appends($request->only(['start_date', 'end_date', 'kode', 'office']));

        $kodeOptions = $this->getRippleMillKodeOptions();
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        return view('kernel.ripple-mill.index', compact(
            'rippleMillRows',
            'statistics',
            'kodeOptions',
            'masterData',
            'startDate',
            'endDate',
            'officeFilter'
        ));
    }

    public function rippleMillCreate()
    {
        if (!Auth::user()->can('create kernel losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data Ripple Mill.');
        }

        $kodeOptions = $this->getRippleMillKodeOptions();
        $jenisOptions = KernelRecord::getJenisOptions();
        $kernelLimitMap = $this->getRippleMillLimitMap();
        $operatorOptions = $this->getOperatorOptionsByOffice($this->getUserOffice(), 'ripple_mill');

        return view('kernel.ripple-mill.create', compact('kodeOptions', 'jenisOptions', 'kernelLimitMap', 'operatorOptions'));
    }

    public function rippleMillStore(Request $request)
    {
        if (!Auth::user()->can('create kernel losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data Ripple Mill.');
        }

        $userOffice = $this->getUserOffice();
        if (!$userOffice) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda harus memiliki Office yang ditentukan untuk dapat input data.'], 422);
            }
            return back()
                ->withInput()
                ->with('error', 'Anda harus memiliki Office yang ditentukan untuk dapat input data. Silakan hubungi Administrator.');
        }

        $validated = $request->validate([
            'kode' => 'required|string',
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($userOffice, true, 'ripple_mill'),
            'sampel_boy' => 'required|string|max:255',
            'pengulangan' => 'nullable|boolean',
            'berat_sampel' => 'required|numeric|gt:0',
            'berat_nut_utuh' => 'required|numeric|min:0',
            'berat_nut_pecah' => 'required|numeric|min:0',
        ], [
            'kode.required' => 'Kode wajib dipilih.',
            'jenis.required' => 'Jenis wajib dipilih.',
            'operator.required' => 'Operator wajib diisi.',
            'operator.in' => 'Operator tidak sesuai dengan daftar office.',
            'sampel_boy.required' => 'Sampel Boy wajib diisi.',
            'berat_sampel.required' => 'Berat Sample wajib diisi.',
            'berat_sampel.gt' => 'Berat Sample harus lebih dari 0.',
            'berat_nut_utuh.required' => 'Berat nut utuh wajib diisi.',
            'berat_nut_pecah.required' => 'Berat nut pecah wajib diisi.',
        ]);

        $isPengulangan = (bool) $request->boolean('pengulangan');
        $this->validatePengulanganWindow(
            KernelRippleMill::class,
            $validated['kode'],
            $userOffice,
            $isPengulangan,
            'Ripple Mill'
        );

        $beratSampel = (float) $validated['berat_sampel'];
        $sampleNutUtuh = round(((float) $validated['berat_nut_utuh'] / $beratSampel) * 100, 6);
        $sampleNutPecah = round(((float) $validated['berat_nut_pecah'] / $beratSampel) * 100, 6);
        $efficiency = round(100 - $sampleNutPecah - $sampleNutUtuh, 6);

        $limitMap = $this->getRippleMillLimitMap();
        $kode = $validated['kode'];

        $kernelRippleMill = KernelRippleMill::create([
            'user_id' => Auth::id(),
            'office' => $userOffice,
            'rounded_time' => $this->getRoundedTime(),
            'kode' => $kode,
            'jenis' => $validated['jenis'],
            'operator' => $validated['operator'],
            'sampel_boy' => $validated['sampel_boy'],
            'pengulangan' => $isPengulangan,
            'berat_sampel' => $beratSampel,
            'berat_nut_utuh' => $validated['berat_nut_utuh'],
            'berat_nut_pecah' => $validated['berat_nut_pecah'],
            'sample_nut_utuh' => $sampleNutUtuh,
            'sample_nut_pecah' => $sampleNutPecah,
            'efficiency' => $efficiency,
            'limit_operator' => data_get($limitMap, $kode . '.operator', 'gt'),
            'limit_value' => data_get($limitMap, $kode . '.value'),
        ]);

        $this->logCreate(
            $kernelRippleMill,
            "Input data Ripple Mill untuk kode {$kernelRippleMill->kode}",
            ['module' => 'ripple_mill', 'office' => $userOffice]
        );

        $message = 'Data Ripple Mill berhasil disimpan.';
        $proofData = $this->buildRippleMillProofData($kernelRippleMill, $message);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'proof' => $proofData]);
        }

        if ($request->input('_action') === 'save_and_add') {
            return redirect()->route('kernel.ripple-mill.create')->with('success', $message);
        }

        return redirect()->route('kernel.ripple-mill.index')
            ->with('success', $message)
            ->with('success_proof', $proofData);
    }

    public function rippleMillEdit(KernelRippleMill $kernelRippleMill)
    {
        $this->ensurePpicRole();

        $kodeOptions = $this->getRippleMillKodeOptions();
        $jenisOptions = KernelRecord::getJenisOptions();
        $operatorOptions = $this->getOperatorOptionsByOffice($kernelRippleMill->office, 'ripple_mill');

        return view('kernel.ripple-mill.edit', compact('kernelRippleMill', 'kodeOptions', 'jenisOptions', 'operatorOptions'));
    }

    public function rippleMillUpdate(Request $request, KernelRippleMill $kernelRippleMill)
    {
        $this->ensurePpicRole();

        $oldAttributes = $kernelRippleMill->getOriginal();

        $validated = $request->validate([
            'kode' => 'required|string',
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($kernelRippleMill->office, true, 'ripple_mill'),
            'sampel_boy' => 'required|string|max:255',
            'berat_sampel' => 'required|numeric|gt:0',
            'berat_nut_utuh' => 'required|numeric|min:0',
            'berat_nut_pecah' => 'required|numeric|min:0',
        ]);

        $beratSampel = (float) $validated['berat_sampel'];
        $sampleNutUtuh = round(((float) $validated['berat_nut_utuh'] / $beratSampel) * 100, 6);
        $sampleNutPecah = round(((float) $validated['berat_nut_pecah'] / $beratSampel) * 100, 6);
        $efficiency = round(100 - $sampleNutPecah - $sampleNutUtuh, 6);

        $limitMap = $this->getRippleMillLimitMap();
        $kode = $validated['kode'];

        $kernelRippleMill->update([
            'user_id' => Auth::id(),
            'kode' => $kode,
            'jenis' => $validated['jenis'],
            'operator' => $validated['operator'],
            'sampel_boy' => $validated['sampel_boy'],
            'berat_sampel' => $beratSampel,
            'berat_nut_utuh' => $validated['berat_nut_utuh'],
            'berat_nut_pecah' => $validated['berat_nut_pecah'],
            'sample_nut_utuh' => $sampleNutUtuh,
            'sample_nut_pecah' => $sampleNutPecah,
            'efficiency' => $efficiency,
            'limit_operator' => data_get($limitMap, $kode . '.operator', 'gt'),
            'limit_value' => data_get($limitMap, $kode . '.value'),
        ]);

        $this->logUpdate(
            $kernelRippleMill,
            $oldAttributes,
            "Update data Ripple Mill kode {$kernelRippleMill->kode}",
            ['module' => 'ripple_mill', 'office' => $kernelRippleMill->office]
        );

        return redirect()->route('kernel.ripple-mill.index')->with('success', 'Data Ripple Mill berhasil diperbarui.');
    }

    public function rippleMillDestroy(KernelRippleMill $kernelRippleMill)
    {
        $this->ensurePpicRole();

        $this->logDelete(
            $kernelRippleMill,
            "Hapus data Ripple Mill kode {$kernelRippleMill->kode}",
            ['module' => 'ripple_mill', 'office' => $kernelRippleMill->office]
        );

        $kernelRippleMill->delete();

        return back()->with('success', 'Data Ripple Mill berhasil dihapus.');
    }

    public function destonerIndex(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $officeFilter = $this->resolveOfficeFilter($request);

        $query = KernelDestoner::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        $this->applyOfficeFilter($query, $officeFilter);

        if ($request->filled('kode')) {
            $query->where('kode', $request->kode);
        }

        if (!Auth::user()->can('view kernel losses')) {
            $query->where('user_id', Auth::id());
        }

        $totalRows = (clone $query)->count();

        $statistics = [
            'total_records' => $totalRows,
            'records_today' => KernelDestoner::whereDate('created_at', today())
                ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
                ->count(),
            'calculations_count' => $totalRows,
        ];

        $destonerRows = $query->paginate(15, ['*'], 'calculations');
        $destonerRows->appends($request->only(['start_date', 'end_date', 'kode', 'office']));

        $kodeOptions = $this->getDestonerKodeOptions();
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        return view('kernel.destoner.index', compact(
            'destonerRows',
            'statistics',
            'kodeOptions',
            'masterData',
            'startDate',
            'endDate',
            'officeFilter'
        ));
    }

    public function destonerCreate()
    {
        if (!Auth::user()->can('create kernel losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data Destoner.');
        }

        $kodeOptions = $this->getDestonerKodeOptions();
        $jenisOptions = KernelRecord::getJenisOptions();
        $kernelLimitMap = $this->getDestonerLimitMap();
        $operatorOptions = $this->getOperatorOptionsByOffice($this->getUserOffice(), 'destoner');

        return view('kernel.destoner.create', compact('kodeOptions', 'jenisOptions', 'kernelLimitMap', 'operatorOptions'));
    }

    public function destonerStore(Request $request)
    {
        if (!Auth::user()->can('create kernel losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data Destoner.');
        }

        $userOffice = $this->getUserOffice();
        if (!$userOffice) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda harus memiliki Office yang ditentukan untuk dapat input data.'], 422);
            }
            return back()
                ->withInput()
                ->with('error', 'Anda harus memiliki Office yang ditentukan untuk dapat input data. Silakan hubungi Administrator.');
        }

        $validated = $request->validate([
            'kode' => 'required|string',
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($userOffice, true, 'destoner'),
            'sampel_boy' => 'required|string|max:255',
            'pengulangan' => 'nullable|boolean',
            'berat_sampel' => 'required|numeric|gt:0',
            'time' => 'required|numeric|gt:0',
            'berat_nut' => 'required|numeric|min:0',
            'berat_kernel' => 'required|numeric|min:0',
        ], [
            'kode.required' => 'Kode wajib dipilih.',
            'jenis.required' => 'Jenis wajib dipilih.',
            'operator.required' => 'Operator wajib diisi.',
            'operator.in' => 'Operator tidak sesuai dengan daftar office.',
            'sampel_boy.required' => 'Sampel Boy wajib diisi.',
            'berat_sampel.required' => 'Berat Sampel wajib diisi.',
            'berat_sampel.gt' => 'Berat Sampel harus lebih dari 0.',
            'time.required' => 'Time (detik) wajib diisi.',
            'time.gt' => 'Time harus lebih dari 0.',
            'berat_nut.required' => 'Berat Nut wajib diisi.',
            'berat_kernel.required' => 'Berat Kernel wajib diisi.',
        ]);

        $isPengulangan = (bool) $request->boolean('pengulangan');
        $this->validatePengulanganWindow(
            KernelDestoner::class,
            $validated['kode'],
            $userOffice,
            $isPengulangan,
            'Destoner'
        );

        $beratSampel = (float) $validated['berat_sampel'];
        $time = (float) $validated['time'];
        $beratNut = (float) $validated['berat_nut'];
        $beratKernel = (float) $validated['berat_kernel'];

        $konversiKg = round($beratSampel / 1000, 6);
        $rasioJamKg = round($konversiKg * 3600 / $time, 6);
        // Destoner percentages are reported on half-sample basis.
        $persenNut = round(($beratNut / $beratSampel) * 50, 6);
        $persenKernel = round(($beratKernel / $beratSampel) * 100, 6);
        $totalLossesKernel = round($persenKernel + $persenNut, 6);
        $lossKernelJam = round(($totalLossesKernel * $rasioJamKg) / 100, 6);
        $lossKernelTbs = round($lossKernelJam / 300, 8);

        $limitMap = $this->getDestonerLimitMap();
        $kode = $validated['kode'];

        $kernelDestoner = KernelDestoner::create([
            'user_id' => Auth::id(),
            'office' => $userOffice,
            'rounded_time' => $this->getRoundedTime(),
            'kode' => $kode,
            'jenis' => $validated['jenis'],
            'operator' => $validated['operator'],
            'sampel_boy' => $validated['sampel_boy'],
            'pengulangan' => $isPengulangan,
            'berat_sampel' => $beratSampel,
            'time' => $time,
            'berat_nut' => $beratNut,
            'berat_kernel' => $beratKernel,
            'konversi_kg' => $konversiKg,
            'rasio_jam_kg' => $rasioJamKg,
            'persen_nut' => $persenNut,
            'persen_kernel' => $persenKernel,
            'total_losses_kernel' => $totalLossesKernel,
            'loss_kernel_jam' => $lossKernelJam,
            'loss_kernel_tbs' => $lossKernelTbs,
            'limit_operator' => data_get($limitMap, $kode . '.operator', 'gt'),
            'limit_value' => data_get($limitMap, $kode . '.value'),
        ]);

        $this->logCreate(
            $kernelDestoner,
            "Input data Destoner untuk kode {$kernelDestoner->kode}",
            ['module' => 'destoner', 'office' => $userOffice]
        );

        $message = 'Data Destoner berhasil disimpan.';
        $proofData = $this->buildDestonerProofData($kernelDestoner, $message);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'proof' => $proofData]);
        }

        if ($request->input('_action') === 'save_and_add') {
            return redirect()->route('kernel.destoner.create')->with('success', $message);
        }

        return redirect()->route('kernel.destoner.index')
            ->with('success', $message)
            ->with('success_proof', $proofData);
    }

    public function destonerEdit(KernelDestoner $kernelDestoner)
    {
        $this->ensurePpicRole();

        $kodeOptions = $this->getDestonerKodeOptions();
        $jenisOptions = KernelRecord::getJenisOptions();
        $operatorOptions = $this->getOperatorOptionsByOffice($kernelDestoner->office, 'destoner');

        return view('kernel.destoner.edit', compact('kernelDestoner', 'kodeOptions', 'jenisOptions', 'operatorOptions'));
    }

    public function destonerUpdate(Request $request, KernelDestoner $kernelDestoner)
    {
        $this->ensurePpicRole();

        $oldAttributes = $kernelDestoner->getOriginal();

        $validated = $request->validate([
            'kode' => 'required|string',
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($kernelDestoner->office, true, 'destoner'),
            'sampel_boy' => 'required|string|max:255',
            'berat_sampel' => 'required|numeric|gt:0',
            'time' => 'required|numeric|gt:0',
            'berat_nut' => 'required|numeric|min:0',
            'berat_kernel' => 'required|numeric|min:0',
        ]);

        $beratSampel = (float) $validated['berat_sampel'];
        $time = (float) $validated['time'];
        $beratNut = (float) $validated['berat_nut'];
        $beratKernel = (float) $validated['berat_kernel'];

        $konversiKg = round($beratSampel / 1000, 6);
        $rasioJamKg = round($konversiKg * 3600 / $time, 6);
        $persenNut = round(($beratNut / $beratSampel) * 50, 6);
        $persenKernel = round(($beratKernel / $beratSampel) * 100, 6);
        $totalLossesKernel = round($persenKernel + $persenNut, 6);
        $lossKernelJam = round(($totalLossesKernel * $rasioJamKg) / 100, 6);
        $lossKernelTbs = round($lossKernelJam / 300, 8);

        $limitMap = $this->getDestonerLimitMap();
        $kode = $validated['kode'];

        $kernelDestoner->update([
            'user_id' => Auth::id(),
            'kode' => $kode,
            'jenis' => $validated['jenis'],
            'operator' => $validated['operator'],
            'sampel_boy' => $validated['sampel_boy'],
            'berat_sampel' => $beratSampel,
            'time' => $time,
            'berat_nut' => $beratNut,
            'berat_kernel' => $beratKernel,
            'konversi_kg' => $konversiKg,
            'rasio_jam_kg' => $rasioJamKg,
            'persen_nut' => $persenNut,
            'persen_kernel' => $persenKernel,
            'total_losses_kernel' => $totalLossesKernel,
            'loss_kernel_jam' => $lossKernelJam,
            'loss_kernel_tbs' => $lossKernelTbs,
            'limit_operator' => data_get($limitMap, $kode . '.operator', 'gt'),
            'limit_value' => data_get($limitMap, $kode . '.value'),
        ]);

        $this->logUpdate(
            $kernelDestoner,
            $oldAttributes,
            "Update data Destoner kode {$kernelDestoner->kode}",
            ['module' => 'destoner', 'office' => $kernelDestoner->office]
        );

        return redirect()->route('kernel.destoner.index')->with('success', 'Data Destoner berhasil diperbarui.');
    }

    public function destonerDestroy(KernelDestoner $kernelDestoner)
    {
        $this->ensurePpicRole();

        $this->logDelete(
            $kernelDestoner,
            "Hapus data Destoner kode {$kernelDestoner->kode}",
            ['module' => 'destoner', 'office' => $kernelDestoner->office]
        );

        $kernelDestoner->delete();

        return back()->with('success', 'Data Destoner berhasil dihapus.');
    }

    public function destroyRecord(KernelRecord $kernelRecord)
    {
        $this->ensurePpicRole();

        $this->logDelete(
            $kernelRecord,
            "Hapus data jenis & sampel kernel kode {$kernelRecord->kode}",
            ['module' => 'kernel_record', 'office' => $kernelRecord->office]
        );

        $kernelRecord->delete();
        return back()->with('success', 'Data jenis & sampel berhasil dihapus.');
    }

    public function destroyCalculation(KernelCalculation $kernelCalculation)
    {
        $this->ensurePpicRole();

        $this->logDelete(
            $kernelCalculation,
            "Hapus data perhitungan kernel losses kode {$kernelCalculation->kode}",
            ['module' => 'kernel_losses', 'office' => $kernelCalculation->office]
        );

        $kernelCalculation->delete();
        return back()->with('success', 'Data perhitungan lab berhasil dihapus.');
    }

    public function rekap(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $officeFilter = $this->resolveOfficeFilter($request);

        [$allKodesData, $dataByDate] = $this->buildRekapData($startDate, $endDate, $officeFilter);
        $columnGroups = $this->getKernelColumnGroups();
        $masterMap = collect($allKodesData)->keyBy('kode');

        return view('kernel.rekap', compact('allKodesData', 'dataByDate', 'startDate', 'endDate', 'columnGroups', 'masterMap', 'officeFilter'));
    }

    public function exportRekap(Request $request)
    {
        if (!Auth::user()->can('view rekap kernel losses')) {
            abort(403);
        }

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $officeFilter = $this->resolveOfficeFilter($request);

        [$allKodesData, $dataByDate] = $this->buildRekapData($startDate, $endDate, $officeFilter);
        $columnGroups = $this->getKernelColumnGroups();

        $this->logExport('Rekap Kernel Losses', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'office' => $officeFilter,
        ]);

        $filename = 'Rekap_Kernel_Losses_'
            . \Carbon\Carbon::parse($startDate)->format('Ymd') . '_'
            . \Carbon\Carbon::parse($endDate)->format('Ymd') . '.xlsx';

        return Excel::download(
            new KernelRekapExport($dataByDate, $allKodesData, $startDate, $endDate, $columnGroups),
            $filename
        );
    }

    private function buildRekapData(string $startDate, string $endDate, string $officeFilter): array
    {
        $allKodesData = KernelMasterData::where('is_active', true)
            ->orderBy('kode')
            ->get()
            ->map(function ($m) {
                return [
                    'kode' => $m->kode,
                    'nama_sample' => $m->nama_sample,
                    'limit_operator' => $m->limit_operator,
                    'limit_value' => $m->limit_value,
                ];
            });

        $masterMap = KernelMasterData::where('is_active', true)
            ->get()
            ->keyBy('kode');

        $calculations = $this->getUnifiedKernelReportRecords($startDate, $endDate, $officeFilter)
            ->sortBy('created_at')
            ->values();

        // Accumulate sum + count per date + kode
        $grouped = [];
        foreach ($calculations as $calc) {
            $date = $calc->created_at->format('Y-m-d');
            $kode = $calc->kode;
            if (!isset($grouped[$date][$kode])) {
                $grouped[$date][$kode] = ['sum' => 0.0, 'count' => 0];
            }
            $grouped[$date][$kode]['sum'] += (float) ($calc->kernel_losses ?? 0);
            $grouped[$date][$kode]['count'] += 1;
        }

        // Build final dataByDate with averages
        $dataByDate = [];
        foreach ($grouped as $date => $kodes) {
            foreach ($kodes as $kode => $stats) {
                $avg = $stats['count'] > 0 ? $stats['sum'] / $stats['count'] : 0.0;
                $master = $masterMap->get($kode);
                $dataByDate[$date][$kode] = [
                    'avg_losses' => $avg,
                    'count' => $stats['count'],
                    'limit_operator' => $master ? $master->limit_operator : null,
                    'limit_value' => $master ? (float) $master->limit_value : null,
                ];
            }
        }
        ksort($dataByDate);

        return [$allKodesData, $dataByDate];
    }

    private function getKernelColumnGroups(): array
    {
        return [
            [
                'name' => 'KERNEL LOSSES',
                'color' => 'bg-amber-100 text-amber-800',
                'columns' => [
                    ['kode' => 'FC1', 'label' => 'FIBRE CYCLONE 1'],
                    ['kode' => 'FC2', 'label' => 'FIBRE CYCLONE 2'],
                    ['kode' => 'L1', 'label' => 'LTDS 1'],
                    ['kode' => 'L2', 'label' => 'LTDS 2'],
                    ['kode' => 'L3', 'label' => 'LTDS 3'],
                    ['kode' => 'L4', 'label' => 'LTDS 4'],
                    ['kode' => 'CWS', 'label' => 'CLAYBATH WET SHELL 1'],
                    ['kode' => 'CWS2', 'label' => 'CLAYBATH WET SHELL 2'],
                    ['kode' => 'CWS3', 'label' => 'CLAYBATH WET SHELL 3'],
                ],
            ],
            [
                'name' => '%Dirty',
                'color' => 'bg-orange-100 text-orange-800',
                'columns' => [
                    ['kode' => 'IN1', 'label' => 'INLET KERNEL SILO 1'],
                    ['kode' => 'IN2', 'label' => 'INLET KERNEL SILO 2'],
                    ['kode' => 'IN3', 'label' => 'INLET KERNEL SILO 3'],
                    ['kode' => 'IN4', 'label' => 'INLET KERNEL SILO 4'],
                    ['kode' => 'IN5', 'label' => 'INLET KERNEL SILO 5'],
                    ['kode' => 'OUT1', 'label' => 'OUTLET KERNEL SILO 1 TO BUNKER'],
                    ['kode' => 'OUT2', 'label' => 'OUTLET KERNEL SILO 2 TO BUNKER'],
                    ['kode' => 'OUT3', 'label' => 'OUTLET KERNEL SILO 3 TO BUNKER'],
                    ['kode' => 'OUT4', 'label' => 'OUTLET KERNEL SILO 4 TO BUNKER'],
                    ['kode' => 'OUT5', 'label' => 'OUTLET KERNEL SILO 5 TO BUNKER'],
                ],
            ],
            [
                'name' => '%Moist',
                'color' => 'bg-blue-100 text-blue-800',
                'columns' => [
                    ['kode' => 'OUT1', 'label' => 'OUTLET KERNEL SILO 1 TO BUNKER'],
                    ['kode' => 'OUT2', 'label' => 'OUTLET KERNEL SILO 2 TO BUNKER'],
                    ['kode' => 'OUT3', 'label' => 'OUTLET KERNEL SILO 3 TO BUNKER'],
                    ['kode' => 'OUT4', 'label' => 'OUTLET KERNEL SILO 4 TO BUNKER'],
                    ['kode' => 'OUT5', 'label' => 'OUTLET KERNEL SILO 5 TO BUNKER'],
                ],
            ],
            [
                'name' => 'BN / TN',
                'color' => 'bg-green-100 text-green-800',
                'columns' => [
                    ['kode' => 'P1', 'label' => 'PRESS 1'],
                    ['kode' => 'P2', 'label' => 'PRESS 2'],
                    ['kode' => 'P3', 'label' => 'PRESS 3'],
                    ['kode' => 'P4', 'label' => 'PRESS 4'],
                    ['kode' => 'P5', 'label' => 'PRESS 5'],
                    ['kode' => 'P6', 'label' => 'PRESS 6'],
                    ['kode' => 'P7', 'label' => 'PRESS 7'],
                    ['kode' => 'P8', 'label' => 'PRESS 8'],
                    ['kode' => 'P9', 'label' => 'PRESS 9'],
                ],
            ],
            [
                'name' => 'Efficiency',
                'color' => 'bg-purple-100 text-purple-800',
                'columns' => [
                    ['kode' => 'R1', 'label' => 'Ripple Mill No. 1'],
                    ['kode' => 'R2', 'label' => 'Ripple Mill No. 2'],
                    ['kode' => 'R3', 'label' => 'Ripple Mill No. 3'],
                    ['kode' => 'R4', 'label' => 'Ripple Mill No. 4'],
                    ['kode' => 'R5', 'label' => 'Ripple Mill No. 5'],
                    ['kode' => 'R6', 'label' => 'Ripple Mill No. 6'],
                    ['kode' => 'R7', 'label' => 'Ripple Mill No. 7'],
                ],
            ],
            [
                'name' => 'DESTONER 1',
                'color' => 'bg-pink-100 text-pink-800',
                'columns' => [
                    ['kode' => 'D1', 'label' => 'LOSS KERNEL/JAM'],
                    ['kode' => 'D1', 'label' => 'LOSS KERNEL/TBS'],
                ],
            ],
            [
                'name' => 'DESTONER 2',
                'color' => 'bg-rose-100 text-rose-800',
                'columns' => [
                    ['kode' => 'D2', 'label' => 'LOSS KERNEL/JAM'],
                    ['kode' => 'D2', 'label' => 'LOSS KERNEL/TBS'],
                ],
            ],
        ];
    }

    public function performance(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $officeFilter = $this->resolveOfficeFilter($request);

        [$reportData, $allKodesData, $operators, $operatorActivities, $reportDates, $dailyPerformance, $allIndividualRecords, $operatorDailyPerformance] = $this->buildPerformanceData($startDate, $endDate, $officeFilter);

        // Paginate individual records (25 per page)
        $perPage = 25;
        $page = $request->get('page', 1);
        $sliced = array_slice($allIndividualRecords, ($page - 1) * $perPage, $perPage);
        $individualRecords = new \Illuminate\Pagination\LengthAwarePaginator(
            $sliced,
            count($allIndividualRecords),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->except('page')]
        );

        return view('kernel.performance', compact(
            'reportData',
            'allKodesData',
            'startDate',
            'endDate',
            'officeFilter',
            'operators',
            'operatorActivities',
            'reportDates',
            'dailyPerformance',
            'individualRecords',
            'operatorDailyPerformance'
        ));
    }

    public function exportPerformance(Request $request)
    {
        if (!Auth::user()->can('view performance kernel losses')) {
            abort(403);
        }

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $officeFilter = $this->resolveOfficeFilter($request);

        [$reportData, $allKodesData, $operators, $operatorActivities, $reportDates, $dailyPerformance] = $this->buildPerformanceData($startDate, $endDate, $officeFilter);

        $this->logExport('Performance Kernel Losses', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'office' => $officeFilter,
        ]);

        $filename = 'Performance_Kernel_'
            . Carbon::parse($startDate)->format('Ymd') . '_'
            . Carbon::parse($endDate)->format('Ymd') . '.xlsx';

        return Excel::download(
            new KernelPerformanceExport($reportData, $allKodesData, $operators, $reportDates, $dailyPerformance, $startDate, $endDate),
            $filename
        );
    }

    private function buildPerformanceData(string $startDate, string $endDate, string $officeFilter): array
    {
        $bobotConfigs = KernelBobotConfig::all()->keyBy('jenis');

        // Get all active kodes that have a matching bobot config
        $allKodesData = KernelMasterData::where('is_active', true)
            ->get()
            ->reject(function ($m) {
                return str_starts_with(strtoupper((string) $m->kode), 'D');
            })
            ->filter(function ($m) use ($bobotConfigs) {
                return $this->mapKodeToKernelConfig($m->kode, $bobotConfigs) !== null;
            })
            ->sortBy('kode')
            ->map(fn($m) => [
                'kode' => $m->kode,
                'nama_sample' => $m->nama_sample,
            ])
            ->values();

        $masterDataMap = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        // Get unified records from all kernel modules grouped by date+kode
        $calculations = $this->getUnifiedKernelReportRecords($startDate, $endDate, $officeFilter)
            ->reject(function ($row) {
                return str_starts_with(strtoupper((string) ($row->kode ?? '')), 'D');
            })
            ->sortBy('created_at')
            ->values();

        // Build individual records (flat, one row per calculation)
        $individualRecords = [];
        foreach ($calculations as $calc) {
            $valuePercent = (float) ($calc->kernel_losses ?? 0) * 100;
            $config = $this->mapKodeToKernelConfig($calc->kode, $bobotConfigs);
            $bobot = $config ? $this->calculateKernelBobot($valuePercent, $config, $calc->kode) : null;
            $master = $masterDataMap->get($calc->kode);
            $individualRecords[] = [
                'date' => $calc->created_at->format('Y-m-d'),
                'time' => $calc->created_at->format('H:i'),
                'operator' => $calc->operator ?? '-',
                'sampel_boy' => $calc->sampel_boy ?? '-',
                'nama_sample' => $master ? $master->nama_sample : $calc->kode,
                'kode' => $calc->kode,
                'nilai_parameter' => $valuePercent,
                'bobot' => $bobot,
            ];
        }

        $grouped = [];
        foreach ($calculations as $calc) {
            $date = $calc->created_at->format('Y-m-d');
            $kode = $calc->kode;
            if (!isset($grouped[$date][$kode])) {
                $grouped[$date][$kode] = ['sum' => 0.0, 'count' => 0];
            }
            $grouped[$date][$kode]['sum'] += (float) ($calc->kernel_losses ?? 0);
            $grouped[$date][$kode]['count'] += 1;
        }

        ksort($grouped);

        $reportData = [];
        foreach ($grouped as $date => $kodes) {
            $dateData = ['date' => $date, 'kodes' => []];
            $bobotScores = [];

            foreach ($allKodesData as $kodeInfo) {
                $kode = $kodeInfo['kode'];

                if (isset($kodes[$kode]) && $kodes[$kode]['count'] > 0) {
                    $avgFraction = $kodes[$kode]['sum'] / $kodes[$kode]['count'];
                    $valuePercent = $avgFraction * 100;

                    $config = $this->mapKodeToKernelConfig($kode, $bobotConfigs);
                    $bobot = $config ? $this->calculateKernelBobot($valuePercent, $config, $kode) : null;

                    $dateData['kodes'][$kode] = [
                        'kernel_losses' => $valuePercent,
                        'bobot' => $bobot,
                    ];

                    if ($bobot !== null) {
                        $bobotScores[] = $bobot;
                    }
                } else {
                    $dateData['kodes'][$kode] = [
                        'kernel_losses' => null,
                        'bobot' => null,
                    ];
                }
            }

            $dateData['average_bobot'] = !empty($bobotScores)
                ? round(array_sum($bobotScores) / count($bobotScores), 2)
                : null;

            $reportData[] = $dateData;
        }

        // Get operator data for the same date range
        $operatorRecords = $calculations
            ->filter(function ($row) {
                return !empty($row->operator);
            })
            ->sortByDesc('created_at')
            ->values();

        $operators = $operatorRecords->pluck('operator')->unique()->sort()->values();

        $operatorActivities = $operatorRecords->groupBy('operator')->map(function ($records) {
            return [
                'total_input' => $records->count(),
                'last_input' => $records->first()->created_at,
            ];
        });

        $reportDates = collect($reportData)->pluck('date');
        $dailyPerformance = collect($reportData)->keyBy('date')->map(function ($item) {
            return ['average_bobot' => $item['average_bobot']];
        });

        // Build per-operator daily performance from individual records
        $operatorDailyPerformance = [];
        foreach ($individualRecords as $rec) {
            $op = $rec['operator'];
            $date = $rec['date'];
            if ($op === '-' || $op === '' || $rec['bobot'] === null)
                continue;
            if (!isset($operatorDailyPerformance[$op][$date])) {
                $operatorDailyPerformance[$op][$date] = ['sum' => 0, 'count' => 0];
            }
            $operatorDailyPerformance[$op][$date]['sum'] += $rec['bobot'];
            $operatorDailyPerformance[$op][$date]['count'] += 1;
        }

        return [$reportData, $allKodesData, $operators, $operatorActivities, $reportDates, $dailyPerformance, $individualRecords, $operatorDailyPerformance];
    }

    private function mapKodeToKernelConfig(string $kode, $bobotConfigs)
    {
        $prefix = preg_replace('/[0-9]+$/', '', $kode);

        $map = [
            'CWS' => 'Claybath',
            'FC' => 'Fibercyclone',
            'L' => 'LTDS',
            'IN' => 'Inlet Kernel Silo',
            'OUT' => 'Outlet Kernel Silo',
            'R' => 'Ripple Mill',
            'D' => 'CaCo3',
            'P' => 'Press',
        ];

        $jenis = $map[$prefix] ?? null;

        return ($jenis && isset($bobotConfigs[$jenis])) ? $bobotConfigs[$jenis] : null;
    }

    private function ensurePpicRole(): void
    {
        if (!Auth::user() || !Auth::user()->hasRole('PPIC')) {
            abort(403, 'Aksi ini hanya tersedia untuk role PPIC.');
        }
    }

    private function getKernelLossesKodeOptions(): array
    {
        $orderedCodes = ['FC1', 'FC2', 'L1', 'L2', 'L3', 'L4', 'CWS', 'CWS2', 'CWS3'];

        $options = KernelMasterData::where('is_active', true)
            ->whereIn('kode', $orderedCodes)
            ->pluck('nama_sample', 'kode')
            ->toArray();

        $orderedOptions = [];
        foreach ($orderedCodes as $kode) {
            if (isset($options[$kode])) {
                $orderedOptions[$kode] = $options[$kode];
            }
        }

        return $orderedOptions;
    }

    private function getQwtKodeOptions(): array
    {
        return KernelMasterData::where('is_active', true)
            ->where('kode', 'like', 'P%')
            ->orderBy('kode')
            ->pluck('nama_sample', 'kode')
            ->toArray();
    }

    private function getQwtLimitMap(): array
    {
        $map = [];

        foreach (array_keys($this->getQwtKodeOptions()) as $kode) {
            $map[$kode] = [
                'bn_tn' => [
                    'operator' => 'le',
                    'value' => 15.00,
                ],
                'moist' => [
                    'operator' => 'le',
                    'value' => 40.00,
                ],
            ];
        }

        return $map;
    }

    private function getDestonerKodeOptions(): array
    {
        return KernelMasterData::where('is_active', true)
            ->where('kode', 'like', 'D%')
            ->orderBy('kode')
            ->pluck('nama_sample', 'kode')
            ->toArray();
    }

    private function getDestonerLimitMap(): array
    {
        return KernelMasterData::where('is_active', true)
            ->where('kode', 'like', 'D%')
            ->orderBy('kode')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->kode => [
                        'operator' => $item->limit_operator,
                        'value' => (float) $item->limit_value,
                    ],
                ];
            })
            ->toArray();
    }

    private function getRippleMillKodeOptions(): array
    {
        return KernelMasterData::where('is_active', true)
            ->where('kode', 'like', 'R%')
            ->orderBy('kode')
            ->pluck('nama_sample', 'kode')
            ->toArray();
    }

    private function getRippleMillLimitMap(): array
    {
        return KernelMasterData::where('is_active', true)
            ->where('kode', 'like', 'R%')
            ->orderBy('kode')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->kode => [
                        'operator' => $item->limit_operator,
                        'value' => (float) $item->limit_value,
                    ],
                ];
            })
            ->toArray();
    }

    private function getDirtMoistKodeOptions(): array
    {
        return KernelMasterData::where('is_active', true)
            ->where(function ($query) {
                $query->where('kode', 'like', 'IN%')
                    ->orWhere('kode', 'like', 'OUT%');
            })
            ->orderBy('kode')
            ->pluck('nama_sample', 'kode')
            ->toArray();
    }

    private function getDirtMoistLimitMap(): array
    {
        $kodeOptions = $this->getDirtMoistKodeOptions();
        $map = [];

        foreach (array_keys($kodeOptions) as $kode) {
            $isOutlet = str_starts_with($kode, 'OUT');
            $map[$kode] = [
                'dirty' => [
                    'operator' => 'le',
                    'value' => 7.00,
                ],
                'moist' => $isOutlet
                    ? [
                        'operator' => 'le',
                        'value' => 6.00,
                    ]
                    : null,
            ];
        }

        return $map;
    }

    private function getPengulanganIntervalHours(string $kode, ?string $office = null): ?int
    {
        // For SUN office, all intervals are 2 hours
        if ($office === 'SUN') {
            return 2;
        }

        // For YBS and other offices (original logic)
        if (preg_match('/^(FC|L|R)/', $kode)) {
            return 2;
        }

        if (preg_match('/^(CWS|IN|OUT)/', $kode)) {
            return 1;
        }

        if (preg_match('/^(P|D)/', $kode)) {
            return 4;
        }

        return null;
    }

    private function validatePengulanganWindow(string $modelClass, string $kode, ?string $office, bool $isPengulangan, string $moduleName): void
    {
        $intervalHours = $this->getPengulanganIntervalHours($kode, $office);
        if (!$intervalHours) {
            throw ValidationException::withMessages([
                'pengulangan' => "Kode {$kode} belum memiliki aturan interval sampel ulang.",
            ]);
        }

        $query = $modelClass::query()
            ->where('kode', $kode)
            ->when($office, fn($q) => $q->where('office', $office));

        if ($this->hasPengulanganColumn($modelClass)) {
            $query->where('pengulangan', false);
        }

        $lastNormalSample = $query->latest('created_at')->first();

        if (!$lastNormalSample) {
            if (!$isPengulangan) {
                return;
            }

            throw ValidationException::withMessages([
                'pengulangan' => "Belum ada data awal untuk kode {$kode} pada modul {$moduleName}. Centang sampel ulang setelah data awal tersedia.",
            ]);
        }

        $referenceTime = $this->getRoundedTime();
        $lastSampleTime = $lastNormalSample->rounded_time ?? $lastNormalSample->created_at;
        $elapsedSeconds = $lastSampleTime->diffInSeconds($referenceTime, false);
        $minSeconds = $intervalHours * 3600;

        if ($elapsedSeconds < $minSeconds && !$isPengulangan) {
            $elapsedHours = max(0, $elapsedSeconds) / 3600;
            throw ValidationException::withMessages([
                'pengulangan' => "Input {$kode} masih dalam interval {$intervalHours} jam sejak data normal terakhir ({$elapsedHours} jam). Tandai sebagai sampel ulang.",
            ]);
        }

        if ($elapsedSeconds >= $minSeconds && $isPengulangan) {
            $elapsedHours = max(0, $elapsedSeconds) / 3600;
            throw ValidationException::withMessages([
                'pengulangan' => "Input {$kode} sudah melewati interval {$intervalHours} jam sejak data normal terakhir ({$elapsedHours} jam), sehingga tidak boleh dicentang sebagai sampel ulang.",
            ]);
        }
    }

    private function hasPengulanganColumn(string $modelClass): bool
    {
        if (array_key_exists($modelClass, $this->pengulanganColumnCache)) {
            return $this->pengulanganColumnCache[$modelClass];
        }

        $table = (new $modelClass())->getTable();
        $exists = Schema::hasColumn($table, 'pengulangan');
        $this->pengulanganColumnCache[$modelClass] = $exists;

        return $exists;
    }

    private function getOperatorOptionsByOffice(?string $office, string $module): array
    {
        if (!$office) {
            return [];
        }

        return config('operator-options.' . $module . '.' . $office, []);
    }

    private function getOperatorValidationRules(?string $office, bool $required = false, string $module = 'kernel'): array
    {
        $rules = [$required ? 'required' : 'nullable', 'string', 'max:255'];
        $operatorOptions = $this->getOperatorOptionsByOffice($office, $module);

        if (!empty($operatorOptions)) {
            $rules[] = Rule::in($operatorOptions);
        }

        return $rules;
    }

    private function getUserOffice(): ?string
    {
        return Auth::user()->office;
    }

    private function resolveOfficeFilter(?Request $request = null): string
    {
        $userOffice = $this->getUserOffice();

        if ($userOffice) {
            return $userOffice;
        }

        return $request?->input('office', 'YBS') ?? 'YBS';
    }

    private function applyOfficeFilter($query, string $officeFilter)
    {
        if ($officeFilter !== 'all') {
            $query->where('office', $officeFilter);
        }

        return $query;
    }

    private function getRoundedTime(): Carbon
    {
        $now = now();
        $minute = (int) $now->format('i');
        $roundedMinute = $minute < 30 ? 0 : 30;

        return $now->copy()->setTime((int) $now->format('H'), $roundedMinute, 0);
    }

    private function buildProofInput(string $label, $value, string $unit = '', int $decimals = 2): array
    {
        return [
            'label' => $label,
            'value' => $value,
            'unit' => $unit,
            'decimals' => $decimals,
        ];
    }

    private function buildProofMetric(
        string $label,
        $value,
        string $unit = '',
        int $decimals = 2,
        ?string $limitOperator = null,
        $limitValue = null,
        ?int $limitDecimals = null
    ): array {
        return [
            'label' => $label,
            'value' => $value,
            'unit' => $unit,
            'decimals' => $decimals,
            'limit_operator' => $limitOperator,
            'limit_value' => $limitValue,
            'limit_decimals' => $limitDecimals ?? $decimals,
        ];
    }

    private function buildKernelProofBase(string $module, string $message, $row, ?KernelMasterData $master): array
    {
        return [
            'module' => $module,
            'message' => $message,
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'tanggal_input' => $row->created_at->format('d/m/Y H:i:s'),
            'jam_proses' => $row->rounded_time
                ? $row->rounded_time->format('H:i')
                : $row->created_at->format('H:i'),
            'office' => $row->office,
            'input_by' => $row->user?->name ?? Auth::user()->name,
            'kode' => $row->kode,
            'kode_label' => $row->kode . ' - ' . ($master?->nama_sample ?? '-'),
            'jenis' => $row->jenis ?? '-',
            'operator' => $row->operator ?? '-',
            'sampel_boy' => $row->sampel_boy ?? '-',
        ];
    }

    private function buildKernelLossesProofData(KernelCalculation $calc, string $message): array
    {
        $master = KernelMasterData::where('kode', $calc->kode)->first();
        $lossPercent = (float) ($calc->kernel_losses ?? 0) * 100;

        $proof = $this->buildKernelProofBase('kernel', $message, $calc, $master);
        $proof['metrics'] = [
            $this->buildProofMetric(
                'Kernel Losses',
                $lossPercent,
                '%',
                2,
                $master?->limit_operator,
                $master?->limit_value,
                2
            ),
        ];
        $proof['inputs'] = [
            $this->buildProofInput('Berat Sampel', $calc->berat_sampel, ' g', 2),
            $this->buildProofInput('Nut Utuh - Nut', $calc->nut_utuh_nut, ' g', 2),
            $this->buildProofInput('Nut Utuh - Kernel', $calc->nut_utuh_kernel, ' g', 2),
            $this->buildProofInput('Nut Pecah - Nut', $calc->nut_pecah_nut, ' g', 2),
            $this->buildProofInput('Nut Pecah - Kernel', $calc->nut_pecah_kernel, ' g', 2),
            $this->buildProofInput('Kernel Utuh', $calc->kernel_utuh, ' g', 2),
            $this->buildProofInput('Kernel Pecah', $calc->kernel_pecah, ' g', 2),
        ];

        return $proof;
    }

    private function buildDirtMoistProofData(KernelDirtMoistCalculation $calc, string $message): array
    {
        $master = KernelMasterData::where('kode', $calc->kode)->first();
        $limitMap = $this->getDirtMoistLimitMap();
        $limitConfig = $limitMap[$calc->kode] ?? ['dirty' => null, 'moist' => null];

        $proof = $this->buildKernelProofBase('dirt_moist', $message, $calc, $master);
        $proof['metrics'] = [
            $this->buildProofMetric(
                'Dirty to Sampel',
                $calc->dirty_to_sampel,
                '%',
                2,
                data_get($limitConfig, 'dirty.operator'),
                data_get($limitConfig, 'dirty.value'),
                2
            ),
            $this->buildProofMetric(
                'Moist',
                $calc->moist_percent,
                '%',
                2,
                data_get($limitConfig, 'moist.operator'),
                data_get($limitConfig, 'moist.value'),
                2
            ),
        ];
        $proof['inputs'] = [
            $this->buildProofInput('Berat Sampel', $calc->berat_sampel, ' g', 2),
            $this->buildProofInput('Berat Dirty', $calc->berat_dirty, ' g', 2),
            $this->buildProofInput('Moist', $calc->moist_percent, ' %', 2),
        ];

        return $proof;
    }

    private function buildQwtProofData(KernelQwt $row, string $message): array
    {
        $master = KernelMasterData::where('kode', $row->kode)->first();

        $proof = $this->buildKernelProofBase('qwt', $message, $row, $master);
        $proof['metrics'] = [
            $this->buildProofMetric(
                'BN / TN',
                $row->bn_tn,
                '%',
                2,
                $row->bn_tn_limit_operator,
                $row->bn_tn_limit_value,
                2
            ),
            $this->buildProofMetric(
                'Moisture',
                $row->moisture,
                '%',
                2,
                $row->moist_limit_operator,
                $row->moist_limit_value,
                2
            ),
        ];
        $proof['inputs'] = [
            $this->buildProofInput('Sampel Setelah Kuarter', $row->sampel_setelah_kuarter, ' g', 2),
            $this->buildProofInput('Berat Nut Utuh', $row->berat_nut_utuh, ' g', 2),
            $this->buildProofInput('Berat Nut Pecah', $row->berat_nut_pecah, ' g', 2),
            $this->buildProofInput('Berat Kernel Utuh', $row->berat_kernel_utuh, ' g', 2),
            $this->buildProofInput('Berat Kernel Pecah', $row->berat_kernel_pecah, ' g', 2),
            $this->buildProofInput('Berat Cangkang', $row->berat_cangkang, ' g', 2),
            $this->buildProofInput('Berat Batu', $row->berat_batu, ' g', 2),
            $this->buildProofInput('Ampere Screw', $row->ampere_screw, '', 2),
            $this->buildProofInput('Tekanan Hydraulic', $row->tekanan_hydraulic, '', 2),
            $this->buildProofInput('Kecepatan Screw', $row->kecepatan_screw, '', 2),
        ];

        return $proof;
    }

    private function buildRippleMillProofData(KernelRippleMill $row, string $message): array
    {
        $master = KernelMasterData::where('kode', $row->kode)->first();

        $proof = $this->buildKernelProofBase('ripple_mill', $message, $row, $master);
        $proof['metrics'] = [
            $this->buildProofMetric(
                'Efficiency',
                $row->efficiency,
                '%',
                2,
                $master?->limit_operator,
                $master?->limit_value,
                2
            ),
        ];
        $proof['inputs'] = [
            $this->buildProofInput('Berat Sampel', $row->berat_sampel, ' g', 2),
            $this->buildProofInput('Berat Nut Utuh', $row->berat_nut_utuh, ' g', 2),
            $this->buildProofInput('Berat Nut Pecah', $row->berat_nut_pecah, ' g', 2),
        ];

        return $proof;
    }

    private function buildDestonerProofData(KernelDestoner $row, string $message): array
    {
        $master = KernelMasterData::where('kode', $row->kode)->first();

        $proof = $this->buildKernelProofBase('destoner', $message, $row, $master);
        $proof['metrics'] = [
            $this->buildProofMetric('Total Losses Kernel', $row->total_losses_kernel, '%', 4, null, null, 4),
            $this->buildProofMetric(
                'Loss Kernel/TBS',
                $row->loss_kernel_tbs,
                '',
                8,
                $row->limit_operator,
                $row->limit_value,
                3
            ),
        ];
        $proof['inputs'] = [
            $this->buildProofInput('Berat Sampel', $row->berat_sampel, ' g', 2),
            $this->buildProofInput('Time', $row->time, ' detik', 2),
            $this->buildProofInput('Berat Nut', $row->berat_nut, ' g', 2),
            $this->buildProofInput('Berat Kernel', $row->berat_kernel, ' g', 2),
        ];

        return $proof;
    }

    private function calculateKernelBobot(float $value, $config, ?string $kode = null): int
    {
        $normalizedKode = strtoupper((string) $kode);

        if (str_starts_with($normalizedKode, 'IN')) {
            // Inlet Kernel Silo conversion (uses highest matching score first).
            if ($value == 6.80)
                return 100;
            if ($value >= 6.49 && $value <= 7.10)
                return 90;
            if ($value >= 6.00 && $value <= 7.40)
                return 80;
            if ($value >= 5.49 && $value <= 7.70)
                return 70;
            if ($value >= 5.00 && $value <= 8.00)
                return 60;
            if ($value >= 4.50 && $value <= 9.00)
                return 50;
            return 0;
        }

        if (str_starts_with($normalizedKode, 'OUT')) {
            // Outlet Kernel Silo conversion (uses highest matching score first).
            if ($value >= 6.50 && $value <= 7.50)
                return 100;
            if ($value >= 6.10 && $value <= 7.80)
                return 90;
            if ($value >= 5.50 && $value <= 8.00)
                return 80;
            if ($value >= 5.10 && $value <= 9.00)
                return 70;
            if ($value >= 4.50 && $value <= 10.00)
                return 60;
            if ($value >= 0.00 && $value <= 20.00)
                return 50;
            return 0;
        }

        if ($config->direction === 'desc') {
            // Higher is better (≥)
            if ($value >= $config->limit_100)
                return 100;
            if ($value >= $config->limit_90)
                return 90;
            if ($value >= $config->limit_80)
                return 80;
            if ($value >= $config->limit_70)
                return 70;
            if ($value >= $config->limit_60)
                return 60;
            if ($value >= $config->limit_50)
                return 50;
            return 0;
        }

        // asc: Lower is better (≤)
        if ($value <= $config->limit_100)
            return 100;
        if ($value <= $config->limit_90)
            return 90;
        if ($value <= $config->limit_80)
            return 80;
        if ($value <= $config->limit_70)
            return 70;
        if ($value <= $config->limit_60)
            return 60;
        if ($value <= $config->limit_50)
            return 50;
        return 0;
    }

    public function laporan(Request $request)
    {
        if (!Auth::user()->can('view laporan kernel losses')) {
            abort(403);
        }

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $kode = $request->get('kode');
        $officeFilter = $this->resolveOfficeFilter($request);

        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $kernelQuery = KernelCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, function ($query) use ($kode) {
                $query->where('kode', $kode);
            })
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter));

        $dirtMoistQuery = KernelDirtMoistCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, function ($query) use ($kode) {
                $query->where('kode', $kode);
            })
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter));

        $qwtQuery = KernelQwt::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, function ($query) use ($kode) {
                $query->where('kode', $kode);
            })
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter));

        $rippleMillQuery = KernelRippleMill::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, function ($query) use ($kode) {
                $query->where('kode', $kode);
            })
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter));

        $destonerQuery = KernelDestoner::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, function ($query) use ($kode) {
                $query->where('kode', $kode);
            })
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter));

        $totalRecords = (clone $kernelQuery)->count();
        $avgLosses = (clone $kernelQuery)->avg('kernel_losses');

        $moduleCounts = [
            'kernel' => $totalRecords,
            'dirt_moist' => (clone $dirtMoistQuery)->count(),
            'qwt' => (clone $qwtQuery)->count(),
            'ripple_mill' => (clone $rippleMillQuery)->count(),
            'destoner' => (clone $destonerQuery)->count(),
        ];

        $calculations = $kernelQuery
            ->orderBy('created_at')
            ->paginate(25, ['*'], 'kernel_page');
        $calculations->appends($request->except('kernel_page'));

        $dirtMoistCalculations = $dirtMoistQuery
            ->orderBy('created_at')
            ->paginate(25, ['*'], 'dirt_moist_page');
        $dirtMoistCalculations->appends($request->except('dirt_moist_page'));

        $kernelQwtRows = $qwtQuery
            ->orderBy('created_at')
            ->paginate(25, ['*'], 'qwt_page');
        $kernelQwtRows->appends($request->except('qwt_page'));

        $rippleMillRows = $rippleMillQuery
            ->orderBy('created_at')
            ->paginate(25, ['*'], 'ripple_mill_page');
        $rippleMillRows->appends($request->except('ripple_mill_page'));

        $destonerRows = $destonerQuery
            ->orderBy('created_at')
            ->paginate(25, ['*'], 'destoner_page');
        $destonerRows->appends($request->except('destoner_page'));

        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $kodeOptions = KernelMasterData::getKodeDropdown();

        return view('kernel.laporan', compact(
            'calculations',
            'dirtMoistCalculations',
            'kernelQwtRows',
            'rippleMillRows',
            'destonerRows',
            'masterData',
            'kodeOptions',
            'startDate',
            'endDate',
            'officeFilter',
            'kode',
            'totalRecords',
            'avgLosses',
            'moduleCounts'
        ));
    }

    public function exportLaporan(Request $request)
    {
        if (!Auth::user()->can('view laporan kernel losses')) {
            abort(403);
        }

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $kode = $request->input('kode');
        $officeFilter = $this->resolveOfficeFilter($request);

        $data = $this->getUnifiedKernelReportRecords($startDate, $endDate, $officeFilter)
            ->when($kode, function ($collection) use ($kode) {
                return $collection->where('kode', $kode);
            })
            ->sortBy('created_at')
            ->values();

        $this->logExport('Laporan Kernel Losses', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'kode' => $kode,
            'office' => $officeFilter,
        ]);

        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        $filename = 'Laporan_Kernel_Losses_' . $startDate . '_to_' . $endDate . '.xlsx';

        return Excel::download(
            new KernelLaporanExport($data, $masterData, $startDate, $endDate, $kode),
            $filename
        );
    }

    private function getUnifiedKernelReportRecords(string $startDate, string $endDate, ?string $officeFilter = null)
    {
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $officeFilter = $officeFilter ?? $this->resolveOfficeFilter();

        $kernelRows = KernelCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->get()
            ->map(function ($row) {
                $row->source_module = 'Kernel Losses';
                return $row;
            });

        $dirtMoistRows = KernelDirtMoistCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->get()
            ->map(function ($row) {
                $metricPercent = (float) ($row->dirty_to_sampel ?? 0);

                return (object) [
                    'created_at' => $row->created_at,
                    'office' => $row->office,
                    'kode' => $row->kode,
                    'jenis' => $row->jenis,
                    'operator' => $row->operator,
                    'sampel_boy' => $row->sampel_boy,
                    'berat_sampel' => $row->berat_sampel,
                    'nut_utuh_nut' => null,
                    'nut_utuh_kernel' => null,
                    'nut_pecah_nut' => null,
                    'nut_pecah_kernel' => null,
                    'kernel_utuh' => null,
                    'kernel_pecah' => null,
                    'kernel_to_sampel_nut_utuh' => null,
                    'kernel_to_sampel_nut_pecah' => null,
                    'kernel_utuh_to_sampel' => null,
                    'kernel_pecah_to_sampel' => null,
                    'kernel_losses' => $metricPercent / 100,
                    'user' => $row->user,
                    'source_module' => 'Dirt & Moist (%Dirty)',
                ];
            });

        $qwtRows = KernelQwt::with('user')
            ->whereBetween('created_at', $range)
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->get()
            ->map(function ($row) {
                $metricPercent = (float) ($row->bn_tn ?? 0);

                return (object) [
                    'created_at' => $row->created_at,
                    'office' => $row->office,
                    'kode' => $row->kode,
                    'jenis' => $row->jenis,
                    'operator' => $row->operator,
                    'sampel_boy' => $row->sampel_boy,
                    'berat_sampel' => $row->sampel_setelah_kuarter,
                    'nut_utuh_nut' => null,
                    'nut_utuh_kernel' => null,
                    'nut_pecah_nut' => null,
                    'nut_pecah_kernel' => null,
                    'kernel_utuh' => null,
                    'kernel_pecah' => null,
                    'kernel_to_sampel_nut_utuh' => null,
                    'kernel_to_sampel_nut_pecah' => null,
                    'kernel_utuh_to_sampel' => null,
                    'kernel_pecah_to_sampel' => null,
                    'kernel_losses' => $metricPercent / 100,
                    'user' => $row->user,
                    'source_module' => 'QWT (BN/TN)',
                ];
            });

        $rippleRows = KernelRippleMill::with('user')
            ->whereBetween('created_at', $range)
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->get()
            ->map(function ($row) {
                $metricPercent = (float) ($row->efficiency ?? 0);

                return (object) [
                    'created_at' => $row->created_at,
                    'office' => $row->office,
                    'kode' => $row->kode,
                    'jenis' => $row->jenis,
                    'operator' => $row->operator,
                    'sampel_boy' => $row->sampel_boy,
                    'berat_sampel' => $row->berat_sampel,
                    'nut_utuh_nut' => null,
                    'nut_utuh_kernel' => null,
                    'nut_pecah_nut' => null,
                    'nut_pecah_kernel' => null,
                    'kernel_utuh' => null,
                    'kernel_pecah' => null,
                    'kernel_to_sampel_nut_utuh' => null,
                    'kernel_to_sampel_nut_pecah' => null,
                    'kernel_utuh_to_sampel' => null,
                    'kernel_pecah_to_sampel' => null,
                    'kernel_losses' => $metricPercent / 100,
                    'user' => $row->user,
                    'source_module' => 'Ripple Mill (Efficiency)',
                ];
            });

        $destonerRows = KernelDestoner::with('user')
            ->whereBetween('created_at', $range)
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->get()
            ->map(function ($row) {
                $metricPercent = (float) ($row->total_losses_kernel ?? 0);

                return (object) [
                    'created_at' => $row->created_at,
                    'office' => $row->office,
                    'kode' => $row->kode,
                    'jenis' => $row->jenis,
                    'operator' => $row->operator,
                    'sampel_boy' => $row->sampel_boy,
                    'berat_sampel' => $row->berat_sampel,
                    'nut_utuh_nut' => null,
                    'nut_utuh_kernel' => null,
                    'nut_pecah_nut' => null,
                    'nut_pecah_kernel' => null,
                    'kernel_utuh' => null,
                    'kernel_pecah' => null,
                    'kernel_to_sampel_nut_utuh' => null,
                    'kernel_to_sampel_nut_pecah' => null,
                    'kernel_utuh_to_sampel' => null,
                    'kernel_pecah_to_sampel' => null,
                    'kernel_losses' => $metricPercent / 100,
                    'user' => $row->user,
                    'source_module' => 'Destoner (Total Losses)',
                ];
            });

        return collect()
            ->concat($kernelRows)
            ->concat($dirtMoistRows)
            ->concat($qwtRows)
            ->concat($rippleRows)
            ->concat($destonerRows);
    }

    // ─── Individual module laporan pages ───────────────────────────────────────

    public function laporanDirtMoist(Request $request)
    {
        if (!Auth::user()->can('view laporan kernel losses'))
            abort(403);

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $kode = $request->get('kode');
        $officeFilter = $this->resolveOfficeFilter($request);
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $query = KernelDirtMoistCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->orderBy('created_at');

        $totalRecords = (clone $query)->count();
        $rows = $query->paginate(25)->appends($request->except('page'));
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $kodeOptions = KernelMasterData::getKodeDropdown();

        $avgDirty = KernelDirtMoistCalculation::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->avg('dirty_to_sampel');
        $avgMoist = KernelDirtMoistCalculation::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->avg('moist_percent');

        return view('kernel.laporan-dirt-moist', compact(
            'rows',
            'masterData',
            'kodeOptions',
            'startDate',
            'endDate',
            'officeFilter',
            'kode',
            'totalRecords',
            'avgDirty',
            'avgMoist'
        ));
    }

    public function exportLaporanDirtMoist(Request $request)
    {
        if (!Auth::user()->can('view laporan kernel losses'))
            abort(403);

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $kode = $request->get('kode');
        $officeFilter = $this->resolveOfficeFilter($request);
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $data = KernelDirtMoistCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->orderBy('created_at')
            ->get();

        $this->logExport('Laporan Dirt & Moist', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'kode' => $kode,
            'office' => $officeFilter,
        ]);

        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $filename = 'laporan-dirt-moist-' . $startDate . '-sd-' . $endDate . '.xlsx';

        return Excel::download(
            new KernelDirtMoistExport($data, $masterData, $startDate, $endDate, $kode),
            $filename
        );
    }

    public function laporanQwt(Request $request)
    {
        if (!Auth::user()->can('view laporan kernel losses'))
            abort(403);

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $kode = $request->get('kode');
        $officeFilter = $this->resolveOfficeFilter($request);
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $query = KernelQwt::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->orderBy('created_at');

        $totalRecords = (clone $query)->count();
        $rows = $query->paginate(25)->appends($request->except('page'));
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $kodeOptions = KernelMasterData::getKodeDropdown();

        $avgBnTn = KernelQwt::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->avg('bn_tn');
        $avgMoist = KernelQwt::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->avg('moisture');

        return view('kernel.laporan-qwt', compact(
            'rows',
            'masterData',
            'kodeOptions',
            'startDate',
            'endDate',
            'officeFilter',
            'kode',
            'totalRecords',
            'avgBnTn',
            'avgMoist'
        ));
    }

    public function exportLaporanQwt(Request $request)
    {
        if (!Auth::user()->can('view laporan kernel losses'))
            abort(403);

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $kode = $request->get('kode');
        $officeFilter = $this->resolveOfficeFilter($request);
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $data = KernelQwt::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->orderBy('created_at')
            ->get();

        $this->logExport('Laporan QWT Fibre Press', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'kode' => $kode,
            'office' => $officeFilter,
        ]);

        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $filename = 'laporan-qwt-fibre-press-' . $startDate . '-sd-' . $endDate . '.xlsx';

        return Excel::download(
            new KernelQwtExport($data, $masterData, $startDate, $endDate, $kode),
            $filename
        );
    }

    public function laporanRippleMill(Request $request)
    {
        if (!Auth::user()->can('view laporan kernel losses'))
            abort(403);

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $kode = $request->get('kode');
        $officeFilter = $this->resolveOfficeFilter($request);
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $query = KernelRippleMill::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->orderBy('created_at');

        $totalRecords = (clone $query)->count();
        $rows = $query->paginate(25)->appends($request->except('page'));
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $kodeOptions = KernelMasterData::getKodeDropdown();

        $avgEfficiency = KernelRippleMill::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->avg('efficiency');

        return view('kernel.laporan-ripple-mill', compact(
            'rows',
            'masterData',
            'kodeOptions',
            'startDate',
            'endDate',
            'officeFilter',
            'kode',
            'totalRecords',
            'avgEfficiency'
        ));
    }

    public function exportLaporanRippleMill(Request $request)
    {
        if (!Auth::user()->can('view laporan kernel losses'))
            abort(403);

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $kode = $request->get('kode');
        $officeFilter = $this->resolveOfficeFilter($request);
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $data = KernelRippleMill::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->orderBy('created_at')
            ->get();

        $this->logExport('Laporan Ripple Mill', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'kode' => $kode,
            'office' => $officeFilter,
        ]);

        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $filename = 'laporan-ripple-mill-' . $startDate . '-sd-' . $endDate . '.xlsx';

        return Excel::download(
            new KernelRippleMillExport($data, $masterData, $startDate, $endDate, $kode),
            $filename
        );
    }

    public function laporanDestoner(Request $request)
    {
        if (!Auth::user()->can('view laporan kernel losses'))
            abort(403);

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $kode = $request->get('kode');
        $officeFilter = $this->resolveOfficeFilter($request);
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $query = KernelDestoner::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->orderBy('created_at');

        $totalRecords = (clone $query)->count();
        $rows = $query->paginate(25)->appends($request->except('page'));
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $kodeOptions = KernelMasterData::getKodeDropdown();

        $avgLossKernelTbs = KernelDestoner::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->avg('loss_kernel_tbs');

        return view('kernel.laporan-destoner', compact(
            'rows',
            'masterData',
            'kodeOptions',
            'startDate',
            'endDate',
            'officeFilter',
            'kode',
            'totalRecords',
            'avgLossKernelTbs'
        ));
    }

    public function exportLaporanDestoner(Request $request)
    {
        if (!Auth::user()->can('view laporan kernel losses'))
            abort(403);

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());
        $kode = $request->get('kode');
        $officeFilter = $this->resolveOfficeFilter($request);
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $data = KernelDestoner::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($officeFilter !== 'all', fn($q) => $q->where('office', $officeFilter))
            ->orderBy('created_at')
            ->get();

        $this->logExport('Laporan Destoner', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'kode' => $kode,
            'office' => $officeFilter,
        ]);

        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $filename = 'laporan-destoner-' . $startDate . '-sd-' . $endDate . '.xlsx';

        return Excel::download(
            new KernelDestonerExport($data, $masterData, $startDate, $endDate, $kode),
            $filename
        );
    }
}
