<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Inputan USB']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Inputan USB']); ?>
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Lap Jangkos</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Inputan USB</h1>
                <p class="mt-2 text-sm text-slate-500">Isi salah satu form saja sudah bisa disimpan. Kolom angka yang kosong dianggap 0.</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-inside list-disc">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('lap-jangkos.store-usb')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-4">
                    <?php $__currentLoopData = $rowNumbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rowNumber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-sm font-bold text-slate-900">No Rebusan / Sterilizer <?php echo e($rowNumber); ?></h2>
                                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">USB</span>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">Tgl</label>
                                    <input type="date" name="rows[<?php echo e($rowNumber); ?>][tanggal]" value="<?php echo e(old('rows.' . $rowNumber . '.tanggal')); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">Jam</label>
                                    <input type="time" name="rows[<?php echo e($rowNumber); ?>][jam]" value="<?php echo e(old('rows.' . $rowNumber . '.jam')); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">Shift</label>
                                    <select name="rows[<?php echo e($rowNumber); ?>][shift]" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        <option value="">Pilih Shift</option>
                                        <?php $__currentLoopData = $shiftOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($shift); ?>" <?php if((string) old('rows.' . $rowNumber . '.shift') === (string) $shift): echo 'selected'; endif; ?>><?php echo e($shift); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">Diamati (Jlh Janjang)</label>
                                    <input type="number" step="0.01" min="0" name="rows[<?php echo e($rowNumber); ?>][diamati_jlh_janjang]" value="<?php echo e(old('rows.' . $rowNumber . '.diamati_jlh_janjang')); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">Lolos (Jlh Janjang)</label>
                                    <input type="number" step="0.01" min="0" name="rows[<?php echo e($rowNumber); ?>][lolos_jlh_janjang]" value="<?php echo e(old('rows.' . $rowNumber . '.lolos_jlh_janjang')); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="flex flex-wrap gap-3 border-t border-slate-200 pt-4">
                    <button type="submit" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Simpan</button>
                    <a href="<?php echo e(route('lap-jangkos.data-usb')); ?>" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Lihat Data</a>
                </div>
            </form>
        </div>
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
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/lap-jangkos/input-usb.blade.php ENDPATH**/ ?>