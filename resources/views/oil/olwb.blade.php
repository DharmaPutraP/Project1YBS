<x-layouts.app title="OLWB - Oil Losses">

    {{-- ── Header Section ────────────────────────────────────────────── --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">OLWB</h1>
        <p class="mt-2 text-sm text-gray-600">
            Tabel data OLWB per tanggal dan kode. Data diambil dari Oil Calculations.
        </p>
    </div>

    {{-- ── Main Card ─────────────────────────────────────────────────── --}}
    <x-ui.card>
        {{-- Date Range Filter --}}
        <div class="mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
            <form method="GET" action="{{ route('oil.olwb') }}"
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
                        <a href="{{ route('oil.olwb') }}"
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
            <div class="mt-3">
                <form action="{{ route('oil.olwb.export') }}" method="POST" class="inline">
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
        </div>

        {{-- OLWB Table --}}
        @if(empty($dataByDate))
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-lg text-gray-500">Tidak ada data OLWB pada periode ini</p>
                <p class="mt-2 text-sm text-gray-400">Silakan pilih rentang tanggal lain atau input data baru</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 sticky left-0 bg-indigo-50 z-10">
                                Tanggal
                            </th>
                            @foreach($allKodesData as $kodeInfo)
                                <th
                                    class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[100px]">
                                    {{ $kodeInfo['pivot'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($dataByDate as $date => $kodeData)
                            <tr class="hover:bg-gray-50">
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-300 sticky left-0 bg-white z-10">
                                    {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                </td>
                                @foreach($allKodesData as $kodeInfo)
                                    @php
                                        $kode = $kodeInfo['kode'];
                                    @endphp
                                    <td class="px-3 py-3 text-center text-sm border-r border-gray-300">
                                        @if(isset($kodeData[$kode]))
                                            @php
                                                $olwb = $kodeData[$kode]['olwb'];
                                                $limit = $kodeData[$kode]['limitOLWB'];

                                                // Determine if value is good based on kode
                                                if ($kode == 'COT IN') {
                                                    $isGood = $olwb > $limit && $limit > 0;
                                                } else {
                                                    $isGood = $olwb <= $limit && $limit > 0;
                                                }
                                            @endphp

                                            <div class="flex flex-col items-center gap-1">
                                                <span @class([
                                                    'font-semibold text-base',
                                                    'text-green-600' => $isGood,
                                                    'text-red-600' => !$isGood && $limit > 0,
                                                    'text-gray-900' => $limit <= 0
                                                ])>
                                                    @if($olwb < 0)
                                                        ({{ number_format(abs($olwb), 2) }})
                                                    @else
                                                        {{ number_format($olwb, 2) }}
                                                    @endif
                                                </span>
                                                @if($limit > 0)
                                                    <span class="text-xs text-gray-500">
                                                        Limit:
                                                        @if($limit < 0)
                                                            ({{ number_format(abs($limit), 2) }})
                                                        @else
                                                            {{ number_format($limit, 2) }}
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Summary --}}
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Total Tanggal:</span> {{ count($dataByDate) }} hari
                </div>

                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-green-600 rounded"></span>
                        <span class="text-gray-600">Sesuai Limit</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-red-600 rounded"></span>
                        <span class="text-gray-600">Melebihi Limit</span>
                    </div>
                </div>
            </div>
        @endif
    </x-ui.card>

</x-layouts.app>