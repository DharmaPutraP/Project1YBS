<x-layouts.app title="Edit Oil Loss Foss">
    <div class="mx-auto max-w-3xl space-y-6">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Oil Loss Foss</p>
                    <h1 class="mt-1 text-2xl font-bold text-slate-900">Edit Data Oil Loss Foss</h1>
                    <p class="mt-2 text-sm text-slate-500">Perbarui data untuk {{ $row->machine_name }} pada {{ $row->tanggal?->format('d-m-Y') }}.</p>
                </div>
                <a href="{{ route('oil-loss-foss.data') }}" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Kembali</a>
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

            <form action="{{ route('oil-loss-foss.update', $row) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="tanggal" class="mb-1 block text-xs font-semibold text-slate-600">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $row->tanggal?->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label for="waktu" class="mb-1 block text-xs font-semibold text-slate-600">Waktu</label>
                        <input type="time" id="waktu" name="waktu" value="{{ old('waktu', substr((string) $row->waktu, 0, 5)) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label for="operator" class="mb-1 block text-xs font-semibold text-slate-600">Operator</label>
                        <select id="operator" name="operator" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Pilih Operator</option>
                            @foreach ($operatorOptions as $operatorOption)
                                <option value="{{ $operatorOption }}" @selected((string) old('operator', $row->operator) === (string) $operatorOption)>{{ $operatorOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="shift" class="mb-1 block text-xs font-semibold text-slate-600">Shift</label>
                        <select id="shift" name="shift" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                            @foreach ($shiftOptions as $shift)
                                <option value="{{ $shift }}" @selected((string) old('shift', (string) $row->shift) === (string) $shift)>{{ $shift }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Machine Group</label>
                        <input type="text" value="{{ $row->machine_group }}" class="w-full rounded-lg border border-slate-300 bg-slate-100 px-3 py-2 text-sm" readonly>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold text-slate-600">Machine Name</label>
                        <input type="text" value="{{ $row->machine_name }}" class="w-full rounded-lg border border-slate-300 bg-slate-100 px-3 py-2 text-sm" readonly>
                    </div>

                    <div>
                        <label for="moist" class="mb-1 block text-xs font-semibold text-slate-600">Moist (%)</label>
                        <input type="number" step="0.01" min="0" id="moist" name="moist" value="{{ old('moist', $row->moist) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label for="olwb" class="mb-1 block text-xs font-semibold text-slate-600">OLWB (%)</label>
                        <input type="number" step="0.01" min="0" id="olwb" name="olwb" value="{{ old('olwb', $row->olwb) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 border-t border-slate-200 pt-4">
                    <button type="submit" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Simpan Perubahan</button>
                    <a href="{{ route('oil-loss-foss.data') }}" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
