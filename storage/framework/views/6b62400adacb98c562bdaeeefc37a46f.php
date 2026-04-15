
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => '']));

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

foreach (array_filter((['title' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<header
    class="h-16 shrink-0 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6 gap-3 md:gap-4">

    
    <button id="mobileMenuToggle" class="lg:hidden p-2 hover:bg-gray-100 rounded-lg transition-colors"
        aria-label="Toggle Menu">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    
    <h1 class="text-sm md:text-base font-semibold text-gray-800 truncate"><?php echo e($title); ?></h1>

    
    <div class="flex items-center gap-3">

        <?php if(isset($actions)): ?>
            <?php echo e($actions); ?>

        <?php endif; ?>

        
        <!-- <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-red-600 transition"
                title="Logout">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="hidden sm:inline">Logout</span>
            </button>
        </form> -->
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800 truncate"><?php echo e(Auth::user()->name); ?></p>
            <p class="text-xs text-gray-400 truncate">
                <?php echo e(Auth::user()->getRoleNames()->first() ?? '—'); ?>

                <?php if(Auth::user()->office): ?>
                    • <span class="text-green-600 font-semibold"><?php echo e(Auth::user()->office); ?></span>
                <?php endif; ?>
            </p>
        </div>
    </div>

</header><?php /**PATH D:\Project1YBS\resources\views/components/navbar.blade.php ENDPATH**/ ?>