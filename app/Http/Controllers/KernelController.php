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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class KernelController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $calculationsQuery = KernelCalculation::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        $userOffice = $this->getUserOffice();
        if ($userOffice) {
            $calculationsQuery->where('office', $userOffice);
        }

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
                ->when($userOffice, fn($q) => $q->where('office', $userOffice))
                ->count(),
            'calculations_count' => $totalCalculations,
        ];

        $kernelCalculations = $calculationsQuery->paginate(15, ['*'], 'calculations');

        $kernelCalculations->appends($request->only(['start_date', 'end_date', 'kode']));

        $kodeOptions = KernelMasterData::getKodeDropdown();
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        return view('kernel.index', compact(
            'kernelCalculations',
            'statistics',
            'kodeOptions',
            'masterData',
            'startDate',
            'endDate'
        ));
    }

    public function create()
    {
        if (!Auth::user()->can('create kernel losses')) {
            abort(403, 'Anda tidak memiliki akses untuk input data kernel losses.');
        }

        $kodeOptions = KernelMasterData::getKodeDropdown();
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
            'kode' => 'required|string',
            'jenis' => 'required|string',
            'operator' => $this->getOperatorValidationRules($userOffice, true, 'kernel'),
            'sampel_boy' => 'required|string|max:255',
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

        // Save calculation record (all ratios stored as percentages ×100)
        $beratSampel = $validated['berat_sampel'];
        $ktsNutUtuh = $beratSampel > 0 ? round(($validated['nut_utuh_kernel'] / $beratSampel) * 100, 6) : 0;
        $ktsNutPecah = $beratSampel > 0 ? round(($validated['nut_pecah_kernel'] / $beratSampel) * 100, 6) : 0;
        $kernelUtuhToSampel = $beratSampel > 0 ? round(($validated['kernel_utuh'] / $beratSampel) * 100, 6) : 0;
        $kernelPecahToSampel = $beratSampel > 0 ? round(($validated['kernel_pecah'] / $beratSampel) * 100, 6) : 0;
        $kernelLosses = ($ktsNutUtuh + $ktsNutPecah + $kernelUtuhToSampel + $kernelPecahToSampel) / 100;

        KernelCalculation::create([
            'user_id' => Auth::id(),
            'office' => $userOffice,
            'rounded_time' => $this->getRoundedTime(),
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

        $message = 'Data Kernel Losses berhasil disimpan.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        if ($request->input('_action') === 'save_and_add') {
            return redirect()->route('kernel.create')->with('success', $message);
        }

        return redirect()->route('kernel.index')->with('success', $message);
    }

    public function dirtMoistIndex(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = KernelDirtMoistCalculation::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        $userOffice = $this->getUserOffice();
        if ($userOffice) {
            $query->where('office', $userOffice);
        }

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
                ->when($userOffice, fn($q) => $q->where('office', $userOffice))
                ->count(),
            'calculations_count' => $totalRows,
        ];

        $dirtMoistCalculations = $query->paginate(15, ['*'], 'calculations');
        $dirtMoistCalculations->appends($request->only(['start_date', 'end_date', 'kode']));

        $kodeOptions = $this->getDirtMoistKodeOptions();
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        return view('kernel.dirt-moist.index', compact(
            'dirtMoistCalculations',
            'statistics',
            'kodeOptions',
            'masterData',
            'startDate',
            'endDate'
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

        $dirtyToSampel = round(($validated['berat_dirty'] / $validated['berat_sampel']) * 100, 6);

        $limitMap = $this->getDirtMoistLimitMap();
        $limitConfig = $limitMap[$validated['kode']] ?? ['dirty' => null, 'moist' => null];
        $dirtyLimitOperator = data_get($limitConfig, 'dirty.operator');
        $dirtyLimitValue = data_get($limitConfig, 'dirty.value');
        $moistLimitOperator = data_get($limitConfig, 'moist.operator');
        $moistLimitValue = data_get($limitConfig, 'moist.value');

        KernelDirtMoistCalculation::create([
            'user_id' => Auth::id(),
            'office' => $userOffice,
            'rounded_time' => $this->getRoundedTime(),
            'kode' => $validated['kode'],
            'jenis' => $validated['jenis'],
            'operator' => $validated['operator'],
            'sampel_boy' => $validated['sampel_boy'] ?? Auth::user()->name,
            'berat_sampel' => $validated['berat_sampel'],
            'berat_dirty' => $validated['berat_dirty'],
            'dirty_to_sampel' => $dirtyToSampel,
            'moist_percent' => $validated['moist_percent'],
            'dirty_limit_operator' => $dirtyLimitOperator,
            'dirty_limit_value' => $dirtyLimitValue,
            'moist_limit_operator' => $moistLimitOperator,
            'moist_limit_value' => $moistLimitValue,
        ]);

        $message = 'Data dirt & moist berhasil disimpan.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        if ($request->input('_action') === 'save_and_add') {
            return redirect()->route('kernel.dirt-moist.create')->with('success', $message);
        }

        return redirect()->route('kernel.dirt-moist.index')->with('success', $message);
    }

    public function qwtIndex(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = KernelQwt::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        $userOffice = $this->getUserOffice();
        if ($userOffice) {
            $query->where('office', $userOffice);
        }

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
                ->when($userOffice, fn($q) => $q->where('office', $userOffice))
                ->count(),
            'calculations_count' => $totalRows,
        ];

        $kernelQwtRows = $query->paginate(15, ['*'], 'calculations');
        $kernelQwtRows->appends($request->only(['start_date', 'end_date', 'kode']));

        $kodeOptions = $this->getQwtKodeOptions();
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        return view('kernel.qwt.index', compact(
            'kernelQwtRows',
            'statistics',
            'kodeOptions',
            'masterData',
            'startDate',
            'endDate'
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

        KernelQwt::create([
            'user_id' => Auth::id(),
            'office' => $userOffice,
            'rounded_time' => $this->getRoundedTime(),
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

        $message = 'Data QWT Fibre Press berhasil disimpan.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        if ($request->input('_action') === 'save_and_add') {
            return redirect()->route('kernel.qwt.create')->with('success', $message);
        }

        return redirect()->route('kernel.qwt.index')->with('success', $message);
    }

    public function rippleMillIndex(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = KernelRippleMill::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        $userOffice = $this->getUserOffice();
        if ($userOffice) {
            $query->where('office', $userOffice);
        }

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
                ->when($userOffice, fn($q) => $q->where('office', $userOffice))
                ->count(),
            'calculations_count' => $totalRows,
        ];

        $rippleMillRows = $query->paginate(15, ['*'], 'calculations');
        $rippleMillRows->appends($request->only(['start_date', 'end_date', 'kode']));

        $kodeOptions = $this->getRippleMillKodeOptions();
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        return view('kernel.ripple-mill.index', compact(
            'rippleMillRows',
            'statistics',
            'kodeOptions',
            'masterData',
            'startDate',
            'endDate'
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

        $beratSampel = (float) $validated['berat_sampel'];
        $sampleNutUtuh = round(((float) $validated['berat_nut_utuh'] / $beratSampel) * 100, 6);
        $sampleNutPecah = round(((float) $validated['berat_nut_pecah'] / $beratSampel) * 100, 6);
        $efficiency = round(100 - $sampleNutPecah - $sampleNutUtuh, 6);

        $limitMap = $this->getRippleMillLimitMap();
        $kode = $validated['kode'];

        KernelRippleMill::create([
            'user_id' => Auth::id(),
            'office' => $userOffice,
            'rounded_time' => $this->getRoundedTime(),
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

        $message = 'Data Ripple Mill berhasil disimpan.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        if ($request->input('_action') === 'save_and_add') {
            return redirect()->route('kernel.ripple-mill.create')->with('success', $message);
        }

        return redirect()->route('kernel.ripple-mill.index')->with('success', $message);
    }

    public function destonerIndex(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = KernelDestoner::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        $userOffice = $this->getUserOffice();
        if ($userOffice) {
            $query->where('office', $userOffice);
        }

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
                ->when($userOffice, fn($q) => $q->where('office', $userOffice))
                ->count(),
            'calculations_count' => $totalRows,
        ];

        $destonerRows = $query->paginate(15, ['*'], 'calculations');
        $destonerRows->appends($request->only(['start_date', 'end_date', 'kode']));

        $kodeOptions = $this->getDestonerKodeOptions();
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        return view('kernel.destoner.index', compact(
            'destonerRows',
            'statistics',
            'kodeOptions',
            'masterData',
            'startDate',
            'endDate'
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

        KernelDestoner::create([
            'user_id' => Auth::id(),
            'office' => $userOffice,
            'rounded_time' => $this->getRoundedTime(),
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

        $message = 'Data Destoner berhasil disimpan.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        if ($request->input('_action') === 'save_and_add') {
            return redirect()->route('kernel.destoner.create')->with('success', $message);
        }

        return redirect()->route('kernel.destoner.index')->with('success', $message);
    }

    public function destroyRecord(KernelRecord $kernelRecord)
    {
        if (!Auth::user()->can('delete kernel losses')) {
            abort(403);
        }
        $kernelRecord->delete();
        return back()->with('success', 'Data jenis & sampel berhasil dihapus.');
    }

    public function destroyCalculation(KernelCalculation $kernelCalculation)
    {
        if (!Auth::user()->can('delete kernel losses')) {
            abort(403);
        }
        $kernelCalculation->delete();
        return back()->with('success', 'Data perhitungan lab berhasil dihapus.');
    }

    public function rekap(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        [$allKodesData, $dataByDate] = $this->buildRekapData($startDate, $endDate);
        $columnGroups = $this->getKernelColumnGroups();
        $masterMap = collect($allKodesData)->keyBy('kode');

        return view('kernel.rekap', compact('allKodesData', 'dataByDate', 'startDate', 'endDate', 'columnGroups', 'masterMap'));
    }

    public function exportRekap(Request $request)
    {
        if (!Auth::user()->can('view rekap kernel losses')) {
            abort(403);
        }

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        [$allKodesData, $dataByDate] = $this->buildRekapData($startDate, $endDate);
        $columnGroups = $this->getKernelColumnGroups();

        $filename = 'Rekap_Kernel_Losses_'
            . \Carbon\Carbon::parse($startDate)->format('Ymd') . '_'
            . \Carbon\Carbon::parse($endDate)->format('Ymd') . '.xlsx';

        return Excel::download(
            new KernelRekapExport($dataByDate, $allKodesData, $startDate, $endDate, $columnGroups),
            $filename
        );
    }

    private function buildRekapData(string $startDate, string $endDate): array
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

        $calculations = $this->getUnifiedKernelReportRecords($startDate, $endDate)
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

        [$reportData, $allKodesData, $operators, $operatorActivities, $reportDates, $dailyPerformance, $allIndividualRecords, $operatorDailyPerformance] = $this->buildPerformanceData($startDate, $endDate);

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

        [$reportData, $allKodesData, $operators, $operatorActivities, $reportDates, $dailyPerformance] = $this->buildPerformanceData($startDate, $endDate);

        $filename = 'Performance_Kernel_'
            . Carbon::parse($startDate)->format('Ymd') . '_'
            . Carbon::parse($endDate)->format('Ymd') . '.xlsx';

        return Excel::download(
            new KernelPerformanceExport($reportData, $allKodesData, $operators, $reportDates, $dailyPerformance, $startDate, $endDate),
            $filename
        );
    }

    private function buildPerformanceData(string $startDate, string $endDate): array
    {
        $bobotConfigs = KernelBobotConfig::all()->keyBy('jenis');

        // Get all active kodes that have a matching bobot config
        $allKodesData = KernelMasterData::where('is_active', true)
            ->get()
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
        $calculations = $this->getUnifiedKernelReportRecords($startDate, $endDate)
            ->sortBy('created_at')
            ->values();

        // Build individual records (flat, one row per calculation)
        $individualRecords = [];
        foreach ($calculations as $calc) {
            $valuePercent = (float) ($calc->kernel_losses ?? 0) * 100;
            $config = $this->mapKodeToKernelConfig($calc->kode, $bobotConfigs);
            $bobot = $config ? $this->calculateKernelBobot($valuePercent, $config) : null;
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
                    $bobot = $config ? $this->calculateKernelBobot($valuePercent, $config) : null;

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

    private function getRoundedTime(): Carbon
    {
        $now = now();
        $minute = (int) $now->format('i');
        $roundedMinute = $minute < 30 ? 0 : 30;

        return $now->copy()->setTime((int) $now->format('H'), $roundedMinute, 0);
    }

    private function calculateKernelBobot(float $value, $config): int
    {
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

        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $userOffice = $this->getUserOffice();

        $kernelQuery = KernelCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, function ($query) use ($kode) {
                $query->where('kode', $kode);
            })
            ->when($userOffice, fn($q) => $q->where('office', $userOffice));

        $dirtMoistQuery = KernelDirtMoistCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, function ($query) use ($kode) {
                $query->where('kode', $kode);
            })
            ->when($userOffice, fn($q) => $q->where('office', $userOffice));

        $qwtQuery = KernelQwt::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, function ($query) use ($kode) {
                $query->where('kode', $kode);
            })
            ->when($userOffice, fn($q) => $q->where('office', $userOffice));

        $rippleMillQuery = KernelRippleMill::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, function ($query) use ($kode) {
                $query->where('kode', $kode);
            })
            ->when($userOffice, fn($q) => $q->where('office', $userOffice));

        $destonerQuery = KernelDestoner::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, function ($query) use ($kode) {
                $query->where('kode', $kode);
            })
            ->when($userOffice, fn($q) => $q->where('office', $userOffice));

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

        $userOffice = $this->getUserOffice();

        $data = $this->getUnifiedKernelReportRecords($startDate, $endDate)
            ->when($kode, function ($collection) use ($kode) {
                return $collection->where('kode', $kode);
            })
            ->when($userOffice, function ($collection) use ($userOffice) {
                return $collection->where('office', $userOffice);
            })
            ->sortBy('created_at')
            ->values();

        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');

        $filename = 'Laporan_Kernel_Losses_' . $startDate . '_to_' . $endDate . '.xlsx';

        return Excel::download(
            new KernelLaporanExport($data, $masterData, $startDate, $endDate, $kode),
            $filename
        );
    }

    private function getUnifiedKernelReportRecords(string $startDate, string $endDate)
    {
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $userOffice = $this->getUserOffice();

        $kernelRows = KernelCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->get()
            ->map(function ($row) {
                $row->source_module = 'Kernel Losses';
                return $row;
            });

        $dirtMoistRows = KernelDirtMoistCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->get()
            ->map(function ($row) {
                $isOutlet = str_starts_with((string) $row->kode, 'OUT');
                $metricPercent = $isOutlet
                    ? (float) ($row->moist_percent ?? 0)
                    : (float) ($row->dirty_to_sampel ?? 0);

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
                    'source_module' => $isOutlet ? 'Dirt & Moist (%Moist)' : 'Dirt & Moist (%Dirty)',
                ];
            });

        $qwtRows = KernelQwt::with('user')
            ->whereBetween('created_at', $range)
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
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
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
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
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
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
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $userOffice = $this->getUserOffice();

        $userOffice = $this->getUserOffice();

        $userOffice = $this->getUserOffice();

        $userOffice = $this->getUserOffice();

        $userOffice = $this->getUserOffice();

        $userOffice = $this->getUserOffice();

        $query = KernelDirtMoistCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->orderBy('created_at');

        $totalRecords = (clone $query)->count();
        $rows = $query->paginate(25)->appends($request->except('page'));
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $kodeOptions = KernelMasterData::getKodeDropdown();

        $avgDirty = KernelDirtMoistCalculation::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->avg('dirty_to_sampel');
        $avgMoist = KernelDirtMoistCalculation::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->avg('moist_percent');

        return view('kernel.laporan-dirt-moist', compact(
            'rows',
            'masterData',
            'kodeOptions',
            'startDate',
            'endDate',
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
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $userOffice = $this->getUserOffice();

        $data = KernelDirtMoistCalculation::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->orderBy('created_at')
            ->get();

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
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $userOffice = $this->getUserOffice();

        $query = KernelQwt::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->orderBy('created_at');

        $totalRecords = (clone $query)->count();
        $rows = $query->paginate(25)->appends($request->except('page'));
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $kodeOptions = KernelMasterData::getKodeDropdown();

        $avgBnTn = KernelQwt::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->avg('bn_tn');
        $avgMoist = KernelQwt::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->avg('moisture');

        return view('kernel.laporan-qwt', compact(
            'rows',
            'masterData',
            'kodeOptions',
            'startDate',
            'endDate',
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
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $userOffice = $this->getUserOffice();

        $data = KernelQwt::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->orderBy('created_at')
            ->get();

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
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $userOffice = $this->getUserOffice();

        $query = KernelRippleMill::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->orderBy('created_at');

        $totalRecords = (clone $query)->count();
        $rows = $query->paginate(25)->appends($request->except('page'));
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $kodeOptions = KernelMasterData::getKodeDropdown();

        $avgEfficiency = KernelRippleMill::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->avg('efficiency');

        return view('kernel.laporan-ripple-mill', compact(
            'rows',
            'masterData',
            'kodeOptions',
            'startDate',
            'endDate',
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
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $userOffice = $this->getUserOffice();

        $data = KernelRippleMill::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->orderBy('created_at')
            ->get();

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
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $userOffice = $this->getUserOffice();

        $query = KernelDestoner::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->orderBy('created_at');

        $totalRecords = (clone $query)->count();
        $rows = $query->paginate(25)->appends($request->except('page'));
        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $kodeOptions = KernelMasterData::getKodeDropdown();

        $avgLossKernelTbs = KernelDestoner::whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->avg('loss_kernel_tbs');

        return view('kernel.laporan-destoner', compact(
            'rows',
            'masterData',
            'kodeOptions',
            'startDate',
            'endDate',
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
        $range = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];

        $userOffice = $this->getUserOffice();

        $data = KernelDestoner::with('user')
            ->whereBetween('created_at', $range)
            ->when($kode, fn($q) => $q->where('kode', $kode))
            ->when($userOffice, fn($q) => $q->where('office', $userOffice))
            ->orderBy('created_at')
            ->get();

        $masterData = KernelMasterData::where('is_active', true)->get()->keyBy('kode');
        $filename = 'laporan-destoner-' . $startDate . '-sd-' . $endDate . '.xlsx';

        return Excel::download(
            new KernelDestonerExport($data, $masterData, $startDate, $endDate, $kode),
            $filename
        );
    }
}
