<x-layouts.app title="Input Data Kernel Losses">
    <x-ui.card title="Input Data Kernel Losses">
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-indigo-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-indigo-900 mb-1">Jam Pengambilan Checklist</h4>
                    <p class="text-sm text-indigo-800">Isi jam pengambilan saat checklist. Jam ini dipakai untuk validasi interval dan jam proses.</p>
                    <div class="mt-2 p-3 bg-white rounded border border-indigo-200">
                        <div class="text-xs text-indigo-700">Waktu Saat Ini (Preview):</div>
                        <div class="text-lg font-bold text-indigo-900" id="currentDateTime">{{ now()->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <h4 class="text-sm font-semibold text-red-900 mb-2">Data belum bisa disimpan</h4>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kernel.store') }}" method="POST" id="kernelForm" class="space-y-6">
            @csrf

            <div class="border-2 border-blue-200 bg-blue-50 rounded-lg p-4">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                    <input type="checkbox" name="kegiatan_dispek" id="kegiatan_dispek" value="1" {{ old('kegiatan_dispek') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Ada kegiatan dispatch</span>
                </label>
                <label for="rounded_time" class="block text-sm font-medium text-gray-700 mb-2">Jam Pengambilan</label>
                <input type="time" name="rounded_time" id="rounded_time" value="{{ old('rounded_time', now()->format('H:i')) }}" {{ old('kegiatan_dispek') ? '' : 'disabled' }} class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('rounded_time') border-red-400 bg-red-50 @enderror">
                @error('rounded_time')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Jam manual hanya aktif jika kegiatan dispatch dicentang.</p>
            </div>

            @foreach($kodeFormGroups as $group)
                @php
                    $count = count($group['items']);
                    $gridClass = $count === 2
                        ? 'md:grid-cols-2'
                        : ($count === 4 ? 'md:grid-cols-2 xl:grid-cols-4' : 'md:grid-cols-3');

                    $groupTheme = 'bg-slate-50 border-slate-200';
                    $cardTheme = 'bg-white border-gray-200';

                    if (str_contains($group['title'], 'Fibre')) {
                        $groupTheme = 'bg-emerald-50 border-emerald-200';
                        $cardTheme = 'bg-emerald-50/60 border-emerald-200';
                    } elseif (str_contains($group['title'], 'LTDS')) {
                        $groupTheme = 'bg-amber-50 border-amber-200';
                        $cardTheme = 'bg-amber-50/60 border-amber-200';
                    } elseif (str_contains($group['title'], 'Claybath')) {
                        $groupTheme = 'bg-sky-50 border-sky-200';
                        $cardTheme = 'bg-sky-50/60 border-sky-200';
                    }
                @endphp

                <div class="space-y-3 rounded-xl border p-4 {{ $groupTheme }}">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-indigo-900">{{ $group['title'] }}</h3>
                    <div class="grid grid-cols-1 {{ $gridClass }} gap-4">
                        @foreach($group['items'] as $item)
                            @php
                                $kode = $item['kode'];
                                $label = $item['label'];
                            @endphp

                            <div class="border rounded-lg p-4 shadow-sm space-y-3 {{ $cardTheme }}">
                                <div class="flex items-center justify-between gap-3">
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $label }}</h4>
                                    <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-700">{{ $kode }}</span>
                                </div>

                                <input type="hidden" name="rows[{{ $kode }}][kode]" value="{{ $kode }}">

                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Jenis</label>
                                        <select name="rows[{{ $kode }}][jenis]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <option value="">-- Pilih Jenis --</option>
                                            @foreach($jenisOptions as $jenisValue => $jenisLabel)
                                                <option value="{{ $jenisValue }}" {{ old("rows.$kode.jenis", 'TBS') == $jenisValue ? 'selected' : '' }}>{{ $jenisLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Operator</label>
                                        @if(!empty($operatorOptions))
                                            <select name="rows[{{ $kode }}][operator]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error("rows.$kode.operator") border-red-400 bg-red-50 @enderror">
                                                <option value="">-- Pilih Operator --</option>
                                                @foreach($operatorOptions as $operatorName)
                                                    <option value="{{ $operatorName }}" {{ old("rows.$kode.operator") == $operatorName ? 'selected' : '' }}>{{ $operatorName }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" name="rows[{{ $kode }}][operator]" value="{{ old("rows.$kode.operator") }}" placeholder="Nama operator" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error("rows.$kode.operator") border-red-400 bg-red-50 @enderror">
                                        @endif
                                        @error("rows.$kode.operator")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <label class="inline-flex items-center gap-2 text-xs font-medium text-gray-700">
                                    <input type="checkbox" name="rows[{{ $kode }}][pengulangan]" value="1" {{ old("rows.$kode.pengulangan") ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span>Data sampel ulang</span>
                                </label>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Berat Sampel (gram)</label>
                                    <input type="number" step="0.0001" name="rows[{{ $kode }}][berat_sampel]" value="{{ old("rows.$kode.berat_sampel") }}" placeholder="0.0000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error("rows.$kode.berat_sampel") border-red-400 bg-red-50 @enderror">
                                    @error("rows.$kode.berat_sampel")<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nut Utuh - Nut (gram)</label>
                                        <input type="number" step="0.0001" name="rows[{{ $kode }}][nut_utuh_nut]" data-pair="nut-utuh-nut" data-code="{{ $kode }}" value="{{ old("rows.$kode.nut_utuh_nut") }}" placeholder="0.0000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nut Utuh - Kernel</label>
                                        <input type="number" step="0.0001" name="rows[{{ $kode }}][nut_utuh_kernel]" data-pair="nut-utuh-kernel" data-code="{{ $kode }}" value="{{ old("rows.$kode.nut_utuh_kernel") }}" placeholder="0.0000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100" readonly>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nut Pecah - Nut (gram)</label>
                                        <input type="number" step="0.0001" name="rows[{{ $kode }}][nut_pecah_nut]" data-pair="nut-pecah-nut" data-code="{{ $kode }}" value="{{ old("rows.$kode.nut_pecah_nut") }}" placeholder="0.0000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nut Pecah - Kernel</label>
                                        <input type="number" step="0.0001" name="rows[{{ $kode }}][nut_pecah_kernel]" data-pair="nut-pecah-kernel" data-code="{{ $kode }}" value="{{ old("rows.$kode.nut_pecah_kernel") }}" placeholder="0.0000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100" readonly>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Kernel Utuh (gram)</label>
                                        <input type="number" step="0.0001" name="rows[{{ $kode }}][kernel_utuh]" value="{{ old("rows.$kode.kernel_utuh") }}" placeholder="0.0000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Kernel Pecah (gram)</label>
                                        <input type="number" step="0.0001" name="rows[{{ $kode }}][kernel_pecah]" value="{{ old("rows.$kode.kernel_pecah") }}" placeholder="0.0000" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                </div>

                                @error("rows.$kode.kode")<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('kernel.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">Simpan Semua Data</button>
            </div>
        </form>
    </x-ui.card>

    <script>
        function updateClock() {
            const now = new Date();
            const pad = n => String(n).padStart(2, '0');
            const el = document.getElementById('currentDateTime');
            if (el) {
                el.textContent =
                    pad(now.getDate()) + '/' + pad(now.getMonth() + 1) + '/' + now.getFullYear() + ' ' +
                    pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
            }
        }

        function toggleRoundedTimeInput() {
            const dispek = document.getElementById('kegiatan_dispek');
            const roundedTime = document.getElementById('rounded_time');
            if (!dispek || !roundedTime) return;
            roundedTime.disabled = !dispek.checked;
        }

        function setHalfValue(rawValue, targetInput) {
            const source = parseFloat(rawValue);
            if (rawValue === '' || !Number.isFinite(source)) {
                targetInput.value = '';
                return;
            }

            targetInput.value = (source / 2).toFixed(4);
        }

        function syncKernelPairs() {
            document.querySelectorAll('input[data-pair="nut-utuh-nut"]').forEach(input => {
                const code = input.getAttribute('data-code');
                const target = document.querySelector('input[data-pair="nut-utuh-kernel"][data-code="' + code + '"]');
                if (target) {
                    setHalfValue(input.value, target);
                }
            });

            document.querySelectorAll('input[data-pair="nut-pecah-nut"]').forEach(input => {
                const code = input.getAttribute('data-code');
                const target = document.querySelector('input[data-pair="nut-pecah-kernel"][data-code="' + code + '"]');
                if (target) {
                    setHalfValue(input.value, target);
                }
            });
        }

        updateClock();
        setInterval(updateClock, 1000);

        document.getElementById('kegiatan_dispek')?.addEventListener('change', toggleRoundedTimeInput);
        document.querySelectorAll('input[data-pair="nut-utuh-nut"], input[data-pair="nut-pecah-nut"]').forEach(input => {
            input.addEventListener('input', syncKernelPairs);
            input.addEventListener('change', syncKernelPairs);
        });

        toggleRoundedTimeInput();
        syncKernelPairs();
    </script>
</x-layouts.app>
