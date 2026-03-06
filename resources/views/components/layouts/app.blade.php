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

<body class="min-h-screen bg-gray-50 flex overflow-x-hidden">
    {{-- Offline Indicator --}}
    <x-offline-indicator />

    {{-- ── Sidebar ──────────────────────────────────────────────── --}}
    <x-sidebar />

    {{-- ── Konten Utama ─────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">

        {{-- Topbar --}}
        <x-navbar :title="$title" />

        {{-- Page Content --}}
        <main class="flex-1 p-6 overflow-y-auto overflow-x-hidden">
            {{ $slot }}
        </main>

    </div>

    {{-- Auto Flash Messages dengan SweetAlert Toast --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                window.showSuccess('{{ session('success') }}');
            @endif

            @if (session('error'))
                window.showError('{{ session('error') }}');
            @endif

            @if (session('info'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: '{{ session('info') }}',
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
                    title: '{{ session('warning') }}',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            @endif
        });
    </script>

</body>

</html>