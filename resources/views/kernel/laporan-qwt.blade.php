<x-layouts.app title="Laporan QWT Fibre Press">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Laporan QWT Fibre Press</h1>
        <p class="mt-2 text-sm text-gray-600">Laporan data QWT Fibre Press per periode.</p>
    </div>

    {{-- Filter & Export --}}
    <div class="mb-6 bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
        <form method="GET" action="{{ route('reports.kernel.qwt') }}"
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
                    <a href="{{ route('reports.kernel.qwt') }}"
                        class="px-4 sm:px-6 py-1.5 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        @can('view laporan kernel losses')
            <form method="POST" action="{{ route('reports.kernel.qwt.export') }}" class="mt-4">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 border-l-4 border-l-cyan-500">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Total Records</p>
            <p class="text-2xl font-bold text-cyan-600">{{ number_format($totalRecords) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 border-l-4 border-l-purple-500">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Rata-rata BN / TN</p>
            <p class="text-2xl font-bold text-purple-600">
                {{ $avgBnTn !== null ? number_format((float) $avgBnTn, 4) : '0.0000' }}
                <span class="text-sm font-normal text-gray-400">%</span>
            </p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 border-l-4 border-l-teal-500">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Rata-rata Moisture</p>
            <p class="text-2xl font-bold text-teal-600">
                {{ $avgMoist !== null ? number_format((float) $avgMoist, 4) : '0.0000' }}
                <span class="text-sm font-normal text-gray-400">%</span>
            </p>
        </div>
    </div>

    <x-ui.card title="Tabel Data Laporan QWT Fibre Press">
        <div class="mb-4 flex items-center justify-between gap-3 text-sm text-gray-600">
            <span>Data QWT Fibre Press sesuai filter aktif.</span>
            <span class="font-medium">{{ number_format($totalRecords) }} record</span>
        </div>

        <style>
            .tbl-qwt { border-collapse: separate; border-spacing: 0; }
            .tbl-qwt thead tr.grp th { font-size:.65rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;padding:6px 8px;border-bottom:none; }
            .tbl-qwt thead tr.sub th { font-size:.65rem;font-weight:600;padding:7px 8px;border-top:1px solid rgba(0,0,0,.08);white-space:nowrap;text-transform:uppercase; }
            .tbl-qwt .fz { position:sticky;background:#fff; }
            .tbl-qwt .fz-last { box-shadow:4px 0 8px -2px rgba(0,0,0,.13); }
            .tbl-qwt tbody tr:nth-child(even) td { background:#f9fafb; }
            .tbl-qwt tbody tr:nth-child(even) td.fz { background:#f9fafb!important; }
            .tbl-qwt tbody tr:hover td { background:#ede9fe!important; }
            .tbl-qwt tbody tr:hover td.fz { background:#ede9fe!important; }
        </style>
        <div class="relative overflow-x-auto overflow-y-auto max-h-[600px] border border-gray-200 rounded-xl shadow-sm">
            <table class="tbl-qwt min-w-max text-xs text-gray-700">
                <thead class="sticky top-0 z-40">
                    <tr class="grp">
                        <th colspan="5" class="fz sticky left-0 z-[60] bg-purple-800 text-white text-center border-r-2 border-purple-400" style="min-width:410px">📋 IDENTITAS SAMPEL</th>
                        <th colspan="8" class="bg-slate-600 text-white text-center border-r border-slate-400">INFO UMUM</th>
                        <th colspan="10" class="bg-teal-700 text-white text-center border-r border-teal-400">⚗️ DATA BERAT (g)</th>
                        <th colspan="2" class="bg-purple-700 text-white text-center border-r border-purple-400">🟣 BN / TN</th>
                        <th colspan="2" class="bg-blue-700 text-white text-center border-r border-blue-400">💧 MOISTURE</th>
                        <th colspan="3" class="bg-slate-700 text-white text-center">⚙️ PARAMETER MESIN</th>
                    </tr>
                    <tr class="sub">
                        <th class="fz sticky left-0 z-[55] bg-purple-700 text-purple-100 border border-purple-600/40 w-[50px] text-center" style="left:0">#</th>
                        <th class="fz sticky z-[50] bg-purple-700 text-purple-100 border border-purple-600/40 w-[90px]" style="left:50px">BULAN</th>
                        <th class="fz sticky z-[50] bg-purple-700 text-purple-100 border border-purple-600/40 w-[90px]" style="left:140px">TANGGAL</th>
                        <th class="fz sticky z-[50] bg-purple-700 text-purple-100 border border-purple-600/40 w-[75px]" style="left:230px">JAM</th>
                        <th class="fz fz-last sticky z-[50] bg-purple-700 text-purple-100 border border-purple-600/40 w-[75px]" style="left:305px">KODE</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50 min-w-[150px]">NAMA SAMPEL</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50 min-w-[120px]">INPUTED BY</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50">JENIS</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50 min-w-[110px]">OPERATOR</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50 min-w-[100px]">SAMPEL BOY</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50">PENGULANGAN</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50">KEG. DISPATCH</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50 min-w-[160px]">REMARKS</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 text-right min-w-[120px]">SAMPEL KTR (g)</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 text-right min-w-[110px]">NUT UTUH (g)</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 text-right min-w-[110px]">NUT PECAH (g)</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 text-right min-w-[120px]">KNL UTUH (g)</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 text-right min-w-[120px]">KNL PECAH (g)</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 text-right min-w-[100px]">CANGKANG (g)</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 text-right min-w-[90px]">BATU (g)</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 text-right min-w-[90px]">FIBER (g)</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 text-right min-w-[110px]">BROKEN NUT (g)</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 text-right min-w-[110px]">TOTAL NUT (g)</th>
                        <th class="bg-purple-600 text-purple-100 border border-purple-500/50 text-center min-w-[100px]">BN / TN (%)</th>
                        <th class="bg-purple-600 text-purple-100 border border-purple-500/50 text-center min-w-[110px]">LIMIT BN/TN</th>
                        <th class="bg-blue-600 text-blue-100 border border-blue-500/50 text-center min-w-[110px]">MOISTURE (%)</th>
                        <th class="bg-blue-600 text-blue-100 border border-blue-500/50 text-center min-w-[110px]">LIMIT MOIST</th>
                        <th class="bg-slate-600 text-slate-100 border border-slate-500/50 text-right min-w-[110px]">AMPERE SCREW</th>
                        <th class="bg-slate-600 text-slate-100 border border-slate-500/50 text-right min-w-[120px]">TKN. HYDRAULIC</th>
                        <th class="bg-slate-600 text-slate-100 border border-slate-500/50 text-right min-w-[110px]">KEC. SCREW</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rows as $row)
                        @php
                            $master = $masterData[$row->kode] ?? null;
                            $displayAt = $row->rounded_time ?? $row->created_at;
                            $productionDate = $displayAt->copy();
                            if ((int) $productionDate->format('H') < 7) {
                                $productionDate->subDay();
                            }
                            $bnTnValue = (float) ($row->bn_tn ?? 0);
                            $moistValue = (float) ($row->moisture ?? 0);
                            $bnTnLimitOperator = $row->bn_tn_limit_operator ?? 'le';
                            $bnTnLimitValue = $row->bn_tn_limit_value !== null ? (float) $row->bn_tn_limit_value : null;
                            $bnTnOk = $bnTnLimitValue !== null
                                ? match ($bnTnLimitOperator) {
                                    'lt' => $bnTnValue < $bnTnLimitValue,
                                    'ge' => $bnTnValue >= $bnTnLimitValue,
                                    'gt' => $bnTnValue > $bnTnLimitValue,
                                    default => $bnTnValue <= $bnTnLimitValue,
                                }
                                : null;
                            $moistLimitOperator = $row->moist_limit_operator ?? 'le';
                            $moistLimitValue = $row->moist_limit_value !== null ? (float) $row->moist_limit_value : null;
                            $moistOk = $moistLimitValue !== null
                                ? match ($moistLimitOperator) {
                                    'lt' => $moistValue < $moistLimitValue,
                                    'ge' => $moistValue >= $moistLimitValue,
                                    'gt' => $moistValue > $moistLimitValue,
                                    default => $moistValue <= $moistLimitValue,
                                }
                                : null;
                        @endphp
                        <tr>
                            <td class="fz border-r border-gray-200 px-2 py-2.5 z-[20]" style="left:0">
                                <div class="flex items-center justify-center gap-1">
                                    <button onclick="toggleQwtDetail({{ $loop->index }})" id="qwt-btn-{{ $loop->index }}"
                                        class="sm:hidden w-5 h-5 flex-shrink-0 rounded-full bg-purple-100 text-purple-700 hover:bg-purple-200 flex items-center justify-center text-xs font-bold transition-all">+</button>
                                    <span class="font-semibold text-gray-500 text-xs">{{ ($rows->currentPage() - 1) * $rows->perPage() + $loop->iteration }}</span>
                                </div>
                            </td>
                            <td class="fz border-r border-gray-200 px-3 py-2.5 whitespace-nowrap z-[10]" style="left:50px">
                                {{ $productionDate->format('F Y') }}
                            </td>
                            <td class="fz border-r border-gray-200 px-3 py-2.5 whitespace-nowrap z-[10]" style="left:140px">
                                {{ $productionDate->format('d-m-Y') }}
                            </td>
                            <td class="fz border-r border-gray-200 px-3 py-2.5 whitespace-nowrap z-[10]" style="left:230px">
                                {{ $displayAt->format('H:i:s') }}
                            </td>
                            <td class="fz fz-last border-r-2 border-purple-200 px-3 py-2.5 z-[10]" style="left:305px">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-purple-100 text-purple-800 ring-1 ring-purple-300">
                                    {{ $row->kode }}
                                </span>
                            </td>
                            <td class="px-3 py-2.5 whitespace-nowrap font-medium text-slate-700 border-r border-gray-100">{{ $master->nama_sample ?? '-' }}</td>
                            <td class="px-3 py-2.5 whitespace-nowrap border-r border-gray-100">{{ optional($row->user)->name ?? '-' }}</td>
                            <td class="px-3 py-2.5 border-r border-gray-100"><span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $row->jenis ?? '-' }}</span></td>
                            <td class="px-3 py-2.5 whitespace-nowrap border-r border-gray-100">{{ $row->operator ?? '-' }}</td>
                            <td class="px-3 py-2.5 whitespace-nowrap border-r border-gray-100">{{ $row->sampel_boy ?? '-' }}</td>
                            <td class="border px-3 py-2 text-center whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ ($row->pengulangan ?? false) ? 'bg-rose-100 text-rose-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ($row->pengulangan ?? false) ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="border px-3 py-2 text-center whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ ($row->kegiatan_dispek ?? false) ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ($row->kegiatan_dispek ?? false) ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="border px-3 py-2 max-w-[220px] break-words">{{ $row->remarks ?? '-' }}</td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->sampel_setelah_kuarter ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->berat_nut_utuh ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->berat_nut_pecah ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->berat_kernel_utuh ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->berat_kernel_pecah ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->berat_cangkang ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-right">{{ number_format((float) ($row->berat_batu ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-right">{{ number_format((float) ($row->berat_fiber ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->berat_broken_nut ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->total_berat_nut ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-center bg-purple-50">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $bnTnOk === null ? 'bg-gray-100 text-gray-700' : ($bnTnOk ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                    {{ number_format($bnTnValue, 4) }}%
                                </span>
                            </td>
                            <td
                                class="border px-3 py-2 text-center bg-orange-50 text-orange-800 font-medium whitespace-nowrap">
                                @if($bnTnLimitValue !== null)
                                    {{ match ($bnTnLimitOperator) { 'lt' => '<', 'ge' => '>=', 'gt' => '>', default => '<='} }}
                                    {{ number_format($bnTnLimitValue, 4) }}%
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border px-3 py-2 text-center bg-cyan-50">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $moistOk === null ? 'bg-gray-100 text-gray-700' : ($moistOk ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                    {{ number_format($moistValue, 4) }}%
                                </span>
                            </td>
                            <td
                                class="border px-3 py-2 text-center bg-orange-50 text-orange-800 font-medium whitespace-nowrap">
                                @if($moistLimitValue !== null)
                                    {{ match ($moistLimitOperator) { 'lt' => '<', 'ge' => '>=', 'gt' => '>', default => '<='} }}
                                    {{ number_format($moistLimitValue, 4) }}%
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->ampere_screw ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->tekanan_hydraulic ?? 0), 4) }}
                            </td>
                            <td class="border px-3 py-2 text-right">
                                {{ number_format((float) ($row->kecepatan_screw ?? 0), 4) }}
                            </td>
                        </tr>
                        <tr id="qwt-detail-{{ $loop->index }}" style="display:none" class="bg-purple-50/30">
                            <td colspan="30" class="px-4 py-4 border-b border-purple-100">
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-3 text-xs">
                                    <div><p class="text-[10px] font-bold text-purple-600 uppercase tracking-wider">Nama Sampel</p><p class="mt-0.5 font-medium">{{ $master->nama_sample ?? '-' }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Inputed By</p><p class="mt-0.5">{{ optional($row->user)->name ?? '-' }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Operator</p><p class="mt-0.5">{{ $row->operator ?? '-' }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sampel Boy</p><p class="mt-0.5">{{ $row->sampel_boy ?? '-' }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jenis</p><p class="mt-0.5">{{ $row->jenis ?? '-' }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pengulangan</p><p class="mt-0.5"><span class="px-2 py-0.5 rounded-full text-xs {{ ($row->pengulangan??false)?'bg-rose-100 text-rose-800':'bg-gray-100 text-gray-600' }}">{{ ($row->pengulangan??false)?'Ya':'Tidak' }}</span></p></div>
                                    <div class="col-span-2 sm:col-span-3 border-t border-purple-100 pt-2 mt-1"></div>
                                    <div><p class="text-[10px] font-bold text-teal-500 uppercase tracking-wider">Sampel Kuarter</p><p class="mt-0.5 font-mono">{{ number_format((float)($row->sampel_setelah_kuarter??0),4) }} g</p></div>
                                    <div><p class="text-[10px] font-bold text-teal-500 uppercase tracking-wider">Nut Utuh</p><p class="mt-0.5 font-mono">{{ number_format((float)($row->berat_nut_utuh??0),4) }} g</p></div>
                                    <div><p class="text-[10px] font-bold text-teal-500 uppercase tracking-wider">Nut Pecah</p><p class="mt-0.5 font-mono">{{ number_format((float)($row->berat_nut_pecah??0),4) }} g</p></div>
                                    <div><p class="text-[10px] font-bold text-teal-500 uppercase tracking-wider">Total Nut</p><p class="mt-0.5 font-mono">{{ number_format((float)($row->total_berat_nut??0),4) }} g</p></div>
                                    <div><p class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">BN/TN (%)</p><p class="mt-0.5"><span class="px-2 py-0.5 rounded-full text-xs {{ $bnTnOk===null?'bg-gray-100 text-gray-700':($bnTnOk?'bg-green-100 text-green-800':'bg-red-100 text-red-800') }}">{{ number_format($bnTnValue,4) }}%</span></p></div>
                                    <div><p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Moisture (%)</p><p class="mt-0.5"><span class="px-2 py-0.5 rounded-full text-xs {{ $moistOk===null?'bg-gray-100 text-gray-700':($moistOk?'bg-green-100 text-green-800':'bg-red-100 text-red-800') }}">{{ number_format($moistValue,4) }}%</span></p></div>
                                    <div><p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Ampere Screw</p><p class="mt-0.5 font-mono">{{ number_format((float)($row->ampere_screw??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tek. Hydraulic</p><p class="mt-0.5 font-mono">{{ number_format((float)($row->tekanan_hydraulic??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kec. Screw</p><p class="mt-0.5 font-mono">{{ number_format((float)($row->kecepatan_screw??0),4) }}</p></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="30" class="px-4 py-16 text-center text-sm text-gray-400">Belum ada data laporan QWT
                                Fibre Press untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rows->hasPages())
            <div class="mt-6 px-4">{{ $rows->links() }}</div>
        @endif
    </x-ui.card>

    <script>
        function toggleQwtDetail(idx) {
            const row = document.getElementById('qwt-detail-' + idx);
            const btn = document.getElementById('qwt-btn-' + idx);
            const isHidden = row.style.display === 'none';
            row.style.display = isHidden ? 'table-row' : 'none';
            btn.textContent = isHidden ? '−' : '+';
            btn.classList.toggle('bg-purple-300', isHidden);
            btn.classList.toggle('bg-purple-100', !isHidden);
        }
    </script>
</x-layouts.app>