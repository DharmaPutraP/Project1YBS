<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Informasi Proses Mesin Spintess']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Informasi Proses Mesin Spintess']); ?>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Informasi Proses Mesin Spintess</h1>
        <p class="mt-2 text-sm text-gray-600">Input per Tim 1 dan Tim 2, lalu data tersimpan bisa dilihat dan diedit di tabel bawah.</p>
    </div>

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Filter Data','class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Filter Data','class' => 'mb-6']); ?>
        <form method="GET" action="<?php echo e(route('process.informasi-proses-mesin-spintess')); ?>" class="flex flex-wrap items-end gap-3">
            <div>
                <label for="process_date_filter" class="mb-2 block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" id="process_date_filter" name="process_date" value="<?php echo e($processDate); ?>"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 md:w-64">
            </div>
            <div>
                <label for="office_filter" class="mb-2 block text-sm font-medium text-gray-700">Office</label>
                <select id="office_filter" name="office" <?php echo e($userOffice !== '' ? 'disabled' : ''); ?>

                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 md:w-48">
                    <?php $__currentLoopData = $officeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $office): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($office); ?>" <?php echo e($selectedOffice === $office ? 'selected' : ''); ?>><?php echo e($office); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php if($userOffice !== ''): ?>
                    <input type="hidden" name="office" value="<?php echo e($selectedOffice); ?>">
                <?php endif; ?>
            </div>
            <button type="submit"
                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                Tampilkan
            </button>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

    <div class="space-y-6">
        <?php $__currentLoopData = $teamOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teamName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $teamForm = $forms[$teamName] ?? [];
                $teamIndex = $loop->index + 1;
                $teamColor = $teamName === 'Tim 1' ? 'blue' : 'emerald';
                $spareRows = (array) ($teamForm['spare_rows'] ?? [['machine_name' => '', 'start_time' => '', 'end_time' => '']]);
                $otherRows = (array) ($teamForm['other_conditions'] ?? [['reason' => '', 'start_time' => '', 'end_time' => '']]);
            ?>

            <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => ''.e($teamName).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e($teamName).'']); ?>
                <form method="POST" action="<?php echo e(route('process.informasi-proses-mesin-spintess.store')); ?>" class="space-y-6 spintest-team-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="process_date" value="<?php echo e($processDate); ?>">
                    <input type="hidden" name="office" value="<?php echo e($selectedOffice); ?>">
                    <input type="hidden" name="input_team" value="<?php echo e($teamName); ?>">

                    <div>
                        <p class="mb-2 block text-sm font-medium text-gray-700">Anggota Tim (Checklist)</p>
                        <?php if(count($teamMembers) > 0): ?>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                                <?php $__currentLoopData = $teamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="inline-flex items-center gap-2 rounded-lg border border-<?php echo e($teamColor); ?>-100 bg-white px-3 py-2 text-sm text-gray-700">
                                        <input type="checkbox" name="team_members[]" value="<?php echo e($member); ?>"
                                            <?php echo e(in_array($member, (array) ($teamForm['team_members'] ?? []), true) ? 'checked' : ''); ?>

                                            class="rounded border-gray-300 text-<?php echo e($teamColor); ?>-600 focus:ring-<?php echo e($teamColor); ?>-500">
                                        <span><?php echo e($member); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Daftar anggota sampel boy belum tersedia untuk office ini.</p>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Jam Mulai *</label>
                            <input type="time" name="team_start_time" value="<?php echo e($teamForm['start_time'] ?? ''); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm transition focus:outline-none focus:ring-2 focus:ring-<?php echo e($teamColor); ?>-500">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Jam Akhir *</label>
                            <input type="time" name="team_end_time" value="<?php echo e($teamForm['end_time'] ?? ''); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm transition focus:outline-none focus:ring-2 focus:ring-<?php echo e($teamColor); ?>-500">
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h3 class="text-sm font-semibold text-slate-900">Input Mesin <?php echo e($teamName); ?></h3>
                        <p class="mt-1 text-xs text-slate-600">Pilih mesin aktif untuk <?php echo e($teamName); ?> lalu isi jam produksi.</p>

                        <div class="mt-4 space-y-4">
                            <?php $__currentLoopData = $machineGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $groupMachines): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <details class="rounded-lg border border-slate-200 bg-white p-3" <?php echo e($loop->first ? 'open' : ''); ?>>
                                    <summary class="cursor-pointer text-sm font-semibold text-slate-800"><?php echo e($groupName); ?></summary>
                                    <div class="mt-3 space-y-2">
                                        <?php $__currentLoopData = $groupMachines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machineName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $row = collect($teamForm['machines'][$groupName] ?? [])->firstWhere('machine_name', $machineName);
                                                $machineKey = 'm_' . $teamIndex . '_' . $loop->parent->index . '_' . $loop->index;
                                            ?>
                                            <div class="rounded-md border border-slate-200 bg-slate-50 p-3">
                                                <div class="grid grid-cols-1 gap-2 md:grid-cols-4 md:items-center">
                                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 md:col-span-2">
                                                        <input type="checkbox"
                                                            name="machines[<?php echo e($machineKey); ?>][selected]"
                                                            value="1"
                                                            <?php echo e(!empty($row['selected']) ? 'checked' : ''); ?>

                                                            class="machine-select rounded border-gray-300 text-<?php echo e($teamColor); ?>-600 focus:ring-<?php echo e($teamColor); ?>-500"
                                                            data-target="time-<?php echo e($machineKey); ?>">
                                                        <span><?php echo e($machineName); ?></span>
                                                    </label>

                                                    <input type="hidden" name="machines[<?php echo e($machineKey); ?>][machine_group]" value="<?php echo e($groupName); ?>">
                                                    <input type="hidden" name="machines[<?php echo e($machineKey); ?>][machine_name]" value="<?php echo e($machineName); ?>">

                                                    <div id="time-<?php echo e($machineKey); ?>" class="grid grid-cols-1 gap-2 md:col-span-2 md:grid-cols-2">
                                                        <input type="time" name="machines[<?php echo e($machineKey); ?>][start_time]" value="<?php echo e($row['start_time'] ?? ''); ?>"
                                                            class="machine-time-input w-full rounded-md border border-gray-300 px-3 py-1.5 text-sm"
                                                            <?php echo e(!empty($row['selected']) ? '' : 'disabled'); ?>>
                                                        <input type="time" name="machines[<?php echo e($machineKey); ?>][end_time]" value="<?php echo e($row['end_time'] ?? ''); ?>"
                                                            class="machine-time-input w-full rounded-md border border-gray-300 px-3 py-1.5 text-sm"
                                                            <?php echo e(!empty($row['selected']) ? '' : 'disabled'); ?>>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </details>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-semibold text-amber-900">Jam mesin hidup setelah breakdown <?php echo e($teamName); ?></h4>
                            <button type="button"
                                class="add-spare-row inline-flex items-center rounded-md border border-amber-300 bg-white px-3 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100"
                                data-target="spare-wrapper-<?php echo e($teamIndex); ?>">
                                Tambah Baris
                            </button>
                        </div>
                        <div id="spare-wrapper-<?php echo e($teamIndex); ?>" class="mt-3 space-y-2">
                            <?php $__currentLoopData = $spareRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spareIndex => $spare): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="spare-row grid grid-cols-1 gap-2 md:grid-cols-4">
                                    <select name="spare_machines[<?php echo e($spareIndex); ?>][machine_name]"
                                        class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                                        <option value="">Pilih mesin</option>
                                        <?php $__currentLoopData = $allowedMachines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($option); ?>" <?php echo e(strtoupper((string) ($spare['machine_name'] ?? '')) === $option ? 'selected' : ''); ?>><?php echo e($option); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <input type="time" name="spare_machines[<?php echo e($spareIndex); ?>][start_time]" value="<?php echo e($spare['start_time'] ?? ''); ?>"
                                        class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    <input type="time" name="spare_machines[<?php echo e($spareIndex); ?>][end_time]" value="<?php echo e($spare['end_time'] ?? ''); ?>"
                                        class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    <button type="button" class="remove-spare-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">
                                        Hapus Baris
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="rounded-xl border border-violet-200 bg-violet-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-semibold text-violet-900">Kondisi Lainnya <?php echo e($teamName); ?></h4>
                            <button type="button"
                                class="add-condition-row inline-flex items-center rounded-md border border-violet-300 bg-white px-3 py-1 text-xs font-medium text-violet-700 hover:bg-violet-100"
                                data-target="condition-wrapper-<?php echo e($teamIndex); ?>">
                                Tambah Baris
                            </button>
                        </div>
                        <div id="condition-wrapper-<?php echo e($teamIndex); ?>" class="mt-3 space-y-2">
                            <?php $__currentLoopData = $otherRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conditionIndex => $condition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="condition-row grid grid-cols-1 gap-2 md:grid-cols-4">
                                    <input type="text" name="other_conditions[<?php echo e($conditionIndex); ?>][reason]" value="<?php echo e($condition['reason'] ?? ''); ?>"
                                        placeholder="Alasan"
                                        class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    <input type="time" name="other_conditions[<?php echo e($conditionIndex); ?>][start_time]" value="<?php echo e($condition['start_time'] ?? ''); ?>"
                                        class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    <input type="time" name="other_conditions[<?php echo e($conditionIndex); ?>][end_time]" value="<?php echo e($condition['end_time'] ?? ''); ?>"
                                        class="rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    <button type="button" class="remove-condition-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">
                                        Hapus Baris
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="inline-flex items-center rounded-lg bg-<?php echo e($teamColor); ?>-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-<?php echo e($teamColor); ?>-700">
                            Simpan Informasi Proses <?php echo e($teamName); ?>

                        </button>
                    </div>
                </form>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Data Informasi Proses Spintess','class' => 'mt-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Informasi Proses Spintess','class' => 'mt-6']); ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Office</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Tim</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Proses</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Total Mesin</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $listRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $key = $row->process_date->format('Y-m-d') . '|' . $row->team_name;
                            $machineCount = (int) ($machineCounts[$key]->total ?? 0);
                        ?>
                        <tr>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row->process_date->format('d/m/Y')); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row->office); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row->team_name); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e(substr((string) $row->start_time, 0, 5)); ?> - <?php echo e(substr((string) $row->end_time, 0, 5)); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($machineCount); ?></td>
                            <td class="px-3 py-2">
                                <a href="<?php echo e(route('process.informasi-proses-mesin-spintess', ['process_date' => $row->process_date->format('Y-m-d'), 'office' => $row->office, 'edit_team' => $row->team_name])); ?>"
                                    class="inline-flex items-center rounded-md bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-200">
                                    Lihat / Edit
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-3 py-4 text-center text-gray-500">Belum ada data spintess tersimpan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.machine-select').forEach(function (checkbox) {
                const targetId = checkbox.getAttribute('data-target');
                const wrapper = document.getElementById(targetId);
                if (!wrapper) {
                    return;
                }

                const toggle = function () {
                    wrapper.querySelectorAll('.machine-time-input').forEach(function (input) {
                        input.disabled = !checkbox.checked;
                        if (!checkbox.checked) {
                            input.value = '';
                        }
                    });
                };

                checkbox.addEventListener('change', toggle);
                toggle();
            });

            document.querySelectorAll('.add-spare-row').forEach(function (button) {
                button.addEventListener('click', function () {
                    const wrapper = document.getElementById(button.getAttribute('data-target'));
                    if (!wrapper) {
                        return;
                    }

                    const index = Date.now().toString();
                    const options = <?php echo json_encode($allowedMachines); ?>;
                    const optionHtml = ['<option value="">Pilih mesin</option>'].concat(options.map(function (name) {
                        return '<option value="' + name + '">' + name + '</option>';
                    })).join('');

                    const row = document.createElement('div');
                    row.className = 'spare-row grid grid-cols-1 gap-2 md:grid-cols-4';
                    row.innerHTML =
                        '<select name="spare_machines[' + index + '][machine_name]" class="rounded-md border border-gray-300 px-3 py-2 text-sm">' + optionHtml + '</select>' +
                        '<input type="time" name="spare_machines[' + index + '][start_time]" class="rounded-md border border-gray-300 px-3 py-2 text-sm">' +
                        '<input type="time" name="spare_machines[' + index + '][end_time]" class="rounded-md border border-gray-300 px-3 py-2 text-sm">' +
                        '<button type="button" class="remove-spare-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>';
                    wrapper.appendChild(row);
                });
            });

            document.querySelectorAll('.add-condition-row').forEach(function (button) {
                button.addEventListener('click', function () {
                    const wrapper = document.getElementById(button.getAttribute('data-target'));
                    if (!wrapper) {
                        return;
                    }

                    const index = Date.now().toString();
                    const row = document.createElement('div');
                    row.className = 'condition-row grid grid-cols-1 gap-2 md:grid-cols-4';
                    row.innerHTML =
                        '<input type="text" name="other_conditions[' + index + '][reason]" placeholder="Alasan" class="rounded-md border border-gray-300 px-3 py-2 text-sm">' +
                        '<input type="time" name="other_conditions[' + index + '][start_time]" class="rounded-md border border-gray-300 px-3 py-2 text-sm">' +
                        '<input type="time" name="other_conditions[' + index + '][end_time]" class="rounded-md border border-gray-300 px-3 py-2 text-sm">' +
                        '<button type="button" class="remove-condition-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>';
                    wrapper.appendChild(row);
                });
            });

            document.addEventListener('click', function (event) {
                const removeSpare = event.target.closest('.remove-spare-row');
                if (removeSpare) {
                    const wrapper = removeSpare.closest('[id^="spare-wrapper-"]');
                    const rows = wrapper ? wrapper.querySelectorAll('.spare-row') : [];
                    if (rows.length <= 1) {
                        rows[0].querySelectorAll('select, input').forEach(function (input) {
                            input.value = '';
                        });
                        return;
                    }
                    removeSpare.closest('.spare-row')?.remove();
                }

                const removeCondition = event.target.closest('.remove-condition-row');
                if (removeCondition) {
                    const wrapper = removeCondition.closest('[id^="condition-wrapper-"]');
                    const rows = wrapper ? wrapper.querySelectorAll('.condition-row') : [];
                    if (rows.length <= 1) {
                        rows[0].querySelectorAll('input').forEach(function (input) {
                            input.value = '';
                        });
                        return;
                    }
                    removeCondition.closest('.condition-row')?.remove();
                }
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/spintest-process-info.blade.php ENDPATH**/ ?>