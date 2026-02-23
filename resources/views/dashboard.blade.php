{{-- Menggunakan layout app (sidebar + navbar) --}}
<x-layouts.app title="Dashboard">

    {{-- Kartu ringkasan selamat datang --}}
    <x-ui.card title="Selamat Datang">
        <p class="text-gray-600">
            Halo, <strong>{{ Auth::user()->name }}</strong>!
            Anda masuk sebagai
            <x-ui.badge color="indigo">{{ Auth::user()->getRoleNames()->first() ?? '—' }}</x-ui.badge>.
        </p>
    </x-ui.card>

    {{-- Tambahkan widget/statistik di sini sesuai kebutuhan --}}

</x-layouts.app>