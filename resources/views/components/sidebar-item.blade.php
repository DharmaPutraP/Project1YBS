{{--
Component: Sidebar Item
Satu baris link di dalam sidebar. Digunakan oleh <x-sidebar>.

    Props:
    - $href (string) : URL tujuan
    - $active (bool) : true jika halaman saat ini
    - $icon (html) : SVG string ikon — opsional

    Contoh:
    <x-sidebar-item href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="...svg...">
        Dashboard
    </x-sidebar-item>
    --}}
    @props([
        'href' => '#',
        'active' => false,
        'icon' => null,
    ])

@php
    $base = 'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors';
    $activeCs = 'bg-indigo-50 text-indigo-700';
    $inactiveCs = 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
    $cls = $base . ' ' . ($active ? $activeCs : $inactiveCs);
@endphp
               <a href="{{ $href }}" {{ $attributes->merge(['class' => $cls]) }}>
                @if($icon)
                    <span class="{{ $active ? 'text-indigo-600' : 'text-gray-400' }}">{!! $icon !!}</span>
                @endif
    <span>{{ $slot }}</span>
</a>
