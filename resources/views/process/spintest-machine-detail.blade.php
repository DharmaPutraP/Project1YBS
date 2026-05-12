<x-layouts.app title="Detail Mesin Proses Spintest">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Mesin Proses Spintest</h1>
            <p class="mt-2 text-sm text-gray-600">
                Rincian mesin pada tanggal {{ $record->process_date?->format('d/m/Y') }}
                @if ($visibleTeam)
                    untuk {{ $visibleTeam }}
                @endif
            </p>
        </div>
        <a href="{{ route('process.spintest.index') }}"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Kembali
        </a>
    </div>

    <x-ui.card title="Data Mesin">
        @if (empty($teamDetail['machine_groups'] ?? []))
            <p class="text-sm text-gray-500">Belum ada data mesin untuk tanggal ini.</p>
        @else
            <div class="space-y-6">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <div class="rounded-lg border border-gray-200 p-3">
                        <p class="text-xs text-gray-500">Tanggal</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $teamDetail['process_date'] ?: '-' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-3">
                        <p class="text-xs text-gray-500">Jam Mulai</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $teamDetail['process_start'] ?: '-' }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-3">
                        <p class="text-xs text-gray-500">Jam Akhir</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $teamDetail['process_end'] ?: '-' }}</p>
                    </div>
                    <div class="rounded-lg border border-sky-200 bg-sky-50 p-3">
                        <p class="text-xs text-gray-500">Total Sampel</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ (int) ($teamDetail['total_samples'] ?? 0) }} sampel</p>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <h2 class="mb-3 text-base font-semibold text-gray-900">{{ $teamDetail['team_name'] ?? '-' }}</h2>

                    <div class="space-y-5">
                        @foreach ($teamDetail['machine_groups'] as $group)
                            <div class="rounded-lg border border-gray-200 bg-white p-4">
                                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">{{ $group['machine_name'] }}</h3>
                                        <p class="mt-1 text-xs text-gray-500">Total Sampel: {{ (int) ($group['expected_samples'] ?? 0) }} | Total Durasi: {{ intdiv((int) ($group['total_minutes'] ?? 0), 60) }} jam {{ (int) ($group['total_minutes'] ?? 0) % 60 }} menit</p>
                                    </div>
                                </div>

                                <div class="overflow-x-auto rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left font-semibold text-gray-700">Status</th>
                                                <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Awal</th>
                                                <th class="px-3 py-2 text-left font-semibold text-gray-700">Jam Akhir</th>
                                                <th class="px-3 py-2 text-left font-semibold text-gray-700">Durasi</th>
                                                <th class="px-3 py-2 text-left font-semibold text-gray-700">Sampel</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 bg-white">
                                            @foreach ($group['rows'] as $row)
                                                @php
                                                    $durationMinutes = (int) ($row['duration_minutes'] ?? 0);
                                                    $durationHours = intdiv($durationMinutes, 60);
                                                    $durationMinutesPart = $durationMinutes % 60;
                                                @endphp
                                                <tr>
                                                    <td class="px-3 py-2 font-semibold {{ ($row['status'] ?? '') === 'Spare' ? 'text-amber-700' : 'text-sky-700' }}">{{ $row['status'] ?? '-' }}</td>
                                                    <td class="px-3 py-2 text-gray-700">{{ substr((string) ($row['machine']->production_start_time ?? ''), 0, 5) }}</td>
                                                    <td class="px-3 py-2 text-gray-700">{{ substr((string) ($row['machine']->production_end_time ?? ''), 0, 5) }}</td>
                                                    <td class="px-3 py-2 font-semibold text-gray-800">{{ $durationHours }} jam {{ $durationMinutesPart }} menit</td>
                                                    <td class="px-3 py-2 font-semibold text-gray-800">{{ (int) ($row['expected_samples'] ?? 0) }} sampel</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if (!empty($teamDetail['other_conditions']))
                    <div>
                        <h2 class="mb-2 text-sm font-semibold text-gray-800">Kondisi Lainnya</h2>
                        <div class="overflow-x-auto rounded-lg border border-violet-200">
                            <table class="min-w-full divide-y divide-violet-200 text-sm">
                                <thead class="bg-violet-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-semibold text-violet-900">Alasan</th>
                                        <th class="px-3 py-2 text-left font-semibold text-violet-900">Jam Mulai</th>
                                        <th class="px-3 py-2 text-left font-semibold text-violet-900">Jam Selesai</th>
                                        <th class="px-3 py-2 text-left font-semibold text-violet-900">Durasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-violet-100 bg-white">
                                    @foreach ($teamDetail['other_conditions'] as $condition)
                                        @php
                                            $conditionMinutes = (int) ($condition['duration_minutes'] ?? 0);
                                            $conditionHoursPart = intdiv($conditionMinutes, 60);
                                            $conditionMinutesPart = $conditionMinutes % 60;
                                        @endphp
                                        <tr>
                                            <td class="px-3 py-2 text-gray-700">{{ $condition['reason'] ?: '-' }}</td>
                                            <td class="px-3 py-2 text-gray-700">{{ $condition['start_time'] ?: '-' }}</td>
                                            <td class="px-3 py-2 text-gray-700">{{ $condition['end_time'] ?: '-' }}</td>
                                            <td class="px-3 py-2 font-semibold text-gray-800">{{ $conditionHoursPart }} jam {{ $conditionMinutesPart }} menit</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </x-ui.card>
</x-layouts.app>
