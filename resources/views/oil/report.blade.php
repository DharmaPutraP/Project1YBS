<x-layouts.app title="Report Bobot">

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
        <h1 class="text-3xl font-bold text-gray-900">Report Bobot</h1>
        <p class="mt-2 text-sm text-gray-600">
            Tabel data OLWB per tanggal dan kode. Data diambil dari Oil Calculations. Perhitungan Bobot melalui limit
        </p>
    </div>

    {{-- ── Main Card ─────────────────────────────────────────────────── --}}
    <x-ui.card>
        {{-- Date Range Filter --}}
        <div class="mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
            <form method="GET" action="{{ route('oil.report') }}" class="flex flex-col sm:flex-row gap-4 items-end">
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
                        <a href="{{ route('oil.report') }}"
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
                            @foreach($allKodes as $kode)
                                <th
                                    class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[120px]">
                                    {{ $kode }}
                                </th>
                            @endforeach
                            <th
                                class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[120px] bg-yellow-50">
                                AVG BOBOT
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
                                @foreach($allKodes as $kode)
                                    <td class="px-3 py-3 text-center text-sm border-r border-gray-300">
                                        @if(isset($dateData['kodes'][$kode]) && $dateData['kodes'][$kode]['olwb'] !== null)
                                            <span class="font-semibold text-gray-900">
                                                {{ number_format($dateData['kodes'][$kode]['olwb'], 2) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-4 py-3 text-center border-r border-gray-300 bg-gray-50">
                                    {{-- Empty cell for AVG BOBOT in OLWB row --}}
                                </td>
                            </tr>

                            {{-- Row 2: Bobot Scores --}}
                            <tr>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-indigo-700 border-r border-gray-300 sticky left-0 bg-indigo-50 z-10">
                                    Bobot
                                </td>
                                @foreach($allKodes as $kode)
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
                                <td class="px-4 py-3 text-center border-r border-gray-300 bg-yellow-50">
                                    @if($dateData['average_bobot'] !== null)
                                        @php
                                            $avgBobot = $dateData['average_bobot'];
                                            $bgColor = 'bg-red-100';
                                            $textColor = 'text-red-800';

                                            if ($avgBobot >= 90) {
                                                $bgColor = 'bg-green-100';
                                                $textColor = 'text-green-800';
                                            } elseif ($avgBobot >= 70) {
                                                $bgColor = 'bg-yellow-100';
                                                $textColor = 'text-yellow-800';
                                            }
                                        @endphp
                                        <span @class([
                                            'inline-flex items-center px-3 py-1 rounded-full text-base font-bold',
                                            $bgColor,
                                            $textColor
                                        ])>
                                            {{ number_format($avgBobot, 2) }}%
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
            <!-- <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">Total Hari:</span> {{ count($reportData) }} hari
                            <span class="mx-2">|</span>
                            <span class="font-medium">Total Kode:</span> {{ count($allKodes) }} kode
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
                    </div> -->
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