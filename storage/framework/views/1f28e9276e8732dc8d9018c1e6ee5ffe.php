<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Activity Log']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Activity Log']); ?>

    
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Activity Log</h1>
        <p class="mt-2 text-sm text-gray-600">
            Riwayat aktivitas pengguna dalam sistem (input, edit, delete, export, dll)
        </p>
    </div>

    
    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'mb-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-6']); ?>
        <form method="GET" action="<?php echo e(route('activity-logs.index')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        User
                    </label>
                    <select name="user_id" class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                        <option value="all">-- Semua User --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Aksi
                    </label>
                    <select name="action" class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                        <option value="all">-- Semua Aksi --</option>
                        <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($action); ?>" <?php echo e(request('action') == $action ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($action)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tipe Data
                    </label>
                    <select name="model_type" class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                        <option value="all">-- Semua Tipe --</option>
                        <?php $__currentLoopData = $modelTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>" <?php echo e(request('model_type') == $type ? 'selected' : ''); ?>>
                                <?php echo e($type); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Cari Deskripsi
                    </label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari..."
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" name="start_date"
                        value="<?php echo e(request('start_date', now()->startOfMonth()->format('Y-m-d'))); ?>"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                </div>

                
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Akhir
                    </label>
                    <input type="date" name="end_date" value="<?php echo e(request('end_date', now()->format('Y-m-d'))); ?>"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                </div>
            </div>

            
            <div class="flex flex-col sm:flex-row gap-2">
                <button type="submit"
                    class="px-4 sm:px-6 py-1.5 sm:py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>

                <?php if(request()->hasAny(['user_id', 'action', 'model_type', 'search', 'start_date', 'end_date'])): ?>
                    <a href="<?php echo e(route('activity-logs.index')); ?>"
                        class="px-4 sm:px-6 py-1.5 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium text-center">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                <?php endif; ?>
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
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Waktu
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IP Address
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Detail
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                                <div><?php echo e($log->created_at->format('d M Y')); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($log->created_at->format('H:i:s')); ?></div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <?php echo e($log->user_name ?? 'System'); ?>

                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        <?php if($log->action_badge_color == 'green'): ?> bg-green-100 text-green-800
                                        <?php elseif($log->action_badge_color == 'yellow'): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($log->action_badge_color == 'red'): ?> bg-red-100 text-red-800
                                        <?php elseif($log->action_badge_color == 'blue'): ?> bg-blue-100 text-blue-800
                                        <?php elseif($log->action_badge_color == 'indigo'): ?> bg-indigo-100 text-indigo-800
                                        <?php else: ?> bg-gray-100 text-gray-800
                                        <?php endif; ?>">
                                    <?php echo e($log->action_label); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div class="max-w-md truncate"><?php echo e($log->description); ?></div>
                                <?php if($log->model_type): ?>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <?php echo e(class_basename($log->model_type)); ?> #<?php echo e($log->model_id); ?>

                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 font-mono">
                                <?php echo e($log->ip_address); ?>

                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <a href="<?php echo e(route('activity-logs.show', $log->id)); ?>"
                                    class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>Tidak ada activity log yang ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($logs->hasPages()): ?>
            <div class="mt-4 px-4">
                <?php echo e($logs->links()); ?>

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
<?php endif; ?><?php /**PATH D:\Project1YBS\resources\views/activity-logs/index.blade.php ENDPATH**/ ?>