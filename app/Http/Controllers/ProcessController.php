<?php

namespace App\Http\Controllers;

use App\Exports\PerformanceSampelBoyExport;
use App\Models\KernelCalculation;
use App\Models\KernelDirtMoistCalculation;
use App\Models\KernelMesin;
use App\Models\KernelProsses;
use App\Models\KernelQwt;
use App\Models\KernelRippleMill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class ProcessController extends Controller
{
    private const TEAM_OPTIONS = ['Tim 1', 'Tim 2'];

    private const MACHINE_GROUPS = [
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

    private const PERFORMANCE_GROUP_INTERVAL_MINUTES = [
        'fibre_cyclone' => 120,
        'ltds' => 120,
        'claybath_wet_shell' => 60,
        'inlet_kernel_silo' => 60,
        'outlet_kernel_silo' => 60,
        'press' => 240,
        'eficiency' => 120,
    ];

    private const PERFORMANCE_CODE_GROUPS = [
        'fibre_cyclone' => ['FC1', 'FC2'],
        'ltds' => ['L1', 'L2', 'L3', 'L4'],
        'claybath_wet_shell' => ['CWS', 'CWS1', 'CWS2', 'CWS3'],
        'inlet_kernel_silo' => ['IN1', 'IN2', 'IN3', 'IN4', 'IN5'],
        'outlet_kernel_silo' => ['OUT1', 'OUT2', 'OUT3', 'OUT4', 'OUT5'],
        'press' => ['P1', 'P2', 'P3', 'P4', 'P5', 'P6', 'P7', 'P8', 'P9'],
        'eficiency' => ['R1', 'R2', 'R3', 'R4', 'R5', 'R6', 'R7'],
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
    ];

    public function index()
    {
        $teamMembers = $this->getTeamMembersByOffice();

        $records = KernelProsses::query()
            ->withCount('mesin')
            ->orderByDesc('process_date')
            ->orderByDesc('id')
            ->limit(100)
            ->get()
            ->map(function (KernelProsses $record): array {
                return [
                    'id' => $record->id,
                    'process_date' => optional($record->process_date)->format('d/m/Y'),
                    'input_team' => (string) ($record->input_team ?? '-'),
                    'mesin_count' => $record->mesin_count,
                ];
            });

        return view('process.index', [
            'teamMembers' => $teamMembers,
            'records' => $records,
            'machineGroups' => self::MACHINE_GROUPS,
        ]);
    }

    public function store(Request $request)
    {
        $teamMemberRules = $this->buildTeamMemberValidationRules();
        $inputTeam = (string) $request->input('input_team');
        if (!in_array($inputTeam, self::TEAM_OPTIONS, true)) {
            return back()->withInput()->withErrors([
                'input_team' => 'Tim input tidak valid.',
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
        ]);

        $machinePayload = $this->extractMachinePayload($request, $inputTeam);

        $office = auth()->user()->office;

        $alreadyExists = KernelProsses::query()
            ->whereDate('process_date', $validated['process_date'])
            ->where('office', $office)
            ->where('input_team', $inputTeam)
            ->exists();

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
            'team_2_start_time' => $isTeamOneInput ? '00:00' : ($validated['team_2_start_time'] ?? '00:00'),
            'team_2_end_time' => $isTeamOneInput ? '00:00' : ($validated['team_2_end_time'] ?? '00:00'),
            'team_2_start_downtime' => $isTeamOneInput ? null : ($validated['team_2_start_downtime'] ?? null),
            'team_2_end_downtime' => $isTeamOneInput ? null : ($validated['team_2_end_downtime'] ?? null),
            'team_2_downtime' => null,
            'team_2_members' => $isTeamOneInput ? [] : ($validated['team_2_members'] ?? []),
        ]);

        if (!empty($machinePayload)) {
            $process->mesin()->createMany($machinePayload);
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
        $selectedDate = (string) $request->input('date', now()->format('Y-m-d'));
        $officeOptions = $this->getAvailableOfficesForPerformance();
        $defaultOffice = (string) (auth()->user()->office ?? '');
        $selectedOffice = (string) $request->input('office', $defaultOffice !== '' ? $defaultOffice : 'all');

        if ($selectedOffice !== 'all' && !in_array($selectedOffice, $officeOptions, true)) {
            $selectedOffice = $defaultOffice !== '' ? $defaultOffice : 'all';
        }

        $records = KernelProsses::query()
            ->with('mesin')
            ->whereDate('process_date', $selectedDate)
            ->when($selectedOffice !== 'all', fn($query) => $query->where('office', $selectedOffice))
            ->orderBy('process_date')
            ->orderBy('id')
            ->get();

        $rows = [];

        foreach ($records as $record) {
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

        return view('process.performance-sampel-boy', [
            'selectedDate' => $selectedDate,
            'selectedOffice' => $selectedOffice,
            'officeOptions' => $officeOptions,
            'detailCodeHeaders' => self::PERFORMANCE_DETAIL_CODES,
            'rows' => $rows,
        ]);
    }

    public function exportPerformanceSampelBoy(Request $request)
    {
        $selectedDate = (string) $request->input('date', now()->format('Y-m-d'));
        $officeOptions = $this->getAvailableOfficesForPerformance();
        $defaultOffice = (string) (auth()->user()->office ?? '');
        $selectedOffice = (string) $request->input('office', $defaultOffice !== '' ? $defaultOffice : 'all');

        if ($selectedOffice !== 'all' && !in_array($selectedOffice, $officeOptions, true)) {
            $selectedOffice = $defaultOffice !== '' ? $defaultOffice : 'all';
        }

        $records = KernelProsses::query()
            ->with('mesin')
            ->whereDate('process_date', $selectedDate)
            ->when($selectedOffice !== 'all', fn($query) => $query->where('office', $selectedOffice))
            ->orderBy('process_date')
            ->orderBy('id')
            ->get();

        $rows = [];
        foreach ($records as $record) {
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

        $filename = 'performance-sampel-boy-'
            . Carbon::parse($selectedDate)->format('Ymd')
            . '-' . ($selectedOffice === 'all' ? 'all-office' : strtolower($selectedOffice))
            . '.xlsx';

        return Excel::download(
            new PerformanceSampelBoyExport($rows, self::PERFORMANCE_DETAIL_CODES, $selectedDate, $selectedOffice),
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

        $groupedMachinesByTeam = collect($teamsToShow)
            ->mapWithKeys(function (string $teamName) use ($allMachines): array {
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
                    ->map(function ($machines) use ($teamSparesByMachineName) {
                        return $machines->map(function (KernelMesin $machine) use ($teamSparesByMachineName): array {
                            $totalMinutes = $this->resolveMachineTotalMinutes($machine);
                            $intervalMinutes = $this->resolveMachineIntervalMinutes((string) $machine->machine_name);

                            return [
                                'main' => $machine,
                                'spares' => $teamSparesByMachineName->get($machine->machine_name, collect()),
                                'total_minutes' => $totalMinutes,
                                'interval_minutes' => $intervalMinutes,
                                'expected_samples' => $intervalMinutes > 0 ? intdiv($totalMinutes, $intervalMinutes) : 0,
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
        $kernelProsses->load('mesin');

        $visibleTeam = in_array((string) $kernelProsses->input_team, self::TEAM_OPTIONS, true)
            ? (string) $kernelProsses->input_team
            : null;

        $selectedMainMachines = [];
        $spareRowsByTeam = [
            'Tim 1' => [],
            'Tim 2' => [],
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
        }

        $machineOptions = collect(self::MACHINE_GROUPS)->flatten()->values()->all();

        return view('process.edit-machines', [
            'record' => $kernelProsses,
            'machineGroups' => self::MACHINE_GROUPS,
            'selectedMainMachines' => $selectedMainMachines,
            'spareRowsByTeam' => $spareRowsByTeam,
            'machineOptions' => $machineOptions,
            'visibleTeam' => $visibleTeam,
        ]);
    }

    public function updateMachines(Request $request, KernelProsses $kernelProsses)
    {
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
        ]);

        $machinePayload = $this->extractMachinePayload($request);

        $kernelProsses->mesin()->delete();
        if (!empty($machinePayload)) {
            $kernelProsses->mesin()->createMany($machinePayload);
        }

        return redirect()
            ->route('process.index')
            ->with('success', 'Data mesin berhasil diperbarui.');
    }

    public function destroyMachines(KernelProsses $kernelProsses)
    {
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
            'team_1_start_downtime' => $validated['team_1_start_downtime'],
            'team_1_end_downtime' => $validated['team_1_end_downtime'],
            'team_1_members' => $validated['team_1_members'] ?? [],
            'team_2_start_time' => $validated['team_2_start_time'],
            'team_2_end_time' => $validated['team_2_end_time'],
            'team_2_start_downtime' => $validated['team_2_start_downtime'],
            'team_2_end_downtime' => $validated['team_2_end_downtime'],
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

        $processMinutes = $this->minutesBetween($processStart, $processEnd);
        $downtimeMinutes = $this->minutesBetween($downtimeStart, $downtimeEnd);
        $processHours = intdiv($processMinutes, 60);
        $downtimeHours = intdiv($downtimeMinutes, 60);
        $productionHours = max($processHours - $downtimeHours, 0);
        $spareHours = $this->calculateTeamSpareHours($record, $teamName);
        $totalProductionHours = $productionHours + $spareHours;

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

        $processMinutes = $this->minutesBetween($processStart, $processEnd);
        $downtimeMinutes = $this->minutesBetween($downtimeStart, $downtimeEnd);
        $processHours = intdiv($processMinutes, 60);
        $downtimeHours = intdiv($downtimeMinutes, 60);
        $productionHours = max($processHours - $downtimeHours, 0);
        $spareMinutes = (int) $teamMachines
            ->where('is_spare_input', false)
            ->sum(fn($machine): int => $this->hoursToMinutes((float) ($machine->is_spare ?? 0)));
        $spareHours = intdiv($spareMinutes, 60);

        $teamDeductionHours = $isTeamOne ? 2 : 1;
        $effectiveHours = max(($productionHours + $spareHours) - $teamDeductionHours, 0);

        $expectedByGroup = $this->buildExpectedSamplesByGroup($teamMachines);
        $machineWindowsByCode = $this->buildMachineWindowsByCode($teamMachines);

        $actualByGroup = $this->buildActualSamplesByGroup(
            (string) optional($record->process_date)->format('Y-m-d'),
            $selectedOffice === 'all' ? '' : $selectedOffice,
            $machineWindowsByCode
        );

        $expectedByCode = $this->buildExpectedSamplesByCode($teamMachines);
        $actualByCode = $this->buildActualSamplesByCode(
            (string) optional($record->process_date)->format('Y-m-d'),
            $selectedOffice === 'all' ? '' : $selectedOffice,
            $machineWindowsByCode
        );

        $detailValues = [];
        $detailActualTotal = 0;
        $detailExpectedTotal = 0;
        foreach (self::PERFORMANCE_DETAIL_CODES as $detailCode) {
            $code = $detailCode['code'];
            $actual = (int) ($actualByCode[$code] ?? 0);
            $expected = (int) ($expectedByCode[$code] ?? 0);
            $detailValues[$code] = $actual . '/' . $expected;
            $detailActualTotal += $actual;
            $detailExpectedTotal += $expected;
        }

        $detailPerfTotal = $detailExpectedTotal > 0
            ? number_format(($detailActualTotal / $detailExpectedTotal) * 100, 2) . '%'
            : '-';

        $perfActualTotal = 0;
        $perfExpectedTotal = 0;
        foreach (array_keys(self::PERFORMANCE_GROUP_INTERVAL_MINUTES) as $groupKey) {
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
            'perf_total' => $perfTotal,
            'detail_values' => $detailValues,
            'detail_perf_total' => $detailPerfTotal,
        ];
    }

    private function formatActualExpected(array $actualByGroup, array $expectedByGroup, string $groupKey): string
    {
        $actual = (int) ($actualByGroup[$groupKey] ?? 0);
        $expected = (int) ($expectedByGroup[$groupKey] ?? 0);

        return $actual . '/' . $expected;
    }

    private function buildExpectedSamplesByGroup(Collection $machines): array
    {
        $expected = [];
        foreach (array_keys(self::PERFORMANCE_GROUP_INTERVAL_MINUTES) as $groupKey) {
            $expected[$groupKey] = 0;
        }

        // Use main machine total production hours (already includes spare hours), then divide by interval.
        $effectiveByGroup = $machines
            ->map(function ($machine): ?array {
                if ((bool) ($machine->is_spare_input ?? false)) {
                    return null;
                }

                $machineName = strtoupper(trim((string) $machine->machine_name));
                $groupKey = $this->resolvePerformanceGroupByMachineName($machineName);

                if ($groupKey === null) {
                    return null;
                }

                $durationMinutes = $this->hoursToMinutes((float) ($machine->total_produsction_hours ?? 0));

                if ($durationMinutes <= 0) {
                    $durationMinutes = $this->minutesBetween(
                        substr((string) $machine->production_start_time, 0, 5),
                        substr((string) $machine->production_end_time, 0, 5)
                    );
                }

                if ($durationMinutes <= 0) {
                    return null;
                }

                return [
                    'group' => $groupKey,
                    'minutes' => $durationMinutes,
                ];
            })
            ->filter()
            ->groupBy(fn(array $row): string => $row['group'])
            ->map(fn(Collection $rows): int => (int) $rows->sum('minutes'));

        foreach (array_keys(self::PERFORMANCE_GROUP_INTERVAL_MINUTES) as $groupKey) {
            $intervalMinutes = (int) (self::PERFORMANCE_GROUP_INTERVAL_MINUTES[$groupKey] ?? 0);
            if ($intervalMinutes <= 0) {
                continue;
            }

            $totalMinutes = (int) ($effectiveByGroup[$groupKey] ?? 0);
            $expected[$groupKey] = intdiv($totalMinutes, $intervalMinutes);
        }

        return $expected;
    }

    private function calculateTeamSpareHours(KernelProsses $record, string $teamName): int
    {
        $spareMinutes = $record->mesin()
            ->where('team_name', $teamName)
            ->where('is_spare_input', false)
            ->get()
            ->sum(fn(KernelMesin $machine): int => $this->hoursToMinutes((float) ($machine->is_spare ?? 0)));

        return intdiv((int) $spareMinutes, 60);
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

        return null;
    }

    private function buildActualSamplesByGroup(string $date, string $office, array $machineWindowsByCode): array
    {
        $actual = [];
        foreach (array_keys(self::PERFORMANCE_GROUP_INTERVAL_MINUTES) as $groupKey) {
            $actual[$groupKey] = 0;
        }

        $rows = collect()
            ->concat($this->fetchTimedCodeRows(KernelCalculation::query(), $date, $office))
            ->concat($this->fetchTimedCodeRows(KernelDirtMoistCalculation::query(), $date, $office))
            ->concat($this->fetchTimedCodeRows(KernelQwt::query(), $date, $office))
            ->concat($this->fetchTimedCodeRows(KernelRippleMill::query(), $date, $office));

        foreach ($rows as $row) {
            $code = $this->normalizeCode((string) ($row['code'] ?? ''));
            $minutes = $this->timeToMinutes((string) ($row['time'] ?? ''));
            $isPengulangan = (bool) ($row['pengulangan'] ?? false);

            if ($isPengulangan) {
                continue;
            }

            if ($code === '' || $minutes === null) {
                continue;
            }

            $windows = $machineWindowsByCode[$code] ?? [];
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
                // Fallback: if machine code exists for this team on the same date,
                // still count the input to tolerate operational time-entry shifts.
                $matched = true;
            }

            $groupKey = $this->resolvePerformanceGroupByCode($code);
            if ($groupKey === null) {
                continue;
            }

            $actual[$groupKey]++;
        }

        return $actual;
    }

    private function buildExpectedSamplesByCode(Collection $machines): array
    {
        $expected = [];
        foreach (self::PERFORMANCE_DETAIL_CODES as $detailCode) {
            $expected[$detailCode['code']] = 0;
        }

        $minutesByCode = [];

        $machinesByName = $machines
            ->groupBy(fn($machine) => strtoupper(trim((string) $machine->machine_name)));

        foreach ($machinesByName as $machineName => $rows) {
            $mainRows = $rows->where('is_spare_input', false);

            $mainMinutes = (int) $mainRows->sum(function ($machine) {
                $durationMinutes = $this->hoursToMinutes((float) ($machine->total_produsction_hours ?? 0));

                if ($durationMinutes <= 0) {
                    $durationMinutes = $this->minutesBetween(
                        substr((string) $machine->production_start_time, 0, 5),
                        substr((string) $machine->production_end_time, 0, 5)
                    );
                }

                return max($durationMinutes, 0);
            });

            $totalMinutes = $mainMinutes;
            if ($totalMinutes <= 0) {
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
                $minutesByCode[$detailCode] = (int) (($minutesByCode[$detailCode] ?? 0) + $totalMinutes);
            }
        }

        foreach ($minutesByCode as $detailCode => $totalMinutes) {
            $intervalMinutes = (int) ($this->resolveCodeIntervalMinutes((string) $detailCode) ?? 0);
            if ($intervalMinutes <= 0) {
                continue;
            }

            $expected[$detailCode] = intdiv((int) $totalMinutes, $intervalMinutes);
        }

        return $expected;
    }

    private function buildActualSamplesByCode(string $date, string $office, array $machineWindowsByCode): array
    {
        $actual = [];
        foreach (self::PERFORMANCE_DETAIL_CODES as $detailCode) {
            $actual[$detailCode['code']] = 0;
        }

        $rows = collect()
            ->concat($this->fetchTimedCodeRows(KernelCalculation::query(), $date, $office))
            ->concat($this->fetchTimedCodeRows(KernelDirtMoistCalculation::query(), $date, $office))
            ->concat($this->fetchTimedCodeRows(KernelQwt::query(), $date, $office))
            ->concat($this->fetchTimedCodeRows(KernelRippleMill::query(), $date, $office));

        foreach ($rows as $row) {
            $isPengulangan = (bool) ($row['pengulangan'] ?? false);
            if ($isPengulangan) {
                continue;
            }

            $rawCode = $this->normalizeCode((string) ($row['code'] ?? ''));
            $normalizedCode = $this->normalizeDetailCode($rawCode);
            $minutes = $this->timeToMinutes((string) ($row['time'] ?? ''));

            if ($normalizedCode === null || $minutes === null) {
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
                $matched = true;
            }

            $actual[$normalizedCode]++;
        }

        return $actual;
    }

    private function fetchTimedCodeRows($query, string $date, string $office): Collection
    {
        $rows = $query
            ->where(function ($builder) use ($date) {
                $builder->whereDate('rounded_time', $date)
                    ->orWhereDate('created_at', $date);
            })
            ->when($office !== '', fn($builder) => $builder->where('office', $office))
            ->get(['kode', 'rounded_time', 'created_at', 'pengulangan']);

        return $rows->map(function ($row): array {
            $time = $this->extractTimeFromDateTimeValue($row->rounded_time);

            if ($time === '') {
                $time = $this->extractTimeFromDateTimeValue($row->created_at);
            }

            return [
                'code' => strtoupper(trim((string) $row->kode)),
                'time' => $time,
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
        if (preg_match('/^FIBRE CYCLONE\s+(\d+)$/', $machineName, $match)) {
            return ['FC' . $match[1]];
        }

        if (preg_match('/^LTDS\s+(\d+)$/', $machineName, $match)) {
            return ['L' . $match[1]];
        }

        if (preg_match('/^CLAYBATH WET SHELL\s+(\d+)$/', $machineName, $match)) {
            $index = $match[1];
            return $index === '1' ? ['CWS', 'CWS1'] : ['CWS' . $index];
        }

        if (preg_match('/^INLET KERNEL SILO\s+(\d+)$/', $machineName, $match)) {
            return ['IN' . $match[1]];
        }

        if (preg_match('/^OUTLET KERNEL SILO\s+(\d+)\s+TO BUNKER$/', $machineName, $match)) {
            return ['OUT' . $match[1]];
        }

        if (preg_match('/^PRESS\s+(\d+)$/', $machineName, $match)) {
            return ['P' . $match[1]];
        }

        if (preg_match('/^RIPPLE MILL NO\.\s*(\d+)$/', $machineName, $match)) {
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

    private function resolveCodeIntervalMinutes(string $code): ?int
    {
        $groupKey = $this->resolvePerformanceGroupByCode($code);
        if ($groupKey === null) {
            return null;
        }

        return (int) (self::PERFORMANCE_GROUP_INTERVAL_MINUTES[$groupKey] ?? 0);
    }

    private function resolveMachineIntervalMinutes(string $machineName): int
    {
        $groupKey = $this->resolvePerformanceGroupByMachineName(strtoupper(trim($machineName)));
        if ($groupKey === null) {
            return 0;
        }

        return (int) (self::PERFORMANCE_GROUP_INTERVAL_MINUTES[$groupKey] ?? 0);
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
        if (!$this->isValidTime($time)) {
            return null;
        }

        [$hour, $minute] = array_map('intval', explode(':', substr($time, 0, 5)));

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

        [$startHour, $startMinute] = array_map('intval', explode(':', substr($start, 0, 5)));
        [$endHour, $endMinute] = array_map('intval', explode(':', substr($end, 0, 5)));

        $startTotal = ($startHour * 60) + $startMinute;
        $endTotal = ($endHour * 60) + $endMinute;

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

    private function extractMachinePayload(Request $request, ?string $inputTeam = null): array
    {
        $allowedByGroup = collect(self::MACHINE_GROUPS)
            ->map(fn(array $machines): array => array_values($machines));

        $machineToGroup = collect(self::MACHINE_GROUPS)
            ->flatMap(function (array $machines, string $group): array {
                $map = [];
                foreach ($machines as $machine) {
                    $map[$machine] = $group;
                }

                return $map;
            });

        $entries = [];
        $errors = [];

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

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        $spareMinutesByMachine = collect($entries)
            ->where('is_spare_input', true)
            ->groupBy('machine_name')
            ->map(fn($rows): float => round((float) $rows->sum('row_hours'), 2));

        return collect($entries)
            ->map(function (array $entry) use ($spareMinutesByMachine): array {
                $rowHours = (float) $entry['row_hours'];
                $spareHours = 0.0;
                $totalHours = $rowHours;

                if (!$entry['is_spare_input']) {
                    $spareHours = (float) $spareMinutesByMachine->get($entry['machine_name'], 0);
                    $totalHours += $spareHours;
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

    private function buildTeamMemberValidationRules(): array
    {
        $members = $this->getTeamMembersByOffice();
        $rules = ['string'];

        if (!empty($members)) {
            $rules[] = Rule::in($members);
        }

        return $rules;
    }
}
