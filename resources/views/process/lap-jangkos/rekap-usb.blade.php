<x-layouts.app title="Rekap USB">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Lap Jangkos</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Rekap USB</h1>
                <p class="mt-2 text-sm text-slate-500">Rekap %USB per tanggal berdasarkan no rebusan, dengan baris Avg harian di bawah.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('lap-jangkos.data-usb', request()->query()) }}" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Data USB</a>
                <a href="{{ route('lap-jangkos.rekap-usb.export', request()->query()) }}" class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">Export Excel</a>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('lap-jangkos.rekap-usb') }}" class="flex flex-col gap-3 md:flex-row md:items-end">
                <div class="flex-1">
                    <label for="start_date" class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Awal</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="flex-1">
                    <label for="end_date" class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Filter</button>
                    <a href="{{ route('lap-jangkos.rekap-usb') }}" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-900">Tabel Rekap USB</h2>
                <p class="mt-1 text-sm text-slate-500">Kolom tanggal mengikuti rentang filter yang dipilih.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-max text-sm">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="sticky left-0 z-20 bg-slate-100 px-4 py-3 text-left font-semibold whitespace-nowrap">No Rebusan</th>
                            @foreach ($recapData['dates'] as $date)
                                <th class="px-4 py-3 text-center font-semibold whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($date)->format('d-m-Y') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($recapData['row_numbers'] as $rowNumber)
                            <tr>
                                <td class="sticky left-0 z-10 bg-white px-4 py-3 font-semibold text-slate-700 whitespace-nowrap">{{ $rowNumber }}</td>
                                @foreach ($recapData['dates'] as $date)
                                    @php
                                        $value = $recapData['matrix'][$rowNumber][$date] ?? null;
                                    @endphp
                                    <td class="px-4 py-3 text-center whitespace-nowrap text-slate-700">
                                        {{ $value === null ? '-' : number_format((float) $value, 2, ',', '.') . '%' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="bg-amber-50">
                            <td class="sticky left-0 z-10 bg-amber-100 px-4 py-3 font-bold text-amber-900 whitespace-nowrap">Avg</td>
                            @foreach ($recapData['dates'] as $date)
                                @php
                                    $avg = $recapData['date_averages'][$date] ?? null;
                                @endphp
                                <td class="px-4 py-3 text-center font-semibold text-amber-900 whitespace-nowrap">
                                    {{ $avg === null ? '-' : number_format((float) $avg, 2, ',', '.') . '%' }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
