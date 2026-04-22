<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Input Data Kernel Losses']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Input Data Kernel Losses']); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: auto !important;
            padding: 0.5rem 1rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            transition: all 0.15s !important;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            outline: none !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0 !important;
            line-height: inherit !important;
            color: #374151 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 0.75rem !important;
        }

        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

        .select2-results__option {
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
        }

        .select2-results__option--highlighted {
            background-color: #3b82f6 !important;
        }

        .select2-container--default.border-red-400 .select2-selection--single {
            border-color: #f87171 !important;
            background-color: #fef2f2 !important;
        }
    </style>
    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => 'Input Data Kernel Losses']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Input Data Kernel Losses']); ?>
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-indigo-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-indigo-900 mb-1">Jam Pengambilan Checklist</h4>
                    <p class="text-sm text-indigo-800">Isi jam pengambilan saat checklist. Jam ini dipakai untuk
                        validasi interval dan jam proses.</p>
                    <div class="mt-2 p-3 bg-white rounded border border-indigo-200">
                        <div class="text-xs text-indigo-700">Waktu Saat Ini (Preview):</div>
                        <div class="text-lg font-bold text-indigo-900" id="currentDateTime">
                            <?php echo e(now()->format('d/m/Y H:i:s')); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        <form action="<?php echo e(route('kernel.store')); ?>" method="POST" id="kernelForm" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="border-2 border-blue-200 bg-blue-50 rounded-lg p-4">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                    <input type="checkbox" name="kegiatan_dispek" id="kegiatan_dispek" value="1" <?php echo e(old('kegiatan_dispek') ? 'checked' : ''); ?>

                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Ada kegiatan dispatch</span>
                </label>
                <label for="rounded_time" class="block text-sm font-medium text-gray-700 mb-2">Jam Pengambilan</label>
                <input type="time" name="rounded_time" id="rounded_time"
                    value="<?php echo e(old('rounded_time', now()->format('H:i'))); ?>" <?php echo e(old('kegiatan_dispek') ? '' : 'disabled'); ?>

                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 <?php $__errorArgs = ['rounded_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['rounded_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="mt-1 text-xs text-gray-500">Jam manual hanya aktif jika kegiatan dispatch dicentang.</p>
            </div>

            <?php $__currentLoopData = $kodeFormGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $count = count($group['items']);
                    $gridClass = $count === 2
                        ? 'md:grid-cols-2'
                        : ($count === 4 ? 'md:grid-cols-2 xl:grid-cols-4' : 'md:grid-cols-3');

                    $groupTheme = 'bg-slate-50 border-slate-200';
                    $cardTheme = 'bg-white border-gray-200';

                    if (str_contains($group['title'], 'Fibre')) {
                        $groupTheme = 'bg-emerald-50 border-emerald-200';
                        $cardTheme = 'bg-emerald-50/60 border-emerald-200';
                    } elseif (str_contains($group['title'], 'LTDS')) {
                        $groupTheme = 'bg-amber-50 border-amber-200';
                        $cardTheme = 'bg-amber-50/60 border-amber-200';
                    } elseif (str_contains($group['title'], 'Claybath')) {
                        $groupTheme = 'bg-sky-50 border-sky-200';
                        $cardTheme = 'bg-sky-50/60 border-sky-200';
                    }
                ?>

                <div class="space-y-3 rounded-xl border p-4 <?php echo e($groupTheme); ?>">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-indigo-900"><?php echo e($group['title']); ?></h3>
                    <div class="grid grid-cols-1 <?php echo e($gridClass); ?> gap-4">
                        <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $kode = $item['kode'];
                                $label = $item['label'];
                            ?>

                            <div class="border rounded-lg p-4 shadow-sm space-y-3 <?php echo e($cardTheme); ?>" data-kernel-row>
                                <div class="flex items-center justify-between gap-3">
                                    <h4 class="text-sm font-semibold text-gray-900"><?php echo e($label); ?></h4>
                                    <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-700"><?php echo e($kode); ?></span>
                                </div>

                                <input type="hidden" name="rows[<?php echo e($kode); ?>][kode]" value="<?php echo e($kode); ?>">

                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Jenis</label>
                                        <select name="rows[<?php echo e($kode); ?>][jenis]"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <option value="">-- Pilih Jenis --</option>
                                            <?php $__currentLoopData = $jenisOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenisValue => $jenisLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($jenisValue); ?>" <?php echo e(old("rows.$kode.jenis", 'TBS') == $jenisValue ? 'selected' : ''); ?>><?php echo e($jenisLabel); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Operator</label>
                                        <?php if(!empty($operatorOptions)): ?>
                                            <select name="rows[<?php echo e($kode); ?>][operator]"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 <?php $__errorArgs = ["rows.$kode.operator"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <option value="">-- Pilih Operator --</option>
                                                <?php $__currentLoopData = $operatorOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operatorName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($operatorName); ?>" <?php echo e(old("rows.$kode.operator") == $operatorName ? 'selected' : ''); ?>><?php echo e($operatorName); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        <?php else: ?>
                                            <input type="text" name="rows[<?php echo e($kode); ?>][operator]"
                                                value="<?php echo e(old("rows.$kode.operator")); ?>" placeholder="Nama operator"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 <?php $__errorArgs = ["rows.$kode.operator"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php endif; ?>
                                        <?php $__errorArgs = ["rows.$kode.operator"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <label class="inline-flex items-center gap-2 text-xs font-medium text-gray-700">
                                    <input type="checkbox" name="rows[<?php echo e($kode); ?>][pengulangan]" value="1" <?php echo e(old("rows.$kode.pengulangan") ? 'checked' : ''); ?> data-remarks-toggle
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span>Data sampel ulang</span>
                                </label>

                                <div class="space-y-1 <?php echo e(old("rows.$kode.pengulangan") ? '' : 'hidden'); ?>" data-remarks-wrapper>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Remarks</label>
                                    <textarea name="rows[<?php echo e($kode); ?>][remarks]" rows="3"
                                        placeholder="Tulis catatan sampel ulang"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"><?php echo e(old("rows.$kode.remarks")); ?></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Berat Sampel (gram)</label>
                                    <input type="number" step="0.0001" name="rows[<?php echo e($kode); ?>][berat_sampel]"
                                        value="<?php echo e(old("rows.$kode.berat_sampel")); ?>" placeholder="0.0000"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 <?php $__errorArgs = ["rows.$kode.berat_sampel"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <?php $__errorArgs = ["rows.$kode.berat_sampel"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nut Utuh - Nut
                                            (gram)</label>
                                        <input type="number" step="0.0001" name="rows[<?php echo e($kode); ?>][nut_utuh_nut]"
                                            data-pair="nut-utuh-nut" data-code="<?php echo e($kode); ?>"
                                            value="<?php echo e(old("rows.$kode.nut_utuh_nut")); ?>" placeholder="0.0000"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nut Utuh - Kernel</label>
                                        <input type="number" step="0.0001" name="rows[<?php echo e($kode); ?>][nut_utuh_kernel]"
                                            data-pair="nut-utuh-kernel" data-code="<?php echo e($kode); ?>"
                                            value="<?php echo e(old("rows.$kode.nut_utuh_kernel")); ?>" placeholder="0.0000"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100"
                                            readonly>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nut Pecah - Nut
                                            (gram)</label>
                                        <input type="number" step="0.0001" name="rows[<?php echo e($kode); ?>][nut_pecah_nut]"
                                            data-pair="nut-pecah-nut" data-code="<?php echo e($kode); ?>"
                                            value="<?php echo e(old("rows.$kode.nut_pecah_nut")); ?>" placeholder="0.0000"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nut Pecah - Kernel</label>
                                        <input type="number" step="0.0001" name="rows[<?php echo e($kode); ?>][nut_pecah_kernel]"
                                            data-pair="nut-pecah-kernel" data-code="<?php echo e($kode); ?>"
                                            value="<?php echo e(old("rows.$kode.nut_pecah_kernel")); ?>" placeholder="0.0000"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100"
                                            readonly>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Kernel Utuh (gram)</label>
                                        <input type="number" step="0.0001" name="rows[<?php echo e($kode); ?>][kernel_utuh]"
                                            value="<?php echo e(old("rows.$kode.kernel_utuh")); ?>" placeholder="0.0000"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Kernel Pecah (gram)</label>
                                        <input type="number" step="0.0001" name="rows[<?php echo e($kode); ?>][kernel_pecah]"
                                            value="<?php echo e(old("rows.$kode.kernel_pecah")); ?>" placeholder="0.0000"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                </div>

                                <?php $__errorArgs = ["rows.$kode.kode"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="<?php echo e(route('kernel.index')); ?>"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">Batal</a>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">Simpan
                    Semua Data</button>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('select[name$="[operator]"]').select2({
                placeholder: '-- Pilih Operator --',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0,
                language: {
                    noResults: function () {
                        return 'Tidak ditemukan';
                    },
                    searching: function () {
                        return 'Mencari...';
                    }
                }
            });
        });

        function updateClock() {
            const now = new Date();
            const pad = n => String(n).padStart(2, '0');
            const el = document.getElementById('currentDateTime');
            if (el) {
                el.textContent =
                    pad(now.getDate()) + '/' + pad(now.getMonth() + 1) + '/' + now.getFullYear() + ' ' +
                    pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
            }
        }

        function toggleRoundedTimeInput() {
            const dispek = document.getElementById('kegiatan_dispek');
            const roundedTime = document.getElementById('rounded_time');
            if (!dispek || !roundedTime) return;
            roundedTime.disabled = !dispek.checked;
        }

        function toggleRemarksField(checkbox) {
            const card = checkbox.closest('[data-kernel-row]');
            const wrapper = card?.querySelector('[data-remarks-wrapper]');
            if (wrapper) {
                wrapper.classList.toggle('hidden', !checkbox.checked);
            }
        }

        function setHalfValue(rawValue, targetInput) {
            const source = parseFloat(rawValue);
            if (rawValue === '' || !Number.isFinite(source)) {
                targetInput.value = '';
                return;
            }

            targetInput.value = (source / 2).toFixed(4);
        }

        function syncKernelPairs() {
            document.querySelectorAll('input[data-pair="nut-utuh-nut"]').forEach(input => {
                const code = input.getAttribute('data-code');
                const target = document.querySelector('input[data-pair="nut-utuh-kernel"][data-code="' + code + '"]');
                if (target) {
                    setHalfValue(input.value, target);
                }
            });

            document.querySelectorAll('input[data-pair="nut-pecah-nut"]').forEach(input => {
                const code = input.getAttribute('data-code');
                const target = document.querySelector('input[data-pair="nut-pecah-kernel"][data-code="' + code + '"]');
                if (target) {
                    setHalfValue(input.value, target);
                }
            });
        }

        updateClock();
        setInterval(updateClock, 1000);

        const kernelForm = document.getElementById('kernelForm');
        if (kernelForm) {
            let isConfirmedSubmit = false;
            kernelForm.addEventListener('submit', async function (e) {
                if (isConfirmedSubmit) {
                    return;
                }

                e.preventDefault();
                const confirmed = await window.confirmSave(this);
                if (confirmed) {
                    isConfirmedSubmit = true;
                    this.submit();
                }
            });
        }

        document.getElementById('kegiatan_dispek')?.addEventListener('change', toggleRoundedTimeInput);
        document.querySelectorAll('[data-remarks-toggle]').forEach(checkbox => {
            checkbox.addEventListener('change', () => toggleRemarksField(checkbox));
            toggleRemarksField(checkbox);
        });
        document.querySelectorAll('input[data-pair="nut-utuh-nut"], input[data-pair="nut-pecah-nut"]').forEach(input => {
            input.addEventListener('input', syncKernelPairs);
            input.addEventListener('change', syncKernelPairs);
        });

        toggleRoundedTimeInput();
        syncKernelPairs();
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
<?php endif; ?><?php /**PATH D:\ybs\Project1YBS\resources\views/kernel/create.blade.php ENDPATH**/ ?>