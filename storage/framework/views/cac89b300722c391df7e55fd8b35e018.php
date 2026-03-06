
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label'       => '',
    'name'        => '',
    'id'          => null,
    'type'        => 'text',
    'value'       => null,
    'placeholder' => '',
    'required'    => false,
    'autofocus'   => false,
    'disabled'    => false,
    'hint'        => null,
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
    'label'       => '',
    'name'        => '',
    'id'          => null,
    'type'        => 'text',
    'value'       => null,
    'placeholder' => '',
    'required'    => false,
    'autofocus'   => false,
    'disabled'    => false,
    'hint'        => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $inputId  = $id ?? $name;
    $hasError = $errors->has($name);

    $inputClass = 'w-full border rounded-lg px-4 py-2 text-sm transition
                   focus:outline-none focus:ring-2 '
        . ($hasError
            ? 'border-red-400 bg-red-50 focus:ring-red-400 text-red-800'
            : 'border-gray-300 focus:ring-indigo-500 text-gray-900')
        . ($disabled ? ' opacity-50 cursor-not-allowed bg-gray-50' : '');
?>

<div class="space-y-1">
    <label for="<?php echo e($inputId); ?>" class="block text-sm font-medium text-gray-700">
        <?php echo e($label); ?>

        <?php if($required): ?>
            <span class="text-red-500 ml-0.5">*</span>
        <?php endif; ?>
    </label>

    <input
        id="<?php echo e($inputId); ?>"
        name="<?php echo e($name); ?>"
        type="<?php echo e($type); ?>"
        value="<?php echo e(old($name, $value)); ?>"
        placeholder="<?php echo e($placeholder); ?>"
        <?php echo e($required  ? 'required'  : ''); ?>

        <?php echo e($autofocus ? 'autofocus' : ''); ?>

        <?php echo e($disabled  ? 'disabled'  : ''); ?>

        <?php echo e($attributes->merge(['class' => $inputClass])); ?>

    >

    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="text-xs text-red-600"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <?php if($hint && !$hasError): ?>
        <p class="text-xs text-gray-400"><?php echo e($hint); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH D:\ybs\Project1YBS\resources\views\components\form\input.blade.php ENDPATH**/ ?>