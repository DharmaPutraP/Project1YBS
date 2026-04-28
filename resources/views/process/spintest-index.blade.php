<x-layouts.app title="Informasi Proses Mesin Spintest">
    @php
        $canManageTeamMeta = (bool) ($canManageTeamMeta ?? false);
        $canManageMachineData = (bool) ($canManageMachineData ?? false);
        $machineGroups = $machineGroups ?? [];
        $oldSpareRows = old('spare_machines', []);
        $oldOtherConditions = old('other_conditions', []);
        $oldMachines = old('machines', []);
        $spareRowsTeam1 = collect($oldSpareRows)->filter(fn ($row) => ($row['team_name'] ?? 'Tim 1') === 'Tim 1')->all();
        $spareRowsTeam2 = collect($oldSpareRows)->filter(fn ($row) => ($row['team_name'] ?? '') === 'Tim 2')->all();
        $otherConditionsTeam1 = collect($oldOtherConditions)->filter(fn ($row) => ($row['team_name'] ?? 'Tim 1') === 'Tim 1')->all();
        $otherConditionsTeam2 = collect($oldOtherConditions)->filter(fn ($row) => ($row['team_name'] ?? '') === 'Tim 2')->all();

        if (empty($spareRowsTeam1)) {
            $spareRowsTeam1 = [['team_name' => 'Tim 1', 'machine_name' => '', 'start_time' => '', 'end_time' => '']];
        }
        if (empty($spareRowsTeam2)) {
            $spareRowsTeam2 = [['team_name' => 'Tim 2', 'machine_name' => '', 'start_time' => '', 'end_time' => '']];
        }
        if (empty($otherConditionsTeam1)) {
            $otherConditionsTeam1 = [['team_name' => 'Tim 1', 'reason' => '', 'start_time' => '', 'end_time' => '']];
        }
        if (empty($otherConditionsTeam2)) {
            $otherConditionsTeam2 = [['team_name' => 'Tim 2', 'reason' => '', 'start_time' => '', 'end_time' => '']];
        }

        $machineOptions = collect($machineGroups)->flatten()->unique()->values()->all();
        $teamOptions = ['Tim 1', 'Tim 2'];
    @endphp

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Informasi Proses Mesin Spintest</h1>
        <p class="mt-2 text-sm text-gray-600">Form monitoring proses spintest USB dan Oil Foss per tanggal untuk Tim 1 dan Tim 2.</p>
    </div>

    <x-ui.card title="Form Informasi Proses Mesin Spintest">
        <form action="{{ route('process.spintest.store') }}" method="POST" class="space-y-6" id="spintest-form">
            @csrf
            <input type="hidden" name="input_team" id="input_team_field" value="">

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="process_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="process_date" name="process_date"
                        value="{{ old('process_date', now()->format('Y-m-d')) }}"
                        class="w-full md:w-72 px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 @error('process_date') border-red-400 bg-red-50 @enderror">
                    @error('process_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="rounded-xl border border-blue-200 bg-blue-50 p-5 space-y-4" id="team-1-section">
                    <h2 class="text-lg font-semibold text-blue-900">Tim 1</h2>

                    <div>
                        <p class="block text-sm font-medium text-gray-700 mb-2">Anggota Tim (Checklist)</p>
                        @if (count($teamMembers) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach ($teamMembers as $member)
                                    <label class="inline-flex items-center gap-2 rounded-lg border border-blue-100 bg-white px-3 py-2 text-sm text-gray-700">
                                        <input type="checkbox" name="team_1_members[]" value="{{ $member }}" data-team="1"
                                            @checked(in_array($member, old('team_1_members', []), true))
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span>{{ $member }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Daftar anggota belum tersedia untuk office Anda.</p>
                        @endif
                        @error('team_1_members')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="team_1_start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_1_start_time" name="team_1_start_time"
                                value="{{ old('team_1_start_time') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 @error('team_1_start_time') border-red-400 bg-red-50 @enderror">
                            @error('team_1_start_time')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="team_1_end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Akhir <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_1_end_time" name="team_1_end_time"
                                value="{{ old('team_1_end_time') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 @error('team_1_end_time') border-red-400 bg-red-50 @enderror">
                            @error('team_1_end_time')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if ($canManageMachineData && !empty($machineGroups))
                        <div class="rounded-lg border border-slate-200 bg-white p-4 space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Input Mesin Tim 1</h3>
                                <p class="mt-1 text-xs text-slate-600">Pilih mesin aktif untuk Tim 1 lalu isi jam produksi.</p>
                            </div>

                            @foreach ($machineGroups as $groupName => $machines)
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                    <button type="button"
                                        class="machine-group-toggle flex w-full items-center justify-between text-left"
                                        data-target="machine-group-t1-{{ $loop->index }}">
                                        <h4 class="text-sm font-semibold text-slate-800">{{ $groupName }}</h4>
                                        <span class="machine-group-arrow inline-block text-lg font-bold text-slate-500 transition-transform">&gt;</span>
                                    </button>

                                    <div id="machine-group-t1-{{ $loop->index }}" class="mt-3 space-y-3 hidden">
                                        @foreach ($machines as $machineName)
                                            @php
                                                $machineKey = 't1_machine_' . $loop->parent->index . '_' . $loop->index;
                                                $isChecked = old('machines.' . $machineKey . '.selected');
                                            @endphp

                                            <div class="rounded-md border border-slate-200 bg-white p-3">
                                                <div class="flex flex-wrap items-center gap-3">
                                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                                        <input type="checkbox"
                                                            name="machines[{{ $machineKey }}][selected]"
                                                            value="1"
                                                            class="machine-select rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                            data-target="machine-time-{{ $machineKey }}"
                                                            @checked($isChecked)>
                                                        <span>{{ $machineName }}</span>
                                                    </label>

                                                    <input type="hidden" name="machines[{{ $machineKey }}][machine_name]" value="{{ $machineName }}">
                                                    <input type="hidden" name="machines[{{ $machineKey }}][machine_group]" value="{{ $groupName }}">
                                                    <input type="hidden" name="machines[{{ $machineKey }}][team_name]" value="Tim 1">

                                                    <div id="machine-time-{{ $machineKey }}" class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:w-auto">
                                                        <input type="time" name="machines[{{ $machineKey }}][start_time]"
                                                            value="{{ old('machines.' . $machineKey . '.start_time') }}"
                                                            class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm @error('machines.' . $machineKey . '.start_time') border-red-400 bg-red-50 @enderror"
                                                            {{ $isChecked ? '' : 'disabled' }}>
                                                        <input type="time" name="machines[{{ $machineKey }}][end_time]"
                                                            value="{{ old('machines.' . $machineKey . '.end_time') }}"
                                                            class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm @error('machines.' . $machineKey . '.end_time') border-red-400 bg-red-50 @enderror"
                                                            {{ $isChecked ? '' : 'disabled' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-semibold text-amber-900">Jam mesin hidup setelah breakdown Tim 1</h4>
                            <button type="button" class="add-spare-machine-row inline-flex items-center rounded-md border border-amber-300 bg-white px-3 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100"
                                data-team="Tim 1" data-target="spare-machine-wrapper-team1">
                                Tambah Baris
                            </button>
                        </div>
                        <div id="spare-machine-wrapper-team1" class="spare-machine-wrapper mt-3 space-y-2" data-team="Tim 1">
                            @foreach ($spareRowsTeam1 as $idx => $spare)
                                @php $spareKey = 't1_' . $idx; @endphp
                                <div class="spare-machine-row grid grid-cols-1 md:grid-cols-5 gap-2 items-center rounded-lg border border-amber-100 bg-white p-3">
                                    <label class="inline-flex items-center gap-2 text-xs font-medium text-amber-800">
                                        <input type="checkbox" name="spare_machines[{{ $spareKey }}][selected]" value="1"
                                            @checked(!empty($spare['selected'] ?? false))
                                            class="spare-machine-toggle rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <span>Aktif</span>
                                    </label>
                                    <input type="hidden" name="spare_machines[{{ $spareKey }}][team_name]" value="Tim 1">
                                    <select name="spare_machines[{{ $spareKey }}][machine_name]"
                                        @disabled(empty($spare['selected'] ?? false))
                                        class="spare-machine-machine px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.machine_name') border-red-400 bg-red-50 @enderror">
                                        <option value="">Pilih mesin</option>
                                        @foreach ($machineOptions as $machineOption)
                                            <option value="{{ $machineOption }}" @selected(($spare['machine_name'] ?? '') === $machineOption)>{{ $machineOption }}</option>
                                        @endforeach
                                    </select>
                                    <input type="time" name="spare_machines[{{ $spareKey }}][start_time]" value="{{ $spare['start_time'] ?? '' }}"
                                        @disabled(empty($spare['selected'] ?? false))
                                        class="spare-machine-start px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.start_time') border-red-400 bg-red-50 @enderror">
                                    <input type="time" name="spare_machines[{{ $spareKey }}][end_time]" value="{{ $spare['end_time'] ?? '' }}"
                                        @disabled(empty($spare['selected'] ?? false))
                                        class="spare-machine-end px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.end_time') border-red-400 bg-red-50 @enderror">
                                    <button type="button" class="remove-spare-machine-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-lg border border-violet-200 bg-violet-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-semibold text-violet-900">Kondisi Lainnya Tim 1</h4>
                            <button type="button" class="add-other-condition-row inline-flex items-center rounded-md border border-violet-300 bg-white px-3 py-1 text-xs font-medium text-violet-700 hover:bg-violet-100"
                                data-team="Tim 1" data-target="other-condition-wrapper-team1">
                                Tambah Baris
                            </button>
                        </div>
                        <div id="other-condition-wrapper-team1" class="other-condition-wrapper mt-3 space-y-2" data-team="Tim 1">
                            @foreach ($otherConditionsTeam1 as $idx => $condition)
                                @php $conditionKey = 't1_' . $idx; @endphp
                                <div class="other-condition-row grid grid-cols-1 md:grid-cols-4 gap-2">
                                    <input type="hidden" name="other_conditions[{{ $conditionKey }}][team_name]" value="Tim 1">
                                    <input type="text" name="other_conditions[{{ $conditionKey }}][reason]" value="{{ $condition['reason'] ?? '' }}"
                                        placeholder="Alasan"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('other_conditions.' . $conditionKey . '.reason') border-red-400 bg-red-50 @enderror">
                                    <input type="time" name="other_conditions[{{ $conditionKey }}][start_time]" value="{{ $condition['start_time'] ?? '' }}"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('other_conditions.' . $conditionKey . '.start_time') border-red-400 bg-red-50 @enderror">
                                    <input type="time" name="other_conditions[{{ $conditionKey }}][end_time]" value="{{ $condition['end_time'] ?? '' }}"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('other_conditions.' . $conditionKey . '.end_time') border-red-400 bg-red-50 @enderror">
                                    <button type="button" class="remove-other-condition-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" data-input-team="Tim 1" onclick="document.getElementById('input_team_field').value='Tim 1'" class="submit-team-button inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan Informasi Proses Spintest Tim 1
                        </button>
                    </div>
                </div>

                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 space-y-4" id="team-2-section">
                    <h2 class="text-lg font-semibold text-emerald-900">Tim 2</h2>

                    <div>
                        <p class="block text-sm font-medium text-gray-700 mb-2">Anggota Tim (Checklist)</p>
                        @if (count($teamMembers) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach ($teamMembers as $member)
                                    <label class="inline-flex items-center gap-2 rounded-lg border border-emerald-100 bg-white px-3 py-2 text-sm text-gray-700">
                                        <input type="checkbox" name="team_2_members[]" value="{{ $member }}" data-team="2"
                                            @checked(in_array($member, old('team_2_members', []), true))
                                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                        <span>{{ $member }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Daftar anggota belum tersedia untuk office Anda.</p>
                        @endif
                        @error('team_2_members')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="team_2_start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_2_start_time" name="team_2_start_time"
                                value="{{ old('team_2_start_time') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('team_2_start_time') border-red-400 bg-red-50 @enderror">
                            @error('team_2_start_time')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="team_2_end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Akhir <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_2_end_time" name="team_2_end_time"
                                value="{{ old('team_2_end_time') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('team_2_end_time') border-red-400 bg-red-50 @enderror">
                            @error('team_2_end_time')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if ($canManageMachineData && !empty($machineGroups))
                        <div class="rounded-lg border border-slate-200 bg-white p-4 space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Input Mesin Tim 2</h3>
                                <p class="mt-1 text-xs text-slate-600">Pilih mesin aktif untuk Tim 2 lalu isi jam produksi.</p>
                            </div>

                            @foreach ($machineGroups as $groupName => $machines)
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                    <button type="button"
                                        class="machine-group-toggle flex w-full items-center justify-between text-left"
                                        data-target="machine-group-t2-{{ $loop->index }}">
                                        <h4 class="text-sm font-semibold text-slate-800">{{ $groupName }}</h4>
                                        <span class="machine-group-arrow inline-block text-lg font-bold text-slate-500 transition-transform">&gt;</span>
                                    </button>

                                    <div id="machine-group-t2-{{ $loop->index }}" class="mt-3 space-y-3 hidden">
                                        @foreach ($machines as $machineName)
                                            @php
                                                $machineKey = 't2_machine_' . $loop->parent->index . '_' . $loop->index;
                                                $isChecked = old('machines.' . $machineKey . '.selected');
                                            @endphp

                                            <div class="rounded-md border border-slate-200 bg-white p-3">
                                                <div class="flex flex-wrap items-center gap-3">
                                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                                        <input type="checkbox"
                                                            name="machines[{{ $machineKey }}][selected]"
                                                            value="1"
                                                            class="machine-select rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                                            data-target="machine-time-{{ $machineKey }}"
                                                            @checked($isChecked)>
                                                        <span>{{ $machineName }}</span>
                                                    </label>

                                                    <input type="hidden" name="machines[{{ $machineKey }}][machine_name]" value="{{ $machineName }}">
                                                    <input type="hidden" name="machines[{{ $machineKey }}][machine_group]" value="{{ $groupName }}">
                                                    <input type="hidden" name="machines[{{ $machineKey }}][team_name]" value="Tim 2">

                                                    <div id="machine-time-{{ $machineKey }}" class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:w-auto">
                                                        <input type="time" name="machines[{{ $machineKey }}][start_time]"
                                                            value="{{ old('machines.' . $machineKey . '.start_time') }}"
                                                            class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm @error('machines.' . $machineKey . '.start_time') border-red-400 bg-red-50 @enderror"
                                                            {{ $isChecked ? '' : 'disabled' }}>
                                                        <input type="time" name="machines[{{ $machineKey }}][end_time]"
                                                            value="{{ old('machines.' . $machineKey . '.end_time') }}"
                                                            class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm @error('machines.' . $machineKey . '.end_time') border-red-400 bg-red-50 @enderror"
                                                            {{ $isChecked ? '' : 'disabled' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-semibold text-amber-900">Jam mesin hidup setelah breakdown Tim 2</h4>
                            <button type="button" class="add-spare-machine-row inline-flex items-center rounded-md border border-amber-300 bg-white px-3 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100"
                                data-team="Tim 2" data-target="spare-machine-wrapper-team2">
                                Tambah Baris
                            </button>
                        </div>
                        <div id="spare-machine-wrapper-team2" class="spare-machine-wrapper mt-3 space-y-2" data-team="Tim 2">
                            @foreach ($spareRowsTeam2 as $idx => $spare)
                                @php $spareKey = 't2_' . $idx; @endphp
                                <div class="spare-machine-row grid grid-cols-1 md:grid-cols-5 gap-2 items-center rounded-lg border border-amber-100 bg-white p-3">
                                    <label class="inline-flex items-center gap-2 text-xs font-medium text-amber-800">
                                        <input type="checkbox" name="spare_machines[{{ $spareKey }}][selected]" value="1"
                                            @checked(!empty($spare['selected'] ?? false))
                                            class="spare-machine-toggle rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <span>Aktif</span>
                                    </label>
                                    <input type="hidden" name="spare_machines[{{ $spareKey }}][team_name]" value="Tim 2">
                                    <select name="spare_machines[{{ $spareKey }}][machine_name]"
                                        @disabled(empty($spare['selected'] ?? false))
                                        class="spare-machine-machine px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.machine_name') border-red-400 bg-red-50 @enderror">
                                        <option value="">Pilih mesin</option>
                                        @foreach ($machineOptions as $machineOption)
                                            <option value="{{ $machineOption }}" @selected(($spare['machine_name'] ?? '') === $machineOption)>{{ $machineOption }}</option>
                                        @endforeach
                                    </select>
                                    <input type="time" name="spare_machines[{{ $spareKey }}][start_time]" value="{{ $spare['start_time'] ?? '' }}"
                                        @disabled(empty($spare['selected'] ?? false))
                                        class="spare-machine-start px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.start_time') border-red-400 bg-red-50 @enderror">
                                    <input type="time" name="spare_machines[{{ $spareKey }}][end_time]" value="{{ $spare['end_time'] ?? '' }}"
                                        @disabled(empty($spare['selected'] ?? false))
                                        class="spare-machine-end px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.end_time') border-red-400 bg-red-50 @enderror">
                                    <button type="button" class="remove-spare-machine-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-lg border border-violet-200 bg-violet-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-semibold text-violet-900">Kondisi Lainnya Tim 2</h4>
                            <button type="button" class="add-other-condition-row inline-flex items-center rounded-md border border-violet-300 bg-white px-3 py-1 text-xs font-medium text-violet-700 hover:bg-violet-100"
                                data-team="Tim 2" data-target="other-condition-wrapper-team2">
                                Tambah Baris
                            </button>
                        </div>
                        <div id="other-condition-wrapper-team2" class="other-condition-wrapper mt-3 space-y-2" data-team="Tim 2">
                            @foreach ($otherConditionsTeam2 as $idx => $condition)
                                @php $conditionKey = 't2_' . $idx; @endphp
                                <div class="other-condition-row grid grid-cols-1 md:grid-cols-4 gap-2">
                                    <input type="hidden" name="other_conditions[{{ $conditionKey }}][team_name]" value="Tim 2">
                                    <input type="text" name="other_conditions[{{ $conditionKey }}][reason]" value="{{ $condition['reason'] ?? '' }}"
                                        placeholder="Alasan"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('other_conditions.' . $conditionKey . '.reason') border-red-400 bg-red-50 @enderror">
                                    <input type="time" name="other_conditions[{{ $conditionKey }}][start_time]" value="{{ $condition['start_time'] ?? '' }}"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('other_conditions.' . $conditionKey . '.start_time') border-red-400 bg-red-50 @enderror">
                                    <input type="time" name="other_conditions[{{ $conditionKey }}][end_time]" value="{{ $condition['end_time'] ?? '' }}"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('other_conditions.' . $conditionKey . '.end_time') border-red-400 bg-red-50 @enderror">
                                    <button type="button" class="remove-other-condition-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" data-input-team="Tim 2" onclick="document.getElementById('input_team_field').value='Tim 2'" class="submit-team-button inline-flex items-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Simpan Informasi Proses Spintest Tim 2
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card title="Data Informasi Proses Spintest" class="mt-6">
        <form method="GET" action="{{ route('process.spintest.index') }}" class="mb-4 flex flex-wrap items-end gap-3">
            <div>
                <label for="office" class="block text-xs font-medium text-gray-700 mb-1">Filter Office</label>
                @if(auth()->user()->office)
                    <input type="text" value="{{ auth()->user()->office }}" disabled
                        class="w-36 px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 text-gray-700">
                    <input type="hidden" name="office" value="{{ auth()->user()->office }}">
                @else
                    <select id="office" name="office" class="w-36 px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="all" @selected(($officeFilter ?? 'all') === 'all')>Semua</option>
                        @foreach ($officeOptions ?? [] as $officeOption)
                            <option value="{{ $officeOption }}" @selected(($officeFilter ?? 'all') === $officeOption)>{{ $officeOption }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700">Office</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700">Tim</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700">Mesin</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($records as $record)
                        <tr>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $record['process_date'] }}</td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $record['office'] }}</td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $record['input_team'] }}</td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $record['machines_summary'] }}</td>
                            <td class="px-3 py-2 text-sm">
                                <div class="inline-flex items-center gap-2">
                                    <a href="javascript:void(0)"
                                        class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 hover:bg-indigo-100">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-4 text-center text-sm text-gray-500">Belum ada data proses spintest.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addSpareButtons = Array.from(document.querySelectorAll('.add-spare-machine-row'));
            const spareWrappers = Array.from(document.querySelectorAll('.spare-machine-wrapper'));
            const addConditionButtons = Array.from(document.querySelectorAll('.add-other-condition-row'));
            const conditionWrappers = Array.from(document.querySelectorAll('.other-condition-wrapper'));
            const machineChecks = Array.from(document.querySelectorAll('.machine-select'));
            const machineGroupToggles = Array.from(document.querySelectorAll('.machine-group-toggle'));
            const submitButtons = Array.from(document.querySelectorAll('.submit-team-button'));
            const formElement = document.getElementById('spintest-form');
            const inputTeamField = document.getElementById('input_team_field');
            const machineOptions = {!! json_encode($machineOptions) !!};

            function setMachineTimeState(wrapperId, enabled) {
                const wrapper = document.getElementById(wrapperId);
                if (!wrapper) return;
                wrapper.querySelectorAll('input[type="time"]').forEach(field => {
                    field.disabled = !enabled;
                    if (!enabled) {
                        field.value = '';
                    }
                });
            }

            machineChecks.forEach(checkbox => {
                const targetId = checkbox.getAttribute('data-target');
                if (!targetId) return;

                setMachineTimeState(targetId, checkbox.checked);
                checkbox.addEventListener('change', function () {
                    setMachineTimeState(targetId, this.checked);
                });
            });

            machineGroupToggles.forEach(toggle => {
                toggle.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const target = targetId ? document.getElementById(targetId) : null;
                    const arrow = this.querySelector('.machine-group-arrow');

                    if (!target) return;

                    const isHidden = target.classList.contains('hidden');
                    target.classList.toggle('hidden');
                    if (arrow) {
                        arrow.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
                    }
                });
            });

            function setSpareRowState(row, enabled) {
                row.querySelectorAll('select, input[type="time"]').forEach(field => {
                    field.disabled = !enabled;
                    if (!enabled) {
                        field.value = '';
                    }
                });
            }

            function syncSpareCheckbox(row) {
                const checkbox = row.querySelector('.spare-machine-toggle');
                if (!checkbox) return;
                setSpareRowState(row, checkbox.checked);

                checkbox.addEventListener('change', function () {
                    setSpareRowState(row, this.checked);
                });
            }

            spareWrappers.forEach(wrapper => {
                wrapper.querySelectorAll('.spare-machine-row').forEach(syncSpareCheckbox);
            });

            addSpareButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    const team = this.getAttribute('data-team');
                    const wrapper = document.getElementById(target);
                    if (!wrapper) return;

                    const idx = 'row_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
                    const row = document.createElement('div');
                    row.className = 'spare-machine-row grid grid-cols-1 md:grid-cols-5 gap-2 items-center rounded-lg border border-amber-100 bg-white p-3';
                    const optionsHtml = '<option value="">Pilih mesin</option>' + machineOptions.map(m => `<option value="${m}">${m}</option>`).join('');
                    row.innerHTML = `
                        <label class="inline-flex items-center gap-2 text-xs font-medium text-amber-800">
                            <input type="checkbox" class="spare-machine-toggle rounded border-gray-300 text-amber-600 focus:ring-amber-500" name="spare_machines[${idx}][selected]" value="1">
                            <span>Aktif</span>
                        </label>
                        <input type="hidden" name="spare_machines[${idx}][team_name]" value="${team}">
                        <select name="spare_machines[${idx}][machine_name]" class="spare-machine-machine px-3 py-2 border border-gray-300 rounded-md text-sm" disabled>${optionsHtml}</select>
                        <input type="time" name="spare_machines[${idx}][start_time]" class="spare-machine-start px-3 py-2 border border-gray-300 rounded-md text-sm" disabled>
                        <input type="time" name="spare_machines[${idx}][end_time]" class="spare-machine-end px-3 py-2 border border-gray-300 rounded-md text-sm" disabled>
                        <button type="button" class="remove-spare-machine-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                    `;
                    syncSpareCheckbox(row);
                    wrapper.appendChild(row);
                });
            });

            spareWrappers.forEach(wrapper => {
                wrapper.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-spare-machine-row')) {
                        const rows = wrapper.querySelectorAll('.spare-machine-row');
                        if (rows.length <= 1) {
                            const row = rows[0];
                            const checkbox = row.querySelector('.spare-machine-toggle');
                            if (checkbox) {
                                checkbox.checked = false;
                            }
                            setSpareRowState(row, false);
                        } else {
                            e.target.closest('.spare-machine-row')?.remove();
                        }
                    }
                });
            });

            addConditionButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    const team = this.getAttribute('data-team');
                    const wrapper = document.getElementById(target);
                    if (!wrapper) return;

                    const idx = 'row_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
                    const row = document.createElement('div');
                    row.className = 'other-condition-row grid grid-cols-1 md:grid-cols-4 gap-2';
                    row.innerHTML = `
                        <input type="hidden" name="other_conditions[${idx}][team_name]" value="${team}">
                        <input type="text" name="other_conditions[${idx}][reason]" placeholder="Alasan" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <input type="time" name="other_conditions[${idx}][start_time]" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <input type="time" name="other_conditions[${idx}][end_time]" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <button type="button" class="remove-other-condition-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                    `;
                    wrapper.appendChild(row);
                });
            });

            conditionWrappers.forEach(wrapper => {
                wrapper.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-other-condition-row')) {
                        const rows = wrapper.querySelectorAll('.other-condition-row');
                        if (rows.length <= 1) {
                            rows[0].querySelectorAll('input, textarea').forEach(f => f.value = '');
                        } else {
                            e.target.closest('.other-condition-row')?.remove();
                        }
                    }
                });
            });

            submitButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const team = this.getAttribute('data-input-team');
                    if (!formElement || !inputTeamField || !team) return;
                    inputTeamField.value = team;
                    formElement.submit();
                });
            });
        });
    </script>
</x-layouts.app>
