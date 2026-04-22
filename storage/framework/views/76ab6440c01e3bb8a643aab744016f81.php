<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Performance Sampel Boy']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Performance Sampel Boy']); ?>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Performance Sampel Boy</h1>
        <p class="mt-2 text-sm text-gray-600">Performa sampling berdasarkan data proses, mesin, dan input sampling aktual.</p>
    </div>

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Filter Tanggal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Filter Tanggal']); ?>
        <form method="GET" action="<?php echo e(route('process.performance-sampel-boy')); ?>" class="flex flex-wrap items-end gap-3">
            <div>
                <label for="date" class="mb-2 block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" id="date" name="date" value="<?php echo e($selectedDate); ?>"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 md:w-64">
            </div>
            <div>
                <label for="office" class="mb-2 block text-sm font-medium text-gray-700">Office</label>
                <select id="office" name="office"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 md:w-48">
                    <option value="all" <?php echo e(($selectedOffice ?? 'all') === 'all' ? 'selected' : ''); ?>>Semua Office</option>
                    <?php $__currentLoopData = ($officeOptions ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $office): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($office); ?>" <?php echo e(($selectedOffice ?? '') === $office ? 'selected' : ''); ?>><?php echo e($office); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button type="submit"
                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                Tampilkan
            </button>
        </form>

        <form method="POST" action="<?php echo e(route('process.performance-sampel-boy.export')); ?>" class="mt-3">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="date" value="<?php echo e($selectedDate); ?>">
            <input type="hidden" name="office" value="<?php echo e($selectedOffice); ?>">
            <button type="submit"
                class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-700">
                Export ke Excel
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

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Data Performance','class' => 'mt-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Performance','class' => 'mt-6']); ?>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1800px] text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Tim</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Mulai Proses</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Akhir Proses</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Total Hours</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Nama Sampel Boy</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Fiber Cyclone</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">LTDS</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Claybath Wet Shell</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Inlet Kernel Silo</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Outlet Kernel Silo</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Press</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Eficiency</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Destoner</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Perf Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['tanggal']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['team_name']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['jam_mulai']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['jam_akhir']); ?></td>
                            <td class="px-3 py-2 font-medium text-gray-800"><?php echo e($row['total_hours']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['nama_sampel_boy']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['fibre_cyclone']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['ltds']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['claybath_wet_shell']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['inlet_kernel_silo']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['outlet_kernel_silo']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['press']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['eficiency']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['destoner']); ?></td>
                            <td class="px-3 py-2 font-semibold text-blue-700"><?php echo e($row['perf_total']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="15" class="px-3 py-4 text-center text-gray-500">Belum ada data performance untuk tanggal ini.</td>
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

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Data Performance Per Kode (Detail)','class' => 'mt-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Performance Per Kode (Detail)','class' => 'mt-6']); ?>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[3600px] text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Tim</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Mulai Proses</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Akhir Proses</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Total Hours</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Nama Sampel Boy</th>
                        <?php $__currentLoopData = ($detailCodeHeaders ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detailCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th class="px-3 py-2 text-left font-semibold text-gray-700"><?php echo e($detailCode['label']); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Perf Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['tanggal']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['team_name']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['jam_mulai']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['jam_akhir']); ?></td>
                            <td class="px-3 py-2 font-medium text-gray-800"><?php echo e($row['total_hours']); ?></td>
                            <td class="px-3 py-2 text-gray-700"><?php echo e($row['nama_sampel_boy']); ?></td>
                            <?php $__currentLoopData = ($detailCodeHeaders ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detailCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td class="px-3 py-2 text-gray-700"><?php echo e($row['detail_values'][$detailCode['code']] ?? '0/0'); ?></td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <td class="px-3 py-2 font-semibold text-blue-700"><?php echo e($row['detail_perf_total'] ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="100" class="px-3 py-4 text-center text-gray-500">Belum ada data performance untuk tanggal ini.</td>
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
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/performance-sampel-boy.blade.php ENDPATH**/ ?>