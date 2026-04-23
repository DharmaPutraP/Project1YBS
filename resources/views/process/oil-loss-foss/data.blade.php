<x-layouts.app title="Data Oil Loss Foss">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Oil Loss Foss</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Data Oil Loss Foss</h1>
                <p class="mt-2 text-sm text-slate-500">Daftar input Oil Loss Foss yang sudah tersimpan.</p>
            </div>
            <a href="{{ route('oil-loss-foss.input') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Input Oil Loss Foss</a>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('oil-loss-foss.data') }}" class="grid gap-3 md:grid-cols-2 xl:grid-cols-6">
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
                <div class="xl:col-span-2">
                    <label for="machine_name" class="mb-1 block text-xs font-semibold text-slate-600">Mesin</label>
                    <select id="machine_name" name="machine_name" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="all" @selected($machineName === 'all')>Semua Mesin</option>
                        @foreach ($machineOptions as $machineOption)
                            <option value="{{ $machineOption }}" @selected($machineName === $machineOption)>{{ $machineOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 xl:col-span-6 flex gap-2">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Filter</button>
                    <a href="{{ route('oil-loss-foss.data') }}" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold">Waktu</th>
                            <th class="px-4 py-3 text-left font-semibold">Operator</th>
                            <th class="px-4 py-3 text-left font-semibold">Shift</th>
                            <th class="px-4 py-3 text-left font-semibold">Group</th>
                            <th class="px-4 py-3 text-left font-semibold">Mesin</th>
                            <th class="px-4 py-3 text-left font-semibold">MOIST (%)</th>
                            <th class="px-4 py-3 text-left font-semibold">OLWB (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($rows as $row)
                            <tr>
                                <td class="px-4 py-3 text-slate-700">{{ $row->tanggal?->format('d-m-Y') }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->waktu }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->operator }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->shift }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->machine_group }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->machine_name }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->moist === null ? '-' : number_format((float) $row->moist, 2) }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->olwb === null ? '-' : number_format((float) $row->olwb, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-500">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-4 py-4">
                {{ $rows->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
