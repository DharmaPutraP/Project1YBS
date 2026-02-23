{{--
Component: Sidebar
Navigasi utama aplikasi YBS.
Setiap menu item dijaga dengan @can agar hanya tampil sesuai hak akses role.
--}}

<aside class="w-64 shrink-0 bg-white border-r border-gray-200 flex flex-col min-h-screen">

    {{-- ── Brand / Logo ──────────────────────────────────────── --}}
    <div class="h-16 flex items-center px-6 border-b border-gray-200">
        <span class="text-xl font-bold text-indigo-600 tracking-tight">YBS</span>
        <span class="ml-2 text-sm text-gray-500 font-medium">Management</span>
    </div>

    {{-- ── Navigasi ──────────────────────────────────────────── --}}
    <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto">

        {{-- Dashboard: semua role bisa akses --}}
        @can('view dashboard')
            <x-sidebar-item href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>'>
                Dashboard
            </x-sidebar-item>
        @endcan

        {{-- ── Laboratorium ────────────────────────────────── --}}
        @canany(['view lab', 'input lab results', 'approve lab results'])
            <p class="px-3 pt-5 pb-1 text-[10px] uppercase tracking-widest text-gray-400 font-semibold">
                Laboratorium
            </p>

            @can('view lab')
                <x-sidebar-item href="{{ route('lab.index') }}" :active="request()->routeIs('lab.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>'>
                    Data Lab
                </x-sidebar-item>
            @endcan
        @endcanany

        {{-- ── Timbangan ───────────────────────────────────── --}}
        @canany(['view timbangan', 'input timbangan'])
            <p class="px-3 pt-5 pb-1 text-[10px] uppercase tracking-widest text-gray-400 font-semibold">
                Timbangan
            </p>

            @can('view timbangan')
                <x-sidebar-item href="{{ route('timbangan.index') }}" :active="request()->routeIs('timbangan.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>'>
                    Data Timbangan
                </x-sidebar-item>
            @endcan
        @endcanany

        {{-- ── Laporan ─────────────────────────────────────── --}}
        @can('view reports')
            <p class="px-3 pt-5 pb-1 text-[10px] uppercase tracking-widest text-gray-400 font-semibold">
                Laporan
            </p>

            <x-sidebar-item href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'>
                Laporan
            </x-sidebar-item>
        @endcan

        {{-- ── Admin ───────────────────────────────────────── --}}
        @canany(['manage users', 'manage roles', 'manage settings'])
            <p class="px-3 pt-5 pb-1 text-[10px] uppercase tracking-widest text-gray-400 font-semibold">
                Administrasi
            </p>

            @can('manage users')
                <x-sidebar-item href="{{ route('users.index') }}" :active="request()->routeIs('users.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>'>
                    Kelola Pengguna
                </x-sidebar-item>
            @endcan

            @can('manage settings')
                <x-sidebar-item href="{{ route('settings.index') }}" :active="request()->routeIs('settings.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'>
                    Pengaturan
                </x-sidebar-item>
            @endcan
        @endcanany

    </nav>

    {{-- ── Info User (bawah sidebar) ─────────────────────────── --}}
    <div class="px-4 py-4 border-t border-gray-200">
        <div class="flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->getRoleNames()->first() ?? '—' }}</p>
            </div>
        </div>
    </div>

</aside>