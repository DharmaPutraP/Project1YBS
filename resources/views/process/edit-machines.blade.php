<x-layouts.app title="Edit Mesin Proses">
    @php
        $oldSpareRows = old('spare_machines');

        $defaultSpareRowsTeam1 = $spareRowsByTeam['Tim 1'] ?? [];
        $defaultSpareRowsTeam2 = $spareRowsByTeam['Tim 2'] ?? [];

        $spareRowsTeam1 = is_array($oldSpareRows)
            ? collect($oldSpareRows)->filter(fn ($row) => ($row['team_name'] ?? 'Tim 1') === 'Tim 1')->values()->all()
            : $defaultSpareRowsTeam1;

        $spareRowsTeam2 = is_array($oldSpareRows)
            ? collect($oldSpareRows)->filter(fn ($row) => ($row['team_name'] ?? '') === 'Tim 2')->values()->all()
            : $defaultSpareRowsTeam2;

        if (empty($spareRowsTeam1)) {
            $spareRowsTeam1 = [['team_name' => 'Tim 1', 'machine_name' => '', 'start_time' => '', 'end_time' => '']];
        }

        if (empty($spareRowsTeam2)) {
            $spareRowsTeam2 = [['team_name' => 'Tim 2', 'machine_name' => '', 'start_time' => '', 'end_time' => '']];
        }
    @endphp

    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Mesin Proses</h1>
            <p class="mt-2 text-sm text-gray-600">Ubah data mesin untuk tanggal {{ optional($record->process_date)->format('d/m/Y') }}.</p>
        </div>
        <a href="{{ route('process.index') }}"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Kembali
        </a>
    </div>

    <x-ui.card title="Form Edit Mesin">
        <form action="{{ route('process.machines.update', ['kernelProsses' => $record->id]) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if (!$visibleTeam || $visibleTeam === 'Tim 1')
                <div class="rounded-xl border border-blue-200 bg-blue-50 p-5 space-y-4">
                    <h2 class="text-lg font-semibold text-blue-900">Tim 1</h2>

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
                                            $selectedKey = 'Tim 1|' . strtoupper(trim($machineName));
                                            $defaultSelected = isset($selectedMainMachines[$selectedKey]);
                                            $isChecked = old('machines.' . $machineKey . '.selected', $defaultSelected ? '1' : null);
                                            $defaultStart = $selectedMainMachines[$selectedKey]['start_time'] ?? '';
                                            $defaultEnd = $selectedMainMachines[$selectedKey]['end_time'] ?? '';
                                        @endphp

                                        <div class="rounded-md border border-slate-200 bg-white p-3">
                                            <div class="flex flex-wrap items-center gap-3">
                                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                                    <input type="checkbox"
                                                        name="machines[{{ $machineKey }}][selected]"
                                                        value="1"
                                                        class="machine-select rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                        data-target="machine-time-{{ $machineKey }}"
                                                        @checked((bool) $isChecked)>
                                                    <span>{{ $machineName }}</span>
                                                </label>

                                                <input type="hidden" name="machines[{{ $machineKey }}][machine_name]" value="{{ $machineName }}">
                                                <input type="hidden" name="machines[{{ $machineKey }}][machine_group]" value="{{ $groupName }}">
                                                <input type="hidden" name="machines[{{ $machineKey }}][team_name]" value="Tim 1">

                                                <div id="machine-time-{{ $machineKey }}" class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:w-auto">
                                                    <input type="time" name="machines[{{ $machineKey }}][start_time]"
                                                        value="{{ old('machines.' . $machineKey . '.start_time', $defaultStart) }}"
                                                        class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm @error('machines.' . $machineKey . '.start_time') border-red-400 bg-red-50 @enderror"
                                                        {{ (bool) $isChecked ? '' : 'disabled' }}>
                                                    <input type="time" name="machines[{{ $machineKey }}][end_time]"
                                                        value="{{ old('machines.' . $machineKey . '.end_time', $defaultEnd) }}"
                                                        class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm @error('machines.' . $machineKey . '.end_time') border-red-400 bg-red-50 @enderror"
                                                        {{ (bool) $isChecked ? '' : 'disabled' }}>
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
                </div>
                @endif

                @if (!$visibleTeam || $visibleTeam === 'Tim 2')
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 space-y-4">
                    <h2 class="text-lg font-semibold text-emerald-900">Tim 2</h2>

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
                                            $selectedKey = 'Tim 2|' . strtoupper(trim($machineName));
                                            $defaultSelected = isset($selectedMainMachines[$selectedKey]);
                                            $isChecked = old('machines.' . $machineKey . '.selected', $defaultSelected ? '1' : null);
                                            $defaultStart = $selectedMainMachines[$selectedKey]['start_time'] ?? '';
                                            $defaultEnd = $selectedMainMachines[$selectedKey]['end_time'] ?? '';
                                        @endphp

                                        <div class="rounded-md border border-slate-200 bg-white p-3">
                                            <div class="flex flex-wrap items-center gap-3">
                                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                                    <input type="checkbox"
                                                        name="machines[{{ $machineKey }}][selected]"
                                                        value="1"
                                                        class="machine-select rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                                        data-target="machine-time-{{ $machineKey }}"
                                                        @checked((bool) $isChecked)>
                                                    <span>{{ $machineName }}</span>
                                                </label>

                                                <input type="hidden" name="machines[{{ $machineKey }}][machine_name]" value="{{ $machineName }}">
                                                <input type="hidden" name="machines[{{ $machineKey }}][machine_group]" value="{{ $groupName }}">
                                                <input type="hidden" name="machines[{{ $machineKey }}][team_name]" value="Tim 2">

                                                <div id="machine-time-{{ $machineKey }}" class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:w-auto">
                                                    <input type="time" name="machines[{{ $machineKey }}][start_time]"
                                                        value="{{ old('machines.' . $machineKey . '.start_time', $defaultStart) }}"
                                                        class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm @error('machines.' . $machineKey . '.start_time') border-red-400 bg-red-50 @enderror"
                                                        {{ (bool) $isChecked ? '' : 'disabled' }}>
                                                    <input type="time" name="machines[{{ $machineKey }}][end_time]"
                                                        value="{{ old('machines.' . $machineKey . '.end_time', $defaultEnd) }}"
                                                        class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm @error('machines.' . $machineKey . '.end_time') border-red-400 bg-red-50 @enderror"
                                                        {{ (bool) $isChecked ? '' : 'disabled' }}>
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
                </div>
                @endif
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Simpan Perubahan Mesin
                </button>
            </div>
        </form>
    </x-ui.card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const machineChecks = Array.from(document.querySelectorAll('.machine-select'));
            const machineGroupToggles = Array.from(document.querySelectorAll('.machine-group-toggle'));
            const addSpareMachineButtons = Array.from(document.querySelectorAll('.add-spare-machine-row'));
            const spareWrappers = Array.from(document.querySelectorAll('.spare-machine-wrapper'));
            const machineOptions = @json($machineOptions);

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

            syncMachineInputs();
        });
    </script>
</x-layouts.app>
