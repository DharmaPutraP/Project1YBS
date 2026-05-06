<x-layouts.app title="Laporan">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Laporan</h1>
        <p class="mt-2 text-sm text-gray-600">
            Tabel data Oil Losses Lengkap
        </p>
    </div>

    {{-- Date Range Filter --}}
    <div class="mb-6 bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
        <form method="GET" action="{{ route('reports.index') }}"
            class="flex flex-col sm:flex-row gap-2 sm:gap-4 items-end">

            <div class="flex-1 w-full">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Filter Office/PT
                </label>
                @if(auth()->user()->office)
                    <div
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                        {{ auth()->user()->office }}
                    </div>
                @else
                    <select name="office" id="office"
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="all" {{ $officeFilter == 'all' ? 'selected' : '' }}>-- Semua Office --</option>
                        <option value="YBS" {{ $officeFilter == 'YBS' ? 'selected' : '' }}>YBS</option>
                        <option value="SUN" {{ $officeFilter == 'SUN' ? 'selected' : '' }}>SUN</option>
                        <option value="SJN" {{ $officeFilter == 'SJN' ? 'selected' : '' }}>SJN</option>
                    </select>
                @endif
            </div>

            <div class="flex-1 w-full">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Filter Kode
                </label>
                <select name="kode" class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                    <option value="">-- Semua Kode --</option>
                    @foreach($kodeOptions as $kodeValue => $kodeLabel)
                        <option value="{{ $kodeValue }}" {{ request('kode') == $kodeValue ? 'selected' : '' }}>
                            {{ $kodeLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 w-full">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Tanggal Mulai
                </label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
            </div>

            <div class="flex-1 w-full">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Tanggal Akhir
                </label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
            </div>

            <div class="flex items-end gap-3 w-full sm:w-auto">
                <label class="flex items-center gap-2 whitespace-nowrap">
                    <input type="checkbox" name="numeric_only" value="1" 
                        {{ request('numeric_only') ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="text-xs sm:text-sm font-medium text-gray-700">Hanya Data Angka</span>
                </label>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <button type="submit"
                    class="px-4 sm:px-6 py-1.5 sm:py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                    Filter
                </button>

                @if(request()->hasAny(['kode', 'start_date', 'end_date', 'office', 'numeric_only']))
                    <a href="{{ route('reports.index') }}"
                        class="px-4 sm:px-6 py-1.5 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Export Button --}}
        @canany(['export laporan oil losses'])
            <form method="POST" action="{{ route('reports.export') }}" class="mt-4">
                @csrf
                <input type="hidden" name="office" value="{{ $officeFilter }}">
                <input type="hidden" name="kode" value="{{ request('kode') }}">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="hidden" name="numeric_only" value="{{ request('numeric_only') ? '1' : '0' }}">
                    Export ke Excel
                </button>
            </form>
        @endcanany
    </div>

    {{-- TABLE DATA OIL LOSSES --}}
    <x-ui.card class="mt-8" title="Data Laporan">

        <style>
            /* ── Tabel Oil Losses Premium Design ─────────────────── */
            .tbl-oil {
                border-collapse: separate;
                border-spacing: 0;
            }

            /* Group header baris pertama */
            .tbl-oil thead tr.group-header th {
                font-size: 0.65rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                padding: 6px 8px;
                border-bottom: none;
            }

            /* Sub-header baris kedua */
            .tbl-oil thead tr.sub-header th {
                font-size: 0.65rem;
                font-weight: 600;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                padding: 7px 8px;
                border-top: 1px solid rgba(0, 0, 0, .08);
                white-space: nowrap;
            }

            /* Freeze shadow kanan setelah kolom terakhir yg di-freeze */
            .tbl-oil .freeze-last {
                box-shadow: 4px 0 8px -2px rgba(0, 0, 0, .12);
            }

            /* Zebra stripes */
            .tbl-oil tbody tr:nth-child(even) td {
                background-color: #f9fafb;
            }

            .tbl-oil tbody tr:nth-child(even) td.freeze-cell {
                background-color: #f9fafb !important;
            }

            /* Hover row */
            .tbl-oil tbody tr:hover td {
                background-color: #eef2ff !important;
            }

            .tbl-oil tbody tr:hover td.freeze-cell {
                background-color: #eef2ff !important;
            }

            /* Badge pill */
            .badge-good {
                display: inline-flex;
                align-items: center;
                padding: 2px 8px;
                border-radius: 9999px;
                font-size: .7rem;
                font-weight: 700;
                background: #dcfce7;
                color: #166534;
            }

            .badge-bad {
                display: inline-flex;
                align-items: center;
                padding: 2px 8px;
                border-radius: 9999px;
                font-size: .7rem;
                font-weight: 700;
                background: #fee2e2;
                color: #991b1b;
            }

            .badge-neutral {
                display: inline-flex;
                align-items: center;
                padding: 2px 8px;
                border-radius: 9999px;
                font-size: .7rem;
                font-weight: 600;
                background: #f3f4f6;
                color: #374151;
            }

            /* Kolom freeze cell body */
            .freeze-cell {
                background-color: #fff;
                position: sticky;
            }

            /* Garis bawah header tebal */
            .tbl-oil thead {
                border-bottom: 2px solid #6366f1;
            }
        </style>

        <div class="relative overflow-x-auto overflow-y-auto max-h-[580px] border border-gray-200 rounded-xl shadow-sm">
            <table class="tbl-oil min-w-[4500px] text-xs text-gray-700">

                {{-- ================= HEADER ================= --}}
                <thead class="sticky top-0 z-40">

                    {{-- Baris 2: Sub-header kolom --}}
                    <tr class="sub-header">
                        {{-- Freeze: # --}}
                        <th
                            class="freeze-cell sticky left-0 z-[60] !bg-indigo-600 text-indigo-100 border border-indigo-500/50 w-[50px] text-center">
                            No</th>
                        {{-- Freeze: TGL SAMPEL --}}
                        <th class="freeze-cell sticky z-[55] !bg-indigo-600 text-indigo-100 border border-indigo-500/50 w-[110px]"
                            style="left:50px">TGL SAMPEL</th>
                        {{-- Freeze: KODE (last) --}}
                        <th class="freeze-cell freeze-last sticky z-[55] !bg-indigo-600 text-indigo-100 border border-indigo-500/50 w-[90px]"
                            style="left:160px">KODE</th>

                        {{-- Info Waktu & Input (scrollable) --}}
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50 min-w-[100px]">BULAN</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50 min-w-[110px]">TANGGAL</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50 min-w-[90px]">JAM</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50 min-w-[160px]">TGL & JAM AKHIR
                            INPUT</th>
                        <th class="bg-slate-500 text-slate-100 border border-slate-400/50 min-w-[140px]">INPUTED BY</th>

                        {{-- Info Pivot --}}
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 min-w-[150px]">NAMA PIVOT</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 min-w-[120px]">OPERATOR</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 min-w-[110px]">SAMPEL BOY</th>
                        <th class="bg-teal-600 text-teal-100 border border-teal-500/50 min-w-[100px]">JENIS OLAH</th>

                        {{-- Pengukuran --}}
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right min-w-[110px]">CAWAN
                            KOSONG</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right min-w-[110px]">BERAT
                            BASAH</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right min-w-[120px]">CAWAN +
                            BASAH</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right min-w-[120px]">CAWAN +
                            KERING</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right min-w-[110px]">SETELAH
                            OVEN</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right min-w-[110px]">LABU
                            KOSONG</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right min-w-[100px]">OIL +
                            LABU</th>
                        <th class="bg-cyan-600 text-cyan-100 border border-cyan-500/50 text-right min-w-[90px]">MINYAK
                        </th>

                        {{-- OLWB --}}
                        <th class="bg-blue-600 text-blue-100 border border-blue-500/50 text-right">MOIST (%)</th>
                        <th class="bg-blue-600 text-blue-100 border border-blue-500/50 text-right">DM/WM (%)</th>
                        <th class="bg-blue-600 text-blue-100 border border-blue-500/50 text-right">OLWB (%)</th>
                        <th class="bg-blue-600 text-blue-100 border border-blue-500/50 text-right">LIMIT OLWB</th>

                        {{-- OLDB --}}
                        <th class="bg-violet-600 text-violet-100 border border-violet-500/50 text-right">OLDB (%)</th>
                        <th class="bg-violet-600 text-violet-100 border border-violet-500/50 text-right">LIMIT OLDB</th>

                        {{-- Oil Losses --}}
                        <th class="bg-red-600 text-red-100 border border-red-500/50 text-right">OIL LOSSES</th>
                        <th class="bg-red-600 text-red-100 border border-red-500/50 text-right">LIMIT OL</th>

                        {{-- Persen 4 --}}
                        <th class="bg-amber-500 text-amber-100 border border-amber-400/50 text-right">PERSEN 4</th>
                    </tr>
                </thead>

                {{-- ================= BODY ================= --}}
                <tbody class="divide-y divide-gray-100">
                    @forelse ($calculations as $calc)
                        @php $rowIdx = ($calculations->currentPage() - 1) * $calculations->perPage() + $loop->iteration; @endphp
                        <tr id="row-{{ $loop->index }}">
                            {{-- Freeze: # + expand --}}
                            <td class="freeze-cell border-r border-gray-200 px-2 py-2.5 z-[20]" style="left:0">
                                <div class="flex items-center gap-1">
                                    <button onclick="toggleOilDetail({{ $loop->index }})" id="oil-btn-{{ $loop->index }}"
                                        class="sm:hidden w-5 h-5 flex-shrink-0 rounded-full bg-indigo-100 text-indigo-700 hover:bg-indigo-200 flex items-center justify-center text-xs font-bold transition-all">+</button>
                                    <span class="font-semibold text-gray-500 text-xs">{{ $rowIdx }}</span>
                                </div>
                            </td>
                            {{-- Freeze: TGL SAMPEL --}}
                            <td class="freeze-cell border-r border-gray-200 px-3 py-2.5 whitespace-nowrap z-[10]"
                                style="left:50px">
                                {{ $calc->tanggal_sampel ? \Carbon\Carbon::parse($calc->tanggal_sampel)->format('d-m-Y') : '-' }}
                            </td>
                            {{-- Freeze: KODE (last) --}}
                            <td class="freeze-cell freeze-last border-r-2 border-indigo-200 px-3 py-2.5 z-[10]"
                                style="left:160px">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-indigo-100 text-indigo-800 ring-1 ring-indigo-300">
                                    {{ $calc->kode }}
                                </span>
                            </td>
                            {{-- Info Waktu & Input (scrollable) --}}
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($calc->created_at)->format('F Y') }}
                            </td>
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($calc->created_at)->format('d-m-Y') }}
                            </td>
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($calc->created_at)->format('H:i:s') }}
                            </td>
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50 whitespace-nowrap">
                                {{ $calc->updated_at ? \Carbon\Carbon::parse($calc->updated_at)->format('d-m-Y H:i:s') : '-' }}
                            </td>
                            <td class="border-r border-slate-200 px-3 py-2.5 bg-slate-50 whitespace-nowrap">
                                {{ $calc->user_name ?? '-' }}
                            </td>
                            {{-- Info Pivot --}}
                            <td
                                class="border-r border-gray-100 px-3 py-2.5 bg-teal-50/40 whitespace-nowrap font-medium text-teal-800">
                                {{ $calc->pivot ?? '-' }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50 whitespace-nowrap">
                                {{ $calc->operator ?? '-' }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50 whitespace-nowrap">
                                {{ $calc->sampel_boy ?? '-' }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 bg-slate-50 whitespace-nowrap">
                                @if($calc->jenis)
                                    <span class="badge-neutral">{{ $calc->jenis }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            {{-- Data Pengukuran --}}
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono text-gray-700">
                                {{ $calc->cawan_kosong_fmt }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono text-gray-700">
                                {{ $calc->berat_basah_fmt }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono text-gray-700">
                                {{ $calc->total_cawan_basah_fmt }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono text-gray-700">
                                {{ $calc->cawan_sample_kering_fmt }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono text-gray-700">
                                {{ $calc->sampel_setelah_oven_fmt }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono text-gray-700">
                                {{ $calc->labu_kosong_fmt }}</td>
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right font-mono text-gray-700">
                                {{ $calc->oil_labu_fmt }}</td>
                            <td class="border-r border-gray-200 px-3 py-2.5 text-right font-mono text-gray-700">
                                {{ $calc->minyak_fmt }}</td>

                            {{-- MOIST & DM/WM --}}
                            <td
                                class="border-r border-gray-100 px-3 py-2.5 text-right font-mono bg-blue-50/50 text-blue-800">
                                {{ $calc->moist_fmt }}</td>
                            <td
                                class="border-r border-blue-200 px-3 py-2.5 text-right font-mono bg-blue-50/50 text-blue-800">
                                {{ $calc->dmwm_fmt }}</td>

                            {{-- OLWB --}}
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right bg-blue-50/40">
                                @php
                                    $olwb_calc = $calc->olwb ?? 0;
                                    $limit_olwb_calc = $calc->limitOLWB ?? 0;
                                    $isGoodOlwb = $olwb_calc <= $limit_olwb_calc;
                                @endphp
                                @if($limit_olwb_calc > 0)
                                    <span class="{{ $isGoodOlwb ? 'badge-good' : 'badge-bad' }}">
                                        {{ $calc->olwb_fmt }}
                                    </span>
                                @else
                                    <span class="font-mono text-gray-700">{{ $calc->olwb_fmt }}</span>
                                @endif
                            </td>
                            <td
                                class="border-r border-blue-200 px-3 py-2.5 text-right font-mono bg-blue-50/40 text-blue-700">
                                {{ $calc->limitOLWB_fmt }}</td>

                            {{-- OLDB --}}
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right bg-violet-50/40">
                                @php
                                    $oldb_calc = $calc->oldb ?? 0;
                                    $limit_oldb_calc = $calc->limitOLDB ?? 0;
                                    $isGoodOldb = $oldb_calc <= $limit_oldb_calc;
                                @endphp
                                @if($limit_oldb_calc > 0)
                                    <span class="{{ $isGoodOldb ? 'badge-good' : 'badge-bad' }}">
                                        {{ $calc->oldb_fmt }}
                                    </span>
                                @else
                                    <span class="font-mono text-gray-700">{{ $calc->oldb_fmt }}</span>
                                @endif
                            </td>
                            <td
                                class="border-r border-violet-200 px-3 py-2.5 text-right font-mono bg-violet-50/40 text-violet-700">
                                {{ $calc->limitOLDB_fmt }}</td>

                            {{-- OIL LOSSES --}}
                            <td class="border-r border-gray-100 px-3 py-2.5 text-right bg-red-50/40">
                                @php
                                    $oil_losses_calc = $calc->oil_losses ?? 0;
                                    $limit_oil_losses_calc = $calc->limitOL ?? 0;
                                    $isGoodOL = $oil_losses_calc <= $limit_oil_losses_calc;
                                @endphp
                                @if($limit_oil_losses_calc > 0)
                                    <span class="{{ $isGoodOL ? 'badge-good' : 'badge-bad' }}">
                                        {{ $calc->oil_losses_fmt }}
                                    </span>
                                @else
                                    <span class="font-mono text-gray-700">{{ $calc->oil_losses_fmt }}</span>
                                @endif
                            </td>
                            <td class="border-r border-red-200 px-3 py-2.5 text-right font-mono bg-red-50/40 text-red-700">
                                {{ $calc->limitOL_fmt }}</td>

                            {{-- PERSEN 4 --}}
                            <td class="px-3 py-2.5 text-right font-mono bg-amber-50/50 text-amber-800">
                                {{ $calc->persen4_fmt }}</td>
                        </tr>
                        {{-- Child detail row (expandable, DataTables-style) --}}
                        <tr id="oil-detail-{{ $loop->index }}" style="display:none" class="bg-indigo-50/30">
                            <td colspan="27" class="px-4 py-4 border-b border-indigo-100">
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-3 text-xs">
                                    {{-- Identitas --}}
                                    <div>
                                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider">TGL Sampel
                                        </p>
                                        <p class="mt-0.5 text-gray-700">
                                            {{ $calc->tanggal_sampel ? \Carbon\Carbon::parse($calc->tanggal_sampel)->format('d-m-Y') : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider">Kode</p>
                                        <p class="mt-0.5 font-bold text-indigo-700">{{ $calc->kode ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bulan/Tgl
                                            Input</p>
                                        <p class="mt-0.5 text-gray-700">
                                            {{ \Carbon\Carbon::parse($calc->created_at)->format('d-m-Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Inputed By
                                        </p>
                                        <p class="mt-0.5 text-gray-700">{{ $calc->user_name ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TGL & JAM
                                            Akhir</p>
                                        <p class="mt-0.5 text-gray-700">
                                            {{ $calc->updated_at ? \Carbon\Carbon::parse($calc->updated_at)->format('d-m-Y H:i:s') : '-' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-teal-500 uppercase tracking-wider">Nama Pivot
                                        </p>
                                        <p class="mt-0.5 font-medium text-teal-700">{{ $calc->pivot ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-teal-500 uppercase tracking-wider">Operator</p>
                                        <p class="mt-0.5 text-gray-700">{{ $calc->operator ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-teal-500 uppercase tracking-wider">Sampel Boy
                                        </p>
                                        <p class="mt-0.5 text-gray-700">{{ $calc->sampel_boy ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-teal-500 uppercase tracking-wider">Jenis Olah
                                        </p>
                                        <p class="mt-0.5 text-gray-700">{{ $calc->jenis ?? '-' }}</p>
                                    </div>
                                    {{-- Separator --}}
                                    <div
                                        class="col-span-2 sm:col-span-3 lg:col-span-4 border-t border-indigo-100 pt-2 mt-1">
                                    </div>
                                    {{-- Data Pengukuran --}}
                                    <div>
                                        <p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Cawan Kosong
                                        </p>
                                        <p class="mt-0.5 font-mono text-gray-800">{{ $calc->cawan_kosong_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Berat Basah
                                        </p>
                                        <p class="mt-0.5 font-mono text-gray-800">{{ $calc->berat_basah_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Cawan +
                                            Basah</p>
                                        <p class="mt-0.5 font-mono text-gray-800">{{ $calc->total_cawan_basah_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Cawan +
                                            Kering</p>
                                        <p class="mt-0.5 font-mono text-gray-800">{{ $calc->cawan_sample_kering_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Setelah Oven
                                        </p>
                                        <p class="mt-0.5 font-mono text-gray-800">{{ $calc->sampel_setelah_oven_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Labu Kosong
                                        </p>
                                        <p class="mt-0.5 font-mono text-gray-800">{{ $calc->labu_kosong_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Oil + Labu
                                        </p>
                                        <p class="mt-0.5 font-mono text-gray-800">{{ $calc->oil_labu_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-cyan-500 uppercase tracking-wider">Minyak</p>
                                        <p class="mt-0.5 font-mono text-gray-800">{{ $calc->minyak_fmt }}</p>
                                    </div>
                                    {{-- Separator --}}
                                    <div
                                        class="col-span-2 sm:col-span-3 lg:col-span-4 border-t border-indigo-100 pt-2 mt-1">
                                    </div>
                                    {{-- Hasil Kalkulasi --}}
                                    <div>
                                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Moist (%)
                                        </p>
                                        <p class="mt-0.5 font-mono text-blue-700">{{ $calc->moist_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">DM/WM (%)
                                        </p>
                                        <p class="mt-0.5 font-mono text-blue-700">{{ $calc->dmwm_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">OLWB (%)</p>
                                        <p class="mt-0.5"><span
                                                class="{{ ($calc->olwb ?? 0) <= ($calc->limitOLWB ?? 0) && ($calc->limitOLWB ?? 0) > 0 ? 'badge-good' : 'badge-neutral' }}">{{ $calc->olwb_fmt }}</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Limit OLWB
                                        </p>
                                        <p class="mt-0.5 font-mono text-blue-600">{{ $calc->limitOLWB_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-violet-500 uppercase tracking-wider">OLDB (%)
                                        </p>
                                        <p class="mt-0.5"><span
                                                class="{{ ($calc->oldb ?? 0) <= ($calc->limitOLDB ?? 0) && ($calc->limitOLDB ?? 0) > 0 ? 'badge-good' : 'badge-neutral' }}">{{ $calc->oldb_fmt }}</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-violet-500 uppercase tracking-wider">Limit OLDB
                                        </p>
                                        <p class="mt-0.5 font-mono text-violet-600">{{ $calc->limitOLDB_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider">Oil Losses
                                        </p>
                                        <p class="mt-0.5"><span
                                                class="{{ ($calc->oil_losses ?? 0) <= ($calc->limitOL ?? 0) && ($calc->limitOL ?? 0) > 0 ? 'badge-good' : 'badge-bad' }}">{{ $calc->oil_losses_fmt }}</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider">Limit OL</p>
                                        <p class="mt-0.5 font-mono text-red-600">{{ $calc->limitOL_fmt }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-amber-500 uppercase tracking-wider">Persen 4
                                        </p>
                                        <p class="mt-0.5 font-mono text-amber-700">{{ $calc->persen4_fmt }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="27" class="text-center py-16 text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm">Tidak ada data untuk filter ini</span>
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
                <span>Melebihi limit (Perlu perhatian)</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="inline-block w-3 h-3 rounded-full bg-gray-200 border border-gray-300"></span>
                <span>Tidak ada limit</span>
            </div>
        </div>

        @if($calculations->hasPages())
            <div class="mt-6 px-4">
                {{ $calculations->links() }}
            </div>
        @endif
    </x-ui.card>

    <script>
        function toggleOilDetail(idx) {
            const row = document.getElementById('oil-detail-' + idx);
            const btn = document.getElementById('oil-btn-' + idx);
            const isHidden = row.style.display === 'none';
            row.style.display = isHidden ? 'table-row' : 'none';
            btn.textContent = isHidden ? '−' : '+';
            btn.classList.toggle('bg-indigo-300', isHidden);
            btn.classList.toggle('bg-indigo-100', !isHidden);
        }
    </script>
</x-layouts.app>