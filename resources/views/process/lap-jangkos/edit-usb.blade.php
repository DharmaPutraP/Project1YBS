<x-layouts.app title="Edit Data USB">
    <div class="mx-auto max-w-3xl space-y-6">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Lap Jangkos</p>
                    <h1 class="mt-1 text-2xl font-bold text-slate-900">Edit Data USB</h1>
                    <p class="mt-2 text-sm text-slate-500">Perbarui data USB untuk No Rebusan / Sterilizer {{ $record->no_rebusan }}.</p>
                </div>
                <a href="{{ route('lap-jangkos.data-usb') }}" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Kembali</a>
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

            <form action="{{ route('lap-jangkos.update-usb', $record->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="tanggal" class="mb-1 block text-xs font-semibold text-slate-600">Tgl</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', optional($record->tanggal)->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label for="jam" class="mb-1 block text-xs font-semibold text-slate-600">Jam</label>
                        <input type="time" id="jam" name="jam" value="{{ old('jam', substr((string) $record->jam, 0, 5)) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label for="shift" class="mb-1 block text-xs font-semibold text-slate-600">Shift</label>
                        <select id="shift" name="shift" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                            @foreach ($shiftOptions as $shift)
                                <option value="{{ $shift }}" @selected((string) old('shift', (string) $record->shift) === (string) $shift)>{{ $shift }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">No Rebusan / Sterilizer</label>
                        <input type="text" value="{{ $record->no_rebusan }}" class="w-full rounded-lg border border-slate-300 bg-slate-100 px-3 py-2 text-sm" readonly>
                    </div>

                    <div>
                        <label for="diamati_jlh_janjang" class="mb-1 block text-xs font-semibold text-slate-600">Diamati (Jlh Janjang)</label>
                        <input type="number" step="0.01" min="0" id="diamati_jlh_janjang" name="diamati_jlh_janjang" value="{{ old('diamati_jlh_janjang', $record->diamati_jlh_janjang) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label for="lolos_jlh_janjang" class="mb-1 block text-xs font-semibold text-slate-600">Lolos (Jlh Janjang)</label>
                        <input type="number" step="0.01" min="0" id="lolos_jlh_janjang" name="lolos_jlh_janjang" value="{{ old('lolos_jlh_janjang', $record->lolos_jlh_janjang) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 border-t border-slate-200 pt-4">
                    <button type="submit" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Simpan Perubahan</button>
                    <a href="{{ route('lap-jangkos.data-usb') }}" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
