<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Rekap USB']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Rekap USB']); ?>
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Lap Jangkos</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Rekap USB</h1>
                <p class="mt-2 text-sm text-slate-500">Rekap %USB per tanggal berdasarkan no rebusan, dengan baris Avg harian di bawah.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="<?php echo e(route('lap-jangkos.data-usb', request()->query())); ?>" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Data USB</a>
                <a href="<?php echo e(route('lap-jangkos.rekap-usb.export', request()->query())); ?>" class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">Export Excel</a>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" action="<?php echo e(route('lap-jangkos.rekap-usb')); ?>" class="flex flex-col gap-3 md:flex-row md:items-end">
                <div class="flex-1">
                    <label for="start_date" class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Awal</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo e($startDate); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="flex-1">
                    <label for="end_date" class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo e($endDate); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Filter</button>
                    <a href="<?php echo e(route('lap-jangkos.rekap-usb')); ?>" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-900">Tabel Rekap USB</h2>
                <p class="mt-1 text-sm text-slate-500">Kolom tanggal mengikuti rentang filter yang dipilih.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-max text-sm">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="sticky left-0 z-20 bg-slate-100 px-4 py-3 text-left font-semibold whitespace-nowrap">No Rebusan</th>
                            <?php $__currentLoopData = $recapData['dates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="px-4 py-3 text-center font-semibold whitespace-nowrap"><?php echo e(\Illuminate\Support\Carbon::parse($date)->format('d-m-Y')); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <?php $__currentLoopData = $recapData['row_numbers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rowNumber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="sticky left-0 z-10 bg-white px-4 py-3 font-semibold text-slate-700 whitespace-nowrap"><?php echo e($rowNumber); ?></td>
                                <?php $__currentLoopData = $recapData['dates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $value = $recapData['matrix'][$rowNumber][$date] ?? null;
                                    ?>
                                    <td class="px-4 py-3 text-center whitespace-nowrap text-slate-700">
                                        <?php echo e($value === null ? '-' : number_format((float) $value, 2, ',', '.') . '%'); ?>

                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr class="bg-amber-50">
                            <td class="sticky left-0 z-10 bg-amber-100 px-4 py-3 font-bold text-amber-900 whitespace-nowrap">Avg</td>
                            <?php $__currentLoopData = $recapData['dates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $avg = $recapData['date_averages'][$date] ?? null;
                                ?>
                                <td class="px-4 py-3 text-center font-semibold text-amber-900 whitespace-nowrap">
                                    <?php echo e($avg === null ? '-' : number_format((float) $avg, 2, ',', '.') . '%'); ?>

                                </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/lap-jangkos/rekap-usb.blade.php ENDPATH**/ ?>