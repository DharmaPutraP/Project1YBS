@props([
    'eyebrow',
    'title',
    'description',
    'inputUrl',
    'filterUrl',
    'exportUrl',
    'startDate',
    'endDate',
    'rows',
    'machineOptions' => [],
    'selectedMachine' => 'all',
])

@php
    $totalRecords = method_exists($rows, 'total') ? $rows->total() : $rows->count();
    $displayedRecords = method_exists($rows, 'count') ? $rows->count() : 0;
    $periodLabel = $startDate === $endDate
        ? \Carbon\Carbon::parse($startDate)->format('d M Y')
        : \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d M Y');
@endphp

<div class="mx-auto max-w-7xl space-y-8">
    <div class="flex flex-col gap-4 rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $eyebrow }}</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">{{ $title }}</h1>
            <p class="mt-2 text-sm text-slate-500">{{ $description }}</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ $inputUrl }}" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Input Data</a>
            @if($exportUrl)
                <form method="POST" action="{{ $exportUrl }}">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    @if (!empty($machineOptions))
                        <input type="hidden" name="machine_name" value="{{ $selectedMachine }}">
                    @endif
                    <button type="submit" class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">Export Excel</button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-indigo-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Total Records</p>
            <p class="mt-1 text-2xl md:text-3xl font-bold text-gray-800">{{ number_format($totalRecords) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-green-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Data Ditampilkan</p>
            <p class="mt-1 text-2xl md:text-3xl font-bold text-gray-800">{{ number_format($displayedRecords) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-blue-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Periode</p>
            <p class="mt-1 text-sm md:text-base font-semibold text-gray-700">{{ $periodLabel }}</p>
        </div>
    </div>

    <div class="mb-4 md:mb-6 bg-gray-50 rounded-lg p-3 md:p-4 border border-gray-200">
        <form method="GET" action="{{ $filterUrl }}" class="flex flex-col gap-3 md:flex-row md:items-end">
            <div class="flex-1 w-full">
                <label for="start_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Tanggal Awal</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div class="flex-1 w-full">
                <label for="end_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            @if (!empty($machineOptions))
                <div class="flex-1 w-full">
                    <label for="machine_name" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Mesin</label>
                    <select id="machine_name" name="machine_name" class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="all" @selected($selectedMachine === 'all')>Semua Mesin</option>
                        @foreach ($machineOptions as $machine)
                            <option value="{{ $machine }}" @selected($selectedMachine === $machine)>{{ $machine }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <button type="submit" class="inline-flex items-center justify-center px-4 md:px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm whitespace-nowrap">Filter</button>
                @if(request()->hasAny(['start_date', 'end_date', 'machine_name']) && (request('start_date') != now()->toDateString() || request('end_date') != now()->toDateString() || request('machine_name', 'all') !== 'all'))
                    <a href="{{ $filterUrl }}" class="inline-flex items-center justify-center px-4 md:px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium text-sm whitespace-nowrap">Reset</a>
                @endif
            </div>
        </form>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <x-ui.card :title="$title">
        {{ $slot }}
    </x-ui.card>
</div>