
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title'   => null,
    'padding' => 'md',
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
    'title'   => null,
    'padding' => 'md',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $paddings = [
        'none' => '',
        'sm'   => 'p-4',
        'md'   => 'p-6',
        'lg'   => 'p-8',
    ];

    $bodyPadding = $paddings[$padding] ?? $paddings['md'];
?>

<div <?php echo e($attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm border border-gray-100'])); ?>>

    <?php if($title): ?>
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800"><?php echo e($title); ?></h2>
            <?php if(isset($actions)): ?>
                <div class="flex items-center gap-2">
                    <?php echo e($actions); ?>

                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="<?php echo e($bodyPadding); ?>">
        <?php echo e($slot); ?>

    </div>

</div>
<?php /**PATH D:\Project1YBS\resources\views\components\ui\card.blade.php ENDPATH**/ ?>