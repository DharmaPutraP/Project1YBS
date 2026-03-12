<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Laporan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Laporan']); ?>

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Laporan</h1>
        <p class="mt-2 text-sm text-gray-600">
            Tabel data Oil Losses Lengkap
        </p>
    </div>

    
    <div class="mb-6 bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
        <form method="GET" action="<?php echo e(route('reports.index')); ?>"
            class="flex flex-col sm:flex-row gap-2 sm:gap-4 items-end">

            <div class="flex-1 w-full">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Filter Office/PT
                </label>
                <select name="office" class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                    <option value="all" <?php echo e($officeFilter == 'all' ? 'selected' : ''); ?>>-- Semua Office --</option>
                    <option value="YBS" <?php echo e($officeFilter == 'YBS' ? 'selected' : ''); ?>>YBS</option>
                    <option value="SUN" <?php echo e($officeFilter == 'SUN' ? 'selected' : ''); ?>>SUN</option>
                    <option value="SJN" <?php echo e($officeFilter == 'SJN' ? 'selected' : ''); ?>>SJN</option>
                </select>
            </div>

            <div class="flex-1 w-full">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Filter Kode
                </label>
                <select name="kode" class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                    <option value="">-- Semua Kode --</option>
                    <?php $__currentLoopData = $kodeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kodeValue => $kodeLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kodeValue); ?>" <?php echo e(request('kode') == $kodeValue ? 'selected' : ''); ?>>
                            <?php echo e($kodeLabel); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="flex-1 w-full">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Tanggal Mulai
                </label>
                <input type="date" name="start_date" value="<?php echo e($startDate); ?>"
                    class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
            </div>

            <div class="flex-1 w-full">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Tanggal Akhir
                </label>
                <input type="date" name="end_date" value="<?php echo e($endDate); ?>"
                    class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <button type="submit"
                    class="px-4 sm:px-6 py-1.5 sm:py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                    Filter
                </button>

                <?php if(request()->hasAny(['kode', 'start_date', 'end_date', 'office'])): ?>
                    <a href="<?php echo e(route('reports.index')); ?>"
                        class="px-4 sm:px-6 py-1.5 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium text-center">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['export laporan oil losses'])): ?>
            <form method="POST" action="<?php echo e(route('reports.export')); ?>" class="mt-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="office" value="<?php echo e($officeFilter); ?>">
                <input type="hidden" name="kode" value="<?php echo e(request('kode')); ?>">
                <input type="hidden" name="start_date" value="<?php echo e($startDate); ?>">
                <input type="hidden" name="end_date" value="<?php echo e($endDate); ?>">

                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export ke Excel
                </button>
            </form>
        <?php endif; ?>
    </div>

    
    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'mt-8','title' => 'Data Laporan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-8','title' => 'Data Laporan']); ?>
        <div class="relative overflow-x-auto overflow-y-auto max-h-[500px] border rounded-lg">

            <table class="min-w-[4500px] text-xs text-gray-700 divide-y divide-gray-200">

                
                <thead class="bg-blue-50 sticky top-0 z-40">
                    <tr>
                        <th class="sticky top-0 left-0 z-[60] bg-blue-50 border px-4 py-3 w-[60px]">NO</th>
                        <th class="sticky top-0 lg:left-[60px] z-[50] bg-blue-50 border px-4 py-3 w-[120px]">BULAN</th>
                        <th class="sticky top-0 lg:left-[180px] z-[50] bg-blue-50 border px-4 py-3 w-[120px]">TANGGAL
                        </th>
                        <th class="sticky top-0 lg:left-[300px] z-[50] bg-blue-50 border px-4 py-3 w-[100px]">JAM</th>
                        <th class="sticky top-0 lg:left-[400px] z-[50] bg-blue-50 border px-4 py-3 w-[120px]">KODE</th>
                        <th class="sticky top-0 lg:left-[520px] z-[50] bg-blue-50 border px-4 py-3 w-[160px]">INPUTED BY
                        </th>

                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">NAMA PIVOT</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OPERATOR</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">SAMPEL BOY</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">JENIS OLAH</th>

                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">CAWAN KOSONG</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">BERAT SAMPEL BASAH</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">CAWAN + BASAH</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">CAWAN + KERING</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">SETELAH OVEN</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LABU KOSONG</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OIL + LABU</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">MINYAK</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">MOIST (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">DM/WM (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OLWB (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LIMIT OLWB (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OLDB (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LIMIT OLDB (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OIL LOSSES</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LIMIT OL</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">PERSEN 4</th>
                    </tr>
                </thead>

                
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $calculations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $calc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-200">

                            <td class="sticky left-0 z-[20] bg-white border px-4 py-2">
                                <?php echo e(($calculations->currentPage() - 1) * $calculations->perPage() + $loop->iteration); ?>

                            </td>

                            <td class="border px-4 py-2 lg:sticky lg:left-[60px] z-[10] bg-white">
                                <?php echo e(\Carbon\Carbon::parse($calc->created_at)->format('F Y')); ?>

                            </td>

                            <td class="border px-4 py-2 lg:sticky lg:left-[180px] z-[10] bg-white">
                                <?php echo e(\Carbon\Carbon::parse($calc->created_at)->format('d-m-Y')); ?>

                            </td>

                            <td class="border px-4 py-2 lg:sticky lg:left-[300px] z-[10] bg-white">
                                <?php echo e(\Carbon\Carbon::parse($calc->created_at)->format('H:i:s')); ?>

                            </td>

                            <td class="border px-4 py-2 font-semibold lg:sticky lg:left-[400px] z-[10] bg-white">
                                <?php echo e($calc->kode); ?>

                            </td>

                            <td class="border px-4 py-2 lg:sticky lg:left-[520px] z-[10] bg-white">
                                <?php echo e($calc->user_name ?? '-'); ?>

                            </td>

                            <td class="border px-4 py-2"><?php echo e($calc->pivot ?? '-'); ?></td>
                            <td class="border px-4 py-2"><?php echo e($calc->operator ?? '-'); ?></td>
                            <td class="border px-4 py-2"><?php echo e($calc->sampel_boy ?? '-'); ?></td>
                            <td class="border px-4 py-2"><?php echo e($calc->jenis ?? '-'); ?></td>

                            
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->cawan_kosong_fmt); ?></td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->berat_basah_fmt); ?></td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->total_cawan_basah_fmt); ?></td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->cawan_sample_kering_fmt); ?></td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->sampel_setelah_oven_fmt); ?></td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->labu_kosong_fmt); ?></td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->oil_labu_fmt); ?></td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->minyak_fmt); ?></td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->moist_fmt); ?></td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->dmwm_fmt); ?></td>
                            <td class="border px-4 py-2 text-right">
                                <?php
                                    $olwb_calc = $calc->olwb ?? 0;
                                    $limit_olwb_calc = $calc->limitOLWB ?? 0;
                                    $isGood = $olwb_calc <= $limit_olwb_calc;
                                ?>
                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'font-semibold',
                                    'text-green-600' => $isGood && $limit_olwb_calc > 0,
                                    'text-red-600' => !$isGood && $limit_olwb_calc > 0,
                                ]); ?>"><?php echo e($calc->olwb_fmt); ?></span>
                            </td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->limitOLWB_fmt); ?></td>
                            <td class="border px-4 py-2 text-right"><?php
                                $oldb_calc = $calc->oldb ?? 0;
                                $limit_oldb_calc = $calc->limitOLDB ?? 0;
                                $isGood = $oldb_calc <= $limit_oldb_calc;
                            ?>
                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'font-semibold',
                                    'text-green-600' => $isGood && $limit_oldb_calc > 0,
                                    'text-red-600' => !$isGood && $limit_oldb_calc > 0,
                                ]); ?>"><?php echo e($calc->oldb_fmt); ?></span>
                            </td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->limitOLDB_fmt); ?></td>
                            <td class="border px-4 py-2 text-right">
                                <?php
                                    $oil_losses_calc = $calc->oil_losses ?? 0;
                                    $limit_oil_losses_calc = $calc->limitOL ?? 0;
                                    $isGood = $oil_losses_calc <= $limit_oil_losses_calc;
                                ?>
                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'font-semibold',
                                    'text-green-600' => $isGood && $limit_oil_losses_calc > 0,
                                    'text-red-600' => !$isGood && $limit_oil_losses_calc > 0,
                                ]); ?>"><?php echo e($calc->oil_losses_fmt); ?></span>
                            </td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->limitOL_fmt); ?></td>
                            <td class="border px-4 py-2 text-right"><?php echo e($calc->persen4_fmt); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="30" class="text-center py-10 text-gray-400">
                                Tidak ada data
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>

        <?php if($calculations->hasPages()): ?>
            <div class="mt-6 px-4">
                <?php echo e($calculations->links()); ?>

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
<?php endif; ?><?php /**PATH D:\Project1YBS\resources\views/reports/index.blade.php ENDPATH**/ ?>