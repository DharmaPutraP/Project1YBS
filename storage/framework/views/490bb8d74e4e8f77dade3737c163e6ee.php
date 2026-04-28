<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'eyebrow',
    'title',
    'description',
    'inputUrl',
    'filterUrl',
    'exportUrl',
    'startDate',
    'endDate',
    'rows',
    'machineOptions' => [],
    'selectedMachine' => 'all',
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
    'eyebrow',
    'title',
    'description',
    'inputUrl',
    'filterUrl',
    'exportUrl',
    'startDate',
    'endDate',
    'rows',
    'machineOptions' => [],
    'selectedMachine' => 'all',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $totalRecords = method_exists($rows, 'total') ? $rows->total() : $rows->count();
    $displayedRecords = method_exists($rows, 'count') ? $rows->count() : 0;
    $periodLabel = $startDate === $endDate
        ? \Carbon\Carbon::parse($startDate)->format('d M Y')
        : \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d M Y');
?>

<div class="mx-auto max-w-7xl space-y-8">
    <div class="flex flex-col gap-4 rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400"><?php echo e($eyebrow); ?></p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900"><?php echo e($title); ?></h1>
            <p class="mt-2 text-sm text-slate-500"><?php echo e($description); ?></p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="<?php echo e($inputUrl); ?>" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Input Data</a>
            <?php if($exportUrl): ?>
                <form method="POST" action="<?php echo e($exportUrl); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="start_date" value="<?php echo e($startDate); ?>">
                    <input type="hidden" name="end_date" value="<?php echo e($endDate); ?>">
                    <?php if(!empty($machineOptions)): ?>
                        <input type="hidden" name="machine_name" value="<?php echo e($selectedMachine); ?>">
                    <?php endif; ?>
                    <button type="submit" class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">Export Excel</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-indigo-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Total Records</p>
            <p class="mt-1 text-2xl md:text-3xl font-bold text-gray-800"><?php echo e(number_format($totalRecords)); ?></p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-green-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Data Ditampilkan</p>
            <p class="mt-1 text-2xl md:text-3xl font-bold text-gray-800"><?php echo e(number_format($displayedRecords)); ?></p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-blue-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Periode</p>
            <p class="mt-1 text-sm md:text-base font-semibold text-gray-700"><?php echo e($periodLabel); ?></p>
        </div>
    </div>

    <div class="mb-4 md:mb-6 bg-gray-50 rounded-lg p-3 md:p-4 border border-gray-200">
        <form method="GET" action="<?php echo e($filterUrl); ?>" class="flex flex-col gap-3 md:flex-row md:items-end">
            <div class="flex-1 w-full">
                <label for="start_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Tanggal Awal</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo e($startDate); ?>" class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div class="flex-1 w-full">
                <label for="end_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo e($endDate); ?>" class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <?php if(!empty($machineOptions)): ?>
                <div class="flex-1 w-full">
                    <label for="machine_name" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Mesin</label>
                    <select id="machine_name" name="machine_name" class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="all" <?php if($selectedMachine === 'all'): echo 'selected'; endif; ?>>Semua Mesin</option>
                        <?php $__currentLoopData = $machineOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($machine); ?>" <?php if($selectedMachine === $machine): echo 'selected'; endif; ?>><?php echo e($machine); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <button type="submit" class="inline-flex items-center justify-center px-4 md:px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm whitespace-nowrap">Filter</button>
                <?php if(request()->hasAny(['start_date', 'end_date', 'machine_name']) && (request('start_date') != now()->toDateString() || request('end_date') != now()->toDateString() || request('machine_name', 'all') !== 'all')): ?>
                    <a href="<?php echo e($filterUrl); ?>" class="inline-flex items-center justify-center px-4 md:px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium text-sm whitespace-nowrap">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php if(session('success')): ?>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['title' => $title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title)]); ?>
        <?php echo e($slot); ?>

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
</div><?php /**PATH D:\ybs\Project1YBS\resources\views/components/process/analisa-moisture/page-shell.blade.php ENDPATH**/ ?>