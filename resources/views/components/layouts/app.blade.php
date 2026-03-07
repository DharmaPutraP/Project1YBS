{{--
Layout: App (Authenticated)
Digunakan untuk semua halaman yang membutuhkan autentikasi.
Menyertakan sidebar navigasi dan topbar.
--}}
@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ config('app.name', 'YBS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    {{-- Offline Indicator --}}
    <x-offline-indicator />

    {{-- Mobile Backdrop Overlay --}}
    <div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>

    {{-- ── Layout Wrapper: flex row (sidebar | konten) ────────────── --}}
    <div class="flex min-h-screen">

        {{-- ── Sidebar ──────────────────────────────────────────────── --}}
        <x-sidebar />

        {{-- ── Konten Utama ─────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Topbar --}}
            <x-navbar :title="$title" />

            {{-- Page Content --}}
            <main class="flex-1 p-4 md:p-6 overflow-x-hidden">
                {{ $slot }}
            </main>

        </div>

    </div>

    {{-- Auto Flash Messages dengan SweetAlert Toast --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                window.showSuccess({!! json_encode(session('success')) !!});
            @endif

            @if (session('error'))
                window.showError({!! json_encode(session('error')) !!});
            @endif

            @if (session('info'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: {!! json_encode(session('info')) !!},
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: {!! json_encode(session('warning')) !!},
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            @endif
        });

        // Mobile Sidebar Toggle
        const mobileToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        function toggleMobileSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        if (mobileToggle) {
            mobileToggle.addEventListener('click', toggleMobileSidebar);
        }

        if (backdrop) {
            backdrop.addEventListener('click', toggleMobileSidebar);
        }

        // Close sidebar saat resize ke desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        });
    </script>

</body>

</html>