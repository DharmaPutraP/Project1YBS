<x-layouts.app title="Data USB">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Lap Jangkos</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Rekap Data USB</h1>
                <p class="mt-2 text-sm text-slate-500">Rekapan %USB per tanggal dan nomor rebusan (1-8).</p>
            </div>
            <a href="{{ route('lap-jangkos.input-usb') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Input USB</a>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('lap-jangkos.data-usb') }}" class="flex flex-col gap-3 md:flex-row md:items-end">
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
                    <a href="{{ route('lap-jangkos.data-usb') }}" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="sticky left-0 z-20 bg-slate-100 px-4 py-3 text-left font-semibold">No Rebusan</th>
                            @forelse ($dates as $date)
                                <th class="px-4 py-3 text-center font-semibold whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($date)->format('d-m-Y') }}</th>
                            @empty
                                <th class="px-4 py-3 text-center font-semibold">Belum ada tanggal</th>
                            @endforelse
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($rowNumbers as $rowNumber)
                            <tr>
                                <td class="sticky left-0 z-10 bg-white px-4 py-3 font-semibold text-slate-700">{{ $rowNumber }}</td>
                                @forelse ($dates as $date)
                                    @php
                                        $value = $matrix[$rowNumber][$date] ?? null;
                                    @endphp
                                    <td class="px-4 py-3 text-center text-slate-700 whitespace-nowrap">
                                        {{ $value === null ? '-' : number_format((float) $value, 2) . '%' }}
                                    </td>
                                @empty
                                    <td class="px-4 py-8 text-center text-slate-500">Belum ada data.</td>
                                @endforelse
                            </tr>
                        @endforeach

                        <tr class="bg-amber-50">
                            <td class="sticky left-0 z-10 bg-amber-100 px-4 py-3 font-bold text-amber-900">Average</td>
                            @forelse ($dates as $date)
                                @php
                                    $avg = $averages[$date] ?? null;
                                @endphp
                                <td class="px-4 py-3 text-center font-semibold text-amber-900 whitespace-nowrap">
                                    {{ $avg === null ? '-' : number_format((float) $avg, 2) . '%' }}
                                </td>
                            @empty
                                <td class="px-4 py-3 text-center font-semibold text-amber-900">-</td>
                            @endforelse
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-xs text-slate-500">*Baris average menghitung rerata %USB per tanggal dari data yang terisi.</p>
    </div>
</x-layouts.app>
