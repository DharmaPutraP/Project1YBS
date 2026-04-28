<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Data Analisa Spintest COT']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Data Analisa Spintest COT']); ?>
    <?php if (isset($component)) { $__componentOriginal32385c4499efdb1d5d7e58b63e7bdfc1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal32385c4499efdb1d5d7e58b63e7bdfc1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.process.analisa-moisture.page-shell','data' => ['eyebrow' => 'Analisa Moisture & Spintes','title' => 'Data Analisa Spintest COT','description' => 'Menampilkan data tersimpan untuk periode yang dipilih.','inputUrl' => route('analisa-moisture.input'),'filterUrl' => route('analisa-moisture.spintest-cot'),'exportUrl' => route('analisa-moisture.spintest-cot.export'),'startDate' => $startDate,'endDate' => $endDate,'rows' => $rows]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('process.analisa-moisture.page-shell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Analisa Moisture & Spintes','title' => 'Data Analisa Spintest COT','description' => 'Menampilkan data tersimpan untuk periode yang dipilih.','input-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('analisa-moisture.input')),'filter-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('analisa-moisture.spintest-cot')),'export-url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('analisa-moisture.spintest-cot.export')),'start-date' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($startDate),'end-date' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($endDate),'rows' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rows)]); ?>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold">Jam</th>
                            <th class="px-4 py-3 text-left font-semibold">Created By</th>
                            <th class="px-4 py-3 text-left font-semibold">Office</th>
                            <th class="px-4 py-3 text-left font-semibold">OIL</th>
                            <th class="px-4 py-3 text-left font-semibold">EMULSI</th>
                            <th class="px-4 py-3 text-left font-semibold">AIR</th>
                            <th class="px-4 py-3 text-left font-semibold">NOS</th>
                            <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Super Admin')): ?>
                                <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                            <?php endif; ?>
                        </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-3 text-slate-700"><?php echo e($row->tanggal?->format('d-m-Y')); ?></td>
                                <td class="px-4 py-3 text-slate-700"><?php echo e($row->jam); ?></td>
                                <td class="px-4 py-3 text-slate-700"><?php echo e($row->created_by ?: ($row->user?->name ?? '-')); ?></td>
                                <td class="px-4 py-3 text-slate-700"><?php echo e($row->office ?: '-'); ?></td>
                                <td class="px-4 py-3 text-slate-700"><?php echo e($row->oil); ?></td>
                                <td class="px-4 py-3 text-slate-700"><?php echo e($row->emulsi); ?></td>
                                <td class="px-4 py-3 text-slate-700"><?php echo e($row->air); ?></td>
                                <td class="px-4 py-3 text-slate-700"><?php echo e($row->nos); ?></td>
                                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Super Admin')): ?>
                                    <td class="px-4 py-3 text-slate-700">
                                        <div class="flex items-center gap-2">
                                            <a href="<?php echo e(route('analisa-moisture.spintest-cot.edit', $row)); ?>" class="inline-flex items-center rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-200">Edit</a>
                                            <form method="POST" action="<?php echo e(route('analisa-moisture.spintest-cot.destroy', $row)); ?>" onsubmit="return confirm('Hapus data ini?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="inline-flex items-center rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-200">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="<?php echo e(auth()->user()?->hasRole('Super Admin') ? 9 : 8); ?>" class="px-4 py-8 text-center text-slate-500">Belum ada data pada periode ini.</td>
                            </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 px-4 py-4">
            <?php echo e($rows->links()); ?>

        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal32385c4499efdb1d5d7e58b63e7bdfc1)): ?>
<?php $attributes = $__attributesOriginal32385c4499efdb1d5d7e58b63e7bdfc1; ?>
<?php unset($__attributesOriginal32385c4499efdb1d5d7e58b63e7bdfc1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal32385c4499efdb1d5d7e58b63e7bdfc1)): ?>
<?php $component = $__componentOriginal32385c4499efdb1d5d7e58b63e7bdfc1; ?>
<?php unset($__componentOriginal32385c4499efdb1d5d7e58b63e7bdfc1); ?>
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
<?php endif; ?>
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/analisa-moisture/spintest-cot.blade.php ENDPATH**/ ?>