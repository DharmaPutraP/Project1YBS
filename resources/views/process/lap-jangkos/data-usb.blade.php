<x-layouts.app title="Data USB">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Lap Jangkos</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Data USB</h1>
                <p class="mt-2 text-sm text-slate-500">Tabel detail input USB dalam periode terpilih.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('lap-jangkos.input-usb') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Input USB</a>
                <a href="{{ route('lap-jangkos.rekap-usb', request()->query()) }}" class="inline-flex items-center rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700">Lihat Rekap USB</a>
            </div>
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
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-900">Data USB</h2>
                <p class="mt-1 text-sm text-slate-500">Kolom AVERAGE menampilkan rata-rata %USB harian dari seluruh no rebusan pada tanggal yang sama.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[1200px] w-full border-collapse text-sm">
                    <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Tgl</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">JAM</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Created By</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Shift</th>
                            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">No Rebusan / Sterilizer</th>
                            <th class="px-4 py-3 text-right font-semibold whitespace-nowrap">Diamati (Jlh Janjang)</th>
                            <th class="px-4 py-3 text-right font-semibold whitespace-nowrap">Lolos (Jlh Janjang)</th>
                            <th class="px-4 py-3 text-right font-semibold whitespace-nowrap">%USB</th>
                            <th class="px-4 py-3 text-right font-semibold whitespace-nowrap">AVERAGE</th>
                            @role('Super Admin')
                                <th class="w-[150px] px-4 py-3 text-center font-semibold whitespace-nowrap">Aksi</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($detailRows as $row)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $row['tanggal'] }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $row['jam'] }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $row['created_by'] ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $row['shift'] }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $row['no_rebusan'] }}</td>
                                <td class="px-4 py-3 text-right whitespace-nowrap text-slate-700">{{ number_format((float) $row['diamati_jlh_janjang'], 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right whitespace-nowrap text-slate-700">{{ number_format((float) $row['lolos_jlh_janjang'], 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right whitespace-nowrap font-semibold text-indigo-700">{{ number_format((float) $row['persen_usb'], 2, ',', '.') . '%' }}</td>
                                <td class="px-4 py-3 text-right whitespace-nowrap font-semibold text-amber-700">{{ number_format((float) $row['average'], 2, ',', '.') . '%' }}</td>
                                @role('Super Admin')
                                    <td class="w-[150px] px-4 py-3 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('lap-jangkos.edit-usb', $row['id']) }}" class="inline-flex items-center rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-200">Edit</a>
                                            <form action="{{ route('lap-jangkos.destroy-usb', $row['id']) }}" method="POST" onsubmit="return confirm('Hapus data USB ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-200">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()?->hasRole('Super Admin') ? 10 : 9 }}" class="px-4 py-8 text-center text-slate-500">Belum ada data USB pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
