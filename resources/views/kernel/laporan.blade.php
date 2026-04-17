<x-layouts.app title="Laporan Kernel Losses">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Kernel</h1>
        <p class="mt-2 text-sm text-gray-600">Laporan Kernel Losses, Dirt &amp; Moist, QWT Fibre Press, Ripple Mill, dan Destoner per periode.</p>
    </div>

    <div class="mb-6 bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
        <form method="GET" action="{{ route('reports.kernel') }}"
            class="flex flex-col sm:flex-row gap-2 sm:gap-4 items-end flex-wrap">

            <div class="flex-1 w-full min-w-[160px]">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Filter Kode</label>
                <select name="kode" class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
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
                    <div class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg text-sm bg-gray-100 text-gray-700">
                        {{ auth()->user()->office }}
                    </div>
                @else
                    <select name="office" class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
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
                    <a href="{{ route('reports.kernel') }}"
                        class="px-4 sm:px-6 py-1.5 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        @can('view laporan kernel losses')
            <form method="POST" action="{{ route('reports.kernel.export') }}" class="mt-4">
                @csrf
                <input type="hidden" name="kode" value="{{ $kode ?? '' }}">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <input type="hidden" name="office" value="{{ $officeFilter }}">
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Laporan Kernel Losses
                </button>
            </form>
        @endcan

        <div class="mt-3 text-sm text-gray-600">
            <span class="font-medium">Periode:</span>
            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </span>
            @if($kode ?? null)
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    Kode: {{ $kode }}
                </span>
            @endif
            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                Office: {{ $officeFilter }}
            </span>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 border-l-4 border-l-red-500">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Total Records</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($moduleCounts['kernel'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 border-l-4 border-l-indigo-500">
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Rata-rata Kernel Losses</p>
            <p class="text-2xl font-bold text-indigo-600">
                {{ $avgLosses !== null ? number_format($avgLosses * 100, 2) : '0.00' }}
                <span class="text-sm font-normal text-gray-400">%</span>
            </p>
        </div>
    </div>

    <x-ui.card title="Tabel Data Laporan Kernel Losses">

        <div class="mb-4 flex items-center justify-between gap-3 text-sm text-gray-600">
            <span>Data dari tabel kernel losses sesuai filter aktif.</span>
            <span class="font-medium">{{ number_format($moduleCounts['kernel'] ?? 0) }} record</span>
        </div>

        <style>
            /* ── Kernel Losses Premium Table ───────────────────── */
            .tbl-kernel { border-collapse: separate; border-spacing: 0; }

            .tbl-kernel thead tr.grp th {
                font-size: .65rem; font-weight: 700; letter-spacing: .08em;
                text-transform: uppercase; padding: 6px 8px; border-bottom: none;
            }
            .tbl-kernel thead tr.sub th {
                font-size: .65rem; font-weight: 600; letter-spacing: .05em;
                text-transform: uppercase; padding: 7px 8px;
                border-top: 1px solid rgba(0,0,0,.08); white-space: nowrap;
            }

            /* Zebra */
            .tbl-kernel tbody tr:nth-child(even) td { background-color: #f9fafb; }
            .tbl-kernel tbody tr:nth-child(even) td.fz { background-color: #f9fafb !important; }
            .tbl-kernel tbody tr:hover td { background-color: #eef2ff !important; }
            .tbl-kernel tbody tr:hover td.fz { background-color: #eef2ff !important; }

            /* Freeze shadow */
            .tbl-kernel .fz-last { box-shadow: 4px 0 8px -2px rgba(0,0,0,.13); }

            /* Freeze cell */
            .tbl-kernel .fz { background-color: #fff; position: sticky; }

            /* Badge */
            .kbadge-ok  { display:inline-flex;align-items:center;padding:2px 8px;border-radius:9999px;font-size:.68rem;font-weight:700;background:#dcfce7;color:#166534; }
            .kbadge-bad { display:inline-flex;align-items:center;padding:2px 8px;border-radius:9999px;font-size:.68rem;font-weight:700;background:#fee2e2;color:#991b1b; }
            .kbadge-na  { display:inline-flex;align-items:center;padding:2px 8px;border-radius:9999px;font-size:.68rem;font-weight:600;background:#f3f4f6;color:#374151; }

            .tbl-kernel thead { border-bottom: 2px solid #7c3aed; }
        </style>

        <div class="relative overflow-x-auto overflow-y-auto max-h-[600px] border border-gray-200 rounded-xl shadow-sm">
            <table class="tbl-kernel min-w-[2800px] text-xs text-gray-700">
                <thead class="sticky top-0 z-40">

                    {{-- Baris 1: Group header --}}
                    <tr class="grp">
                        {{-- Freeze: Identitas (5 kolom: #, BULAN, TANGGAL, JAM, KODE) --}}
                        <th colspan="5"
                            class="sticky left-0 z-[60] bg-violet-700 text-white text-center border-r-2 border-violet-400"
                            style="min-width:450px">
                            📋 IDENTITAS SAMPEL
                        </th>
                        {{-- Info Umum (termasuk NAMA SAMPEL) --}}
                        <th colspan="7" class="bg-slate-600 text-white text-center border-r border-slate-400">INFO UMUM</th>
                        {{-- Data Berat --}}
                        <th colspan="7" class="bg-cyan-700 text-white text-center border-r border-cyan-500">⚗️ DATA BERAT (g)</th>
                        {{-- Kalkulasi --}}
                        <th colspan="4" class="bg-purple-700 text-white text-center border-r border-purple-400">📊 KALKULASI (%)</th>
                        {{-- Kernel Losses --}}
                        <th colspan="1" class="bg-red-700 text-white text-center border-r border-red-400">🔴 KERNEL LOSSES</th>
                        {{-- Limit --}}
                        <th colspan="1" class="bg-amber-600 text-white text-center">LIMIT</th>
                    </tr>

                    {{-- Baris 2: Sub-header --}}
                    <tr class="sub">
                        {{-- Freeze 5 cols --}}
                        <th class="fz sticky left-0 z-[60] bg-violet-600 text-violet-100 border border-violet-500/50 w-[50px] text-center">#</th>
                        <th class="fz sticky z-[55] bg-violet-600 text-violet-100 border border-violet-500/50 w-[110px]" style="left:50px">BULAN</th>
                        <th class="fz sticky z-[55] bg-violet-600 text-violet-100 border border-violet-500/50 w-[110px]" style="left:160px">TANGGAL</th>
                        <th class="fz sticky z-[55] bg-violet-600 text-violet-100 border border-violet-500/50 w-[90px]"  style="left:270px">JAM</th>
                        <th class="fz fz-last sticky z-[55] bg-violet-600 text-violet-100 border border-violet-500/50 w-[90px]"  style="left:360px">KODE</th>

                        {{-- Info Umum (NAMA SAMPEL tidak di-freeze) --}}
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50 min-w-[160px]">NAMA SAMPEL</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50">INPUTED BY</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50">JENIS</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50">OPERATOR</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50">SAMPEL BOY</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50">PENGULANGAN</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50">KEG. DISPATCH</th>

                        {{-- Data Berat --}}
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right">BERAT SAMPEL</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right">NUT UTUH - NUT</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right">NUT UTUH - KERNEL</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right">NUT PECAH - NUT</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right">NUT PECAH - KERNEL</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right">KERNEL UTUH</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right">KERNEL PECAH</th>

                        {{-- Kalkulasi --}}
                        <th class="bg-purple-600 text-purple-100 border border-purple-500/50 text-right">KTS NUT UTUH</th>
                        <th class="bg-purple-600 text-purple-100 border border-purple-500/50 text-right">KTS NUT PECAH</th>
                        <th class="bg-purple-600 text-purple-100 border border-purple-500/50 text-right">KERNEL UTUH/SAMPEL</th>
                        <th class="bg-purple-600 text-purple-100 border border-purple-500/50 text-right">KERNEL PECAH/SAMPEL</th>

                        {{-- Kernel Losses --}}
                        <th class="bg-red-600 text-red-100 border border-red-500/50 text-center">KERNEL LOSSES (%)</th>

                        {{-- Limit --}}
                        <th class="bg-amber-500 text-amber-100 border border-amber-400/50 text-center">LIMIT</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($calculations as $calc)
                        @php
                            $master = $masterData[$calc->kode] ?? null;
                            $displayAt = $calc->rounded_time ?? $calc->created_at;
                            $productionDate = $displayAt->copy();
                            if ((int) $productionDate->format('H') < 7) {
                                $productionDate->subDay();
                            }
                            $lossPercent = (float) ($calc->kernel_losses ?? 0) * 100;
                            $isExceeded = $master ? $master->isExceeded($lossPercent) : null;
                        @endphp
                        @php $rowIdx = ($calculations->currentPage()-1)*$calculations->perPage()+$loop->iteration; @endphp
                        <tr class="main-row">
                            {{-- Freeze: NO + expand toggle --}}
                            <td class="fz border-r border-gray-200 px-2 py-2.5 z-[20]" style="left:0">
                                <div class="flex items-center gap-1">
                                    <button onclick="toggleDetail({{ $loop->index }})" id="btn-{{ $loop->index }}"
                                        class="sm:hidden w-5 h-5 flex-shrink-0 rounded-full bg-violet-100 text-violet-700 hover:bg-violet-200 flex items-center justify-center text-xs font-bold transition-all">+</button>
                                    <span class="font-semibold text-gray-500 text-xs">{{ $rowIdx }}</span>
                                </div>
                            </td>
                            {{-- Freeze: BULAN --}}
                            <td class="fz border-r border-gray-200 px-3 py-2.5 whitespace-nowrap z-[10]" style="left:50px">
                                {{ $productionDate->format('F Y') }}
                            </td>
                            {{-- Freeze: TANGGAL --}}
                            <td class="fz border-r border-gray-200 px-3 py-2.5 whitespace-nowrap z-[10]" style="left:160px">
                                {{ $productionDate->format('d-m-Y') }}
                            </td>
                            {{-- Freeze: JAM --}}
                            <td class="fz border-r border-gray-200 px-3 py-2.5 whitespace-nowrap z-[10]" style="left:270px">
                                {{ $displayAt->format('H:i:s') }}
                            </td>
                            {{-- Freeze: KODE (last freeze) --}}
                            <td class="fz fz-last border-r-2 border-violet-200 px-3 py-2.5 z-[10]" style="left:360px">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-violet-100 text-violet-800 ring-1 ring-violet-300">
                                    {{ $calc->kode }}
                                </span>
                            </td>

                            {{-- NAMA SAMPEL (tidak di-freeze) --}}
                            <td class="border-r border-gray-100 px-3 py-2.5 whitespace-nowrap font-medium text-slate-700">{{ $master->nama_sample ?? '-' }}</td>
                            {{-- Info Umum --}}
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50 whitespace-nowrap">{{ optional($calc->user)->name ?? '-' }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50">
                                @if($calc->jenis)
                                    <span class="kbadge-na">{{ $calc->jenis }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50 whitespace-nowrap">{{ $calc->operator ?? '-' }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50 whitespace-nowrap">{{ $calc->sampel_boy ?? '-' }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ ($calc->pengulangan ?? false) ? 'bg-rose-100 text-rose-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ($calc->pengulangan ?? false) ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="border-r border-slate-200 px-3 py-2.5 bg-slate-50 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ ($calc->kegiatan_dispek ?? false) ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ($calc->kegiatan_dispek ?? false) ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>

                            {{-- Data Berat --}}
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono">{{ number_format((float)($calc->berat_sampel ?? 0), 4) }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono">{{ number_format((float)($calc->nut_utuh_nut ?? 0), 4) }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono">{{ number_format((float)($calc->nut_utuh_kernel ?? 0), 4) }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono">{{ number_format((float)($calc->nut_pecah_nut ?? 0), 4) }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono">{{ number_format((float)($calc->nut_pecah_kernel ?? 0), 4) }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono">{{ number_format((float)($calc->kernel_utuh ?? 0), 4) }}</td>
                            <td class="border-r border-cyan-200 px-3 py-2.5 text-right font-mono">{{ number_format((float)($calc->kernel_pecah ?? 0), 4) }}</td>

                            {{-- Kalkulasi --}}
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono bg-purple-50/40 text-purple-800">{{ number_format((float)($calc->kernel_to_sampel_nut_utuh ?? 0), 4) }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono bg-purple-50/40 text-purple-800">{{ number_format((float)($calc->kernel_to_sampel_nut_pecah ?? 0), 4) }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono bg-purple-50/40 text-purple-800">{{ number_format((float)($calc->kernel_utuh_to_sampel ?? 0), 4) }}</td>
                            <td class="border-r border-purple-200 px-3 py-2.5 text-right font-mono bg-purple-50/40 text-purple-800">{{ number_format((float)($calc->kernel_pecah_to_sampel ?? 0), 4) }}</td>

                            {{-- Kernel Losses --}}
                            <td class="border-r border-gray-100 px-3 py-2.5 text-center bg-red-50/40">
                                @if($isExceeded === null)
                                    <span class="kbadge-na">{{ number_format($lossPercent, 4) }}%</span>
                                @elseif($isExceeded)
                                    <span class="kbadge-bad">{{ number_format($lossPercent, 4) }}%</span>
                                @else
                                    <span class="kbadge-ok">{{ number_format($lossPercent, 4) }}%</span>
                                @endif
                            </td>

                            {{-- Limit --}}
                            <td class="px-3 py-2.5 text-center bg-amber-50/50 text-amber-800 font-medium whitespace-nowrap">
                                {{ $master ? $master->limit_label : '-' }}
                            </td>
                        </tr>
                        {{-- Child detail row (expandable) --}}
                        <tr id="detail-{{ $loop->index }}" style="display:none" class="bg-violet-50/30">
                            <td colspan="25" class="px-4 py-4 border-b border-violet-100">
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-3 text-xs">
                                    <div><p class="text-[10px] font-bold text-violet-500 uppercase tracking-wider">Nama Sampel</p><p class="mt-0.5 font-medium text-gray-800">{{ $master->nama_sample ?? '-' }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Inputed By</p><p class="mt-0.5 text-gray-700">{{ optional($calc->user)->name ?? '-' }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jenis</p><p class="mt-0.5 text-gray-700">{{ $calc->jenis ?? '-' }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Operator</p><p class="mt-0.5 text-gray-700">{{ $calc->operator ?? '-' }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sampel Boy</p><p class="mt-0.5 text-gray-700">{{ $calc->sampel_boy ?? '-' }}</p></div>
                                    <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pengulangan</p><p class="mt-0.5"><span class="px-2 py-0.5 rounded-full text-xs {{ ($calc->pengulangan ?? false) ? 'bg-rose-100 text-rose-800' : 'bg-gray-100 text-gray-600' }}">{{ ($calc->pengulangan ?? false) ? 'Ya' : 'Tidak' }}</span></p></div>
                                    <div><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keg. Dispatch</p><p class="mt-0.5"><span class="px-2 py-0.5 rounded-full text-xs {{ ($calc->kegiatan_dispek ?? false) ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600' }}">{{ ($calc->kegiatan_dispek ?? false) ? 'Ya' : 'Tidak' }}</span></p></div>
                                    <div class="col-span-2 sm:col-span-3 lg:col-span-4 border-t border-violet-100 pt-2 mt-1"></div>
                                    <div><p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Berat Sampel</p><p class="mt-0.5 font-mono text-gray-800">{{ number_format((float)($calc->berat_sampel??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Nut Utuh - Nut</p><p class="mt-0.5 font-mono text-gray-800">{{ number_format((float)($calc->nut_utuh_nut??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Nut Utuh - Kernel</p><p class="mt-0.5 font-mono text-gray-800">{{ number_format((float)($calc->nut_utuh_kernel??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Nut Pecah - Nut</p><p class="mt-0.5 font-mono text-gray-800">{{ number_format((float)($calc->nut_pecah_nut??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Nut Pecah - Kernel</p><p class="mt-0.5 font-mono text-gray-800">{{ number_format((float)($calc->nut_pecah_kernel??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Kernel Utuh</p><p class="mt-0.5 font-mono text-gray-800">{{ number_format((float)($calc->kernel_utuh??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Kernel Pecah</p><p class="mt-0.5 font-mono text-gray-800">{{ number_format((float)($calc->kernel_pecah??0),4) }}</p></div>
                                    <div class="col-span-2 sm:col-span-3 lg:col-span-4 border-t border-violet-100 pt-2 mt-1"></div>
                                    <div><p class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">KTS Nut Utuh</p><p class="mt-0.5 font-mono text-purple-700">{{ number_format((float)($calc->kernel_to_sampel_nut_utuh??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">KTS Nut Pecah</p><p class="mt-0.5 font-mono text-purple-700">{{ number_format((float)($calc->kernel_to_sampel_nut_pecah??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">Kernel Utuh/Sampel</p><p class="mt-0.5 font-mono text-purple-700">{{ number_format((float)($calc->kernel_utuh_to_sampel??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">Kernel Pecah/Sampel</p><p class="mt-0.5 font-mono text-purple-700">{{ number_format((float)($calc->kernel_pecah_to_sampel??0),4) }}</p></div>
                                    <div><p class="text-[10px] font-bold text-red-500 uppercase tracking-wider">Kernel Losses</p><p class="mt-0.5">@if($isExceeded===null)<span class="kbadge-na">{{ number_format($lossPercent,4) }}%</span>@elseif($isExceeded)<span class="kbadge-bad">{{ number_format($lossPercent,4) }}%</span>@else<span class="kbadge-ok">{{ number_format($lossPercent,4) }}%</span>@endif</p></div>
                                    <div><p class="text-[10px] font-bold text-amber-500 uppercase tracking-wider">Limit</p><p class="mt-0.5 font-medium text-amber-700">{{ $master ? $master->limit_label : '-' }}</p></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="26" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm">Belum ada data laporan kernel losses untuk periode ini.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Legend --}}
        <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-gray-500 border-t border-gray-100 pt-3">
            <div class="flex items-center gap-1.5">
                <span class="inline-block w-3 h-3 rounded-full bg-green-200 border border-green-400"></span>
                <span>Di bawah limit (Baik)</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="inline-block w-3 h-3 rounded-full bg-red-200 border border-red-400"></span>
                <span>Melebihi limit (Perhatian)</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="inline-block w-3 h-3 rounded-full bg-gray-200 border border-gray-300"></span>
                <span>Tidak ada limit</span>
            </div>
        </div>

        @if($calculations->hasPages())
            <div class="mt-6 px-4">{{ $calculations->links() }}</div>
        @endif
    </x-ui.card>

    <script>
        function toggleDetail(idx) {
            const row = document.getElementById('detail-' + idx);
            const btn = document.getElementById('btn-' + idx);
            const isHidden = row.style.display === 'none';
            row.style.display = isHidden ? 'table-row' : 'none';
            btn.textContent = isHidden ? '−' : '+';
            btn.classList.toggle('bg-violet-300', isHidden);
            btn.classList.toggle('bg-violet-100', !isHidden);
        }
    </script>
</x-layouts.app>