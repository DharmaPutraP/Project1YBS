{{--
Component: Button
Mirip <Button> di React — menangani semua variant dan state.

    Props:
    - $variant (string) : 'primary' | 'secondary' | 'danger' | 'outline' | 'ghost'
    default: 'primary'
    - $type (string) : 'button' | 'submit' | 'reset' — default: 'button'
    - $size (string) : 'sm' | 'md' | 'lg' — default: 'md'
    - $disabled (bool) : menonaktifkan tombol — default: false
    - $class (string) : class tambahan
    Slot:
    - teks/ikon tombol

    Contoh:
    <x-ui.button type="submit" variant="primary">Login</x-ui.button>
    <x-ui.button variant="danger" size="sm">Hapus</x-ui.button>
    <x-ui.button variant="outline" :disabled="true">Simpan</x-ui.button>
    --}}
    @props([
        'variant' => 'primary',
        'type' => 'button',
        'size' => 'md',
        'disabled' => false,
    ])
@php
    $base = 'inline-flex items-center justify-center font-semibold rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    $variants = [
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500',
        'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800 focus:ring-gray-400',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'outline' => 'border border-indigo-600 text-indigo-600 hover:bg-indigo-50 focus:ring-indigo-500',
        'ghost' => 'text-gray-600 hover:bg-gray-100 focus:ring-gray-400',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

    <button
        type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</button>
