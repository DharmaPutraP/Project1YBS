<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak | {{ config('app.name', 'YBS') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-lg text-center">

        {{-- Error Code --}}
        <h1 class="text-6xl sm:text-8xl font-black text-red-200 leading-none select-none mb-2">403</h1>

        {{-- Title --}}
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3">Akses Ditolak</h2>

        {{-- Description --}}
        <p class="text-sm sm:text-base text-gray-500 mb-8 leading-relaxed max-w-sm mx-auto">
            Anda tidak memiliki izin untuk mengakses halaman ini.
            Hubungi administrator jika Anda merasa ini adalah kesalahan.
        </p>

        {{-- Error detail from exception --}}
        @if($exception->getMessage())
            <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700 text-left max-w-sm mx-auto">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $exception->getMessage() }}</span>
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl
                       bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm
                       transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Ke Beranda
            </a>
            <button onclick="history.back()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl
                       border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium
                       transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </button>
        </div>

        {{-- App Name Footer --}}
        <p class="mt-10 text-xs text-gray-400">
            &copy; {{ date('Y') }} {{ config('app.name', 'YBS') }}
        </p>

    </div>

</body>
</html>
