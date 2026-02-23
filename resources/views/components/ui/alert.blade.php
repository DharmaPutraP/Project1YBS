{{--
  Component: Alert
  Kotak notifikasi untuk menampilkan pesan sukses, error, warning, atau info.

  Props:
    - $type    (string) : 'success' | 'error' | 'warning' | 'info'  — default: 'info'
    - $title   (string) : judul opsional
    - $dismiss (bool)   : tampilkan tombol × untuk sembunyikan         — default: false
  Slot:
    - pesan utama

  Contoh:
    <x-ui.alert type="success">Data berhasil disimpan.</x-ui.alert>

    <x-ui.alert type="error" title="Terjadi Kesalahan" :dismiss="true">
        Username atau password salah.
    </x-ui.alert>

    {{-- Menampilkan validation errors Laravel --}}
    @if ($errors->any())
        <x-ui.alert type="error">
            @foreach ($errors->all() as $e) <p>{{ $e }}</p> @endforeach
        </x-ui.alert>
    @endif
--}}
@props([
    'type'    => 'info',
    'title'   => null,
    'dismiss' => false,
])

@php
    $styles = [
        'success' => [
            'wrap'  => 'bg-green-50 border-green-300 text-green-800',
            'icon'  => '✓',
            'icncl' => 'text-green-500',
        ],
        'error' => [
            'wrap'  => 'bg-red-50 border-red-300 text-red-800',
            'icon'  => '✕',
            'icncl' => 'text-red-500',
        ],
        'warning' => [
            'wrap'  => 'bg-yellow-50 border-yellow-300 text-yellow-800',
            'icon'  => '!',
            'icncl' => 'text-yellow-500',
        ],
        'info' => [
            'wrap'  => 'bg-blue-50 border-blue-300 text-blue-800',
            'icon'  => 'i',
            'icncl' => 'text-blue-500',
        ],
    ];

    $s = $styles[$type] ?? $styles['info'];
@endphp

<div
    {{ $attributes->merge(['class' => "flex gap-3 border rounded-lg px-4 py-3 text-sm {$s['wrap']}"]) }}
    role="alert"
    @if($dismiss) x-data="{ show: true }" x-show="show" @endif
>
    {{-- Ikon --}}
    <span class="mt-0.5 shrink-0 font-bold {{ $s['icncl'] }}">{{ $s['icon'] }}</span>

    {{-- Teks --}}
    <div class="flex-1">
        @if ($title)
            <p class="font-semibold mb-0.5">{{ $title }}</p>
        @endif
        {{ $slot }}
    </div>

    {{-- Tombol dismiss (butuh Alpine.js) --}}
    @if ($dismiss)
        <button @click="show = false" class="ml-auto shrink-0 opacity-60 hover:opacity-100 transition focus:outline-none">
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    @endif
</div>
