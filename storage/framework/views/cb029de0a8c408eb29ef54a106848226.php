<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Input Oil Loss Foss']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Input Oil Loss Foss']); ?>
    <div class="mx-auto max-w-7xl">
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Input Oil Loss Foss']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Input Oil Loss Foss']); ?>
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Oil Loss Foss</p>
                    <h1 class="mt-1 text-2xl font-bold text-slate-900">Input Oil Loss Foss</h1>
                    <p class="mt-2 text-sm text-slate-500">Isi data per titik mesin. Tanggal, waktu, operator, dan shift ada di setiap kartu mesin.</p>
                </div>
                <a href="<?php echo e(route('oil-loss-foss.data')); ?>" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Lihat Data</a>
            </div>

            <?php if(session('success')): ?>
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-inside list-disc">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('oil-loss-foss.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>

                <?php
                    $groupStyleMap = [
                        'COT' => [
                            'section' => 'border-emerald-200 bg-emerald-50',
                            'badge' => 'bg-emerald-100 text-emerald-700',
                            'card' => 'border-emerald-200 bg-white',
                        ],
                        'CST' => [
                            'section' => 'border-amber-200 bg-amber-50',
                            'badge' => 'bg-amber-100 text-amber-700',
                            'card' => 'border-amber-200 bg-white',
                        ],
                        'FD' => [
                            'section' => 'border-sky-200 bg-sky-50',
                            'badge' => 'bg-sky-100 text-sky-700',
                            'card' => 'border-sky-200 bg-white',
                        ],
                        'HP' => [
                            'section' => 'border-indigo-200 bg-indigo-50',
                            'badge' => 'bg-indigo-100 text-indigo-700',
                            'card' => 'border-indigo-200 bg-white',
                        ],
                        'SD' => [
                            'section' => 'border-rose-200 bg-rose-50',
                            'badge' => 'bg-rose-100 text-rose-700',
                            'card' => 'border-rose-200 bg-white',
                        ],
                        'HPL' => [
                            'section' => 'border-fuchsia-200 bg-fuchsia-50',
                            'badge' => 'bg-fuchsia-100 text-fuchsia-700',
                            'card' => 'border-fuchsia-200 bg-white',
                        ],
                        'FE' => [
                            'section' => 'border-cyan-200 bg-cyan-50',
                            'badge' => 'bg-cyan-100 text-cyan-700',
                            'card' => 'border-cyan-200 bg-white',
                        ],
                        'FBP' => [
                            'section' => 'border-orange-200 bg-orange-50',
                            'badge' => 'bg-orange-100 text-orange-700',
                            'card' => 'border-orange-200 bg-white',
                        ],
                        'FP' => [
                            'section' => 'border-violet-200 bg-violet-50',
                            'badge' => 'bg-violet-100 text-violet-700',
                            'card' => 'border-violet-200 bg-white',
                        ],
                    ];
                ?>

                <div class="grid gap-6 xl:grid-cols-1">
                    <?php $__currentLoopData = $machineGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $machines): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $style = $groupStyleMap[$group] ?? [
                                'section' => 'border-slate-200 bg-slate-50',
                                'badge' => 'bg-slate-100 text-slate-700',
                                'card' => 'border-slate-200 bg-white',
                            ];
                        ?>

                        <section class="space-y-4 rounded-xl border p-4 <?php echo e($style['section']); ?>">
                            <div class="flex items-center justify-between gap-3">
                                <h2 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-800"><?php echo e($group); ?></h2>
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold <?php echo e($style['badge']); ?>"><?php echo e(count($machines)); ?> kode</span>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <?php $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="overflow-hidden rounded-lg border shadow-sm <?php echo e($style['card']); ?>">
                                        <div class="flex items-start justify-between gap-4 border-b px-5 py-4 <?php echo e($style['section']); ?>">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Oil Loss Foss</p>
                                                <h3 class="mt-1 text-lg font-bold text-slate-900"><?php echo e($machine['label']); ?></h3>
                                                <p class="mt-1 text-xs text-slate-600"><?php echo e($machine['sample_name'] ?? $machine['label']); ?></p>
                                            </div>
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1 <?php echo e($style['badge']); ?>"><?php echo e($group); ?></span>
                                        </div>

                                        <div class="space-y-4 p-5">
                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">TANGGAL</label>
                                                    <input type="date" name="rows[<?php echo e($machine['key']); ?>][tanggal]" value="<?php echo e(old('rows.' . $machine['key'] . '.tanggal', now()->toDateString())); ?>" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                </div>
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">WAKTU</label>
                                                    <input type="time" name="rows[<?php echo e($machine['key']); ?>][waktu]" value="<?php echo e(old('rows.' . $machine['key'] . '.waktu', now()->format('H:i'))); ?>" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">OPERATOR</label>
                                                    <select name="rows[<?php echo e($machine['key']); ?>][operator]" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                        <?php $__empty_1 = true; $__currentLoopData = $operatorOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operatorOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                            <option value="<?php echo e($operatorOption); ?>" <?php if((string) old('rows.' . $machine['key'] . '.operator', $defaultOperator) === (string) $operatorOption): echo 'selected'; endif; ?>><?php echo e($operatorOption); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                            <option value="<?php echo e($defaultOperator); ?>"><?php echo e($defaultOperator); ?></option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">SHIFT</label>
                                                    <select name="rows[<?php echo e($machine['key']); ?>][shift]" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                        <?php $__currentLoopData = $shiftOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($shift); ?>" <?php if((string) old('rows.' . $machine['key'] . '.shift', '1') === (string) $shift): echo 'selected'; endif; ?>><?php echo e($shift); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">MOIST (%)</label>
                                                    <input type="number" step="0.01" min="0" name="rows[<?php echo e($machine['key']); ?>][moist]" value="<?php echo e(old('rows.' . $machine['key'] . '.moist')); ?>" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                </div>
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">OLWB (%)</label>
                                                    <input type="number" step="0.01" min="0" name="rows[<?php echo e($machine['key']); ?>][olwb]" value="<?php echo e(old('rows.' . $machine['key'] . '.olwb')); ?>" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </section>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="flex flex-wrap gap-3 border-t border-slate-200 pt-4">
                    <button type="submit" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Simpan Semua Data</button>
                    <a href="<?php echo e(route('oil-loss-foss.rekap')); ?>" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Lihat Rekap</a>
                </div>
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
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/oil-loss-foss/input.blade.php ENDPATH**/ ?>