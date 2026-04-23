<?php

namespace App\Http\Controllers;

use App\Exports\PerformanceSampelBoyExport;
use App\Exports\ProcessAnalysisExport;
use App\Models\AnalisaUsb;
use App\Models\FfaMoisture;
use App\Models\KernelCalculation;
use App\Models\KernelDirtMoistCalculation;
use App\Models\KernelDestoner;
use App\Models\KernelMasterData;
use App\Models\KernelMesin;
use App\Models\KernelProsses;
use App\Models\KernelQwt;
use App\Models\KernelRippleMill;
use App\Models\OilFoss;
use App\Models\SpintestCot;
use App\Models\SpintestCst;
use App\Models\SpintestFeedDecanter;
use App\Models\SpintestLightPhase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class ProcessController extends Controller
{
    private array $timedCodeRowsCache = [];

    private const TEAM_OPTIONS = ['Tim 1', 'Tim 2'];
    private const CST_MACHINES = ['CST1', 'CST2'];
    private const DECANTER_MACHINES = ['Alfa Laval 1', 'Alfa Laval 2', 'GEA', 'Flottweg'];
    private const LIGHT_PHASE_MACHINES = ['Alfa Laval 1', 'Alfa Laval 2', 'GEA', 'Flottweg'];

    // Hardcoded master mesin per PT.
    // Silakan ubah daftar di konstanta ini sesuai kebutuhan masing-masing PT.
    private const MACHINE_GROUPS_YBS = [
        'FIBRE CYCLONE' => [
            'FIBRE CYCLONE 1',
            'FIBRE CYCLONE 2',
        ],
        'LTDS' => [
            'LTDS 1',
            'LTDS 2',
            'LTDS 3',
            'LTDS 4',
        ],
        'CLAYBATH WET SHELL' => [
            'CLAYBATH WET SHELL 1',
            'CLAYBATH WET SHELL 2',
            'CLAYBATH WET SHELL 3',
        ],
        'INLET KERNEL SILO' => [
            'INLET KERNEL SILO 1',
            'INLET KERNEL SILO 2',
            'INLET KERNEL SILO 3',
            'INLET KERNEL SILO 4',
            'INLET KERNEL SILO 5',
        ],
        'OUTLET KERNEL SILO TO BUNKER' => [
            'OUTLET KERNEL SILO 1 TO BUNKER',
            'OUTLET KERNEL SILO 2 TO BUNKER',
            'OUTLET KERNEL SILO 3 TO BUNKER',
            'OUTLET KERNEL SILO 4 TO BUNKER',
            'OUTLET KERNEL SILO 5 TO BUNKER',
        ],
        'PRESS' => [
            'PRESS 1',
            'PRESS 2',
            'PRESS 3',
            'PRESS 4',
            'PRESS 5',
            'PRESS 6',
            'PRESS 7',
            'PRESS 8',
            'PRESS 9',
        ],
        'RIPPLE MILL' => [
            'RIPPLE MILL NO. 1',
            'RIPPLE MILL NO. 2',
            'RIPPLE MILL NO. 3',
            'RIPPLE MILL NO. 4',
            'RIPPLE MILL NO. 5',
            'RIPPLE MILL NO. 6',
            'RIPPLE MILL NO. 7',
        ],
        'DESTONER' => [
            'DESTONER 1',
            'DESTONER 2',
        ],
    ];

    // Ubah jika daftar SUN berbeda dengan YBS.
    private const MACHINE_GROUPS_SUN = [
        'FIBRE CYCLONE' => [
            'FIBRE CYCLONE 1',
            'FIBRE CYCLONE 2',
        ],
        'LTDS' => [
            'LTDS 1',
            'LTDS 2',
        ],
        'CLAYBATH WET SHELL' => [
            'CLAYBATH WET SHELL 1',
        ],
        'INLET KERNEL SILO' => [
            'INLET KERNEL SILO 1',
            'INLET KERNEL SILO 2',
        ],
        'OUTLET KERNEL SILO TO BUNKER' => [
            'OUTLET KERNEL SILO 1 TO BUNKER',
            'OUTLET KERNEL SILO 2 TO BUNKER',
            'OUTLET KERNEL SILO 3 TO BUNKER',
            'OUTLET KERNEL SILO 4 TO BUNKER',
        ],
        'PRESS' => [
            'PRESS 1',
            'PRESS 2',
            'PRESS 3',
            'PRESS 4',
        ],
        'RIPPLE MILL' => [
            'RIPPLE MILL NO. 1',
            'RIPPLE MILL NO. 2',
            'RIPPLE MILL NO. 3',
        ],
        'DESTONER' => [
            'DESTONER 1',
            'DESTONER 2',
        ],
    ];

    // Ubah jika daftar SJN berbeda dengan YBS.
    private const MACHINE_GROUPS_SJN = self::MACHINE_GROUPS_YBS;

    // YBS: interval sampling per kelompok mesin (tetap harus berturut-turut)
    private const PERFORMANCE_GROUP_INTERVAL_MINUTES = [
        'fibre_cyclone' => 120,
        'ltds' => 120,
        'claybath_wet_shell' => 60,
        'inlet_kernel_silo' => 60,
        'outlet_kernel_silo' => 60,
        'press' => 240,
        'eficiency' => 120,
        'destoner' => 240,
    ];

    // SUN office: all sampling interval 2 hours
    private const PERFORMANCE_GROUP_INTERVAL_MINUTES_SUN = [
        'fibre_cyclone' => 120,
        'ltds' => 120,
        'claybath_wet_shell' => 120,
        'inlet_kernel_silo' => 120,
        'outlet_kernel_silo' => 120,
        'press' => 120,
        'eficiency' => 120,
        'destoner' => 120,
    ];

    private const PERFORMANCE_CODE_GROUPS = [
        'fibre_cyclone' => ['FC1', 'FC2'],
        'ltds' => ['L1', 'L2', 'L3', 'L4'],
        'claybath_wet_shell' => ['CWS', 'CWS1', 'CWS2', 'CWS3'],
        'inlet_kernel_silo' => ['IN1', 'IN2', 'IN3', 'IN4', 'IN5'],
        'outlet_kernel_silo' => ['OUT1', 'OUT2', 'OUT3', 'OUT4', 'OUT5'],
        'press' => ['P1', 'P2', 'P3', 'P4', 'P5', 'P6', 'P7', 'P8', 'P9'],
        'eficiency' => ['R1', 'R2', 'R3', 'R4', 'R5', 'R6', 'R7'],
        'destoner' => ['D1', 'D2'],
    ];

    private const PERFORMANCE_DETAIL_CODES = [
        ['code' => 'FC1', 'label' => 'FC1', 'aliases' => ['FC1']],
        ['code' => 'FC2', 'label' => 'FC2', 'aliases' => ['FC2']],
        ['code' => 'L1', 'label' => 'LTDS1', 'aliases' => ['L1']],
        ['code' => 'L2', 'label' => 'LTDS2', 'aliases' => ['L2']],
        ['code' => 'L3', 'label' => 'LTDS3', 'aliases' => ['L3']],
        ['code' => 'L4', 'label' => 'LTDS4', 'aliases' => ['L4']],
        ['code' => 'CWS1', 'label' => 'CWS1', 'aliases' => ['CWS', 'CWS1']],
        ['code' => 'CWS2', 'label' => 'CWS2', 'aliases' => ['CWS2']],
        ['code' => 'CWS3', 'label' => 'CWS3', 'aliases' => ['CWS3']],
        ['code' => 'IN1', 'label' => 'IN1', 'aliases' => ['IN1']],
        ['code' => 'IN2', 'label' => 'IN2', 'aliases' => ['IN2']],
        ['code' => 'IN3', 'label' => 'IN3', 'aliases' => ['IN3']],
        ['code' => 'IN4', 'label' => 'IN4', 'aliases' => ['IN4']],
        ['code' => 'IN5', 'label' => 'IN5', 'aliases' => ['IN5']],
        ['code' => 'OUT1', 'label' => 'OUT1', 'aliases' => ['OUT1']],
        ['code' => 'OUT2', 'label' => 'OUT2', 'aliases' => ['OUT2']],
        ['code' => 'OUT3', 'label' => 'OUT3', 'aliases' => ['OUT3']],
        ['code' => 'OUT4', 'label' => 'OUT4', 'aliases' => ['OUT4']],
        ['code' => 'OUT5', 'label' => 'OUT5', 'aliases' => ['OUT5']],
        ['code' => 'P1', 'label' => 'P1', 'aliases' => ['P1']],
        ['code' => 'P2', 'label' => 'P2', 'aliases' => ['P2']],
        ['code' => 'P3', 'label' => 'P3', 'aliases' => ['P3']],
        ['code' => 'P4', 'label' => 'P4', 'aliases' => ['P4']],
        ['code' => 'P5', 'label' => 'P5', 'aliases' => ['P5']],
        ['code' => 'P6', 'label' => 'P6', 'aliases' => ['P6']],
        ['code' => 'P7', 'label' => 'P7', 'aliases' => ['P7']],
        ['code' => 'P8', 'label' => 'P8', 'aliases' => ['P8']],
        ['code' => 'P9', 'label' => 'P9', 'aliases' => ['P9']],
        ['code' => 'R1', 'label' => 'R1', 'aliases' => ['R1']],
        ['code' => 'R2', 'label' => 'R2', 'aliases' => ['R2']],
        ['code' => 'R3', 'label' => 'R3', 'aliases' => ['R3']],
        ['code' => 'R4', 'label' => 'R4', 'aliases' => ['R4']],
        ['code' => 'R5', 'label' => 'R5', 'aliases' => ['R5']],
        ['code' => 'R6', 'label' => 'R6', 'aliases' => ['R6']],
        ['code' => 'R7', 'label' => 'R7', 'aliases' => ['R7']],
        ['code' => 'D1', 'label' => 'D1', 'aliases' => ['D1']],
        ['code' => 'D2', 'label' => 'D2', 'aliases' => ['D2']],
    ];

    public function index(Request $request)
    {
        $roleFlags = $this->resolveProcessRoleFlags();
        $teamMembers = $this->getTeamMembersByOffice();

        $userOffice = trim((string) (auth()->user()->office ?? ''));
        $officeFilter = $userOffice !== ''
            ? $userOffice
            : trim((string) $request->input('office', 'all'));

        $officeOptions = ['YBS', 'SUN', 'SJN'];
        if ($officeFilter !== 'all' && !in_array($officeFilter, $officeOptions, true)) {
            $officeFilter = $userOffice !== '' ? $userOffice : 'all';
        }

        $records = KernelProsses::query()
            ->withCount('mesin')
            ->when($officeFilter !== 'all', fn($query) => $query->where('office', $officeFilter))
            ->orderByDesc('process_date')
            ->orderByDesc('id')
            ->limit(100)
            ->get()
            ->map(function (KernelProsses $record): array {
                return [
                    'id' => $record->id,
                    'process_date' => optional($record->process_date)->format('d/m/Y'),
                    'office' => (string) ($record->office ?? '-'),
                    'input_team' => (string) ($record->input_team ?? '-'),
                    'mesin_count' => $record->mesin_count,
                ];
            });

        $machineGroups = $this->getMachineGroupsForOffice($userOffice !== '' ? $userOffice : null);

        return view('process.index', [
            'teamMembers' => $teamMembers,
            'records' => $records,
            'machineGroups' => $machineGroups,
            'officeFilter' => $officeFilter,
            'officeOptions' => $officeOptions,
            'canManageTeamMeta' => $roleFlags['can_manage_team_meta'],
            'canManageMachineData' => $roleFlags['can_manage_machine_data'],
        ]);
    }

    public function store(Request $request)
    {
        $roleFlags = $this->resolveProcessRoleFlags();
        $teamMemberRules = $this->buildTeamMemberValidationRules();
        $inputTeam = (string) $request->input('input_team');
        if (!in_array($inputTeam, self::TEAM_OPTIONS, true)) {
            return back()->withInput()->withErrors([
                'input_team' => 'Tim input tidak valid.',
            ]);
        }

        $office = (string) auth()->user()->office;
        $existingProcess = KernelProsses::query()
            ->whereDate('process_date', $request->input('process_date'))
            ->where('office', $office)
            ->where('input_team', $inputTeam)
            ->first();

        if (!$roleFlags['can_manage_team_meta'] && $roleFlags['can_manage_machine_data']) {
            $validated = $request->validate([
                'process_date' => ['required', 'date'],
                'machines' => ['nullable', 'array'],
                'machines.*.selected' => ['nullable'],
                'machines.*.machine_name' => ['nullable', 'string', 'max:150'],
                'machines.*.machine_group' => ['nullable', 'string', 'max:120'],
                'machines.*.team_name' => ['nullable', 'string', 'in:Tim 1,Tim 2'],
                'machines.*.start_time' => ['nullable', 'date_format:H:i'],
                'machines.*.end_time' => ['nullable', 'date_format:H:i'],
                'spare_machines' => ['nullable', 'array'],
                'spare_machines.*.team_name' => ['nullable', 'string', 'in:Tim 1,Tim 2'],
                'spare_machines.*.machine_name' => ['nullable', 'string', 'max:150'],
                'spare_machines.*.start_time' => ['nullable', 'date_format:H:i'],
                'spare_machines.*.end_time' => ['nullable', 'date_format:H:i'],
                'other_conditions' => ['nullable', 'array'],
                'other_conditions.*.team_name' => ['nullable', 'string', 'in:Tim 1,Tim 2'],
                'other_conditions.*.reason' => ['nullable', 'string', 'max:255', 'required_with:other_conditions.*.start_time,other_conditions.*.end_time'],
                'other_conditions.*.start_time' => ['nullable', 'date_format:H:i', 'required_with:other_conditions.*.reason,other_conditions.*.end_time'],
                'other_conditions.*.end_time' => ['nullable', 'date_format:H:i', 'required_with:other_conditions.*.reason,other_conditions.*.start_time'],
            ]);

            if (!$existingProcess) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'process_date' => 'Data checklist dan shift belum diinput Analis untuk tanggal dan tim ini.',
                    ]);
            }

            $machinePayload = $this->extractMachinePayload($request, $inputTeam, $office);
            $otherConditionsByTeam = $this->extractOtherConditionsPayload($request, $inputTeam);

            $existingProcess->mesin()
                ->where('team_name', $inputTeam)
                ->delete();

            if (!empty($machinePayload)) {
                $existingProcess->mesin()->createMany($machinePayload);
            }

            if ($inputTeam === 'Tim 1') {
                $existingProcess->update([
                    'team_1_other_conditions' => $otherConditionsByTeam['Tim 1'] ?? [],
                ]);
            } else {
                $existingProcess->update([
                    'team_2_other_conditions' => $otherConditionsByTeam['Tim 2'] ?? [],
                ]);
            }

            return redirect()
                ->route('process.index')
                ->with('success', 'Detail mesin ' . $inputTeam . ' berhasil disimpan.');
        }

        if (!$roleFlags['can_manage_team_meta']) {
            return back()
                ->withInput()
                ->withErrors([
                    'input_team' => 'Anda tidak memiliki akses untuk mengubah checklist tim dan jam shift.',
                ]);
        }

        $isTeamOneInput = $inputTeam === 'Tim 1';

        $validated = $request->validate([
            'process_date' => ['required', 'date'],

            'team_1_start_time' => [$isTeamOneInput ? 'required' : 'nullable', 'date_format:H:i'],
            'team_1_end_time' => [$isTeamOneInput ? 'required' : 'nullable', 'date_format:H:i'],
            'team_1_start_downtime' => ['nullable', 'date_format:H:i', 'required_with:team_1_end_downtime'],
            'team_1_end_downtime' => ['nullable', 'date_format:H:i', 'required_with:team_1_start_downtime'],
            'team_1_members' => ['nullable', 'array'],
            'team_1_members.*' => $teamMemberRules,

            'team_2_start_time' => [$isTeamOneInput ? 'nullable' : 'required', 'date_format:H:i'],
            'team_2_end_time' => [$isTeamOneInput ? 'nullable' : 'required', 'date_format:H:i'],
            'team_2_start_downtime' => ['nullable', 'date_format:H:i', 'required_with:team_2_end_downtime'],
            'team_2_end_downtime' => ['nullable', 'date_format:H:i', 'required_with:team_2_start_downtime'],
            'team_2_members' => ['nullable', 'array'],
            'team_2_members.*' => $teamMemberRules,

            'machines' => ['nullable', 'array'],
            'machines.*.selected' => ['nullable'],
            'machines.*.machine_name' => ['nullable', 'string', 'max:150'],
            'machines.*.machine_group' => ['nullable', 'string', 'max:120'],
            'machines.*.team_name' => ['nullable', 'string', 'in:Tim 1,Tim 2'],
            'machines.*.start_time' => ['nullable', 'date_format:H:i'],
            'machines.*.end_time' => ['nullable', 'date_format:H:i'],

            'spare_machines' => ['nullable', 'array'],
            'spare_machines.*.team_name' => ['nullable', 'string', 'in:Tim 1,Tim 2'],
            'spare_machines.*.machine_name' => ['nullable', 'string', 'max:150'],
            'spare_machines.*.start_time' => ['nullable', 'date_format:H:i'],
            'spare_machines.*.end_time' => ['nullable', 'date_format:H:i'],

            'other_conditions' => ['nullable', 'array'],
            'other_conditions.*.team_name' => ['nullable', 'string', 'in:Tim 1,Tim 2'],
            'other_conditions.*.reason' => ['nullable', 'string', 'max:255', 'required_with:other_conditions.*.start_time,other_conditions.*.end_time'],
            'other_conditions.*.start_time' => ['nullable', 'date_format:H:i', 'required_with:other_conditions.*.reason,other_conditions.*.end_time'],
            'other_conditions.*.end_time' => ['nullable', 'date_format:H:i', 'required_with:other_conditions.*.reason,other_conditions.*.start_time'],
        ]);

        $alreadyExists = $existingProcess !== null;

        if ($alreadyExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'process_date' => 'Data proses untuk tanggal ini dan tim ini sudah diinput.',
                ]);
        }

        $conflicts = array_intersect(
            $isTeamOneInput ? ($validated['team_1_members'] ?? []) : [],
            !$isTeamOneInput ? ($validated['team_2_members'] ?? []) : []
        );
        if (!empty($conflicts)) {
            return back()
                ->withInput()
                ->withErrors([
                    'team_2_members' => 'Nama yang sama tidak boleh dipilih di Tim 1 dan Tim 2: ' . implode(', ', $conflicts),
                ]);
        }

        $process = KernelProsses::create([
            'user_id' => auth()->id(),
            'office' => $office,
            'process_date' => $validated['process_date'],
            'input_team' => $inputTeam,
            'team_1_start_time' => $isTeamOneInput ? ($validated['team_1_start_time'] ?? '00:00') : '00:00',
            'team_1_end_time' => $isTeamOneInput ? ($validated['team_1_end_time'] ?? '00:00') : '00:00',
            'team_1_start_downtime' => $isTeamOneInput ? ($validated['team_1_start_downtime'] ?? null) : null,
            'team_1_end_downtime' => $isTeamOneInput ? ($validated['team_1_end_downtime'] ?? null) : null,
            'team_1_downtime' => null,
            'team_1_members' => $isTeamOneInput ? ($validated['team_1_members'] ?? []) : [],
            'team_1_other_conditions' => [],
            'team_2_start_time' => $isTeamOneInput ? '00:00' : ($validated['team_2_start_time'] ?? '00:00'),
            'team_2_end_time' => $isTeamOneInput ? '00:00' : ($validated['team_2_end_time'] ?? '00:00'),
            'team_2_start_downtime' => $isTeamOneInput ? null : ($validated['team_2_start_downtime'] ?? null),
            'team_2_end_downtime' => $isTeamOneInput ? null : ($validated['team_2_end_downtime'] ?? null),
            'team_2_downtime' => null,
            'team_2_members' => $isTeamOneInput ? [] : ($validated['team_2_members'] ?? []),
            'team_2_other_conditions' => [],
        ]);

        if ($roleFlags['can_manage_machine_data']) {
            $machinePayload = $this->extractMachinePayload($request, $inputTeam, $office);
            if (!empty($machinePayload)) {
                $process->mesin()->createMany($machinePayload);
            }

            $otherConditionsByTeam = $this->extractOtherConditionsPayload($request, $inputTeam);
            if ($inputTeam === 'Tim 1') {
                $process->update([
                    'team_1_other_conditions' => $otherConditionsByTeam['Tim 1'] ?? [],
                ]);
            } else {
                $process->update([
                    'team_2_other_conditions' => $otherConditionsByTeam['Tim 2'] ?? [],
                ]);
            }
        }

        return redirect()
            ->route('process.index')
            ->with('success', 'Informasi proses ' . $inputTeam . ' berhasil disimpan.');
    }

    public function show(KernelProsses $kernelProsses)
    {
        $team1 = $this->buildTeamRow($kernelProsses, 'Tim 1');
        $team2 = $this->buildTeamRow($kernelProsses, 'Tim 2');
        $visibleTeam = in_array((string) $kernelProsses->input_team, self::TEAM_OPTIONS, true)
            ? (string) $kernelProsses->input_team
            : null;

        return view('process.show', [
            'record' => $kernelProsses,
            'team1' => $team1,
            'team2' => $team2,
            'visibleTeam' => $visibleTeam,
        ]);
    }

    public function edit(KernelProsses $kernelProsses)
    {
        if (!$this->resolveProcessRoleFlags()['can_manage_team_meta']) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah checklist tim dan jam shift.');
        }

        $team1 = $this->buildTeamRow($kernelProsses, 'Tim 1');
        $team2 = $this->buildTeamRow($kernelProsses, 'Tim 2');
        $visibleTeam = in_array((string) $kernelProsses->input_team, self::TEAM_OPTIONS, true)
            ? (string) $kernelProsses->input_team
            : null;
        $teamMembers = $this->getTeamMembersByOffice();

        return view('process.edit', [
            'record' => $kernelProsses,
            'team1' => $team1,
            'team2' => $team2,
            'teamMembers' => $teamMembers,
            'visibleTeam' => $visibleTeam,
        ]);
    }

    public function performanceSampelBoy(Request $request)
    {
        $dateStart = (string) $request->input('date_start', now()->format('Y-m-d'));
        $dateEnd = (string) $request->input('date_end', now()->format('Y-m-d'));
        $officeOptions = $this->getAvailableOfficesForPerformance();
        $defaultOffice = (string) (auth()->user()->office ?? '');
        $selectedOffice = (string) $request->input('office', $defaultOffice !== '' ? $defaultOffice : 'all');

        if ($selectedOffice !== 'all' && !in_array($selectedOffice, $officeOptions, true)) {
            $selectedOffice = $defaultOffice !== '' ? $defaultOffice : 'all';
        }

        // Ensure dates are valid and start <= end
        try {
            $startDate = Carbon::parse($dateStart)->startOfDay();
            $endDate = Carbon::parse($dateEnd)->endOfDay();
            if ($startDate > $endDate) {
                $startDate = $endDate->copy()->startOfDay();
                $endDate = $startDate->copy()->endOfDay();
            }
        } catch (\Throwable) {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
        }

        $records = KernelProsses::query()
            ->with('mesin')
            ->whereBetween('process_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->when($selectedOffice !== 'all', fn($query) => $query->where('office', $selectedOffice))
            ->orderBy('process_date')
            ->orderBy('id')
            ->get();

        $rows = [];
        $perfTeam1 = []; // Track Tim 1 performance
        $perfTeam2 = []; // Track Tim 2 performance

        foreach ($records as $record) {
            /** @var KernelProsses $record */
            $team1Machines = $record->mesin->where('team_name', 'Tim 1')->values();
            $team2Machines = $record->mesin->where('team_name', 'Tim 2')->values();

            $inputTeam = in_array((string) $record->input_team, self::TEAM_OPTIONS, true)
                ? (string) $record->input_team
                : null;

            if ($inputTeam === 'Tim 1') {
                $row = $this->buildPerformanceRow(
                    $record,
                    'Tim 1',
                    (array) ($record->team_1_members ?? []),
                    $team1Machines,
                    $selectedOffice
                );
                $rows[] = $row;
                if (!empty($row['perf_total']) && $row['perf_total'] !== '-') {
                    $perfTeam1[] = (float) str_replace('%', '', $row['perf_total']);
                }
                continue;
            }

            if ($inputTeam === 'Tim 2') {
                $row = $this->buildPerformanceRow(
                    $record,
                    'Tim 2',
                    (array) ($record->team_2_members ?? []),
                    $team2Machines,
                    $selectedOffice
                );
                $rows[] = $row;
                if (!empty($row['perf_total']) && $row['perf_total'] !== '-') {
                    $perfTeam2[] = (float) str_replace('%', '', $row['perf_total']);
                }
                continue;
            }

            $row1 = $this->buildPerformanceRow(
                $record,
                'Tim 1',
                (array) ($record->team_1_members ?? []),
                $team1Machines,
                $selectedOffice
            );
            $rows[] = $row1;
            if (!empty($row1['perf_total']) && $row1['perf_total'] !== '-') {
                $perfTeam1[] = (float) str_replace('%', '', $row1['perf_total']);
            }

            $row2 = $this->buildPerformanceRow(
                $record,
                'Tim 2',
                (array) ($record->team_2_members ?? []),
                $team2Machines,
                $selectedOffice
            );
            $rows[] = $row2;
            if (!empty($row2['perf_total']) && $row2['perf_total'] !== '-') {
                $perfTeam2[] = (float) str_replace('%', '', $row2['perf_total']);
            }
        }

        // Calculate average performance per team
        $avgTeam1 = !empty($perfTeam1)
            ? number_format(array_sum($perfTeam1) / count($perfTeam1), 2) . '%'
            : '-';
        
        $avgTeam2 = !empty($perfTeam2)
            ? number_format(array_sum($perfTeam2) / count($perfTeam2), 2) . '%'
            : '-';

        return view('process.performance-sampel-boy', [
            'dateStart' => $startDate->format('Y-m-d'),
            'dateEnd' => $endDate->format('Y-m-d'),
            'selectedOffice' => $selectedOffice,
            'officeOptions' => $officeOptions,
            'rows' => $rows,
            'avgTeam1' => $avgTeam1,
            'avgTeam2' => $avgTeam2,
        ]);
    }

    public function exportPerformanceSampelBoy(Request $request)
    {
        $dateStart = (string) $request->input('date_start', now()->format('Y-m-d'));
        $dateEnd = (string) $request->input('date_end', now()->format('Y-m-d'));
        $officeOptions = $this->getAvailableOfficesForPerformance();
        $defaultOffice = (string) (auth()->user()->office ?? '');
        $selectedOffice = (string) $request->input('office', $defaultOffice !== '' ? $defaultOffice : 'all');

        if ($selectedOffice !== 'all' && !in_array($selectedOffice, $officeOptions, true)) {
            $selectedOffice = $defaultOffice !== '' ? $defaultOffice : 'all';
        }

        // Ensure dates are valid and start <= end
        try {
            $startDate = Carbon::parse($dateStart)->startOfDay();
            $endDate = Carbon::parse($dateEnd)->endOfDay();
            if ($startDate > $endDate) {
                $startDate = $endDate->copy()->startOfDay();
                $endDate = $startDate->copy()->endOfDay();
            }
        } catch (\Throwable) {
            $startDate = now()->startOfDay();
            $endDate = now()->endOfDay();
        }

        $records = KernelProsses::query()
            ->with('mesin')
            ->whereBetween('process_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->when($selectedOffice !== 'all', fn($query) => $query->where('office', $selectedOffice))
            ->orderBy('process_date')
            ->orderBy('id')
            ->get();

        $rows = [];
        foreach ($records as $record) {
            /** @var KernelProsses $record */
            $team1Machines = $record->mesin->where('team_name', 'Tim 1')->values();
            $team2Machines = $record->mesin->where('team_name', 'Tim 2')->values();

            $inputTeam = in_array((string) $record->input_team, self::TEAM_OPTIONS, true)
                ? (string) $record->input_team
                : null;

            if ($inputTeam === 'Tim 1') {
                $rows[] = $this->buildPerformanceRow(
                    $record,
                    'Tim 1',
                    (array) ($record->team_1_members ?? []),
                    $team1Machines,
                    $selectedOffice
                );
                continue;
            }

            if ($inputTeam === 'Tim 2') {
                $rows[] = $this->buildPerformanceRow(
                    $record,
                    'Tim 2',
                    (array) ($record->team_2_members ?? []),
                    $team2Machines,
                    $selectedOffice
                );
                continue;
            }

            $rows[] = $this->buildPerformanceRow(
                $record,
                'Tim 1',
                (array) ($record->team_1_members ?? []),
                $team1Machines,
                $selectedOffice
            );

            $rows[] = $this->buildPerformanceRow(
                $record,
                'Tim 2',
                (array) ($record->team_2_members ?? []),
                $team2Machines,
                $selectedOffice
            );
        }

        $dateStartFormatted = Carbon::parse($startDate)->format('Ymd');
        $dateEndFormatted = Carbon::parse($endDate)->format('Ymd');
        $officeLabel = $selectedOffice === 'all' ? 'all-office' : strtolower($selectedOffice);

        $filename = 'Performance-Summary-' . $dateStartFormatted . '-' . $dateEndFormatted . '-' . $officeLabel . '.xlsx';

        return Excel::download(
            new PerformanceSampelBoyExport($rows, $startDate->format('Y-m-d'), $selectedOffice),
            $filename
        );
    }

    public function machineDetail(KernelProsses $kernelProsses)
    {
        $allMachines = $kernelProsses->mesin()
            ->orderBy('team_name')
            ->orderBy('machine_group')
            ->orderBy('machine_name')
            ->get();

        $teamsToShow = in_array((string) $kernelProsses->input_team, self::TEAM_OPTIONS, true)
            ? [(string) $kernelProsses->input_team]
            : self::TEAM_OPTIONS;

        $office = !empty($kernelProsses->office) ? (string) $kernelProsses->office : 'YBS';

        $groupedMachinesByTeam = collect($teamsToShow)
            ->mapWithKeys(function (string $teamName) use ($allMachines, $office, $kernelProsses): array {
                $teamMachines = $allMachines
                    ->where('team_name', $teamName)
                    ->values();

                $teamMainMachines = $teamMachines
                    ->where('is_spare_input', false)
                    ->values();

                $teamSparesByMachineName = $teamMachines
                    ->where('is_spare_input', true)
                    ->groupBy(fn(KernelMesin $machine): string => (string) $machine->machine_name);

                $teamSpareRows = $teamMachines
                    ->where('is_spare_input', true)
                    ->map(function (KernelMesin $spare): array {
                        return [
                            'machine' => $spare,
                            'total_minutes' => $this->resolveMachineTotalMinutes($spare),
                        ];
                    })
                    ->values();

                $groupedMachines = $teamMainMachines
                    ->groupBy(fn(KernelMesin $machine): string => (string) $machine->machine_group)
                    ->map(function ($machines) use ($teamSparesByMachineName, $office, $teamMachines, $kernelProsses, $teamName) {
                        return $machines->map(function (KernelMesin $machine) use ($teamSparesByMachineName, $office, $teamMachines, $kernelProsses, $teamName): array {
                            $totalMinutes = $this->resolveMachineTotalMinutes($machine);
                            $intervalMinutes = $this->resolveMachineIntervalMinutes((string) $machine->machine_name, $office);
                            $machineRows = $teamMachines
                                ->where('machine_name', $machine->machine_name)
                                ->values();
                            $expectedSamples = $this->calculateExpectedSamplesFromRowsWithConditions(
                                $kernelProsses,
                                $teamName,
                                $machineRows,
                                $intervalMinutes
                            );

                            return [
                                'main' => $machine,
                                'spares' => $teamSparesByMachineName->get($machine->machine_name, collect()),
                                'total_minutes' => $totalMinutes,
                                'interval_minutes' => $intervalMinutes,
                                'expected_samples' => $expectedSamples,
                            ];
                        });
                    });

                $orphanSpares = $teamMachines
                    ->where('is_spare_input', true)
                    ->filter(function (KernelMesin $spare) use ($teamMainMachines): bool {
                        return !$teamMainMachines->contains(fn(KernelMesin $machine): bool => $machine->machine_name === $spare->machine_name);
                    })
                    ->map(function (KernelMesin $spare): array {
                        return [
                            'machine' => $spare,
                            'total_minutes' => $this->resolveMachineTotalMinutes($spare),
                        ];
                    })
                    ->values();

                $expectedSamplesTotal = (int) $groupedMachines
                    ->flatten(1)
                    ->sum(fn(array $item): int => (int) ($item['expected_samples'] ?? 0));

                return [
                    $teamName => [
                        'groups' => $groupedMachines,
                        'spare_rows' => $teamSpareRows,
                        'orphans' => $orphanSpares,
                        'expected_samples_total' => $expectedSamplesTotal,
                        'other_conditions' => $this->getTeamOtherConditions($kernelProsses, $teamName),
                    ],
                ];
            });

        return view('process.machine-detail', [
            'record' => $kernelProsses,
            'groupedMachinesByTeam' => $groupedMachinesByTeam,
        ]);
    }

    public function editMachines(KernelProsses $kernelProsses)
    {
        if (!$this->resolveProcessRoleFlags()['can_manage_machine_data']) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah jam proses mesin.');
        }

        $kernelProsses->load('mesin');

        $visibleTeam = in_array((string) $kernelProsses->input_team, self::TEAM_OPTIONS, true)
            ? (string) $kernelProsses->input_team
            : null;

        $selectedMainMachines = [];
        $spareRowsByTeam = [
            'Tim 1' => [],
            'Tim 2' => [],
        ];
        $otherConditionsByTeam = [
            'Tim 1' => collect((array) ($kernelProsses->team_1_other_conditions ?? []))
                ->filter(fn($row): bool => is_array($row))
                ->map(fn($row): array => [
                    'team_name' => 'Tim 1',
                    'reason' => trim((string) ($row['reason'] ?? '')),
                    'start_time' => trim((string) ($row['start_time'] ?? '')),
                    'end_time' => trim((string) ($row['end_time'] ?? '')),
                ])
                ->values()
                ->all(),
            'Tim 2' => collect((array) ($kernelProsses->team_2_other_conditions ?? []))
                ->filter(fn($row): bool => is_array($row))
                ->map(fn($row): array => [
                    'team_name' => 'Tim 2',
                    'reason' => trim((string) ($row['reason'] ?? '')),
                    'start_time' => trim((string) ($row['start_time'] ?? '')),
                    'end_time' => trim((string) ($row['end_time'] ?? '')),
                ])
                ->values()
                ->all(),
        ];

        foreach ($kernelProsses->mesin as $machine) {
            $teamName = (string) $machine->team_name;
            if (!in_array($teamName, self::TEAM_OPTIONS, true)) {
                continue;
            }

            if ($machine->is_spare_input) {
                $spareRowsByTeam[$teamName][] = [
                    'team_name' => $teamName,
                    'machine_name' => (string) $machine->machine_name,
                    'start_time' => substr((string) $machine->production_start_time, 0, 5),
                    'end_time' => substr((string) $machine->production_end_time, 0, 5),
                ];
                continue;
            }

            $key = $teamName . '|' . strtoupper(trim((string) $machine->machine_name));
            if (!isset($selectedMainMachines[$key])) {
                $selectedMainMachines[$key] = [
                    'start_time' => substr((string) $machine->production_start_time, 0, 5),
                    'end_time' => substr((string) $machine->production_end_time, 0, 5),
                ];
            }
        }

        foreach (self::TEAM_OPTIONS as $teamName) {
            if (empty($spareRowsByTeam[$teamName])) {
                $spareRowsByTeam[$teamName] = [
                    [
                        'team_name' => $teamName,
                        'machine_name' => '',
                        'start_time' => '',
                        'end_time' => '',
                    ]
                ];
            }

            if (empty($otherConditionsByTeam[$teamName])) {
                $otherConditionsByTeam[$teamName] = [
                    [
                        'team_name' => $teamName,
                        'reason' => '',
                        'start_time' => '',
                        'end_time' => '',
                    ]
                ];
            }
        }

        $machineGroups = $this->getMachineGroupsForOffice($kernelProsses->office ?: null);
        $machineOptions = collect($machineGroups)->flatten()->values()->all();

        return view('process.edit-machines', [
            'record' => $kernelProsses,
            'machineGroups' => $machineGroups,
            'selectedMainMachines' => $selectedMainMachines,
            'spareRowsByTeam' => $spareRowsByTeam,
            'otherConditionsByTeam' => $otherConditionsByTeam,
            'machineOptions' => $machineOptions,
            'visibleTeam' => $visibleTeam,
        ]);
    }

    public function updateMachines(Request $request, KernelProsses $kernelProsses)
    {
        if (!$this->resolveProcessRoleFlags()['can_manage_machine_data']) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah jam proses mesin.');
        }

        $validated = $request->validate([
            'machines' => ['nullable', 'array'],
            'machines.*.selected' => ['nullable'],
            'machines.*.machine_name' => ['nullable', 'string', 'max:150'],
            'machines.*.machine_group' => ['nullable', 'string', 'max:120'],
            'machines.*.team_name' => ['nullable', 'string', 'in:Tim 1,Tim 2'],
            'machines.*.start_time' => ['nullable', 'date_format:H:i'],
            'machines.*.end_time' => ['nullable', 'date_format:H:i'],
            'spare_machines' => ['nullable', 'array'],
            'spare_machines.*.team_name' => ['nullable', 'string', 'in:Tim 1,Tim 2'],
            'spare_machines.*.machine_name' => ['nullable', 'string', 'max:150'],
            'spare_machines.*.start_time' => ['nullable', 'date_format:H:i'],
            'spare_machines.*.end_time' => ['nullable', 'date_format:H:i'],

            'other_conditions' => ['nullable', 'array'],
            'other_conditions.*.team_name' => ['nullable', 'string', 'in:Tim 1,Tim 2'],
            'other_conditions.*.reason' => ['nullable', 'string', 'max:255', 'required_with:other_conditions.*.start_time,other_conditions.*.end_time'],
            'other_conditions.*.start_time' => ['nullable', 'date_format:H:i', 'required_with:other_conditions.*.reason,other_conditions.*.end_time'],
            'other_conditions.*.end_time' => ['nullable', 'date_format:H:i', 'required_with:other_conditions.*.reason,other_conditions.*.start_time'],
        ]);

        $otherConditionsByTeam = $this->extractOtherConditionsPayload($request);

        $machinePayload = $this->extractMachinePayload($request, null, $kernelProsses->office ?: null);

        $kernelProsses->mesin()->delete();
        if (!empty($machinePayload)) {
            $kernelProsses->mesin()->createMany($machinePayload);
        }

        $team1OtherConditions = $this->requestContainsTeamConditions($request, 'Tim 1')
            ? ($otherConditionsByTeam['Tim 1'] ?? [])
            : (array) ($kernelProsses->team_1_other_conditions ?? []);

        $team2OtherConditions = $this->requestContainsTeamConditions($request, 'Tim 2')
            ? ($otherConditionsByTeam['Tim 2'] ?? [])
            : (array) ($kernelProsses->team_2_other_conditions ?? []);

        $kernelProsses->update([
            'team_1_other_conditions' => $team1OtherConditions,
            'team_2_other_conditions' => $team2OtherConditions,
        ]);

        return redirect()
            ->route('process.index')
            ->with('success', 'Data mesin berhasil diperbarui.');
    }

    public function destroyMachines(KernelProsses $kernelProsses)
    {
        if (!$this->resolveProcessRoleFlags()['can_manage_machine_data']) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus jam proses mesin.');
        }

        $deletedCount = $kernelProsses->mesin()->count();
        $kernelProsses->mesin()->delete();

        return redirect()
            ->route('process.index')
            ->with('success', $deletedCount > 0
                ? "Data mesin berhasil dihapus ({$deletedCount} item)."
                : 'Tidak ada data mesin untuk dihapus.');
    }

    public function updateBoth(Request $request, KernelProsses $kernelProsses)
    {
        if (!$this->resolveProcessRoleFlags()['can_manage_team_meta']) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah checklist tim dan jam shift.');
        }

        $teamMemberRules = $this->buildTeamMemberValidationRules();
        $validated = $request->validate([
            'process_date' => ['required', 'date'],

            'team_1_start_time' => ['required', 'date_format:H:i'],
            'team_1_end_time' => ['required', 'date_format:H:i'],
            'team_1_start_downtime' => ['nullable', 'date_format:H:i', 'required_with:team_1_end_downtime'],
            'team_1_end_downtime' => ['nullable', 'date_format:H:i', 'required_with:team_1_start_downtime'],
            'team_1_members' => ['nullable', 'array'],
            'team_1_members.*' => $teamMemberRules,

            'team_2_start_time' => ['required', 'date_format:H:i'],
            'team_2_end_time' => ['required', 'date_format:H:i'],
            'team_2_start_downtime' => ['nullable', 'date_format:H:i', 'required_with:team_2_end_downtime'],
            'team_2_end_downtime' => ['nullable', 'date_format:H:i', 'required_with:team_2_start_downtime'],
            'team_2_members' => ['nullable', 'array'],
            'team_2_members.*' => $teamMemberRules,
        ]);

        $conflicts = array_intersect($validated['team_1_members'] ?? [], $validated['team_2_members'] ?? []);
        if (!empty($conflicts)) {
            return back()
                ->withInput()
                ->withErrors([
                    'team_2_members' => 'Nama yang sama tidak boleh dipilih di Tim 1 dan Tim 2: ' . implode(', ', $conflicts),
                ]);
        }

        $kernelProsses->update([
            'process_date' => $validated['process_date'],
            'team_1_start_time' => $validated['team_1_start_time'],
            'team_1_end_time' => $validated['team_1_end_time'],
            'team_1_start_downtime' => $validated['team_1_start_downtime'] ?? null,
            'team_1_end_downtime' => $validated['team_1_end_downtime'] ?? null,
            'team_1_members' => $validated['team_1_members'] ?? [],
            'team_2_start_time' => $validated['team_2_start_time'],
            'team_2_end_time' => $validated['team_2_end_time'],
            'team_2_start_downtime' => $validated['team_2_start_downtime'] ?? null,
            'team_2_end_downtime' => $validated['team_2_end_downtime'] ?? null,
            'team_2_members' => $validated['team_2_members'] ?? [],
        ]);

        return redirect()
            ->route('process.index')
            ->with('success', 'Informasi proses berhasil diperbarui.');
    }

    public function editOld(KernelProsses $kernelProsses, int $team)
    {
        $this->ensureValidTeam($team);
        $teamMembers = $this->getTeamMembersByOffice();

        $otherMembers = $team === 1
            ? ($kernelProsses->team_2_members ?? [])
            : ($kernelProsses->team_1_members ?? []);

        $teamData = $team === 1
            ? [
                'start_time' => substr((string) $kernelProsses->team_1_start_time, 0, 5),
                'end_time' => substr((string) $kernelProsses->team_1_end_time, 0, 5),
                'start_downtime' => substr((string) $kernelProsses->team_1_start_downtime, 0, 5),
                'end_downtime' => substr((string) $kernelProsses->team_1_end_downtime, 0, 5),
                'members' => $kernelProsses->team_1_members ?? [],
            ]
            : [
                'start_time' => substr((string) $kernelProsses->team_2_start_time, 0, 5),
                'end_time' => substr((string) $kernelProsses->team_2_end_time, 0, 5),
                'start_downtime' => substr((string) $kernelProsses->team_2_start_downtime, 0, 5),
                'end_downtime' => substr((string) $kernelProsses->team_2_end_downtime, 0, 5),
                'members' => $kernelProsses->team_2_members ?? [],
            ];

        return view('process.edit', [
            'record' => $kernelProsses,
            'team' => $team,
            'teamLabel' => $team === 1 ? 'Tim 1' : 'Tim 2',
            'teamMembers' => $teamMembers,
            'teamData' => $teamData,
            'otherMembers' => $otherMembers,
        ]);
    }

    public function update(Request $request, KernelProsses $kernelProsses, int $team)
    {
        if (!$this->resolveProcessRoleFlags()['can_manage_team_meta']) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah checklist tim dan jam shift.');
        }

        $this->ensureValidTeam($team);
        $teamMemberRules = $this->buildTeamMemberValidationRules();

        $validated = $request->validate([
            'process_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'start_downtime' => ['nullable', 'date_format:H:i', 'required_with:end_downtime'],
            'end_downtime' => ['nullable', 'date_format:H:i', 'required_with:start_downtime'],
            'members' => ['nullable', 'array'],
            'members.*' => $teamMemberRules,
        ]);

        $selectedMembers = $validated['members'] ?? [];
        $otherMembers = $team === 1
            ? ($kernelProsses->team_2_members ?? [])
            : ($kernelProsses->team_1_members ?? []);

        $conflicts = array_intersect($selectedMembers, $otherMembers);
        if (!empty($conflicts)) {
            return back()
                ->withInput()
                ->withErrors([
                    'members' => 'Nama yang sudah dipakai tim lain tidak bisa dipilih: ' . implode(', ', $conflicts),
                ]);
        }

        $payload = ['process_date' => $validated['process_date']];

        if ($team === 1) {
            $payload = array_merge($payload, [
                'team_1_start_time' => $validated['start_time'],
                'team_1_end_time' => $validated['end_time'],
                'team_1_start_downtime' => $validated['start_downtime'] ?? null,
                'team_1_end_downtime' => $validated['end_downtime'] ?? null,
                'team_1_members' => $selectedMembers,
            ]);
        } else {
            $payload = array_merge($payload, [
                'team_2_start_time' => $validated['start_time'],
                'team_2_end_time' => $validated['end_time'],
                'team_2_start_downtime' => $validated['start_downtime'] ?? null,
                'team_2_end_downtime' => $validated['end_downtime'] ?? null,
                'team_2_members' => $selectedMembers,
            ]);
        }

        $kernelProsses->update($payload);

        return redirect()
            ->route('process.index')
            ->with('success', 'Data proses berhasil diperbarui.');
    }

    private function buildTeamRow(KernelProsses $record, string $teamName): array
    {
        $isTeamOne = $teamName === 'Tim 1';

        $processStart = (string) ($isTeamOne ? $record->team_1_start_time : $record->team_2_start_time);
        $processEnd = (string) ($isTeamOne ? $record->team_1_end_time : $record->team_2_end_time);
        $downtimeStart = (string) ($isTeamOne ? $record->team_1_start_downtime : $record->team_2_start_downtime);
        $downtimeEnd = (string) ($isTeamOne ? $record->team_1_end_downtime : $record->team_2_end_downtime);

        $totalProductionHours = $this->calculateTeamProductionHours(
            $processStart,
            $processEnd,
            $downtimeStart,
            $downtimeEnd
        );

        return [
            'process_date' => optional($record->process_date)->format('d/m/Y'),
            'process_start' => substr($processStart, 0, 5),
            'process_end' => substr($processEnd, 0, 5),
            'downtime_start' => substr($downtimeStart, 0, 5),
            'downtime_end' => substr($downtimeEnd, 0, 5),
            'production_hours' => $totalProductionHours . ' jam',
            'team_name' => $teamName,
            'members' => $isTeamOne ? ($record->team_1_members ?? []) : ($record->team_2_members ?? []),
        ];
    }

    private function buildPerformanceRow(KernelProsses $record, string $teamName, array $members, Collection $teamMachines, string $selectedOffice): array
    {
        $isTeamOne = $teamName === 'Tim 1';

        $processStart = substr((string) ($isTeamOne ? $record->team_1_start_time : $record->team_2_start_time), 0, 5);
        $processEnd = substr((string) ($isTeamOne ? $record->team_1_end_time : $record->team_2_end_time), 0, 5);
        $downtimeStart = substr((string) ($isTeamOne ? $record->team_1_start_downtime : $record->team_2_start_downtime), 0, 5);
        $downtimeEnd = substr((string) ($isTeamOne ? $record->team_1_end_downtime : $record->team_2_end_downtime), 0, 5);

        $effectiveHours = $this->calculateTeamProductionHours(
            $processStart,
            $processEnd,
            $downtimeStart,
            $downtimeEnd
        );

        // Use actual office from record if available, fallback to selectedOffice filter
        $office = $selectedOffice === 'all' ? 'YBS' : $selectedOffice;
        if (!empty($record->office)) {
            $office = (string) $record->office;
        }

        $expectedByGroup = $this->buildExpectedSamplesByGroup($record, $teamName, $teamMachines, $office);
        $machineWindowsByCode = $this->buildMachineWindowsByCode($teamMachines);

        $actualBundle = $this->buildActualSamplesBundle(
            (string) optional($record->process_date)->format('Y-m-d'),
            $office === 'all' ? '' : $office,
            $machineWindowsByCode,
            $members
        );
        $actualByGroup = $actualBundle['group'];

        $perfActualTotal = 0;
        $perfExpectedTotal = 0;
        $intervalKeys = $this->getIntervalMinutesForOffice($office);
        foreach (array_keys($intervalKeys) as $groupKey) {
            $perfActualTotal += (int) ($actualByGroup[$groupKey] ?? 0);
            $perfExpectedTotal += (int) ($expectedByGroup[$groupKey] ?? 0);
        }

        $perfTotal = $perfExpectedTotal > 0
            ? number_format(($perfActualTotal / $perfExpectedTotal) * 100, 2) . '%'
            : '-';

        return [
            'tanggal' => optional($record->process_date)->format('d/m/Y'),
            'team_name' => $teamName,
            'jam_mulai' => $processStart,
            'jam_akhir' => $processEnd,
            'downtime_awal' => $downtimeStart !== '' ? $downtimeStart : '-',
            'downtime_akhir' => $downtimeEnd !== '' ? $downtimeEnd : '-',
            'total_hours' => $effectiveHours . ' jam',
            'nama_sampel_boy' => !empty($members) ? implode(', ', $members) : '-',
            'fibre_cyclone' => $this->formatActualExpected($actualByGroup, $expectedByGroup, 'fibre_cyclone'),
            'ltds' => $this->formatActualExpected($actualByGroup, $expectedByGroup, 'ltds'),
            'claybath_wet_shell' => $this->formatActualExpected($actualByGroup, $expectedByGroup, 'claybath_wet_shell'),
            'inlet_kernel_silo' => $this->formatActualExpected($actualByGroup, $expectedByGroup, 'inlet_kernel_silo'),
            'outlet_kernel_silo' => $this->formatActualExpected($actualByGroup, $expectedByGroup, 'outlet_kernel_silo'),
            'press' => $this->formatActualExpected($actualByGroup, $expectedByGroup, 'press'),
            'eficiency' => $this->formatActualExpected($actualByGroup, $expectedByGroup, 'eficiency'),
            'destoner' => $this->formatActualExpected($actualByGroup, $expectedByGroup, 'destoner'),
            'perf_total' => $perfTotal,
        ];
    }

    private function formatActualExpected(array $actualByGroup, array $expectedByGroup, string $groupKey): string
    {
        $actual = (int) ($actualByGroup[$groupKey] ?? 0);
        $expected = (int) ($expectedByGroup[$groupKey] ?? 0);

        return $actual . '/' . $expected;
    }

    private function buildExpectedSamplesByGroup(KernelProsses $record, string $teamName, Collection $machines, string $office = 'YBS'): array
    {
        $expected = [];
        $intervals = $this->getIntervalMinutesForOffice($office);
        foreach (array_keys($intervals) as $groupKey) {
            $expected[$groupKey] = 0;
        }

        foreach ($intervals as $groupKey => $intervalMinutes) {
            if ((int) $intervalMinutes <= 0) {
                continue;
            }

            $rowsForGroup = $machines
                ->filter(function ($machine) use ($groupKey): bool {
                    $machineName = strtoupper(trim((string) $machine->machine_name));
                    return $this->resolvePerformanceGroupByMachineName($machineName) === $groupKey;
                })
                ->values();

            if ($rowsForGroup->isEmpty()) {
                continue;
            }

            $expected[$groupKey] = $this->calculateExpectedSamplesFromRowsWithConditions(
                $record,
                $teamName,
                $rowsForGroup,
                (int) $intervalMinutes
            );
        }

        return $expected;
    }

    private function calculateTeamProductionHours(string $processStart, string $processEnd, string $downtimeStart, string $downtimeEnd): int
    {
        $processMinutes = $this->minutesBetween($processStart, $processEnd);
        $downtimeMinutes = $this->minutesBetween($downtimeStart, $downtimeEnd);

        return max(intdiv($processMinutes - $downtimeMinutes, 60), 0);
    }

    private function buildMachineWindowsByCode(Collection $machines): array
    {
        $windowsByCode = [];

        foreach ($machines as $machine) {
            $machineName = strtoupper(trim((string) $machine->machine_name));
            $codes = $this->resolveCodesByMachineName($machineName);

            if (empty($codes)) {
                continue;
            }

            $start = $this->timeToMinutes(substr((string) $machine->production_start_time, 0, 5));
            $end = $this->timeToMinutes(substr((string) $machine->production_end_time, 0, 5));

            if ($start === null || $end === null) {
                continue;
            }

            foreach ($codes as $code) {
                $normalizedCode = $this->normalizeCode($code);
                if (!isset($windowsByCode[$normalizedCode])) {
                    $windowsByCode[$normalizedCode] = [];
                }

                $windowsByCode[$normalizedCode][] = [
                    'start' => $start,
                    'end' => $end,
                ];
            }
        }

        return $windowsByCode;
    }

    private function resolvePerformanceGroupByMachineName(string $machineName): ?string
    {
        if (str_starts_with($machineName, 'FIBRE CYCLONE')) {
            return 'fibre_cyclone';
        }

        if (str_starts_with($machineName, 'LTDS')) {
            return 'ltds';
        }

        if (str_starts_with($machineName, 'CLAYBATH WET SHELL')) {
            return 'claybath_wet_shell';
        }

        if (str_starts_with($machineName, 'INLET KERNEL SILO')) {
            return 'inlet_kernel_silo';
        }

        if (str_starts_with($machineName, 'OUTLET KERNEL SILO')) {
            return 'outlet_kernel_silo';
        }

        if (str_starts_with($machineName, 'PRESS')) {
            return 'press';
        }

        if (str_starts_with($machineName, 'RIPPLE MILL')) {
            return 'eficiency';
        }

        if (str_starts_with($machineName, 'DESTONER')) {
            return 'destoner';
        }

        return null;
    }

    private function buildActualSamplesByGroup(string $date, string $office, array $machineWindowsByCode, array $teamMembers = []): array
    {
        return $this->buildActualSamplesBundle($date, $office, $machineWindowsByCode, $teamMembers)['group'];
    }

    private function buildExpectedSamplesByCode(KernelProsses $record, string $teamName, Collection $machines, string $office = 'YBS'): array
    {
        $expected = [];
        foreach (self::PERFORMANCE_DETAIL_CODES as $detailCode) {
            $expected[$detailCode['code']] = 0;
        }

        $machinesByName = $machines
            ->groupBy(fn($machine) => strtoupper(trim((string) $machine->machine_name)));

        foreach ($machinesByName as $machineName => $rows) {
            $intervalMinutes = $this->resolveMachineIntervalMinutes((string) $machineName, $office);
            if ($intervalMinutes <= 0) {
                continue;
            }

            $expectedSamples = $this->calculateExpectedSamplesFromRowsWithConditions(
                $record,
                $teamName,
                $rows->values(),
                $intervalMinutes
            );

            if ($expectedSamples <= 0) {
                continue;
            }

            $codes = $this->resolveCodesByMachineName((string) $machineName);
            if (empty($codes)) {
                continue;
            }

            $addedCodes = [];
            foreach ($codes as $rawCode) {
                $detailCode = $this->normalizeDetailCode((string) $rawCode);
                if ($detailCode === null || isset($addedCodes[$detailCode])) {
                    continue;
                }

                $addedCodes[$detailCode] = true;
                $expected[$detailCode] = (int) (($expected[$detailCode] ?? 0) + $expectedSamples);
            }
        }

        return $expected;
    }

    private function buildActualSamplesByCode(string $date, string $office, array $machineWindowsByCode, array $teamMembers = []): array
    {
        return $this->buildActualSamplesBundle($date, $office, $machineWindowsByCode, $teamMembers)['code'];
    }

    private function buildActualSamplesBundle(string $date, string $office, array $machineWindowsByCode, array $teamMembers = []): array
    {
        $groupActual = [];
        foreach (array_keys(self::PERFORMANCE_GROUP_INTERVAL_MINUTES) as $groupKey) {
            $groupActual[$groupKey] = 0;
        }

        $codeActual = [];
        foreach (self::PERFORMANCE_DETAIL_CODES as $detailCode) {
            $codeActual[$detailCode['code']] = 0;
        }

        $rows = $this->getTimedCodeRowsCached($date, $office);

        // Normalize team members to uppercase for comparison
        $normalizedTeamMembers = array_map(fn($member) => strtoupper(trim((string) $member)), $teamMembers);
        $groupSlots = [];

        foreach ($rows as $row) {
            // Filter by team member if team_members is provided
            if (!empty($normalizedTeamMembers)) {
                $sampelBoy = strtoupper(trim((string) ($row['sampel_boy'] ?? '')));
                if (!in_array($sampelBoy, $normalizedTeamMembers, true)) {
                    continue;
                }
            }

            $isPengulangan = (bool) ($row['pengulangan'] ?? false);
            if ($isPengulangan) {
                continue;
            }

            $rawCode = $this->normalizeCode((string) ($row['code'] ?? ''));
            $minutes = $this->timeToMinutes((string) ($row['time'] ?? ''));

            if ($rawCode === '' || $minutes === null) {
                continue;
            }

            $windows = $machineWindowsByCode[$rawCode] ?? [];
            if (empty($windows)) {
                continue;
            }

            $matched = false;
            foreach ($windows as $window) {
                if ($this->isMinutesWithinRange($minutes, (int) $window['start'], (int) $window['end'])) {
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                continue;
            }

            $groupKey = $this->resolvePerformanceGroupByCode($rawCode);
            if ($groupKey !== null) {
                $intervalMinutes = $this->resolveCodeIntervalMinutes($rawCode, $office === '' ? 'YBS' : $office);
                if ($intervalMinutes > 0) {
                    $slotKey = (int) floor($minutes / $intervalMinutes);
                    if (!isset($groupSlots[$groupKey])) {
                        $groupSlots[$groupKey] = [];
                    }

                    $groupSlots[$groupKey][$slotKey] = true;
                }
            }

            $normalizedCode = $this->normalizeDetailCode($rawCode);
            if ($normalizedCode !== null) {
                $codeActual[$normalizedCode]++;
            }
        }

        foreach ($groupSlots as $groupKey => $slots) {
            $groupActual[$groupKey] = count($slots);
        }

        return [
            'group' => $groupActual,
            'code' => $codeActual,
        ];
    }

    private function getTimedCodeRowsCached(string $date, string $office): Collection
    {
        $key = $date . '|' . strtoupper(trim($office));

        if (!isset($this->timedCodeRowsCache[$key])) {
            $this->timedCodeRowsCache[$key] = collect()
                ->concat($this->fetchTimedCodeRows(KernelCalculation::query(), $date, $office))
                ->concat($this->fetchTimedCodeRows(KernelDirtMoistCalculation::query(), $date, $office))
                ->concat($this->fetchTimedCodeRows(KernelQwt::query(), $date, $office))
                ->concat($this->fetchTimedCodeRows(KernelRippleMill::query(), $date, $office))
                ->concat($this->fetchTimedCodeRows(KernelDestoner::query(), $date, $office))
                ->values();
        }

        return $this->timedCodeRowsCache[$key];
    }

    private function fetchTimedCodeRows($query, string $date, string $office): Collection
    {
        $baseDate = Carbon::parse($date);
        $startDateTime = $baseDate->copy()->setTime(7, 0, 0)->format('Y-m-d H:i:s');
        $endDateTime = $baseDate->copy()->addDay()->setTime(6, 59, 59)->format('Y-m-d H:i:s');
        $table = $query->getModel()->getTable();
        $hasPengulanganColumn = Schema::hasColumn($table, 'pengulangan');
        $selectColumns = ['kode', 'rounded_time', 'created_at', 'sampel_boy'];
        if ($hasPengulanganColumn) {
            $selectColumns[] = 'pengulangan';
        }

        $rows = $query
            ->where(function ($builder) use ($startDateTime, $endDateTime) {
                $builder->whereBetween('rounded_time', [$startDateTime, $endDateTime])
                    ->orWhere(function ($fallback) use ($startDateTime, $endDateTime) {
                        $fallback->whereNull('rounded_time')
                            ->whereBetween('created_at', [$startDateTime, $endDateTime]);
                    });
            })
            ->when($office !== '', fn($builder) => $builder->where('office', $office))
            ->when($hasPengulanganColumn, fn($builder) => $builder->where('pengulangan', false))
            ->get($selectColumns);

        return $rows->map(function ($row): array {
            $time = $this->extractTimeFromDateTimeValue($row->rounded_time);

            if ($time === '') {
                $time = $this->extractTimeFromDateTimeValue($row->created_at);
            }

            return [
                'code' => strtoupper(trim((string) $row->kode)),
                'time' => $time,
                'sampel_boy' => (string) ($row->sampel_boy ?? ''),
                'pengulangan' => (bool) ($row->pengulangan ?? false),
            ];
        })->values();
    }

    private function extractTimeFromDateTimeValue(mixed $value): string
    {
        if (empty($value)) {
            return '';
        }

        try {
            return Carbon::parse($value)
                ->setTimezone(config('app.timezone'))
                ->format('H:i');
        } catch (\Throwable) {
            return '';
        }
    }

    private function resolveCodesByMachineName(string $machineName): array
    {
        if (preg_match('/^FIBRE\s*CYCLONE\s*(\d+)$/', $machineName, $match)) {
            return ['FC' . $match[1]];
        }

        if (preg_match('/^LTDS\s+(\d+)$/', $machineName, $match)) {
            return ['L' . $match[1]];
        }

        if (preg_match('/^CLAYBATH\s*WET\s*SHELL\s*(\d+)$/', $machineName, $match)) {
            $index = $match[1];
            return $index === '1' ? ['CWS', 'CWS1'] : ['CWS' . $index];
        }

        if (preg_match('/^INLET\s*KERNEL\s*SILO\s*(\d+)$/', $machineName, $match)) {
            return ['IN' . $match[1]];
        }

        if (preg_match('/^OUTLET\s*KERNEL\s*SILO\s*(\d+)\s*TO\s*BUNKER$/', $machineName, $match)) {
            return ['OUT' . $match[1]];
        }

        if (preg_match('/^PRESS\s+(\d+)$/', $machineName, $match)) {
            return ['P' . $match[1]];
        }

        if (preg_match('/^RIPPLE\s*MILL\s*(?:NO\.?\s*)?(\d+)$/', $machineName, $match)) {
            return ['R' . $match[1]];
        }

        if (preg_match('/^DESTONER\s+(\d+)$/', $machineName, $match)) {
            return ['D' . $match[1]];
        }

        return [];
    }

    private function resolvePerformanceGroupByCode(string $code): ?string
    {
        $normalizedCode = $this->normalizeCode($code);

        foreach (self::PERFORMANCE_CODE_GROUPS as $groupKey => $codes) {
            foreach ($codes as $itemCode) {
                if ($this->normalizeCode($itemCode) === $normalizedCode) {
                    return $groupKey;
                }
            }
        }

        return null;
    }

    private function normalizeDetailCode(string $code): ?string
    {
        $normalized = $this->normalizeCode($code);

        foreach (self::PERFORMANCE_DETAIL_CODES as $detailCode) {
            foreach ($detailCode['aliases'] as $alias) {
                if ($normalized === $this->normalizeCode((string) $alias)) {
                    return (string) $detailCode['code'];
                }
            }
        }

        return null;
    }

    private function resolveCodeIntervalMinutes(string $code, string $office = 'YBS'): ?int
    {
        $groupKey = $this->resolvePerformanceGroupByCode($code);
        if ($groupKey === null) {
            return null;
        }

        $intervals = $this->getIntervalMinutesForOffice($office);

        return (int) ($intervals[$groupKey] ?? 0);
    }

    private function resolveMachineIntervalMinutes(string $machineName, string $office = 'YBS'): int
    {
        $groupKey = $this->resolvePerformanceGroupByMachineName(strtoupper(trim($machineName)));
        if ($groupKey === null) {
            return 0;
        }

        $intervals = $this->getIntervalMinutesForOffice($office);

        return (int) ($intervals[$groupKey] ?? 0);
    }

    private function calculateExpectedSamplesFromRowsWithConditions(
        KernelProsses $record,
        string $teamName,
        Collection $rows,
        int $intervalMinutes
    ): int {
        if ($intervalMinutes <= 0 || $rows->isEmpty()) {
            return 0;
        }

        $effectiveSegments = $this->buildEffectiveMachineSegmentsForExpected($record, $teamName, $rows);

        $expected = 0;
        foreach ($effectiveSegments as $segment) {
            $length = (int) ($segment['end'] - $segment['start']);
            if ($length <= 0) {
                continue;
            }

            $expected += intdiv($length, $intervalMinutes);
        }

        return $expected;
    }

    private function buildEffectiveMachineSegmentsForExpected(KernelProsses $record, string $teamName, Collection $rows): array
    {
        $baseSegments = [];
        foreach ($rows as $row) {
            $start = substr((string) ($row->production_start_time ?? ''), 0, 5);
            $end = substr((string) ($row->production_end_time ?? ''), 0, 5);

            $baseSegments = array_merge($baseSegments, $this->expandTimeRangeSegments($start, $end));
        }

        $baseSegments = $this->normalizeSegments($baseSegments);
        if (empty($baseSegments)) {
            return [];
        }

        $conditionSegments = collect($this->getTeamOtherConditions($record, $teamName))
            ->map(function (array $condition): array {
                $start = trim((string) ($condition['start_time'] ?? ''));
                $end = trim((string) ($condition['end_time'] ?? ''));

                return $this->expandTimeRangeSegments($start, $end);
            })
            ->flatten(1)
            ->values()
            ->all();

        $conditionSegments = $this->normalizeSegments($conditionSegments);

        return $this->subtractSegments($baseSegments, $conditionSegments);
    }

    private function normalizeSegments(array $segments): array
    {
        $normalized = collect($segments)
            ->filter(fn($segment): bool => is_array($segment)
                && isset($segment['start'], $segment['end'])
                && (int) $segment['end'] > (int) $segment['start'])
            ->map(fn($segment): array => [
                'start' => (int) $segment['start'],
                'end' => (int) $segment['end'],
            ])
            ->sortBy('start')
            ->values()
            ->all();

        if (empty($normalized)) {
            return [];
        }

        $merged = [];
        foreach ($normalized as $segment) {
            if (empty($merged)) {
                $merged[] = $segment;
                continue;
            }

            $lastIndex = count($merged) - 1;
            if ($segment['start'] <= $merged[$lastIndex]['end']) {
                $merged[$lastIndex]['end'] = max($merged[$lastIndex]['end'], $segment['end']);
                continue;
            }

            $merged[] = $segment;
        }

        return $merged;
    }

    private function subtractSegments(array $baseSegments, array $cutSegments): array
    {
        $base = $this->normalizeSegments($baseSegments);
        $cuts = $this->normalizeSegments($cutSegments);

        if (empty($base) || empty($cuts)) {
            return $base;
        }

        $result = [];

        foreach ($base as $segment) {
            $parts = [$segment];

            foreach ($cuts as $cut) {
                if (empty($parts)) {
                    break;
                }

                $nextParts = [];
                foreach ($parts as $part) {
                    if ($cut['end'] <= $part['start'] || $cut['start'] >= $part['end']) {
                        $nextParts[] = $part;
                        continue;
                    }

                    if ($cut['start'] > $part['start']) {
                        $nextParts[] = [
                            'start' => $part['start'],
                            'end' => $cut['start'],
                        ];
                    }

                    if ($cut['end'] < $part['end']) {
                        $nextParts[] = [
                            'start' => $cut['end'],
                            'end' => $part['end'],
                        ];
                    }
                }

                $parts = $nextParts;
            }

            $result = array_merge($result, $parts);
        }

        return $this->normalizeSegments($result);
    }

    private function minutesFromWindow(int $start, int $end): int
    {
        if ($end >= $start) {
            return $end - $start;
        }

        return (24 * 60 - $start) + $end;
    }

    private function normalizeCode(string $code): string
    {
        return strtoupper(str_replace([' ', '-', '_'], '', trim($code)));
    }

    private function timeToMinutes(string $time): ?int
    {
        $normalizedTime = substr(trim($time), 0, 5);

        if (!$this->isValidTime($normalizedTime)) {
            return null;
        }

        [$hour, $minute] = array_map('intval', explode(':', $normalizedTime));

        return ($hour * 60) + $minute;
    }

    private function isMinutesWithinRange(int $point, int $start, int $end): bool
    {
        if ($end >= $start) {
            return $point >= $start && $point <= $end;
        }

        return $point >= $start || $point <= $end;
    }

    private function ensureValidTeam(int $team): void
    {
        abort_unless(in_array($team, [1, 2], true), 404);
    }

    private function minutesBetween(string $start, string $end): int
    {
        if ($start === '' || $end === '') {
            return 0;
        }

        $startTotal = $this->timeToMinutes($start);
        $endTotal = $this->timeToMinutes($end);

        if ($startTotal === null || $endTotal === null) {
            return 0;
        }

        if ($endTotal < $startTotal) {
            $endTotal += 24 * 60;
        }

        return $endTotal - $startTotal;
    }

    private function hoursToMinutes(float $hours): int
    {
        if ($hours <= 0) {
            return 0;
        }

        return (int) round($hours * 60);
    }

    private function resolveMachineTotalMinutes(KernelMesin $machine): int
    {
        $minutesFromHours = $this->hoursToMinutes((float) ($machine->total_produsction_hours ?? 0));
        if ($minutesFromHours > 0) {
            return $minutesFromHours;
        }

        $minutesFromWindow = $this->minutesBetween(
            substr((string) $machine->production_start_time, 0, 5),
            substr((string) $machine->production_end_time, 0, 5)
        );

        return max($minutesFromWindow, 0);
    }

    private function formatMinutes(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        return sprintf('%02d:%02d', $hours, $mins);
    }

    private function calculateOtherConditionDeductionMinutes(string $machineStart, string $machineEnd, array $conditions): int
    {
        if (!$this->isValidTime($machineStart) || !$this->isValidTime($machineEnd)) {
            return 0;
        }

        $overlapSegments = [];

        foreach ($conditions as $condition) {
            $startTime = trim((string) ($condition['start_time'] ?? ''));
            $endTime = trim((string) ($condition['end_time'] ?? ''));

            if (!$this->isValidTime($startTime) || !$this->isValidTime($endTime)) {
                continue;
            }

            $overlapSegments = array_merge(
                $overlapSegments,
                $this->intersectTimeRanges($machineStart, $machineEnd, $startTime, $endTime)
            );
        }

        return $this->sumMergedMinutes($overlapSegments);
    }

    private function intersectTimeRanges(string $startA, string $endA, string $startB, string $endB): array
    {
        $segmentsA = $this->expandTimeRangeSegments($startA, $endA);
        $segmentsB = $this->expandTimeRangeSegments($startB, $endB);
        $intersections = [];

        foreach ($segmentsA as $segmentA) {
            foreach ($segmentsB as $segmentB) {
                $start = max($segmentA['start'], $segmentB['start']);
                $end = min($segmentA['end'], $segmentB['end']);

                if ($end > $start) {
                    $intersections[] = [
                        'start' => $start,
                        'end' => $end,
                    ];
                }
            }
        }

        return $intersections;
    }

    private function expandTimeRangeSegments(string $start, string $end): array
    {
        $startMinute = $this->timeToMinutes($start);
        $endMinute = $this->timeToMinutes($end);

        if ($startMinute === null || $endMinute === null || $startMinute === $endMinute) {
            return [];
        }

        if ($endMinute > $startMinute) {
            return [
                [
                    'start' => $startMinute,
                    'end' => $endMinute,
                ]
            ];
        }

        return [
            [
                'start' => $startMinute,
                'end' => 24 * 60,
            ],
            [
                'start' => 0,
                'end' => $endMinute,
            ],
        ];
    }

    private function sumMergedMinutes(array $segments): int
    {
        $normalized = collect($segments)
            ->filter(fn($segment): bool => is_array($segment)
                && isset($segment['start'], $segment['end'])
                && (int) $segment['end'] > (int) $segment['start'])
            ->map(fn($segment): array => [
                'start' => (int) $segment['start'],
                'end' => (int) $segment['end'],
            ])
            ->sortBy('start')
            ->values()
            ->all();

        if (empty($normalized)) {
            return 0;
        }

        $merged = [];
        foreach ($normalized as $segment) {
            if (empty($merged)) {
                $merged[] = $segment;
                continue;
            }

            $lastIndex = count($merged) - 1;
            if ($segment['start'] <= $merged[$lastIndex]['end']) {
                $merged[$lastIndex]['end'] = max($merged[$lastIndex]['end'], $segment['end']);
                continue;
            }

            $merged[] = $segment;
        }

        return (int) collect($merged)->sum(fn($segment): int => $segment['end'] - $segment['start']);
    }

    private function extractMachinePayload(Request $request, ?string $inputTeam = null, ?string $office = null): array
    {
        $machineGroups = $this->getMachineGroupsForOffice($office);

        $allowedByGroup = collect($machineGroups)
            ->map(fn(array $machines): array => array_values($machines));

        $machineToGroup = collect($machineGroups)
            ->flatMap(function (array $machines, string $group): array {
                $map = [];
                foreach ($machines as $machine) {
                    $map[$machine] = $group;
                }

                return $map;
            });

        $entries = [];
        $errors = [];
        $otherConditionsByTeam = collect();

        foreach ((array) $request->input('machines', []) as $idx => $machine) {
            if (!is_array($machine) || !$this->toBool($machine['selected'] ?? false)) {
                continue;
            }

            $machineName = trim((string) ($machine['machine_name'] ?? ''));
            $machineGroup = trim((string) ($machine['machine_group'] ?? ''));
            $teamName = trim((string) ($machine['team_name'] ?? ''));
            $startTime = trim((string) ($machine['start_time'] ?? ''));
            $endTime = trim((string) ($machine['end_time'] ?? ''));

            if ($inputTeam !== null && $teamName !== $inputTeam) {
                continue;
            }

            if (!$allowedByGroup->has($machineGroup)) {
                $errors["machines.$idx.machine_group"] = 'Sub mesin tidak valid.';
            }

            if ($machineGroup !== '' && !in_array($machineName, $allowedByGroup->get($machineGroup, []), true)) {
                $errors["machines.$idx.machine_name"] = 'Nama sub mesin tidak valid.';
            }

            if (!in_array($teamName, self::TEAM_OPTIONS, true)) {
                $errors["machines.$idx.team_name"] = 'Tim mesin wajib dipilih.';
            }

            if (!$this->isValidTime($startTime)) {
                $errors["machines.$idx.start_time"] = 'Jam awal produksi wajib diisi dengan format HH:MM.';
            }

            if (!$this->isValidTime($endTime)) {
                $errors["machines.$idx.end_time"] = 'Jam akhir produksi wajib diisi dengan format HH:MM.';
            }

            $entries[] = [
                'team_name' => $teamName,
                'machine_group' => $machineGroup,
                'machine_name' => $machineName,
                'production_start_time' => $startTime,
                'production_end_time' => $endTime,
                'row_hours' => ($this->isValidTime($startTime) && $this->isValidTime($endTime))
                    ? round($this->minutesBetween($startTime, $endTime) / 60, 2)
                    : 0,
                'is_spare_input' => false,
            ];
        }

        foreach ((array) $request->input('spare_machines', []) as $idx => $machine) {
            if (!is_array($machine)) {
                continue;
            }

            $teamName = trim((string) ($machine['team_name'] ?? ''));
            $machineName = trim((string) ($machine['machine_name'] ?? ''));
            $startTime = trim((string) ($machine['start_time'] ?? ''));
            $endTime = trim((string) ($machine['end_time'] ?? ''));

            if ($inputTeam !== null && $teamName !== $inputTeam) {
                continue;
            }

            if ($machineName === '' && $startTime === '' && $endTime === '') {
                continue;
            }

            if ($machineName === '') {
                $errors["spare_machines.$idx.machine_name"] = 'Nama mesin spare wajib diisi.';
            }

            if ($machineName !== '' && !$machineToGroup->has($machineName)) {
                $errors["spare_machines.$idx.machine_name"] = 'Nama mesin spare tidak valid.';
            }

            if (!in_array($teamName, self::TEAM_OPTIONS, true)) {
                $errors["spare_machines.$idx.team_name"] = 'Tim mesin spare wajib dipilih.';
            }

            if (!$this->isValidTime($startTime)) {
                $errors["spare_machines.$idx.start_time"] = 'Jam awal mesin spare wajib diisi dengan format HH:MM.';
            }

            if (!$this->isValidTime($endTime)) {
                $errors["spare_machines.$idx.end_time"] = 'Jam akhir mesin spare wajib diisi dengan format HH:MM.';
            }

            $entries[] = [
                'team_name' => $teamName,
                'machine_group' => (string) $machineToGroup->get($machineName, 'SPARE INPUT'),
                'machine_name' => $machineName,
                'production_start_time' => $startTime,
                'production_end_time' => $endTime,
                'row_hours' => ($this->isValidTime($startTime) && $this->isValidTime($endTime))
                    ? round($this->minutesBetween($startTime, $endTime) / 60, 2)
                    : 0,
                'is_spare_input' => true,
            ];
        }

        foreach ((array) $request->input('other_conditions', []) as $idx => $condition) {
            if (!is_array($condition)) {
                continue;
            }

            $teamName = trim((string) ($condition['team_name'] ?? ''));
            $reason = trim((string) ($condition['reason'] ?? ''));
            $startTime = trim((string) ($condition['start_time'] ?? ''));
            $endTime = trim((string) ($condition['end_time'] ?? ''));

            if ($inputTeam !== null && $teamName !== $inputTeam) {
                continue;
            }

            if ($reason === '' && $startTime === '' && $endTime === '') {
                continue;
            }

            if (!in_array($teamName, self::TEAM_OPTIONS, true)) {
                $errors["other_conditions.$idx.team_name"] = 'Tim kondisi lainnya wajib dipilih.';
            }

            if ($reason === '') {
                $errors["other_conditions.$idx.reason"] = 'Alasan kondisi lainnya wajib diisi.';
            }

            if (!$this->isValidTime($startTime)) {
                $errors["other_conditions.$idx.start_time"] = 'Jam mulai kondisi lainnya wajib diisi dengan format HH:MM.';
            }

            if (!$this->isValidTime($endTime)) {
                $errors["other_conditions.$idx.end_time"] = 'Jam selesai kondisi lainnya wajib diisi dengan format HH:MM.';
            }

            if (!isset($errors["other_conditions.$idx.start_time"]) && !isset($errors["other_conditions.$idx.end_time"])) {
                $otherConditionsByTeam->push([
                    'team_name' => $teamName,
                    'reason' => $reason,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ]);
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        $spareHoursByTeamMachine = collect($entries)
            ->where('is_spare_input', true)
            ->groupBy(fn(array $entry): string => $entry['team_name'] . '|' . $entry['machine_name'])
            ->map(fn($rows): float => round((float) $rows->sum('row_hours'), 2));

        $otherConditionsByTeam = $otherConditionsByTeam
            ->groupBy('team_name')
            ->map(fn(Collection $rows): array => $rows->values()->all());

        return collect($entries)
            ->map(function (array $entry) use ($spareHoursByTeamMachine, $otherConditionsByTeam): array {
                $rowHours = (float) $entry['row_hours'];
                $spareHours = 0.0;
                $totalHours = $rowHours;

                if (!$entry['is_spare_input']) {
                    $teamMachineKey = $entry['team_name'] . '|' . $entry['machine_name'];
                    $teamConditions = (array) $otherConditionsByTeam->get($entry['team_name'], []);
                    $deductionMinutes = $this->calculateOtherConditionDeductionMinutes(
                        (string) $entry['production_start_time'],
                        (string) $entry['production_end_time'],
                        $teamConditions
                    );

                    $deductionHours = round($deductionMinutes / 60, 2);
                    $effectiveRowHours = max(round($rowHours - $deductionHours, 2), 0);

                    $spareHours = (float) $spareHoursByTeamMachine->get($teamMachineKey, 0);
                    $totalHours = $effectiveRowHours + $spareHours;
                }

                return [
                    'team_name' => $entry['team_name'],
                    'machine_group' => $entry['machine_group'],
                    'machine_name' => $entry['machine_name'],
                    'production_start_time' => $entry['production_start_time'],
                    'production_end_time' => $entry['production_end_time'],
                    'total_produsction_hours' => round($totalHours, 2),
                    'is_spare' => round($entry['is_spare_input'] ? $rowHours : $spareHours, 2),
                    'is_spare_input' => $entry['is_spare_input'],
                ];
            })
            ->values()
            ->all();
    }

    private function getMachineGroupsForOffice(?string $office = null): array
    {
        $officeCode = strtoupper(trim((string) ($office ?? auth()->user()->office ?? 'YBS')));

        $machineGroupsFromMaster = KernelMasterData::query()
            ->where('is_active', true)
            ->where('office', $officeCode)
            ->orderBy('kode')
            ->get(['nama_sample'])
            ->groupBy(function ($row): string {
                return $this->resolveMachineGroupFromSampleName((string) $row->nama_sample);
            })
            ->map(function ($rows): array {
                return $rows
                    ->pluck('nama_sample')
                    ->map(fn($name) => strtoupper(trim((string) $name)))
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();
            })
            ->filter(fn(array $rows): bool => !empty($rows))
            ->toArray();

        if (!empty($machineGroupsFromMaster)) {
            return $machineGroupsFromMaster;
        }

        return match ($officeCode) {
            'SUN' => self::MACHINE_GROUPS_SUN,
            'SJN' => self::MACHINE_GROUPS_SJN,
            default => self::MACHINE_GROUPS_YBS,
        };
    }

    private function resolveMachineGroupFromSampleName(string $sampleName): string
    {
        $name = strtoupper(trim($sampleName));

        if (str_starts_with($name, 'FIBRE CYCLONE')) {
            return 'FIBRE CYCLONE';
        }

        if (str_starts_with($name, 'LTDS')) {
            return 'LTDS';
        }

        if (str_starts_with($name, 'CLAYBATH WET SHELL')) {
            return 'CLAYBATH WET SHELL';
        }

        if (str_starts_with($name, 'INLET KERNEL SILO')) {
            return 'INLET KERNEL SILO';
        }

        if (str_starts_with($name, 'OUTLET KERNEL SILO')) {
            return 'OUTLET KERNEL SILO TO BUNKER';
        }

        if (str_starts_with($name, 'PRESS')) {
            return 'PRESS';
        }

        if (str_starts_with($name, 'RIPPLE MILL')) {
            return 'RIPPLE MILL';
        }

        if (str_starts_with($name, 'DESTONER')) {
            return 'DESTONER';
        }

        return 'LAINNYA';
    }

    private function extractOtherConditionsPayload(Request $request, ?string $inputTeam = null): array
    {
        $grouped = [
            'Tim 1' => [],
            'Tim 2' => [],
        ];

        foreach ((array) $request->input('other_conditions', []) as $condition) {
            if (!is_array($condition)) {
                continue;
            }

            $teamName = trim((string) ($condition['team_name'] ?? ''));
            $reason = trim((string) ($condition['reason'] ?? ''));
            $startTime = trim((string) ($condition['start_time'] ?? ''));
            $endTime = trim((string) ($condition['end_time'] ?? ''));

            if ($inputTeam !== null && $teamName !== $inputTeam) {
                continue;
            }

            if ($reason === '' && $startTime === '' && $endTime === '') {
                continue;
            }

            if (!in_array($teamName, self::TEAM_OPTIONS, true)) {
                continue;
            }

            if ($reason === '' || !$this->isValidTime($startTime) || !$this->isValidTime($endTime)) {
                continue;
            }

            $grouped[$teamName][] = [
                'reason' => $reason,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ];
        }

        return $grouped;
    }

    private function requestContainsTeamConditions(Request $request, string $teamName): bool
    {
        foreach ((array) $request->input('other_conditions', []) as $condition) {
            if (!is_array($condition)) {
                continue;
            }

            if (trim((string) ($condition['team_name'] ?? '')) === $teamName) {
                return true;
            }
        }

        return false;
    }

    private function getTeamOtherConditions(KernelProsses $record, string $teamName): array
    {
        $rows = $teamName === 'Tim 1'
            ? (array) ($record->team_1_other_conditions ?? [])
            : (array) ($record->team_2_other_conditions ?? []);

        return collect($rows)
            ->filter(fn($row): bool => is_array($row))
            ->map(function (array $row): array {
                $start = trim((string) ($row['start_time'] ?? ''));
                $end = trim((string) ($row['end_time'] ?? ''));
                $minutes = ($this->isValidTime($start) && $this->isValidTime($end))
                    ? $this->minutesBetween($start, $end)
                    : 0;

                return [
                    'reason' => trim((string) ($row['reason'] ?? '')),
                    'start_time' => $start,
                    'end_time' => $end,
                    'duration_minutes' => $minutes,
                    'duration_hours' => round($minutes / 60, 2),
                ];
            })
            ->filter(fn(array $row): bool => $row['reason'] !== '' && $row['start_time'] !== '' && $row['end_time'] !== '')
            ->values()
            ->all();
    }

    private function isValidTime(string $time): bool
    {
        if ($time === '') {
            return false;
        }

        $date = \DateTime::createFromFormat('H:i', $time);

        return $date !== false && $date->format('H:i') === $time;
    }

    private function toBool(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
    }

    private function getIntervalMinutesForOffice(string $office): array
    {
        if ($office === 'SUN') {
            return self::PERFORMANCE_GROUP_INTERVAL_MINUTES_SUN;
        }

        // Default to YBS intervals for all other offices
        return self::PERFORMANCE_GROUP_INTERVAL_MINUTES;
    }

    private function getAvailableOfficesForPerformance(): array
    {
        $offices = KernelProsses::query()
            ->whereNotNull('office')
            ->select('office')
            ->distinct()
            ->orderBy('office')
            ->pluck('office')
            ->map(fn($office) => trim((string) $office))
            ->filter(fn($office) => $office !== '')
            ->values()
            ->all();

        $userOffice = trim((string) (auth()->user()->office ?? ''));
        if ($userOffice !== '' && !in_array($userOffice, $offices, true)) {
            $offices[] = $userOffice;
            sort($offices);
        }

        return $offices;
    }

    private function getTeamMembersByOffice(): array
    {
        $office = trim((string) (auth()->user()->office ?? ''));

        return User::query()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'like', 'Sampel Boy%');
            })
            ->when($office !== '', fn($query) => $query->where('office', $office))
            ->orderBy('name')
            ->pluck('name')
            ->filter(fn($name) => $name !== null && $name !== '')
            ->unique()
            ->values()
            ->all();
    }

    private function resolveProcessRoleFlags(): array
    {
        $user = auth()->user();
        $canManageTeamMeta = $user
            ? ($user->can('create informasi proses mesin') || $user->can('edit informasi proses mesin'))
            : false;

        $canManageMachineData = $user
            ? ($user->can('create jam proses mesin') || $user->can('edit jam proses mesin'))
            : false;

        return [
            'can_manage_team_meta' => $canManageTeamMeta,
            'can_manage_machine_data' => $canManageMachineData,
        ];
    }

    private function buildTeamMemberValidationRules(): array
    {
        $members = $this->getTeamMembersByOffice();
        $rules = ['string'];

        if (!empty($members)) {
            $rules[] = Rule::in($members);
        }

        return $rules;
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ── ANALISA MOISTURE & SPINTES ────────────────────────────────────────────
    // ══════════════════════════════════════════════════════════════════════════

    public function analisisaMoistureInput()
    {
        return view('process.analisa-moisture.input', [
            'cstMachines' => self::CST_MACHINES,
            'decanterMachines' => self::DECANTER_MACHINES,
            'lightPhaseMachines' => self::LIGHT_PHASE_MACHINES,
        ]);
    }

    public function analisisaMoistureStore(Request $request)
    {
        if ($request->boolean('bulk_submit')) {
            $userId = auth()->id();

            return DB::transaction(function () use ($request, $userId) {
                $validated = $request->validate([
                    'ffa.tanggal' => ['nullable', 'date'],
                    'ffa.jam' => ['nullable', 'date_format:H:i'],
                    'ffa.moisture' => ['nullable', 'string', 'max:100'],
                    'ffa.bst1_ffa' => ['nullable', 'numeric'],
                    'ffa.bst2_ffa' => ['nullable', 'numeric'],
                    'ffa.bst3_ffa' => ['nullable', 'numeric'],
                    'ffa.impurities' => ['nullable', 'numeric'],

                    'cot.tanggal' => ['nullable', 'date'],
                    'cot.jam' => ['nullable', 'date_format:H:i'],
                    'cot.oil' => ['nullable', 'numeric'],
                    'cot.emulsi' => ['nullable', 'numeric'],
                    'cot.air' => ['nullable', 'numeric'],
                    'cot.nos' => ['nullable', 'numeric'],

                    'machines.spintest_cst' => ['nullable', 'array'],
                    'machines.spintest_cst.*.tanggal' => ['nullable', 'date'],
                    'machines.spintest_cst.*.jam' => ['nullable', 'date_format:H:i'],
                    'machines.spintest_cst.*.machine_name' => ['nullable', Rule::in(self::CST_MACHINES)],
                    'machines.spintest_cst.*.oil' => ['nullable', 'numeric'],
                    'machines.spintest_cst.*.emulsi' => ['nullable', 'numeric'],
                    'machines.spintest_cst.*.air' => ['nullable', 'numeric'],
                    'machines.spintest_cst.*.nos' => ['nullable', 'numeric'],

                    'machines.spintest_feed_decanter' => ['nullable', 'array'],
                    'machines.spintest_feed_decanter.*.tanggal' => ['nullable', 'date'],
                    'machines.spintest_feed_decanter.*.jam' => ['nullable', 'date_format:H:i'],
                    'machines.spintest_feed_decanter.*.machine_name' => ['nullable', Rule::in(self::DECANTER_MACHINES)],
                    'machines.spintest_feed_decanter.*.oil' => ['nullable', 'numeric'],
                    'machines.spintest_feed_decanter.*.emulsi' => ['nullable', 'numeric'],
                    'machines.spintest_feed_decanter.*.air' => ['nullable', 'numeric'],
                    'machines.spintest_feed_decanter.*.nos' => ['nullable', 'numeric'],

                    'machines.spintest_light_phase' => ['nullable', 'array'],
                    'machines.spintest_light_phase.*.tanggal' => ['nullable', 'date'],
                    'machines.spintest_light_phase.*.jam' => ['nullable', 'date_format:H:i'],
                    'machines.spintest_light_phase.*.machine_name' => ['nullable', Rule::in(self::LIGHT_PHASE_MACHINES)],
                    'machines.spintest_light_phase.*.oil' => ['nullable', 'numeric'],
                    'machines.spintest_light_phase.*.emulsi' => ['nullable', 'numeric'],
                    'machines.spintest_light_phase.*.air' => ['nullable', 'numeric'],
                    'machines.spintest_light_phase.*.nos' => ['nullable', 'numeric'],
                ]);

                $savedCount = 0;

                if ($this->hasAnyMeaningfulValue($validated['ffa'] ?? [])) {
                    FfaMoisture::create([
                        'user_id' => $userId,
                        'tanggal' => $validated['ffa']['tanggal'] ?? now()->toDateString(),
                        'jam' => $validated['ffa']['jam'] ?? now()->format('H:i'),
                        'moisture' => $this->stringOrDefault($validated['ffa']['moisture'] ?? null, '0'),
                        'bst1_ffa' => $this->numericOrZero($validated['ffa']['bst1_ffa'] ?? null),
                        'bst2_ffa' => $this->numericOrZero($validated['ffa']['bst2_ffa'] ?? null),
                        'bst3_ffa' => $this->numericOrZero($validated['ffa']['bst3_ffa'] ?? null),
                        'impurities' => $this->numericOrZero($validated['ffa']['impurities'] ?? null),
                    ]);
                    $savedCount++;
                }

                if ($this->hasAnyMeaningfulValue($validated['cot'] ?? [])) {
                    SpintestCot::create([
                        'user_id' => $userId,
                        'tanggal' => $validated['cot']['tanggal'] ?? now()->toDateString(),
                        'jam' => $validated['cot']['jam'] ?? now()->format('H:i'),
                        'oil' => $this->numericOrZero($validated['cot']['oil'] ?? null),
                        'emulsi' => $this->numericOrZero($validated['cot']['emulsi'] ?? null),
                        'air' => $this->numericOrZero($validated['cot']['air'] ?? null),
                        'nos' => $this->numericOrZero($validated['cot']['nos'] ?? null),
                    ]);
                    $savedCount++;
                }

                foreach (($validated['machines']['spintest_cst'] ?? []) as $row) {
                    if (!$this->hasAnyMeaningfulValue($row, ['machine_name'])) {
                        continue;
                    }

                    SpintestCst::create([
                        'user_id' => $userId,
                        'tanggal' => $row['tanggal'] ?? now()->toDateString(),
                        'jam' => $row['jam'] ?? now()->format('H:i'),
                        'machine_name' => $row['machine_name'] ?? self::CST_MACHINES[0],
                        'oil' => $this->numericOrZero($row['oil'] ?? null),
                        'emulsi' => $this->numericOrZero($row['emulsi'] ?? null),
                        'air' => $this->numericOrZero($row['air'] ?? null),
                        'nos' => $this->numericOrZero($row['nos'] ?? null),
                    ]);
                    $savedCount++;
                }

                foreach (($validated['machines']['spintest_feed_decanter'] ?? []) as $row) {
                    if (!$this->hasAnyMeaningfulValue($row, ['machine_name'])) {
                        continue;
                    }

                    SpintestFeedDecanter::create([
                        'user_id' => $userId,
                        'tanggal' => $row['tanggal'] ?? now()->toDateString(),
                        'jam' => $row['jam'] ?? now()->format('H:i'),
                        'machine_name' => $row['machine_name'] ?? self::DECANTER_MACHINES[0],
                        'oil' => $this->numericOrZero($row['oil'] ?? null),
                        'emulsi' => $this->numericOrZero($row['emulsi'] ?? null),
                        'air' => $this->numericOrZero($row['air'] ?? null),
                        'nos' => $this->numericOrZero($row['nos'] ?? null),
                    ]);
                    $savedCount++;
                }

                foreach (($validated['machines']['spintest_light_phase'] ?? []) as $row) {
                    if (!$this->hasAnyMeaningfulValue($row, ['machine_name'])) {
                        continue;
                    }

                    SpintestLightPhase::create([
                        'user_id' => $userId,
                        'tanggal' => $row['tanggal'] ?? now()->toDateString(),
                        'jam' => $row['jam'] ?? now()->format('H:i'),
                        'machine_name' => $row['machine_name'] ?? self::LIGHT_PHASE_MACHINES[0],
                        'oil' => $this->numericOrZero($row['oil'] ?? null),
                        'emulsi' => $this->numericOrZero($row['emulsi'] ?? null),
                        'air' => $this->numericOrZero($row['air'] ?? null),
                        'nos' => $this->numericOrZero($row['nos'] ?? null),
                    ]);
                    $savedCount++;
                }

                if ($savedCount === 0) {
                    throw ValidationException::withMessages([
                        'bulk_submit' => 'Minimal satu form harus diisi agar data bisa disimpan.',
                    ]);
                }

                return redirect()->route('analisa-moisture.input')
                    ->with('success', 'Semua data analisa berhasil disimpan.');
            });
        }

        $module = (string) $request->input('module', '');
        $userId = auth()->id();

        return DB::transaction(function () use ($request, $module, $userId) {
            $redirectRoute = 'analisa-moisture.ffa-moisture';
            $successMessage = 'Data berhasil disimpan.';

            switch ($module) {
                case 'ffa_moisture':
                    $validated = $request->validate([
                        'tanggal' => ['required', 'date'],
                        'jam' => ['required', 'date_format:H:i'],
                        'moisture' => ['required', 'string', 'max:100'],
                        'bst1_ffa' => ['required', 'numeric'],
                        'bst2_ffa' => ['required', 'numeric'],
                        'bst3_ffa' => ['required', 'numeric'],
                        'impurities' => ['required', 'numeric'],
                    ]);

                    FfaMoisture::create($validated + ['user_id' => $userId]);
                    $redirectRoute = 'analisa-moisture.ffa-moisture';
                    $successMessage = 'Data analisa FFA dan Moisture berhasil disimpan.';
                    break;

                case 'spintest_cot':
                    $validated = $request->validate([
                        'tanggal' => ['required', 'date'],
                        'jam' => ['required', 'date_format:H:i'],
                        'oil' => ['required', 'numeric'],
                        'emulsi' => ['required', 'numeric'],
                        'air' => ['required', 'numeric'],
                        'nos' => ['required', 'numeric'],
                    ]);

                    SpintestCot::create($validated + ['user_id' => $userId]);
                    $redirectRoute = 'analisa-moisture.spintest-cot';
                    $successMessage = 'Data analisa Spintest COT berhasil disimpan.';
                    break;

                case 'spintest_cst':
                    $validated = $request->validate([
                        'tanggal' => ['required', 'date'],
                        'jam' => ['required', 'date_format:H:i'],
                        'machine_name' => ['required', Rule::in(self::CST_MACHINES)],
                        'oil' => ['required', 'numeric'],
                        'emulsi' => ['required', 'numeric'],
                        'air' => ['required', 'numeric'],
                        'nos' => ['required', 'numeric'],
                    ]);

                    SpintestCst::create($validated + ['user_id' => $userId]);
                    $redirectRoute = 'analisa-moisture.spintest-underflow-cst';
                    $successMessage = 'Data analisa Spintest Underflow CST berhasil disimpan.';
                    break;

                case 'spintest_feed_decanter':
                    $validated = $request->validate([
                        'tanggal' => ['required', 'date'],
                        'jam' => ['required', 'date_format:H:i'],
                        'machine_name' => ['required', Rule::in(self::DECANTER_MACHINES)],
                        'oil' => ['required', 'numeric'],
                        'emulsi' => ['required', 'numeric'],
                        'air' => ['required', 'numeric'],
                        'nos' => ['required', 'numeric'],
                    ]);

                    SpintestFeedDecanter::create($validated + ['user_id' => $userId]);
                    $redirectRoute = 'analisa-moisture.spintest-feed-decanter';
                    $successMessage = 'Data analisa Spintest Feed Decanter berhasil disimpan.';
                    break;

                case 'spintest_light_phase':
                    $validated = $request->validate([
                        'tanggal' => ['required', 'date'],
                        'jam' => ['required', 'date_format:H:i'],
                        'machine_name' => ['required', Rule::in(self::LIGHT_PHASE_MACHINES)],
                        'oil' => ['required', 'numeric'],
                        'emulsi' => ['required', 'numeric'],
                        'air' => ['required', 'numeric'],
                        'nos' => ['required', 'numeric'],
                    ]);

                    SpintestLightPhase::create($validated + ['user_id' => $userId]);
                    $redirectRoute = 'analisa-moisture.spintest-light-phase';
                    $successMessage = 'Data analisa Spintest Light Phase berhasil disimpan.';
                    break;

                default:
                    abort(422, 'Module analisa tidak valid.');
            }

            return redirect()->route($redirectRoute)->with('success', $successMessage);
        });
    }

    public function analisaFfaMoisture(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);

        $rows = FfaMoisture::query()
            ->with('user')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->paginate(25)
            ->appends($request->except('page'));

        return view('process.analisa-moisture.ffa-moisture', compact('rows', 'startDate', 'endDate'));
    }

    public function editAnalisaFfaMoisture(FfaMoisture $ffaMoisture)
    {
        return view('process.analisa-moisture.edit-ffa-moisture', [
            'row' => $ffaMoisture,
        ]);
    }

    public function updateAnalisaFfaMoisture(Request $request, FfaMoisture $ffaMoisture)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'moisture' => ['nullable', 'string', 'max:100'],
            'bst1_ffa' => ['nullable', 'numeric'],
            'bst2_ffa' => ['nullable', 'numeric'],
            'bst3_ffa' => ['nullable', 'numeric'],
            'impurities' => ['nullable', 'numeric'],
        ]);

        $ffaMoisture->update([
            'tanggal' => $validated['tanggal'],
            'jam' => $validated['jam'],
            'moisture' => $this->stringOrDefault($validated['moisture'] ?? null, '0'),
            'bst1_ffa' => $this->numericOrZero($validated['bst1_ffa'] ?? null),
            'bst2_ffa' => $this->numericOrZero($validated['bst2_ffa'] ?? null),
            'bst3_ffa' => $this->numericOrZero($validated['bst3_ffa'] ?? null),
            'impurities' => $this->numericOrZero($validated['impurities'] ?? null),
        ]);

        return redirect()
            ->route('analisa-moisture.ffa-moisture', $this->buildAnalysisListQuery($ffaMoisture->tanggal?->toDateString()))
            ->with('success', 'Data analisa FFA dan Moisture berhasil diperbarui.');
    }

    public function destroyAnalisaFfaMoisture(FfaMoisture $ffaMoisture)
    {
        $tanggal = $ffaMoisture->tanggal?->toDateString();
        $ffaMoisture->delete();

        return redirect()
            ->route('analisa-moisture.ffa-moisture', $this->buildAnalysisListQuery($tanggal))
            ->with('success', 'Data analisa FFA dan Moisture berhasil dihapus.');
    }

    public function exportAnalisaFfaMoisture(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);

        $rows = FfaMoisture::query()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get()
            ->map(function (FfaMoisture $row): array {
                return [
                    Carbon::parse($row->tanggal)->format('d-m-Y'),
                    $row->jam,
                    $row->moisture,
                    $row->bst1_ffa,
                    $row->bst2_ffa,
                    $row->bst3_ffa,
                    $row->impurities,
                ];
            })
            ->values();

        return $this->downloadAnalysisExport(
            'Data Analisa FFA dan Moisture',
            $startDate,
            $endDate,
            $rows,
            ['Tanggal', 'Jam', 'Moisture', 'BST1 (FFA)', 'BST2 (FFA)', 'BST3 (FFA)', 'Impurities'],
            'FFA_Moisture_' . $startDate . '_to_' . $endDate . '.xlsx'
        );
    }

    public function analisaSpintestCot(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);

        $rows = SpintestCot::query()
            ->with('user')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->paginate(25)
            ->appends($request->except('page'));

        return view('process.analisa-moisture.spintest-cot', compact('rows', 'startDate', 'endDate'));
    }

    public function editAnalisaSpintestCot(SpintestCot $spintestCot)
    {
        return view('process.analisa-moisture.edit-spintest-cot', [
            'row' => $spintestCot,
        ]);
    }

    public function updateAnalisaSpintestCot(Request $request, SpintestCot $spintestCot)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'oil' => ['nullable', 'numeric'],
            'emulsi' => ['nullable', 'numeric'],
            'air' => ['nullable', 'numeric'],
            'nos' => ['nullable', 'numeric'],
        ]);

        $spintestCot->update([
            'tanggal' => $validated['tanggal'],
            'jam' => $validated['jam'],
            'oil' => $this->numericOrZero($validated['oil'] ?? null),
            'emulsi' => $this->numericOrZero($validated['emulsi'] ?? null),
            'air' => $this->numericOrZero($validated['air'] ?? null),
            'nos' => $this->numericOrZero($validated['nos'] ?? null),
        ]);

        return redirect()
            ->route('analisa-moisture.spintest-cot', $this->buildAnalysisListQuery($spintestCot->tanggal?->toDateString()))
            ->with('success', 'Data analisa Spintest COT berhasil diperbarui.');
    }

    public function destroyAnalisaSpintestCot(SpintestCot $spintestCot)
    {
        $tanggal = $spintestCot->tanggal?->toDateString();
        $spintestCot->delete();

        return redirect()
            ->route('analisa-moisture.spintest-cot', $this->buildAnalysisListQuery($tanggal))
            ->with('success', 'Data analisa Spintest COT berhasil dihapus.');
    }

    public function exportAnalisaSpintestCot(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);

        $rows = SpintestCot::query()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get()
            ->map(function (SpintestCot $row): array {
                return [
                    Carbon::parse($row->tanggal)->format('d-m-Y'),
                    $row->jam,
                    $row->oil,
                    $row->emulsi,
                    $row->air,
                    $row->nos,
                ];
            })
            ->values();

        return $this->downloadAnalysisExport(
            'Data Analisa Spintest COT',
            $startDate,
            $endDate,
            $rows,
            ['Tanggal', 'Jam', 'OIL', 'EMULSI', 'AIR', 'NOS'],
            'Spintest_COT_' . $startDate . '_to_' . $endDate . '.xlsx'
        );
    }

    public function analisaSpintestUnderflowCst(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);
        $selectedMachine = trim((string) $request->input('machine_name', 'all'));

        if ($selectedMachine !== 'all' && !in_array($selectedMachine, self::CST_MACHINES, true)) {
            $selectedMachine = 'all';
        }

        $rows = SpintestCst::query()
            ->with('user')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($selectedMachine !== 'all', fn($query) => $query->where('machine_name', $selectedMachine))
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->orderBy('machine_name')
            ->paginate(25)
            ->appends($request->except('page'));

        return view('process.analisa-moisture.spintest-underflow-cst', [
            'rows' => $rows,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'machineOptions' => self::CST_MACHINES,
            'selectedMachine' => $selectedMachine,
        ]);
    }

    public function editAnalisaSpintestUnderflowCst(SpintestCst $spintestCst)
    {
        return view('process.analisa-moisture.edit-spintest-underflow-cst', [
            'row' => $spintestCst,
            'machineOptions' => self::CST_MACHINES,
        ]);
    }

    public function updateAnalisaSpintestUnderflowCst(Request $request, SpintestCst $spintestCst)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'machine_name' => ['required', Rule::in(self::CST_MACHINES)],
            'oil' => ['nullable', 'numeric'],
            'emulsi' => ['nullable', 'numeric'],
            'air' => ['nullable', 'numeric'],
            'nos' => ['nullable', 'numeric'],
        ]);

        $spintestCst->update([
            'tanggal' => $validated['tanggal'],
            'jam' => $validated['jam'],
            'machine_name' => $validated['machine_name'],
            'oil' => $this->numericOrZero($validated['oil'] ?? null),
            'emulsi' => $this->numericOrZero($validated['emulsi'] ?? null),
            'air' => $this->numericOrZero($validated['air'] ?? null),
            'nos' => $this->numericOrZero($validated['nos'] ?? null),
        ]);

        return redirect()
            ->route('analisa-moisture.spintest-underflow-cst', $this->buildAnalysisListQuery($spintestCst->tanggal?->toDateString(), $spintestCst->machine_name))
            ->with('success', 'Data analisa Spintest Underflow CST berhasil diperbarui.');
    }

    public function destroyAnalisaSpintestUnderflowCst(SpintestCst $spintestCst)
    {
        $tanggal = $spintestCst->tanggal?->toDateString();
        $machineName = $spintestCst->machine_name;
        $spintestCst->delete();

        return redirect()
            ->route('analisa-moisture.spintest-underflow-cst', $this->buildAnalysisListQuery($tanggal, $machineName))
            ->with('success', 'Data analisa Spintest Underflow CST berhasil dihapus.');
    }

    public function exportAnalisaSpintestUnderflowCst(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);
        $selectedMachine = trim((string) $request->input('machine_name', 'all'));

        if ($selectedMachine !== 'all' && !in_array($selectedMachine, self::CST_MACHINES, true)) {
            $selectedMachine = 'all';
        }

        $rows = SpintestCst::query()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($selectedMachine !== 'all', fn($query) => $query->where('machine_name', $selectedMachine))
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->orderBy('machine_name')
            ->get()
            ->map(function (SpintestCst $row): array {
                return [
                    Carbon::parse($row->tanggal)->format('d-m-Y'),
                    $row->jam,
                    $row->machine_name,
                    $row->oil,
                    $row->emulsi,
                    $row->air,
                    $row->nos,
                ];
            })
            ->values();

        return $this->downloadAnalysisExport(
            'Data Analisa Spintest Underflow CST',
            $startDate,
            $endDate,
            $rows,
            ['Tanggal', 'Jam', 'Mesin', 'OIL', 'EMULSI', 'AIR', 'NOS'],
            'Spintest_CST_' . $startDate . '_to_' . $endDate . '.xlsx'
        );
    }

    public function analisaSpintestFeedDecanter(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);
        $selectedMachine = trim((string) $request->input('machine_name', 'all'));

        if ($selectedMachine !== 'all' && !in_array($selectedMachine, self::DECANTER_MACHINES, true)) {
            $selectedMachine = 'all';
        }

        $rows = SpintestFeedDecanter::query()
            ->with('user')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($selectedMachine !== 'all', fn($query) => $query->where('machine_name', $selectedMachine))
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->orderBy('machine_name')
            ->paginate(25)
            ->appends($request->except('page'));

        return view('process.analisa-moisture.spintest-feed-decanter', [
            'rows' => $rows,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'machineOptions' => self::DECANTER_MACHINES,
            'selectedMachine' => $selectedMachine,
        ]);
    }

    public function editAnalisaSpintestFeedDecanter(SpintestFeedDecanter $spintestFeedDecanter)
    {
        return view('process.analisa-moisture.edit-spintest-feed-decanter', [
            'row' => $spintestFeedDecanter,
            'machineOptions' => self::DECANTER_MACHINES,
        ]);
    }

    public function updateAnalisaSpintestFeedDecanter(Request $request, SpintestFeedDecanter $spintestFeedDecanter)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'machine_name' => ['required', Rule::in(self::DECANTER_MACHINES)],
            'oil' => ['nullable', 'numeric'],
            'emulsi' => ['nullable', 'numeric'],
            'air' => ['nullable', 'numeric'],
            'nos' => ['nullable', 'numeric'],
        ]);

        $spintestFeedDecanter->update([
            'tanggal' => $validated['tanggal'],
            'jam' => $validated['jam'],
            'machine_name' => $validated['machine_name'],
            'oil' => $this->numericOrZero($validated['oil'] ?? null),
            'emulsi' => $this->numericOrZero($validated['emulsi'] ?? null),
            'air' => $this->numericOrZero($validated['air'] ?? null),
            'nos' => $this->numericOrZero($validated['nos'] ?? null),
        ]);

        return redirect()
            ->route('analisa-moisture.spintest-feed-decanter', $this->buildAnalysisListQuery($spintestFeedDecanter->tanggal?->toDateString(), $spintestFeedDecanter->machine_name))
            ->with('success', 'Data analisa Spintest Feed Decanter berhasil diperbarui.');
    }

    public function destroyAnalisaSpintestFeedDecanter(SpintestFeedDecanter $spintestFeedDecanter)
    {
        $tanggal = $spintestFeedDecanter->tanggal?->toDateString();
        $machineName = $spintestFeedDecanter->machine_name;
        $spintestFeedDecanter->delete();

        return redirect()
            ->route('analisa-moisture.spintest-feed-decanter', $this->buildAnalysisListQuery($tanggal, $machineName))
            ->with('success', 'Data analisa Spintest Feed Decanter berhasil dihapus.');
    }

    public function exportAnalisaSpintestFeedDecanter(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);
        $selectedMachine = trim((string) $request->input('machine_name', 'all'));

        if ($selectedMachine !== 'all' && !in_array($selectedMachine, self::DECANTER_MACHINES, true)) {
            $selectedMachine = 'all';
        }

        $rows = SpintestFeedDecanter::query()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($selectedMachine !== 'all', fn($query) => $query->where('machine_name', $selectedMachine))
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->orderBy('machine_name')
            ->get()
            ->map(function (SpintestFeedDecanter $row): array {
                return [
                    Carbon::parse($row->tanggal)->format('d-m-Y'),
                    $row->jam,
                    $row->machine_name,
                    $row->oil,
                    $row->emulsi,
                    $row->air,
                    $row->nos,
                ];
            })
            ->values();

        return $this->downloadAnalysisExport(
            'Data Analisa Spintest Feed Decanter',
            $startDate,
            $endDate,
            $rows,
            ['Tanggal', 'Jam', 'Mesin', 'OIL', 'EMULSI', 'AIR', 'NOS'],
            'Spintest_Feed_Decanter_' . $startDate . '_to_' . $endDate . '.xlsx'
        );
    }

    public function analisaSpintestLightPhase(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);
        $selectedMachine = trim((string) $request->input('machine_name', 'all'));

        if ($selectedMachine !== 'all' && !in_array($selectedMachine, self::LIGHT_PHASE_MACHINES, true)) {
            $selectedMachine = 'all';
        }

        $rows = SpintestLightPhase::query()
            ->with('user')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($selectedMachine !== 'all', fn($query) => $query->where('machine_name', $selectedMachine))
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->orderBy('machine_name')
            ->paginate(25)
            ->appends($request->except('page'));

        return view('process.analisa-moisture.spintest-light-phase', [
            'rows' => $rows,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'machineOptions' => self::LIGHT_PHASE_MACHINES,
            'selectedMachine' => $selectedMachine,
        ]);
    }

    public function editAnalisaSpintestLightPhase(SpintestLightPhase $spintestLightPhase)
    {
        return view('process.analisa-moisture.edit-spintest-light-phase', [
            'row' => $spintestLightPhase,
            'machineOptions' => self::LIGHT_PHASE_MACHINES,
        ]);
    }

    public function updateAnalisaSpintestLightPhase(Request $request, SpintestLightPhase $spintestLightPhase)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'machine_name' => ['required', Rule::in(self::LIGHT_PHASE_MACHINES)],
            'oil' => ['nullable', 'numeric'],
            'emulsi' => ['nullable', 'numeric'],
            'air' => ['nullable', 'numeric'],
            'nos' => ['nullable', 'numeric'],
        ]);

        $spintestLightPhase->update([
            'tanggal' => $validated['tanggal'],
            'jam' => $validated['jam'],
            'machine_name' => $validated['machine_name'],
            'oil' => $this->numericOrZero($validated['oil'] ?? null),
            'emulsi' => $this->numericOrZero($validated['emulsi'] ?? null),
            'air' => $this->numericOrZero($validated['air'] ?? null),
            'nos' => $this->numericOrZero($validated['nos'] ?? null),
        ]);

        return redirect()
            ->route('analisa-moisture.spintest-light-phase', $this->buildAnalysisListQuery($spintestLightPhase->tanggal?->toDateString(), $spintestLightPhase->machine_name))
            ->with('success', 'Data analisa Spintest Light Phase berhasil diperbarui.');
    }

    public function destroyAnalisaSpintestLightPhase(SpintestLightPhase $spintestLightPhase)
    {
        $tanggal = $spintestLightPhase->tanggal?->toDateString();
        $machineName = $spintestLightPhase->machine_name;
        $spintestLightPhase->delete();

        return redirect()
            ->route('analisa-moisture.spintest-light-phase', $this->buildAnalysisListQuery($tanggal, $machineName))
            ->with('success', 'Data analisa Spintest Light Phase berhasil dihapus.');
    }

    public function exportAnalisaSpintestLightPhase(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);
        $selectedMachine = trim((string) $request->input('machine_name', 'all'));

        if ($selectedMachine !== 'all' && !in_array($selectedMachine, self::LIGHT_PHASE_MACHINES, true)) {
            $selectedMachine = 'all';
        }

        $rows = SpintestLightPhase::query()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($selectedMachine !== 'all', fn($query) => $query->where('machine_name', $selectedMachine))
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->orderBy('machine_name')
            ->get()
            ->map(function (SpintestLightPhase $row): array {
                return [
                    Carbon::parse($row->tanggal)->format('d-m-Y'),
                    $row->jam,
                    $row->machine_name,
                    $row->oil,
                    $row->emulsi,
                    $row->air,
                    $row->nos,
                ];
            })
            ->values();

        return $this->downloadAnalysisExport(
            'Data Analisa Spintest Light Phase',
            $startDate,
            $endDate,
            $rows,
            ['Tanggal', 'Jam', 'Mesin', 'OIL', 'EMULSI', 'AIR', 'NOS'],
            'Spintest_Light_Phase_' . $startDate . '_to_' . $endDate . '.xlsx'
        );
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ── LAP JANGKOS ───────────────────────────────────────────────────────────
    // ══════════════════════════════════════════════════════════════════════════

    public function lapJangkosInputUsb()
    {
        return view('process.lap-jangkos.input-usb', [
            'rowNumbers' => range(1, 8),
            'shiftOptions' => [1, 2],
        ]);
    }

    public function lapJangkosStoreUsb(Request $request)
    {
        $validated = $request->validate([
            'rows' => ['required', 'array', 'size:8'],
            'rows.*.tanggal' => ['nullable', 'date'],
            'rows.*.jam' => ['nullable', 'date_format:H:i'],
            'rows.*.shift' => ['nullable', Rule::in([1, 2, '1', '2'])],
            'rows.*.diamati_jlh_janjang' => ['nullable', 'numeric', 'min:0'],
            'rows.*.lolos_jlh_janjang' => ['nullable', 'numeric', 'min:0'],
        ]);

        $savedCount = 0;
        $userId = auth()->id();

        DB::transaction(function () use ($validated, $userId, &$savedCount) {
            foreach (range(1, 8) as $index) {
                $row = $validated['rows'][$index] ?? [];

                if (!$this->hasAnyMeaningfulValue($row)) {
                    continue;
                }

                $diamati = $this->numericOrZero($row['diamati_jlh_janjang'] ?? null);
                $lolos = $this->numericOrZero($row['lolos_jlh_janjang'] ?? null);
                $persenUsb = $diamati > 0 ? round(($lolos / $diamati) * 100, 2) : 0;

                AnalisaUsb::create([
                    'user_id' => $userId,
                    'tanggal' => $row['tanggal'] ?? now()->toDateString(),
                    'jam' => $row['jam'] ?? now()->format('H:i'),
                    'shift' => (int) ($row['shift'] ?? 1),
                    'no_rebusan' => $index,
                    'diamati_jlh_janjang' => $diamati,
                    'lolos_jlh_janjang' => $lolos,
                    'persen_usb' => $persenUsb,
                ]);

                $savedCount++;
            }
        });

        if ($savedCount === 0) {
            throw ValidationException::withMessages([
                'rows' => 'Minimal satu form rebusan harus diisi agar data bisa disimpan.',
            ]);
        }

        return redirect()->route('lap-jangkos.data-usb')
            ->with('success', 'Data USB berhasil disimpan.');
    }

    public function lapJangkosDataUsb(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);

        $records = AnalisaUsb::query()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->orderBy('no_rebusan')
            ->orderByDesc('id')
            ->get();

        $dates = $records
            ->pluck('tanggal')
            ->filter()
            ->map(fn($tanggal) => Carbon::parse($tanggal)->toDateString())
            ->unique()
            ->sort()
            ->values()
            ->all();

        $rowNumbers = range(1, 8);
        $matrix = [];

        foreach ($rowNumbers as $rowNumber) {
            $matrix[$rowNumber] = [];
            foreach ($dates as $date) {
                $matrix[$rowNumber][$date] = null;
            }
        }

        foreach ($records as $record) {
            $dateKey = Carbon::parse($record->tanggal)->toDateString();
            $rowNumber = (int) $record->no_rebusan;

            if (!isset($matrix[$rowNumber][$dateKey])) {
                continue;
            }

            if ($matrix[$rowNumber][$dateKey] === null) {
                $matrix[$rowNumber][$dateKey] = (float) $record->persen_usb;
            }
        }

        $averages = [];
        foreach ($dates as $date) {
            $values = collect($rowNumbers)
                ->map(fn($rowNumber) => $matrix[$rowNumber][$date] ?? null)
                ->filter(fn($value) => $value !== null)
                ->values();

            $averages[$date] = $values->isEmpty()
                ? null
                : round($values->avg(), 2);
        }

        return view('process.lap-jangkos.data-usb', [
            'rowNumbers' => $rowNumbers,
            'dates' => $dates,
            'matrix' => $matrix,
            'averages' => $averages,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function oilLossFossInput()
    {
        return view('process.oil-loss-foss.input', [
            'machineGroups' => $this->oilFossMachineGroups(),
            'operator' => $this->resolveOilFossOperator(),
            'shiftOptions' => [1, 2],
        ]);
    }

    public function oilLossFossStore(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'waktu' => ['required', 'date_format:H:i'],
            'operator' => ['required', Rule::in(['YBS', 'SUN'])],
            'shift' => ['required', Rule::in([1, 2, '1', '2'])],
            'rows' => ['required', 'array'],
            'rows.*.moist' => ['nullable', 'numeric', 'min:0'],
            'rows.*.olwb' => ['nullable', 'numeric', 'min:0'],
        ]);

        $definitions = collect($this->oilFossMachineDefinitions())->keyBy('key');
        $savedCount = 0;
        $userId = auth()->id();

        DB::transaction(function () use ($validated, $definitions, $userId, &$savedCount) {
            foreach ($validated['rows'] as $rowKey => $row) {
                if (!$definitions->has($rowKey)) {
                    continue;
                }

                $moistRaw = $row['moist'] ?? null;
                $olwbRaw = $row['olwb'] ?? null;

                $moistFilled = $moistRaw !== null && $moistRaw !== '';
                $olwbFilled = $olwbRaw !== null && $olwbRaw !== '';

                if (!$moistFilled && !$olwbFilled) {
                    continue;
                }

                $moist = $this->nullableNumeric($moistRaw);
                $olwb = $this->nullableNumeric($olwbRaw);

                $hasNonZeroValue = ($moistFilled && (float) $moist !== 0.0)
                    || ($olwbFilled && (float) $olwb !== 0.0);

                if (!$hasNonZeroValue && !($moistFilled && $olwbFilled)) {
                    continue;
                }

                $definition = $definitions->get($rowKey);

                OilFoss::create([
                    'user_id' => $userId,
                    'tanggal' => $validated['tanggal'],
                    'waktu' => $validated['waktu'],
                    'operator' => strtoupper((string) $validated['operator']),
                    'shift' => (int) $validated['shift'],
                    'machine_group' => $definition['group'],
                    'machine_name' => $definition['label'],
                    'moist' => $moist,
                    'olwb' => $olwb,
                ]);

                $savedCount++;
            }
        });

        if ($savedCount === 0) {
            throw ValidationException::withMessages([
                'rows' => 'Minimal satu form Oil Loss Foss harus terisi agar data dapat disimpan.',
            ]);
        }

        return redirect()->route('oil-loss-foss.data')
            ->with('success', 'Data Oil Loss Foss berhasil disimpan.');
    }

    public function oilLossFossData(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);

        $operator = strtoupper(trim((string) $request->input('operator', 'ALL')));
        if (!in_array($operator, ['ALL', 'YBS', 'SUN'], true)) {
            $operator = 'ALL';
        }

        $shift = (string) $request->input('shift', 'all');
        if (!in_array($shift, ['all', '1', '2'], true)) {
            $shift = 'all';
        }

        $machineName = trim((string) $request->input('machine_name', 'all'));
        $machineOptions = collect($this->oilFossMachineDefinitions())
            ->pluck('label')
            ->unique()
            ->sort()
            ->values()
            ->all();

        if ($machineName !== 'all' && !in_array($machineName, $machineOptions, true)) {
            $machineName = 'all';
        }

        $rows = OilFoss::query()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($operator !== 'ALL', fn($query) => $query->where('operator', $operator))
            ->when($shift !== 'all', fn($query) => $query->where('shift', (int) $shift))
            ->when($machineName !== 'all', fn($query) => $query->where('machine_name', $machineName))
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->orderBy('machine_name')
            ->paginate(25)
            ->appends($request->except('page'));

        return view('process.oil-loss-foss.data', [
            'rows' => $rows,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'operator' => $operator,
            'shift' => $shift,
            'machineName' => $machineName,
            'machineOptions' => $machineOptions,
        ]);
    }

    public function oilLossFossRekap(Request $request)
    {
        [$startDate, $endDate] = $this->resolveAnalysisDateRange($request);

        $operator = strtoupper(trim((string) $request->input('operator', 'ALL')));
        if (!in_array($operator, ['ALL', 'YBS', 'SUN'], true)) {
            $operator = 'ALL';
        }

        $shift = (string) $request->input('shift', 'all');
        if (!in_array($shift, ['all', '1', '2'], true)) {
            $shift = 'all';
        }

        $records = OilFoss::query()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($operator !== 'ALL', fn($query) => $query->where('operator', $operator))
            ->when($shift !== 'all', fn($query) => $query->where('shift', (int) $shift))
            ->select(
                'tanggal',
                'machine_name',
                DB::raw('AVG(moist) as avg_moist'),
                DB::raw('AVG(olwb) as avg_olwb'),
                DB::raw('COUNT(*) as total_data')
            )
            ->groupBy('tanggal', 'machine_name')
            ->orderBy('tanggal')
            ->orderBy('machine_name')
            ->get();

        $dates = $records
            ->pluck('tanggal')
            ->filter()
            ->map(fn($tanggal) => Carbon::parse($tanggal)->toDateString())
            ->unique()
            ->sort()
            ->values()
            ->all();

        $machineNames = collect($this->oilFossMachineDefinitions())
            ->pluck('label')
            ->unique()
            ->sort()
            ->values()
            ->all();

        $rekap = [];
        foreach ($machineNames as $machineName) {
            $rekap[$machineName] = [];
            foreach ($dates as $date) {
                $rekap[$machineName][$date] = null;
            }
        }

        foreach ($records as $record) {
            $dateKey = Carbon::parse($record->tanggal)->toDateString();
            $machineName = (string) $record->machine_name;

            if (!isset($rekap[$machineName][$dateKey])) {
                continue;
            }

            $rekap[$machineName][$dateKey] = [
                'avg_moist' => round((float) $record->avg_moist, 2),
                'avg_olwb' => round((float) $record->avg_olwb, 2),
                'total_data' => (int) $record->total_data,
            ];
        }

        return view('process.oil-loss-foss.rekap', [
            'dates' => $dates,
            'machineNames' => $machineNames,
            'rekap' => $rekap,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'operator' => $operator,
            'shift' => $shift,
        ]);
    }

    private function numericOrZero(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (float) $value;
    }

    private function stringOrDefault(mixed $value, string $default): string
    {
        $stringValue = trim((string) ($value ?? ''));

        return $stringValue !== '' ? $stringValue : $default;
    }

    private function nullableNumeric(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }

    private function oilFossMachineGroups(): array
    {
        $groups = [];

        foreach ($this->oilFossMachineDefinitions() as $definition) {
            $group = $definition['group'];
            if (!isset($groups[$group])) {
                $groups[$group] = [];
            }

            $groups[$group][] = $definition;
        }

        return $groups;
    }

    private function oilFossMachineDefinitions(): array
    {
        return [
            ['key' => 'cot_in', 'group' => 'COT', 'label' => 'COT IN'],
            ['key' => 'cot_2', 'group' => 'COT', 'label' => 'COT 2'],
            ['key' => 'cst', 'group' => 'CST', 'label' => 'CST'],
            ['key' => 'fd_1', 'group' => 'FD', 'label' => 'FD1'],
            ['key' => 'fd_2', 'group' => 'FD', 'label' => 'FD2'],
            ['key' => 'fd_3', 'group' => 'FD', 'label' => 'FD3'],
            ['key' => 'fd_4', 'group' => 'FD', 'label' => 'FD4'],
            ['key' => 'hp_1', 'group' => 'HP', 'label' => 'HP1'],
            ['key' => 'hp_2', 'group' => 'HP', 'label' => 'HP2'],
            ['key' => 'hp_3', 'group' => 'HP', 'label' => 'HP3'],
            ['key' => 'hp_4', 'group' => 'HP', 'label' => 'HP4'],
            ['key' => 'sd_1', 'group' => 'SD', 'label' => 'SD1'],
            ['key' => 'sd_2', 'group' => 'SD', 'label' => 'SD2'],
            ['key' => 'sd_3', 'group' => 'SD', 'label' => 'SD3'],
            ['key' => 'sd_4', 'group' => 'SD', 'label' => 'SD4'],
            ['key' => 'hpl_1', 'group' => 'HPL', 'label' => 'HPL1'],
            ['key' => 'hpl_2', 'group' => 'HPL', 'label' => 'HPL2'],
            ['key' => 'hpl_3', 'group' => 'HPL', 'label' => 'HPL3'],
            ['key' => 'fe', 'group' => 'FE', 'label' => 'FE'],
            ['key' => 'fbp_1', 'group' => 'FBP', 'label' => 'FBP1'],
            ['key' => 'fbp_2', 'group' => 'FBP', 'label' => 'FBP2'],
            ['key' => 'fbp_3', 'group' => 'FBP', 'label' => 'FBP3'],
            ['key' => 'fbp_4', 'group' => 'FBP', 'label' => 'FBP4'],
            ['key' => 'fbp_5', 'group' => 'FBP', 'label' => 'FBP5'],
            ['key' => 'fp_1', 'group' => 'FP', 'label' => 'FP1'],
            ['key' => 'fp_2', 'group' => 'FP', 'label' => 'FP2'],
            ['key' => 'fp_3', 'group' => 'FP', 'label' => 'FP3'],
            ['key' => 'fp_4', 'group' => 'FP', 'label' => 'FP4'],
            ['key' => 'fp_5', 'group' => 'FP', 'label' => 'FP5'],
            ['key' => 'fp_6', 'group' => 'FP', 'label' => 'FP6'],
            ['key' => 'fp_7', 'group' => 'FP', 'label' => 'FP7'],
            ['key' => 'fp_8', 'group' => 'FP', 'label' => 'FP8'],
            ['key' => 'fp_9', 'group' => 'FP', 'label' => 'FP9'],
        ];
    }

    private function resolveOilFossOperator(): string
    {
        $office = strtoupper(trim((string) (auth()->user()->office ?? '')));

        if ($office === 'SUN') {
            return 'SUN';
        }

        return 'YBS';
    }

    private function hasAnyMeaningfulValue(array $row, array $ignoredKeys = []): bool
    {
        foreach ($row as $key => $value) {
            if (in_array((string) $key, $ignoredKeys, true)) {
                continue;
            }

            if (is_string($value) && trim($value) !== '') {
                return true;
            }

            if (is_numeric($value) && (float) $value !== 0.0) {
                return true;
            }
        }

        return false;
    }

    private function buildAnalysisListQuery(?string $date = null, ?string $machineName = null): array
    {
        $targetDate = $date ?: now()->toDateString();

        $query = [
            'start_date' => $targetDate,
            'end_date' => $targetDate,
        ];

        if ($machineName !== null && $machineName !== '') {
            $query['machine_name'] = $machineName;
        }

        return $query;
    }

    private function resolveAnalysisDateRange(Request $request): array
    {
        $startDate = Carbon::parse($request->input('start_date', now()->startOfMonth()->toDateString()))->toDateString();
        $endDate = Carbon::parse($request->input('end_date', now()->toDateString()))->toDateString();

        if ($startDate > $endDate) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        return [$startDate, $endDate];
    }

    private function downloadAnalysisExport(string $title, string $startDate, string $endDate, array|Collection $rows, array $headings, string $filename)
    {
        $subtitle = 'Periode: ' . Carbon::parse($startDate)->format('d-m-Y') . ' s/d ' . Carbon::parse($endDate)->format('d-m-Y');

        return Excel::download(
            new ProcessAnalysisExport(collect($rows), $headings, $title, $subtitle),
            $filename
        );
    }
}


