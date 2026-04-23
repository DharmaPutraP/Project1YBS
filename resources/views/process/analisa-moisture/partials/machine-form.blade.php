@php
    $cardClass = $cardClass ?? 'border-gray-200 bg-white';
    $headerClass = $headerClass ?? 'border-gray-100 bg-white';
    $badgeClass = $badgeClass ?? 'bg-indigo-50 text-indigo-700 ring-indigo-100';
@endphp

<div class="overflow-hidden rounded-lg border shadow-sm {{ $cardClass }}">
    <div class="flex items-start justify-between gap-4 border-b px-5 py-4 {{ $headerClass }}">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $section }}</p>
            <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $title }}</h3>
        </div>
        <div class="rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $badgeClass }}">
            {{ $machine }}
        </div>
    </div>

    <div class="space-y-4 p-5">
        <input type="hidden" name="{{ $namePrefix }}[machine_name]" value="{{ $machine }}">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}_tanggal_{{ $machineKey }}">TANGGAL</label>
                <input type="date" id="{{ $fieldPrefix }}_tanggal_{{ $machineKey }}" name="{{ $namePrefix }}[tanggal]" value="{{ old($oldPrefix . '.tanggal') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}_jam_{{ $machineKey }}">JAM</label>
                <input type="time" id="{{ $fieldPrefix }}_jam_{{ $machineKey }}" name="{{ $namePrefix }}[jam]" value="{{ old($oldPrefix . '.jam') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}_oil_{{ $machineKey }}">OIL</label>
                <input type="number" step="0.01" id="{{ $fieldPrefix }}_oil_{{ $machineKey }}" name="{{ $namePrefix }}[oil]" value="{{ old($oldPrefix . '.oil') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}_emulsi_{{ $machineKey }}">EMULSI</label>
                <input type="number" step="0.01" id="{{ $fieldPrefix }}_emulsi_{{ $machineKey }}" name="{{ $namePrefix }}[emulsi]" value="{{ old($oldPrefix . '.emulsi') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}_air_{{ $machineKey }}">AIR</label>
                <input type="number" step="0.01" id="{{ $fieldPrefix }}_air_{{ $machineKey }}" name="{{ $namePrefix }}[air]" value="{{ old($oldPrefix . '.air') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}_nos_{{ $machineKey }}">NOS</label>
                <input type="number" step="0.01" id="{{ $fieldPrefix }}_nos_{{ $machineKey }}" name="{{ $namePrefix }}[nos]" value="{{ old($oldPrefix . '.nos') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>
        </div>
    </div>
</div>
