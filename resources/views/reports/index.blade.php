<x-layouts.app title="Laporan">

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

    {{-- Date Range Filter --}}
    <div class="mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Filter Kode
                </label>
                <select name="kode" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">-- Semua Kode --</option>
                    @foreach($kodeOptions as $kodeValue => $kodeLabel)
                        <option value="{{ $kodeValue }}" {{ request('kode') == $kodeValue ? 'selected' : '' }}>
                            {{ $kodeLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Mulai
                </label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal Akhir
                </label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div class="flex gap-2">
                <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg">
                    Filter
                </button>

                @if(request()->hasAny(['kode', 'start_date', 'end_date']))
                    <a href="{{ route('reports.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABLE DATA LAB --}}
    <x-ui.card class="mt-8" title="Data Laporan">
        <div class="relative overflow-x-auto overflow-y-auto max-h-[500px] border rounded-lg">

            <table class="min-w-[4500px] text-xs text-gray-700 divide-y divide-gray-200">

                {{-- ================= HEADER ================= --}}
                <thead class="bg-blue-50 sticky top-0 z-40">
                    <tr>
                        <th class="sticky top-0 left-0 z-[60] bg-blue-50 border px-4 py-3 w-[60px]">NO</th>
                        <th class="sticky top-0 left-[60px] z-[55] bg-blue-50 border px-4 py-3 w-[120px]">BULAN</th>
                        <th class="sticky top-0 left-[180px] z-[55] bg-blue-50 border px-4 py-3 w-[120px]">TANGGAL</th>
                        <th class="sticky top-0 left-[300px] z-[55] bg-blue-50 border px-4 py-3 w-[100px]">JAM</th>
                        <th class="sticky top-0 left-[400px] z-[55] bg-blue-50 border px-4 py-3 w-[120px]">KODE</th>
                        <th class="sticky top-0 left-[520px] z-[55] bg-blue-50 border px-4 py-3 w-[160px]">INPUTED BY
                        </th>

                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">NAMA PIVOT</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OPERATOR</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">SAMPEL BOY</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">JENIS OLAH</th>

                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">CAWAN KOSONG</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">BERAT BASAH</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">CAWAN + BASAH</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">CAWAN + KERING</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">SETELAH OVEN</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LABU KOSONG</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OIL + LABU</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">MINYAK</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">MOIST (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">DM/WM (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OLWB (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LIMIT (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OLDB (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LIMIT (%)</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">OIL LOSSES</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">LIMIT</th>
                        <th class="sticky top-0 z-20 bg-blue-50 border px-4 py-3">PERSEN 4</th>
                    </tr>
                </thead>

                {{-- ================= BODY ================= --}}
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($calculations as $calc)
                        <tr class="hover:bg-gray-50">

                            <td class="sticky left-0 bg-white border px-4 py-2">
                                {{ ($calculations->currentPage() - 1) * $calculations->perPage() + $loop->iteration }}
                            </td>

                            <td class="sticky left-[60px] bg-white border px-4 py-2">
                                {{ \Carbon\Carbon::parse($calc->created_at)->format('F Y') }}
                            </td>

                            <td class="sticky left-[180px] bg-white border px-4 py-2">
                                {{ \Carbon\Carbon::parse($calc->created_at)->format('d-m-Y') }}
                            </td>

                            <td class="sticky left-[300px] bg-white border px-4 py-2">
                                {{ \Carbon\Carbon::parse($calc->created_at)->format('H:i:s') }}
                            </td>

                            <td class="sticky left-[400px] bg-white border px-4 py-2 font-semibold">
                                {{ $calc->kode }}
                            </td>

                            <td class="sticky left-[520px] bg-white border px-4 py-2">
                                {{ $calc->user_name ?? '-' }}
                            </td>

                            <td class="border px-4 py-2">{{ $calc->pivot ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $calc->operator ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $calc->sampel_boy ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $calc->jenis ?? '-' }}</td>

                            {{-- ===== NUMERIC (NULL → -) ===== --}}
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->cawan_kosong === null ? '-' : number_format($calc->cawan_kosong, 4) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->berat_basah === null ? '-' : number_format($calc->berat_basah, 4) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->total_cawan_basah === null ? '-' : number_format($calc->total_cawan_basah, 4) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->cawan_sample_kering === null ? '-' : number_format($calc->cawan_sample_kering, 4) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->sampel_setelah_oven === null ? '-' : number_format($calc->sampel_setelah_oven, 4) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->labu_kosong === null ? '-' : number_format($calc->labu_kosong, 4) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->oil_labu === null ? '-' : number_format($calc->oil_labu, 4) }}
                            </td>

                            <td class="border px-4 py-2 text-right font-bold">
                                {{ $calc->minyak === null ? '-' : number_format($calc->minyak, 4) }}
                            </td>

                            <td class="border px-4 py-2 text-right">
                                {{ $calc->moist === null ? '-' : number_format($calc->moist, 2) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->dmwm === null ? '-' : number_format($calc->dmwm, 2) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->olwb === null ? '-' : number_format($calc->olwb, 2) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->limitOLWB === null ? '-' : number_format($calc->limitOLWB, 2) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->oldb === null ? '-' : number_format($calc->oldb, 2) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->limitOLDB === null ? '-' : number_format($calc->limitOLDB, 2) }}
                            </td>
                            <td class="border px-4 py-2 text-right font-semibold">
                                {{ $calc->oil_losses === null ? '-' : number_format($calc->oil_losses, 2) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->limitOL === null ? '-' : number_format($calc->limitOL, 2) }}
                            </td>
                            <td class="border px-4 py-2 text-right">
                                {{ $calc->persen4 === null ? '-' : number_format($calc->persen4, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="30" class="text-center py-10 text-gray-400">
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