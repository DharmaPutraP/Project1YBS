<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Data Oil Losses - Oil Losses']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Oil Losses - Oil Losses']); ?>

    <?php
        $successProof = session('success_proof');
        $defaultTab = $successProof['active_tab'] ?? 'records';
        $mode1Entries = collect($successProof['mode1_entries'] ?? [])->values();
        if ($mode1Entries->isEmpty() && !empty($successProof['mode1'])) {
            $mode1Entries = collect([$successProof['mode1']]);
        }
        $mode2Entries = collect($successProof['mode2_entries'] ?? [])->values();
        if ($mode2Entries->isEmpty() && !empty($successProof['mode2'])) {
            $mode2Entries = collect([$successProof['mode2']]);
        }
    ?>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #success-proof-print,
            #success-proof-print * {
                visibility: visible;
            }

            #success-proof-print {
                position: absolute;
                inset: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
        }

        .success-proof-export-root {
            position: fixed;
            left: -10000px;
            top: 0;
            width: 1480px;
            padding: 32px;
            background: #f8fafc;
            z-index: -1;
        }

        .success-proof-export-root table {
            width: 100%;
            border-collapse: collapse;
        }

        .success-proof-export-root th,
        .success-proof-export-root td {
            white-space: normal;
            word-break: break-word;
            vertical-align: top;
        }
    </style>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Total Records</p>
                    <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1"><?php echo e($statistics['total_records']); ?></p>
                </div>
                <div class="p-2 md:p-3 bg-indigo-100 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-indigo-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Records Today</p>
                    <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1"><?php echo e($statistics['records_today']); ?></p>
                </div>
                <div class="p-2 md:p-3 bg-green-100 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-green-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Data Non-Angka</p>
                    <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1"><?php echo e($statistics['records_count']); ?></p>
                </div>
                <div class="p-2 md:p-3 bg-blue-100 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Data Perhitungan</p>
                    <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1"><?php echo e($statistics['calculations_count']); ?>

                    </p>
                </div>
                <div class="p-2 md:p-3 bg-purple-100 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-purple-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    
    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Data Oil Losses']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Oil Losses']); ?>
        
        <div class="mb-4 md:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create oil losses')): ?>
                    <a href="<?php echo e(route('oil.create')); ?>"
                        class="inline-flex items-center justify-center px-3 md:px-4 py-2 md:py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Input Data Oil Losses
                    </a>
                <?php endif; ?>
            </div>

        </div>

        
        <div class="mb-4 md:mb-6 bg-gray-50 rounded-lg p-3 md:p-4 border border-gray-200">
            <form method="GET" action="<?php echo e(route('oil.index')); ?>"
                class="flex flex-col sm:flex-row gap-3 md:gap-4 items-end">
                <div class="flex-1 w-full">
                    <label for="kode" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                        Filter Kode <span class="text-xs text-gray-500">(Opsional)</span>
                    </label>
                    <select name="kode" id="kode"
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Semua Kode --</option>
                        <?php $__currentLoopData = $kodeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kodeValue => $kodeLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kodeValue); ?>" <?php echo e(request('kode') == $kodeValue ? 'selected' : ''); ?>>
                                <?php echo e($kodeLabel); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="flex-1 w-full">
                    <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                        Office/PT
                    </label>
                    <?php if(auth()->user()->office): ?>
                        <div
                            class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                            <?php echo e(auth()->user()->office); ?>

                        </div>
                    <?php else: ?>
                        <select name="office" id="office"
                            class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="all" <?php echo e($officeFilter == 'all' ? 'selected' : ''); ?>>-- Semua Office --</option>
                            <option value="YBS" <?php echo e($officeFilter == 'YBS' ? 'selected' : ''); ?>>YBS</option>
                            <option value="SUN" <?php echo e($officeFilter == 'SUN' ? 'selected' : ''); ?>>SUN</option>
                            <option value="SJN" <?php echo e($officeFilter == 'SJN' ? 'selected' : ''); ?>>SJN</option>
                        </select>
                    <?php endif; ?>
                </div>

                <div class="flex-1 w-full">
                    <label for="start_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai <span class="text-xs text-gray-500">(Default: Hari ini)</span>
                    </label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo e($startDate); ?>"
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="flex-1 w-full">
                    <label for="end_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Akhir <span class="text-xs text-gray-500">(Default: Hari ini)</span>
                    </label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo e($endDate); ?>"
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 md:px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm whitespace-nowrap">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>

                    <?php if(request('start_date') && request('start_date') != now()->format('Y-m-d') || request('end_date') && request('end_date') != now()->format('Y-m-d') || request('kode') || request('office')): ?>
                        <a href="<?php echo e(route('oil.index')); ?>"
                            class="inline-flex items-center justify-center px-4 md:px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium text-sm whitespace-nowrap">
                            <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="hidden sm:inline">Reset ke Hari Ini</span>
                            <span class="sm:hidden">Reset</span>
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="mt-3 text-xs md:text-sm text-gray-600 flex flex-wrap items-center gap-2">
                <span class="font-medium">Menampilkan data:</span>
                <?php if($startDate == $endDate): ?>
                    <span
                        class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 whitespace-nowrap">
                        📅 <?php echo e(\Carbon\Carbon::parse($startDate)->format('d M Y')); ?>

                    </span>
                <?php else: ?>
                    <span
                        class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        📅 <?php echo e(\Carbon\Carbon::parse($startDate)->format('d M Y')); ?> -
                        <?php echo e(\Carbon\Carbon::parse($endDate)->format('d M Y')); ?>

                    </span>
                <?php endif; ?>
                <?php if(request('kode')): ?>
                    <span
                        class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 whitespace-nowrap">
                        🔖 Kode: <?php echo e(request('kode')); ?>

                    </span>
                <?php else: ?>
                    <span
                        class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 whitespace-nowrap">
                        🔖 Semua Kode
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view oil losses')): ?>
            
            <div class="border-b border-gray-200 mb-4 md:mb-6 overflow-x-auto">
                <nav class="-mb-px flex space-x-4 md:space-x-8">
                    <button onclick="switchTab('records')" id="tab-records"
                        class="tab-button border-b-2 border-blue-500 text-blue-600 whitespace-nowrap py-3 md:py-4 px-1 font-medium text-xs md:text-sm flex-shrink-0">
                        <span class="hidden sm:inline">📝 Data Jenis & Sampel</span>
                        <span class="sm:hidden">📝 Jenis</span>
                        <span
                            class="ml-1 md:ml-2 bg-blue-100 text-blue-600 py-0.5 px-1.5 md:px-2.5 rounded-full text-xs"><?php echo e($statistics['records_count']); ?></span>
                    </button>
                    <button onclick="switchTab('calculations')" id="tab-calculations"
                        class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 md:py-4 px-1 font-medium text-xs md:text-sm flex-shrink-0">
                        <span class="hidden sm:inline">🧪 Data Perhitungan Lab</span>
                        <span class="sm:hidden">🧪 Perhitungan</span>
                        <span
                            class="ml-1 md:ml-2 bg-gray-100 text-gray-600 py-0.5 px-1.5 md:px-2.5 rounded-full text-xs"><?php echo e($statistics['calculations_count']); ?></span>
                    </button>
                </nav>
            </div>

            
            <div id="content-records" class="tab-content">
                <?php if($oilRecords->isEmpty()): ?>
                    <div class="text-center py-8 md:py-12">
                        <svg class="mx-auto h-10 w-10 md:h-12 md:w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-4 text-base md:text-lg text-gray-500">Belum ada data jenis & sampel</p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create oil losses')): ?>
                            <a href="<?php echo e(route('oil.create')); ?>"
                                class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                Input Data Pertama
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Tanggal Input
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Kode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Jenis
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Operator
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Sampel Boy
                                    </th>
                                    <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                                                                                                                                        Parameter Lain
                                                                                                                                                    </th> -->
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Input By
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $oilRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-blue-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($record->created_at->format('d/m/Y H:i')); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-900">
                                            <?php echo e($record->kode ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($record->jenis == 'TBS' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800'); ?>">
                                                <?php echo e($record->jenis ?? '-'); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($record->operator ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($record->sampel_boy ?? '-'); ?>

                                        </td>
                                        <!-- <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                                                                                                                                                                                                            <?php echo e($record->parameter_lain ?? '-'); ?>

                                                                                                                                                                                                                        </td> -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($record->user->name); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit oil losses')): ?>
                                                    <a href="<?php echo e(route('oil.records.edit', $record->id)); ?>"
                                                        class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete oil losses')): ?>
                                                    <form action="<?php echo e(route('oil.records.destroy', $record->id)); ?>" method="POST"
                                                        class="inline delete-form" data-item-name="Data <?php echo e($record->kode ?? 'ini'); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <div class="mt-6">
                        <?php echo e($oilRecords->links()); ?>

                    </div>
                <?php endif; ?>
            </div>

            
            <div id="content-calculations" class="tab-content hidden">
                <?php if($oilLosses->isEmpty()): ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-4 text-lg text-gray-500">Belum ada data perhitungan lab</p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create oil losses')): ?>
                            <a href="<?php echo e(route('oil.create')); ?>"
                                class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                Input Data Pertama
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-purple-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Tanggal Input
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Kode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Moist (%)
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        DMWM (%)
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        OLWB
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        OLDB
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Oil Losses (%)
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Input By
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $oilLosses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oilLoss): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-purple-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e($oilLoss->created_at->format('d/m/Y H:i')); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-purple-900">
                                            <?php echo e($oilLoss->kode); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e(number_format($oilLoss->moist ?? 0, 4)); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo e(number_format($oilLoss->dmwm ?? 0, 4)); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <!-- <?php echo e(number_format($oilLoss->olwb ?? 0, 4)); ?> -->
                                            <?php
                                                $olwb_calc = $oilLoss->olwb ?? 0;
                                                $limitolwb = $oilLoss->limitOLWB ?? 0;
                                                $limitOperator = $oilLoss->limit_operator ?? 'le';
                                                $isGood = $limitolwb > 0 && match ($limitOperator) {
                                                    'lt' => $olwb_calc < $limitolwb,
                                                    'ge' => $olwb_calc >= $limitolwb,
                                                    'gt' => $olwb_calc > $limitolwb,
                                                    default => $olwb_calc <= $limitolwb,
                                                };
                                            ?>
                                            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'font-semibold',
                                                'text-green-600' => $isGood,
                                                'text-red-600' => !$isGood && $limitolwb > 0,
                                            ]); ?>">
                                                <?php if($olwb_calc < 0): ?>
                                                    (<?php echo e(number_format(abs($olwb_calc), 2)); ?>)
                                                <?php else: ?>
                                                    <?php echo e(number_format($olwb_calc, 2)); ?>

                                                <?php endif; ?>
                                            </span>
                                            <span class="text-xs text-gray-500 block">Limit:
                                                <?php if($limitolwb > 0): ?>
                                                    <?php if($limitolwb < 0): ?>
                                                        (<?php echo e(number_format(abs($limitolwb), 2)); ?>)
                                                    <?php else: ?>
                                                        <?php echo e(number_format($limitolwb, 2)); ?>

                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <!-- <?php echo e(number_format($oilLoss->oldb ?? 0, 4)); ?> -->
                                            <?php
                                                $oldb_calc = $oilLoss->oldb ?? 0;
                                                $limitoldb = $oilLoss->limitOLDB ?? 0;
                                                $isGood = $oldb_calc <= $limitoldb && $limitoldb > 0;
                                            ?>
                                            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'font-semibold',
                                                'text-green-600' => $isGood,
                                                'text-red-600' => !$isGood && $limitoldb > 0,
                                            ]); ?>">
                                                <?php if($oldb_calc < 0): ?>
                                                    (<?php echo e(number_format(abs($oldb_calc), 2)); ?>)
                                                <?php else: ?>
                                                    <?php echo e(number_format($oldb_calc, 2)); ?>

                                                <?php endif; ?>
                                            </span>
                                            <span class="text-xs text-gray-500 block">
                                                Limit:
                                                <?php if($limitoldb > 0): ?>
                                                    <?php if($limitoldb < 0): ?>
                                                        (<?php echo e(number_format(abs($limitoldb), 2)); ?>)
                                                    <?php else: ?>
                                                        <?php echo e(number_format($limitoldb, 2)); ?>

                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </span>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <?php
                                                $losses = $oilLoss->oil_losses ?? 0;
                                                $limitOL = $oilLoss->limitOL ?? 0;
                                                $isGood = $losses <= $limitOL && $limitOL > 0;
                                            ?>
                                            <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'font-semibold',
                                                'text-green-600' => $isGood,
                                                'text-red-600' => !$isGood && $limitOL > 0,
                                            ]); ?>">
                                                <?php if($losses < 0): ?>
                                                    (<?php echo e(number_format(abs($losses), 2)); ?>)
                                                <?php else: ?>
                                                    <?php echo e(number_format($losses, 2)); ?>

                                                <?php endif; ?>
                                            </span>

                                            <span class="text-xs text-gray-500 block">Limit:
                                                <?php if($limitOL > 0): ?>
                                                    <?php if($limitOL < 0): ?>
                                                        (<?php echo e(number_format(abs($limitOL), 2)); ?>)
                                                    <?php else: ?>
                                                        <?php echo e(number_format($limitOL, 2)); ?>

                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </span>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($oilLoss->user->name); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit oil losses')): ?>
                                                    <a href="<?php echo e(route('oil.edit', $oilLoss->id)); ?>"
                                                        class="text-blue-600 hover:text-blue-900" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete oil losses')): ?>
                                                    <form action="<?php echo e(route('oil.destroy', $oilLoss->id)); ?>" method="POST"
                                                        class="inline delete-form" data-item-name="Data <?php echo e($oilLoss->kode ?? 'ini'); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <div class="mt-6">
                        <?php echo e($oilLosses->links()); ?>

                    </div>
                <?php endif; ?>
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

    <?php if($successProof): ?>
        <div class="success-proof-export-root" aria-hidden="true">
            <div id="success-proof-export" class="rounded-[28px] bg-white p-8 shadow-2xl">
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6">
                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900">Bukti Input Data Oil Losses</h1>
                            <p class="mt-3 text-lg font-semibold text-emerald-900"><?php echo e($successProof['message']); ?></p>
                            <p class="mt-2 text-base text-emerald-800">Waktu bukti dibuat:
                                <?php echo e($successProof['generated_at']); ?></p>
                        </div>
                        <div class="min-w-[260px] rounded-xl bg-white/80 p-4 text-base text-emerald-900">
                            <div><span class="font-semibold">User login:</span> <?php echo e(auth()->user()->name); ?></div>
                            <div class="mt-1"><span class="font-semibold">Office login:</span>
                                <?php echo e(auth()->user()->office ?? '-'); ?></div>
                        </div>
                    </div>
                </div>

                <?php if($mode1Entries->isNotEmpty()): ?>
                    <section class="mb-6 rounded-2xl border border-blue-200 bg-blue-50/60 p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-3xl font-bold text-slate-900">Data Jenis &amp; Sampel</h2>
                            <span class="rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">Mode
                                Non-Angka</span>
                        </div>

                        <div class="overflow-hidden rounded-2xl border border-blue-200 bg-white">
                            <table class="divide-y divide-blue-200">
                                <thead class="bg-blue-100 text-slate-700">
                                    <tr>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal
                                            Input</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Kode</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Jenis
                                        </th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Operator
                                        </th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Sampel
                                            Boy</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Input By
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-blue-100 text-base text-slate-700">
                                    <?php $__currentLoopData = $mode1Entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proofMode1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-5 py-4"><?php echo e($proofMode1['tanggal_input']); ?></td>
                                            <td class="px-5 py-4 font-semibold text-blue-900"><?php echo e($proofMode1['kode_label']); ?></td>
                                            <td class="px-5 py-4"><?php echo e($proofMode1['jenis'] ?? '-'); ?></td>
                                            <td class="px-5 py-4"><?php echo e($proofMode1['operator'] ?? '-'); ?></td>
                                            <td class="px-5 py-4"><?php echo e($proofMode1['sampel_boy'] ?? '-'); ?></td>
                                            <td class="px-5 py-4"><?php echo e($proofMode1['input_by'] ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if($mode2Entries->isNotEmpty()): ?>
                    <section class="rounded-2xl border border-purple-200 bg-purple-50/60 p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-3xl font-bold text-slate-900">Data Perhitungan Lab</h2>
                            <span class="rounded-full bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">Mode
                                Angka</span>
                        </div>

                        <div class="mb-5 overflow-hidden rounded-2xl border border-purple-200 bg-white">
                            <table class="divide-y divide-purple-200">
                                <thead class="bg-purple-100 text-slate-700">
                                    <tr>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Kode</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Cawan
                                            Kosong</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Berat
                                            Basah</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Cawan +
                                            Sample Kering</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Labu
                                            Kosong</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Oil +
                                            Labu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-purple-100 text-base text-slate-700">
                                    <?php $__currentLoopData = $mode2Entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proofMode2Export): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-5 py-4 font-semibold text-purple-900">
                                                <?php echo e($proofMode2Export['kode_label']); ?></td>
                                            <td class="px-5 py-4"><?php echo e(number_format((float) $proofMode2Export['cawan_kosong'], 6)); ?>

                                            </td>
                                            <td class="px-5 py-4"><?php echo e(number_format((float) $proofMode2Export['berat_basah'], 6)); ?>

                                            </td>
                                            <td class="px-5 py-4">
                                                <?php echo e(number_format((float) $proofMode2Export['cawan_sample_kering'], 6)); ?></td>
                                            <td class="px-5 py-4"><?php echo e(number_format((float) $proofMode2Export['labu_kosong'], 6)); ?>

                                            </td>
                                            <td class="px-5 py-4"><?php echo e(number_format((float) $proofMode2Export['oil_labu'], 6)); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="overflow-hidden rounded-2xl border border-purple-200 bg-white">
                            <table class="divide-y divide-purple-200">
                                <thead class="bg-purple-100 text-slate-700">
                                    <tr>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal
                                            Input</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Kode</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Moist (%)
                                        </th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">DMWM (%)
                                        </th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">OLWB</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">OLDB</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Oil
                                            Losses (%)</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Input By
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-purple-100 text-base text-slate-700">
                                    <?php $__currentLoopData = $mode2Entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proofMode2Export): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $proofOlwbExport = (float) ($proofMode2Export['olwb'] ?? 0);
                                            $proofOldbExport = (float) ($proofMode2Export['oldb'] ?? 0);
                                            $proofLossesExport = (float) ($proofMode2Export['oil_losses'] ?? 0);
                                            $proofLimitOlwbExport = (float) ($proofMode2Export['limitOLWB'] ?? 0);
                                            $proofLimitOldbExport = (float) ($proofMode2Export['limitOLDB'] ?? 0);
                                            $proofLimitOlExport = (float) ($proofMode2Export['limitOL'] ?? 0);
                                            $proofLimitOperatorExport = $proofMode2Export['limit_operator'] ?? 'le';
                                            $isOlwbGoodExport = $proofLimitOlwbExport > 0
                                                && match ($proofLimitOperatorExport) {
                                                    'lt' => $proofOlwbExport < $proofLimitOlwbExport,
                                                    'ge' => $proofOlwbExport >= $proofLimitOlwbExport,
                                                    'gt' => $proofOlwbExport > $proofLimitOlwbExport,
                                                    default => $proofOlwbExport <= $proofLimitOlwbExport,
                                                };
                                            $isOldbGoodExport = $proofOldbExport <= $proofLimitOldbExport && $proofLimitOldbExport > 0;
                                            $isLossesGoodExport = $proofLossesExport <= $proofLimitOlExport && $proofLimitOlExport > 0;
                                        ?>
                                        <tr>
                                            <td class="px-5 py-4"><?php echo e($proofMode2Export['tanggal_input']); ?></td>
                                            <td class="px-5 py-4 font-semibold text-purple-900">
                                                <?php echo e($proofMode2Export['kode_label']); ?></td>
                                            <td class="px-5 py-4"><?php echo e(number_format((float) $proofMode2Export['moist'], 4)); ?></td>
                                            <td class="px-5 py-4"><?php echo e(number_format((float) $proofMode2Export['dmwm'], 4)); ?></td>
                                            <td class="px-5 py-4">
                                                <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['font-semibold', 'text-green-600' => $isOlwbGoodExport, 'text-red-600' => !$isOlwbGoodExport && $proofLimitOlwbExport > 0]); ?>">
                                                    <?php if($proofOlwbExport < 0): ?>
                                                        (<?php echo e(number_format(abs($proofOlwbExport), 2)); ?>)
                                                    <?php else: ?>
                                                        <?php echo e(number_format($proofOlwbExport, 2)); ?>

                                                    <?php endif; ?>
                                                </div>
                                                <div class="mt-1 text-sm text-gray-500">Limit:
                                                    <?php if($proofLimitOlwbExport > 0): ?>
                                                        <?php if($proofLimitOlwbExport < 0): ?>
                                                            (<?php echo e(number_format(abs($proofLimitOlwbExport), 2)); ?>)
                                                        <?php else: ?>
                                                            <?php echo e(number_format($proofLimitOlwbExport, 2)); ?>

                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['font-semibold', 'text-green-600' => $isOldbGoodExport, 'text-red-600' => !$isOldbGoodExport && $proofLimitOldbExport > 0]); ?>">
                                                    <?php if($proofOldbExport < 0): ?>
                                                        (<?php echo e(number_format(abs($proofOldbExport), 2)); ?>)
                                                    <?php else: ?>
                                                        <?php echo e(number_format($proofOldbExport, 2)); ?>

                                                    <?php endif; ?>
                                                </div>
                                                <div class="mt-1 text-sm text-gray-500">Limit:
                                                    <?php if($proofLimitOldbExport > 0): ?>
                                                        <?php if($proofLimitOldbExport < 0): ?>
                                                            (<?php echo e(number_format(abs($proofLimitOldbExport), 2)); ?>)
                                                        <?php else: ?>
                                                            <?php echo e(number_format($proofLimitOldbExport, 2)); ?>

                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="<?php echo \Illuminate\Support\Arr::toCssClasses(['font-semibold', 'text-green-600' => $isLossesGoodExport, 'text-red-600' => !$isLossesGoodExport && $proofLimitOlExport > 0]); ?>">
                                                    <?php if($proofLossesExport < 0): ?>
                                                        (<?php echo e(number_format(abs($proofLossesExport), 2)); ?>)
                                                    <?php else: ?>
                                                        <?php echo e(number_format($proofLossesExport, 2)); ?>

                                                    <?php endif; ?>
                                                </div>
                                                <div class="mt-1 text-sm text-gray-500">Limit:
                                                    <?php if($proofLimitOlExport > 0): ?>
                                                        <?php if($proofLimitOlExport < 0): ?>
                                                            (<?php echo e(number_format(abs($proofLimitOlExport), 2)); ?>)
                                                        <?php else: ?>
                                                            <?php echo e(number_format($proofLimitOlExport, 2)); ?>

                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4"><?php echo e($proofMode2Export['input_by'] ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
        </div>

        <div id="successProofModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
            <div class="w-full max-w-6xl rounded-2xl bg-white shadow-2xl">
                <div class="flex items-start justify-between border-b border-slate-200 px-6 py-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Bukti Input Data Oil Losses</h2>
                        <p class="mt-1 text-sm text-slate-600">Silakan screenshot bagian ini sebagai bukti bahwa data sudah
                            tersimpan.</p>
                    </div>
                    <button type="button" onclick="closeSuccessProofModal()"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="success-proof-print" class="max-h-[80vh] overflow-y-auto px-6 py-5">
                    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-emerald-900"><?php echo e($successProof['message']); ?></p>
                                <p class="mt-1 text-sm text-emerald-800">Waktu bukti dibuat:
                                    <?php echo e($successProof['generated_at']); ?></p>
                                <p class="mt-1 text-xs text-emerald-700">Tips mobile: gunakan tombol Unduh Gambar untuk
                                    bukti 1 file JPG tanpa perlu screenshot panjang.</p>
                            </div>
                            <div class="text-sm text-emerald-800">
                                <div>User login: <?php echo e(auth()->user()->name); ?></div>
                                <div>Office login: <?php echo e(auth()->user()->office ?? '-'); ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if($mode1Entries->isNotEmpty()): ?>
                        <section class="mb-6 rounded-xl border border-blue-200 bg-blue-50/60 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-slate-900">Data Jenis &amp; Sampel</h3>
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Mode
                                    Non-Angka</span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-blue-200 overflow-hidden rounded-lg bg-white">
                                    <thead class="bg-blue-100 text-slate-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                                Tanggal Input</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kode
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jenis
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                                Operator</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                                Sampel Boy</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Input
                                                By</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-blue-100 text-sm text-slate-700">
                                        <?php $__currentLoopData = $mode1Entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proofMode1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap"><?php echo e($proofMode1['tanggal_input']); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-blue-900">
                                                    <?php echo e($proofMode1['kode_label']); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap"><?php echo e($proofMode1['jenis'] ?? '-'); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap"><?php echo e($proofMode1['operator'] ?? '-'); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap"><?php echo e($proofMode1['sampel_boy'] ?? '-'); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap"><?php echo e($proofMode1['input_by'] ?? '-'); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if($mode2Entries->isNotEmpty()): ?>
                        <section class="rounded-xl border border-purple-200 bg-purple-50/60 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-slate-900">Data Perhitungan Lab</h3>
                                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">Mode
                                    Angka</span>
                            </div>

                            <div class="mb-4 overflow-x-auto rounded-lg border border-purple-200 bg-white">
                                <table class="min-w-full divide-y divide-purple-200">
                                    <thead class="bg-purple-100 text-slate-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kode
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Cawan
                                                Kosong</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Berat
                                                Basah</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Cawan
                                                + Sample Kering</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Labu
                                                Kosong</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Oil +
                                                Labu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-purple-100 text-sm text-slate-700">
                                        <?php $__currentLoopData = $mode2Entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proofMode2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-purple-900">
                                                    <?php echo e($proofMode2['kode_label']); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <?php echo e(number_format((float) $proofMode2['cawan_kosong'], 6)); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <?php echo e(number_format((float) $proofMode2['berat_basah'], 6)); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <?php echo e(number_format((float) $proofMode2['cawan_sample_kering'], 6)); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <?php echo e(number_format((float) $proofMode2['labu_kosong'], 6)); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <?php echo e(number_format((float) $proofMode2['oil_labu'], 6)); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-purple-200 overflow-hidden rounded-lg bg-white">
                                    <thead class="bg-purple-100 text-slate-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                                Tanggal Input</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kode
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Moist
                                                (%)</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">DMWM
                                                (%)</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">OLWB
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">OLDB
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Oil
                                                Losses (%)</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Input
                                                By</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-purple-100 text-sm text-slate-700">
                                        <?php $__currentLoopData = $mode2Entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proofMode2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $proofOlwb = (float) ($proofMode2['olwb'] ?? 0);
                                                $proofOldb = (float) ($proofMode2['oldb'] ?? 0);
                                                $proofLosses = (float) ($proofMode2['oil_losses'] ?? 0);
                                                $proofLimitOlwb = (float) ($proofMode2['limitOLWB'] ?? 0);
                                                $proofLimitOldb = (float) ($proofMode2['limitOLDB'] ?? 0);
                                                $proofLimitOl = (float) ($proofMode2['limitOL'] ?? 0);
                                                $proofLimitOperator = $proofMode2['limit_operator'] ?? 'le';
                                                $isOlwbGood = $proofLimitOlwb > 0
                                                    && match ($proofLimitOperator) {
                                                        'lt' => $proofOlwb < $proofLimitOlwb,
                                                        'ge' => $proofOlwb >= $proofLimitOlwb,
                                                        'gt' => $proofOlwb > $proofLimitOlwb,
                                                        default => $proofOlwb <= $proofLimitOlwb,
                                                    };
                                                $isOldbGood = $proofOldb <= $proofLimitOldb && $proofLimitOldb > 0;
                                                $isLossesGood = $proofLosses <= $proofLimitOl && $proofLimitOl > 0;
                                            ?>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap"><?php echo e($proofMode2['tanggal_input']); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-purple-900">
                                                    <?php echo e($proofMode2['kode_label']); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <?php echo e(number_format((float) $proofMode2['moist'], 4)); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <?php echo e(number_format((float) $proofMode2['dmwm'], 4)); ?></td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                        'font-semibold',
                                                        'text-green-600' => $isOlwbGood,
                                                        'text-red-600' => !$isOlwbGood && $proofLimitOlwb > 0,
                                                    ]); ?>">
                                                        <?php if($proofOlwb < 0): ?>
                                                            (<?php echo e(number_format(abs($proofOlwb), 2)); ?>)
                                                        <?php else: ?>
                                                            <?php echo e(number_format($proofOlwb, 2)); ?>

                                                        <?php endif; ?>
                                                    </span>
                                                    <span class="text-xs text-gray-500 block">Limit:
                                                        <?php if($proofLimitOlwb > 0): ?>
                                                            <?php if($proofLimitOlwb < 0): ?>
                                                                (<?php echo e(number_format(abs($proofLimitOlwb), 2)); ?>)
                                                            <?php else: ?>
                                                                <?php echo e(number_format($proofLimitOlwb, 2)); ?>

                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                        'font-semibold',
                                                        'text-green-600' => $isOldbGood,
                                                        'text-red-600' => !$isOldbGood && $proofLimitOldb > 0,
                                                    ]); ?>">
                                                        <?php if($proofOldb < 0): ?>
                                                            (<?php echo e(number_format(abs($proofOldb), 2)); ?>)
                                                        <?php else: ?>
                                                            <?php echo e(number_format($proofOldb, 2)); ?>

                                                        <?php endif; ?>
                                                    </span>
                                                    <span class="text-xs text-gray-500 block">Limit:
                                                        <?php if($proofLimitOldb > 0): ?>
                                                            <?php if($proofLimitOldb < 0): ?>
                                                                (<?php echo e(number_format(abs($proofLimitOldb), 2)); ?>)
                                                            <?php else: ?>
                                                                <?php echo e(number_format($proofLimitOldb, 2)); ?>

                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                        'font-semibold',
                                                        'text-green-600' => $isLossesGood,
                                                        'text-red-600' => !$isLossesGood && $proofLimitOl > 0,
                                                    ]); ?>">
                                                        <?php if($proofLosses < 0): ?>
                                                            (<?php echo e(number_format(abs($proofLosses), 2)); ?>)
                                                        <?php else: ?>
                                                            <?php echo e(number_format($proofLosses, 2)); ?>

                                                        <?php endif; ?>
                                                    </span>
                                                    <span class="text-xs text-gray-500 block">Limit:
                                                        <?php if($proofLimitOl > 0): ?>
                                                            <?php if($proofLimitOl < 0): ?>
                                                                (<?php echo e(number_format(abs($proofLimitOl), 2)); ?>)
                                                            <?php else: ?>
                                                                <?php echo e(number_format($proofLimitOl, 2)); ?>

                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap"><?php echo e($proofMode2['input_by'] ?? '-'); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    <?php endif; ?>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">
                    <button type="button" onclick="downloadSuccessProofImage()"
                        class="rounded-lg border border-emerald-300 px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50">
                        Unduh Gambar (JPG)
                    </button>
                    <button type="button" onclick="closeSuccessProofModal()"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active styles from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Add active styles to selected tab
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            activeButton.classList.add('border-blue-500', 'text-blue-600');
        }

        // Default: Show records tab on load
        document.addEventListener('DOMContentLoaded', function () {
            switchTab(<?php echo json_encode($defaultTab, 15, 512) ?>);
        });
    </script>

    <script>
        async function downloadSuccessProofImage() {
            const target = document.getElementById('success-proof-export');
            if (!target || !window.htmlToImage || typeof window.htmlToImage.toJpeg !== 'function') {
                alert('Fitur unduh gambar belum siap. Coba refresh halaman.');
                return;
            }

            try {
                const dataUrl = await window.htmlToImage.toJpeg(target, {
                    backgroundColor: '#ffffff',
                    quality: 0.82,
                    pixelRatio: Math.min(2, window.devicePixelRatio || 1),
                    cacheBust: true,
                    skipAutoScale: false,
                });

                const link = document.createElement('a');
                const timestamp = new Date().toISOString().slice(0, 19).replace(/[:T]/g, '-');
                link.href = dataUrl;
                link.download = `bukti-input-oil-${timestamp}.jpg`;
                document.body.appendChild(link);
                link.click();
                link.remove();
            } catch (error) {
                console.error('downloadSuccessProofImage', error);
                alert('Gagal mengunduh gambar. Silakan coba lagi.');
            }
        }

        function closeSuccessProofModal() {
            const modal = document.getElementById('successProofModal');
            if (modal) {
                modal.remove();
            }
        }

        // Handle delete confirmations with SweetAlert2
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const itemName = this.dataset.itemName || 'data ini';
                    const confirmed = await window.confirmDelete(itemName);
                    if (confirmed) {
                        if (typeof window.lockFormSubmission === 'function') {
                            const submitter = e.submitter || this.querySelector('button[type="submit"], input[type="submit"]');
                            window.lockFormSubmission(this, submitter);
                        }
                        this.submit();
                    }
                });
            });

            const successProofModal = document.getElementById('successProofModal');
            if (successProofModal) {
                successProofModal.addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeSuccessProofModal();
                    }
                });
            }
        });
    </script>

    <?php if($successProof): ?>
        <script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.11/dist/html-to-image.js"></script>
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
<?php endif; ?><?php /**PATH D:\Project1YBS\resources\views/oil/index.blade.php ENDPATH**/ ?>