<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Input Analisa Moisture & Spintest.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Input Analisa Moisture & Spintest.']); ?>
    <div class="mx-auto max-w-7xl">
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Input Analisa Moisture Spintest.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Input Analisa Moisture Spintest.']); ?>

        <?php if(session('success')): ?>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <h4 class="text-sm font-semibold text-red-900 mb-2">Data belum bisa disimpan</h4>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('analisa-moisture.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="bulk_submit" value="1">

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="overflow-hidden rounded-lg border border-emerald-200 bg-emerald-50 shadow-sm">
                    <div class="p-5">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-800">Analisa FFA dan Moisture</p>
                            </div>
                            <div class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">FFA Moisture</div>
                        </div>

                        <div class="space-y-4">

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="ffa_tanggal" class="mb-2 block text-sm font-medium text-slate-700">TANGGAL</label>
                            <input type="date" id="ffa_tanggal" name="ffa[tanggal]" value="<?php echo e(old('ffa.tanggal')); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label for="ffa_jam" class="mb-2 block text-sm font-medium text-slate-700">JAM</label>
                            <input type="time" id="ffa_jam" name="ffa[jam]" value="<?php echo e(old('ffa.jam')); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>

                    <div>
                        <label for="ffa_moisture" class="mb-2 block text-sm font-medium text-slate-700">MOISTURE</label>
                        <input type="text" id="ffa_moisture" name="ffa[moisture]" value="<?php echo e(old('ffa.moisture')); ?>"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 <?php echo e($isSunOffice ? 'xl:grid-cols-3' : 'xl:grid-cols-4'); ?>">
                        <div>
                            <label for="ffa_bst1" class="mb-2 block text-sm font-medium text-slate-700">BST1 (FFA)</label>
                            <input type="number" step="0.01" id="ffa_bst1" name="ffa[bst1_ffa]" value="<?php echo e(old('ffa.bst1_ffa')); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label for="ffa_bst2" class="mb-2 block text-sm font-medium text-slate-700">BST2 (FFA)</label>
                            <input type="number" step="0.01" id="ffa_bst2" name="ffa[bst2_ffa]" value="<?php echo e(old('ffa.bst2_ffa')); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <?php if (! ($isSunOffice)): ?>
                            <div>
                                <label for="ffa_bst3" class="mb-2 block text-sm font-medium text-slate-700">BST3 (FFA)</label>
                                <input type="number" step="0.01" id="ffa_bst3" name="ffa[bst3_ffa]" value="<?php echo e(old('ffa.bst3_ffa')); ?>"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                            </div>
                        <?php endif; ?>
                        <div>
                            <label for="ffa_impurities" class="mb-2 block text-sm font-medium text-slate-700">IMPURITIES</label>
                            <input type="number" step="0.01" id="ffa_impurities" name="ffa[impurities]" value="<?php echo e(old('ffa.impurities')); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>

                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg border border-amber-200 bg-amber-50 shadow-sm">
                    <div class="p-5">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-800">Analisa Spintest COT</p>
                            </div>
                            <div class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">COT</div>
                        </div>

                        <div class="space-y-4">

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="cot_tanggal" class="mb-2 block text-sm font-medium text-slate-700">TANGGAL</label>
                            <input type="date" id="cot_tanggal" name="cot[tanggal]" value="<?php echo e(old('cot.tanggal')); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label for="cot_jam" class="mb-2 block text-sm font-medium text-slate-700">JAM</label>
                            <input type="time" id="cot_jam" name="cot[jam]" value="<?php echo e(old('cot.jam')); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <label for="cot_oil" class="mb-2 block text-sm font-medium text-slate-700">OIL</label>
                            <input type="number" step="0.01" id="cot_oil" name="cot[oil]" value="<?php echo e(old('cot.oil')); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label for="cot_emulsi" class="mb-2 block text-sm font-medium text-slate-700">EMULSI</label>
                            <input type="number" step="0.01" id="cot_emulsi" name="cot[emulsi]" value="<?php echo e(old('cot.emulsi')); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label for="cot_air" class="mb-2 block text-sm font-medium text-slate-700">AIR</label>
                            <input type="number" step="0.01" id="cot_air" name="cot[air]" value="<?php echo e(old('cot.air')); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div>
                            <label for="cot_nos" class="mb-2 block text-sm font-medium text-slate-700">NOS</label>
                            <input type="number" step="0.01" id="cot_nos" name="cot[nos]" value="<?php echo e(old('cot.nos')); ?>"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>

                        </div>
                    </div>
                </div>
            </div>

            <section class="space-y-4 mt-8 rounded-xl border border-sky-200 bg-sky-50 p-4">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-800">Analisa Spintest Underflow CST</p>
                    </div>
                </div>

            <div class="grid gap-6 xl:grid-cols-2">
                <?php $__currentLoopData = $cstMachines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('process.analisa-moisture.partials.machine-form', [
                        'section' => 'Analisa Spintest Underflow CST',
                        'title' => '',
                        'machine' => $machine,
                        'module' => 'spintest_cst',
                        'machineKey' => str_replace([' ', '/'], '_', $machine),
                        'namePrefix' => 'machines[spintest_cst][' . str_replace([' ', '/'], '_', $machine) . ']',
                        'oldPrefix' => 'machines.spintest_cst.' . str_replace([' ', '/'], '_', $machine),
                        'fieldPrefix' => 'cst',
                        'cardClass' => 'border-sky-200 bg-white',
                        'headerClass' => 'border-sky-100 bg-sky-50',
                        'badgeClass' => 'bg-sky-100 text-sky-700 ring-sky-200',
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            </section>

            <section class="space-y-4 mt-8 rounded-xl border border-slate-300 bg-slate-100 p-4">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-800">Analisa Spintest Feed Decanter</p>
                    </div>
                </div>

            <div class="grid gap-6 xl:grid-cols-2 2xl:grid-cols-4">
                <?php $__currentLoopData = $decanterMachines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('process.analisa-moisture.partials.machine-form', [
                        'section' => 'Analisa Spintest Feed Decanter',
                        'title' => '',
                        'machine' => $machine,
                        'module' => 'spintest_feed_decanter',
                        'machineKey' => str_replace([' ', '/'], '_', $machine),
                        'namePrefix' => 'machines[spintest_feed_decanter][' . str_replace([' ', '/'], '_', $machine) . ']',
                        'oldPrefix' => 'machines.spintest_feed_decanter.' . str_replace([' ', '/'], '_', $machine),
                        'fieldPrefix' => 'decanter',
                        'cardClass' => 'border-slate-300 bg-white',
                        'headerClass' => 'border-slate-200 bg-slate-50',
                        'badgeClass' => 'bg-slate-200 text-slate-700 ring-slate-300',
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            </section>

            <section class="space-y-4 mt-8 rounded-xl border border-indigo-200 bg-indigo-50 p-4">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-800">Analisa Spintest Light Phase</p>
                    </div>
                </div>

            <div class="grid gap-6 xl:grid-cols-2 2xl:grid-cols-4">
                <?php $__currentLoopData = $lightPhaseMachines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('process.analisa-moisture.partials.machine-form', [
                        'section' => 'Analisa Spintest Light Phase',
                        'title' => '',
                        'machine' => $machine,
                        'module' => 'spintest_light_phase',
                        'machineKey' => str_replace([' ', '/'], '_', $machine),
                        'namePrefix' => 'machines[spintest_light_phase][' . str_replace([' ', '/'], '_', $machine) . ']',
                        'oldPrefix' => 'machines.spintest_light_phase.' . str_replace([' ', '/'], '_', $machine),
                        'fieldPrefix' => 'light',
                        'cardClass' => 'border-indigo-200 bg-white',
                        'headerClass' => 'border-indigo-100 bg-indigo-50',
                        'badgeClass' => 'bg-indigo-100 text-indigo-700 ring-indigo-200',
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            </section>

            <div class="flex flex-wrap justify-end gap-3 border-t border-gray-200 pt-6">
                <a href="<?php echo e(route('analisa-moisture.ffa-moisture')); ?>"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 font-medium text-gray-700 transition hover:bg-gray-100">Batal</a>
                <button type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 font-medium text-white shadow-sm transition hover:bg-indigo-700">Simpan Semua Data</button>
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
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/analisa-moisture/input.blade.php ENDPATH**/ ?>