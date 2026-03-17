<x-layouts.app title="Data Ripple Mill">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-indigo-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium">Total Records</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">{{ $statistics['total_records'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-green-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium">Records Hari Ini</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">{{ $statistics['records_today'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-blue-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium">Total Data</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">{{ $statistics['calculations_count'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-purple-500">
            <p class="text-xs md:text-sm text-gray-500 font-medium">Data Ditampilkan</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">{{ $statistics['calculations_count'] }}</p>
        </div>
    </div>

    <x-ui.card title="Data Ripple Mill">
        <div class="mb-4 md:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            @can('create kernel losses')
                <a href="{{ route('kernel.ripple-mill.create') }}" class="inline-flex items-center justify-center px-3 md:px-4 py-2 md:py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm w-full sm:w-auto">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Input Ripple Mill
                </a>
            @endcan
        </div>

        <div class="mb-4 md:mb-6 bg-gray-50 rounded-lg p-3 md:p-4 border border-gray-200">
            <form method="GET" action="{{ route('kernel.ripple-mill.index') }}" class="flex flex-col sm:flex-row gap-3 md:gap-4 items-end">
                <div class="flex-1 w-full">
                    <label for="kode" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Filter Kode</label>
                    <select name="kode" id="kode" class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Semua Kode --</option>
                        @foreach($kodeOptions as $kodeValue => $kodeLabel)
                            <option value="{{ $kodeValue }}" {{ request('kode') == $kodeValue ? 'selected' : '' }}>{{ $kodeValue }} - {{ $kodeLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 w-full">
                    <label for="start_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div class="flex-1 w-full">
                    <label for="end_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <button type="submit" class="inline-flex items-center justify-center px-4 md:px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm whitespace-nowrap">Filter</button>
            </form>
        </div>

        @if ($rippleMillRows->isEmpty())
            <div class="text-center py-10">
                <p class="text-base text-gray-500">Belum ada data Ripple Mill</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal Input</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama Sampel</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Operator</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Sampel Boy</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Berat Sample</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Berat Nut Utuh</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Berat Nut Pecah</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-purple-700 uppercase tracking-wider bg-purple-100">Sample Nut Utuh</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-purple-700 uppercase tracking-wider bg-purple-100">Sample Nut Pecah</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-red-700 uppercase tracking-wider bg-red-50">Efficiency</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-orange-700 uppercase tracking-wider bg-orange-50">Limit</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($rippleMillRows as $row)
                            @php
                                $master = $masterData[$row->kode] ?? null;
                                $efficiencyValue = (float) ($row->efficiency ?? 0);
                                $limitOperator = $row->limit_operator ?? 'gt';
                                $limitValue = $row->limit_value !== null ? (float) $row->limit_value : null;
                                $isGood = $limitValue !== null
                                    ? ($limitOperator === 'le' ? $efficiencyValue <= $limitValue : $efficiencyValue > $limitValue)
                                    : null;
                            @endphp
                            <tr class="hover:bg-blue-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $row->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-blue-900">{{ $row->kode }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $master->nama_sample ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $row->jenis ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $row->operator ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $row->sampel_boy ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900">{{ number_format((float) ($row->berat_sampel ?? 0), 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900">{{ number_format((float) ($row->berat_nut_utuh ?? 0), 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900">{{ number_format((float) ($row->berat_nut_pecah ?? 0), 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700">{{ number_format((float) ($row->sample_nut_utuh ?? 0), 2) }}%</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700">{{ number_format((float) ($row->sample_nut_pecah ?? 0), 2) }}%</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $isGood === null ? 'bg-gray-100 text-gray-700' : ($isGood ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">{{ number_format($efficiencyValue, 2) }}%</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center text-xs font-semibold bg-orange-50 text-orange-800">
                                    @if($limitValue !== null)
                                        {{ $limitOperator === 'le' ? '≤' : '>' }} {{ number_format($limitValue, 2) }}%
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $rippleMillRows->links() }}</div>
        @endif
    </x-ui.card>

</x-layouts.app>