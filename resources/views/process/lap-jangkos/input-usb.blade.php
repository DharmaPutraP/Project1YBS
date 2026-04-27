<x-layouts.app title="Inputan USB">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Lap Jangkos</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Inputan USB</h1>
                <p class="mt-2 text-sm text-slate-500">Isi salah satu form saja sudah bisa disimpan. Kolom angka yang kosong dianggap 0.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('lap-jangkos.store-usb') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-4">
                    @foreach ($rowNumbers as $rowNumber)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-sm font-bold text-slate-900">No Rebusan / Sterilizer {{ $rowNumber }}</h2>
                                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">USB</span>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">Tgl</label>
                                    <input type="date" name="rows[{{ $rowNumber }}][tanggal]" value="{{ old('rows.' . $rowNumber . '.tanggal') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">Jam</label>
                                    <input type="time" name="rows[{{ $rowNumber }}][jam]" value="{{ old('rows.' . $rowNumber . '.jam') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">Shift</label>
                                    <select name="rows[{{ $rowNumber }}][shift]" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        <option value="">Pilih Shift</option>
                                        @foreach ($shiftOptions as $shift)
                                            <option value="{{ $shift }}" @selected((string) old('rows.' . $rowNumber . '.shift') === (string) $shift)>{{ $shift }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">Diamati (Jlh Janjang)</label>
                                    <input type="number" step="0.01" min="0" name="rows[{{ $rowNumber }}][diamati_jlh_janjang]" value="{{ old('rows.' . $rowNumber . '.diamati_jlh_janjang') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-slate-600">Lolos (Jlh Janjang)</label>
                                    <input type="number" step="0.01" min="0" name="rows[{{ $rowNumber }}][lolos_jlh_janjang]" value="{{ old('rows.' . $rowNumber . '.lolos_jlh_janjang') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-3 border-t border-slate-200 pt-4">
                    <button type="submit" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Simpan</button>
                    <a href="{{ route('lap-jangkos.data-usb') }}" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Lihat Data</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
