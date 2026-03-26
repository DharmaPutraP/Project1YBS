<x-layouts.app title="Input Data Dirt & Moist">

    <x-ui.card title="Input Data Dirt & Moist">
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-indigo-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-indigo-900 mb-1">Waktu Input Otomatis</h4>
                    <p class="text-sm text-indigo-800">
                        Tanggal &amp; Jam akan diisi otomatis saat Anda klik <strong>Simpan Data</strong>.
                        <span class="text-indigo-600 font-medium">Gunakan tombol "Simpan &amp; Tambah Lagi" untuk input
                            berkali-kali.</span>
                    </p>
                    <div class="mt-2 p-3 bg-white rounded border border-indigo-200">
                        <div class="text-xs text-indigo-700">Waktu Saat Ini (Preview):</div>
                        <div class="text-lg font-bold text-indigo-900" id="currentDateTime">
                            {{ now()->format('d/m/Y H:i:s') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('kernel.dirt-moist.store') }}" method="POST" id="dirtMoistForm" class="space-y-5">
            @csrf
            <input type="hidden" name="_action" id="formAction" value="save">

            <div class="mt-6 border-2 border-blue-200 bg-blue-50 rounded-lg p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <select name="kode" id="kode"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('kode') border-red-400 bg-red-50 @enderror">
                            <option value="">-- Pilih Kode --</option>
                            @foreach($kodeOptions as $kodeValue => $kodeLabel)
                                <option value="{{ $kodeValue }}" {{ old('kode') == $kodeValue ? 'selected' : '' }}>
                                    {{ $kodeValue }} - {{ $kodeLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error('kode')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis" id="jenis"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('jenis') border-red-400 bg-red-50 @enderror">
                            @foreach($jenisOptions as $jenisValue => $jenisLabel)
                                <option value="{{ $jenisValue }}" {{ old('jenis', 'TBS') == $jenisValue ? 'selected' : '' }}>
                                    {{ $jenisLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="operator" class="block text-sm font-medium text-gray-700 mb-2">
                            Operator <span class="text-red-500">*</span>
                        </label>
                        @if(!empty($operatorOptions))
                            <select name="operator" id="operator"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('operator') border-red-400 bg-red-50 @enderror">
                                <option value="">-- Pilih Operator --</option>
                                @foreach($operatorOptions as $operatorName)
                                    <option value="{{ $operatorName }}" {{ old('operator') == $operatorName ? 'selected' : '' }}>
                                        {{ $operatorName }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Daftar operator mengikuti office
                                {{ Auth::user()->office ?? '-' }}.</p>
                        @else
                            <input type="text" name="operator" id="operator" value="{{ old('operator') }}"
                                placeholder="Nama operator"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('operator') border-red-400 bg-red-50 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Dropdown operator belum tersedia untuk office
                                {{ Auth::user()->office ?? '-' }}.</p>
                        @endif
                        @error('operator')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sampel_boy" class="block text-sm font-medium text-gray-700 mb-2">
                            Sampel Boy
                        </label>
                        <input type="text" name="sampel_boy" id="sampel_boy"
                            value="{{ old('sampel_boy', Auth::user()->name) }}" readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100 cursor-not-allowed">
                        @error('sampel_boy')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="berat_sampel" class="block text-sm font-medium text-gray-700 mb-2">
                            Berat Sampel (gram) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.0001" name="berat_sampel" id="berat_sampel"
                            value="{{ old('berat_sampel') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('berat_sampel') border-red-400 bg-red-50 @enderror">
                        @error('berat_sampel')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="berat_dirty" class="block text-sm font-medium text-gray-700 mb-2">
                            Berat Dirty (gram) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.0001" name="berat_dirty" id="berat_dirty"
                            value="{{ old('berat_dirty') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('berat_dirty') border-red-400 bg-red-50 @enderror">
                        @error('berat_dirty')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="moist_percent" class="block text-sm font-medium text-gray-700 mb-2">
                            Moist (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.0001" name="moist_percent" id="moist_percent"
                            value="{{ old('moist_percent') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('moist_percent') border-red-400 bg-red-50 @enderror">
                        @error('moist_percent')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                    <input type="checkbox" name="pengulangan" value="1" {{ old('pengulangan') ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Apakah ini data sampel ulang?</span>
                </label>
                <p class="mt-1 text-xs text-gray-500">Checklist hanya valid pada rentang waktu sesuai kode sampling.</p>
                @error('pengulangan')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('kernel.dirt-moist.index') }}"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                    Batal
                </a>
                <button type="button" id="resetBtn"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                    Reset Form
                </button>
                <button type="submit" onclick="document.getElementById('formAction').value='save'"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">
                    Simpan &amp; Selesai
                </button>
            </div>
        </form>
    </x-ui.card>

    <script>
        const kernelLimitMap = @json($kernelLimitMap ?? []);

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

        updateClock();
        setInterval(updateClock, 1000);

        // Reset Form
        document.getElementById('resetBtn').addEventListener('click', function () {
            const form = document.getElementById('dirtMoistForm');
            form.reset();
            document.getElementById('kode').value = '';
            document.getElementById('jenis').value = 'TBS';
            document.getElementById('sampel_boy').value = '{{ Auth::user()->name }}';
            const errEl = document.getElementById('validation-error');
            if (errEl) errEl.remove();
        });

        function parseInputNumber(id) {
            const raw = document.getElementById(id)?.value ?? '';
            const value = parseFloat(raw);
            return Number.isFinite(value) ? value : 0;
        }

        function getInputValue(id, fallback = '-') {
            const raw = document.getElementById(id)?.value ?? '';
            const value = raw.toString().trim();
            return value !== '' ? value : fallback;
        }

        function getSelectText(id, fallback = '-') {
            const el = document.getElementById(id);
            if (!el) return fallback;
            const text = el.options?.[el.selectedIndex]?.text?.trim() ?? '';
            return text && !text.startsWith('-- Pilih') ? text : fallback;
        }

        function formatNumber(value) {
            return Number.isFinite(value) ? value.toFixed(2) : '0.00';
        }

        function formatPercent(value) {
            return Number.isFinite(value) ? value.toFixed(2) + '%' : '0.00%';
        }

        function evaluateLimit(valuePercent, limitConfig) {
            if (!limitConfig) return { isGood: null, label: '-' };
            const limitValue = Number(limitConfig.value ?? 0);
            const operator = limitConfig.operator === 'gt' ? 'gt' : 'le';
            const isGood = operator === 'le'
                ? valuePercent <= limitValue
                : valuePercent > limitValue;
            return {
                isGood,
                label: (operator === 'le' ? '≤ ' : '> ') + formatNumber(limitValue) + '%'
            };
        }

        function buildDirtMoistAlertHtml() {
            const kodeValue = getInputValue('kode', '');
            const kode = getSelectText('kode');
            const jenis = getSelectText('jenis');
            const operator = getInputValue('operator');
            const sampelBoy = getInputValue('sampel_boy');
            const beratSampel = parseInputNumber('berat_sampel');
            const beratDirty = parseInputNumber('berat_dirty');
            const moistPercent = parseInputNumber('moist_percent');

            const dirtyToSampel = beratSampel > 0 ? (beratDirty / beratSampel) * 100 : 0;
            const limitConfig = kernelLimitMap?.[kodeValue] ?? null;
            const dirtyStatus = evaluateLimit(dirtyToSampel, limitConfig?.dirty ?? null);
            const moistStatus = evaluateLimit(moistPercent, limitConfig?.moist ?? null);

            const dirtyClass = dirtyStatus.isGood === true
                ? 'text-green-700'
                : (dirtyStatus.isGood === false ? 'text-red-700' : 'text-indigo-900');
            const moistClass = moistStatus.isGood === true
                ? 'text-green-700'
                : (moistStatus.isGood === false ? 'text-red-700' : 'text-indigo-900');

            return '<div class="text-left">' +
                '<p class="text-sm text-gray-600 mb-3">Pastikan data yang Anda masukkan sudah benar.</p>' +
                '<div class="rounded-lg border border-gray-200 bg-gray-50 p-3 mb-3">' +
                '<h4 class="text-sm font-semibold text-gray-900 mb-2">Data Input</h4>' +
                '<ul class="text-sm text-gray-800 space-y-1">' +
                '<li><strong>Kode:</strong> ' + kode + '</li>' +
                '<li><strong>Jenis:</strong> ' + jenis + '</li>' +
                '<li><strong>Operator:</strong> ' + operator + '</li>' +
                '<li><strong>Sampel Boy:</strong> ' + sampelBoy + '</li>' +
                '<li><strong>Berat Sampel:</strong> ' + formatNumber(beratSampel) + ' gram</li>' +
                '<li><strong>Berat Dirty:</strong> ' + formatNumber(beratDirty) + ' gram</li>' +
                '<li><strong>Moist:</strong> ' + formatPercent(moistPercent) + '</li>' +
                '</ul>' +
                '</div>' +
                '<div class="rounded-lg border border-indigo-200 bg-indigo-50 p-3">' +
                '<h4 class="text-sm font-semibold text-indigo-900 mb-2">Hasil Perhitungan Dirt & Moist</h4>' +
                '<ul class="text-sm text-indigo-900 space-y-1">' +
                '<li class="' + dirtyClass + '"><strong>Dirty to Sampel:</strong> ' + formatPercent(dirtyToSampel) + ' <span class="text-xs text-indigo-700">(Limit: ' + dirtyStatus.label + ')</span></li>' +
                '<li class="' + moistClass + '"><strong>Kadar Air Kernel:</strong> ' + formatPercent(moistPercent) + ' <span class="text-xs text-indigo-700">(Limit: ' + moistStatus.label + ')</span></li>' +
                '</ul>' +
                '</div>' +
                '</div>';
        }

        document.getElementById('dirtMoistForm').addEventListener('submit', async function (event) {
            event.preventDefault();
            const confirmed = await window.confirmSave(this, {
                title: 'Simpan Data Dirt & Moist?',
                html: buildDirtMoistAlertHtml()
            });
            if (confirmed) {
                this.submit();
            }
        });
    </script>

</x-layouts.app>