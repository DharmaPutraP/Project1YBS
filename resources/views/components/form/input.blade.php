{{--
  Component: Form Input Field
  Menggabungkan <label> + <input> + pesan error menjadi satu komponen.
  Mirip pola <FormField> / <InputGroup> di React.

  Props:
    - $label       (string)  : teks label — wajib
    - $id          (string)  : id & for pada label/input — default: sama dengan $name
    - $name        (string)  : name attribute input   — wajib
    - $type        (string)  : text | password | email | number | date ... — default: 'text'
    - $value       (mixed)   : nilai awal (gunakan old() di luar jika perlu)
    - $placeholder (string)  : placeholder
    - $required    (bool)    : wajib diisi                 — default: false
    - $autofocus   (bool)    : fokus otomatis              — default: false
    - $disabled    (bool)    : nonaktifkan input           — default: false
    - $hint        (string)  : teks bantuan di bawah input — opsional

  Contoh:
    <x-form.input
        label="Username"
        name="username"
        :value="old('username')"
        required
        autofocus
        placeholder="Masukkan username"
    />

    <x-form.input
        label="Password"
        name="password"
        type="password"
        required
        hint="Minimal 8 karakter"
    />
--}}
@props([
    'label'       => '',
    'name'        => '',
    'id'          => null,
    'type'        => 'text',
    'value'       => null,
    'placeholder' => '',
    'required'    => false,
    'autofocus'   => false,
    'disabled'    => false,
    'hint'        => null,
])

@php
    $inputId  = $id ?? $name;
    $hasError = $errors->has($name);

    $inputClass = 'w-full border rounded-lg px-4 py-2 text-sm transition
                   focus:outline-none focus:ring-2 '
        . ($hasError
            ? 'border-red-400 bg-red-50 focus:ring-red-400 text-red-800'
            : 'border-gray-300 focus:ring-indigo-500 text-gray-900')
        . ($disabled ? ' opacity-50 cursor-not-allowed bg-gray-50' : '');
@endphp

<div class="space-y-1">
    <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
        @if($required)
            <span class="text-red-500 ml-0.5">*</span>
        @endif
    </label>

    <input
        id="{{ $inputId }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required  ? 'required'  : '' }}
        {{ $autofocus ? 'autofocus' : '' }}
        {{ $disabled  ? 'disabled'  : '' }}
        {{ $attributes->merge(['class' => $inputClass]) }}
    >

    @error($name)
        <p class="text-xs text-red-600">{{ $message }}</p>
    @enderror

    @if($hint && !$hasError)
        <p class="text-xs text-gray-400">{{ $hint }}</p>
    @endif
</div>
