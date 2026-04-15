<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'OLWB - Oil Losses']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'OLWB - Oil Losses']); ?>

    
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">OLWB</h1>
        <p class="mt-2 text-sm text-gray-600">
            Tabel data OLWB per tanggal dan kode. Data diambil dari Oil Calculations.
        </p>
    </div>

    
    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        
        <div class="mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
            <form method="GET" action="<?php echo e(route('oil.olwb')); ?>"
                class="flex flex-col sm:flex-row gap-2 sm:gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Office/PT
                    </label>
                    <?php if(auth()->user()->office): ?>
                        <div
                            class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg bg-gray-100 text-sm text-gray-700">
                            <?php echo e(auth()->user()->office); ?>

                        </div>
                    <?php else: ?>
                        <select name="office" id="office"
                            class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                            <option value="all" <?php echo e($officeFilter == 'all' ? 'selected' : ''); ?>>-- Semua Office --</option>
                            <option value="YBS" <?php echo e($officeFilter == 'YBS' ? 'selected' : ''); ?>>YBS</option>
                            <option value="SUN" <?php echo e($officeFilter == 'SUN' ? 'selected' : ''); ?>>SUN</option>
                            <option value="SJN" <?php echo e($officeFilter == 'SJN' ? 'selected' : ''); ?>>SJN</option>
                        </select>
                    <?php endif; ?>
                </div>

                <div class="flex-1 w-full">
                    <label for="start_date" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo e($startDate); ?>"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                </div>

                <div class="flex-1 w-full">
                    <label for="end_date" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Akhir
                    </label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo e($endDate); ?>"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                </div>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-1.5 sm:py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>

                    <?php if(request('start_date') || request('end_date') || request('office')): ?>
                        <a href="<?php echo e(route('oil.olwb')); ?>"
                            class="inline-flex items-center justify-center px-4 sm:px-6 py-1.5 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium text-sm">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="mt-3 text-sm text-gray-600">
                <span class="font-medium">Periode:</span>
                <span
                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    <?php echo e(\Carbon\Carbon::parse($startDate)->format('d M Y')); ?> -
                    <?php echo e(\Carbon\Carbon::parse($endDate)->format('d M Y')); ?>

                </span>
            </div>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['export olwb reports'])): ?>
                <div class="mt-3">
                    <form action="<?php echo e(route('oil.olwb.export')); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="office" value="<?php echo e($officeFilter); ?>">
                        <input type="hidden" name="start_date" value="<?php echo e($startDate); ?>">
                        <input type="hidden" name="end_date" value="<?php echo e($endDate); ?>">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export ke Excel
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        
        <?php if(empty($dataByDate)): ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-lg text-gray-500">Tidak ada data OLWB pada periode ini</p>
                <p class="mt-2 text-sm text-gray-400">Silakan pilih rentang tanggal lain atau input data baru</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 sticky left-0 bg-indigo-50 z-10">
                                Tanggal
                            </th>
                            <?php $__currentLoopData = $allKodesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kodeInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th
                                    class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[100px]">
                                    <?php echo e($kodeInfo['pivot']); ?>

                                </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $dataByDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $kodeData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-300 sticky left-0 bg-white z-10">
                                    <?php echo e(\Carbon\Carbon::parse($date)->format('d/m/Y')); ?>

                                </td>
                                <?php $__currentLoopData = $allKodesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kodeInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $kode = $kodeInfo['kode'];
                                    ?>
                                    <td class="px-3 py-3 text-center text-sm border-r border-gray-300">
                                        <?php if(isset($kodeData[$kode])): ?>
                                            <?php
                                                $olwb = $kodeData[$kode]['olwb'];
                                                $limit = $kodeData[$kode]['limitOLWB'];
                                                $operator = $kodeData[$kode]['limit_operator'] ?? 'le';
                                                $isGood = $limit > 0 && match ($operator) {
                                                    'lt' => $olwb < $limit,
                                                    'ge' => $olwb >= $limit,
                                                    'gt' => $olwb > $limit,
                                                    default => $olwb <= $limit,
                                                };
                                            ?>

                                            <div class="flex flex-col items-center gap-1">
                                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                    'font-semibold text-base',
                                                    'text-green-600' => $isGood,
                                                    'text-red-600' => !$isGood && $limit > 0,
                                                    'text-gray-900' => $limit <= 0
                                                ]); ?>">
                                                    <?php if($olwb < 0): ?>
                                                        (<?php echo e(number_format(abs($olwb), 2)); ?>)
                                                    <?php else: ?>
                                                        <?php echo e(number_format($olwb, 2)); ?>

                                                    <?php endif; ?>
                                                </span>
                                                <?php if($limit > 0): ?>
                                                    <span class="text-xs text-gray-500">
                                                        Limit:
                                                        <?php if($limit < 0): ?>
                                                            (<?php echo e(number_format(abs($limit), 2)); ?>)
                                                        <?php else: ?>
                                                            <?php echo e(number_format($limit, 2)); ?>

                                                        <?php endif; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Total Tanggal:</span> <?php echo e(count($dataByDate)); ?> hari
                </div>

                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-600 rounded"></span>
                        <span class="text-gray-600">Sesuai Limit</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-red-600 rounded"></span>
                        <span class="text-gray-600">Melebihi Limit</span>
                    </div>
                </div>
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
<?php endif; ?><?php /**PATH D:\Project1YBS\resources\views/oil/olwb.blade.php ENDPATH**/ ?>