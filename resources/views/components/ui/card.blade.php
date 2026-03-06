{{--
  Component: Card
  Container dengan shadow dan padding, mirip <Card> di React.

  Props:
    - $title    (string) : judul card — opsional, jika diisi muncul header
    - $padding  (string) : 'none' | 'sm' | 'md' | 'lg'  — default: 'md'
  Slots:
    - $slot     : konten card
    - $actions  : slot opsional untuk tombol di pojok kanan header

  Contoh:
    <x-ui.card title="Data Timbangan">
        ...konten...
    </x-ui.card>

    <x-ui.card title="Hasil Lab">
        <x-slot:actions>
            <x-ui.button size="sm">Tambah</x-ui.button>
        </x-slot:actions>
        ...konten...
    </x-ui.card>
--}}
@props([
    'title'   => null,
    'padding' => 'md',
])

@php
    $paddings = [
        'none' => '',
        'sm'   => 'p-3 md:p-4',
        'md'   => 'p-4 md:p-6',
        'lg'   => 'p-6 md:p-8',
    ];

    $bodyPadding = $paddings[$padding] ?? $paddings['md'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg md:rounded-2xl shadow-sm border border-gray-100']) }}>

    @if ($title)
        <div class="flex items-center justify-between px-4 md:px-6 py-3 md:py-4 border-b border-gray-100">
            <h2 class="text-sm md:text-base font-semibold text-gray-800 truncate">{{ $title }}</h2>
            @isset($actions)
                <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif

    <div class="{{ $bodyPadding }}">
        {{ $slot }}
    </div>

</div>
