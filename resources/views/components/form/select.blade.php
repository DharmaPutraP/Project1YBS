{{--
  Component: Form Select
  Dropdown select dengan label dan error handling.

  Props:
    - $label    (string) : teks label       — wajib
    - $name     (string) : name attribute   — wajib
    - $id       (string) : override id      — default: $name
    - $selected (mixed)  : nilai terpilih   — opsional
    - $required (bool)   : wajib diisi      — default: false
    - $disabled (bool)   : nonaktifkan      — default: false
    - $placeholder (string) : opsi pertama kosong

  Contoh:
    <x-form.select label="Role" name="role_id" :selected="old('role_id')" required>
        @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
    </x-form.select>
--}}
@props([
    'label'       => '',
    'name'        => '',
    'id'          => null,
    'selected'    => null,
    'required'    => false,
    'disabled'    => false,
    'placeholder' => null,
])

@php
    $inputId  = $id ?? $name;
    $hasError = $errors->has($name);

    $cls = 'w-full border rounded-lg px-4 py-2 text-sm transition focus:outline-none focus:ring-2 '
        . ($hasError
            ? 'border-red-400 bg-red-50 focus:ring-red-400'
            : 'border-gray-300 focus:ring-indigo-500')
        . ($disabled ? ' opacity-50 cursor-not-allowed bg-gray-50' : '');
@endphp

<div class="space-y-1">
    <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if($required)<span class="text-red-500 ml-0.5">*</span>@endif
    </label>

    <select
        id="{{ $inputId }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => $cls]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>

    @error($name)
        <p class="text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
