<x-layouts.app title="Informasi Proses">
    @php
        $oldSpareRows = old('spare_machines', []);
        $spareRowsTeam1 = collect($oldSpareRows)->filter(fn ($row) => ($row['team_name'] ?? 'Tim 1') === 'Tim 1')->all();
        $spareRowsTeam2 = collect($oldSpareRows)->filter(fn ($row) => ($row['team_name'] ?? '') === 'Tim 2')->all();

        if (empty($spareRowsTeam1)) {
            $spareRowsTeam1 = [['team_name' => 'Tim 1', 'machine_name' => '', 'start_time' => '', 'end_time' => '']];
        }

        if (empty($spareRowsTeam2)) {
            $spareRowsTeam2 = [['team_name' => 'Tim 2', 'machine_name' => '', 'start_time' => '', 'end_time' => '']];
        }

        $machineOptions = collect($machineGroups)->flatten()->values()->all();
        $teamOptions = ['Tim 1', 'Tim 2'];
    @endphp

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Informasi Proses</h1>
        <p class="mt-2 text-sm text-gray-600">Form monitoring proses per tanggal untuk Tim 1 dan Tim 2.</p>
    </div>

    <x-ui.card title="Form Informasi Proses">
        <form action="{{ route('process.store') }}" method="POST" class="space-y-6" id="process-form">
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

                        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <h4 class="text-sm font-semibold text-amber-900">Spare Input Mesin Tim 1</h4>
                                <button type="button" class="add-spare-machine-row inline-flex items-center rounded-md border border-amber-300 bg-white px-3 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100"
                                    data-team="Tim 1" data-target="spare-machine-wrapper-team1">
                                    Tambah Baris
                                </button>
                            </div>
                            <div id="spare-machine-wrapper-team1" class="spare-machine-wrapper mt-3 space-y-2" data-team="Tim 1">
                                @foreach ($spareRowsTeam1 as $idx => $spare)
                                    @php
                                        $spareKey = 't1_' . $idx;
                                    @endphp
                                    <div class="spare-machine-row grid grid-cols-1 md:grid-cols-4 gap-2">
                                        <input type="hidden" name="spare_machines[{{ $spareKey }}][team_name]" value="Tim 1">
                                        <select name="spare_machines[{{ $spareKey }}][machine_name]"
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.machine_name') border-red-400 bg-red-50 @enderror">
                                            <option value="">Pilih mesin</option>
                                            @foreach ($machineOptions as $machineOption)
                                                <option value="{{ $machineOption }}" @selected(($spare['machine_name'] ?? '') === $machineOption)>{{ $machineOption }}</option>
                                            @endforeach
                                        </select>
                                        <input type="time" name="spare_machines[{{ $spareKey }}][start_time]" value="{{ $spare['start_time'] ?? '' }}"
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.start_time') border-red-400 bg-red-50 @enderror">
                                        <input type="time" name="spare_machines[{{ $spareKey }}][end_time]" value="{{ $spare['end_time'] ?? '' }}"
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.end_time') border-red-400 bg-red-50 @enderror">
                                        <button type="button" class="remove-spare-machine-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="button" data-input-team="Tim 1" class="submit-team-button inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan Informasi Proses Tim 1
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

                        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <h4 class="text-sm font-semibold text-amber-900">Spare Input Mesin Tim 2</h4>
                                <button type="button" class="add-spare-machine-row inline-flex items-center rounded-md border border-amber-300 bg-white px-3 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100"
                                    data-team="Tim 2" data-target="spare-machine-wrapper-team2">
                                    Tambah Baris
                                </button>
                            </div>
                            <div id="spare-machine-wrapper-team2" class="spare-machine-wrapper mt-3 space-y-2" data-team="Tim 2">
                                @foreach ($spareRowsTeam2 as $idx => $spare)
                                    @php
                                        $spareKey = 't2_' . $idx;
                                    @endphp
                                    <div class="spare-machine-row grid grid-cols-1 md:grid-cols-4 gap-2">
                                        <input type="hidden" name="spare_machines[{{ $spareKey }}][team_name]" value="Tim 2">
                                        <select name="spare_machines[{{ $spareKey }}][machine_name]"
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.machine_name') border-red-400 bg-red-50 @enderror">
                                            <option value="">Pilih mesin</option>
                                            @foreach ($machineOptions as $machineOption)
                                                <option value="{{ $machineOption }}" @selected(($spare['machine_name'] ?? '') === $machineOption)>{{ $machineOption }}</option>
                                            @endforeach
                                        </select>
                                        <input type="time" name="spare_machines[{{ $spareKey }}][start_time]" value="{{ $spare['start_time'] ?? '' }}"
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.start_time') border-red-400 bg-red-50 @enderror">
                                        <input type="time" name="spare_machines[{{ $spareKey }}][end_time]" value="{{ $spare['end_time'] ?? '' }}"
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm @error('spare_machines.' . $spareKey . '.end_time') border-red-400 bg-red-50 @enderror">
                                        <button type="button" class="remove-spare-machine-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="button" data-input-team="Tim 2" class="submit-team-button inline-flex items-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Simpan Informasi Proses Tim 2
                        </button>
                    </div>

                    
                </div>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card title="Data Informasi Proses" class="mt-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700">Tim</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($records as $record)
                        <tr>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $record['process_date'] }}</td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $record['input_team'] }}</td>
                            <td class="px-3 py-2 text-sm">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('process.show', ['kernelProsses' => $record['id']]) }}"
                                        class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 hover:bg-indigo-100">
                                        Detail
                                    </a>
                                    <a href="{{ route('process.edit', ['kernelProsses' => $record['id']]) }}"
                                        class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100">
                                        Edit
                                    </a>
                                    <a href="{{ route('process.machines.show', ['kernelProsses' => $record['id']]) }}"
                                        class="inline-flex items-center rounded-md bg-teal-50 px-2 py-1 text-xs font-medium text-teal-700 hover:bg-teal-100">
                                        Detail Mesin
                                    </a>
                                    <a href="{{ route('process.machines.edit', ['kernelProsses' => $record['id']]) }}"
                                        class="inline-flex items-center rounded-md bg-cyan-50 px-2 py-1 text-xs font-medium text-cyan-700 hover:bg-cyan-100">
                                        Edit Mesin
                                    </a>
                                    <span class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-600">
                                        {{ $record['mesin_count'] }} mesin
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-3 py-4 text-center text-sm text-gray-500">Belum ada data proses.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const team1Checks = Array.from(document.querySelectorAll('input[name="team_1_members[]"]'));
            const team2Checks = Array.from(document.querySelectorAll('input[name="team_2_members[]"]'));
            const machineChecks = Array.from(document.querySelectorAll('.machine-select'));
            const machineGroupToggles = Array.from(document.querySelectorAll('.machine-group-toggle'));
            const addSpareMachineButtons = Array.from(document.querySelectorAll('.add-spare-machine-row'));
            const spareWrappers = Array.from(document.querySelectorAll('.spare-machine-wrapper'));
            const submitTeamButtons = Array.from(document.querySelectorAll('.submit-team-button'));
            const processForm = document.getElementById('process-form');
            const inputTeamField = document.getElementById('input_team_field');
            const team1Section = document.getElementById('team-1-section');
            const team2Section = document.getElementById('team-2-section');
            const machineOptions = @json($machineOptions);

            function syncTeams() {
                const selectedTeam1 = new Set(team1Checks.filter(input => input.checked).map(input => input.value));
                const selectedTeam2 = new Set(team2Checks.filter(input => input.checked).map(input => input.value));

                team1Checks.forEach(input => {
                    if (!input.checked) {
                        input.disabled = selectedTeam2.has(input.value);
                    }
                });

                team2Checks.forEach(input => {
                    if (!input.checked) {
                        input.disabled = selectedTeam1.has(input.value);
                    }
                });
            }

            function syncMachineInputs() {
                machineChecks.forEach(check => {
                    const targetId = check.getAttribute('data-target');
                    const wrapper = document.getElementById(targetId);

                    if (!wrapper) {
                        return;
                    }

                    const managedInputs = Array.from(wrapper.querySelectorAll('.machine-time-input'));

                    managedInputs.forEach(input => {
                        input.disabled = !check.checked;
                        input.required = check.checked;

                        if (!check.checked) {
                            input.value = '';
                        }
                    });
                });
            }

            function appendSpareMachineRow(targetId, teamName) {
                const spareWrapper = document.getElementById(targetId);

                if (!spareWrapper) {
                    return;
                }

                const nextIndex = `row_${Date.now()}_${Math.floor(Math.random() * 1000)}`;
                const row = document.createElement('div');
                row.className = 'spare-machine-row grid grid-cols-1 md:grid-cols-4 gap-2';

                const optionsHtml = ['<option value="">Pilih mesin</option>']
                    .concat(machineOptions.map(name => `<option value="${name}">${name}</option>`))
                    .join('');

                row.innerHTML = `
                    <input type="hidden" name="spare_machines[${nextIndex}][team_name]" value="${teamName}">
                    <select name="spare_machines[${nextIndex}][machine_name]" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                        ${optionsHtml}
                    </select>
                    <input type="time" name="spare_machines[${nextIndex}][start_time]" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <input type="time" name="spare_machines[${nextIndex}][end_time]" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <button type="button" class="remove-spare-machine-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                `;
                spareWrapper.appendChild(row);
            }

            [...team1Checks, ...team2Checks].forEach(input => {
                input.addEventListener('change', syncTeams);
            });

            machineChecks.forEach(input => {
                input.addEventListener('change', syncMachineInputs);
            });

            addSpareMachineButtons.forEach(button => {
                button.addEventListener('click', function () {
                    appendSpareMachineRow(button.getAttribute('data-target'), button.getAttribute('data-team'));
                });
            });

            machineGroupToggles.forEach(toggle => {
                const targetId = toggle.getAttribute('data-target');
                const target = document.getElementById(targetId);
                const arrow = toggle.querySelector('.machine-group-arrow');

                if (!target || !arrow) {
                    return;
                }

                toggle.addEventListener('click', function () {
                    target.classList.toggle('hidden');
                    arrow.classList.toggle('rotate-90');
                });
            });

            spareWrappers.forEach(spareWrapper => {
                spareWrapper.addEventListener('click', function (event) {
                    const removeButton = event.target.closest('.remove-spare-machine-row');

                    if (!removeButton) {
                        return;
                    }

                    const rows = spareWrapper.querySelectorAll('.spare-machine-row');
                    if (rows.length <= 1) {
                        const fields = rows[0].querySelectorAll('select, input');
                        fields.forEach(field => {
                            field.value = '';
                        });
                        return;
                    }

                    removeButton.closest('.spare-machine-row')?.remove();
                });
            });

            submitTeamButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const teamName = button.getAttribute('data-input-team');
                    if (!processForm || !inputTeamField || !teamName) {
                        return;
                    }

                    inputTeamField.value = teamName;

                    const disableSection = teamName === 'Tim 1' ? team2Section : team1Section;
                    if (disableSection) {
                        disableSection.querySelectorAll('input, select, textarea, button').forEach(field => {
                            if (field === button) {
                                return;
                            }

                            if (field.name === '_token' || field.name === 'process_date' || field.name === 'input_team') {
                                return;
                            }

                            field.disabled = true;
                        });
                    }

                    processForm.submit();
                });
            });

            syncTeams();
            syncMachineInputs();
        });
    </script>
</x-layouts.app>
