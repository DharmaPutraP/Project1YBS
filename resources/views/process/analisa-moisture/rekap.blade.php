<x-layouts.app title="Rekap Analisa Moisture & Spintest">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Rekap Analisa Moisture & Spintest</h1>
        <p class="mt-2 text-sm text-gray-600">
            Tabel rekap average per hari untuk FFA & Moisture, Spintest COT, Spintest Underflow CST, Feed Decanter, dan Light Phase.
        </p>
    </div>

    <x-ui.card>
        <div class="mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
            <form method="GET" action="{{ route('analisa-moisture.rekap') }}"
                class="flex flex-col sm:flex-row gap-2 sm:gap-4 items-end">
                <div class="flex-1 w-full">
                    <label for="start_date" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                </div>

                <div class="flex-1 w-full">
                    <label for="end_date" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Akhir
                    </label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                </div>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-1.5 sm:py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                        Filter
                    </button>

                    @if(request('start_date') || request('end_date'))
                        <a href="{{ route('analisa-moisture.rekap') }}"
                            class="inline-flex items-center justify-center px-4 sm:px-6 py-1.5 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-medium text-sm">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="mt-3 text-sm text-gray-600">
                <span class="font-medium">Periode:</span>
                <span
                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                </span>
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
                <form action="{{ route('analisa-moisture.rekap.export') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        Export ke Excel
                    </button>
                </form>

                <a href="{{ route('analisa-moisture.input') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-slate-700 hover:bg-slate-800">
                    Input Data Analisa
                </a>
            </div>
        </div>

        @if($rows->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-lg text-gray-500">Tidak ada data rekap pada periode ini</p>
                <p class="mt-2 text-sm text-gray-400">Silakan pilih rentang tanggal lain atau input data baru</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300 text-xs md:text-sm">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th rowspan="2" class="px-4 py-3 text-left font-bold text-gray-700 uppercase tracking-wider border border-gray-300 sticky left-0 bg-indigo-50 z-20">Tanggal</th>
                            <th colspan="{{ $isSunOffice ? 4 : 5 }}" class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider border border-gray-300">FFA & Moisture</th>
                            <th colspan="4" class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider border border-gray-300">Spintest COT</th>
                            <th colspan="4" class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider border border-gray-300">Spintest CST</th>
                            <th colspan="4" class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider border border-gray-300">Feed Decanter</th>
                            <th colspan="4" class="px-3 py-3 text-center font-bold text-gray-700 uppercase tracking-wider border border-gray-300">Light Phase</th>
                        </tr>
                        <tr>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Moisture</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">BST 1</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">BST 2</th>
                            @if(!$isSunOffice)
                                <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">BST 3</th>
                            @endif
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Impurities</th>

                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Oil</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Emulsi</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Air</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Nos</th>

                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Oil</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Emulsi</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Air</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Nos</th>

                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Oil</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Emulsi</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Air</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Nos</th>

                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Oil</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Emulsi</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Air</th>
                            <th class="px-2 py-2 text-center font-bold text-gray-700 border border-gray-300">Nos</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($rows as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900 border border-gray-300 sticky left-0 bg-white z-10">
                                    {{ \Illuminate\Support\Carbon::parse($row['tanggal'])->format('d/m/Y') }}
                                </td>

                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['ffa_moisture'] === null ? '-' : number_format((float) $row['ffa_moisture'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['bst1_ffa'] === null ? '-' : number_format((float) $row['bst1_ffa'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['bst2_ffa'] === null ? '-' : number_format((float) $row['bst2_ffa'], 2) }}</td>
                                @if(!$isSunOffice)
                                    <td class="px-2 py-3 text-center border border-gray-300">{{ $row['bst3_ffa'] === null ? '-' : number_format((float) $row['bst3_ffa'], 2) }}</td>
                                @endif
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['impurities'] === null ? '-' : number_format((float) $row['impurities'], 2) }}</td>

                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['cot_oil'] === null ? '-' : number_format((float) $row['cot_oil'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['cot_emulsi'] === null ? '-' : number_format((float) $row['cot_emulsi'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['cot_air'] === null ? '-' : number_format((float) $row['cot_air'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['cot_nos'] === null ? '-' : number_format((float) $row['cot_nos'], 2) }}</td>

                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['cst_oil'] === null ? '-' : number_format((float) $row['cst_oil'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['cst_emulsi'] === null ? '-' : number_format((float) $row['cst_emulsi'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['cst_air'] === null ? '-' : number_format((float) $row['cst_air'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['cst_nos'] === null ? '-' : number_format((float) $row['cst_nos'], 2) }}</td>

                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['feed_oil'] === null ? '-' : number_format((float) $row['feed_oil'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['feed_emulsi'] === null ? '-' : number_format((float) $row['feed_emulsi'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['feed_air'] === null ? '-' : number_format((float) $row['feed_air'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['feed_nos'] === null ? '-' : number_format((float) $row['feed_nos'], 2) }}</td>

                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['light_oil'] === null ? '-' : number_format((float) $row['light_oil'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['light_emulsi'] === null ? '-' : number_format((float) $row['light_emulsi'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['light_air'] === null ? '-' : number_format((float) $row['light_air'], 2) }}</td>
                                <td class="px-2 py-3 text-center border border-gray-300">{{ $row['light_nos'] === null ? '-' : number_format((float) $row['light_nos'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-sm text-gray-600">
                <span class="font-medium">Total Tanggal:</span> {{ $rows->count() }} hari
            </div>
        @endif
    </x-ui.card>

</x-layouts.app>
