<x-layouts.app title="Data Oil Losses - Oil Losses">

    @php
        $successProof = session('success_proof');
        $defaultTab = $successProof['active_tab'] ?? 'records';
        $mode1Entries = collect($successProof['mode1_entries'] ?? [])->values();
        if ($mode1Entries->isEmpty() && !empty($successProof['mode1'])) {
            $mode1Entries = collect([$successProof['mode1']]);
        }
        $mode2Entries = collect($successProof['mode2_entries'] ?? [])->values();
        if ($mode2Entries->isEmpty() && !empty($successProof['mode2'])) {
            $mode2Entries = collect([$successProof['mode2']]);
        }
    @endphp

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #success-proof-print,
            #success-proof-print * {
                visibility: visible;
            }

            #success-proof-print {
                position: absolute;
                inset: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
        }

        .success-proof-export-root {
            position: fixed;
            left: -10000px;
            top: 0;
            width: 1480px;
            padding: 32px;
            background: #f8fafc;
            z-index: -1;
        }

        .success-proof-export-root table {
            width: 100%;
            border-collapse: collapse;
        }

        .success-proof-export-root th,
        .success-proof-export-root td {
            white-space: normal;
            word-break: break-word;
            vertical-align: top;
        }
    </style>

    {{-- ── Statistics Cards ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Total Records</p>
                    <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">{{ $statistics['total_records'] }}</p>
                </div>
                <div class="p-2 md:p-3 bg-indigo-100 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-indigo-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Records Today</p>
                    <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">{{ $statistics['records_today'] }}</p>
                </div>
                <div class="p-2 md:p-3 bg-green-100 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-green-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Data Non-Angka</p>
                    <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">{{ $statistics['records_count'] }}</p>
                </div>
                <div class="p-2 md:p-3 bg-blue-100 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs md:text-sm text-gray-500 font-medium truncate">Data Perhitungan</p>
                    <p class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">{{ $statistics['calculations_count'] }}
                    </p>
                </div>
                <div class="p-2 md:p-3 bg-purple-100 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-purple-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main Card with Tabs ───────────────────────────────────────── --}}
    <x-ui.card title="Data Oil Losses">
        {{-- Action Buttons --}}
        <div class="mb-4 md:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                @can('create oil losses')
                    <a href="{{ route('oil.create') }}"
                        class="inline-flex items-center justify-center px-3 md:px-4 py-2 md:py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Input Data Oil Losses
                    </a>
                @endcan
            </div>

        </div>

        {{-- Date Range Filter --}}
        <div class="mb-4 md:mb-6 bg-gray-50 rounded-lg p-3 md:p-4 border border-gray-200">
            <form method="GET" action="{{ route('oil.index') }}"
                class="flex flex-col sm:flex-row gap-3 md:gap-4 items-end">
                <div class="flex-1 w-full">
                    <label for="kode" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                        Filter Kode <span class="text-xs text-gray-500">(Opsional)</span>
                    </label>
                    <select name="kode" id="kode"
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Semua Kode --</option>
                        @foreach($kodeOptions as $kodeValue => $kodeLabel)
                            <option value="{{ $kodeValue }}" {{ request('kode') == $kodeValue ? 'selected' : '' }}>
                                {{ $kodeLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 w-full">
                    <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                        Office/PT
                    </label>
                    @if(auth()->user()->office)
                        <div
                            class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                            {{ auth()->user()->office }}
                        </div>
                    @else
                        <select name="office" id="office"
                            class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="all" {{ $officeFilter == 'all' ? 'selected' : '' }}>-- Semua Office --</option>
                            <option value="YBS" {{ $officeFilter == 'YBS' ? 'selected' : '' }}>YBS</option>
                            <option value="SUN" {{ $officeFilter == 'SUN' ? 'selected' : '' }}>SUN</option>
                            <option value="SJN" {{ $officeFilter == 'SJN' ? 'selected' : '' }}>SJN</option>
                        </select>
                    @endif
                </div>

                <div class="flex-1 w-full">
                    <label for="start_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai <span class="text-xs text-gray-500">(Default: Hari ini)</span>
                    </label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="flex-1 w-full">
                    <label for="end_date" class="block text-xs md:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Akhir <span class="text-xs text-gray-500">(Default: Hari ini)</span>
                    </label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-3 md:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 md:px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm whitespace-nowrap">
                        <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>

                    @if(request('start_date') && request('start_date') != now()->format('Y-m-d') || request('end_date') && request('end_date') != now()->format('Y-m-d') || request('kode') || request('office'))
                        <a href="{{ route('oil.index') }}"
                            class="inline-flex items-center justify-center px-4 md:px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium text-sm whitespace-nowrap">
                            <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="hidden sm:inline">Reset ke Hari Ini</span>
                            <span class="sm:hidden">Reset</span>
                        </a>
                    @endif
                </div>
            </form>

            <div class="mt-3 text-xs md:text-sm text-gray-600 flex flex-wrap items-center gap-2">
                <span class="font-medium">Menampilkan data:</span>
                @if($startDate == $endDate)
                    <span
                        class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 whitespace-nowrap">
                        📅 {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        📅 {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </span>
                @endif
                @if(request('kode'))
                    <span
                        class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 whitespace-nowrap">
                        🔖 Kode: {{ request('kode') }}
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-2 md:px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 whitespace-nowrap">
                        🔖 Semua Kode
                    </span>
                @endif
            </div>
        </div>

        @can('view oil losses')
            {{-- Tabs Navigation --}}
            <div class="border-b border-gray-200 mb-4 md:mb-6 overflow-x-auto">
                <nav class="-mb-px flex space-x-4 md:space-x-8">
                    <button onclick="switchTab('records')" id="tab-records"
                        class="tab-button border-b-2 border-blue-500 text-blue-600 whitespace-nowrap py-3 md:py-4 px-1 font-medium text-xs md:text-sm flex-shrink-0">
                        <span class="hidden sm:inline">📝 Data Jenis & Sampel</span>
                        <span class="sm:hidden">📝 Jenis</span>
                        <span
                            class="ml-1 md:ml-2 bg-blue-100 text-blue-600 py-0.5 px-1.5 md:px-2.5 rounded-full text-xs">{{ $statistics['records_count'] }}</span>
                    </button>
                    <button onclick="switchTab('calculations')" id="tab-calculations"
                        class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 md:py-4 px-1 font-medium text-xs md:text-sm flex-shrink-0">
                        <span class="hidden sm:inline">🧪 Data Perhitungan Lab</span>
                        <span class="sm:hidden">🧪 Perhitungan</span>
                        <span
                            class="ml-1 md:ml-2 bg-gray-100 text-gray-600 py-0.5 px-1.5 md:px-2.5 rounded-full text-xs">{{ $statistics['calculations_count'] }}</span>
                    </button>
                </nav>
            </div>

            {{-- Tab 1: Data Non-Angka (Lab Records) --}}
            <div id="content-records" class="tab-content">
                @if ($oilRecords->isEmpty())
                    <div class="text-center py-8 md:py-12">
                        <svg class="mx-auto h-10 w-10 md:h-12 md:w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-4 text-base md:text-lg text-gray-500">Belum ada data jenis & sampel</p>
                        @can('create oil losses')
                            <a href="{{ route('oil.create') }}"
                                class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                Input Data Pertama
                            </a>
                        @endcan
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Tanggal Input
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Tgl & Jam Akhir Input
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Tanggal Sampel
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Kode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Jenis
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Operator
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Sampel Boy
                                    </th>
                                    <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                                                                                                                                                Parameter Lain
                                                                                                                                                            </th> -->
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Input By
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($oilRecords as $record)
                                    <tr class="hover:bg-blue-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $record->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $record->updated_at ? $record->updated_at->format('d/m/Y H:i:s') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $record->tanggal_sampel?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-900">
                                            {{ $record->kode ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium {{ $record->jenis == 'TBS' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ $record->jenis ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $record->operator ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $record->sampel_boy ?? '-' }}
                                        </td>
                                        <!-- <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                                                                                                                                                                                                                        {{ $record->parameter_lain ?? '-' }}
                                                                                                                                                                                                                                    </td> -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $record->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                @can('edit oil losses')
                                                    <a href="{{ route('oil.records.edit', $record->id) }}"
                                                        class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                @endcan

                                                @can('delete oil losses')
                                                    <form action="{{ route('oil.records.destroy', $record->id) }}" method="POST"
                                                        class="inline delete-form" data-item-name="Data {{ $record->kode ?? 'ini' }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $oilRecords->appends(array_merge(request()->except('page'), ['tab' => 'records']))->links() }}
                    </div>
                @endif
            </div>

            {{-- Tab 2: Data Perhitungan Lab (Lab Calculations) --}}
            <div id="content-calculations" class="tab-content hidden">
                @if ($oilLosses->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-4 text-lg text-gray-500">Belum ada data perhitungan lab</p>
                        @can('create oil losses')
                            <a href="{{ route('oil.create') }}"
                                class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                Input Data Pertama
                            </a>
                        @endcan
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-purple-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Tanggal Input
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Tgl & Jam Akhir Input
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Tanggal Sampel
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Kode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Moist (%)
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        DMWM (%)
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        OLWB
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        OLDB
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Oil Losses (%)
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Input By
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($oilLosses as $oilLoss)
                                    <tr class="hover:bg-purple-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $oilLoss->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $oilLoss->updated_at ? $oilLoss->updated_at->format('d/m/Y H:i:s') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $oilLoss->tanggal_sampel?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-purple-900">
                                            {{ $oilLoss->kode }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($oilLoss->moist ?? 0, 4) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($oilLoss->dmwm ?? 0, 4) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <!-- {{ number_format($oilLoss->olwb ?? 0, 4) }} -->
                                            @php
                                                $olwb_calc = $oilLoss->olwb ?? 0;
                                                $limitolwb = $oilLoss->limitOLWB ?? 0;
                                                $limitOperator = $oilLoss->limit_operator ?? 'le';
                                                $isGood = $limitolwb > 0 && match ($limitOperator) {
                                                    'lt' => $olwb_calc < $limitolwb,
                                                    'ge' => $olwb_calc >= $limitolwb,
                                                    'gt' => $olwb_calc > $limitolwb,
                                                    default => $olwb_calc <= $limitolwb,
                                                };
                                            @endphp
                                            <span @class([
                                                'font-semibold',
                                                'text-green-600' => $isGood,
                                                'text-red-600' => !$isGood && $limitolwb > 0,
                                            ])>
                                                @if($olwb_calc < 0)
                                                    ({{ number_format(abs($olwb_calc), 2) }})
                                                @else
                                                    {{ number_format($olwb_calc, 2) }}
                                                @endif
                                            </span>
                                            <span class="text-xs text-gray-500 block">Limit:
                                                @if($limitolwb > 0)
                                                    @if($limitolwb < 0)
                                                        ({{ number_format(abs($limitolwb), 2) }})
                                                    @else
                                                        {{ number_format($limitolwb, 2) }}
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <!-- {{ number_format($oilLoss->oldb ?? 0, 4) }} -->
                                            @php
                                                $oldb_calc = $oilLoss->oldb ?? 0;
                                                $limitoldb = $oilLoss->limitOLDB ?? 0;
                                                $isGood = $oldb_calc <= $limitoldb && $limitoldb > 0;
                                            @endphp
                                            <span @class([
                                                'font-semibold',
                                                'text-green-600' => $isGood,
                                                'text-red-600' => !$isGood && $limitoldb > 0,
                                            ])>
                                                @if($oldb_calc < 0)
                                                    ({{ number_format(abs($oldb_calc), 2) }})
                                                @else
                                                    {{ number_format($oldb_calc, 2) }}
                                                @endif
                                            </span>
                                            <span class="text-xs text-gray-500 block">
                                                Limit:
                                                @if($limitoldb > 0)
                                                    @if($limitoldb < 0)
                                                        ({{ number_format(abs($limitoldb), 2) }})
                                                    @else
                                                        {{ number_format($limitoldb, 2) }}
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </span>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $losses = $oilLoss->oil_losses ?? 0;
                                                $limitOL = $oilLoss->limitOL ?? 0;
                                                $isGood = $losses <= $limitOL && $limitOL > 0;
                                            @endphp
                                            <span @class([
                                                'font-semibold',
                                                'text-green-600' => $isGood,
                                                'text-red-600' => !$isGood && $limitOL > 0,
                                            ])>
                                                @if($losses < 0)
                                                    ({{ number_format(abs($losses), 2) }})
                                                @else
                                                    {{ number_format($losses, 2) }}
                                                @endif
                                            </span>

                                            <span class="text-xs text-gray-500 block">Limit:
                                                @if($limitOL > 0)
                                                    @if($limitOL < 0)
                                                        ({{ number_format(abs($limitOL), 2) }})
                                                    @else
                                                        {{ number_format($limitOL, 2) }}
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </span>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $oilLoss->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                @can('edit oil losses')
                                                    <a href="{{ route('oil.edit', $oilLoss->id) }}"
                                                        class="text-blue-600 hover:text-blue-900" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                @endcan
                                                @can('delete oil losses')
                                                    <form action="{{ route('oil.destroy', $oilLoss->id) }}" method="POST"
                                                        class="inline delete-form" data-item-name="Data {{ $oilLoss->kode ?? 'ini' }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $oilLosses->appends(array_merge(request()->except('page'), ['tab' => 'calculations']))->links() }}
                    </div>
                @endif
            </div>
        @endcan
    </x-ui.card>

    @if($successProof)
        <div class="success-proof-export-root" aria-hidden="true">
            <div id="success-proof-export" class="rounded-[28px] bg-white p-8 shadow-2xl">
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6">
                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900">Bukti Input Data Oil Losses</h1>
                            <p class="mt-3 text-lg font-semibold text-emerald-900">{{ $successProof['message'] }}</p>
                            <p class="mt-2 text-base text-emerald-800">Waktu bukti dibuat:
                                {{ $successProof['generated_at'] }}
                            </p>
                        </div>
                        <div class="min-w-[260px] rounded-xl bg-white/80 p-4 text-base text-emerald-900">
                            <div><span class="font-semibold">User login:</span> {{ auth()->user()->name }}</div>
                            <div class="mt-1"><span class="font-semibold">Office login:</span>
                                {{ auth()->user()->office ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                @if($mode1Entries->isNotEmpty())
                    <section class="mb-6 rounded-2xl border border-blue-200 bg-blue-50/60 p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-3xl font-bold text-slate-900">Data Jenis &amp; Sampel</h2>
                            <span class="rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">Mode
                                Non-Angka</span>
                        </div>

                        <div class="overflow-hidden rounded-2xl border border-blue-200 bg-white">
                            <table class="divide-y divide-blue-200">
                                <thead class="bg-blue-100 text-slate-700">
                                    <tr>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal
                                            Input</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal
                                            Sampel</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Kode</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Jenis
                                        </th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Operator
                                        </th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Sampel
                                            Boy</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Input By
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-blue-100 text-base text-slate-700">
                                    @foreach($mode1Entries as $proofMode1)
                                        <tr>
                                            <td class="px-5 py-4">{{ $proofMode1['tanggal_input'] }}</td>
                                            <td class="px-5 py-4">{{ $proofMode1['tanggal_sampel'] ?? '-' }}</td>
                                            <td class="px-5 py-4 font-semibold text-blue-900">{{ $proofMode1['kode_label'] }}</td>
                                            <td class="px-5 py-4">{{ $proofMode1['jenis'] ?? '-' }}</td>
                                            <td class="px-5 py-4">{{ $proofMode1['operator'] ?? '-' }}</td>
                                            <td class="px-5 py-4">{{ $proofMode1['sampel_boy'] ?? '-' }}</td>
                                            <td class="px-5 py-4">{{ $proofMode1['input_by'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif

                @if($mode2Entries->isNotEmpty())
                    <section class="rounded-2xl border border-purple-200 bg-purple-50/60 p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-3xl font-bold text-slate-900">Data Perhitungan Lab</h2>
                            <span class="rounded-full bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">Mode
                                Angka</span>
                        </div>

                        <div class="mb-5 overflow-hidden rounded-2xl border border-purple-200 bg-white">
                            <table class="divide-y divide-purple-200">
                                <thead class="bg-purple-100 text-slate-700">
                                    <tr>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Kode</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Cawan
                                            Kosong</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Berat
                                            Basah</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Cawan +
                                            Sample Kering</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Labu
                                            Kosong</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Oil +
                                            Labu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-purple-100 text-base text-slate-700">
                                    @foreach($mode2Entries as $proofMode2Export)
                                        <tr>
                                            <td class="px-5 py-4 font-semibold text-purple-900">
                                                {{ $proofMode2Export['kode_label'] }}
                                            </td>
                                            <td class="px-5 py-4">{{ number_format((float) $proofMode2Export['cawan_kosong'], 6) }}
                                            </td>
                                            <td class="px-5 py-4">{{ number_format((float) $proofMode2Export['berat_basah'], 6) }}
                                            </td>
                                            <td class="px-5 py-4">
                                                {{ number_format((float) $proofMode2Export['cawan_sample_kering'], 6) }}
                                            </td>
                                            <td class="px-5 py-4">{{ number_format((float) $proofMode2Export['labu_kosong'], 6) }}
                                            </td>
                                            <td class="px-5 py-4">{{ number_format((float) $proofMode2Export['oil_labu'], 6) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="overflow-hidden rounded-2xl border border-purple-200 bg-white">
                            <table class="divide-y divide-purple-200">
                                <thead class="bg-purple-100 text-slate-700">
                                    <tr>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal
                                            Input</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Tanggal
                                            Sampel</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Kode</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Moist (%)
                                        </th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">DMWM (%)
                                        </th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">OLWB</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">OLDB</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Oil
                                            Losses (%)</th>
                                        <th class="px-5 py-4 text-left text-sm font-semibold uppercase tracking-wider">Input By
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-purple-100 text-base text-slate-700">
                                    @foreach($mode2Entries as $proofMode2Export)
                                        @php
                                            $proofOlwbExport = (float) ($proofMode2Export['olwb'] ?? 0);
                                            $proofOldbExport = (float) ($proofMode2Export['oldb'] ?? 0);
                                            $proofLossesExport = (float) ($proofMode2Export['oil_losses'] ?? 0);
                                            $proofLimitOlwbExport = (float) ($proofMode2Export['limitOLWB'] ?? 0);
                                            $proofLimitOldbExport = (float) ($proofMode2Export['limitOLDB'] ?? 0);
                                            $proofLimitOlExport = (float) ($proofMode2Export['limitOL'] ?? 0);
                                            $proofLimitOperatorExport = $proofMode2Export['limit_operator'] ?? 'le';
                                            $isOlwbGoodExport = $proofLimitOlwbExport > 0
                                                && match ($proofLimitOperatorExport) {
                                                    'lt' => $proofOlwbExport < $proofLimitOlwbExport,
                                                    'ge' => $proofOlwbExport >= $proofLimitOlwbExport,
                                                    'gt' => $proofOlwbExport > $proofLimitOlwbExport,
                                                    default => $proofOlwbExport <= $proofLimitOlwbExport,
                                                };
                                            $isOldbGoodExport = $proofOldbExport <= $proofLimitOldbExport && $proofLimitOldbExport > 0;
                                            $isLossesGoodExport = $proofLossesExport <= $proofLimitOlExport && $proofLimitOlExport > 0;
                                        @endphp
                                        <tr>
                                            <td class="px-5 py-4">{{ $proofMode2Export['tanggal_input'] }}</td>
                                            <td class="px-5 py-4">{{ $proofMode2Export['tanggal_sampel'] ?? '-' }}</td>
                                            <td class="px-5 py-4 font-semibold text-purple-900">
                                                {{ $proofMode2Export['kode_label'] }}
                                            </td>
                                            <td class="px-5 py-4">{{ number_format((float) $proofMode2Export['moist'], 4) }}</td>
                                            <td class="px-5 py-4">{{ number_format((float) $proofMode2Export['dmwm'], 4) }}</td>
                                            <td class="px-5 py-4">
                                                <div @class(['font-semibold', 'text-green-600' => $isOlwbGoodExport, 'text-red-600' => !$isOlwbGoodExport && $proofLimitOlwbExport > 0])>
                                                    @if($proofOlwbExport < 0)
                                                        ({{ number_format(abs($proofOlwbExport), 2) }})
                                                    @else
                                                        {{ number_format($proofOlwbExport, 2) }}
                                                    @endif
                                                </div>
                                                <div class="mt-1 text-sm text-gray-500">Limit:
                                                    @if($proofLimitOlwbExport > 0)
                                                        @if($proofLimitOlwbExport < 0)
                                                            ({{ number_format(abs($proofLimitOlwbExport), 2) }})
                                                        @else
                                                            {{ number_format($proofLimitOlwbExport, 2) }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div @class(['font-semibold', 'text-green-600' => $isOldbGoodExport, 'text-red-600' => !$isOldbGoodExport && $proofLimitOldbExport > 0])>
                                                    @if($proofOldbExport < 0)
                                                        ({{ number_format(abs($proofOldbExport), 2) }})
                                                    @else
                                                        {{ number_format($proofOldbExport, 2) }}
                                                    @endif
                                                </div>
                                                <div class="mt-1 text-sm text-gray-500">Limit:
                                                    @if($proofLimitOldbExport > 0)
                                                        @if($proofLimitOldbExport < 0)
                                                            ({{ number_format(abs($proofLimitOldbExport), 2) }})
                                                        @else
                                                            {{ number_format($proofLimitOldbExport, 2) }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div @class(['font-semibold', 'text-green-600' => $isLossesGoodExport, 'text-red-600' => !$isLossesGoodExport && $proofLimitOlExport > 0])>
                                                    @if($proofLossesExport < 0)
                                                        ({{ number_format(abs($proofLossesExport), 2) }})
                                                    @else
                                                        {{ number_format($proofLossesExport, 2) }}
                                                    @endif
                                                </div>
                                                <div class="mt-1 text-sm text-gray-500">Limit:
                                                    @if($proofLimitOlExport > 0)
                                                        @if($proofLimitOlExport < 0)
                                                            ({{ number_format(abs($proofLimitOlExport), 2) }})
                                                        @else
                                                            {{ number_format($proofLimitOlExport, 2) }}
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">{{ $proofMode2Export['input_by'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            </div>
        </div>

        <div id="successProofModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
            <div class="w-full max-w-6xl rounded-2xl bg-white shadow-2xl">
                <div class="flex items-start justify-between border-b border-slate-200 px-6 py-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Bukti Input Data Oil Losses</h2>
                        <p class="mt-1 text-sm text-slate-600">Silakan screenshot bagian ini sebagai bukti bahwa data sudah
                            tersimpan.</p>
                    </div>
                    <button type="button" onclick="closeSuccessProofModal()"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="success-proof-print" class="max-h-[80vh] overflow-y-auto px-6 py-5">
                    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-emerald-900">{{ $successProof['message'] }}</p>
                                <p class="mt-1 text-sm text-emerald-800">Waktu bukti dibuat:
                                    {{ $successProof['generated_at'] }}
                                </p>
                                <p class="mt-1 text-xs text-emerald-700">Tips mobile: gunakan tombol Unduh Gambar untuk
                                    bukti 1 file JPG tanpa perlu screenshot panjang.</p>
                            </div>
                            <div class="text-sm text-emerald-800">
                                <div>User login: {{ auth()->user()->name }}</div>
                                <div>Office login: {{ auth()->user()->office ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    @if($mode1Entries->isNotEmpty())
                        <section class="mb-6 rounded-xl border border-blue-200 bg-blue-50/60 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-slate-900">Data Jenis &amp; Sampel</h3>
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Mode
                                    Non-Angka</span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-blue-200 overflow-hidden rounded-lg bg-white">
                                    <thead class="bg-blue-100 text-slate-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                                Tanggal Input</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                                Tanggal Sampel</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kode
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jenis
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                                Operator</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                                Sampel Boy</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Input
                                                By</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-blue-100 text-sm text-slate-700">
                                        @foreach($mode1Entries as $proofMode1)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $proofMode1['tanggal_input'] }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $proofMode1['tanggal_sampel'] ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-blue-900">
                                                    {{ $proofMode1['kode_label'] }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $proofMode1['jenis'] ?? '-' }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $proofMode1['operator'] ?? '-' }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $proofMode1['sampel_boy'] ?? '-' }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $proofMode1['input_by'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endif

                    @if($mode2Entries->isNotEmpty())
                        <section class="rounded-xl border border-purple-200 bg-purple-50/60 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-slate-900">Data Perhitungan Lab</h3>
                                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">Mode
                                    Angka</span>
                            </div>

                            <div class="mb-4 overflow-x-auto rounded-lg border border-purple-200 bg-white">
                                <table class="min-w-full divide-y divide-purple-200">
                                    <thead class="bg-purple-100 text-slate-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kode
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Cawan
                                                Kosong</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Berat
                                                Basah</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Cawan
                                                + Sample Kering</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Labu
                                                Kosong</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Oil +
                                                Labu</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-purple-100 text-sm text-slate-700">
                                        @foreach($mode2Entries as $proofMode2)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-purple-900">
                                                    {{ $proofMode2['kode_label'] }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    {{ number_format((float) $proofMode2['cawan_kosong'], 6) }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    {{ number_format((float) $proofMode2['berat_basah'], 6) }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    {{ number_format((float) $proofMode2['cawan_sample_kering'], 6) }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    {{ number_format((float) $proofMode2['labu_kosong'], 6) }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    {{ number_format((float) $proofMode2['oil_labu'], 6) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-purple-200 overflow-hidden rounded-lg bg-white">
                                    <thead class="bg-purple-100 text-slate-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                                Tanggal Input</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                                Tanggal Sampel</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kode
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Moist
                                                (%)</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">DMWM
                                                (%)</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">OLWB
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">OLDB
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Oil
                                                Losses (%)</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Input
                                                By</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-purple-100 text-sm text-slate-700">
                                        @foreach($mode2Entries as $proofMode2)
                                            @php
                                                $proofOlwb = (float) ($proofMode2['olwb'] ?? 0);
                                                $proofOldb = (float) ($proofMode2['oldb'] ?? 0);
                                                $proofLosses = (float) ($proofMode2['oil_losses'] ?? 0);
                                                $proofLimitOlwb = (float) ($proofMode2['limitOLWB'] ?? 0);
                                                $proofLimitOldb = (float) ($proofMode2['limitOLDB'] ?? 0);
                                                $proofLimitOl = (float) ($proofMode2['limitOL'] ?? 0);
                                                $proofLimitOperator = $proofMode2['limit_operator'] ?? 'le';
                                                $isOlwbGood = $proofLimitOlwb > 0
                                                    && match ($proofLimitOperator) {
                                                        'lt' => $proofOlwb < $proofLimitOlwb,
                                                        'ge' => $proofOlwb >= $proofLimitOlwb,
                                                        'gt' => $proofOlwb > $proofLimitOlwb,
                                                        default => $proofOlwb <= $proofLimitOlwb,
                                                    };
                                                $isOldbGood = $proofOldb <= $proofLimitOldb && $proofLimitOldb > 0;
                                                $isLossesGood = $proofLosses <= $proofLimitOl && $proofLimitOl > 0;
                                            @endphp
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $proofMode2['tanggal_input'] }}</td>
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $proofMode2['tanggal_sampel'] ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-purple-900">
                                                    {{ $proofMode2['kode_label'] }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    {{ number_format((float) $proofMode2['moist'], 4) }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    {{ number_format((float) $proofMode2['dmwm'], 4) }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span @class([
                                                        'font-semibold',
                                                        'text-green-600' => $isOlwbGood,
                                                        'text-red-600' => !$isOlwbGood && $proofLimitOlwb > 0,
                                                    ])>
                                                        @if($proofOlwb < 0)
                                                            ({{ number_format(abs($proofOlwb), 2) }})
                                                        @else
                                                            {{ number_format($proofOlwb, 2) }}
                                                        @endif
                                                    </span>
                                                    <span class="text-xs text-gray-500 block">Limit:
                                                        @if($proofLimitOlwb > 0)
                                                            @if($proofLimitOlwb < 0)
                                                                ({{ number_format(abs($proofLimitOlwb), 2) }})
                                                            @else
                                                                {{ number_format($proofLimitOlwb, 2) }}
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span @class([
                                                        'font-semibold',
                                                        'text-green-600' => $isOldbGood,
                                                        'text-red-600' => !$isOldbGood && $proofLimitOldb > 0,
                                                    ])>
                                                        @if($proofOldb < 0)
                                                            ({{ number_format(abs($proofOldb), 2) }})
                                                        @else
                                                            {{ number_format($proofOldb, 2) }}
                                                        @endif
                                                    </span>
                                                    <span class="text-xs text-gray-500 block">Limit:
                                                        @if($proofLimitOldb > 0)
                                                            @if($proofLimitOldb < 0)
                                                                ({{ number_format(abs($proofLimitOldb), 2) }})
                                                            @else
                                                                {{ number_format($proofLimitOldb, 2) }}
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span @class([
                                                        'font-semibold',
                                                        'text-green-600' => $isLossesGood,
                                                        'text-red-600' => !$isLossesGood && $proofLimitOl > 0,
                                                    ])>
                                                        @if($proofLosses < 0)
                                                            ({{ number_format(abs($proofLosses), 2) }})
                                                        @else
                                                            {{ number_format($proofLosses, 2) }}
                                                        @endif
                                                    </span>
                                                    <span class="text-xs text-gray-500 block">Limit:
                                                        @if($proofLimitOl > 0)
                                                            @if($proofLimitOl < 0)
                                                                ({{ number_format(abs($proofLimitOl), 2) }})
                                                            @else
                                                                {{ number_format($proofLimitOl, 2) }}
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">{{ $proofMode2['input_by'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endif
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">
                    <button type="button" onclick="downloadSuccessProofImage()"
                        class="rounded-lg border border-emerald-300 px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50">
                        Unduh Gambar (JPG)
                    </button>
                    <button type="button" onclick="closeSuccessProofModal()"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Tab Switching JavaScript --}}
    <script>
        function switchTab(tabName, updateUrl = true) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active styles from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Add active styles to selected tab
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            activeButton.classList.add('border-blue-500', 'text-blue-600');

            // Update URL query param so pagination preserves tab
            if (updateUrl) {
                const url = new URL(window.location.href);
                url.searchParams.set('tab', tabName);
                url.searchParams.delete('page'); // reset page when manually switching tab
                window.history.replaceState({}, '', url.toString());
            }
        }

        // On load: honour ?tab= query param, then session default
        document.addEventListener('DOMContentLoaded', function () {
            const urlTab = new URLSearchParams(window.location.search).get('tab');
            const sessionTab = @json($defaultTab);
            switchTab(urlTab || sessionTab, false);
        });
    </script>

    <script>
        async function downloadSuccessProofImage() {
            const target = document.getElementById('success-proof-export');
            if (!target || !window.htmlToImage || typeof window.htmlToImage.toJpeg !== 'function') {
                alert('Fitur unduh gambar belum siap. Coba refresh halaman.');
                return;
            }

            try {
                const dataUrl = await window.htmlToImage.toJpeg(target, {
                    backgroundColor: '#ffffff',
                    quality: 0.82,
                    pixelRatio: Math.min(2, window.devicePixelRatio || 1),
                    cacheBust: true,
                    skipAutoScale: false,
                });

                const link = document.createElement('a');
                const timestamp = new Date().toISOString().slice(0, 19).replace(/[:T]/g, '-');
                link.href = dataUrl;
                link.download = `bukti-input-oil-${timestamp}.jpg`;
                document.body.appendChild(link);
                link.click();
                link.remove();
            } catch (error) {
                console.error('downloadSuccessProofImage', error);
                alert('Gagal mengunduh gambar. Silakan coba lagi.');
            }
        }

        function closeSuccessProofModal() {
            const modal = document.getElementById('successProofModal');
            if (modal) {
                modal.remove();
            }
        }

        // Handle delete confirmations with SweetAlert2
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const itemName = this.dataset.itemName || 'data ini';
                    const confirmed = await window.confirmDelete(itemName);
                    if (confirmed) {
                        if (typeof window.lockFormSubmission === 'function') {
                            const submitter = e.submitter || this.querySelector('button[type="submit"], input[type="submit"]');
                            window.lockFormSubmission(this, submitter);
                        }
                        this.submit();
                    }
                });
            });

            const successProofModal = document.getElementById('successProofModal');
            if (successProofModal) {
                successProofModal.addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeSuccessProofModal();
                    }
                });
            }
        });
    </script>

    @if($successProof)
        <script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.11/dist/html-to-image.js"></script>
    @endif

</x-layouts.app>