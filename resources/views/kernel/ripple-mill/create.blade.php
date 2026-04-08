<x-layouts.app title="Input Data Ripple Mill">
    <x-ui.card title="Input Data Ripple Mill">
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

        <form action="{{ route('kernel.ripple-mill.store') }}" method="POST" id="rippleMillForm" class="space-y-6">
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
                <div class="space-y-3 rounded-xl border border-violet-200 bg-violet-50 p-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-violet-900">{{ $group['title'] }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4">
                        @foreach($group['items'] as $item)
                            @php $kode = $item['kode']; @endphp
                            <div class="border border-violet-200 rounded-lg p-4 bg-white/85 shadow-sm space-y-3">
                                <div class="flex items-center justify-between gap-3">
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $item['label'] }}</h4>
                                    <span class="text-xs px-2 py-0.5 rounded bg-gray-100 text-gray-700">{{ $kode }}</span>
                                </div>

                                <input type="hidden" name="rows[{{ $kode }}][kode]" value="{{ $kode }}">

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Jenis</label>
                                    <select name="rows[{{ $kode }}][jenis]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        <option value="">-- Pilih Jenis --</option>
                                        @foreach($jenisOptions as $jenisValue => $jenisLabel)
                                            <option value="{{ $jenisValue }}" {{ old("rows.$kode.jenis", 'TBS') == $jenisValue ? 'selected' : '' }}>{{ $jenisLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Operator</label>
                                    @if(!empty($operatorOptions))
                                        <select name="rows[{{ $kode }}][operator]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                            <option value="">-- Pilih Operator --</option>
                                            @foreach($operatorOptions as $operatorName)
                                                <option value="{{ $operatorName }}" {{ old("rows.$kode.operator") == $operatorName ? 'selected' : '' }}>{{ $operatorName }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" name="rows[{{ $kode }}][operator]" value="{{ old("rows.$kode.operator") }}" placeholder="Nama operator" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    @endif
                                </div>

                                <label class="inline-flex items-center gap-2 text-xs font-medium text-gray-700">
                                    <input type="checkbox" name="rows[{{ $kode }}][pengulangan]" value="1" {{ old("rows.$kode.pengulangan") ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span>Data sampel ulang</span>
                                </label>

                                <input type="number" step="0.0001" name="rows[{{ $kode }}][berat_sampel]" value="{{ old("rows.$kode.berat_sampel") }}" placeholder="Berat Sampel" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <input type="number" step="0.0001" name="rows[{{ $kode }}][berat_nut_utuh]" value="{{ old("rows.$kode.berat_nut_utuh") }}" placeholder="Berat Nut Utuh" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <input type="number" step="0.0001" name="rows[{{ $kode }}][berat_nut_pecah]" value="{{ old("rows.$kode.berat_nut_pecah") }}" placeholder="Berat Nut Pecah" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('kernel.ripple-mill.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">Batal</a>
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
            const dispatch = document.getElementById('kegiatan_dispek');
            const roundedTime = document.getElementById('rounded_time');
            if (!dispatch || !roundedTime) return;
            roundedTime.disabled = !dispatch.checked;
        }

        updateClock();
        setInterval(updateClock, 1000);
        document.getElementById('kegiatan_dispek')?.addEventListener('change', toggleRoundedTimeInput);
        toggleRoundedTimeInput();
    </script>
</x-layouts.app>
