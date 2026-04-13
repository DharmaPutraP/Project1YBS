<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Data Dirt & Moist']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Dirt & Moist']); ?>

    <?php
        $successProof = session('success_proof');
    ?>

    <?php echo $__env->make('kernel.partials.success-proof-modal', ['successProof' => $successProof], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-indigo-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium">Total Records</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1"><?php echo e($statistics['total_records']); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-green-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium">Records Hari Ini</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1"><?php echo e($statistics['records_today']); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-blue-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium">Total Data</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1"><?php echo e($statistics['calculations_count']); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-purple-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium">Data Ditampilkan</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1"><?php echo e($statistics['calculations_count']); ?></p>
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Data Dirt & Moist']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Dirt & Moist']); ?>

        <div class="mb-4 md:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create kernel losses')): ?>
                <a href="<?php echo e(route('kernel.dirt-moist.create')); ?>"
                    class="inline-flex items-center justify-center px-3 md:px-4 py-2 md:py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm w-full sm:w-auto">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Input Data Dirt &amp; Moist
                </a>
            <?php endif; ?>
        </div>

        <div class="mb-4 md:mb-6 bg-gray-50 rounded-lg p-3 md:p-4 border border-gray-200">
            <form method="GET" action="<?php echo e(route('kernel.dirt-moist.index')); ?>"
                class="flex flex-col sm:flex-row gap-3 md:gap-4 items-end">

                <div class="flex-1 w-full">
                    <label for="kode" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Filter
                        Kode</label>
                    <select name="kode" id="kode"
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Semua Kode --</option>
                        <?php $__currentLoopData = $kodeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kodeValue => $kodeLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kodeValue); ?>" <?php echo e(request('kode') == $kodeValue ? 'selected' : ''); ?>>
                                <?php echo e($kodeValue); ?> - <?php echo e($kodeLabel); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="flex-1 w-full">
                    <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Office/PT</label>
                    <?php if(auth()->user()->office): ?>
                        <div class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                            <?php echo e(auth()->user()->office); ?>

                        </div>
                    <?php else: ?>
                        <select name="office" class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="all" <?php echo e($officeFilter == 'all' ? 'selected' : ''); ?>>-- Semua Office --</option>
                            <option value="YBS" <?php echo e($officeFilter == 'YBS' ? 'selected' : ''); ?>>YBS</option>
                            <option value="SUN" <?php echo e($officeFilter == 'SUN' ? 'selected' : ''); ?>>SUN</option>
                            <option value="SJN" <?php echo e($officeFilter == 'SJN' ? 'selected' : ''); ?>>SJN</option>
                        </select>
                    <?php endif; ?>
                </div>

                <div class="flex-1 w-full">
                    <label for="start_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Tanggal
                        Mulai</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo e($startDate); ?>"
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="flex-1 w-full">
                    <label for="end_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Tanggal
                        Akhir</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo e($endDate); ?>"
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <button type="submit"
                    class="inline-flex items-center justify-center px-4 md:px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm whitespace-nowrap">
                    Filter
                </button>
            </form>
        </div>

        <?php if($dirtMoistCalculations->isEmpty()): ?>
            <div class="text-center py-10">
                <p class="text-base text-gray-500">Belum ada data dirt &amp; moist</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Tanggal Input</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jam
                                Proses</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kode
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama
                                Sample</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jenis
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Operator</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Sampel Boy</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Pengulangan</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Berat Sampel</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Berat Dirty</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-purple-700 uppercase tracking-wider bg-purple-100">
                                Dirty to Sampel</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-orange-700 uppercase tracking-wider bg-orange-50">
                                Limit</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-blue-700 uppercase tracking-wider bg-blue-50">
                                Kadar Air Kernel</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium text-orange-700 uppercase tracking-wider bg-orange-50">
                                Limit</th>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['edit kernel losses', 'delete kernel losses'])): ?>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $dirtMoistCalculations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $master = $masterData[$row->kode] ?? null;
                                $displayAt = $row->rounded_time ?? $row->created_at;
                                $dirtyValue = (float) ($row->dirty_to_sampel ?? 0);
                                $moistValue = (float) ($row->moist_percent ?? 0);

                                $dirtyLimitOperator = $row->dirty_limit_operator ?? 'le';
                                $dirtyLimitValue = $row->dirty_limit_value !== null ? (float) $row->dirty_limit_value : null;
                                $dirtyOk = $dirtyLimitValue !== null
                                    ? ($dirtyLimitOperator === 'le' ? $dirtyValue <= $dirtyLimitValue : $dirtyValue > $dirtyLimitValue)
                                    : null;

                                $moistLimitOperator = $row->moist_limit_operator;
                                $moistLimitValue = $row->moist_limit_value !== null ? (float) $row->moist_limit_value : null;
                                $moistOk = $moistLimitValue !== null
                                    ? ($moistLimitOperator === 'le' ? $moistValue <= $moistLimitValue : $moistValue > $moistLimitValue)
                                    : null;
                            ?>
                            <tr class="hover:bg-blue-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($displayAt->format('d/m/Y H:i')); ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($row->rounded_time ? $row->rounded_time->format('H:i') : $row->created_at->format('H:i')); ?>

                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-blue-900"><?php echo e($row->kode); ?>

                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($master->nama_sample ?? '-'); ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo e($row->jenis ?? '-'); ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo e($row->operator ?? '-'); ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo e($row->sampel_boy ?? '-'); ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo e($row->pengulangan ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700'); ?>">
                                        <?php echo e($row->pengulangan ? 'Ya' : 'Tidak'); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900">
                                    <?php echo e(number_format((float) ($row->berat_sampel ?? 0), 2)); ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900">
                                    <?php echo e(number_format((float) ($row->berat_dirty ?? 0), 2)); ?></td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?php echo e($dirtyOk === null ? 'bg-gray-100 text-gray-700' : ($dirtyOk ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')); ?>">
                                        <?php echo e(number_format($dirtyValue, 2)); ?>%
                                    </span>
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-center text-xs font-semibold bg-orange-50 text-orange-800">
                                    <?php if($dirtyLimitValue !== null): ?>
                                        <?php echo e($dirtyLimitOperator === 'le' ? '≤' : '>'); ?> <?php echo e(number_format($dirtyLimitValue, 2)); ?>%
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?php echo e($moistOk === null ? 'bg-gray-100 text-gray-700' : ($moistOk ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')); ?>">
                                        <?php echo e(number_format($moistValue, 2)); ?>%
                                    </span>
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-center text-xs font-semibold bg-orange-50 text-orange-800">
                                    <?php if($moistLimitValue !== null): ?>
                                        <?php echo e($moistLimitOperator === 'le' ? '≤' : '>'); ?> <?php echo e(number_format($moistLimitValue, 2)); ?>%
                                    <?php else: ?>
                                        ≤ 6.00%
                                    <?php endif; ?>
                                </td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['edit kernel losses', 'delete kernel losses'])): ?>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit kernel losses')): ?>
                                                <a href="<?php echo e(route('kernel.dirt-moist.edit', $row->id)); ?>"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-indigo-200 text-indigo-600 hover:bg-indigo-50 transition"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete kernel losses')): ?>
                                                <form action="<?php echo e(route('kernel.dirt-moist.destroy', $row->id)); ?>" method="POST" class="delete-form" data-item-name="Data <?php echo e($row->kode ?? 'ini'); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-red-200 text-red-600 hover:bg-red-50 transition"
                                                        title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-6"><?php echo e($dirtMoistCalculations->links()); ?></div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const itemName = this.dataset.itemName || 'data ini';
                    const confirmed = await window.confirmDelete(itemName);
                    if (confirmed) {
                        this.submit();
                    }
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
<?php endif; ?><?php /**PATH D:\ybs\Project1YBS\resources\views/kernel/dirt-moist/index.blade.php ENDPATH**/ ?>