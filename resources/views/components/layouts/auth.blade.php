{{--
Layout: Auth
Digunakan untuk halaman login & register.

Props:
- $title (string) : judul tab browser
--}}
@props(['title' => config('app.name', 'YBS')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — {{ config('app.name', 'YBS') }}</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-md w-full max-w-md p-8">
        {{ $slot }}
    </div>

</body>

</html>