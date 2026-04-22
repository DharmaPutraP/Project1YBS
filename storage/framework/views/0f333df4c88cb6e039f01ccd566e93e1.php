<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Detail Informasi Proses']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Detail Informasi Proses']); ?>
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Informasi Proses</h1>
            <p class="mt-2 text-sm text-gray-600">Rincian data proses tanggal <?php echo e($record->process_date?->format('d/m/Y')); ?></p>
        </div>
        <a href="<?php echo e(route('process.index')); ?>"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if(!$visibleTeam || $visibleTeam === 'Tim 1'): ?>
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => ''.e($team1['team_name']).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e($team1['team_name']).'']); ?>
            <div class="space-y-3 text-sm">
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Tanggal</p>
                    <p class="mt-1 font-semibold text-gray-900"><?php echo e($team1['process_date']); ?></p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Jam Mulai Proses</p>
                    <p class="mt-1 font-semibold text-gray-900"><?php echo e($team1['process_start'] ?: '-'); ?></p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Jam Akhir Proses</p>
                    <p class="mt-1 font-semibold text-gray-900"><?php echo e($team1['process_end'] ?: '-'); ?></p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3 bg-blue-50">
                    <p class="text-gray-500 text-xs">Total Jam Produksi</p>
                    <p class="mt-1 font-semibold text-gray-900"><?php echo e($team1['production_hours']); ?></p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Anggota Tim</p>
                    <?php if(!empty($team1['members'])): ?>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <?php $__currentLoopData = $team1['members']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                    <?php echo e($member); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="mt-1 font-semibold text-gray-900">-</p>
                    <?php endif; ?>
                </div>
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
        <?php endif; ?>

        <?php if(!$visibleTeam || $visibleTeam === 'Tim 2'): ?>
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => ''.e($team2['team_name']).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e($team2['team_name']).'']); ?>
            <div class="space-y-3 text-sm">
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Tanggal</p>
                    <p class="mt-1 font-semibold text-gray-900"><?php echo e($team2['process_date']); ?></p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Jam Mulai Proses</p>
                    <p class="mt-1 font-semibold text-gray-900"><?php echo e($team2['process_start'] ?: '-'); ?></p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Jam Akhir Proses</p>
                    <p class="mt-1 font-semibold text-gray-900"><?php echo e($team2['process_end'] ?: '-'); ?></p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3 bg-emerald-50">
                    <p class="text-gray-500 text-xs">Total Jam Produksi</p>
                    <p class="mt-1 font-semibold text-gray-900"><?php echo e($team2['production_hours']); ?></p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Anggota Tim</p>
                    <?php if(!empty($team2['members'])): ?>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <?php $__currentLoopData = $team2['members']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                    <?php echo e($member); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="mt-1 font-semibold text-gray-900">-</p>
                    <?php endif; ?>
                </div>
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
        <?php endif; ?>
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
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/show.blade.php ENDPATH**/ ?>