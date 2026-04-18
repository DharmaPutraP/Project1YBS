@php
    $moduleLabelMap = [
        'kernel' => 'Kernel Losses',
        'dirt_moist' => 'Dirt & Moist',
        'qwt' => 'QWT Fibre Press',
        'ripple_mill' => 'Ripple Mill',
        'destoner' => 'Destoner',
    ];
    $moduleLabel = $moduleLabelMap[$successProof['module'] ?? ''] ?? 'Kernel';
    $metrics = $successProof['metrics'] ?? [];
    $inputs = $successProof['inputs'] ?? [];
    $entries = $successProof['entries'] ?? [];

    if (empty($entries)) {
        $entries = [
            [
                'tanggal_input' => $successProof['tanggal_input'] ?? '-',
                'jam_proses' => $successProof['jam_proses'] ?? '-',
                'kode_label' => $successProof['kode_label'] ?? ($successProof['kode'] ?? '-'),
                'jenis' => $successProof['jenis'] ?? '-',
                'operator' => $successProof['operator'] ?? '-',
                'sampel_boy' => $successProof['sampel_boy'] ?? '-',
                'input_by' => $successProof['input_by'] ?? '-',
            ]
        ];
    }

    $formatValue = function ($value, $decimals = 2, $unit = '') {
        if ($value === null) {
            return '-';
        }
        $formatted = number_format((float) $value, $decimals);
        return $unit ? $formatted . $unit : $formatted;
    };

    $formatLimit = function ($operator, $value, $decimals = 2) {
        if (!$operator || $value === null) {
            return '-';
        }
        $symbol = match ($operator) {
            'lt' => '<',
            'ge' => '>=',
            'gt' => '>',
            default => '<=',
        };
        return $symbol . ' ' . number_format((float) $value, $decimals);
    };
    $detailInputsHtml = '';
    if (!empty($inputs)) {
        $detailInputsHtml = collect($inputs)->map(function ($input) use ($formatValue) {
            $label = e($input['label'] ?? '-');
            $value = e($formatValue($input['value'] ?? null, $input['decimals'] ?? 2, $input['unit'] ?? ''));
            return $label . ': ' . $value;
        })->implode('<br>');
    }
@endphp

<section class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
        <div>
            <p class="text-sm font-semibold text-emerald-900">
                {{ $successProof['message'] ?? 'Data berhasil disimpan.' }}
            </p>
            <p class="mt-1 text-sm text-emerald-800">Waktu bukti dibuat: {{ $successProof['generated_at'] ?? '-' }}</p>
            <p class="mt-1 text-xs text-emerald-700">Silakan unduh gambar untuk bukti input data.</p>
        </div>
        <div class="text-sm text-emerald-800">
            <div>User login: {{ auth()->user()->name ?? '-' }}</div>
            <div>Office login: {{ auth()->user()->office ?? '-' }}</div>
        </div>
    </div>
</section>

