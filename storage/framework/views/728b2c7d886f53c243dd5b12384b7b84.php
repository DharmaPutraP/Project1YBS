<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Report Bobot']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Report Bobot']); ?>

    
    <?php if(session('success')): ?>
        <div id="flash-success" class="mb-6">
            <?php if (isset($component)) { $__componentOriginal746de018ded8594083eb43be3f1332e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal746de018ded8594083eb43be3f1332e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert','data' => ['type' => 'success','title' => 'Berhasil']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'success','title' => 'Berhasil']); ?><?php echo e(session('success')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $attributes = $__attributesOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__attributesOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $component = $__componentOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__componentOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div id="flash-error" class="mb-6">
            <?php if (isset($component)) { $__componentOriginal746de018ded8594083eb43be3f1332e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal746de018ded8594083eb43be3f1332e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert','data' => ['type' => 'error','title' => 'Gagal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'error','title' => 'Gagal']); ?><?php echo e(session('error')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $attributes = $__attributesOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__attributesOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $component = $__componentOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__componentOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
        </div>
    <?php endif; ?>

    
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Performance</h1>
        <p class="mt-2 text-sm text-gray-600">
            Tabel data OLWB per tanggal dan kode. Data diambil dari Oil Calculations. Perhitungan Bobot melalui limit
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
            <form method="GET" action="<?php echo e(route('oil.report')); ?>" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo e($startDate); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="flex-1">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Akhir
                    </label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo e($endDate); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>

                    <?php if(request('start_date') || request('end_date')): ?>
                        <a href="<?php echo e(route('oil.report')); ?>"
                            class="inline-flex items-center px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            
            <div class="mt-3">
                <form action="<?php echo e(route('oil.report.export')); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
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
        </div>

        
        <?php if(empty($reportData)): ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-lg text-gray-500">Tidak ada data pada periode ini</p>
                <p class="mt-2 text-sm text-gray-400">Silakan pilih rentang tanggal lain atau input data baru</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 sticky left-0 bg-indigo-50 z-10 min-w-[120px]">
                                Tanggal
                            </th>
                            <?php $__currentLoopData = $allKodesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kodeInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th
                                    class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[120px]">
                                    <?php echo e($kodeInfo['pivot']); ?>

                                </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <th
                                class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[120px] bg-blue-50">
                                AVG PRESS
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[120px] bg-green-50">
                                AVG CLARIFICAT
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                            <tr class="hover:bg-gray-50">
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-300 sticky left-0 bg-white z-10">
                                    <?php echo e(\Carbon\Carbon::parse($dateData['date'])->format('d/m/Y')); ?>

                                </td>
                                <?php $__currentLoopData = $allKodesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kodeInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $kode = $kodeInfo['kode']; ?>
                                    <td class="px-3 py-3 text-center text-sm border-r border-gray-300">
                                        <?php if(isset($dateData['kodes'][$kode]) && $dateData['kodes'][$kode]['olwb'] !== null): ?>
                                            <?php
                                                $olwb = $dateData['kodes'][$kode]['olwb'];
                                            ?>
                                            <span class="font-semibold text-gray-900">
                                                <?php if($olwb < 0): ?>
                                                    (<?php echo e(number_format(abs($olwb), 2)); ?>)
                                                <?php else: ?>
                                                    <?php echo e(number_format($olwb, 2)); ?>

                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td colspan="3" class="px-4 py-3 text-center border-r border-gray-300 bg-gray-50">
                                    
                                </td>
                            </tr>

                            
                            <tr>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-indigo-700 border-r border-gray-300 sticky left-0 bg-indigo-50 z-10">
                                    Bobot
                                </td>
                                <?php $__currentLoopData = $allKodesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kodeInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $kode = $kodeInfo['kode']; ?>
                                    <td class="px-3 py-3 text-center text-sm border-r border-gray-300">
                                        <?php if(isset($dateData['kodes'][$kode]) && $dateData['kodes'][$kode]['bobot'] !== null): ?>
                                            <?php
                                                $bobot = $dateData['kodes'][$kode]['bobot'];
                                                $bgColor = 'bg-red-100';
                                                $textColor = 'text-red-800';

                                                if ($bobot >= 90) {
                                                    $bgColor = 'bg-green-100';
                                                    $textColor = 'text-green-800';
                                                } elseif ($bobot >= 70) {
                                                    $bgColor = 'bg-yellow-100';
                                                    $textColor = 'text-yellow-800';
                                                }
                                            ?>
                                            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold',
                                                $bgColor,
                                                $textColor
                                            ]); ?>">
                                                <?php echo e($bobot); ?>%
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                
                                <td class="px-4 py-3 text-center border-r border-gray-300 bg-blue-50">
                                    <?php if($dateData['average_press'] !== null): ?>
                                        <?php
                                            $avgPress = $dateData['average_press'];
                                            $bgColor = 'bg-red-100';
                                            $textColor = 'text-red-800';

                                            if ($avgPress >= 90) {
                                                $bgColor = 'bg-green-100';
                                                $textColor = 'text-green-800';
                                            } elseif ($avgPress >= 70) {
                                                $bgColor = 'bg-yellow-100';
                                                $textColor = 'text-yellow-800';
                                            }
                                        ?>
                                        <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                            'inline-flex items-center px-3 py-1 rounded-full text-base font-bold',
                                            $bgColor,
                                            $textColor
                                        ]); ?>">
                                            <?php echo e(number_format($avgPress, 2)); ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>

                                
                                <td class="px-4 py-3 text-center border-r border-gray-300 bg-green-50">
                                    <?php if($dateData['average_clarification'] !== null): ?>
                                        <?php
                                            $avgClarif = $dateData['average_clarification'];
                                            $bgColor = 'bg-red-100';
                                            $textColor = 'text-red-800';

                                            if ($avgClarif >= 90) {
                                                $bgColor = 'bg-green-100';
                                                $textColor = 'text-green-800';
                                            } elseif ($avgClarif >= 70) {
                                                $bgColor = 'bg-yellow-100';
                                                $textColor = 'text-yellow-800';
                                            }
                                        ?>
                                        <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                            'inline-flex items-center px-3 py-1 rounded-full text-base font-bold',
                                            $bgColor,
                                            $textColor
                                        ]); ?>">
                                            <?php echo e(number_format($avgClarif, 2)); ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Total Hari:</span> <?php echo e(count($reportData)); ?> hari
                    <span class="mx-2">|</span>
                    <span class="font-medium">Total Kode:</span> <?php echo e(count($allKodesData)); ?> kode
                </div>

                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-100 border border-green-300 rounded"></span>
                        <span class="text-gray-600">≥ 90% (Baik)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-yellow-100 border border-yellow-300 rounded"></span>
                        <span class="text-gray-600">70-89% (Cukup)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-red-100 border border-red-300 rounded"></span>
                        <span class="text-gray-600">
                            < 70% (Kurang)</span>
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

    
    <?php if(isset($operatorPress) && isset($operatorClarification) && isset($reportDates)): ?>
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Daftar Operator & Performance</h2>
            <p class="text-sm text-gray-600 mb-6">
                Daftar operator dan performance harian berdasarkan kategori (Clarification dan Press).
            </p>

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
                <div class="relative overflow-x-auto border rounded-lg">
                    <table class="min-w-full text-sm text-gray-700 divide-y divide-gray-200">
                        
                        <thead class="bg-gray-100 sticky top-0 z-10">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[250px]">
                                    OPERATOR
                                </th>
                                <?php $__currentLoopData = $reportDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[120px]">
                                        <?php echo e(\Carbon\Carbon::parse($date)->format('d M')); ?>

                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            
                            <tr class="bg-green-50">
                                <td colspan="<?php echo e(count($reportDates) + 1); ?>"
                                    class="px-6 py-3 font-bold text-green-900 uppercase text-sm border-b-2 border-green-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                        Operator Clarification
                                        <!-- <span class="ml-2 text-xs text-green-700">(FEED, SOLID, HEAVY PHASE,
                                                            EFFLUENT)</span> -->
                                    </div>
                                </td>
                            </tr>

                            <?php $__empty_1 = true; $__currentLoopData = $operatorClarification; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-green-50 transition">
                                    <td class="px-6 py-3 border-r border-gray-300 bg-green-50/30">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900"><?php echo e($operator); ?></p>
                                                <?php if(isset($operatorActivities[$operator])): ?>
                                                    <p class="text-xs text-gray-500">
                                                        <?php echo e($operatorActivities[$operator]['total_input']); ?> input
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <?php $__currentLoopData = $reportDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="px-4 py-3 text-center border-r border-gray-300">
                                            <?php if(isset($dailyPerformance[$date]) && $dailyPerformance[$date]['average_clarification'] !== null): ?>
                                                <?php
                                                    $avgClarif = $dailyPerformance[$date]['average_clarification'];
                                                    $bgColor = 'bg-red-100';
                                                    $textColor = 'text-red-800';

                                                    if ($avgClarif >= 90) {
                                                        $bgColor = 'bg-green-100';
                                                        $textColor = 'text-green-800';
                                                    } elseif ($avgClarif >= 70) {
                                                        $bgColor = 'bg-yellow-100';
                                                        $textColor = 'text-yellow-800';
                                                    }
                                                ?>
                                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold',
                                                    $bgColor,
                                                    $textColor
                                                ]); ?>">
                                                    <?php echo e(number_format($avgClarif, 1)); ?>%
                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="<?php echo e(count($reportDates) + 1); ?>" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada operator Clarification pada periode ini
                                    </td>
                                </tr>
                            <?php endif; ?>

                            
                            <tr class="bg-blue-50">
                                <td colspan="<?php echo e(count($reportDates) + 1); ?>"
                                    class="px-6 py-3 font-bold text-blue-900 uppercase text-sm border-b-2 border-blue-200 border-t-4 border-t-gray-300">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Operator Press
                                        <!-- <span class="ml-2 text-xs text-blue-700">(BUNCH PRESS, FIBRE PRESS)</span> -->
                                    </div>
                                </td>
                            </tr>

                            <?php $__empty_1 = true; $__currentLoopData = $operatorPress; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-3 border-r border-gray-300 bg-blue-50/30">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900"><?php echo e($operator); ?></p>
                                                <?php if(isset($operatorActivities[$operator])): ?>
                                                    <p class="text-xs text-gray-500">
                                                        <?php echo e($operatorActivities[$operator]['total_input']); ?> input
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <?php $__currentLoopData = $reportDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="px-4 py-3 text-center border-r border-gray-300">
                                            <?php if(isset($dailyPerformance[$date]) && $dailyPerformance[$date]['average_press'] !== null): ?>
                                                <?php
                                                    $avgPress = $dailyPerformance[$date]['average_press'];
                                                    $bgColor = 'bg-red-100';
                                                    $textColor = 'text-red-800';

                                                    if ($avgPress >= 90) {
                                                        $bgColor = 'bg-green-100';
                                                        $textColor = 'text-green-800';
                                                    } elseif ($avgPress >= 70) {
                                                        $bgColor = 'bg-yellow-100';
                                                        $textColor = 'text-yellow-800';
                                                    }
                                                ?>
                                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold',
                                                    $bgColor,
                                                    $textColor
                                                ]); ?>">
                                                    <?php echo e(number_format($avgPress, 1)); ?>%
                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="<?php echo e(count($reportDates) + 1); ?>" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada operator Press pada periode ini
                                    </td>
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
        </div>
    <?php endif; ?>

    
    <script>
        ['flash-success', 'flash-error'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                setTimeout(function () {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(function () {
                        el.remove();
                    }, 500);
                }, 4000);
            }
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
<?php endif; ?><?php /**PATH D:\Project1YBS\resources\views\oil\report.blade.php ENDPATH**/ ?>