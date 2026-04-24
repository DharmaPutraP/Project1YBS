<x-layouts.app title="Data Oil Loss Foss">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Oil Loss Foss</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Data Oil Loss Foss</h1>
                <p class="mt-2 text-sm text-slate-500">Daftar input Oil Loss Foss yang sudah tersimpan.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('oil-loss-foss.input') }}" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Input Oil Loss Foss</a>
                <a href="{{ route('oil-loss-foss.data.export', request()->query()) }}" class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">Export Excel</a>
            </div>
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
                <div class="xl:col-span-2">
                    <label for="machine_name" class="mb-1 block text-xs font-semibold text-slate-600">Kode</label>
                    <select id="machine_name" name="machine_name" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="all" @selected($machineName === 'all')>Semua Kode</option>
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
                            <th colspan="11" class="px-4 py-3 text-left font-bold tracking-wide text-slate-800">DATA OIL LOSS FOSS</th>
                            <th colspan="2" class="px-4 py-3 text-left font-bold tracking-wide text-slate-800">MASS BALANCE</th>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold">Kode</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Sample</th>
                            <th class="px-4 py-3 text-left font-semibold">MOIST (%)</th>
                            <th class="px-4 py-3 text-left font-semibold">DM/WM (%)</th>
                            <th class="px-4 py-3 text-left font-semibold">OLWB (%)</th>
                            <th class="px-4 py-3 text-left font-semibold">LIMIT (%)</th>
                            <th class="px-4 py-3 text-left font-semibold">OLDB (%)</th>
                            <th class="px-4 py-3 text-left font-semibold">LIMIT (%)2</th>
                            <th class="px-4 py-3 text-left font-semibold">OIL LOSSES/TON TBS (%)</th>
                            <th class="px-4 py-3 text-left font-semibold">LIMIT (%)3</th>
                            <th class="px-4 py-3 text-left font-semibold">%</th>
                            <th class="px-4 py-3 text-left font-semibold">%2</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($rows as $row)
                            @php
                                $code = (string) $row->machine_name;
                                $definition = $definitionMap[$code] ?? null;

                                $moist = $row->moist === null ? null : (float) $row->moist;
                                $dmwm = $moist === null ? null : (100 - $moist);
                                $olwb = $row->olwb === null ? null : (float) $row->olwb;
                                $oldb = ($olwb !== null && $dmwm !== null && $dmwm != 0.0)
                                    ? ($olwb / $dmwm)
                                    : null;

                                $massPercent = $definition['mass_balance_percent'] ?? null;
                                $massPercent2 = $definition['mass_balance_percent_2'] ?? null;
                                $oilLossesTon = ($olwb !== null && $massPercent !== null)
                                    ? ($olwb * (float) $massPercent)
                                    : null;

                                $operatorMap = [
                                    '<=' => '≤',
                                    '>=' => '≥',
                                    '<' => '<',
                                    '>' => '>',
                                    '=' => '=',
                                ];

                                $limitOlwb = null;
                                if (($definition['limit_olwb'] ?? null) !== null) {
                                    $symbol = $operatorMap[$definition['limit_olwb_operator'] ?? '<='] ?? ($definition['limit_olwb_operator'] ?? '<=');
                                    $limitOlwb = $symbol . ' ' . number_format((float) $definition['limit_olwb'], 2);
                                }

                                $limitOldb = null;
                                if (($definition['limit_oldb'] ?? null) !== null) {
                                    $symbol = $operatorMap[$definition['limit_oldb_operator'] ?? '<='] ?? ($definition['limit_oldb_operator'] ?? '<=');
                                    $limitOldb = $symbol . ' ' . number_format((float) $definition['limit_oldb'], 2);
                                }

                                $limitOilLoss = null;
                                if (($definition['limit_oil_losses'] ?? null) !== null) {
                                    $symbol = $operatorMap[$definition['limit_oil_losses_operator'] ?? '<='] ?? ($definition['limit_oil_losses_operator'] ?? '<=');
                                    $limitOilLoss = $symbol . ' ' . number_format((float) $definition['limit_oil_losses'], 2);
                                }

                                $matchesLimit = static function (?float $value, ?float $limit, ?string $operator): bool {
                                    if ($value === null) {
                                        return false;
                                    }

                                    // Jika limit tidak ada, dianggap aman (hijau).
                                    if ($limit === null || $operator === null) {
                                        return true;
                                    }

                                    return match ($operator) {
                                        '<=' => $value <= $limit,
                                        '>=' => $value >= $limit,
                                        '<' => $value < $limit,
                                        '>' => $value > $limit,
                                        '=' => $value == $limit,
                                        default => false,
                                    };
                                };

                                $olwbInLimit = $matchesLimit(
                                    $olwb,
                                    isset($definition['limit_olwb']) ? (float) $definition['limit_olwb'] : null,
                                    $definition['limit_olwb_operator'] ?? null
                                );

                                $oldbInLimit = $matchesLimit(
                                    $oldb,
                                    isset($definition['limit_oldb']) ? (float) $definition['limit_oldb'] : null,
                                    $definition['limit_oldb_operator'] ?? null
                                );

                                $oilLossInLimit = $matchesLimit(
                                    $oilLossesTon,
                                    isset($definition['limit_oil_losses']) ? (float) $definition['limit_oil_losses'] : null,
                                    $definition['limit_oil_losses_operator'] ?? null
                                );
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-slate-700">{{ $row->tanggal?->format('d-m-Y') }}</td>
                                <td class="px-4 py-3 text-slate-700 font-semibold">{{ $code }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $definition['sample_name'] ?? $code }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $moist === null ? '-' : number_format($moist, 2) }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $dmwm === null ? '-' : number_format($dmwm, 2) }}</td>
                                <td class="px-4 py-3 font-semibold {{ $olwb === null ? 'text-slate-700' : ($olwbInLimit ? 'text-emerald-700' : 'text-rose-700') }}">{{ $olwb === null ? '-' : number_format($olwb, 2) }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $limitOlwb ?? '-' }}</td>
                                <td class="px-4 py-3 font-semibold {{ $oldb === null ? 'text-slate-700' : ($oldbInLimit ? 'text-emerald-700' : 'text-rose-700') }}">{{ $oldb === null ? '-' : number_format($oldb, 4) }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $limitOldb ?? '-' }}</td>
                                <td class="px-4 py-3 font-semibold {{ $oilLossesTon === null ? 'text-slate-700' : ($oilLossInLimit ? 'text-emerald-700' : 'text-rose-700') }}">{{ $oilLossesTon === null ? '-' : number_format($oilLossesTon, 4) }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $limitOilLoss ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $massPercent === null ? '-' : number_format((float) $massPercent, 2) }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $massPercent2 === null ? '-' : number_format((float) $massPercent2, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="px-4 py-8 text-center text-slate-500">Belum ada data.</td>
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
