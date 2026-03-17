<x-layouts.app title="Performance Kernel Losses">

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Performance Kernel Losses</h1>
        <p class="mt-1 text-sm text-gray-500">
            Tabel data Kernel Losses per tanggal dan kode. Perhitungan Bobot melalui limit konfigurasi.
        </p>
    </div>

    {{-- ── Main Card ───────────────────────────────────────────────────────── --}}
    <x-ui.card>
        {{-- Date Range Filter --}}
        <div class="mb-6 bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
            <form method="GET" action="{{ route('kernel.performance') }}"
                  class="flex flex-col sm:flex-row gap-2 sm:gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-1.5 sm:py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                    @if(request('start_date') || request('end_date'))
                        <a href="{{ route('kernel.performance') }}"
                            class="inline-flex items-center justify-center px-4 sm:px-6 py-1.5 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium text-sm">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="mt-3 text-sm text-gray-600">
                <span class="font-medium">Periode:</span>
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                </span>
            </div>

            {{-- Export Button --}}
            @canany(['view performance kernel losses'])
                <div class="mt-3">
                    <form action="{{ route('kernel.performance.export') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export ke Excel
                        </button>
                    </form>
                </div>
            @endcanany
        </div>

        {{-- Performance Detail Table --}}
        @if(empty($individualRecords))
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-4 text-lg text-gray-500">Tidak ada data pada periode ini</p>
                <p class="mt-2 text-sm text-gray-400">Silakan pilih rentang tanggal lain atau input data baru</p>
            </div>
        @else
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300 text-xs">
                    <thead>
                        <tr class="bg-indigo-50">
                            <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[90px]">
                                Tanggal
                            </th>
                            <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[70px]">
                                Jam
                            </th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[150px]">
                                Nama Operator
                            </th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[150px]">
                                Sample Boy
                            </th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[160px]">
                                Jenis Sampel
                            </th>
                            <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[110px]">
                                Nilai Parameter
                            </th>
                            <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[120px] bg-blue-50">
                                Nilai Performance
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($individualRecords as $rec)
                            <tr class="hover:bg-gray-50">
                                {{-- Tanggal --}}
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-800 border-r border-gray-300">
                                    {{ \Carbon\Carbon::parse($rec['date'])->format('d-M') }}
                                </td>
                                {{-- Jam --}}
                                <td class="px-3 py-2 text-center whitespace-nowrap text-sm text-gray-700 border-r border-gray-300">
                                    {{ $rec['time'] }}
                                </td>
                                {{-- Operator --}}
                                <td class="px-3 py-2 text-sm text-gray-800 border-r border-gray-300">
                                    {{ $rec['operator'] ?: '-' }}
                                </td>
                                {{-- Sample Boy --}}
                                <td class="px-3 py-2 text-sm text-gray-700 border-r border-gray-300">
                                    {{ $rec['sampel_boy'] ?: '-' }}
                                </td>
                                {{-- Jenis Sampel --}}
                                <td class="px-3 py-2 text-sm font-medium text-gray-800 border-r border-gray-300">
                                    {{ $rec['nama_sample'] }}
                                </td>
                                {{-- Nilai Parameter --}}
                                <td class="px-3 py-2 text-center text-sm border-r border-gray-300">
                                    <span class="font-semibold text-gray-800">
                                        {{ number_format($rec['nilai_parameter'], 2) }}
                                    </span>
                                </td>
                                {{-- Nilai Performance (Bobot) --}}
                                <td class="px-3 py-2 text-center border-r border-gray-300 bg-blue-50/40">
                                    @if($rec['bobot'] === null)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">—</span>
                                    @elseif($rec['bobot'] === 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">0</span>
                                    @else
                                        @php
                                            $b = $rec['bobot'];
                                            if ($b >= 90)      { $bg = 'bg-green-100'; $tc = 'text-green-800'; }
                                            elseif ($b >= 70)  { $bg = 'bg-yellow-100'; $tc = 'text-yellow-800'; }
                                            else               { $bg = 'bg-red-100'; $tc = 'text-red-800'; }
                                        @endphp
                                        <span @class(['inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold', $bg, $tc])>
                                            {{ $b }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Summary --}}
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-gray-600">
                <div>
                    <span class="font-medium">Total Record:</span> {{ $individualRecords->total() }} data
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-green-200 inline-block border border-green-400"></span>
                        <span>&ge; 90 (Baik)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-yellow-200 inline-block border border-yellow-400"></span>
                        <span>70&ndash;89 (Cukup)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded bg-red-200 inline-block border border-red-400"></span>
                        <span>&lt; 70 (Kurang)</span>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $individualRecords->links() }}
            </div>
        @endif
    </x-ui.card>

    {{-- ── Operator Performance Table ───────────────────────────────────── --}}
    @if(isset($operators) && count($operators) > 0)
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-1">Daftar Operator &amp; Performance</h2>
            <p class="text-sm text-gray-600 mb-4">
                Total dan rata-rata nilai performance (bobot) tiap operator berdasarkan periode yang dipilih.
            </p>

            <x-ui.card>
                <div class="relative overflow-x-auto border rounded-lg">
                    <table class="min-w-full text-sm text-gray-700 divide-y divide-gray-200">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[220px]">
                                    Operator
                                </th>
                                @foreach($reportDates as $date)
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-300 min-w-[100px]">
                                        {{ \Carbon\Carbon::parse($date)->format('d M') }}
                                    </th>
                                @endforeach
                                <th class="px-3 py-3 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider border-gray-300 min-w-[100px] bg-indigo-50">
                                    Rata-rata
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($operators as $operator)
                                @php
                                    $grandSum   = 0;
                                    $grandCount = 0;
                                @endphp
                                <tr class="hover:bg-indigo-50/40 transition">
                                    <td class="px-4 py-3 border-r border-gray-300">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 text-sm">{{ $operator }}</p>
                                                @if(isset($operatorActivities[$operator]))
                                                    <p class="text-xs text-gray-400">{{ $operatorActivities[$operator]['total_input'] }} input</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    @foreach($reportDates as $date)
                                        @php
                                            $opStats = $operatorDailyPerformance[$operator][$date] ?? null;
                                            if ($opStats && $opStats['count'] > 0) {
                                                $opDateAvg   = round($opStats['sum'] / $opStats['count'], 1);
                                                $grandSum   += $opStats['sum'];
                                                $grandCount += $opStats['count'];
                                            } else {
                                                $opDateAvg = null;
                                            }
                                        @endphp
                                        <td class="px-3 py-3 text-center border-r border-gray-300">
                                            @if($opDateAvg !== null)
                                                @php
                                                    if ($opDateAvg >= 90)     { $bg = 'bg-green-100'; $tc = 'text-green-800'; }
                                                    elseif ($opDateAvg >= 70) { $bg = 'bg-yellow-100'; $tc = 'text-yellow-800'; }
                                                    else                      { $bg = 'bg-red-100'; $tc = 'text-red-800'; }
                                                @endphp
                                                <span @class(['inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold', $bg, $tc])>
                                                    {{ $opDateAvg }}
                                                </span>
                                            @else
                                                <span class="text-gray-300">&mdash;</span>
                                            @endif
                                        </td>
                                    @endforeach


                                    {{-- Rata-rata --}}
                                    <td class="px-3 py-3 text-center bg-indigo-50/50">
                                        @if($grandCount > 0)
                                            @php
                                                $grandAvg = round($grandSum / $grandCount, 2);
                                                if ($grandAvg >= 90)     { $bg = 'bg-green-100'; $tc = 'text-green-800'; }
                                                elseif ($grandAvg >= 70) { $bg = 'bg-yellow-100'; $tc = 'text-yellow-800'; }
                                                else                     { $bg = 'bg-red-100'; $tc = 'text-red-800'; }
                                            @endphp
                                            <span @class(['inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold', $bg, $tc])>
                                                {{ $grandAvg }}
                                            </span>
                                        @else
                                            <span class="text-gray-300">&mdash;</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>
    @endif

</x-layouts.app>
