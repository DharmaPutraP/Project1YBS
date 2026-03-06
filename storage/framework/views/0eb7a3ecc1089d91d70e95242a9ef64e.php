
    <?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
        'href' => '#',
        'active' => false,
        'icon' => null,
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
        'href' => '#',
        'active' => false,
        'icon' => null,
    ]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $base = 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors';
    $activeCs = 'bg-indigo-50 text-indigo-700';
    $inactiveCs = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
    $cls = $base . ' ' . ($active ? $activeCs : $inactiveCs);
?>
               <a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => $cls])); ?>>
                <?php if($icon): ?>
                    <span class="<?php echo e($active ? 'text-indigo-600' : 'text-gray-400'); ?>"><?php echo $icon; ?></span>
                <?php endif; ?>
    <span><?php echo e($slot); ?></span>
</a>
<?php /**PATH D:\Project1YBS\resources\views\components\sidebar-item.blade.php ENDPATH**/ ?>