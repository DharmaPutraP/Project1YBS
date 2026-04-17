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

            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <button type="submit"
                    class="px-4 sm:px-6 py-1.5 sm:py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                    Filter
                </button>

                @if(request()->hasAny(['kode', 'start_date', 'end_date', 'office']))
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

                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export ke Excel
                </button>
            </form>
        @endcanany
    </div>

    {{-- TABLE DATA LAB --}}
    <x-ui.card class="mt-8" title="Data Laporan">
        <div class="relative overflow-x-auto overflow-y-auto max-h-[500px] border rounded-lg">

            <table class="min-w-[4500px] text-xs text-gray-700 divide-y divide-gray-200">

                {{-- ================= HEADER ================= --}}
                <thead class="bg-blue-50 sticky top-0 z-40">
                    <tr>
                        <th class="sticky top-0 left-0 z-[60] bg-blue-50 border px-4 py-3 w-[60px]">NO</th>
                        <th class="sticky top-0 lg:left-[60px] z-[50] bg-blue-50 border px-4 py-3 w-[120px]">BULAN</th>
                        <th class="sticky top-0 lg:left-[180px] z-[50] bg-blue-50 border px-4 py-3 w-[120px]">TANGGAL
                        </th>
                        <th class="sticky top-0 lg:left-[300px] z-[50] bg-blue-50 border px-4 py-3 w-[100px]">JAM</th>
                        <th class="sticky top-0 lg:left-[400px] z-[50] bg-blue-50 border px-4 py-3 w-[120px]">TGL & JAM
                            AKHIR
                            INPUT</th>
                        <th class="sticky top-0 lg:left-[520px] z-[50] bg-blue-50 border px-4 py-3 w-[120px]">TANGGAL
                            SAMPEL</th>
                        <th class="sticky top-0 lg:left-[640px] z-[50] bg-blue-50 border px-4 py-3 w-[120px]">KODE</th>
                        <th class="sticky top-0 lg:left-[760px] z-[50] bg-blue-50 border px-4 py-3 w-[160px]">INPUTED BY
                        </th>

                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">NAMA PIVOT</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OPERATOR</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">SAMPEL BOY</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">JENIS OLAH</th>

                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">CAWAN KOSONG</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">BERAT SAMPEL BASAH</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">CAWAN + BASAH</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">CAWAN + KERING</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">SETELAH OVEN</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LABU KOSONG</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OIL + LABU</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">MINYAK</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">MOIST (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">DM/WM (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OLWB (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LIMIT OLWB (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OLDB (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LIMIT OLDB (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OIL LOSSES</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LIMIT OL</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">PERSEN 4</th>
                    </tr>
                </thead>

                {{-- ================= BODY ================= --}}
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($calculations as $calc)
                        <tr class="hover:bg-gray-200">

                            <td class="sticky left-0 z-[20] bg-white border px-4 py-2">
                                {{ ($calculations->currentPage() - 1) * $calculations->perPage() + $loop->iteration }}
                            </td>

                            <td class="border px-4 py-2 lg:sticky lg:left-[60px] z-[10] bg-white">
                                {{ \Carbon\Carbon::parse($calc->created_at)->format('F Y') }}
                            </td>

                            <td class="border px-4 py-2 lg:sticky lg:left-[180px] z-[10] bg-white">
                                {{ \Carbon\Carbon::parse($calc->created_at)->format('d-m-Y') }}
                            </td>

                            <td class="border px-4 py-2 lg:sticky lg:left-[300px] z-[10] bg-white">
                                {{ \Carbon\Carbon::parse($calc->created_at)->format('H:i:s') }}
                            </td>

                            <td class="border px-4 py-2 lg:sticky lg:left-[400px] z-[10] bg-white">
                                {{ $calc->updated_at ? \Carbon\Carbon::parse($calc->updated_at)->format('d-m-Y H:i:s') : '-' }}
                            </td>

                            <td class="border px-4 py-2 lg:sticky lg:left-[520px] z-[10] bg-white">
                                {{ $calc->tanggal_sampel?->format('d-m-Y') ?? '-' }}
                            </td>

                            <td class="border px-4 py-2 font-semibold lg:sticky lg:left-[640px] z-[10] bg-white">
                                {{ $calc->kode }}
                            </td>

                            <td class="border px-4 py-2 lg:sticky lg:left-[760px] z-[10] bg-white">
                                {{ $calc->user_name ?? '-' }}
                            </td>

                            <td class="border px-4 py-2">{{ $calc->pivot ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $calc->operator ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $calc->sampel_boy ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $calc->jenis ?? '-' }}</td>

                            {{-- ===== NUMERIC (NULL → -) ===== --}}
                            <td class="border px-4 py-2 text-right">{{ $calc->cawan_kosong_fmt }}</td>
                            <td class="border px-4 py-2 text-right">{{ $calc->berat_basah_fmt }}</td>
                            <td class="border px-4 py-2 text-right">{{ $calc->total_cawan_basah_fmt }}</td>
                            <td class="border px-4 py-2 text-right">{{ $calc->cawan_sample_kering_fmt }}</td>
                            <td class="border px-4 py-2 text-right">{{ $calc->sampel_setelah_oven_fmt }}</td>
                            <td class="border px-4 py-2 text-right">{{ $calc->labu_kosong_fmt }}</td>
                            <td class="border px-4 py-2 text-right">{{ $calc->oil_labu_fmt }}</td>
                            <td class="border px-4 py-2 text-right">{{ $calc->minyak_fmt }}</td>
                            <td class="border px-4 py-2 text-right">{{ $calc->moist_fmt }}</td>
                            <td class="border px-4 py-2 text-right">{{ $calc->dmwm_fmt }}</td>
                            <td class="border px-4 py-2 text-right">
                                @php
                                    $olwb_calc = $calc->olwb ?? 0;
                                    $limit_olwb_calc = $calc->limitOLWB ?? 0;
                                    $isGood = $olwb_calc <= $limit_olwb_calc;
                                @endphp
                                <span @class([
                                    'font-semibold',
                                    'text-green-600' => $isGood && $limit_olwb_calc > 0,
                                    'text-red-600' => !$isGood && $limit_olwb_calc > 0,
                                ])>{{ $calc->olwb_fmt }}</span>
                            </td>
                            <td class="border px-4 py-2 text-right">{{ $calc->limitOLWB_fmt }}</td>
                            <td class="border px-4 py-2 text-right">@php
                                $oldb_calc = $calc->oldb ?? 0;
                                $limit_oldb_calc = $calc->limitOLDB ?? 0;
                                $isGood = $oldb_calc <= $limit_oldb_calc;
                            @endphp
                                <span @class([
                                    'font-semibold',
                                    'text-green-600' => $isGood && $limit_oldb_calc > 0,
                                    'text-red-600' => !$isGood && $limit_oldb_calc > 0,
                                ])>{{ $calc->oldb_fmt }}</span>
                            </td>
                            <td class="border px-4 py-2 text-right">{{ $calc->limitOLDB_fmt }}</td>
                            <td class="border px-4 py-2 text-right">
                                @php
                                    $oil_losses_calc = $calc->oil_losses ?? 0;
                                    $limit_oil_losses_calc = $calc->limitOL ?? 0;
                                    $isGood = $oil_losses_calc <= $limit_oil_losses_calc;
                                @endphp
                                <span @class([
                                    'font-semibold',
                                    'text-green-600' => $isGood && $limit_oil_losses_calc > 0,
                                    'text-red-600' => !$isGood && $limit_oil_losses_calc > 0,
                                ])>{{ $calc->oil_losses_fmt }}</span>
                            </td>
                            <td class="border px-4 py-2 text-right">{{ $calc->limitOL_fmt }}</td>
                            <td class="border px-4 py-2 text-right">{{ $calc->persen4_fmt }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="32" class="text-center py-10 text-gray-400">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        @if($calculations->hasPages())
            <div class="mt-6 px-4">
                {{ $calculations->links() }}
            </div>
        @endif
    </x-ui.card>

</x-layouts.app>