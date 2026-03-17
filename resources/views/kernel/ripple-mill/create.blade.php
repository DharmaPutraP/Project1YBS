<x-layouts.app title="Input Ripple Mill">

    <x-ui.card title="Input Ripple Mill">
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-indigo-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-indigo-900 mb-1">Waktu Input Otomatis</h4>
                    <p class="text-sm text-indigo-800">
                        Tanggal &amp; Jam akan diisi otomatis saat Anda klik <strong>Simpan Data</strong>.
                        <span class="text-indigo-600 font-medium">Gunakan tombol "Simpan &amp; Tambah Lagi" untuk input berkali-kali.</span>
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

        <form action="{{ route('kernel.ripple-mill.store') }}" method="POST" id="rippleMillForm" class="space-y-5"
              onsubmit="return handleFormSubmit(event, this)">
            @csrf
            <input type="hidden" name="_action" id="formAction" value="save">

            <div class="mt-6 border-2 border-blue-200 bg-blue-50 rounded-lg p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">Kode <span class="text-red-500">*</span></label>
                        <select name="kode" id="kode" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('kode') border-red-400 bg-red-50 @enderror">
                            <option value="">-- Pilih Kode --</option>
                            @foreach($kodeOptions as $kodeValue => $kodeLabel)
                                <option value="{{ $kodeValue }}" {{ old('kode') == $kodeValue ? 'selected' : '' }}>{{ $kodeValue }} - {{ $kodeLabel }}</option>
                            @endforeach
                        </select>
                        @error('kode')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis <span class="text-red-500">*</span></label>
                        <select name="jenis" id="jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('jenis') border-red-400 bg-red-50 @enderror">
                            @foreach($jenisOptions as $jenisValue => $jenisLabel)
                                <option value="{{ $jenisValue }}" {{ old('jenis', 'TBS') == $jenisValue ? 'selected' : '' }}>{{ $jenisLabel }}</option>
                            @endforeach
                        </select>
                        @error('jenis')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="operator" class="block text-sm font-medium text-gray-700 mb-2">Operator <span class="text-red-500">*</span></label>
                        <input type="text" name="operator" id="operator" value="{{ old('operator') }}" placeholder="Nama operator" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('operator') border-red-400 bg-red-50 @enderror">
                        @error('operator')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="sampel_boy" class="block text-sm font-medium text-gray-700 mb-2">Sampel Boy</label>
                        <input type="text" name="sampel_boy" id="sampel_boy" value="{{ old('sampel_boy', Auth::user()->name) }}" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100 cursor-not-allowed">
                    </div>

                    <div>
                        <label for="berat_sampel" class="block text-sm font-medium text-gray-700 mb-2">Berat Sample <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="berat_sampel" id="berat_sampel" value="{{ old('berat_sampel') }}" placeholder="0.0000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('berat_sampel') border-red-400 bg-red-50 @enderror">
                        @error('berat_sampel')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="berat_nut_utuh" class="block text-sm font-medium text-gray-700 mb-2">Berat Nut Utuh <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="berat_nut_utuh" id="berat_nut_utuh" value="{{ old('berat_nut_utuh') }}" placeholder="0.0000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('berat_nut_utuh') border-red-400 bg-red-50 @enderror">
                        @error('berat_nut_utuh')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="berat_nut_pecah" class="block text-sm font-medium text-gray-700 mb-2">Berat Nut Pecah <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="berat_nut_pecah" id="berat_nut_pecah" value="{{ old('berat_nut_pecah') }}" placeholder="0.0000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('berat_nut_pecah') border-red-400 bg-red-50 @enderror">
                        @error('berat_nut_pecah')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('kernel.ripple-mill.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">Batal</a>
                <button type="button" id="resetBtn" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">Reset Form</button>
                <button type="submit" onclick="document.getElementById('formAction').value='save_and_add'" class="px-5 py-2.5 border border-indigo-300 text-indigo-700 rounded-lg hover:bg-indigo-50 transition font-medium">Simpan &amp; Tambah Lagi</button>
                <button type="submit" onclick="document.getElementById('formAction').value='save'" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">Simpan &amp; Selesai</button>
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
            const form = document.getElementById('rippleMillForm');
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
            const isGood = operator === 'le' ? valuePercent <= limitValue : valuePercent > limitValue;

            return {
                isGood,
                label: (operator === 'le' ? '≤ ' : '> ') + formatNumber(limitValue) + '%'
            };
        }

        function buildRippleMillAlertHtml() {
            const kodeValue = getInputValue('kode', '');
            const kode = getSelectText('kode');
            const jenis = getSelectText('jenis');
            const operator = getInputValue('operator');
            const sampelBoy = getInputValue('sampel_boy');
            const beratSampel = parseInputNumber('berat_sampel');
            const beratNutUtuh = parseInputNumber('berat_nut_utuh');
            const beratNutPecah = parseInputNumber('berat_nut_pecah');

            const sampleNutUtuh = beratSampel > 0 ? (beratNutUtuh / beratSampel) * 100 : 0;
            const sampleNutPecah = beratSampel > 0 ? (beratNutPecah / beratSampel) * 100 : 0;
            const efficiency = 100 - sampleNutPecah - sampleNutUtuh;
            const limitStatus = evaluateLimit(efficiency, kernelLimitMap?.[kodeValue] ?? null);
            const efficiencyClass = limitStatus.isGood === true ? 'text-green-700' : (limitStatus.isGood === false ? 'text-red-700' : 'text-indigo-900');

            return '<div class="text-left">' +
                '<p class="text-sm text-gray-600 mb-3">Pastikan data yang Anda masukkan sudah benar.</p>' +
                '<div class="rounded-lg border border-gray-200 bg-gray-50 p-3 mb-3">' +
                '<h4 class="text-sm font-semibold text-gray-900 mb-2">Data Input</h4>' +
                '<ul class="text-sm text-gray-800 space-y-1">' +
                '<li><strong>Kode:</strong> ' + kode + '</li>' +
                '<li><strong>Jenis:</strong> ' + jenis + '</li>' +
                '<li><strong>Operator:</strong> ' + operator + '</li>' +
                '<li><strong>Sampel Boy:</strong> ' + sampelBoy + '</li>' +
                '<li><strong>Berat Sample:</strong> ' + formatNumber(beratSampel) + ' gram</li>' +
                '<li><strong>Berat Nut Utuh:</strong> ' + formatNumber(beratNutUtuh) + ' gram</li>' +
                '<li><strong>Berat Nut Pecah:</strong> ' + formatNumber(beratNutPecah) + ' gram</li>' +
                '</ul>' +
                '</div>' +
                '<div class="rounded-lg border border-indigo-200 bg-indigo-50 p-3">' +
                '<h4 class="text-sm font-semibold text-indigo-900 mb-2">Hasil Perhitungan Ripple Mill</h4>' +
                '<ul class="text-sm text-indigo-900 space-y-1">' +
                '<li><strong>Sample Nut Utuh:</strong> ' + formatPercent(sampleNutUtuh) + '</li>' +
                '<li><strong>Sample Nut Pecah:</strong> ' + formatPercent(sampleNutPecah) + '</li>' +
                '<li class="' + efficiencyClass + '"><strong>Efficiency:</strong> ' + formatPercent(efficiency) + ' <span class="text-xs text-indigo-700">(Limit: ' + limitStatus.label + ')</span></li>' +
                '</ul>' +
                '</div>' +
                '</div>';
        }

        async function handleFormSubmit(event, form) {
            event.preventDefault();
            const confirmed = await window.confirmSave(form, {
                title: 'Simpan Data Ripple Mill?',
                html: buildRippleMillAlertHtml()
            });
            if (confirmed) {
                form.submit();
            }
            return false;
        }
    </script>

</x-layouts.app>