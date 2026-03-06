{{-- Menggunakan layout auth + form & UI components --}}
<x-layouts.auth title="Login">

    <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Sign In</h1>

    {{-- Alert validasi - Check custom variable first for fast path --}}
    @if (isset($__username_error))
        <div class="mb-5 bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded">
            {{ $__username_error }}
        </div>
    @elseif ($errors->any())
        <div class="mb-5">
            <x-ui.alert type="error">{{ $errors->first() }}</x-ui.alert>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <x-form.input label="Username" name="username" :value="$__old_username ?? old('username')"
            placeholder="Masukkan username" required autofocus />

        <x-form.input label="Password" name="password" type="password" placeholder="Masukkan password" required />

        {{-- Remember me --}}
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox"
                class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
            <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
        </div>

        <x-ui.button type="submit" variant="primary" class="w-full">
            Login
        </x-ui.button>
    </form>

</x-layouts.auth>