<section class="mb-6 rounded-xl border border-blue-200 bg-blue-50/60 p-4">
    <div class="mb-3 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-slate-900">Ringkasan Input {{ $moduleLabel }}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-blue-200 overflow-hidden rounded-lg bg-white">
            <thead class="bg-blue-100 text-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tanggal Input</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jam Proses</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jenis</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Operator</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Sampel Boy</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Input By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-100 text-sm text-slate-700">
                @foreach($entries as $entry)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $entry['tanggal_input'] ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $entry['jam_proses'] ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap font-semibold text-blue-900">
                            {{ $entry['kode_label'] ?? ($entry['kode'] ?? '-') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $entry['jenis'] ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $entry['operator'] ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $entry['sampel_boy'] ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $entry['input_by'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@php
    $entriesMetrics = $successProof['entries_metrics'] ?? [];
@endphp

@if(!empty($entriesMetrics))
    <section class="mb-6 rounded-xl border border-purple-200 bg-purple-50/60 p-4">
        <div class="mb-3 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Hasil Losses</h3>
        </div>

        @foreach($entriesMetrics as $entryMetric)
            @php
                $kodeLabel = $entryMetric['kode_label'] ?? '-';
                $entryMetricsRows = $entryMetric['metrics'] ?? [];
                if (empty($entryMetricsRows) && !empty($entryMetric['metric'])) {
                    $entryMetricsRows = [$entryMetric['metric']];
                }
                $inputs = $entryMetric['inputs'] ?? [];
                $entryDetailInputsHtml = '';
                if (!empty($inputs)) {
                    $entryDetailInputsHtml = collect($inputs)->map(function ($input) use ($formatValue) {
                        $label = e($input['label'] ?? '-');
                        $value = e($formatValue($input['value'] ?? null, $input['decimals'] ?? 2, $input['unit'] ?? ''));
                        return $label . ': ' . $value;
                    })->implode('<br>');
                }
            @endphp
            <div class="mb-4 overflow-x-auto">
                <div class="mb-2 rounded bg-purple-100 px-3 py-2 text-sm font-semibold text-purple-900">
                    {{ $kodeLabel }}
                </div>
                <table class="min-w-full divide-y divide-purple-200 overflow-hidden rounded-lg bg-white">
                    <thead class="bg-purple-100 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Parameter</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Detail Input</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nilai</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Limit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-100 text-sm text-slate-700">
                        @foreach($entryMetricsRows as $metric)
                            @php
                                $decimals = $metric['decimals'] ?? 2;
                                $limitDecimals = $metric['limit_decimals'] ?? $decimals;
                                $limitOperator = $metric['limit_operator'] ?? null;
                                $limitValue = $metric['limit_value'] ?? null;
                                $isGood = null;
                                if ($limitOperator && $limitValue !== null) {
                                    $metricValue = (float) ($metric['value'] ?? 0);
                                    $limitNumber = (float) $limitValue;
                                    $isGood = match ($limitOperator) {
                                        'lt' => $metricValue < $limitNumber,
                                        'ge' => $metricValue >= $limitNumber,
                                        'gt' => $metricValue > $limitNumber,
                                        default => $metricValue <= $limitNumber,
                                    };
                                }
                                $badgeClass = $isGood === null
                                    ? 'bg-gray-100 text-gray-700'
                                    : ($isGood ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $metric['label'] ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($entryDetailInputsHtml)
                                        <div class="text-xs text-slate-700">{!! $entryDetailInputsHtml !!}</div>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badgeClass }}">
                                        {{ $formatValue($metric['value'] ?? null, $decimals, $metric['unit'] ?? '') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-purple-800">
                                    {{ $formatLimit($limitOperator, $limitValue, $limitDecimals) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </section>
@elseif(!empty($metrics))
    <section class="mb-6 rounded-xl border border-purple-200 bg-purple-50/60 p-4">
        <div class="mb-3 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Hasil Losses</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-purple-200 overflow-hidden rounded-lg bg-white">
                <thead class="bg-purple-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Parameter</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Detail Input</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nilai</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Limit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-100 text-sm text-slate-700">
                    @foreach($metrics as $metric)
                        @php
                            $decimals = $metric['decimals'] ?? 2;
                            $limitDecimals = $metric['limit_decimals'] ?? $decimals;
                            $limitOperator = $metric['limit_operator'] ?? null;
                            $limitValue = $metric['limit_value'] ?? null;
                            $isGood = null;
                            if ($limitOperator && $limitValue !== null) {
                                $metricValue = (float) ($metric['value'] ?? 0);
                                $limitNumber = (float) $limitValue;
                                $isGood = match ($limitOperator) {
                                    'lt' => $metricValue < $limitNumber,
                                    'ge' => $metricValue >= $limitNumber,
                                    'gt' => $metricValue > $limitNumber,
                                    default => $metricValue <= $limitNumber,
                                };
                            }
                            $badgeClass = $isGood === null
                                ? 'bg-gray-100 text-gray-700'
                                : ($isGood ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                        @endphp
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $metric['label'] ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($detailInputsHtml)
                                    <div class="text-xs text-slate-700">{!! $detailInputsHtml !!}</div>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badgeClass }}">
                                    {{ $formatValue($metric['value'] ?? null, $decimals, $metric['unit'] ?? '') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-purple-800">
                                {{ $formatLimit($limitOperator, $limitValue, $limitDecimals) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endif