
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['color' => 'gray']));

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

foreach (array_filter((['color' => 'gray']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $colors = [
        'green' => 'bg-green-100 text-green-700 ring-green-200',
        'red' => 'bg-red-100 text-red-700 ring-red-200',
        'yellow' => 'bg-yellow-100 text-yellow-700 ring-yellow-200',
        'blue' => 'bg-blue-100 text-blue-700 ring-blue-200',
        'indigo' => 'bg-indigo-100 text-indigo-700 ring-indigo-200',
        'gray' => 'bg-gray-100 text-gray-600 ring-gray-200',
    ];

    $cls = $colors[$color] ?? $colors['gray'];
?>

<span <?php echo e($attributes->merge(['class' => "inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset $cls"])); ?>>
    <?php echo e($slot); ?>

</span><?php /**PATH D:\Project1YBS\resources\views/components/ui/badge.blade.php ENDPATH**/ ?>