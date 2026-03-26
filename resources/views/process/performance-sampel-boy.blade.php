<x-layouts.app title="Performance Sampel Boy">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Performance Sampel Boy</h1>
        <p class="mt-2 text-sm text-gray-600">Performa sampling berdasarkan data proses, mesin, dan input sampling aktual.</p>
    </div>

    <x-ui.card title="Filter Tanggal">
        <form method="GET" action="{{ route('process.performance-sampel-boy') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label for="date" class="mb-2 block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" id="date" name="date" value="{{ $selectedDate }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 md:w-64">
            </div>
            <div>
                <label for="office" class="mb-2 block text-sm font-medium text-gray-700">Office</label>
                <select id="office" name="office"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 md:w-48">
                    <option value="all" {{ ($selectedOffice ?? 'all') === 'all' ? 'selected' : '' }}>Semua Office</option>
                    @foreach (($officeOptions ?? []) as $office)
                        <option value="{{ $office }}" {{ ($selectedOffice ?? '') === $office ? 'selected' : '' }}>{{ $office }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                Tampilkan
            </button>
        </form>

        <form method="POST" action="{{ route('process.performance-sampel-boy.export') }}" class="mt-3">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}">
            <input type="hidden" name="office" value="{{ $selectedOffice }}">
            <button type="submit"
                class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-700">
                Export ke Excel
            </button>
        </form>
    </x-ui.card>

    <x-ui.card title="Data Performance" class="mt-6">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1800px] text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Tim</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Mulai Proses</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Akhir Proses</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Total Hours</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Nama Sampel Boy</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Fiber Cyclone</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">LTDS</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Claybath Wet Shell</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Inlet Kernel Silo</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Outlet Kernel Silo</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Press</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Eficiency</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Perf Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($rows as $row)
                        <tr>
                            <td class="px-3 py-2 text-gray-700">{{ $row['tanggal'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['team_name'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['jam_mulai'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['jam_akhir'] }}</td>
                            <td class="px-3 py-2 font-medium text-gray-800">{{ $row['total_hours'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['nama_sampel_boy'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['fibre_cyclone'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['ltds'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['claybath_wet_shell'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['inlet_kernel_silo'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['outlet_kernel_silo'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['press'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['eficiency'] }}</td>
                            <td class="px-3 py-2 font-semibold text-blue-700">{{ $row['perf_total'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="16" class="px-3 py-4 text-center text-gray-500">Belum ada data performance untuk tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <x-ui.card title="Data Performance Per Kode (Detail)" class="mt-6">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[3600px] text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Tim</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Mulai Proses</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Akhir Proses</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Total Hours</th>
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Nama Sampel Boy</th>
                        @foreach (($detailCodeHeaders ?? []) as $detailCode)
                            <th class="px-3 py-2 text-left font-semibold text-gray-700">{{ $detailCode['label'] }}</th>
                        @endforeach
                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Perf Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($rows as $row)
                        <tr>
                            <td class="px-3 py-2 text-gray-700">{{ $row['tanggal'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['team_name'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['jam_mulai'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['jam_akhir'] }}</td>
                            <td class="px-3 py-2 font-medium text-gray-800">{{ $row['total_hours'] }}</td>
                            <td class="px-3 py-2 text-gray-700">{{ $row['nama_sampel_boy'] }}</td>
                            @foreach (($detailCodeHeaders ?? []) as $detailCode)
                                <td class="px-3 py-2 text-gray-700">{{ $row['detail_values'][$detailCode['code']] ?? '0/0' }}</td>
                            @endforeach
                            <td class="px-3 py-2 font-semibold text-blue-700">{{ $row['detail_perf_total'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100" class="px-3 py-4 text-center text-gray-500">Belum ada data performance untuk tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
</x-layouts.app>
