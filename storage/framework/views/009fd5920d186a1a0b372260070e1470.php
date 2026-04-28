<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 — Sesi Kedaluwarsa | <?php echo e(config('app.name', 'YBS')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-lg text-center">

        
        <h1 class="text-6xl sm:text-8xl font-black text-amber-200 leading-none select-none mb-2">419</h1>

        
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3">Sesi Kedaluwarsa</h2>

        
        <p class="text-sm sm:text-base text-gray-500 mb-8 leading-relaxed max-w-sm mx-auto">
            Sesi Anda telah berakhir karena tidak ada aktivitas dalam beberapa waktu.
            Silakan muat ulang halaman untuk melanjutkan.
        </p>

        
        <div class="mb-6 px-4 py-3 rounded-xl bg-amber-50 border border-amber-200 text-sm text-amber-800 text-left max-w-sm mx-auto">
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Token CSRF tidak valid atau sudah kedaluwarsa. Halaman akan dimuat ulang secara otomatis.</span>
            </div>
        </div>

        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="location.reload()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl
                       bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold shadow-sm
                       transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Muat Ulang Halaman
            </button>
            <a href="<?php echo e(url('/')); ?>"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl
                       border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium
                       transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Ke Beranda
            </a>
        </div>

        
        <p class="mt-10 text-xs text-gray-400">
            &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'YBS')); ?>

        </p>

    </div>

    <script>
        // Auto-reload after 5 seconds
        setTimeout(() => location.reload(), 5000);
    </script>

</body>
</html>
<?php /**PATH D:\ybs\Project1YBS\resources\views/errors/419.blade.php ENDPATH**/ ?>