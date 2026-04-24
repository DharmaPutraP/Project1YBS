<x-layouts.app title="Rekap Oil Loss Foss">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Oil Loss Foss</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Rekap Oil Loss Foss</h1>
                <p class="mt-2 text-sm text-slate-500">Average OLWB (%) berdasarkan kode dan tanggal.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('oil-loss-foss.input') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Input Oil Loss Foss</a>
                <a href="{{ route('oil-loss-foss.rekap.export', request()->query()) }}" class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">Export Excel</a>
            </div>
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
                        @foreach ($operatorOptions as $operatorOption)
                            <option value="{{ $operatorOption }}" @selected($operator === $operatorOption)>{{ $operatorOption }}</option>
                        @endforeach
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
                            <th class="sticky left-0 z-20 bg-slate-100 px-4 py-3 text-left font-semibold">Row Labels</th>
                            @forelse ($columns as $column)
                                <th class="px-4 py-3 text-center font-semibold whitespace-nowrap">{{ $column['sample_name'] }}</th>
                            @empty
                                <th class="px-4 py-3 text-center font-semibold">Belum ada kode</th>
                            @endforelse
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($dates as $date)
                            <tr>
                                <td class="sticky left-0 z-10 bg-white px-4 py-3 font-semibold text-slate-700">{{ \Illuminate\Support\Carbon::parse($date)->format('d/m/Y') }}</td>
                                @foreach ($columns as $column)
                                    @php
                                        $value = $matrix[$date][$column['code']] ?? null;
                                    @endphp
                                    <td class="px-4 py-3 text-center text-slate-700 whitespace-nowrap">
                                        {{ $value === null ? '' : number_format((float) $value, 2) }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 1 }}" class="px-4 py-8 text-center text-slate-500">Belum ada data.</td>
                            </tr>
                        @endforelse

                        @if (count($columns) > 0)
                            <tr class="bg-amber-50">
                                <td class="sticky left-0 z-10 bg-amber-100 px-4 py-3 font-bold text-amber-900">Grand Total</td>
                                @foreach ($columns as $column)
                                    @php
                                        $grand = $grandTotals[$column['code']] ?? null;
                                    @endphp
                                    <td class="px-4 py-3 text-center font-semibold text-amber-900 whitespace-nowrap">
                                        {{ $grand === null ? '' : number_format((float) $grand, 2) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-xs text-slate-500">Nilai sel adalah rata-rata OLWB (%) per tanggal dan per kode.</p>
    </div>
</x-layouts.app>
