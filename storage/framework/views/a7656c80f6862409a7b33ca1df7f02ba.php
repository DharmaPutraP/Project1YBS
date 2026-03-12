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
                    <label for="office" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                        Office/PT
                    </label>
                    <select name="office" id="office"
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="all" <?php echo e($officeFilter == 'all' ? 'selected' : ''); ?>>-- Semua Office --</option>
                        <option value="YBS" <?php echo e($officeFilter == 'YBS' ? 'selected' : ''); ?>>YBS</option>
                        <option value="SUN" <?php echo e($officeFilter == 'SUN' ? 'selected' : ''); ?>>SUN</option>
                        <option value="SJN" <?php echo e($officeFilter == 'SJN' ? 'selected' : ''); ?>>SJN</option>
                    </select>
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
                                                $oilLoss->kode == 'COT IN' ? $isGood = $olwb_calc > $limitolwb && $limitolwb > 0 : $isGood = $olwb_calc <= $limitolwb && $limitolwb > 0;
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
            switchTab('records');
        });
    </script>

    <script>
        // Handle delete confirmations with SweetAlert2
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
<?php endif; ?><?php /**PATH D:\Project1YBS\resources\views/oil/index.blade.php ENDPATH**/ ?>