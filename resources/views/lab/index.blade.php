<x-layouts.app title="Data Lab">

    <x-ui.card title="Data Lab">
        <p class="text-gray-500 text-sm">Daftar data laboratorium</p>
    </x-ui.card>

    {{-- Form Input Data Lab --}}
    <x-ui.card title="Tambah Data Lab" class="mt-8">
        <form class="space-y-5">
            {{-- Row 1--}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kode
                </label>
                <select
                    name="kode"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                        focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                >
                    <option value="">-- Pilih Kode --</option>
                    <option value="jbp">JBP</option>
                    <option value="cd">CD</option>
                </select>
            </div>

            {{-- Row 2--}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Operator
                </label>
                <select
                    name="operator"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                        focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                >
                    <option value="">-- Pilih Operator --</option>
                    <option value="a">a</option>
                    <option value="b">b</option>
                </select>
            </div>
            
            {{-- Row 3--}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Sampel Boy
                </label>
                <select
                    name="sampel_boy"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                        focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                >
                    <option value="">-- Pilih Sampel Boy --</option>
                    <option value="a">a</option>
                    <option value="b">b</option>
                </select>
            </div>
            
            {{-- Row 4--}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Jenis
                </label>
                <select
                    name="operator"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                        focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                >
                    <option value="">-- Pilih Jenis --</option>
                    <option value="tbs">TBS</option>
                    <option value="brondolan">Brondolan</option>
                </select>
            </div>
            
            {{-- Row 5--}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <x-form.input label="Cawan Kosong" name="cawan_kosong" placeholder="Masukkan cawan kosong" />
                <x-form.input label="Berat Basah" name="berat_basah" placeholder="Masukkan berat basah"/>
                <x-form.input label="Cawan Sampel Kering" name="cawan_sampel_kering" placeholder="Masukkan cawan + sampel kering"/>
            </div>

            {{-- Row 6--}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <x-form.input label="Labu Kosong" name="labu_kosong" placeholder="Masukkan labu kosong" />
                <x-form.input label="Oil Labu" name="oil_labu" placeholder="Masukkan berat oil labu"/>
            </div>

            


            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 pt-6">
                <button type="reset"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                    Reset
                </button>

                <x-ui.button type="submit" variant="primary">
                    Simpan Data
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    {{-- TABLE DATA LAB --}}
    <x-ui.card class="mt-8">
        <div class="relative overflow-x-auto overflow-y-auto max-h-[500px] border rounded-lg">

            <table class="min-w-[4500px] border-collapse text-xs text-gray-700">

                {{-- ================= HEADER ================= --}}
                <thead class="bg-gray-100">

                    <tr>

                        {{-- FREEZE KIRI + ATAS --}}
                        <th class="sticky top-0 left-0 z-30 bg-gray-100 border px-3 py-2 w-[60px]">
                            No
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            BULAN
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            TANGGAL
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            JAM
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            KODE
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            NAMA
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            NAMA PIVOT
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            OPERATOR
                        </th>

                        <th class="sticky top-0 z-20 bg-gray-100 border px-3 py-2">
                            SAMPLE BOY
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