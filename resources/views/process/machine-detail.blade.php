<x-layouts.app title="Detail Mesin Proses">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Mesin Proses</h1>
            <p class="mt-2 text-sm text-gray-600">Rincian mesin yang digunakan pada tanggal {{ $record->process_date?->format('d/m/Y') }}.</p>
        </div>
        <a href="{{ route('process.index') }}"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Kembali
        </a>
    </div>

    <x-ui.card title="Data Mesin">
        @php
            $hasAnyMachine = collect($groupedMachinesByTeam)
                ->contains(fn ($teamData) => $teamData['groups']->isNotEmpty() || ($teamData['spare_rows'] ?? collect())->isNotEmpty());
        @endphp

        @if (!$hasAnyMachine)
            <p class="text-sm text-gray-500">Belum ada data mesin untuk tanggal ini.</p>
        @else
            <div class="space-y-6">
                @foreach ($groupedMachinesByTeam as $teamName => $teamData)
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <h2 class="mb-3 text-base font-semibold text-gray-900">{{ $teamName }}</h2>

                        @if ($teamData['groups']->isEmpty() && $teamData['orphans']->isEmpty())
                            <p class="text-sm text-gray-500">Belum ada data mesin pada {{ $teamName }}.</p>
                        @else
                            <div class="space-y-5">
                                @foreach ($teamData['groups'] as $group => $machines)
                                    <div>
                                        <h3 class="mb-2 text-sm font-semibold text-gray-800">{{ $group }}</h3>
                                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Mesin</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Awal Produksi</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Akhir Produksi</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Total Jam Produksi</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Interval Sampling</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Total Sampel Seharusnya</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Spare</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 bg-white">
                                                    @foreach ($machines as $item)
                                                        @php
                                                            $machine = $item['main'];
                                                            $spares = $item['spares'];
                                                            $totalHours = number_format(((int) ($item['total_minutes'] ?? 0)) / 60, 2, ',', '.');
                                                            $intervalMinutes = (int) ($item['interval_minutes'] ?? 0);
                                                            $expectedSamples = (int) ($item['expected_samples'] ?? 0);
                                                        @endphp
                                                        <tr>
                                                            <td class="px-3 py-2 text-gray-700">{{ $machine->machine_name }}</td>
                                                            <td class="px-3 py-2 text-gray-700">{{ substr((string) $machine->production_start_time, 0, 5) }}</td>
                                                            <td class="px-3 py-2 text-gray-700">{{ substr((string) $machine->production_end_time, 0, 5) }}</td>
                                                            <td class="px-3 py-2 font-semibold text-gray-800">{{ $totalHours }} jam</td>
                                                            <td class="px-3 py-2 text-gray-700">{{ $intervalMinutes > 0 ? (($intervalMinutes / 60) . ' jam sekali') : '-' }}</td>
                                                            <td class="px-3 py-2 font-semibold text-gray-800">{{ $expectedSamples }} sampel</td>
                                                            <td class="px-3 py-2 font-semibold text-gray-700">Tidak</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="mt-2 text-xs font-semibold text-sky-700">
                                            Total sampel seharusnya {{ $teamName }}: {{ (int) ($teamData['expected_samples_total'] ?? 0) }} sampel
                                        </p>
                                    </div>
                                @endforeach

                                @if (($teamData['spare_rows'] ?? collect())->isNotEmpty())
                                    <div>
                                        <h3 class="mb-2 text-sm font-semibold text-gray-800">Data Spare Input</h3>
                                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Mesin</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Awal Spare</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Akhir Spare</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Total Jam Spare</th>
                                                        <th class="px-3 py-2 text-left font-semibold text-gray-700">Spare</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 bg-white">
                                                    @foreach (($teamData['spare_rows'] ?? collect()) as $orphan)
                                                        @php
                                                            $spare = $orphan['machine'];
                                                            $spareHours = number_format(((int) ($orphan['total_minutes'] ?? 0)) / 60, 2, ',', '.');
                                                        @endphp
                                                        <tr>
                                                            <td class="px-3 py-2 text-gray-700">{{ $spare->machine_name }}</td>
                                                            <td class="px-3 py-2 text-gray-700">{{ substr((string) $spare->production_start_time, 0, 5) }}</td>
                                                            <td class="px-3 py-2 text-gray-700">{{ substr((string) $spare->production_end_time, 0, 5) }}</td>
                                                            <td class="px-3 py-2 font-semibold text-gray-800">{{ $spareHours }} jam</td>
                                                            <td class="px-3 py-2 font-semibold text-amber-700">Iya</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </x-ui.card>
</x-layouts.app>
