<x-layouts.app title="Input Oil Loss Foss">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Oil Loss Foss</p>
                    <h1 class="mt-1 text-2xl font-bold text-slate-900">Input Oil Loss Foss</h1>
                    <p class="mt-2 text-sm text-slate-500">Isi header data lalu input MOIST dan OLWB pada titik mesin yang diperlukan.</p>
                </div>
                <a href="{{ route('oil-loss-foss.data') }}" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Lihat Data</a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('oil-loss-foss.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <label for="tanggal" class="mb-1 block text-xs font-semibold text-slate-600">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label for="waktu" class="mb-1 block text-xs font-semibold text-slate-600">Waktu</label>
                        <input type="time" id="waktu" name="waktu" value="{{ old('waktu', now()->format('H:i')) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label for="operator" class="mb-1 block text-xs font-semibold text-slate-600">Operator</label>
                        <input type="text" id="operator" name="operator" value="{{ old('operator', $operator) }}" class="w-full rounded-lg border border-slate-300 bg-slate-100 px-3 py-2 text-sm uppercase" readonly>
                    </div>
                    <div>
                        <label for="shift" class="mb-1 block text-xs font-semibold text-slate-600">Shift</label>
                        <select id="shift" name="shift" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                            @foreach ($shiftOptions as $shift)
                                <option value="{{ $shift }}" @selected((string) old('shift') === (string) $shift)>{{ $shift }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-5">
                    @foreach ($machineGroups as $group => $machines)
                        <section class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-slate-700">{{ $group }}</h2>
                                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ count($machines) }} titik</span>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($machines as $machine)
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                        <p class="mb-3 text-sm font-semibold text-slate-800">{{ $machine['label'] }}</p>
                                        <div class="grid gap-3">
                                            <div>
                                                <label class="mb-1 block text-xs font-semibold text-slate-600">MOIST (%)</label>
                                                <input type="number" step="0.01" min="0" name="rows[{{ $machine['key'] }}][moist]" value="{{ old('rows.' . $machine['key'] . '.moist') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-semibold text-slate-600">OLWB (%)</label>
                                                <input type="number" step="0.01" min="0" name="rows[{{ $machine['key'] }}][olwb]" value="{{ old('rows.' . $machine['key'] . '.olwb') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-3 border-t border-slate-200 pt-4">
                    <button type="submit" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Simpan Semua Data</button>
                    <a href="{{ route('oil-loss-foss.rekap') }}" class="inline-flex items-center rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">Lihat Rekap</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
