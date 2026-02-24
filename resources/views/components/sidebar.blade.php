{{--
Component: Sidebar
Navigasi utama aplikasi YBS.
Setiap menu item dijaga dengan @can agar hanya tampil sesuai hak akses role.
--}}

<style>
    #sidebar {
        transition: width 0.3s ease, visibility 0.3s ease;
    }

    #sidebar.collapsed {
        width: 150px;
        overflow: hidden;
    }

    #sidebar.collapsed .sidebar-header {
        flex-direction: column;
        gap: 0;
    }

    #sidebar.collapsed .sidebar-brand-text {
        display: none;
    }

    #sidebar.collapsed .sidebar-nav {
        padding: 6px;
    }

    #sidebar.collapsed .sidebar-nav p {
        display: none;
    }

    #sidebar.collapsed .sidebar-item-text {
        display: none;
    }

    #sidebar.collapsed .sidebar-user-info {
        display: none;
    }

    #sidebar.collapsed nav {
        padding: 0.75rem 0.5rem;
    }

    .sidebar-item-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>

<aside id="sidebar" class="w-64 shrink-0 bg-white border-r border-gray-200 flex flex-col min-h-screen shadow-sm">

    {{-- ── Brand / Logo ──────────────────────────────────────── --}}
    <div
        class="sidebar-header h-16 flex items-center justify-between px-6 border-b border-gray-500 bg-gray-50 gap-2 flex-row">
        <div class="flex items-center gap-2 min-w-0">
            <span class="text-2xl font-bold text-indigo-600 tracking-tight">YBS</span>
            <span class="sidebar-brand-text text-lg text-gray-500 font-medium">Management</span>
            <button id="sidebarToggle" class="block sm:hidden p-2 hover:bg-gray-200 rounded-lg transition flex-shrink-0"
                title="Toggle Sidebar">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>
    </div>

    {{-- ── Navigasi ──────────────────────────────────────────── --}}
    <nav class="sidebar-nav flex-1 px-3 py-6 mt-6 space-y-1.5 overflow-y-auto">

        {{-- Dashboard: semua role bisa akses --}}
        @can('view dashboard')
            <x-sidebar-item href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>'>
                <span class="sidebar-item-text">Dashboard</span>
            </x-sidebar-item>
        @endcan

        {{-- ── Laboratorium ────────────────────────────────── --}}
        @canany(['view lab', 'create lab results', 'approve lab results'])
            <p class="px-3 pt-5 pb-1 mt-4 text-[11px] uppercase tracking-widest text-gray-400 font-semibold">
                Laboratorium
            </p>

            {{-- Data Oil Losses --}}
            @can('view lab')
                <x-sidebar-item href="{{ route('lab.index') }}" :active="request()->routeIs('lab.index')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>'>
                    <span class="sidebar-item-text">Data Oil Losses</span>
                </x-sidebar-item>
            @endcan

            {{-- Input Data Baru --}}
            @can('create lab results')
                <x-sidebar-item href="{{ route('lab.create') }}" :active="request()->routeIs('lab.create')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>'>
                    <span class="sidebar-item-text">Input Data Baru</span>
                </x-sidebar-item>
            @endcan

            {{-- Pending Approval --}}
            <!-- @can('approve lab results')
                    <x-sidebar-item href="{{ route('lab.index', ['status' => 'submitted']) }}"
                        :active="request()->routeIs('lab.index') && request('status') === 'submitted'"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'>
                        <span class="sidebar-item-text">Pending Approval</span>
                    </x-sidebar-item>
                @endcan -->

            {{-- Lab Samples (for future) --}}
            @can('view lab samples')
                <x-sidebar-item href="#" :active="false"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>'>
                    <span class="sidebar-item-text">Kelola Sampel</span>
                </x-sidebar-item>
            @endcan
        @endcanany

        {{-- ── Timbangan ───────────────────────────────────── --}}
        @canany(['view timbangan', 'create timbangan'])
            <p class="px-3 pt-5 pb-1 mt-4 text-[11px] uppercase tracking-widest text-gray-400 font-semibold">
                Timbangan
            </p>

            @can('view timbangan')
                <x-sidebar-item href="{{ route('timbangan.index') }}" :active="request()->routeIs('timbangan.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>'>
                    <span class="sidebar-item-text">Data Timbangan</span>
                </x-sidebar-item>
            @endcan
        @endcanany

        {{-- ── Laporan ─────────────────────────────────────── --}}
        @can('view reports')
            <p class="px-3 pt-5 pb-1 mt-4 text-[11px] uppercase tracking-widest text-gray-400 font-semibold">
                Laporan
            </p>

            <x-sidebar-item href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'>
                <span class="sidebar-item-text">Laporan</span>
            </x-sidebar-item>
        @endcan

        {{-- ── Admin ───────────────────────────────────────── --}}
        @canany(['view users', 'view roles', 'view settings'])
            <p class="px-3 pt-5 pb-1 mt-4 text-[11px] uppercase tracking-widest text-gray-400 font-semibold">
                Administrasi
            </p>

            @can('view users')
                <x-sidebar-item href="{{ route('users.index') }}" :active="request()->routeIs('users.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>'>
                    <span class="sidebar-item-text">Kelola Pengguna</span>
                </x-sidebar-item>
            @endcan

            @can('view settings')
                <x-sidebar-item href="{{ route('settings.index') }}" :active="request()->routeIs('settings.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'>
                    <span class="sidebar-item-text">Pengaturan</span>
                </x-sidebar-item>
            @endcan
        @endcanany

    </nav>

    {{-- ── Info User (bawah sidebar) ──────────────────────── --}}

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <div class="sidebar-user-info px-4 py-4 border-t border-gray-200 bg-gray-50">
            <button type="submit" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-red-600 transition"
                title="Logout">

                <div class="flex items-center gap-3 cursor-pointer">
                    <div
                        class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">
                        <!-- {{ strtoupper(substr(Auth::user()->name, 0, 1)) }} -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">Logout</p>
                        <!-- <p class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->getRoleNames()->first() ?? '—' }}</p> -->
                    </div>
                </div>
            </button>
        </div>
    </form>

</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function (e) {
                e.preventDefault();
                sidebar.classList.toggle('collapsed');

                // Simpan status ke localStorage
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });

            // Restore dari localStorage pada load
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            }
        }
    });
</script>