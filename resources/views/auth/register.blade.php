{{-- Menggunakan layout auth + form & UI components --}}
<x-layouts.auth title="Daftar Akun">

    <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Buat Akun</h1>

    {{-- Alert validasi --}}
    @if ($errors->any())
        <div class="mb-5">
            <x-ui.alert type="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </x-ui.alert>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <x-form.input label="Nama Lengkap" name="name" :value="old('name')" placeholder="Masukkan nama lengkap" required
            autofocus />

        <x-form.input label="Username" name="username" :value="old('username')"
            placeholder="Pilih username (huruf, angka, _ -)" required />

        <x-form.input label="Password" name="password" type="password" placeholder="Buat password baru" required
            hint="Minimal 8 karakter" />

        <x-form.input label="Konfirmasi Password" name="password_confirmation" type="password"
            placeholder="Ulangi password" required />

        <x-ui.button type="submit" variant="primary" class="w-full">
            Daftar
        </x-ui.button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Masuk</a>
    </p>

</x-layouts.auth>