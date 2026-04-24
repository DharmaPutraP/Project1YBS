<x-layouts.app title="Input Oil Loss Foss">
    <div class="mx-auto max-w-7xl">
        <x-ui.card title="Input Oil Loss Foss">
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Oil Loss Foss</p>
                    <h1 class="mt-1 text-2xl font-bold text-slate-900">Input Oil Loss Foss</h1>
                    <p class="mt-2 text-sm text-slate-500">Isi data per titik mesin. Tanggal, waktu, operator, dan shift ada di setiap kartu mesin.</p>
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

                @php
                    $groupStyleMap = [
                        'COT' => [
                            'section' => 'border-emerald-200 bg-emerald-50',
                            'badge' => 'bg-emerald-100 text-emerald-700',
                            'card' => 'border-emerald-200 bg-white',
                        ],
                        'CST' => [
                            'section' => 'border-amber-200 bg-amber-50',
                            'badge' => 'bg-amber-100 text-amber-700',
                            'card' => 'border-amber-200 bg-white',
                        ],
                        'FD' => [
                            'section' => 'border-sky-200 bg-sky-50',
                            'badge' => 'bg-sky-100 text-sky-700',
                            'card' => 'border-sky-200 bg-white',
                        ],
                        'HP' => [
                            'section' => 'border-indigo-200 bg-indigo-50',
                            'badge' => 'bg-indigo-100 text-indigo-700',
                            'card' => 'border-indigo-200 bg-white',
                        ],
                        'SD' => [
                            'section' => 'border-rose-200 bg-rose-50',
                            'badge' => 'bg-rose-100 text-rose-700',
                            'card' => 'border-rose-200 bg-white',
                        ],
                        'HPL' => [
                            'section' => 'border-fuchsia-200 bg-fuchsia-50',
                            'badge' => 'bg-fuchsia-100 text-fuchsia-700',
                            'card' => 'border-fuchsia-200 bg-white',
                        ],
                        'FE' => [
                            'section' => 'border-cyan-200 bg-cyan-50',
                            'badge' => 'bg-cyan-100 text-cyan-700',
                            'card' => 'border-cyan-200 bg-white',
                        ],
                        'FBP' => [
                            'section' => 'border-orange-200 bg-orange-50',
                            'badge' => 'bg-orange-100 text-orange-700',
                            'card' => 'border-orange-200 bg-white',
                        ],
                        'FP' => [
                            'section' => 'border-violet-200 bg-violet-50',
                            'badge' => 'bg-violet-100 text-violet-700',
                            'card' => 'border-violet-200 bg-white',
                        ],
                    ];
                @endphp

                <div class="grid gap-6 xl:grid-cols-1">
                    @foreach ($machineGroups as $group => $machines)
                        @php
                            $style = $groupStyleMap[$group] ?? [
                                'section' => 'border-slate-200 bg-slate-50',
                                'badge' => 'bg-slate-100 text-slate-700',
                                'card' => 'border-slate-200 bg-white',
                            ];
                        @endphp

                        <section class="space-y-4 rounded-xl border p-4 {{ $style['section'] }}">
                            <div class="flex items-center justify-between gap-3">
                                <h2 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-800">{{ $group }}</h2>
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $style['badge'] }}">{{ count($machines) }} kode</span>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                @foreach ($machines as $machine)
                                    <div class="overflow-hidden rounded-lg border shadow-sm {{ $style['card'] }}">
                                        <div class="flex items-start justify-between gap-4 border-b px-5 py-4 {{ $style['section'] }}">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Oil Loss Foss</p>
                                                <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $machine['label'] }}</h3>
                                                <p class="mt-1 text-xs text-slate-600">{{ $machine['sample_name'] ?? $machine['label'] }}</p>
                                            </div>
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $style['badge'] }}">{{ $group }}</span>
                                        </div>

                                        <div class="space-y-4 p-5">
                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">TANGGAL</label>
                                                    <input type="date" name="rows[{{ $machine['key'] }}][tanggal]" value="{{ old('rows.' . $machine['key'] . '.tanggal', now()->toDateString()) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                </div>
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">WAKTU</label>
                                                    <input type="time" name="rows[{{ $machine['key'] }}][waktu]" value="{{ old('rows.' . $machine['key'] . '.waktu', now()->format('H:i')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">OPERATOR</label>
                                                    <select name="rows[{{ $machine['key'] }}][operator]" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                        @forelse ($operatorOptions as $operatorOption)
                                                            <option value="{{ $operatorOption }}" @selected((string) old('rows.' . $machine['key'] . '.operator', $defaultOperator) === (string) $operatorOption)>{{ $operatorOption }}</option>
                                                        @empty
                                                            <option value="{{ $defaultOperator }}">{{ $defaultOperator }}</option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">SHIFT</label>
                                                    <select name="rows[{{ $machine['key'] }}][shift]" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                        @foreach ($shiftOptions as $shift)
                                                            <option value="{{ $shift }}" @selected((string) old('rows.' . $machine['key'] . '.shift', '1') === (string) $shift)>{{ $shift }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">MOIST (%)</label>
                                                    <input type="number" step="0.01" min="0" name="rows[{{ $machine['key'] }}][moist]" value="{{ old('rows.' . $machine['key'] . '.moist') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                </div>
                                                <div>
                                                    <label class="mb-2 block text-sm font-medium text-slate-700">OLWB (%)</label>
                                                    <input type="number" step="0.01" min="0" name="rows[{{ $machine['key'] }}][olwb]" value="{{ old('rows.' . $machine['key'] . '.olwb') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                                                </div>
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
        </x-ui.card>
    </div>
</x-layouts.app>
