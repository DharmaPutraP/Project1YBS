{{-- Menggunakan layout app (sidebar + navbar) --}}
<x-layouts.app title="Dashboard">

    {{-- Kartu ringkasan selamat datang --}}
    <x-ui.card title="Selamat Datang" class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-lg">
                    Halo, <strong class="text-indigo-600">{{ Auth::user()->name }}</strong>! 👋
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Anda terhubung sebagai <x-ui.badge
                        color="indigo">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</x-ui.badge>
                </p>
            </div>
        </div>
    </x-ui.card>

</x-layouts.app>