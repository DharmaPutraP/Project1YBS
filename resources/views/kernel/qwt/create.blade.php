<x-layouts.app title="Input Data QWT Fibre Press">

    <x-ui.card title="Input Data QWT Fibre Press">
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

        <form action="{{ route('kernel.qwt.store') }}" method="POST" id="kernelQwtForm" class="space-y-5"
            onsubmit="return handleFormSubmit(event, this)">
            @csrf
            <input type="hidden" name="_action" id="formAction" value="save">

            <div class="mt-6 border-2 border-blue-200 bg-blue-50 rounded-lg p-5">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">Kode <span
                                class="text-red-500">*</span></label>
                        <select name="kode" id="kode"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('kode') border-red-400 bg-red-50 @enderror">
                            <option value="">-- Pilih Kode --</option>
                            @foreach($kodeOptions as $kodeValue => $kodeLabel)
                                <option value="{{ $kodeValue }}" {{ old('kode') == $kodeValue ? 'selected' : '' }}>
                                    {{ $kodeValue }} - {{ $kodeLabel }}</option>
                            @endforeach
                        </select>
                        @error('kode')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis <span
                                class="text-red-500">*</span></label>
                        <select name="jenis" id="jenis"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('jenis') border-red-400 bg-red-50 @enderror">
                            @foreach($jenisOptions as $jenisValue => $jenisLabel)
                                <option value="{{ $jenisValue }}" {{ old('jenis', 'TBS') == $jenisValue ? 'selected' : '' }}>
                                    {{ $jenisLabel }}</option>
                            @endforeach
                        </select>
                        @error('jenis')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="operator" class="block text-sm font-medium text-gray-700 mb-2">Operator <span
                                class="text-red-500">*</span></label>
                        @if(!empty($operatorOptions))
                            <select name="operator" id="operator"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('operator') border-red-400 bg-red-50 @enderror">
                                <option value="">-- Pilih Operator --</option>
                                @foreach($operatorOptions as $operatorName)
                                    <option value="{{ $operatorName }}" {{ old('operator') == $operatorName ? 'selected' : '' }}>
                                        {{ $operatorName }}</option>
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
                        @error('operator')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="sampel_boy" class="block text-sm font-medium text-gray-700 mb-2">Sampel Boy</label>
                        <input type="text" name="sampel_boy" id="sampel_boy"
                            value="{{ old('sampel_boy', Auth::user()->name) }}" readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100 cursor-not-allowed">
                    </div>

                    <div>
                        <label for="sampel_setelah_kuarter" class="block text-sm font-medium text-gray-700 mb-2">Sampel
                            Setelah Kuarter <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="sampel_setelah_kuarter" id="sampel_setelah_kuarter"
                            value="{{ old('sampel_setelah_kuarter') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('sampel_setelah_kuarter') border-red-400 bg-red-50 @enderror">
                        @error('sampel_setelah_kuarter')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="berat_nut_utuh" class="block text-sm font-medium text-gray-700 mb-2">Berat Nut Utuh
                            <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="berat_nut_utuh" id="berat_nut_utuh"
                            value="{{ old('berat_nut_utuh') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('berat_nut_utuh') border-red-400 bg-red-50 @enderror">
                        @error('berat_nut_utuh')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="berat_nut_pecah" class="block text-sm font-medium text-gray-700 mb-2">Berat Nut
                            Pecah <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="berat_nut_pecah" id="berat_nut_pecah"
                            value="{{ old('berat_nut_pecah') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('berat_nut_pecah') border-red-400 bg-red-50 @enderror">
                        @error('berat_nut_pecah')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="berat_kernel_utuh" class="block text-sm font-medium text-gray-700 mb-2">Berat Kernel
                            Utuh <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="berat_kernel_utuh" id="berat_kernel_utuh"
                            value="{{ old('berat_kernel_utuh') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('berat_kernel_utuh') border-red-400 bg-red-50 @enderror">
                        @error('berat_kernel_utuh')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="berat_kernel_pecah" class="block text-sm font-medium text-gray-700 mb-2">Berat
                            Kernel Pecah <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="berat_kernel_pecah" id="berat_kernel_pecah"
                            value="{{ old('berat_kernel_pecah') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('berat_kernel_pecah') border-red-400 bg-red-50 @enderror">
                        @error('berat_kernel_pecah')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="berat_cangkang" class="block text-sm font-medium text-gray-700 mb-2">Berat Cangkang
                            <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="berat_cangkang" id="berat_cangkang"
                            value="{{ old('berat_cangkang') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('berat_cangkang') border-red-400 bg-red-50 @enderror">
                        @error('berat_cangkang')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="berat_batu" class="block text-sm font-medium text-gray-700 mb-2">Berat Batu <span
                                class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="berat_batu" id="berat_batu"
                            value="{{ old('berat_batu') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('berat_batu') border-red-400 bg-red-50 @enderror">
                        @error('berat_batu')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="moisture" class="block text-sm font-medium text-gray-700 mb-2">Moisture <span
                                class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="moisture" id="moisture" value="{{ old('moisture') }}"
                            placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('moisture') border-red-400 bg-red-50 @enderror">
                        @error('moisture')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="ampere_screw" class="block text-sm font-medium text-gray-700 mb-2">Ampere Screw
                            <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="ampere_screw" id="ampere_screw"
                            value="{{ old('ampere_screw') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('ampere_screw') border-red-400 bg-red-50 @enderror">
                        @error('ampere_screw')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="tekanan_hydraulic" class="block text-sm font-medium text-gray-700 mb-2">Tekanan
                            Hydraulic <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="tekanan_hydraulic" id="tekanan_hydraulic"
                            value="{{ old('tekanan_hydraulic') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('tekanan_hydraulic') border-red-400 bg-red-50 @enderror">
                        @error('tekanan_hydraulic')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="kecepatan_screw" class="block text-sm font-medium text-gray-700 mb-2">Kecepatan
                            Screw <span class="text-red-500">*</span></label>
                        <input type="number" step="0.0001" name="kecepatan_screw" id="kecepatan_screw"
                            value="{{ old('kecepatan_screw') }}" placeholder="0.0000"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('kecepatan_screw') border-red-400 bg-red-50 @enderror">
                        @error('kecepatan_screw')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('kernel.qwt.index') }}"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">Batal</a>
                <button type="button" id="resetBtn"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">Reset
                    Form</button>
                <button type="submit" onclick="document.getElementById('formAction').value='save'"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">Simpan
                    &amp; Selesai</button>
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
            const form = document.getElementById('kernelQwtForm');
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

        function buildQwtAlertHtml() {
            const kodeValue = getInputValue('kode', '');
            const kode = getSelectText('kode');
            const jenis = getSelectText('jenis');
            const operator = getInputValue('operator');
            const sampelBoy = getInputValue('sampel_boy');
            const sampelSetelahKuarter = parseInputNumber('sampel_setelah_kuarter');
            const beratNutUtuh = parseInputNumber('berat_nut_utuh');
            const beratNutPecah = parseInputNumber('berat_nut_pecah');
            const beratKernelUtuh = parseInputNumber('berat_kernel_utuh');
            const beratKernelPecah = parseInputNumber('berat_kernel_pecah');
            const beratCangkang = parseInputNumber('berat_cangkang');
            const beratBatu = parseInputNumber('berat_batu');
            const moisture = parseInputNumber('moisture');
            const ampereScrew = parseInputNumber('ampere_screw');
            const tekananHydraulic = parseInputNumber('tekanan_hydraulic');
            const kecepatanScrew = parseInputNumber('kecepatan_screw');

            const totalBeratNut = beratNutUtuh + beratNutPecah + beratKernelUtuh + beratKernelPecah + beratCangkang;
            const beratFiber = sampelSetelahKuarter - totalBeratNut;
            const beratBrokenNut = beratNutPecah + beratKernelUtuh + beratKernelPecah + beratCangkang;
            const bnTn = totalBeratNut > 0 ? (beratBrokenNut / totalBeratNut) * 100 : 0;

            const limitConfig = kernelLimitMap?.[kodeValue] ?? null;
            const bnTnStatus = evaluateLimit(bnTn, limitConfig?.bn_tn ?? null);
            const moistStatus = evaluateLimit(moisture, limitConfig?.moist ?? null);
            const bnTnClass = bnTnStatus.isGood === true ? 'text-green-700' : (bnTnStatus.isGood === false ? 'text-red-700' : 'text-indigo-900');
            const moistClass = moistStatus.isGood === true ? 'text-green-700' : (moistStatus.isGood === false ? 'text-red-700' : 'text-indigo-900');

            return '<div class="text-left">' +
                '<p class="text-sm text-gray-600 mb-3">Pastikan data yang Anda masukkan sudah benar.</p>' +
                '<div class="rounded-lg border border-gray-200 bg-gray-50 p-3 mb-3">' +
                '<h4 class="text-sm font-semibold text-gray-900 mb-2">Data Input</h4>' +
                '<ul class="text-sm text-gray-800 space-y-1">' +
                '<li><strong>Kode:</strong> ' + kode + '</li>' +
                '<li><strong>Jenis:</strong> ' + jenis + '</li>' +
                '<li><strong>Operator:</strong> ' + operator + '</li>' +
                '<li><strong>Sampel Boy:</strong> ' + sampelBoy + '</li>' +
                '<li><strong>Sampel Setelah Kuarter:</strong> ' + formatNumber(sampelSetelahKuarter) + ' gram</li>' +
                '<li><strong>Berat Nut Utuh:</strong> ' + formatNumber(beratNutUtuh) + ' gram</li>' +
                '<li><strong>Berat Nut Pecah:</strong> ' + formatNumber(beratNutPecah) + ' gram</li>' +
                '<li><strong>Berat Kernel Utuh:</strong> ' + formatNumber(beratKernelUtuh) + ' gram</li>' +
                '<li><strong>Berat Kernel Pecah:</strong> ' + formatNumber(beratKernelPecah) + ' gram</li>' +
                '<li><strong>Berat Cangkang:</strong> ' + formatNumber(beratCangkang) + ' gram</li>' +
                '<li><strong>Berat Batu:</strong> ' + formatNumber(beratBatu) + ' gram</li>' +
                '<li><strong>Moisture:</strong> ' + formatPercent(moisture) + '</li>' +
                '<li><strong>Ampere Screw:</strong> ' + formatNumber(ampereScrew) + '</li>' +
                '<li><strong>Tekanan Hydraulic:</strong> ' + formatNumber(tekananHydraulic) + '</li>' +
                '<li><strong>Kecepatan Screw:</strong> ' + formatNumber(kecepatanScrew) + '</li>' +
                '</ul>' +
                '</div>' +
                '<div class="rounded-lg border border-indigo-200 bg-indigo-50 p-3">' +
                '<h4 class="text-sm font-semibold text-indigo-900 mb-2">Hasil Perhitungan QWT Fibre Press</h4>' +
                '<ul class="text-sm text-indigo-900 space-y-1">' +
                '<li><strong>Berat Fiber:</strong> ' + formatNumber(beratFiber) + ' gram</li>' +
                '<li><strong>Berat Broken Nut:</strong> ' + formatNumber(beratBrokenNut) + ' gram</li>' +
                '<li><strong>Total Berat Nut:</strong> ' + formatNumber(totalBeratNut) + ' gram</li>' +
                '<li class="' + bnTnClass + '"><strong>BN / TN:</strong> ' + formatPercent(bnTn) + ' <span class="text-xs text-indigo-700">(Limit: ' + bnTnStatus.label + ')</span></li>' +
                '<li class="' + moistClass + '"><strong>Moisture:</strong> ' + formatPercent(moisture) + ' <span class="text-xs text-indigo-700">(Limit: ' + moistStatus.label + ')</span></li>' +
                '</ul>' +
                '</div>' +
                '</div>';
        }

        async function handleFormSubmit(event, form) {
            event.preventDefault();
            const confirmed = await window.confirmSave(form, {
                title: 'Simpan Data QWT Fibre Press?',
                html: buildQwtAlertHtml()
            });
            if (confirmed) {
                form.submit();
            }
            return false;
        }
    </script>

</x-layouts.app>