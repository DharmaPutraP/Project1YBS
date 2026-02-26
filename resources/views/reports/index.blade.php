<x-layouts.app title="Laporan">
    <x-ui.card title="Laporan">
        <p class="text-gray-500 text-sm">Halaman ini sedang dalam pengembangan.</p>
    </x-ui.card>
    
    
    {{-- TABLE DATA LAB --}}
    <x-ui.card class="mt-8">
        <div class="relative overflow-x-auto overflow-y-auto max-h-[500px] border rounded-lg">

            <table class="min-w-[4500px] border-rounded-lg border-color:gray-30 text-xs text-gray-700">

                {{-- ================= HEADER ================= --}}
                <thead class="bg-gray-100">

                    <tr>

                        {{-- FREEZE KIRI + ATAS --}}
                        <th class="sticky top-0 left-0 z-[60] bg-gray-100 border px-3 py-2 w-[60px]">
                            NO
                        </th>

                        <th class="sticky top-0 left-[60px] z-[55] bg-gray-100 border px-3 py-2 w-[120px]">
                            BULAN
                        </th>

                        <th class="sticky top-0 left-[180px] z-[55] bg-gray-100 border px-3 py-2 w-[120px]">
                            TANGGAL
                        </th>

                        <th class="sticky top-0 left-[300px] z-[55] bg-gray-100 border px-3 py-2 w-[100px]">
                            JAM
                        </th>

                        <th class="sticky top-0 left-[400px] z-[55] bg-gray-100 border px-3 py-2 w-[120px]">
                            KODE
                        </th>

                        <th class="sticky top-0 left-[520px] z-[55] bg-gray-100 border px-3 py-2 w-[160px]">
                            NAMA
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            NAMA PIVOT
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            OPERATOR
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            SAMPEL BOY
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            JENIS OLAH PRODUKSI
                        </th>

                        {{-- FREEZE ATAS SAJA --}}
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">CAWAN KOSONG</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">BERAT SAMPEL BASAH</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">CAWAN + SAMPEL BASAH</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">CAWAN + SAMPEL KERING</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">SAMPLE SETELAH OVEN</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">LABU KOSONG</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">OIL + LABU</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">MINYAK</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">MOIST (%)</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">DM/WM (%)</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">OLWB (%)</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">LIMIT (%)</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">OLDB (%)</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">LIMIT (%) 2</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">OIL LOSSES / TON TBS (%)</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">LIMIT (%) 3</th>
                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">LIMIT (%) 4</th>

                    </tr>
                </thead>

                {{-- ================= BODY ================= --}}
                <tbody>
                    <tr>
                        <td colspan="30" class="px-4 py-6 text-center text-gray-400">
                            Tidak ada data
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </x-ui.card>

</x-layouts.app>