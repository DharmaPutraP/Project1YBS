
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => 'Dashboard']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title' => 'Dashboard']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title); ?> — <?php echo e(config('app.name', 'YBS')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="bg-gray-50">
    
    <?php if (isset($component)) { $__componentOriginal30ed851a7370ef0c75347addc2809e2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30ed851a7370ef0c75347addc2809e2c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.offline-indicator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('offline-indicator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30ed851a7370ef0c75347addc2809e2c)): ?>
<?php $attributes = $__attributesOriginal30ed851a7370ef0c75347addc2809e2c; ?>
<?php unset($__attributesOriginal30ed851a7370ef0c75347addc2809e2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30ed851a7370ef0c75347addc2809e2c)): ?>
<?php $component = $__componentOriginal30ed851a7370ef0c75347addc2809e2c; ?>
<?php unset($__componentOriginal30ed851a7370ef0c75347addc2809e2c); ?>
<?php endif; ?>

    
    <div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>

    
    <div class="flex min-h-screen">

        
        <?php if (isset($component)) { $__componentOriginal2880b66d47486b4bfeaf519598a469d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2880b66d47486b4bfeaf519598a469d6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $attributes = $__attributesOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $component = $__componentOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__componentOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>

        
        <div class="flex-1 flex flex-col min-w-0">

            
            <?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => ['title' => $title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $attributes = $__attributesOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__attributesOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $component = $__componentOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__componentOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>

            
            <main class="flex-1 p-4 md:p-6 overflow-x-hidden">
                <?php echo e($slot); ?>

            </main>

        </div>

    </div>

    
    <script>
        function lockFormSubmission(form, submitter = null) {
            if (!form || form.dataset.submitting === '1') {
                return;
            }

            form.dataset.submitting = '1';

            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            submitButtons.forEach((button) => {
                button.disabled = true;

                if (button.tagName === 'BUTTON') {
                    if (!button.dataset.originalHtml) {
                        button.dataset.originalHtml = button.innerHTML;
                    }
                    if (button === submitter) {
                        button.innerHTML = 'Menyimpan...';
                    }
                } else if (button.tagName === 'INPUT') {
                    if (!button.dataset.originalValue) {
                        button.dataset.originalValue = button.value;
                    }
                    if (button === submitter) {
                        button.value = 'Menyimpan...';
                    }
                }
            });
        }

        window.lockFormSubmission = lockFormSubmission;

        document.addEventListener('DOMContentLoaded', function () {
            <?php if(session('success')): ?>
                window.showSuccess(<?php echo json_encode(session('success')); ?>);
            <?php endif; ?>

            <?php if(session('error')): ?>
                window.showError(<?php echo json_encode(session('error')); ?>);
            <?php endif; ?>

            <?php if(session('info')): ?>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: <?php echo json_encode(session('info')); ?>,
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            <?php endif; ?>

            <?php if(session('warning')): ?>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: <?php echo json_encode(session('warning')); ?>,
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            <?php endif; ?>
        });

        // Mobile Sidebar Toggle
        const mobileToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        function toggleMobileSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        if (mobileToggle) {
            mobileToggle.addEventListener('click', toggleMobileSidebar);
        }

        if (backdrop) {
            backdrop.addEventListener('click', toggleMobileSidebar);
        }

        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            const method = (form.getAttribute('method') || 'get').toLowerCase();
            if (method === 'get' || event.defaultPrevented) {
                return;
            }

            lockFormSubmission(form, event.submitter ?? null);
        }, false);

        // Close sidebar saat resize ke desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        });
    </script>

</body>

</html><?php /**PATH D:\Project1YBS\resources\views/components/layouts/app.blade.php ENDPATH**/ ?>