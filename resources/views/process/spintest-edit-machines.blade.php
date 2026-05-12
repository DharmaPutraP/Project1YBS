<x-layouts.app title="Edit Mesin Proses Spintest">
    @php
        $teamName = $visibleTeam ?: 'Tim 1';
        $oldMachines = old('machines', []);
        $oldSpareRows = old('spare_machines', []);
        $oldOtherConditions = old('other_conditions', []);

        $spareRows = collect($oldSpareRows)
            ->filter(fn ($row) => (string) ($row['team_name'] ?? $teamName) === $teamName)
            ->values()
            ->all();

        $otherConditions = collect($oldOtherConditions)
            ->filter(fn ($row) => (string) ($row['team_name'] ?? $teamName) === $teamName)
            ->values()
            ->all();

        if (empty($spareRows)) {
            $spareRows = $spareRowsByTeam[$teamName] ?? [['team_name' => $teamName, 'machine_name' => '', 'start_time' => '', 'end_time' => '', 'selected' => false]];
        }

        if (empty($otherConditions)) {
            $otherConditions = $otherConditionsByTeam[$teamName] ?? [['team_name' => $teamName, 'reason' => '', 'start_time' => '', 'end_time' => '']];
        }
    @endphp

    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Mesin Proses Spintest</h1>
            <p class="mt-2 text-sm text-gray-600">Ubah data mesin untuk tanggal {{ $record->process_date?->format('d/m/Y') }}.</p>
        </div>
        <a href="{{ route('process.spintest.index') }}"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Kembali
        </a>
    </div>

    <x-ui.card title="Form Edit Mesin Spintest">
        <form action="{{ route('process.spintest.machines.update', ['spintestProsses' => $record->id]) }}" method="POST" class="space-y-6" id="spintest-machine-form">
            @csrf
            @method('PUT')

            <input type="hidden" name="input_team" value="{{ $teamName }}">

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">{{ $teamName }}</h2>
                    <p class="mt-1 text-xs text-slate-600">Nama mesin dicentang berarti mesin beroperasi. Jam mesin hidup dicentang berarti spare. Kondisi lainnya akan memakan waktu proses.</p>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white p-4 space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Input Mesin {{ $teamName }}</h3>
                        <p class="mt-1 text-xs text-slate-600">Pilih mesin aktif lalu isi jam produksi.</p>
                    </div>

                    @foreach ($machineGroups as $groupName => $machines)
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                            <button type="button" class="machine-group-toggle flex w-full items-center justify-between text-left" data-target="machine-group-{{ $loop->index }}">
                                <h4 class="text-sm font-semibold text-slate-800">{{ $groupName }}</h4>
                                <span class="machine-group-arrow inline-block text-lg font-bold text-slate-500 transition-transform">&gt;</span>
                            </button>

                            <div id="machine-group-{{ $loop->index }}" class="mt-3 space-y-3 hidden">
                                @foreach ($machines as $machineName)
                                    @php
                                        $machineKey = 'machine_' . $loop->parent->index . '_' . $loop->index;
                                        $selectedKey = $teamName . '|' . strtoupper(trim($machineName));
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
                                                    @checked($isChecked)>
                                                <span>{{ $machineName }}</span>
                                            </label>

                                            <input type="hidden" name="machines[{{ $machineKey }}][machine_name]" value="{{ $machineName }}">
                                            <input type="hidden" name="machines[{{ $machineKey }}][machine_group]" value="{{ $groupName }}">
                                            <input type="hidden" name="machines[{{ $machineKey }}][team_name]" value="{{ $teamName }}">

                                            <div id="machine-time-{{ $machineKey }}" class="grid w-full grid-cols-1 gap-2 sm:w-auto sm:grid-cols-2">
                                                <input type="time" name="machines[{{ $machineKey }}][start_time]"
                                                    value="{{ old('machines.' . $machineKey . '.start_time', $defaultStart) }}"
                                                    class="machine-time-input w-full rounded-md border border-gray-300 px-3 py-1.5 text-sm @error('machines.' . $machineKey . '.start_time') border-red-400 bg-red-50 @enderror"
                                                    {{ $isChecked ? '' : 'disabled' }}>
                                                <input type="time" name="machines[{{ $machineKey }}][end_time]"
                                                    value="{{ old('machines.' . $machineKey . '.end_time', $defaultEnd) }}"
                                                    class="machine-time-input w-full rounded-md border border-gray-300 px-3 py-1.5 text-sm @error('machines.' . $machineKey . '.end_time') border-red-400 bg-red-50 @enderror"
                                                    {{ $isChecked ? '' : 'disabled' }}>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <h4 class="text-sm font-semibold text-amber-900">Jam mesin hidup setelah breakdown {{ $teamName }}</h4>
                        <button type="button" class="add-spare-machine-row inline-flex items-center rounded-md border border-amber-300 bg-white px-3 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100" data-team="{{ $teamName }}" data-target="spare-machine-wrapper">
                            Tambah Baris
                        </button>
                    </div>
                    <div id="spare-machine-wrapper" class="spare-machine-wrapper mt-3 space-y-2" data-team="{{ $teamName }}">
                        @foreach ($spareRows as $idx => $spare)
                            @php $spareKey = 'row_' . $idx; @endphp
                            <div class="spare-machine-row grid grid-cols-1 items-center gap-2 rounded-lg border border-amber-100 bg-white p-3 md:grid-cols-5">
                                <label class="inline-flex items-center gap-2 text-xs font-medium text-amber-800">
                                    <input type="checkbox" name="spare_machines[{{ $spareKey }}][selected]" value="1"
                                        @checked(!empty($spare['selected'] ?? false))
                                        class="spare-machine-toggle rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                    <span>Aktif</span>
                                </label>
                                <input type="hidden" name="spare_machines[{{ $spareKey }}][team_name]" value="{{ $teamName }}">
                                <select name="spare_machines[{{ $spareKey }}][machine_name]"
                                    @disabled(empty($spare['selected'] ?? false))
                                    class="spare-machine-machine rounded-md border border-gray-300 px-3 py-2 text-sm @error('spare_machines.' . $spareKey . '.machine_name') border-red-400 bg-red-50 @enderror">
                                    <option value="">Pilih mesin</option>
                                    @foreach ($machineOptions as $machineOption)
                                        <option value="{{ $machineOption }}" @selected(($spare['machine_name'] ?? '') === $machineOption)>{{ $machineOption }}</option>
                                    @endforeach
                                </select>
                                <input type="time" name="spare_machines[{{ $spareKey }}][start_time]" value="{{ $spare['start_time'] ?? '' }}"
                                    @disabled(empty($spare['selected'] ?? false))
                                    class="spare-machine-start rounded-md border border-gray-300 px-3 py-2 text-sm @error('spare_machines.' . $spareKey . '.start_time') border-red-400 bg-red-50 @enderror">
                                <input type="time" name="spare_machines[{{ $spareKey }}][end_time]" value="{{ $spare['end_time'] ?? '' }}"
                                    @disabled(empty($spare['selected'] ?? false))
                                    class="spare-machine-end rounded-md border border-gray-300 px-3 py-2 text-sm @error('spare_machines.' . $spareKey . '.end_time') border-red-400 bg-red-50 @enderror">
                                <button type="button" class="remove-spare-machine-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-lg border border-violet-200 bg-violet-50 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <h4 class="text-sm font-semibold text-violet-900">Kondisi Lainnya {{ $teamName }}</h4>
                        <button type="button" class="add-other-condition-row inline-flex items-center rounded-md border border-violet-300 bg-white px-3 py-1 text-xs font-medium text-violet-700 hover:bg-violet-100" data-team="{{ $teamName }}" data-target="other-condition-wrapper">
                            Tambah Baris
                        </button>
                    </div>
                    <div id="other-condition-wrapper" class="other-condition-wrapper mt-3 space-y-2" data-team="{{ $teamName }}">
                        @foreach ($otherConditions as $idx => $condition)
                            @php $conditionKey = 'row_' . $idx; @endphp
                            <div class="other-condition-row grid grid-cols-1 gap-2 md:grid-cols-4">
                                <input type="hidden" name="other_conditions[{{ $conditionKey }}][team_name]" value="{{ $teamName }}">
                                <input type="text" name="other_conditions[{{ $conditionKey }}][reason]" value="{{ $condition['reason'] ?? '' }}" placeholder="Alasan"
                                    class="rounded-md border border-gray-300 px-3 py-2 text-sm @error('other_conditions.' . $conditionKey . '.reason') border-red-400 bg-red-50 @enderror">
                                <input type="time" name="other_conditions[{{ $conditionKey }}][start_time]" value="{{ $condition['start_time'] ?? '' }}"
                                    class="rounded-md border border-gray-300 px-3 py-2 text-sm @error('other_conditions.' . $conditionKey . '.start_time') border-red-400 bg-red-50 @enderror">
                                <input type="time" name="other_conditions[{{ $conditionKey }}][end_time]" value="{{ $condition['end_time'] ?? '' }}"
                                    class="rounded-md border border-gray-300 px-3 py-2 text-sm @error('other_conditions.' . $conditionKey . '.end_time') border-red-400 bg-red-50 @enderror">
                                <button type="button" class="remove-other-condition-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
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
            const addOtherConditionButtons = Array.from(document.querySelectorAll('.add-other-condition-row'));
            const otherConditionWrappers = Array.from(document.querySelectorAll('.other-condition-wrapper'));
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
                row.className = 'spare-machine-row grid grid-cols-1 md:grid-cols-5 gap-2 items-center rounded-lg border border-amber-100 bg-white p-3';

                const optionsHtml = ['<option value="">Pilih mesin</option>']
                    .concat(machineOptions.map(name => `<option value="${name}">${name}</option>`))
                    .join('');

                row.innerHTML = `
                    <label class="inline-flex items-center gap-2 text-xs font-medium text-amber-800">
                        <input type="checkbox" name="spare_machines[${nextIndex}][selected]" value="1" class="spare-machine-toggle rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <span>Aktif</span>
                    </label>
                    <input type="hidden" name="spare_machines[${nextIndex}][team_name]" value="${teamName}">
                    <select name="spare_machines[${nextIndex}][machine_name]" class="spare-machine-machine rounded-md border border-gray-300 px-3 py-2 text-sm">
                        ${optionsHtml}
                    </select>
                    <input type="time" name="spare_machines[${nextIndex}][start_time]" class="spare-machine-start rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <input type="time" name="spare_machines[${nextIndex}][end_time]" class="spare-machine-end rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <button type="button" class="remove-spare-machine-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                `;
                spareWrapper.appendChild(row);
                syncSpareRowState(row, false);
            }

            function appendOtherConditionRow(targetId, teamName) {
                const otherWrapper = document.getElementById(targetId);

                if (!otherWrapper) {
                    return;
                }

                const nextIndex = `row_${Date.now()}_${Math.floor(Math.random() * 1000)}`;
                const row = document.createElement('div');
                row.className = 'other-condition-row grid grid-cols-1 md:grid-cols-4 gap-2';

                row.innerHTML = `
                    <input type="hidden" name="other_conditions[${nextIndex}][team_name]" value="${teamName}">
                    <input type="text" name="other_conditions[${nextIndex}][reason]" placeholder="Alasan" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <input type="time" name="other_conditions[${nextIndex}][start_time]" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <input type="time" name="other_conditions[${nextIndex}][end_time]" class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <button type="button" class="remove-other-condition-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                `;
                otherWrapper.appendChild(row);
            }

            function syncSpareRowState(row, enabled) {
                row.querySelectorAll('select, input[type="time"]').forEach(field => {
                    field.disabled = !enabled;
                    if (!enabled) {
                        field.value = '';
                    }
                });
            }

            machineChecks.forEach(checkbox => {
                const targetId = checkbox.getAttribute('data-target');
                if (!targetId) {
                    return;
                }

                const target = document.getElementById(targetId);
                if (target) {
                    target.querySelectorAll('input[type="time"]').forEach(field => {
                        field.disabled = !checkbox.checked;
                    });
                }

                checkbox.addEventListener('change', function () {
                    const wrapper = document.getElementById(targetId);
                    if (!wrapper) {
                        return;
                    }

                    wrapper.querySelectorAll('input[type="time"]').forEach(field => {
                        field.disabled = !this.checked;
                        if (!this.checked) {
                            field.value = '';
                        }
                    });
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

            spareWrappers.forEach(wrapper => {
                wrapper.querySelectorAll('.spare-machine-row').forEach(row => {
                    const checkbox = row.querySelector('.spare-machine-toggle');

                    if (!checkbox) {
                        return;
                    }

                    syncSpareRowState(row, checkbox.checked);
                    checkbox.addEventListener('change', function () {
                        syncSpareRowState(row, this.checked);
                    });
                });
            });

            addSpareMachineButtons.forEach(button => {
                button.addEventListener('click', function () {
                    appendSpareMachineRow(button.getAttribute('data-target'), button.getAttribute('data-team'));
                });
            });

            addOtherConditionButtons.forEach(button => {
                button.addEventListener('click', function () {
                    appendOtherConditionRow(button.getAttribute('data-target'), button.getAttribute('data-team'));
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
                        const fields = rows[0].querySelectorAll('select, input:not([type="hidden"])');
                        fields.forEach(field => {
                            field.value = '';
                        });
                        return;
                    }

                    removeButton.closest('.spare-machine-row')?.remove();
                });
            });

            otherConditionWrappers.forEach(otherWrapper => {
                otherWrapper.addEventListener('click', function (event) {
                    const removeButton = event.target.closest('.remove-other-condition-row');

                    if (!removeButton) {
                        return;
                    }

                    const rows = otherWrapper.querySelectorAll('.other-condition-row');
                    if (rows.length <= 1) {
                        const fields = rows[0].querySelectorAll('input:not([type="hidden"]), textarea');
                        fields.forEach(field => {
                            field.value = '';
                        });
                        return;
                    }

                    removeButton.closest('.other-condition-row')?.remove();
                });
            });

            syncMachineInputs();
        });
    </script>
</x-layouts.app>
