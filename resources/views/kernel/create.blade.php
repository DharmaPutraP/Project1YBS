<x-layouts.app title="Input Data Kernel Losses">

    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container--default .select2-selection--single {
            height: auto !important;
            padding: 0.5rem 1rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            transition: all 0.15s !important;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            outline: none !important;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.5) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0 !important;
            line-height: inherit !important;
            color: #374151 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 0.75rem !important;
        }

        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

        .select2-results__option {
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
        }

        .select2-results__option--highlighted {
            background-color: #6366f1 !important;
        }
    </style>

    <x-ui.card title="Input Data Kernel Losses">

        {{-- Waktu Otomatis --}}
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
                        Tanggal &amp; jam akan diisi otomatis saat Anda klik <strong>Simpan Data</strong>.
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

        <form action="{{ route('kernel.store') }}" method="POST" id="kernelForm" class="space-y-5"
            onsubmit="return handleFormSubmit(event, this)">
            @csrf
            <input type="hidden" name="_action" id="formAction" value="save">

            <div class="mt-10 border-2 border-blue-200 bg-blue-50 rounded-lg p-5">
                {{-- Row 1: Kode & Jenis --}}
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 ">
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <select name="kode" id="kode"
                            class="w-full select2-kode @error('kode') border-red-400 bg-red-50 @enderror">
                            <option value="">-- Pilih Kode --</option>
                            @foreach($kodeOptions as $kodeValue => $kodeLabel)
                                <option value="{{ $kodeValue }}" {{ old('kode') == $kodeValue ? 'selected' : '' }}>
                                    {{ $kodeLabel }}
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
                            class="w-full select2-jenis @error('jenis') border-red-400 bg-red-50 @enderror">
                            @foreach($jenisOptions as $jenisValue => $jenisLabel)
                                <option value="{{ $jenisValue }}" {{ (old('jenis', 'TBS') == $jenisValue) ? 'selected' : '' }}>
                                    {{ $jenisLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Row 2: Operator & Sampel Boy --}}
                <div class=" mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="operator" class="block text-sm font-medium text-gray-700 mb-2">
                            Operator <span class="text-red-500">*</span>
                        </label>
                        @if(!empty($operatorOptions))
                            <select name="operator" id="operator" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500
                                           @error('operator') border-red-400 bg-red-50 @enderror">
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
                                placeholder="Nama operator" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500
                                       @error('operator') border-red-400 bg-red-50 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Dropdown operator belum tersedia untuk office
                                {{ Auth::user()->office ?? '-' }}.</p>
                        @endif
                        @error('operator')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sampel_boy" class="block text-sm font-medium text-gray-700 mb-2">
                            Sampel Boy <span class="text-gray-400 text-xs">(Otomatis)</span>
                        </label>
                        <input type="text" name="sampel_boy" id="sampel_boy" value="{{ Auth::user()->name }}" readonly
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100 cursor-not-allowed">
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Row 3: Berat Sampel --}}
                <div class="mt-4 md:w-1/2">
                    <label for="berat_sampel" class="block text-sm font-medium text-gray-700 mb-2">
                        Berat Sampel <span class="text-red-500">*</span>
                        <span class="text-gray-400 text-xs">(gram)</span>
                    </label>
                    <input type="number" step="0.0001" name="berat_sampel" id="berat_sampel"
                        value="{{ old('berat_sampel') }}" placeholder="0.0000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500
                           @error('berat_sampel') border-red-400 bg-red-50 @enderror">
                    @error('berat_sampel')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Row 4: Nut Utuh --}}
                <div class="mt-4">
                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Nut Utuh</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nut_utuh_nut" class="block text-sm font-medium text-gray-700 mb-2">
                                Nut <span class="text-red-500">*</span>
                                <span class="text-gray-400 text-xs">(gram)</span>
                            </label>
                            <input type="number" step="0.0001" name="nut_utuh_nut" id="nut_utuh_nut"
                                value="{{ old('nut_utuh_nut') }}" placeholder="0.0000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   @error('nut_utuh_nut') border-red-400 bg-red-50 @enderror">
                            @error('nut_utuh_nut')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nut_utuh_kernel" class="block text-sm font-medium text-gray-700 mb-2">
                                Kernel <span class="text-red-500">*</span>
                                <span class="text-gray-400 text-xs">(gram)</span>
                            </label>
                            <input type="number" step="0.0001" name="nut_utuh_kernel" id="nut_utuh_kernel"
                                value="{{ old('nut_utuh_kernel') }}" placeholder="0.0000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   @error('nut_utuh_kernel') border-red-400 bg-red-50 @enderror">
                            @error('nut_utuh_kernel')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Row 5: Nut Pecah --}}
                <div class="mt-4">
                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Nut Pecah</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nut_pecah_nut" class="block text-sm font-medium text-gray-700 mb-2">
                                Nut <span class="text-red-500">*</span>
                                <span class="text-gray-400 text-xs">(gram)</span>
                            </label>
                            <input type="number" step="0.0001" name="nut_pecah_nut" id="nut_pecah_nut"
                                value="{{ old('nut_pecah_nut') }}" placeholder="0.0000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   @error('nut_pecah_nut') border-red-400 bg-red-50 @enderror">
                            @error('nut_pecah_nut')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nut_pecah_kernel" class="block text-sm font-medium text-gray-700 mb-2">
                                Kernel <span class="text-red-500">*</span>
                                <span class="text-gray-400 text-xs">(gram)</span>
                            </label>
                            <input type="number" step="0.0001" name="nut_pecah_kernel" id="nut_pecah_kernel"
                                value="{{ old('nut_pecah_kernel') }}" placeholder="0.0000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500
                                   @error('nut_pecah_kernel') border-red-400 bg-red-50 @enderror">
                            @error('nut_pecah_kernel')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Row 6: Kernel Utuh & Kernel Pecah --}}
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="kernel_utuh" class="block text-sm font-medium text-gray-700 mb-2">
                            Kernel Utuh <span class="text-red-500">*</span>
                            <span class="text-gray-400 text-xs">(gram)</span>
                        </label>
                        <input type="number" step="0.0001" name="kernel_utuh" id="kernel_utuh"
                            value="{{ old('kernel_utuh') }}" placeholder="0.0000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500
                               @error('kernel_utuh') border-red-400 bg-red-50 @enderror">
                        @error('kernel_utuh')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kernel_pecah" class="block text-sm font-medium text-gray-700 mb-2">
                            Kernel Pecah <span class="text-red-500">*</span>
                            <span class="text-gray-400 text-xs">(gram)</span>
                        </label>
                        <input type="number" step="0.0001" name="kernel_pecah" id="kernel_pecah"
                            value="{{ old('kernel_pecah') }}" placeholder="0.0000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500
                               @error('kernel_pecah') border-red-400 bg-red-50 @enderror">
                        @error('kernel_pecah')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('kernel.index') }}"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                    Batal
                </a>
                <button type="button" id="resetBtn"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                    Reset Form
                </button>
                <button type="submit" id="btnSave" onclick="document.getElementById('formAction').value='save'"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">
                    <svg class="w-5 h-5 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan &amp; Selesai
                </button>
            </div>
        </form>
    </x-ui.card>

    {{-- Select2 JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const kernelLimitMap = @json($kernelLimitMap ?? []);

        $(document).ready(function () {
            $('.select2-kode').select2({
                placeholder: '-- Pilih Kode --',
                allowClear: true,
                width: '100%',
                language: { noResults: function () { return "Tidak ditemukan"; } }
            });

            $('.select2-jenis').select2({
                allowClear: false,
                width: '100%',
                language: { noResults: function () { return "Tidak ditemukan"; } }
            });

            // Live clock
            function updateClock() {
                const now = new Date();
                const pad = n => String(n).padStart(2, '0');
                const el = document.getElementById('currentDateTime');
                if (el) el.textContent =
                    pad(now.getDate()) + '/' + pad(now.getMonth() + 1) + '/' + now.getFullYear() + ' ' +
                    pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
            }
            updateClock();
            setInterval(updateClock, 1000);

            // Reset Form
            $('#resetBtn').on('click', function () {
                $('#kernelForm')[0].reset();
                setTimeout(function () {
                    $('.select2-kode').val(null).trigger('change');
                    $('.select2-jenis').val('TBS').trigger('change');
                    $('#validation-error').remove();
                }, 10);
            });

            // Validation
            $('#kernelForm').on('submit', function (e) {
                $('#validation-error').remove();
                let errors = [];
                if (!$('#kode').val()) errors.push('Kode wajib dipilih');
                if (!$('#jenis').val()) errors.push('Jenis wajib dipilih');
                if (!$('#operator').val().trim()) errors.push('Operator wajib diisi');
                if (!$('#berat_sampel').val().trim()) errors.push('Berat Sampel wajib diisi');
                if (!$('#nut_utuh_nut').val().trim()) errors.push('Nut Utuh - Nut wajib diisi');
                if (!$('#nut_utuh_kernel').val().trim()) errors.push('Nut Utuh - Kernel wajib diisi');
                if (!$('#nut_pecah_nut').val().trim()) errors.push('Nut Pecah - Nut wajib diisi');
                if (!$('#nut_pecah_kernel').val().trim()) errors.push('Nut Pecah - Kernel wajib diisi');
                if (!$('#kernel_utuh').val().trim()) errors.push('Kernel Utuh wajib diisi');
                if (!$('#kernel_pecah').val().trim()) errors.push('Kernel Pecah wajib diisi');

                if (errors.length > 0) {
                    e.preventDefault();
                    const html = '<div id="validation-error" class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">' +
                        '<div class="flex items-start">' +
                        '<svg class="w-6 h-6 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' +
                        '<div><h4 class="text-sm font-bold text-red-900 mb-2">Data Tidak Lengkap</h4>' +
                        '<ul class="list-disc list-inside text-sm text-red-700 space-y-1">' +
                        errors.map(m => '<li>' + m + '</li>').join('') +
                        '</ul></div></div></div>';
                    $('#kernelForm').before(html);
                    $('html, body').animate({ scrollTop: $('#validation-error').offset().top - 100 }, 300);
                    return false;
                }
            });
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

        function getKernelLimitStatus(kodeValue, kernelLossesPercent) {
            const limitConfig = kernelLimitMap?.[kodeValue];
            if (!limitConfig || !Number.isFinite(kernelLossesPercent)) {
                return { isGood: null, label: '-' };
            }

            const limitValue = Number(limitConfig.value ?? 0);
            const operator = limitConfig.operator === 'gt' ? 'gt' : 'le';
            const isGood = operator === 'le'
                ? kernelLossesPercent <= limitValue
                : kernelLossesPercent > limitValue;
            const symbol = operator === 'le' ? '≤' : '>';

            return {
                isGood,
                label: symbol + ' ' + formatNumber(limitValue) + '%'
            };
        }

        function buildKernelLossesAlertHtml() {
            const kodeValue = getInputValue('kode', '');
            const kode = getSelectText('kode');
            const jenis = getSelectText('jenis');
            const operator = getInputValue('operator');
            const sampelBoy = getInputValue('sampel_boy');
            const beratSampel = parseInputNumber('berat_sampel');
            const nutUtuhNut = parseInputNumber('nut_utuh_nut');
            const nutUtuhKernel = parseInputNumber('nut_utuh_kernel');
            const nutPecahNut = parseInputNumber('nut_pecah_nut');
            const nutPecahKernel = parseInputNumber('nut_pecah_kernel');
            const kernelUtuh = parseInputNumber('kernel_utuh');
            const kernelPecah = parseInputNumber('kernel_pecah');

            const ktsNutUtuh = beratSampel > 0 ? (nutUtuhKernel / beratSampel) * 100 : 0;
            const ktsNutPecah = beratSampel > 0 ? (nutPecahKernel / beratSampel) * 100 : 0;
            const kernelUtuhPerSampel = beratSampel > 0 ? (kernelUtuh / beratSampel) * 100 : 0;
            const kernelPecahPerSampel = beratSampel > 0 ? (kernelPecah / beratSampel) * 100 : 0;
            const kernelLosses = ktsNutUtuh + ktsNutPecah + kernelUtuhPerSampel + kernelPecahPerSampel;
            const limitStatus = getKernelLimitStatus(kodeValue, kernelLosses);
            const kernelLossesClass = limitStatus.isGood === true
                ? 'text-green-700'
                : (limitStatus.isGood === false ? 'text-red-700' : 'text-indigo-900');

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
                '<li><strong>Nut Utuh - Nut:</strong> ' + formatNumber(nutUtuhNut) + ' gram</li>' +
                '<li><strong>Nut Utuh - Kernel:</strong> ' + formatNumber(nutUtuhKernel) + ' gram</li>' +
                '<li><strong>Nut Pecah - Nut:</strong> ' + formatNumber(nutPecahNut) + ' gram</li>' +
                '<li><strong>Nut Pecah - Kernel:</strong> ' + formatNumber(nutPecahKernel) + ' gram</li>' +
                '<li><strong>Kernel Utuh:</strong> ' + formatNumber(kernelUtuh) + ' gram</li>' +
                '<li><strong>Kernel Pecah:</strong> ' + formatNumber(kernelPecah) + ' gram</li>' +
                '</ul>' +
                '</div>' +
                '<div class="rounded-lg border border-indigo-200 bg-indigo-50 p-3">' +
                '<h4 class="text-sm font-semibold text-indigo-900 mb-2">Hasil Perhitungan Kernel Losses</h4>' +
                '<ul class="text-sm text-indigo-900 space-y-1">' +
                '<li><strong>KTS Nut Utuh:</strong> ' + formatPercent(ktsNutUtuh) + '</li>' +
                '<li><strong>KTS Nut Pecah:</strong> ' + formatPercent(ktsNutPecah) + '</li>' +
                '<li><strong>Kernel Utuh/Sampel:</strong> ' + formatPercent(kernelUtuhPerSampel) + '</li>' +
                '<li><strong>Kernel Pecah/Sampel:</strong> ' + formatPercent(kernelPecahPerSampel) + '</li>' +
                '<li class="pt-1 border-t border-indigo-200 ' + kernelLossesClass + '"><strong>Kernel Losses:</strong> ' + formatPercent(kernelLosses) + '</li>' +
                '<li class="text-xs text-indigo-700"><strong>Limit:</strong> ' + limitStatus.label + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
        }

        async function handleFormSubmit(event, form) {
            event.preventDefault();
            const confirmed = await window.confirmSave(form, {
                title: 'Simpan Data Kernel Losses?',
                html: buildKernelLossesAlertHtml()
            });
            if (confirmed) { form.submit(); }
            return false;
        }
    </script>

</x-layouts.app>