<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Informasi Proses Mesin Spintest']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Informasi Proses Mesin Spintest']); ?>
    <?php
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
    ?>

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Informasi Proses Mesin Spintest</h1>
        <p class="mt-2 text-sm text-gray-600">Form monitoring proses spintest USB dan Oil Foss per tanggal untuk Tim 1 dan Tim 2.</p>
    </div>

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Form Informasi Proses Mesin Spintest']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Form Informasi Proses Mesin Spintest']); ?>
        <form action="<?php echo e(route('process.spintest.store')); ?>" method="POST" class="space-y-6" id="spintest-form">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="input_team" id="input_team_field" value="">

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="process_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="process_date" name="process_date"
                        value="<?php echo e(old('process_date', now()->format('Y-m-d'))); ?>"
                        class="w-full md:w-72 px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['process_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['process_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="rounded-xl border border-blue-200 bg-blue-50 p-5 space-y-4" id="team-1-section">
                    <h2 class="text-lg font-semibold text-blue-900">Tim 1</h2>

                    <div>
                        <p class="block text-sm font-medium text-gray-700 mb-2">Anggota Tim (Checklist)</p>
                        <?php if(count($teamMembers) > 0): ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <?php $__currentLoopData = $teamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="inline-flex items-center gap-2 rounded-lg border border-blue-100 bg-white px-3 py-2 text-sm text-gray-700">
                                        <input type="checkbox" name="team_1_members[]" value="<?php echo e($member); ?>" data-team="1"
                                            <?php if(in_array($member, old('team_1_members', []), true)): echo 'checked'; endif; ?>
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span><?php echo e($member); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Daftar anggota belum tersedia untuk office Anda.</p>
                        <?php endif; ?>
                        <?php $__errorArgs = ['team_1_members'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="team_1_start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_1_start_time" name="team_1_start_time"
                                value="<?php echo e(old('team_1_start_time')); ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['team_1_start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['team_1_start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="team_1_end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Akhir <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_1_end_time" name="team_1_end_time"
                                value="<?php echo e(old('team_1_end_time')); ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['team_1_end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['team_1_end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <?php if($canManageMachineData && !empty($machineGroups)): ?>
                        <div class="rounded-lg border border-slate-200 bg-white p-4 space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Input Mesin Tim 1</h3>
                                <p class="mt-1 text-xs text-slate-600">Pilih mesin aktif untuk Tim 1 lalu isi jam produksi.</p>
                            </div>

                            <?php $__currentLoopData = $machineGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $machines): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                    <button type="button"
                                        class="machine-group-toggle flex w-full items-center justify-between text-left"
                                        data-target="machine-group-t1-<?php echo e($loop->index); ?>">
                                        <h4 class="text-sm font-semibold text-slate-800"><?php echo e($groupName); ?></h4>
                                        <span class="machine-group-arrow inline-block text-lg font-bold text-slate-500 transition-transform">&gt;</span>
                                    </button>

                                    <div id="machine-group-t1-<?php echo e($loop->index); ?>" class="mt-3 space-y-3 hidden">
                                        <?php $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machineName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $machineKey = 't1_machine_' . $loop->parent->index . '_' . $loop->index;
                                                $isChecked = old('machines.' . $machineKey . '.selected');
                                            ?>

                                            <div class="rounded-md border border-slate-200 bg-white p-3">
                                                <div class="flex flex-wrap items-center gap-3">
                                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                                        <input type="checkbox"
                                                            name="machines[<?php echo e($machineKey); ?>][selected]"
                                                            value="1"
                                                            class="machine-select rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                            data-target="machine-time-<?php echo e($machineKey); ?>"
                                                            <?php if($isChecked): echo 'checked'; endif; ?>>
                                                        <span><?php echo e($machineName); ?></span>
                                                    </label>

                                                    <input type="hidden" name="machines[<?php echo e($machineKey); ?>][machine_name]" value="<?php echo e($machineName); ?>">
                                                    <input type="hidden" name="machines[<?php echo e($machineKey); ?>][machine_group]" value="<?php echo e($groupName); ?>">
                                                    <input type="hidden" name="machines[<?php echo e($machineKey); ?>][team_name]" value="Tim 1">

                                                    <div id="machine-time-<?php echo e($machineKey); ?>" class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:w-auto">
                                                        <input type="time" name="machines[<?php echo e($machineKey); ?>][start_time]"
                                                            value="<?php echo e(old('machines.' . $machineKey . '.start_time')); ?>"
                                                            class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['machines.' . $machineKey . '.start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                            <?php echo e($isChecked ? '' : 'disabled'); ?>>
                                                        <input type="time" name="machines[<?php echo e($machineKey); ?>][end_time]"
                                                            value="<?php echo e(old('machines.' . $machineKey . '.end_time')); ?>"
                                                            class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['machines.' . $machineKey . '.end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                            <?php echo e($isChecked ? '' : 'disabled'); ?>>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-semibold text-amber-900">Jam mesin hidup setelah breakdown Tim 1</h4>
                            <button type="button" class="add-spare-machine-row inline-flex items-center rounded-md border border-amber-300 bg-white px-3 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100"
                                data-team="Tim 1" data-target="spare-machine-wrapper-team1">
                                Tambah Baris
                            </button>
                        </div>
                        <div id="spare-machine-wrapper-team1" class="spare-machine-wrapper mt-3 space-y-2" data-team="Tim 1">
                            <?php $__currentLoopData = $spareRowsTeam1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $spare): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $spareKey = 't1_' . $idx; ?>
                                <div class="spare-machine-row grid grid-cols-1 md:grid-cols-5 gap-2 items-center rounded-lg border border-amber-100 bg-white p-3">
                                    <label class="inline-flex items-center gap-2 text-xs font-medium text-amber-800">
                                        <input type="checkbox" name="spare_machines[<?php echo e($spareKey); ?>][selected]" value="1"
                                            <?php if(!empty($spare['selected'] ?? false)): echo 'checked'; endif; ?>
                                            class="spare-machine-toggle rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <span>Aktif</span>
                                    </label>
                                    <input type="hidden" name="spare_machines[<?php echo e($spareKey); ?>][team_name]" value="Tim 1">
                                    <select name="spare_machines[<?php echo e($spareKey); ?>][machine_name]"
                                        <?php if(empty($spare['selected'] ?? false)): echo 'disabled'; endif; ?>
                                        class="spare-machine-machine px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.machine_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Pilih mesin</option>
                                        <?php $__currentLoopData = $machineOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machineOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($machineOption); ?>" <?php if(($spare['machine_name'] ?? '') === $machineOption): echo 'selected'; endif; ?>><?php echo e($machineOption); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <input type="time" name="spare_machines[<?php echo e($spareKey); ?>][start_time]" value="<?php echo e($spare['start_time'] ?? ''); ?>"
                                        <?php if(empty($spare['selected'] ?? false)): echo 'disabled'; endif; ?>
                                        class="spare-machine-start px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <input type="time" name="spare_machines[<?php echo e($spareKey); ?>][end_time]" value="<?php echo e($spare['end_time'] ?? ''); ?>"
                                        <?php if(empty($spare['selected'] ?? false)): echo 'disabled'; endif; ?>
                                        class="spare-machine-end px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <button type="button" class="remove-spare-machine-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <?php $__currentLoopData = $otherConditionsTeam1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $condition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $conditionKey = 't1_' . $idx; ?>
                                <div class="other-condition-row grid grid-cols-1 md:grid-cols-4 gap-2">
                                    <input type="hidden" name="other_conditions[<?php echo e($conditionKey); ?>][team_name]" value="Tim 1">
                                    <input type="text" name="other_conditions[<?php echo e($conditionKey); ?>][reason]" value="<?php echo e($condition['reason'] ?? ''); ?>"
                                        placeholder="Alasan"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['other_conditions.' . $conditionKey . '.reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <input type="time" name="other_conditions[<?php echo e($conditionKey); ?>][start_time]" value="<?php echo e($condition['start_time'] ?? ''); ?>"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['other_conditions.' . $conditionKey . '.start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <input type="time" name="other_conditions[<?php echo e($conditionKey); ?>][end_time]" value="<?php echo e($condition['end_time'] ?? ''); ?>"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['other_conditions.' . $conditionKey . '.end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <button type="button" class="remove-other-condition-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="button" data-input-team="Tim 1" class="submit-team-button inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan Informasi Proses Spintest Tim 1
                        </button>
                    </div>
                </div>

                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 space-y-4" id="team-2-section">
                    <h2 class="text-lg font-semibold text-emerald-900">Tim 2</h2>

                    <div>
                        <p class="block text-sm font-medium text-gray-700 mb-2">Anggota Tim (Checklist)</p>
                        <?php if(count($teamMembers) > 0): ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <?php $__currentLoopData = $teamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="inline-flex items-center gap-2 rounded-lg border border-emerald-100 bg-white px-3 py-2 text-sm text-gray-700">
                                        <input type="checkbox" name="team_2_members[]" value="<?php echo e($member); ?>" data-team="2"
                                            <?php if(in_array($member, old('team_2_members', []), true)): echo 'checked'; endif; ?>
                                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                        <span><?php echo e($member); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Daftar anggota belum tersedia untuk office Anda.</p>
                        <?php endif; ?>
                        <?php $__errorArgs = ['team_2_members'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="team_2_start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_2_start_time" name="team_2_start_time"
                                value="<?php echo e(old('team_2_start_time')); ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-500 <?php $__errorArgs = ['team_2_start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['team_2_start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="team_2_end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Akhir <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_2_end_time" name="team_2_end_time"
                                value="<?php echo e(old('team_2_end_time')); ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-500 <?php $__errorArgs = ['team_2_end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['team_2_end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <?php if($canManageMachineData && !empty($machineGroups)): ?>
                        <div class="rounded-lg border border-slate-200 bg-white p-4 space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Input Mesin Tim 2</h3>
                                <p class="mt-1 text-xs text-slate-600">Pilih mesin aktif untuk Tim 2 lalu isi jam produksi.</p>
                            </div>

                            <?php $__currentLoopData = $machineGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $machines): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                    <button type="button"
                                        class="machine-group-toggle flex w-full items-center justify-between text-left"
                                        data-target="machine-group-t2-<?php echo e($loop->index); ?>">
                                        <h4 class="text-sm font-semibold text-slate-800"><?php echo e($groupName); ?></h4>
                                        <span class="machine-group-arrow inline-block text-lg font-bold text-slate-500 transition-transform">&gt;</span>
                                    </button>

                                    <div id="machine-group-t2-<?php echo e($loop->index); ?>" class="mt-3 space-y-3 hidden">
                                        <?php $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machineName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $machineKey = 't2_machine_' . $loop->parent->index . '_' . $loop->index;
                                                $isChecked = old('machines.' . $machineKey . '.selected');
                                            ?>

                                            <div class="rounded-md border border-slate-200 bg-white p-3">
                                                <div class="flex flex-wrap items-center gap-3">
                                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                                        <input type="checkbox"
                                                            name="machines[<?php echo e($machineKey); ?>][selected]"
                                                            value="1"
                                                            class="machine-select rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                                            data-target="machine-time-<?php echo e($machineKey); ?>"
                                                            <?php if($isChecked): echo 'checked'; endif; ?>>
                                                        <span><?php echo e($machineName); ?></span>
                                                    </label>

                                                    <input type="hidden" name="machines[<?php echo e($machineKey); ?>][machine_name]" value="<?php echo e($machineName); ?>">
                                                    <input type="hidden" name="machines[<?php echo e($machineKey); ?>][machine_group]" value="<?php echo e($groupName); ?>">
                                                    <input type="hidden" name="machines[<?php echo e($machineKey); ?>][team_name]" value="Tim 2">

                                                    <div id="machine-time-<?php echo e($machineKey); ?>" class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:w-auto">
                                                        <input type="time" name="machines[<?php echo e($machineKey); ?>][start_time]"
                                                            value="<?php echo e(old('machines.' . $machineKey . '.start_time')); ?>"
                                                            class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['machines.' . $machineKey . '.start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                            <?php echo e($isChecked ? '' : 'disabled'); ?>>
                                                        <input type="time" name="machines[<?php echo e($machineKey); ?>][end_time]"
                                                            value="<?php echo e(old('machines.' . $machineKey . '.end_time')); ?>"
                                                            class="machine-time-input w-full px-3 py-1.5 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['machines.' . $machineKey . '.end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                            <?php echo e($isChecked ? '' : 'disabled'); ?>>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-semibold text-amber-900">Jam mesin hidup setelah breakdown Tim 2</h4>
                            <button type="button" class="add-spare-machine-row inline-flex items-center rounded-md border border-amber-300 bg-white px-3 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100"
                                data-team="Tim 2" data-target="spare-machine-wrapper-team2">
                                Tambah Baris
                            </button>
                        </div>
                        <div id="spare-machine-wrapper-team2" class="spare-machine-wrapper mt-3 space-y-2" data-team="Tim 2">
                            <?php $__currentLoopData = $spareRowsTeam2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $spare): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $spareKey = 't2_' . $idx; ?>
                                <div class="spare-machine-row grid grid-cols-1 md:grid-cols-5 gap-2 items-center rounded-lg border border-amber-100 bg-white p-3">
                                    <label class="inline-flex items-center gap-2 text-xs font-medium text-amber-800">
                                        <input type="checkbox" name="spare_machines[<?php echo e($spareKey); ?>][selected]" value="1"
                                            <?php if(!empty($spare['selected'] ?? false)): echo 'checked'; endif; ?>
                                            class="spare-machine-toggle rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <span>Aktif</span>
                                    </label>
                                    <input type="hidden" name="spare_machines[<?php echo e($spareKey); ?>][team_name]" value="Tim 2">
                                    <select name="spare_machines[<?php echo e($spareKey); ?>][machine_name]"
                                        <?php if(empty($spare['selected'] ?? false)): echo 'disabled'; endif; ?>
                                        class="spare-machine-machine px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.machine_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Pilih mesin</option>
                                        <?php $__currentLoopData = $machineOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machineOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($machineOption); ?>" <?php if(($spare['machine_name'] ?? '') === $machineOption): echo 'selected'; endif; ?>><?php echo e($machineOption); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <input type="time" name="spare_machines[<?php echo e($spareKey); ?>][start_time]" value="<?php echo e($spare['start_time'] ?? ''); ?>"
                                        <?php if(empty($spare['selected'] ?? false)): echo 'disabled'; endif; ?>
                                        class="spare-machine-start px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <input type="time" name="spare_machines[<?php echo e($spareKey); ?>][end_time]" value="<?php echo e($spare['end_time'] ?? ''); ?>"
                                        <?php if(empty($spare['selected'] ?? false)): echo 'disabled'; endif; ?>
                                        class="spare-machine-end px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <button type="button" class="remove-spare-machine-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <?php $__currentLoopData = $otherConditionsTeam2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $condition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $conditionKey = 't2_' . $idx; ?>
                                <div class="other-condition-row grid grid-cols-1 md:grid-cols-4 gap-2">
                                    <input type="hidden" name="other_conditions[<?php echo e($conditionKey); ?>][team_name]" value="Tim 2">
                                    <input type="text" name="other_conditions[<?php echo e($conditionKey); ?>][reason]" value="<?php echo e($condition['reason'] ?? ''); ?>"
                                        placeholder="Alasan"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['other_conditions.' . $conditionKey . '.reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <input type="time" name="other_conditions[<?php echo e($conditionKey); ?>][start_time]" value="<?php echo e($condition['start_time'] ?? ''); ?>"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['other_conditions.' . $conditionKey . '.start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <input type="time" name="other_conditions[<?php echo e($conditionKey); ?>][end_time]" value="<?php echo e($condition['end_time'] ?? ''); ?>"
                                        class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['other_conditions.' . $conditionKey . '.end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <button type="button" class="remove-other-condition-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="button" data-input-team="Tim 2" class="submit-team-button inline-flex items-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Simpan Informasi Proses Spintest Tim 2
                        </button>
                    </div>
                </div>
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

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Data Informasi Proses Spintest','class' => 'mt-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Informasi Proses Spintest','class' => 'mt-6']); ?>
        <form method="GET" action="<?php echo e(route('process.spintest.index')); ?>" class="mb-4 flex flex-wrap items-end gap-3">
            <div>
                <label for="office" class="block text-xs font-medium text-gray-700 mb-1">Filter Office</label>
                <?php if(auth()->user()->office): ?>
                    <input type="text" value="<?php echo e(auth()->user()->office); ?>" disabled
                        class="w-36 px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 text-gray-700">
                    <input type="hidden" name="office" value="<?php echo e(auth()->user()->office); ?>">
                <?php else: ?>
                    <select id="office" name="office" class="w-36 px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="all" <?php if(($officeFilter ?? 'all') === 'all'): echo 'selected'; endif; ?>>Semua</option>
                        <?php $__currentLoopData = $officeOptions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $officeOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($officeOption); ?>" <?php if(($officeFilter ?? 'all') === $officeOption): echo 'selected'; endif; ?>><?php echo e($officeOption); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-3 py-2 text-sm text-gray-700"><?php echo e($record['process_date']); ?></td>
                            <td class="px-3 py-2 text-sm text-gray-700"><?php echo e($record['office']); ?></td>
                            <td class="px-3 py-2 text-sm text-gray-700"><?php echo e($record['input_team']); ?></td>
                            <td class="px-3 py-2 text-sm text-gray-700"><?php echo e($record['machines_summary']); ?></td>
                            <td class="px-3 py-2 text-sm">
                                <div class="inline-flex items-center gap-2">
                                    <a href="javascript:void(0)"
                                        class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 hover:bg-indigo-100">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-3 py-4 text-center text-sm text-gray-500">Belum ada data proses spintest.</td>
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
            const addSpareButtons = Array.from(document.querySelectorAll('.add-spare-machine-row'));
            const spareWrappers = Array.from(document.querySelectorAll('.spare-machine-wrapper'));
            const addConditionButtons = Array.from(document.querySelectorAll('.add-other-condition-row'));
            const conditionWrappers = Array.from(document.querySelectorAll('.other-condition-wrapper'));
            const machineChecks = Array.from(document.querySelectorAll('.machine-select'));
            const machineGroupToggles = Array.from(document.querySelectorAll('.machine-group-toggle'));
            const submitButtons = Array.from(document.querySelectorAll('.submit-team-button'));
            const formElement = document.getElementById('spintest-form');
            const inputTeamField = document.getElementById('input_team_field');
            const machineOptions = <?php echo json_encode($machineOptions); ?>;

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
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/spintest-index.blade.php ENDPATH**/ ?>