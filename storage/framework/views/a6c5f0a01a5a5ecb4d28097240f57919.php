<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Informasi Proses']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Informasi Proses']); ?>
    <?php
        $canManageTeamMeta = (bool) ($canManageTeamMeta ?? false);
        $canManageMachineData = (bool) ($canManageMachineData ?? false);
        $oldSpareRows = old('spare_machines', []);
        $oldOtherConditions = old('other_conditions', []);
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

        $machineOptions = collect($machineGroups)->flatten()->values()->all();
        $teamOptions = ['Tim 1', 'Tim 2'];
    ?>

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Informasi Proses</h1>
        <p class="mt-2 text-sm text-gray-600">Form monitoring proses per tanggal untuk Tim 1 dan Tim 2.</p>
    </div>

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Form Informasi Proses']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Form Informasi Proses']); ?>
        <form action="<?php echo e(route('process.store')); ?>" method="POST" class="space-y-6" id="process-form">
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
                    <?php if(!$canManageTeamMeta): ?>
                        <p class="text-xs text-slate-600">Checklist dan jam proses hanya bisa dilihat.</p>
                    <?php endif; ?>

                        <div>
                            <p class="block text-sm font-medium text-gray-700 mb-2">Anggota Tim (Checklist)</p>

                            <?php if(count($teamMembers) > 0): ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <?php $__currentLoopData = $teamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="inline-flex items-center gap-2 rounded-lg border border-blue-100 bg-white px-3 py-2 text-sm text-gray-700">
                                            <input type="checkbox" name="team_1_members[]" value="<?php echo e($member); ?>" data-team="1"
                                                <?php if(in_array($member, old('team_1_members', []), true)): echo 'checked'; endif; ?>
                                                <?php if(!$canManageTeamMeta): echo 'disabled'; endif; ?>
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
                                    <?php if(!$canManageTeamMeta): echo 'disabled'; endif; ?>
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
                                    <?php if(!$canManageTeamMeta): echo 'disabled'; endif; ?>
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

                    <?php if($canManageMachineData): ?>
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
                                    <?php
                                        $spareKey = 't1_' . $idx;
                                    ?>
                                    <div class="spare-machine-row grid grid-cols-1 md:grid-cols-4 gap-2">
                                        <input type="hidden" name="spare_machines[<?php echo e($spareKey); ?>][team_name]" value="Tim 1">
                                        <select name="spare_machines[<?php echo e($spareKey); ?>][machine_name]"
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.machine_name'];
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
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <input type="time" name="spare_machines[<?php echo e($spareKey); ?>][end_time]" value="<?php echo e($spare['end_time'] ?? ''); ?>"
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.end_time'];
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
                                    <?php
                                        $conditionKey = 't1_' . $idx;
                                    ?>
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
                    </div>
                    <?php endif; ?>

                    <div class="pt-2">
                        <?php if($canManageTeamMeta || $canManageMachineData): ?>
                            <button type="button" data-input-team="Tim 1" class="submit-team-button inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <?php if($canManageTeamMeta && $canManageMachineData): ?>
                                    Simpan Informasi Proses Tim 1
                                <?php elseif($canManageTeamMeta): ?>
                                    Simpan Checklist &amp; Shift Tim 1
                                <?php else: ?>
                                    Simpan Detail Mesin Tim 1
                                <?php endif; ?>
                            </button>
                        <?php endif; ?>
                    </div>
                    
                </div>

                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 space-y-4" id="team-2-section">
                    <h2 class="text-lg font-semibold text-emerald-900">Tim 2</h2>
                    <?php if(!$canManageTeamMeta): ?>
                        <p class="text-xs text-slate-600">Checklist dan jam proses hanya bisa dilihat.</p>
                    <?php endif; ?>

                        <div>
                            <p class="block text-sm font-medium text-gray-700 mb-2">Anggota Tim (Checklist)</p>

                            <?php if(count($teamMembers) > 0): ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <?php $__currentLoopData = $teamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="inline-flex items-center gap-2 rounded-lg border border-emerald-100 bg-white px-3 py-2 text-sm text-gray-700">
                                            <input type="checkbox" name="team_2_members[]" value="<?php echo e($member); ?>" data-team="2"
                                                <?php if(in_array($member, old('team_2_members', []), true)): echo 'checked'; endif; ?>
                                                <?php if(!$canManageTeamMeta): echo 'disabled'; endif; ?>
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
                                    <?php if(!$canManageTeamMeta): echo 'disabled'; endif; ?>
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
                                    <?php if(!$canManageTeamMeta): echo 'disabled'; endif; ?>
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

                    <?php if($canManageMachineData): ?>
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
                                    <?php
                                        $spareKey = 't2_' . $idx;
                                    ?>
                                    <div class="spare-machine-row grid grid-cols-1 md:grid-cols-4 gap-2">
                                        <input type="hidden" name="spare_machines[<?php echo e($spareKey); ?>][team_name]" value="Tim 2">
                                        <select name="spare_machines[<?php echo e($spareKey); ?>][machine_name]"
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.machine_name'];
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
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <input type="time" name="spare_machines[<?php echo e($spareKey); ?>][end_time]" value="<?php echo e($spare['end_time'] ?? ''); ?>"
                                            class="px-3 py-2 border border-gray-300 rounded-md text-sm <?php $__errorArgs = ['spare_machines.' . $spareKey . '.end_time'];
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
                                    <?php
                                        $conditionKey = 't2_' . $idx;
                                    ?>
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
                    </div>
                    <?php endif; ?>

                    <div class="pt-2">
                        <?php if($canManageTeamMeta || $canManageMachineData): ?>
                            <button type="button" data-input-team="Tim 2" class="submit-team-button inline-flex items-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                <?php if($canManageTeamMeta && $canManageMachineData): ?>
                                    Simpan Informasi Proses Tim 2
                                <?php elseif($canManageTeamMeta): ?>
                                    Simpan Checklist &amp; Shift Tim 2
                                <?php else: ?>
                                    Simpan Detail Mesin Tim 2
                                <?php endif; ?>
                            </button>
                        <?php endif; ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Data Informasi Proses','class' => 'mt-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Informasi Proses','class' => 'mt-6']); ?>
        <form method="GET" action="<?php echo e(route('process.index')); ?>" class="mb-4 flex flex-wrap items-end gap-3">
            <div>
                <label for="office" class="block text-xs font-medium text-gray-700 mb-1">Filter Office</label>
                <?php if(auth()->user()->office): ?>
                    <input type="text" value="<?php echo e(auth()->user()->office); ?>" disabled
                        class="w-36 px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100 text-gray-700">
                    <input type="hidden" name="office" value="<?php echo e(auth()->user()->office); ?>">
                <?php else: ?>
                    <select id="office" name="office" class="w-36 px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="all" <?php echo e(($officeFilter ?? 'all') === 'all' ? 'selected' : ''); ?>>Semua</option>
                        <?php $__currentLoopData = ($officeOptions ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $officeOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($officeOption); ?>" <?php echo e(($officeFilter ?? 'all') === $officeOption ? 'selected' : ''); ?>>
                                <?php echo e($officeOption); ?>

                            </option>
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
                        <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-3 py-2 text-sm text-gray-700"><?php echo e($record['process_date']); ?></td>
                            <td class="px-3 py-2 text-sm text-gray-700"><?php echo e($record['office']); ?></td>
                            <td class="px-3 py-2 text-sm text-gray-700"><?php echo e($record['input_team']); ?></td>
                            <td class="px-3 py-2 text-sm">
                                <div class="inline-flex items-center gap-2">
                                    <a href="<?php echo e(route('process.show', ['kernelProsses' => $record['id']])); ?>"
                                        class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 hover:bg-indigo-100">
                                        Detail
                                    </a>
                                    <?php if($canManageTeamMeta): ?>
                                        <a href="<?php echo e(route('process.edit', ['kernelProsses' => $record['id']])); ?>"
                                            class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 hover:bg-amber-100">
                                            Edit
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo e(route('process.machines.show', ['kernelProsses' => $record['id']])); ?>"
                                        class="inline-flex items-center rounded-md bg-teal-50 px-2 py-1 text-xs font-medium text-teal-700 hover:bg-teal-100">
                                        Detail Mesin
                                    </a>
                                    <?php if($canManageMachineData): ?>
                                        <a href="<?php echo e(route('process.machines.edit', ['kernelProsses' => $record['id']])); ?>"
                                            class="inline-flex items-center rounded-md bg-cyan-50 px-2 py-1 text-xs font-medium text-cyan-700 hover:bg-cyan-100">
                                            Edit Mesin
                                        </a>
                                    <?php endif; ?>
                                    <span class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-600">
                                        <?php echo e($record['mesin_count']); ?> mesin
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-3 py-4 text-center text-sm text-gray-500">Belum ada data proses.</td>
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
            const team1Checks = Array.from(document.querySelectorAll('input[name="team_1_members[]"]'));
            const team2Checks = Array.from(document.querySelectorAll('input[name="team_2_members[]"]'));
            const machineChecks = Array.from(document.querySelectorAll('.machine-select'));
            const machineGroupToggles = Array.from(document.querySelectorAll('.machine-group-toggle'));
            const addSpareMachineButtons = Array.from(document.querySelectorAll('.add-spare-machine-row'));
            const spareWrappers = Array.from(document.querySelectorAll('.spare-machine-wrapper'));
            const addOtherConditionButtons = Array.from(document.querySelectorAll('.add-other-condition-row'));
            const otherConditionWrappers = Array.from(document.querySelectorAll('.other-condition-wrapper'));
            const submitTeamButtons = Array.from(document.querySelectorAll('.submit-team-button'));
            const processForm = document.getElementById('process-form');
            const inputTeamField = document.getElementById('input_team_field');
            const team1Section = document.getElementById('team-1-section');
            const team2Section = document.getElementById('team-2-section');
            const machineOptions = <?php echo json_encode($machineOptions, 15, 512) ?>;
            const canManageTeamMeta = <?php echo json_encode($canManageTeamMeta, 15, 512) ?>;

            function syncTeams() {
                if (!canManageTeamMeta) {
                    [...team1Checks, ...team2Checks].forEach(input => {
                        input.disabled = true;
                    });
                    return;
                }

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
                    <input type="text" name="other_conditions[${nextIndex}][reason]" placeholder="Alasan" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <input type="time" name="other_conditions[${nextIndex}][start_time]" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <input type="time" name="other_conditions[${nextIndex}][end_time]" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <button type="button" class="remove-other-condition-row inline-flex items-center justify-center rounded-md border border-rose-300 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100">Hapus Baris</button>
                `;
                otherWrapper.appendChild(row);
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

            addOtherConditionButtons.forEach(button => {
                button.addEventListener('click', function () {
                    appendOtherConditionRow(button.getAttribute('data-target'), button.getAttribute('data-team'));
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
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/index.blade.php ENDPATH**/ ?>