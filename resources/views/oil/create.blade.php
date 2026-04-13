<x-layouts.app title="Input Data Oil Losses Oil Losses">

    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    {{-- Custom Select2 Styling to Match Standard Inputs --}}
    <style>
        /* Match Select2 container with Tailwind input styling */
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
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important;
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
        
        /* Dropdown styling */
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
            background-color: #3b82f6 !important;
        }
        
        /* Match error state */
        .select2-container--default.border-red-400 .select2-selection--single {
            border-color: #f87171 !important;
            background-color: #fef2f2 !important;
        }
    </style>

    {{-- Form Input Data Oil Losses dengan Dual-Mode --}}
    <x-ui.card title="Input Data Oil Losses Oil Losses">
        <form action="{{ route('oil.store') }}" method="POST" id="labForm" class="space-y-6" onsubmit="return handleFormSubmit(event, this)">
            @csrf

            {{-- Info: Tanggal & Jam Otomatis --}}
            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-indigo-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-indigo-900 mb-1">⏰ Waktu Input Otomatis</h4>
                        <p class="text-sm text-indigo-800">
                            Tanggal & Jam akan diisi otomatis saat Anda klik <strong>Simpan Data</strong> </p>
                        <div class="mt-2 p-3 bg-white rounded border border-indigo-200">
                            <div class="text-xs text-indigo-700">Waktu Saat Ini (Preview):</div>
                            <div class="text-lg font-bold text-indigo-900" id="currentDateTime">
                                {{ now()->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-gray-200">

            {{-- MODE 1: Non-Angka (Bisa Multiple per Day) --}}
            <div id="mode1Section" class="border-2 border-blue-200 bg-blue-50 rounded-lg p-5">
                <div class="flex items-center mb-4">
                    <div
                        class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">
                        1
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Mode Non-Angka <span class="text-sm text-gray-600 font-normal">(Bisa input berkali-kali per hari)</span>
                    </h3>
                </div>

                {{-- Info Alert --}}
                <div class="mb-4 p-3 bg-blue-100 border border-blue-200 rounded-lg text-xs text-blue-800">
                    <strong>💡 Tips:</strong> Jika Anda mengisi <strong>Kode</strong> atau <strong>Operator</strong>, maka keduanya harus diisi.
                    <br><strong>Jenis</strong> dan <strong>Sampel Boy</strong> sudah terisi otomatis.
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode <span class="text-gray-400 text-xs">(wajib bersama Operator)</span>
                        </label>
                        <select name="kode" id="kode" class="w-full select2-kode
                                @error('kode') border-red-400 bg-red-50 @enderror">
                            <option value="">-- Pilih atau ketik untuk cari --</option>
                            @foreach($kodeOptions as $kodeValue => $kodeLabel)
                                <option value="{{ $kodeValue }}" {{ old('kode') == $kodeValue ? 'selected' : '' }}>
                                    {{ $kodeLabel }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Format tampilan: Kode - Description. Description hanya untuk informasi.</p>
                        @error('kode')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis (Searchable Dropdown) --}}
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis <span class="text-gray-400 text-xs">(Default: TBS)</span>
                        </label>
                        <select name="jenis" id="jenis" class="w-full select2-jenis
                                @error('jenis') border-red-400 bg-red-50 @enderror">
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

                    {{-- Operator & Sampel Boy --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="operator" class="block text-sm font-medium text-gray-700 mb-2">
                                Operator <span class="text-gray-400 text-xs">(wajib bersama Kode)</span>
                            </label>
                            @if(!empty($operatorOptions))
                                <select name="operator" id="operator" class="w-full select2-operator
                                    @error('operator') border-red-400 bg-red-50 @enderror">
                                    <option value="">-- Pilih Operator --</option>
                                    @foreach($operatorOptions as $operatorName)
                                        <option value="{{ $operatorName }}" {{ old('operator') == $operatorName ? 'selected' : '' }}>
                                            {{ $operatorName }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Daftar operator mengikuti office {{ Auth::user()->office ?? '-' }}.</p>
                            @else
                                <input type="text" name="operator" id="operator"
                                    value="{{ old('operator') }}" placeholder="Nama operator"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500
                                           @error('operator') border-red-400 bg-red-50 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Dropdown operator belum tersedia untuk office {{ Auth::user()->office ?? '-' }}.</p>
                            @endif
                            @error('operator')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sampel_boy" class="block text-sm font-medium text-gray-700 mb-2">
                                Sampel Boy <span class="text-gray-400 text-xs">(Otomatis)</span>
                            </label>
                            <input type="text" name="sampel_boy" id="sampel_boy" 
                                value="{{ Auth::user()->name }}"
                                data-default-value="{{ Auth::user()->name }}"
                                readonly
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100 cursor-not-allowed
                                       @error('sampel_boy') border-red-400 bg-red-50 @enderror">
                            @error('sampel_boy')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="mode1RowsContainer" class="space-y-4"></div>

                    <div>
                        <button type="button" id="addMode1Row"
                            class="px-4 py-2 border border-blue-300 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                            + Add Form Mode Non-Angka
                        </button>
                    </div>
                </div>
            </div>

            {{-- MODE 2: Angka (Phase-Based) --}}
            <div id="mode2Section" class="border-2 border-green-200 bg-green-50 rounded-lg p-5">
                <div class="flex items-center mb-4">
                    <div
                        class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-bold mr-3">
                        2
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Mode Angka <span class="text-sm text-gray-600 font-normal">(Tahap 1, Tahap 2, lalu Tahap Akhir)</span>
                    </h3>
                </div>

                <div class="space-y-4">
                    <div class="text-sm text-green-800 bg-green-100 border border-green-300 rounded-lg p-3">
                        <p>
                            ℹ️ <strong>Perhatian:</strong> Urutan input: <strong>Tahap 1</strong> (Cawan Kosong, Berat Sampel Basah, Labu Kosong), <strong>Tahap 2</strong> (Cawan + Sampel Kering), lalu <strong>Tahap Akhir</strong> (Oil + Labu). Tahap terdeteksi otomatis dari field yang diisi.
                        </p>
                    </div>

                    {{-- Kode Selection for Mode 2 --}}
                    <div>
                        <label for="kode_mode2" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <select name="kode_mode2" id="kode_mode2" class="w-full select2-kode
                                   @error('kode_mode2') border-red-400 bg-red-50 @enderror">
                            <option value="">-- Pilih Kode --</option>
                            @foreach ($kodeOptions as $kodeValue => $kodeName)
                                <option value="{{ $kodeValue }}" {{ old('kode_mode2') == $kodeValue ? 'selected' : '' }}>
                                    {{ $kodeName }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Format tampilan: Kode - Description. Description hanya untuk informasi.</p>
                        @error('kode_mode2')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="initialPhaseFields">
                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-green-700">Tahap 1</div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="cawan_kosong" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cawan Kosong
                                </label>
                                <input type="number" step="0.000001" name="cawan_kosong" id="cawan_kosong"
                                    value="{{ old('cawan_kosong') }}" placeholder="0.000000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500
                                           @error('cawan_kosong') border-red-400 bg-red-50 @enderror">
                                @error('cawan_kosong')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="berat_basah" class="block text-sm font-medium text-gray-700 mb-2">
                                    Berat Sampel Basah
                                </label>
                                <input type="number" step="0.000001" name="berat_basah" id="berat_basah"
                                    value="{{ old('berat_basah') }}" placeholder="0.000000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500
                                           @error('berat_basah') border-red-400 bg-red-50 @enderror">
                                @error('berat_basah')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="labu_kosong" class="block text-sm font-medium text-gray-700 mb-2">
                                    Labu Kosong
                                </label>
                                <input type="number" step="0.000001" name="labu_kosong" id="labu_kosong"
                                    value="{{ old('labu_kosong') }}" placeholder="0.000000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500
                                           @error('labu_kosong') border-red-400 bg-red-50 @enderror">
                                @error('labu_kosong')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="middlePhaseFields">
                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-green-700">Tahap 2</div>
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            <div>
                                <label for="cawan_sample_kering" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cawan + Sample Kering
                                </label>
                                <input type="number" step="0.000001" name="cawan_sample_kering" id="cawan_sample_kering"
                                    value="{{ old('cawan_sample_kering') }}" placeholder="0.000000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500
                                           @error('cawan_sample_kering') border-red-400 bg-red-50 @enderror">
                                @error('cawan_sample_kering')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="finalPhaseFields">
                        <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-green-700">Tahap Akhir</div>
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            <div>
                                <label for="oil_labu" class="block text-sm font-medium text-gray-700 mb-2">
                                    Oil + Labu
                                </label>
                                <input type="number" step="0.000001" name="oil_labu" id="oil_labu" value="{{ old('oil_labu') }}"
                                    placeholder="0.000000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500
                                           @error('oil_labu') border-red-400 bg-red-50 @enderror">
                                @error('oil_labu')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="mode2RowsContainer" class="space-y-4"></div>

                    <div>
                        <button type="button" id="addMode2Row"
                            class="px-4 py-2 border border-green-300 text-green-700 rounded-lg hover:bg-green-100 transition text-sm font-medium">
                            + Add Form Mode Angka
                        </button>
                    </div>
                </div>
            </div>

            <template id="mode1RowTemplate">
                <div class="mode1-row border border-blue-200 bg-white rounded-lg p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-blue-900">Mode Non-Angka Tambahan</h4>
                        <button type="button" class="remove-mode1-row text-xs text-red-600 hover:text-red-800">Hapus</button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode</label>
                        <select name="mode1_rows[__INDEX__][kode]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">-- Pilih Kode --</option>
                            @foreach($kodeOptions as $kodeValue => $kodeLabel)
                                <option value="{{ $kodeValue }}">{{ $kodeLabel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                            <select name="mode1_rows[__INDEX__][jenis]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                @foreach($jenisOptions as $jenisValue => $jenisLabel)
                                    <option value="{{ $jenisValue }}" {{ $jenisValue === 'TBS' ? 'selected' : '' }}>{{ $jenisLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Operator</label>
                            @if(!empty($operatorOptions))
                                <select name="mode1_rows[__INDEX__][operator]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="">-- Pilih Operator --</option>
                                    @foreach($operatorOptions as $operatorName)
                                        <option value="{{ $operatorName }}">{{ $operatorName }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" name="mode1_rows[__INDEX__][operator]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Nama operator">
                            @endif
                        </div>
                    </div>
                </div>
            </template>

            <template id="mode2RowTemplate">
                <div class="mode2-row border-2 border-green-200 bg-white rounded-lg p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-green-900">Mode Angka Tambahan</h4>
                        <button type="button" class="remove-mode2-row text-xs text-red-600 hover:text-red-800">Hapus</button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode</label>
                        <select name="mode2_rows[__INDEX__][kode_mode2]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">-- Pilih Kode --</option>
                            @foreach($kodeOptions as $kodeValue => $kodeName)
                                <option value="{{ $kodeValue }}">{{ $kodeName }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Format tampilan: Kode - Description. Description hanya untuk informasi.</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-green-700">Tahap 1</div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cawan Kosong</label>
                                    <input type="number" step="0.000001" name="mode2_rows[__INDEX__][cawan_kosong]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="0.000000">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Berat Sampel Basah</label>
                                    <input type="number" step="0.000001" name="mode2_rows[__INDEX__][berat_basah]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="0.000000">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Labu Kosong</label>
                                    <input type="number" step="0.000001" name="mode2_rows[__INDEX__][labu_kosong]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="0.000000">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-green-700">Tahap 2</div>
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cawan + Sample Kering</label>
                                    <input type="number" step="0.000001" name="mode2_rows[__INDEX__][cawan_sample_kering]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="0.000000">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-3 text-xs font-semibold uppercase tracking-wide text-green-700">Tahap Akhir</div>
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Oil + Labu</label>
                                    <input type="number" step="0.000001" name="mode2_rows[__INDEX__][oil_labu]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="0.000000">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('oil.index') }}"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                    Batal
                </a>

                <button type="reset"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                    Reset Form
                </button>

                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm">
                    <svg class="w-5 h-5 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </x-ui.card>

    {{-- Select2 JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for Kode dropdown
            $('.select2-kode').select2({
                placeholder: '-- Pilih atau ketik untuk cari --',
                allowClear: true,
                width: '100%',
                theme: 'default',
                language: {
                    noResults: function () {
                        return "Tidak ditemukan";
                    },
                    searching: function () {
                        return "Mencari...";
                    }
                }
            });

            // Initialize Select2 for Jenis dropdown
            $('.select2-jenis').select2({
                placeholder: 'TBS (Default)',
                allowClear: false,
                width: '100%',
                theme: 'default',
                language: {
                    noResults: function () {
                        return "Tidak ditemukan";
                    },
                    searching: function () {
                        return "Mencari...";
                    }
                }
            });

            // Initialize Select2 for Operator dropdown
            $('.select2-operator').select2({
                placeholder: '-- Pilih Operator --',
                allowClear: true,
                width: '100%',
                theme: 'default',
                language: {
                    noResults: function () {
                        return "Tidak ditemukan";
                    },
                    searching: function () {
                        return "Mencari...";
                    }
                }
            });

            // Live Clock - Update current date/time display every second
            function updateClock() {
                const now = new Date();
                const options = {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                };
                const formattedTime = now.toLocaleString('id-ID', options).replace(',', '');
                const clockEl = document.getElementById('currentDateTime');
                if (clockEl) {
                    clockEl.textContent = formattedTime;
                }
            }

            // Update clock immediately and then every second
            updateClock();
            setInterval(updateClock, 1000);

            function detectMode2Phase() {
                const hasStep1 = ['cawan_kosong', 'berat_basah', 'labu_kosong'].some((field) => {
                    const value = $(`#${field}`).val();
                    return value && value.trim() !== '';
                });

                const hasStep2 = (() => {
                    const value = $('#cawan_sample_kering').val();
                    return value && value.trim() !== '';
                })();

                const hasFinal = (() => {
                    const value = $('#oil_labu').val();
                    return value && value.trim() !== '';
                })();

                if (hasFinal) {
                    return 'complete';
                }

                if (hasStep2) {
                    return 'final';
                }

                if (hasStep1) {
                    return 'initial';
                }

                return 'initial';
            }

            function getPhase() {
                return detectMode2Phase();
            }

            function togglePhaseFields() {
                const phase = getPhase();
                const initialFields = $('#initialPhaseFields').find('input');
                const middleFields = $('#middlePhaseFields').find('input');
                const finalFields = $('#finalPhaseFields').find('input');

                // Jangan lock field: user bebas isi awal/akhir, phase dideteksi otomatis.
                initialFields.prop('disabled', false);
                middleFields.prop('disabled', false);
                finalFields.prop('disabled', false);

                $('#initialPhaseFields, #middlePhaseFields, #finalPhaseFields').removeClass('ring-2 ring-green-200');
                if (phase === 'initial') {
                    $('#initialPhaseFields').addClass('ring-2 ring-green-200');
                } else if (phase === 'final') {
                    $('#middlePhaseFields').addClass('ring-2 ring-green-200');
                } else {
                    $('#initialPhaseFields, #middlePhaseFields, #finalPhaseFields').addClass('ring-2 ring-green-200');
                }
            }

            $('#initialPhaseFields input, #middlePhaseFields input, #finalPhaseFields input').on('input change', togglePhaseFields);
            togglePhaseFields();

            // Form validation: Jika 1 field di mode diisi, semua field di mode tersebut WAJIB diisi
            $('#labForm').on('submit', function(e) {
                let hasError = false;
                let errorMessages = [];
                const phase = getPhase();

                // Mode 1 validation
                // HANYA cek field yang user bisa edit: kode dan operator
                // Jenis dan Sampel Boy diabaikan karena punya default value
                const mode1UserFields = {
                    'kode': 'Kode',
                    'operator': 'Operator'
                };

                const mode1Values = Object.keys(mode1UserFields).map(field => {
                    const value = $(`#${field}`).val();
                    return value && value.trim() !== '';
                });

                const mode1HasAnyValue = mode1Values.some(v => v === true);

                // Jika user mengisi kode atau operator, maka keduanya harus diisi
                if (mode1HasAnyValue) {
                    Object.keys(mode1UserFields).forEach(field => {
                        const value = $(`#${field}`).val();
                        if (!value || value.trim() === '') {
                            errorMessages.push(`${mode1UserFields[field]} wajib diisi (Mode Non-Angka)`);
                            $(`#${field}`).addClass('border-red-400 bg-red-50');
                            hasError = true;
                        } else {
                            $(`#${field}`).removeClass('border-red-400 bg-red-50');
                        }
                    });
                    
                    // Jenis dan Sampel Boy selalu valid karena sudah ada default
                    $('#jenis').removeClass('border-red-400 bg-red-50');
                    $('#sampel_boy').removeClass('border-red-400 bg-red-50');
                }

                // Mode 2 validation
                const mode2Fields = {
                    'kode_mode2': 'Kode',
                    'cawan_kosong': 'Cawan Kosong',
                    'berat_basah': 'Berat Basah',
                    'labu_kosong': 'Labu Kosong',
                    'cawan_sample_kering': 'Cawan + Sample Kering',
                    'oil_labu': 'Oil + Labu'
                };

                const phaseFields = phase === 'initial'
                    ? ['kode_mode2', 'cawan_kosong', 'berat_basah', 'labu_kosong']
                    : phase === 'final'
                        ? ['kode_mode2', 'cawan_sample_kering']
                        : ['kode_mode2', 'oil_labu'];

                const mode2Values = phaseFields.map(field => {
                    const value = $(`#${field}`).val();
                    return value && value.trim() !== '';
                });

                const mode2HasAnyValue = mode2Values.some(v => v === true);

                if (mode2HasAnyValue) {
                    phaseFields.forEach(field => {
                        const value = $(`#${field}`).val();
                        if (!value || value.trim() === '') {
                            errorMessages.push(`${mode2Fields[field]} wajib diisi (Mode Angka)`);
                            $(`#${field}`).addClass('border-red-400 bg-red-50');
                            hasError = true;
                        } else {
                            $(`#${field}`).removeClass('border-red-400 bg-red-50');
                        }
                    });
                }

                // Mode 2 tambahan validation (per-row, phase-based)
                const mode2Rows = document.querySelectorAll('.mode2-row');
                mode2Rows.forEach((rowEl, idx) => {
                    const rowNo = idx + 1;

                    const getVal = (field) => {
                        const input = rowEl.querySelector(`[name$="[${field}]"]`);
                        return input ? String(input.value || '').trim() : '';
                    };

                    const kode = getVal('kode_mode2');
                    const cawanKosong = getVal('cawan_kosong');
                    const beratBasah = getVal('berat_basah');
                    const cawanSampleKering = getVal('cawan_sample_kering');
                    const labuKosong = getVal('labu_kosong');
                    const oilLabu = getVal('oil_labu');

                    const hasStep1 = cawanKosong !== '' || beratBasah !== '' || labuKosong !== '';
                    const hasStep2 = cawanSampleKering !== '';
                    const hasFinal = oilLabu !== '';
                    const hasAnyNumeric = hasStep1 || hasStep2 || hasFinal;

                    if (!hasAnyNumeric && kode === '') {
                        return;
                    }

                    if (kode === '') {
                        errorMessages.push(`Mode Angka Tambahan #${rowNo}: Kode wajib diisi jika ada input angka`);
                        hasError = true;
                        return;
                    }

                    const rowPhase = hasFinal ? 'complete' : (hasStep2 ? 'final' : 'initial');

                    if (rowPhase === 'initial') {
                        if (cawanKosong === '' || beratBasah === '' || labuKosong === '') {
                            errorMessages.push(`Mode Angka Tambahan #${rowNo}: Tahap 1 harus lengkap (Cawan Kosong, Berat Sampel Basah, Labu Kosong)`);
                            hasError = true;
                        }
                    } else if (rowPhase === 'final') {
                        if (cawanSampleKering === '') {
                            errorMessages.push(`Mode Angka Tambahan #${rowNo}: Tahap 2 membutuhkan Cawan + Sample Kering`);
                            hasError = true;
                        }
                    } else {
                        if (oilLabu === '') {
                            errorMessages.push(`Mode Angka Tambahan #${rowNo}: Tahap Akhir membutuhkan Oil + Labu`);
                            hasError = true;
                        }
                    }
                });

                // Tampilkan error jika ada
                if (hasError) {
                    e.preventDefault();
                    
                    // Buat alert error
                    const errorHtml = `
                        <div id="validation-error" class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-red-900 mb-2">⚠️ Data Tidak Lengkap</h4>
                                    <p class="text-sm text-red-800 mb-2">Jika Anda mengisi <strong>Kode</strong> atau <strong>Operator</strong>, maka keduanya harus diisi:</p>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `;

                    // Hapus error lama jika ada
                    $('#validation-error').remove();
                    
                    // Tambahkan error di atas form
                    $('#labForm').before(errorHtml);
                    
                    // Scroll ke atas untuk melihat error
                    $('html, body').animate({
                        scrollTop: $('#validation-error').offset().top - 100
                    }, 300);

                    return false;
                }
            });
        });
    </script>

    <script>
        // Confirmation handler for form submission
        let mode1RowIndex = 0;
        let mode2RowIndex = 0;

        function bindDynamicRows() {
            const mode1Container = document.getElementById('mode1RowsContainer');
            const mode2Container = document.getElementById('mode2RowsContainer');
            const mode1Template = document.getElementById('mode1RowTemplate');
            const mode2Template = document.getElementById('mode2RowTemplate');

            document.getElementById('addMode1Row')?.addEventListener('click', function () {
                const html = mode1Template.innerHTML.replaceAll('__INDEX__', String(mode1RowIndex++));
                mode1Container.insertAdjacentHTML('beforeend', html);
            });

            document.getElementById('addMode2Row')?.addEventListener('click', function () {
                const html = mode2Template.innerHTML.replaceAll('__INDEX__', String(mode2RowIndex++));
                mode2Container.insertAdjacentHTML('beforeend', html);
            });

            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-mode1-row')) {
                    event.target.closest('.mode1-row')?.remove();
                }
                if (event.target.classList.contains('remove-mode2-row')) {
                    event.target.closest('.mode2-row')?.remove();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', bindDynamicRows);

        async function handleFormSubmit(event, form) {
            event.preventDefault();
            const confirmed = await window.confirmSave(form); // Pass form element untuk offline save
            if (confirmed) {
                if (typeof window.lockFormSubmission === 'function') {
                    const submitter = event.submitter || form.querySelector('button[type="submit"], input[type="submit"]');
                    window.lockFormSubmission(form, submitter);
                }
                form.submit();
            }
            return false;
        }
    </script>

</x-layouts.app>

