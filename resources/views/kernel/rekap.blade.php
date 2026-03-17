<x-layouts.app title="Rekap Data Kernel Losses">

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Rekap Kernel Losses</h1>
        <p class="mt-2 text-sm text-gray-600">Rata-rata kernel losses per kode per hari. Merah = melebihi limit, Hijau = sesuai limit.</p>
    </div>

    {{-- ── Filter + Export ─────────────────────────────────────────────────── --}}
    <x-ui.card>
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <form method="GET" action="{{ route('kernel.rekap') }}"
                  class="flex flex-col sm:flex-row gap-2 sm:gap-4 items-end flex-wrap">

                <div class="flex-1 w-full min-w-[140px]">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="flex-1 w-full min-w-[140px]">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                    @if(request('start_date') || request('end_date'))
                        <a href="{{ route('kernel.rekap') }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-medium transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Export --}}
            @can('view rekap kernel losses')
                <form method="POST" action="{{ route('kernel.rekap.export') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export ke Excel
                    </button>
                </form>
            @endcan

            <div class="mt-3 text-sm text-gray-600">
                <span class="font-medium">Periode:</span>
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} —
                    {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                </span>
            </div>
        </div>

        {{-- ── Table ───────────────────────────────────────────────────────── --}}
        <div class="mt-6">
            @if(empty($dataByDate))
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-4 text-sm text-gray-400">Tidak ada data untuk periode ini.</p>
                </div>
            @else
                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-300 text-xs">
                        <thead>
                            {{-- Row 1: Group headers --}}
                            <tr>
                                <th rowspan="2" class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-b border-gray-300 sticky left-0 bg-indigo-50 z-10 min-w-[110px]">
                                    Tanggal
                                </th>
                                @foreach($columnGroups as $group)
                                    <th colspan="{{ count($group['columns']) }}"
                                        class="px-2 py-2 text-center text-xs font-bold uppercase tracking-wider border-r border-b border-gray-300 {{ $group['color'] }}">
                                        {{ $group['name'] }}
                                    </th>
                                @endforeach
                            </tr>
                            {{-- Row 2: Individual column headers --}}
                            <tr class="bg-indigo-50">
                                @foreach($columnGroups as $gIdx => $group)
                                    @foreach($group['columns'] as $cIdx => $col)
                                        <th class="px-2 py-2 text-center text-[10px] font-semibold text-gray-700 border-r border-b border-gray-300 min-w-[100px] {{ $cIdx === 0 ? 'border-l-2 border-l-gray-400' : '' }}">
                                            <div class="whitespace-nowrap">{{ $col['label'] }}</div>
                                            @if(isset($masterMap[$col['kode']]))
                                                <div class="text-[9px] font-normal text-orange-600 mt-0.5">
                                                    Limit: {{ $masterMap[$col['kode']]['limit_operator'] === 'le' ? '≤' : '>' }}
                                                    {{ number_format((float)$masterMap[$col['kode']]['limit_value'], 2) }}%
                                                </div>
                                            @endif
                                        </th>
                                    @endforeach
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($dataByDate as $date => $kodeData)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-300 sticky left-0 bg-white z-10">
                                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                    </td>
                                    @foreach($columnGroups as $group)
                                        @foreach($group['columns'] as $cIdx => $col)
                                            @php
                                                $kode = $col['kode'];
                                                $cell = $kodeData[$kode] ?? null;
                                            @endphp
                                            <td class="px-2 py-2 text-center border-r border-gray-300 {{ $cIdx === 0 ? 'border-l-2 border-l-gray-400' : '' }}">
                                                @if($cell !== null)
                                                    @php
                                                        $pct    = $cell['avg_losses'] * 100;
                                                        $limOp  = $cell['limit_operator'];
                                                        $limVal = $cell['limit_value'];
                                                        if ($limVal !== null) {
                                                            $isGood = ($limOp === 'le') ? ($pct <= $limVal) : ($pct > $limVal);
                                                        } else {
                                                            $isGood = null;
                                                        }
                                                    @endphp
                                                    <span class="font-semibold text-sm
                                                        {{ $isGood === true ? 'text-green-600' : ($isGood === false ? 'text-red-600' : 'text-gray-700') }}">
                                                        {{ number_format($pct, 4) }}%
                                                    </span>
                                                @else
                                                    <span class="text-gray-300">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Legend --}}
                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Total Hari:</span> {{ count($dataByDate) }} hari
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-green-500 inline-block"></span>
                            <span>Sesuai Limit</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-red-500 inline-block"></span>
                            <span>Melebihi Limit</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-ui.card>

</x-layouts.app>