<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Edit Permission — '.e($role->name).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Edit Permission — '.e($role->name).'']); ?>

    
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
                <a href="<?php echo e(route('permissions.index')); ?>" class="hover:text-indigo-600 transition">Permission
                    Control</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600 font-medium"><?php echo e($role->name); ?></span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Permission</h1>
            <p class="mt-1 text-sm text-gray-500">Atur hak akses untuk role <strong><?php echo e($role->name); ?></strong>.</p>
        </div>
        <a href="<?php echo e(route('permissions.index')); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 bg-white
                   text-sm font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    
    <?php if(session('success')): ?>
        <div id="flashMessage"
            class="mb-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 shadow-sm">
            <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span><?php echo session('success'); ?></span>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('permissions.update', $role)); ?>" method="POST" id="permissionForm">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-xl
                    border border-gray-200 bg-white px-5 py-3 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900"><?php echo e($role->name); ?></p>
                    <p class="text-xs text-gray-400">
                        <span id="selectedCount" class="font-semibold text-indigo-600">0</span>
                        / <?php echo e($permissions->count()); ?> permission aktif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="btnSelectAll"
                    class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-medium
                           text-gray-600 hover:bg-gray-50 transition">
                    Pilih Semua
                </button>
                <button type="button" id="btnClearAll"
                    class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-medium
                           text-gray-600 hover:bg-gray-50 transition">
                    Hapus Semua
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-indigo-600
                           hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>

        
        <div class="space-y-4">
            <?php $__currentLoopData = $groupedPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $perms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($module === 'Lainnya'): ?> <?php continue; ?> <?php endif; ?>
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
                    
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-indigo-500"></span>
                            <h3 class="text-sm font-bold text-gray-800"><?php echo e($module); ?></h3>
                            <span class="text-[11px] text-gray-400 font-normal">
                                (<?php echo e($perms->count()); ?> permission)
                            </span>
                        </div>
                        <button type="button" class="btn-toggle-module text-xs text-indigo-500 hover:text-indigo-700 font-medium transition"
                            data-permissions='<?php echo json_encode($perms->pluck('name')->toArray(), 15, 512) ?>'>
                            Toggle modul
                        </button>
                    </div>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        <?php $__currentLoopData = $perms->sortBy('name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="permission-label flex items-center gap-3 px-3 py-2 rounded-lg border cursor-pointer select-none
                                       transition hover:border-indigo-300 hover:bg-indigo-50/40 border-gray-200 bg-white">
                                <input type="checkbox" 
                                    name="permissions[]" 
                                    value="<?php echo e($permission->name); ?>"
                                    class="permission-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600
                                           focus:ring-indigo-500 cursor-pointer flex-shrink-0"
                                    <?php echo e(in_array($permission->name, $rolePermissions) ? 'checked' : ''); ?>

                                />
                                <span class="text-xs font-mono text-gray-700 leading-tight">
                                    <?php echo e($permission->name); ?>

                                </span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="mt-6 flex justify-end gap-3">
            <a href="<?php echo e(route('permissions.index')); ?>" class="px-5 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-medium
                       text-gray-600 hover:bg-gray-50 transition shadow-sm">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600
                       hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Perubahan
            </button>
        </div>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const form = document.getElementById('permissionForm');
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const selectedCount = document.getElementById('selectedCount');
            const btnSelectAll = document.getElementById('btnSelectAll');
            const btnClearAll = document.getElementById('btnClearAll');
            const btnToggleModules = document.querySelectorAll('.btn-toggle-module');
            const flashMessage = document.getElementById('flashMessage');

            // Auto hide flash message after 4 seconds
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.transition = 'opacity 0.3s ease';
                    flashMessage.style.opacity = '0';
                    setTimeout(() => flashMessage.remove(), 300);
                }, 4000);
            }

            // Update counter
            function updateCounter() {
                const checked = document.querySelectorAll('.permission-checkbox:checked').length;
                selectedCount.textContent = checked;
            }

            // Update label styling based on checkbox state
            function updateLabelStyling(checkbox) {
                const label = checkbox.closest('.permission-label');
                if (checkbox.checked) {
                    label.classList.remove('border-gray-200', 'bg-white');
                    label.classList.add('border-indigo-300', 'bg-indigo-50');
                } else {
                    label.classList.remove('border-indigo-300', 'bg-indigo-50');
                    label.classList.add('border-gray-200', 'bg-white');
                }
            }

            // Initialize: Update count and styling for all checkboxes
            checkboxes.forEach(checkbox => {
                updateLabelStyling(checkbox);
                
                checkbox.addEventListener('change', function() {
                    updateCounter();
                    updateLabelStyling(this);
                });
            });
            
            // Initial count
            updateCounter();

            // Select All button
            btnSelectAll.addEventListener('click', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    updateLabelStyling(checkbox);
                });
                updateCounter();
            });

            // Clear All button
            btnClearAll.addEventListener('click', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    updateLabelStyling(checkbox);
                });
                updateCounter();
            });

            // Toggle Module buttons
            btnToggleModules.forEach(btn => {
                btn.addEventListener('click', function() {
                    const permissions = JSON.parse(this.dataset.permissions);
                    const moduleCheckboxes = Array.from(checkboxes).filter(cb => 
                        permissions.includes(cb.value)
                    );
                    
                    // Check if all in module are checked
                    const allChecked = moduleCheckboxes.every(cb => cb.checked);
                    
                    // Toggle: if all checked, uncheck all; otherwise check all
                    moduleCheckboxes.forEach(cb => {
                        cb.checked = !allChecked;
                        updateLabelStyling(cb);
                    });
                    
                    updateCounter();
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
<?php endif; ?><?php /**PATH D:\Project1YBS\resources\views/permissions/edit.blade.php ENDPATH**/ ?>