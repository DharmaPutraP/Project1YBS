<x-layouts.app title="OLWB - Oil Losses">

    {{-- ── Flash Messages ───────────────────────────────────────────── --}}
    @if (session('success'))
        <div id="flash-success" class="mb-6">
            <x-ui.alert type="success" title="Berhasil">{{ session('success') }}</x-ui.alert>
        </div>
    @endif

    @if (session('error'))
        <div id="flash-error" class="mb-6">
            <x-ui.alert type="error" title="Gagal">{{ session('error') }}</x-ui.alert>
        </div>
    @endif

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
            <form method="GET" action="{{ route('oil.olwb') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="flex-1">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Akhir
                    </label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>

                    @if(request('start_date') || request('end_date'))
                        <a href="{{ route('oil.olwb') }}"
                            class="inline-flex items-center px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            @foreach($allKodes as $kode)
                                <th
                                    class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[100px]">
                                    {{ $kode }}
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
                                @foreach($allKodes as $kode)
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
                                                    {{ number_format($olwb, 2) }}
                                                </span>
                                                @if($limit > 0)
                                                    <span class="text-xs text-gray-500">
                                                        Limit: {{ number_format($limit, 2) }}
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
                    <span class="mx-2">|</span>
                    <span class="font-medium">Total Kode:</span> {{ count($allKodes) }} kode
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

    {{-- Auto-dismiss flash messages after 4 seconds --}}
    <script>
        ['flash-success', 'flash-error'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                setTimeout(function () {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(function () {
                        el.remove();
                    }, 500);
                }, 4000);
            }
        });
    </script>

</x-layouts.app>