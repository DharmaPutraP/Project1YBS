<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Detail Mesin Proses']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Detail Mesin Proses']); ?>
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Mesin Proses</h1>
            <p class="mt-2 text-sm text-gray-600">Rincian mesin yang digunakan pada tanggal <?php echo e($record->process_date?->format('d/m/Y')); ?>.</p>
        </div>
        <a href="<?php echo e(route('process.index')); ?>"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Kembali
        </a>
    </div>

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Data Mesin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Mesin']); ?>
        <?php
            $hasAnyMachine = collect($groupedMachinesByTeam)
                ->contains(fn ($teamData) => $teamData['groups']->isNotEmpty() || ($teamData['spare_rows'] ?? collect())->isNotEmpty());
        ?>

        <?php if(!$hasAnyMachine): ?>
            <p class="text-sm text-gray-500">Belum ada data mesin untuk tanggal ini.</p>
        <?php else: ?>
            <div class="space-y-6">
                <?php $__currentLoopData = $groupedMachinesByTeam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teamName => $teamData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <h2 class="mb-3 text-base font-semibold text-gray-900"><?php echo e($teamName); ?></h2>

                        <?php if($teamData['groups']->isEmpty() && $teamData['orphans']->isEmpty()): ?>
                            <p class="text-sm text-gray-500">Belum ada data mesin pada <?php echo e($teamName); ?>.</p>
                        <?php else: ?>
                            <div class="space-y-5">
                                <?php $__currentLoopData = $teamData['groups']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $machines): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div>
                                        <h3 class="mb-2 text-sm font-semibold text-gray-800"><?php echo e($group); ?></h3>
                                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Mesin</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Awal Produksi</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Akhir Produksi</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Total Jam Produksi</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Interval Sampling</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Total Sampel Seharusnya</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Spare</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 bg-white">
                                                    <?php $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $machine = $item['main'];
                                                            $spares = $item['spares'];
                                                            $totalMinutes = (int) ($item['total_minutes'] ?? 0);
                                                            $totalHoursPart = intdiv($totalMinutes, 60);
                                                            $totalMinutesPart = $totalMinutes % 60;
                                                            $intervalMinutes = (int) ($item['interval_minutes'] ?? 0);
                                                            $expectedSamples = (int) ($item['expected_samples'] ?? 0);
                                                        ?>
                                                        <tr>
                                                            <td class="px-3 py-2 text-gray-700"><?php echo e($machine->machine_name); ?></td>
                                                            <td class="px-3 py-2 text-gray-700"><?php echo e(substr((string) $machine->production_start_time, 0, 5)); ?></td>
                                                            <td class="px-3 py-2 text-gray-700"><?php echo e(substr((string) $machine->production_end_time, 0, 5)); ?></td>
                                                            <td class="px-3 py-2 font-semibold text-gray-800"><?php echo e($totalHoursPart); ?> jam <?php echo e($totalMinutesPart); ?> menit</td>
                                                            <td class="px-3 py-2 text-gray-700"><?php echo e($intervalMinutes > 0 ? (($intervalMinutes / 60) . ' jam sekali') : '-'); ?></td>
                                                            <td class="px-3 py-2 font-semibold text-gray-800"><?php echo e($expectedSamples); ?> sampel</td>
                                                            <td class="px-3 py-2 font-semibold text-gray-700">Tidak</td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="mt-2 text-xs font-semibold text-sky-700">
                                            Total sampel seharusnya <?php echo e($teamName); ?>: <?php echo e((int) ($teamData['expected_samples_total'] ?? 0)); ?> sampel
                                        </p>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if(($teamData['spare_rows'] ?? collect())->isNotEmpty()): ?>
                                    <div>
                                        <h3 class="mb-2 text-sm font-semibold text-gray-800">Mesin hidup setelah breakdown</h3>
                                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Mesin</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Awal Spare</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Akhir Spare</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Total Jam Spare</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Spare</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 bg-white">
                                                    <?php $__currentLoopData = ($teamData['spare_rows'] ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orphan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $spare = $orphan['machine'];
                                                            $spareMinutes = (int) ($orphan['total_minutes'] ?? 0);
                                                            $spareHoursPart = intdiv($spareMinutes, 60);
                                                            $spareMinutesPart = $spareMinutes % 60;
                                                        ?>
                                                        <tr>
                                                            <td class="px-3 py-2 text-gray-700"><?php echo e($spare->machine_name); ?></td>
                                                            <td class="px-3 py-2 text-gray-700"><?php echo e(substr((string) $spare->production_start_time, 0, 5)); ?></td>
                                                            <td class="px-3 py-2 text-gray-700"><?php echo e(substr((string) $spare->production_end_time, 0, 5)); ?></td>
                                                            <td class="px-3 py-2 font-semibold text-gray-800"><?php echo e($spareHoursPart); ?> jam <?php echo e($spareMinutesPart); ?> menit</td>
                                                            <td class="px-3 py-2 font-semibold text-amber-700">Iya</td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($teamData['other_conditions'] ?? [])): ?>
                                    <div>
                                        <h3 class="mb-2 text-sm font-semibold text-gray-800">Kondisi Lainnya</h3>
                                        <div class="overflow-x-auto rounded-lg border border-violet-200">
                                            <table class="min-w-full divide-y divide-violet-200 text-sm">
                                                <thead class="bg-violet-50">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left font-semibold text-violet-900">Alasan</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-violet-900">Jam Mulai</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-violet-900">Jam Selesai</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-violet-900">Durasi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-violet-100 bg-white">
                                                    <?php $__currentLoopData = ($teamData['other_conditions'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $condition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $conditionMinutes = (int) ($condition['duration_minutes'] ?? 0);
                                                            $conditionHoursPart = intdiv($conditionMinutes, 60);
                                                            $conditionMinutesPart = $conditionMinutes % 60;
                                                        ?>
                                                        <tr>
                                                            <td class="px-3 py-2 text-gray-700"><?php echo e($condition['reason'] ?? '-'); ?></td>
                                                            <td class="px-3 py-2 text-gray-700"><?php echo e($condition['start_time'] ?? '-'); ?></td>
                                                            <td class="px-3 py-2 text-gray-700"><?php echo e($condition['end_time'] ?? '-'); ?></td>
                                                            <td class="px-3 py-2 font-semibold text-gray-800"><?php echo e($conditionHoursPart); ?> jam <?php echo e($conditionMinutesPart); ?> menit</td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
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
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/machine-detail.blade.php ENDPATH**/ ?>