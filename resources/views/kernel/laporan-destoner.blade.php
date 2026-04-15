<x-layouts.app title="Laporan Destoner">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Destoner</h1>
        <p class="mt-2 text-sm text-gray-600">Laporan data Destoner per periode.</p>
    </div>

    {{-- Filter & Export --}}
    <div class="mb-6 bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
        <form method="GET" action="{{ route('reports.kernel.destoner') }}"
            class="flex flex-col sm:flex-row gap-2 sm:gap-4 items-end flex-wrap">

            <div class="flex-1 w-full min-w-[160px]">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Filter Kode</label>
                <select name="kode"
                    class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Semua Kode --</option>
                    @foreach($kodeOptions as $kodeValue => $kodeLabel)
                        <option value="{{ $kodeValue }}" {{ ($kode ?? '') == $kodeValue ? 'selected' : '' }}>
                            {{ $kodeValue }} - {{ $kodeLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 w-full min-w-[140px]">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Office/PT</label>
                @if(auth()->user()->office)
                    <div
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg text-sm bg-gray-100 text-gray-700">
                        {{ auth()->user()->office }}
                    </div>
                @else
                    <select name="office"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="all" {{ $officeFilter == 'all' ? 'selected' : '' }}>-- Semua Office --</option>
                        <option value="YBS" {{ $officeFilter == 'YBS' ? 'selected' : '' }}>YBS</option>
                        <option value="SUN" {{ $officeFilter == 'SUN' ? 'selected' : '' }}>SUN</option>
                        <option value="SJN" {{ $officeFilter == 'SJN' ? 'selected' : '' }}>SJN</option>
                    </select>
                @endif
            </div>

            <div class="flex-1 w-full min-w-[140px]">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="flex-1 w-full min-w-[140px]">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <button type="submit"
                    class="px-4 sm:px-6 py-1.5 sm:py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request()->hasAny(['kode', 'start_date', 'end_date', 'office']))
                    <a href="{{ route('reports.kernel.destoner') }}"
                        class="px-4 sm:px-6 py-1.5 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        @can('view laporan kernel losses')
            <form method="POST" action="{{ route('reports.kernel.destoner.export') }}" class="mt-4">
                @csrf
                <input type="hidden" name="kode" value="{{ $kode ?? '' }}">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <input type="hidden" name="office" value="{{ $officeFilter }}">
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export ke Excel
                </button>
            </form>
        @endcan

        <div class="mt-3 text-sm text-gray-600">
            <span class="font-medium">Periode:</span>
            <span
                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </span>
            @if($kode ?? null)
                <span
                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    Kode: {{ $kode }}
                </span>
            @endif
            <span
                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                Office: {{ $officeFilter }}
            </span>
        </div>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 border-l-4 border-l-fuchsia-500">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Total Records</p>
            <p class="text-2xl font-bold text-fuchsia-600">{{ number_format($totalRecords) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 border-l-4 border-l-orange-500">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Rata-rata Loss Kernel/TBS</p>
            <p class="text-2xl font-bold text-orange-600">
                {{ $avgLossKernelTbs !== null ? number_format((float) $avgLossKernelTbs, 2) : '0.00' }}
            </p>
        </div>
    </div>

    <x-ui.card title="Tabel Data Laporan Destoner">
        <div class="mb-4 flex items-center justify-between gap-3 text-sm text-gray-600">
            <span>Data Destoner sesuai filter aktif.</span>
            <span class="font-medium">{{ number_format($totalRecords) }} record</span>
        </div>

        <div class="relative overflow-x-auto overflow-y-auto max-h-[600px] border rounded-lg">
            <table class="min-w-[2550px] text-xs text-gray-700 divide-y divide-gray-200">
                <thead class="bg-blue-50 sticky top-0 z-40">
                    <tr>
                        <th class="sticky top-0 left-0 z-[60] bg-blue-50 border px-3 py-3 w-[50px] text-center">NO</th>
                        <th class="sticky top-0 lg:left-[50px] z-[50] bg-blue-50 border px-3 py-3 w-[110px]">BULAN</th>
                        <th class="sticky top-0 lg:left-[160px] z-[50] bg-blue-50 border px-3 py-3 w-[110px]">TANGGAL
                        </th>
                        <th class="sticky top-0 lg:left-[270px] z-[50] bg-blue-50 border px-3 py-3 w-[90px]">JAM</th>
                        <th class="sticky top-0 lg:left-[360px] z-[50] bg-blue-50 border px-3 py-3 w-[90px]">KODE</th>
                        <th class="sticky top-0 lg:left-[450px] z-[50] bg-blue-50 border px-3 py-3 w-[200px]">NAMA
                            SAMPLE</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">INPUTED BY</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">JENIS</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">OPERATOR</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">SAMPEL BOY</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">KEGIATAN DISPATCH</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">REMARKS</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">BERAT SAMPEL (g)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">KONVERSI (KG)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">TIME (DETIK)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">RASIO JAM/KG</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">BERAT NUT (g)</th>
                        <th class="sticky top-0 z-20 bg-purple-100 border px-3 py-3 text-purple-700">% NUT</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-3 py-3">BERAT KERNEL (g)</th>
                        <th class="sticky top-0 z-20 bg-purple-100 border px-3 py-3 text-purple-700">% KERNEL</th>
                        <th class="sticky top-0 z-20 bg-red-50 border px-3 py-3 text-red-700">TOTAL LOSSES KERNEL</th>
                        <th class="sticky top-0 z-20 bg-orange-50 border px-3 py-3 text-orange-700">LOSS KERNEL/JAM</th>
                        <th class="sticky top-0 z-20 bg-orange-50 border px-3 py-3 text-orange-700">LOSS KERNEL/TBS</th>
                        <th class="sticky top-0 z-20 bg-orange-50 border px-3 py-3 text-orange-700">LIMIT</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rows as $row)
                        @php
                            $master = $masterData[$row->kode] ?? null;
                            $displayAt = $row->rounded_time ?? $row->created_at;
                            $productionDate = $displayAt->copy();
                            if ((int) $productionDate->format('H') < 7) {
                                $productionDate->subDay();
                            }
                            $limitOp = $row->limit_operator;
                            $limitVal = $row->limit_value !== null ? (float) $row->limit_value : null;
                            $lossTbs = (float) ($row->loss_kernel_tbs ?? 0);
                            $isGood = null;
                            if ($limitOp && $limitVal !== null) {
                                $isGood = match ($limitOp) {
                                    'lt' => $lossTbs < $limitVal,
                                    'ge' => $lossTbs >= $limitVal,
                                    'gt' => $lossTbs > $limitVal,
                                    default => $lossTbs <= $limitVal,
                                };
                            }
                            $limitBadge = $isGood === null ? 'bg-gray-100 text-gray-700' : ($isGood ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="sticky left-0 z-[20] bg-white border px-3 py-2 text-center">
                                {{ ($rows->currentPage() - 1) * $rows->perPage() + $loop->iteration }}
                            </td>
                            <td class="border px-3 py-2 lg:sticky lg:left-[50px] z-[10] bg-white whitespace-nowrap">
                                {{ $productionDate->format('F Y') }}
                            </td>
                            <td class="border px-3 py-2 lg:sticky lg:left-[160px] z-[10] bg-white whitespace-nowrap">
                                {{ $productionDate->format('d-m-Y') }}
                            </td>
                            <td class="border px-3 py-2 lg:sticky lg:left-[270px] z-[10] bg-white whitespace-nowrap">
                                {{ $displayAt->format('H:i:s') }}
                            </td>
                            <td
                                class="border px-3 py-2 lg:sticky lg:left-[360px] z-[10] bg-white font-semibold text-blue-800 whitespace-nowrap">
                                {{ $row->kode }}
                            </td>
                            <td class="border px-3 py-2 lg:sticky lg:left-[450px] z-[10] bg-white whitespace-nowrap">
                                {{ $master->nama_sample ?? '-' }}
                            </td>
                            <td class="border px-3 py-2 whitespace-nowrap">{{ optional($row->user)->name ?? '-' }}</td>
                            <td class="border px-3 py-2 whitespace-nowrap"><span
                                    class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $row->jenis ?? '-' }}</span>
                            </td>
                            <td class="border px-3 py-2 whitespace-nowrap">{{ $row->operator ?? '-' }}</td>
                            <td class="border px-3 py-2 whitespace-nowrap">{{ $row->sampel_boy ?? '-' }}</td>
                            <td class="border px-3 py-2 text-center whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ ($row->kegiatan_dispek ?? false) ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ($row->kegiatan_dispek ?? false) ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="border px-3 py-2 max-w-[220px] break-words">{{ $row->remarks ?? '-' }}</td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->berat_sampel ?? 0), 2) }}
                            </td>
                            <td class="border px-3 py-2 text-right">{{ number_format((float) ($row->konversi_kg ?? 0), 2) }}
                            </td>
                            <td class="border px-3 py-2 text-right">{{ number_format((float) ($row->time ?? 0), 2) }}</td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->rasio_jam_kg ?? 0), 2) }}
                            </td>
                            <td class="border px-3 py-2 text-right">{{ number_format((float) ($row->berat_nut ?? 0), 2) }}
                            </td>
                            <td class="border px-3 py-2 text-center bg-purple-50">
                                {{ number_format((float) ($row->persen_nut ?? 0), 2) }}
                            </td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->berat_kernel ?? 0), 2) }}
                            </td>
                            <td class="border px-3 py-2 text-center bg-purple-50">
                                {{ number_format((float) ($row->persen_kernel ?? 0), 2) }}
                            </td>
                            <td class="border px-3 py-2 text-center bg-red-50">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                    {{ number_format((float) ($row->total_losses_kernel ?? 0), 2) }}
                                </span>
                            </td>
                            <td class="border px-3 py-2 text-right bg-orange-50">
                                {{ number_format((float) ($row->loss_kernel_jam ?? 0), 2) }}
                            </td>
                            <td class="border px-3 py-2 text-center bg-orange-50">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $limitBadge }}">
                                    {{ $lossTbs }}
                                </span>
                            </td>
                            <td
                                class="border px-3 py-2 text-center bg-orange-50 text-orange-800 font-medium whitespace-nowrap">
                                @if($limitOp && $limitVal !== null)
                                    {{ match ($limitOp) { 'lt' => '<', 'ge' => '>=', 'gt' => '>', default => '<='} }}
                                    {{ $limitVal }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="24" class="px-4 py-16 text-center text-sm text-gray-400">Belum ada data laporan
                                Destoner untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rows->hasPages())
            <div class="mt-6 px-4">{{ $rows->links() }}</div>
        @endif
    </x-ui.card>

</x-layouts.app>