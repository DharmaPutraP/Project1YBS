<x-layouts.app title="Rekap Breakdown">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Lap Jangkos</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Rekap Breakdown</h1>
                <p class="mt-2 text-sm text-slate-500">Rekap kondisi breakdown berdasarkan data Kondisi Lainnya Tim 1 dan Tim 2 dari Informasi Proses.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('process.performance-sampel-boy') }}" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Performance Sampel Boy</a>
                <form method="POST" action="{{ route('process.rekap-breakdown.export') }}" class="inline">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button type="submit" class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">Export Excel</button>
                </form>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('process.rekap-breakdown') }}" class="flex flex-col gap-3 md:flex-row md:items-end">
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
                    <a href="{{ route('process.rekap-breakdown') }}" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-900">Tabel Rekap Breakdown</h2>
                <p class="mt-1 text-sm text-slate-500">Kolom tanggal mengikuti rentang filter yang dipilih.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Remarks</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Jam Awal Breakdown</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Jam Akhir Breakdown</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Jam Breakdown</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse (($breakdownData['rows'] ?? []) as $row)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $row['tanggal'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row['alasan'] ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $row['jam_awal_breakdown'] ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $row['jam_akhir_breakdown'] ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-slate-900">{{ $row['total_jam_breakdown'] ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-slate-900">{{ $breakdownData['total_duration'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-slate-500">Belum ada data breakdown untuk rentang tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
