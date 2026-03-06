
    <?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
        'variant' => 'primary',
        'type' => 'button',
        'size' => 'md',
        'disabled' => false,
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
        'variant' => 'primary',
        'type' => 'button',
        'size' => 'md',
        'disabled' => false,
    ]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $base = 'inline-flex items-center justify-center font-semibold rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    $variants = [
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800 focus:ring-gray-400',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'outline' => 'border border-indigo-600 text-indigo-600 hover:bg-indigo-50 focus:ring-indigo-500',
        'ghost' => 'text-gray-600 hover:bg-gray-100 focus:ring-gray-400',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
?>

    <button
        type="<?php echo e($type); ?>"
    <?php echo e($disabled ? 'disabled' : ''); ?>

    <?php echo e($attributes->merge(['class' => $classes])); ?>

>
    <?php echo e($slot); ?>

</button>
<?php /**PATH D:\ybs\Project1YBS\resources\views/components/ui/button.blade.php ENDPATH**/ ?>