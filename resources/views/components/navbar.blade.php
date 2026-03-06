{{--
Component: Navbar (Topbar)
Header bagian atas untuk halaman yang sudah login.

Props:
- $title (string) : judul halaman saat ini

Slot:
- $actions : tombol/aksi tambahan di sisi kanan (opsional)

Contoh:
<x-navbar title="Dashboard" />

<x-navbar title="Kelola Pengguna">
    <x-slot:actions>
        <x-ui.button size="sm">Tambah User</x-ui.button>
    </x-slot:actions>
</x-navbar>
--}}
@props(['title' => ''])

<header
    class="h-16 shrink-0 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6 gap-3 md:gap-4">

    {{-- Hamburger Menu (Mobile Only) --}}
    <button id="mobileMenuToggle" class="lg:hidden p-2 hover:bg-gray-100 rounded-lg transition-colors"
        aria-label="Toggle Menu">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    {{-- Judul Halaman --}}
    <h1 class="text-sm md:text-base font-semibold text-gray-800 truncate">{{ $title }}</h1>

    {{-- Aksi kanan: slot opsional + info user --}}
    <div class="flex items-center gap-3">

        @isset($actions)
            {{ $actions }}
        @endisset

        {{-- Logout --}}
        <!-- <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-red-600 transition"
                title="Logout">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="hidden sm:inline">Logout</span>
            </button>
        </form> -->
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->getRoleNames()->first() ?? '—' }}</p>
        </div>
    </div>

</header>