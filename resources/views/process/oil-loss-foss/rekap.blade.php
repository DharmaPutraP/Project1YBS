<x-layouts.app title="Rekap Oil Loss Foss">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Oil Loss Foss</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Rekap Oil Loss Foss</h1>
                <p class="mt-2 text-sm text-slate-500">Rekapan harian MOIST dan OLWB per titik mesin.</p>
            </div>
            <a href="{{ route('oil-loss-foss.input') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Input Oil Loss Foss</a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('oil-loss-foss.rekap') }}" class="grid gap-3 md:grid-cols-2 xl:grid-cols-5">
                <div>
                    <label for="start_date" class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Awal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="end_date" class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="operator" class="mb-1 block text-xs font-semibold text-slate-600">Operator</label>
                    <select id="operator" name="operator" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="ALL" @selected($operator === 'ALL')>Semua</option>
                        <option value="YBS" @selected($operator === 'YBS')>YBS</option>
                        <option value="SUN" @selected($operator === 'SUN')>SUN</option>
                    </select>
                </div>
                <div>
                    <label for="shift" class="mb-1 block text-xs font-semibold text-slate-600">Shift</label>
                    <select id="shift" name="shift" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="all" @selected($shift === 'all')>Semua</option>
                        <option value="1" @selected($shift === '1')>1</option>
                        <option value="2" @selected($shift === '2')>2</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Filter</button>
                    <a href="{{ route('oil-loss-foss.rekap') }}" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs md:text-sm">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="sticky left-0 z-20 bg-slate-100 px-4 py-3 text-left font-semibold">Mesin</th>
                            @forelse ($dates as $date)
                                <th class="px-4 py-3 text-center font-semibold whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($date)->format('d-m-Y') }}</th>
                            @empty
                                <th class="px-4 py-3 text-center font-semibold">Belum ada tanggal</th>
                            @endforelse
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($machineNames as $machineName)
                            <tr>
                                <td class="sticky left-0 z-10 bg-white px-4 py-3 font-semibold text-slate-700">{{ $machineName }}</td>
                                @forelse ($dates as $date)
                                    @php
                                        $cell = $rekap[$machineName][$date] ?? null;
                                    @endphp
                                    <td class="px-4 py-3 text-center text-slate-700 align-top whitespace-nowrap">
                                        @if ($cell === null)
                                            -
                                        @else
                                            <div>M: {{ number_format((float) $cell['avg_moist'], 2) }}</div>
                                            <div>O: {{ number_format((float) $cell['avg_olwb'], 2) }}</div>
                                            <div class="text-[10px] text-slate-500">n={{ $cell['total_data'] }}</div>
                                        @endif
                                    </td>
                                @empty
                                    <td class="px-4 py-8 text-center text-slate-500">Belum ada data.</td>
                                @endforelse
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-xs text-slate-500">Keterangan sel: M = average MOIST, O = average OLWB, n = jumlah data pada tanggal tersebut.</p>
    </div>
</x-layouts.app>
