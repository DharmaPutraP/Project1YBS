<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gray-100">

    <nav class="bg-white shadow px-6 py-4 flex items-center justify-between">
        <span class="text-lg font-bold text-indigo-600">My App</span>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">Hello, <strong>{{ Auth::user()->username }}</strong></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:underline">Logout</button>
            </form>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto mt-12 p-6 bg-white rounded-2xl shadow">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Dashboard</h1>
        <p class="text-gray-600">Welcome back, <strong>{{ Auth::user()->name }}</strong>! You are logged in.</p>
    </main>

</body>
</html>
