{{--
Layout: App (Authenticated)
Digunakan untuk semua halaman yang membutuhkan autentikasi.
Menyertakan sidebar navigasi dan topbar.

Props:
- $title (string) : judul halaman (ditampilkan di <title> dan heading)
    Slots:
    - $slot : konten utama halaman
    --}}
    @props(['title' => 'Dashboard'])

    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title }} — {{ config('app.name', 'YBS') }}</title>
        @vite('resources/css/app.css')
    </head>

    <body class="min-h-screen bg-gray-50 flex">

        {{-- ── Sidebar ──────────────────────────────────────────────── --}}
        <x-sidebar />

        {{-- ── Konten Utama ─────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col min-h-screen">

            {{-- Topbar --}}
            <x-navbar :title="$title" />

            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-y-auto">
                {{ $slot }}
            </main>

        </div>

    </body>

    </html>