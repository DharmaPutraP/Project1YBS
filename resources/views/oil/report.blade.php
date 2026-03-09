<x-layouts.app title="Report Bobot">

    {{-- ── Header Section ────────────────────────────────────────────── --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Performance</h1>
        <p class="mt-2 text-sm text-gray-600">
            Tabel data OLWB per tanggal dan kode. Data diambil dari Oil Calculations. Perhitungan Bobot melalui limit
        </p>
    </div>

    {{-- ── Main Card ─────────────────────────────────────────────────── --}}
    <x-ui.card>
        {{-- Date Range Filter --}}
        <div class="mb-6 bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
            <form method="GET" action="{{ route('oil.report') }}"
                class="flex flex-col sm:flex-row gap-2 sm:gap-4 items-end">
                <div class="flex-1 w-full">
                    <label for="start_date" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                </div>

                <div class="flex-1 w-full">
                    <label for="end_date" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Akhir
                    </label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                </div>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-1.5 sm:py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>

                    @if(request('start_date') || request('end_date'))
                        <a href="{{ route('oil.report') }}"
                            class="inline-flex items-center justify-center px-4 sm:px-6 py-1.5 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium text-sm">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="mt-3 text-sm text-gray-600">
                <span class="font-medium">Periode:</span>
                <span
                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                </span>
            </div>

            {{-- Export Button --}}
            @canany(['export performance reports oil losses'])
                <div class="mt-3">
                    <form action="{{ route('oil.report.export') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export ke Excel
                        </button>
                    </form>
                </div>
            @endcanany
        </div>

        {{-- Bobot Report Table --}}
        @if(empty($reportData))
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-lg text-gray-500">Tidak ada data pada periode ini</p>
                <p class="mt-2 text-sm text-gray-400">Silakan pilih rentang tanggal lain atau input data baru</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 sticky left-0 bg-indigo-50 z-10 min-w-[120px]">
                                Tanggal
                            </th>
                            @foreach($allKodesData as $kodeInfo)
                                <th
                                    class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[120px]">
                                    {{ $kodeInfo['pivot'] }}
                                </th>
                            @endforeach
                            <th
                                class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[120px] bg-blue-50">
                                AVG PRESS
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[120px] bg-green-50">
                                AVG CLARIFICAT
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reportData as $dateData)
                            {{-- Row 1: OLWB Values --}}
                            <tr class="hover:bg-gray-50">
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-300 sticky left-0 bg-white z-10">
                                    {{ \Carbon\Carbon::parse($dateData['date'])->format('d/m/Y') }}
                                </td>
                                @foreach($allKodesData as $kodeInfo)
                                    @php $kode = $kodeInfo['kode']; @endphp
                                    <td class="px-3 py-3 text-center text-sm border-r border-gray-300">
                                        @if(isset($dateData['kodes'][$kode]) && $dateData['kodes'][$kode]['olwb'] !== null)
                                            @php
                                                $olwb = $dateData['kodes'][$kode]['olwb'];
                                            @endphp
                                            <span class="font-semibold text-gray-900">
                                                @if($olwb < 0)
                                                    ({{ number_format(abs($olwb), 2) }})
                                                @else
                                                    {{ number_format($olwb, 2) }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td colspan="3" class="px-4 py-3 text-center border-r border-gray-300 bg-gray-50">
                                    {{-- Empty cells for AVG columns in OLWB row --}}
                                </td>
                            </tr>

                            {{-- Row 2: Bobot Scores --}}
                            <tr>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-indigo-700 border-r border-gray-300 sticky left-0 bg-indigo-50 z-10">
                                    Bobot
                                </td>
                                @foreach($allKodesData as $kodeInfo)
                                    @php $kode = $kodeInfo['kode']; @endphp
                                    <td class="px-3 py-3 text-center text-sm border-r border-gray-300">
                                        @if(isset($dateData['kodes'][$kode]) && $dateData['kodes'][$kode]['bobot'] !== null)
                                            @php
                                                $bobot = $dateData['kodes'][$kode]['bobot'];
                                                $bgColor = 'bg-red-100';
                                                $textColor = 'text-red-800';

                                                if ($bobot >= 90) {
                                                    $bgColor = 'bg-green-100';
                                                    $textColor = 'text-green-800';
                                                } elseif ($bobot >= 70) {
                                                    $bgColor = 'bg-yellow-100';
                                                    $textColor = 'text-yellow-800';
                                                }
                                            @endphp
                                            <span @class([
                                                'inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold',
                                                $bgColor,
                                                $textColor
                                            ])>
                                                {{ $bobot }}%
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                @endforeach

                                {{-- AVG PRESS --}}
                                <td class="px-4 py-3 text-center border-r border-gray-300 bg-blue-50">
                                    @if($dateData['average_press'] !== null)
                                        @php
                                            $avgPress = $dateData['average_press'];
                                            $bgColor = 'bg-red-100';
                                            $textColor = 'text-red-800';

                                            if ($avgPress >= 90) {
                                                $bgColor = 'bg-green-100';
                                                $textColor = 'text-green-800';
                                            } elseif ($avgPress >= 70) {
                                                $bgColor = 'bg-yellow-100';
                                                $textColor = 'text-yellow-800';
                                            }
                                        @endphp
                                        <span @class([
                                            'inline-flex items-center px-3 py-1 rounded-full text-base font-bold',
                                            $bgColor,
                                            $textColor
                                        ])>
                                            {{ number_format($avgPress, 2) }}%
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                {{-- AVG CLARIFICATION --}}
                                <td class="px-4 py-3 text-center border-r border-gray-300 bg-green-50">
                                    @if($dateData['average_clarification'] !== null)
                                        @php
                                            $avgClarif = $dateData['average_clarification'];
                                            $bgColor = 'bg-red-100';
                                            $textColor = 'text-red-800';

                                            if ($avgClarif >= 90) {
                                                $bgColor = 'bg-green-100';
                                                $textColor = 'text-green-800';
                                            } elseif ($avgClarif >= 70) {
                                                $bgColor = 'bg-yellow-100';
                                                $textColor = 'text-yellow-800';
                                            }
                                        @endphp
                                        <span @class([
                                            'inline-flex items-center px-3 py-1 rounded-full text-base font-bold',
                                            $bgColor,
                                            $textColor
                                        ])>
                                            {{ number_format($avgClarif, 2) }}%
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Summary and Legend --}}
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Total Hari:</span> {{ count($reportData) }} hari
                    <span class="mx-2">|</span>
                    <span class="font-medium">Total Kode:</span> {{ count($allKodesData) }} kode
                </div>

                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-100 border border-green-300 rounded"></span>
                        <span class="text-gray-600">≥ 90% (Baik)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-yellow-100 border border-yellow-300 rounded"></span>
                        <span class="text-gray-600">70-89% (Cukup)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-red-100 border border-red-300 rounded"></span>
                        <span class="text-gray-600">
                            < 70% (Kurang)</span>
                    </div>
                </div>
            </div>
        @endif
    </x-ui.card>

    {{-- ── Operator Performance Table ───────────────────────────────────── --}}
    @if(isset($operatorPress) && isset($operatorClarification) && isset($reportDates))
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Daftar Operator & Performance</h2>
            <p class="text-sm text-gray-600 mb-6">
                Daftar operator dan performance harian berdasarkan kategori (Clarification dan Press).
            </p>

            <x-ui.card>
                <div class="relative overflow-x-auto border rounded-lg">
                    <table class="min-w-full text-sm text-gray-700 divide-y divide-gray-200">
                        {{-- Header --}}
                        <thead class="bg-gray-100 sticky top-0 z-10">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[250px]">
                                    OPERATOR
                                </th>
                                @foreach($reportDates as $date)
                                    <th
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[120px]">
                                        {{ \Carbon\Carbon::parse($date)->format('d M') }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Operator Clarification Section --}}
                            <tr class="bg-green-50">
                                <td colspan="{{ count($reportDates) + 1 }}"
                                    class="px-6 py-3 font-bold text-green-900 uppercase text-sm border-b-2 border-green-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                        Operator Clarification
                                        <!-- <span class="ml-2 text-xs text-green-700">(FEED, SOLID, HEAVY PHASE,
                                                                                EFFLUENT)</span> -->
                                    </div>
                                </td>
                            </tr>

                            @forelse($operatorClarification as $operator)
                                <tr class="hover:bg-green-50 transition">
                                    <td class="px-6 py-3 border-r border-gray-300 bg-green-50/30">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $operator }}</p>
                                                @if(isset($operatorActivities[$operator]))
                                                    <p class="text-xs text-gray-500">
                                                        {{ $operatorActivities[$operator]['total_input'] }} input
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($reportDates as $date)
                                        <td class="px-4 py-3 text-center border-r border-gray-300">
                                            @if(isset($dailyPerformance[$date]) && $dailyPerformance[$date]['average_clarification'] !== null)
                                                @php
                                                    $avgClarif = $dailyPerformance[$date]['average_clarification'];
                                                    $bgColor = 'bg-red-100';
                                                    $textColor = 'text-red-800';

                                                    if ($avgClarif >= 90) {
                                                        $bgColor = 'bg-green-100';
                                                        $textColor = 'text-green-800';
                                                    } elseif ($avgClarif >= 70) {
                                                        $bgColor = 'bg-yellow-100';
                                                        $textColor = 'text-yellow-800';
                                                    }
                                                @endphp
                                                <span @class([
                                                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold',
                                                    $bgColor,
                                                    $textColor
                                                ])>
                                                    {{ number_format($avgClarif, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($reportDates) + 1 }}" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada operator Clarification pada periode ini
                                    </td>
                                </tr>
                            @endforelse

                            {{-- Operator Press Section --}}
                            <tr class="bg-blue-50">
                                <td colspan="{{ count($reportDates) + 1 }}"
                                    class="px-6 py-3 font-bold text-blue-900 uppercase text-sm border-b-2 border-blue-200 border-t-4 border-t-gray-300">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Operator Press
                                        <!-- <span class="ml-2 text-xs text-blue-700">(BUNCH PRESS, FIBRE PRESS)</span> -->
                                    </div>
                                </td>
                            </tr>

                            @forelse($operatorPress as $operator)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-3 border-r border-gray-300 bg-blue-50/30">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $operator }}</p>
                                                @if(isset($operatorActivities[$operator]))
                                                    <p class="text-xs text-gray-500">
                                                        {{ $operatorActivities[$operator]['total_input'] }} input
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($reportDates as $date)
                                        <td class="px-4 py-3 text-center border-r border-gray-300">
                                            @if(isset($dailyPerformance[$date]) && $dailyPerformance[$date]['average_press'] !== null)
                                                @php
                                                    $avgPress = $dailyPerformance[$date]['average_press'];
                                                    $bgColor = 'bg-red-100';
                                                    $textColor = 'text-red-800';

                                                    if ($avgPress >= 90) {
                                                        $bgColor = 'bg-green-100';
                                                        $textColor = 'text-green-800';
                                                    } elseif ($avgPress >= 70) {
                                                        $bgColor = 'bg-yellow-100';
                                                        $textColor = 'text-yellow-800';
                                                    }
                                                @endphp
                                                <span @class([
                                                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold',
                                                    $bgColor,
                                                    $textColor
                                                ])>
                                                    {{ number_format($avgPress, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($reportDates) + 1 }}" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada operator Press pada periode ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </x-ui.card>
        </div>
    @endif

</x-layouts.app>