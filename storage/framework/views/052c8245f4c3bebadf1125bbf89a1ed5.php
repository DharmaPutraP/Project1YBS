
    <?php if($errors->any()): ?>
        <?php if (isset($component)) { $__componentOriginal746de018ded8594083eb43be3f1332e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal746de018ded8594083eb43be3f1332e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert','data' => ['type' => 'error']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'error']); ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <p><?php echo e($e); ?></p> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $attributes = $__attributesOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__attributesOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal746de018ded8594083eb43be3f1332e1)): ?>
<?php $component = $__componentOriginal746de018ded8594083eb43be3f1332e1; ?>
<?php unset($__componentOriginal746de018ded8594083eb43be3f1332e1); ?>
<?php endif; ?>
    <?php endif; ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type'    => 'info',
    'title'   => null,
    'dismiss' => false,
]));

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

foreach (array_filter(([
    'type'    => 'info',
    'title'   => null,
    'dismiss' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $styles = [
        'success' => [
            'wrap'  => 'bg-green-50 border-green-300 text-green-800',
            'icon'  => '✓',
            'icncl' => 'text-green-500',
        ],
        'error' => [
            'wrap'  => 'bg-red-50 border-red-300 text-red-800',
            'icon'  => '✕',
            'icncl' => 'text-red-500',
        ],
        'warning' => [
            'wrap'  => 'bg-yellow-50 border-yellow-300 text-yellow-800',
            'icon'  => '!',
            'icncl' => 'text-yellow-500',
        ],
        'info' => [
            'wrap'  => 'bg-blue-50 border-blue-300 text-blue-800',
            'icon'  => 'i',
            'icncl' => 'text-blue-500',
        ],
    ];

    $s = $styles[$type] ?? $styles['info'];
?>

<div
    <?php echo e($attributes->merge(['class' => "flex gap-3 border rounded-lg px-4 py-3 text-sm {$s['wrap']}"])); ?>

    role="alert"
    <?php if($dismiss): ?> x-data="{ show: true }" x-show="show" <?php endif; ?>
>
    
    <span class="mt-0.5 shrink-0 font-bold <?php echo e($s['icncl']); ?>"><?php echo e($s['icon']); ?></span>

    
    <div class="flex-1">
        <?php if($title): ?>
            <p class="font-semibold mb-0.5"><?php echo e($title); ?></p>
        <?php endif; ?>
        <?php echo e($slot); ?>

    </div>

    
    <?php if($dismiss): ?>
        <button @click="show = false" class="ml-auto shrink-0 opacity-60 hover:opacity-100 transition focus:outline-none">
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    <?php endif; ?>
</div>
<?php /**PATH D:\ybs\Project1YBS\resources\views\components\ui\alert.blade.php ENDPATH**/ ?>