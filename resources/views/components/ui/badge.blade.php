{{--
Component: Badge
Label kecil berwarna untuk status / kategori.

Props:
- $color (string) : 'green' | 'red' | 'yellow' | 'blue' | 'gray' | 'indigo'
default: 'gray'

Contoh:
<x-ui.badge color="green">Approved</x-ui.badge>
<x-ui.badge color="red">Rejected</x-ui.badge>
<x-ui.badge color="yellow">Pending</x-ui.badge>
--}}
@props(['color' => 'gray'])

@php
    $colors = [
        'green' => 'bg-green-100 text-green-700 ring-green-200',
        'red' => 'bg-red-100 text-red-700 ring-red-200',
        'yellow' => 'bg-yellow-100 text-yellow-700 ring-yellow-200',
        'blue' => 'bg-blue-100 text-blue-700 ring-blue-200',
        'indigo' => 'bg-indigo-100 text-indigo-700 ring-indigo-200',
        'gray' => 'bg-gray-100 text-gray-600 ring-gray-200',
    ];

    $cls = $colors[$color] ?? $colors['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ring-inset $cls"]) }}>
    {{ $slot }}
</span>