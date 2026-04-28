<?php
    $cardClass = $cardClass ?? 'border-gray-200 bg-white';
    $headerClass = $headerClass ?? 'border-gray-100 bg-white';
    $badgeClass = $badgeClass ?? 'bg-indigo-50 text-indigo-700 ring-indigo-100';
?>

<div class="overflow-hidden rounded-lg border shadow-sm <?php echo e($cardClass); ?>">
    <div class="flex items-start justify-between gap-4 border-b px-5 py-4 <?php echo e($headerClass); ?>">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400"><?php echo e($section); ?></p>
            <h3 class="mt-1 text-lg font-bold text-slate-900"><?php echo e($title); ?></h3>
        </div>
        <div class="rounded-full px-3 py-1 text-xs font-semibold ring-1 <?php echo e($badgeClass); ?>">
            <?php echo e($machine); ?>

        </div>
    </div>

    <div class="space-y-4 p-5">
        <input type="hidden" name="<?php echo e($namePrefix); ?>[machine_name]" value="<?php echo e($machine); ?>">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="<?php echo e($fieldPrefix); ?>_tanggal_<?php echo e($machineKey); ?>">TANGGAL</label>
                <input type="date" id="<?php echo e($fieldPrefix); ?>_tanggal_<?php echo e($machineKey); ?>" name="<?php echo e($namePrefix); ?>[tanggal]" value="<?php echo e(old($oldPrefix . '.tanggal')); ?>"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="<?php echo e($fieldPrefix); ?>_jam_<?php echo e($machineKey); ?>">JAM</label>
                <input type="time" id="<?php echo e($fieldPrefix); ?>_jam_<?php echo e($machineKey); ?>" name="<?php echo e($namePrefix); ?>[jam]" value="<?php echo e(old($oldPrefix . '.jam')); ?>"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="<?php echo e($fieldPrefix); ?>_oil_<?php echo e($machineKey); ?>">OIL</label>
                <input type="number" step="0.01" id="<?php echo e($fieldPrefix); ?>_oil_<?php echo e($machineKey); ?>" name="<?php echo e($namePrefix); ?>[oil]" value="<?php echo e(old($oldPrefix . '.oil')); ?>"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="<?php echo e($fieldPrefix); ?>_emulsi_<?php echo e($machineKey); ?>">EMULSI</label>
                <input type="number" step="0.01" id="<?php echo e($fieldPrefix); ?>_emulsi_<?php echo e($machineKey); ?>" name="<?php echo e($namePrefix); ?>[emulsi]" value="<?php echo e(old($oldPrefix . '.emulsi')); ?>"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="<?php echo e($fieldPrefix); ?>_air_<?php echo e($machineKey); ?>">AIR</label>
                <input type="number" step="0.01" id="<?php echo e($fieldPrefix); ?>_air_<?php echo e($machineKey); ?>" name="<?php echo e($namePrefix); ?>[air]" value="<?php echo e(old($oldPrefix . '.air')); ?>"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="<?php echo e($fieldPrefix); ?>_nos_<?php echo e($machineKey); ?>">NOS</label>
                <input type="number" step="0.01" id="<?php echo e($fieldPrefix); ?>_nos_<?php echo e($machineKey); ?>" name="<?php echo e($namePrefix); ?>[nos]" value="<?php echo e(old($oldPrefix . '.nos')); ?>"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\ybs\Project1YBS\resources\views/process/analisa-moisture/partials/machine-form.blade.php ENDPATH**/ ?>