<x-layouts.app title="Input Data Lab Oil Losses">

    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- ── Flash Messages ───────────────────────────────────────────── --}}
    @if (session('error'))
        <div id="flash-error" class="mb-6">
            <x-ui.alert type="error" title="Gagal">{{ session('error') }}</x-ui.alert>
        </div>
    @endif
    @if (session('success'))
        <div id="flash-success" class="mb-6">
            <x-ui.alert type="success" title="Berhasil">{{ session('success') }}</x-ui.alert>
        </div>
    @endif

    {{-- Info Alert: Dual-Mode Input --}}
    <x-ui.card class="mb-6 bg-amber-50 border-amber-200">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h4 class="text-sm font-bold text-amber-900 mb-2">⚠️ Aturan Input Data</h4>
                <p class="text-sm text-amber-800 leading-relaxed">
                    <strong>PILIH SALAH SATU MODE SAJA:</strong><br>
                    <span class="inline-block mt-1">
                        <strong>Mode 1 (Non-Angka):</strong> Isi <strong>Kode</strong> hingga <strong>Parameter
                            Lain</strong> → Bisa input berkali-kali dalam 1 hari
                    </span><br>
                    <span class="inline-block mt-1">
                        <strong>Mode 2 (Angka):</strong> Isi <strong>Cawan Kosong</strong> hingga <strong>Oil
                            Labu</strong> → Hanya 1x per hari (smart shifting otomatis)
                    </span><br>
                    <span class="text-xs text-amber-700 italic mt-2 inline-block">
                        ❌ Tidak boleh isi keduanya sekaligus!
                    </span>
                </p>
            </div>
        </div>
    </x-ui.card>

    {{-- Form Input Data Lab dengan Dual-Mode --}}
    <x-ui.card title="Input Data Lab Oil Losses">
        <form action="{{ route('lab.store') }}" method="POST" id="labForm" class="space-y-6">
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

            {{-- Info: Dual Mode dengan Auto Disable --}}
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-yellow-900 mb-1">🔒 Sistem Auto-Disable</h4>
                        <p class="text-sm text-yellow-800">
                            Form ini memiliki 2 mode input. <strong>Saat Anda mulai mengisi salah satu mode, mode lainnya akan otomatis ter-disable</strong> untuk mencegah kesalahan input.
                        </p>
                        <ul class="mt-2 text-xs text-yellow-700 space-y-1 list-disc list-inside">
                            <li>Isi <strong>Mode 1</strong> → Mode 2 otomatis disabled</li>
                            <li>Isi <strong>Mode 2</strong> → Mode 1 otomatis disabled</li>
                            <li>Hapus semua isian → Kedua mode aktif kembali</li>
                        </ul>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-gray-200">

            {{-- MODE 1: Non-Angka (Bisa Multiple per Day) --}}
            <div id="mode1Section" class="border-2 border-blue-200 bg-blue-50 rounded-lg p-5 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-3">
                            1
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Mode Non-Angka <span class="text-sm text-gray-600 font-normal">(Bisa input berkali-kali)</span>
                        </h3>
                    </div>
                    <span id="mode1Status" class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        SIAP
                    </span>
                </div>

                <div class="mb-4 p-3 bg-blue-100 border border-blue-300 rounded-lg">
                    <p class="text-sm text-blue-800">
                        💡 <strong>Tip:</strong> Jika Anda mulai mengisi mode ini, Mode Angka akan otomatis di-disable untuk menghindari kesalahan input.
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode <span class="text-gray-400 text-xs">(Ketik untuk cari)</span>
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
                        @error('kode')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis (Searchable Dropdown) --}}
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis <span class="text-gray-400 text-xs">(Ketik untuk cari)</span>
                        </label>
                        <select name="jenis" id="jenis" class="w-full select2-jenis
                                @error('jenis') border-red-400 bg-red-50 @enderror">
                            <option value="">-- Pilih atau ketik untuk cari --</option>
                            @foreach($jenisOptions as $jenisValue => $jenisLabel)
                                <option value="{{ $jenisValue }}" {{ old('jenis') == $jenisValue ? 'selected' : '' }}>
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
                                Operator <span class="text-gray-400 text-xs">(opsional)</span>
                            </label>
                            <input type="text" name="operator" id="operator" value="{{ old('operator') }}"
                                placeholder="Nama operator" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500
                                       @error('operator') border-red-400 bg-red-50 @enderror">
                            @error('operator')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sampel_boy" class="block text-sm font-medium text-gray-700 mb-2">
                                Sampel Boy <span class="text-gray-400 text-xs">(opsional)</span>
                            </label>
                            <input type="text" name="sampel_boy" id="sampel_boy" value="{{ old('sampel_boy') }}"
                                placeholder="Nama sampel boy" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500
                                       @error('sampel_boy') border-red-400 bg-red-50 @enderror">
                            @error('sampel_boy')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Parameter Lain --}}
                    <div>
                        <label for="parameter_lain" class="block text-sm font-medium text-gray-700 mb-2">
                            Parameter Lain <span class="text-gray-400 text-xs">(opsional)</span>
                        </label>
                        <textarea name="parameter_lain" id="parameter_lain" rows="2"
                            placeholder="Catatan atau parameter tambahan lainnya..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500
                                   @error('parameter_lain') border-red-400 bg-red-50 @enderror">{{ old('parameter_lain') }}</textarea>
                        @error('parameter_lain')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-center py-2">
                <span class="bg-gray-100 px-4 py-2 rounded-full text-sm font-medium text-gray-700">
                    ATAU
                </span>
            </div>

            {{-- MODE 2: Angka (Hanya 1x per Kode per Day) --}}
            <div id="mode2Section" class="border-2 border-green-200 bg-green-50 rounded-lg p-5 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div
                        class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-bold mr-3">
                        2
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Mode Angka <span class="text-sm text-gray-600 font-normal">(Hanya 1x per kode per hari)</span>
                    </h3>
                    <span id="mode2Status" class="ml-auto px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        SIAP
                    </span>
                </div>

                <div class="space-y-4">
                    <div class="text-sm text-green-800 bg-green-100 border border-green-300 rounded-lg p-3 space-y-2">
                        <p>
                            ℹ️ <strong>Perhatian:</strong> Setiap kode hanya bisa diinput sekali per hari. Jika kode sudah ada di tanggal yang sama, data akan ditolak.
                        </p>
                        <p>
                            💡 <strong>Tip:</strong> Jika Anda mulai mengisi mode ini, Mode Non-Angka akan otomatis di-disable untuk menghindari kesalahan input.
                        </p>
                    </div>

                    {{-- Kode Selection for Mode 2 --}}
                    <div>
                        <label for="kode_mode2" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <select name="kode" id="kode_mode2" class="w-full select2-kode
                                   @error('kode') border-red-400 bg-red-50 @enderror">
                            <option value="">-- Pilih Kode --</option>
                            @foreach ($kodeOptions as $kodeValue => $kodeName)
                                <option value="{{ $kodeValue }}" {{ old('kode') == $kodeValue ? 'selected' : '' }}>
                                    {{ $kodeName }}
                                </option>
                            @endforeach
                        </select>
                        @error('kode')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Row 1: Cawan Kosong, Berat Basah, Cawan Sample Kering --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="cawan_kosong" class="block text-sm font-medium text-gray-700 mb-2">
                                Cawan Kosong <span class="text-gray-400 text-xs">(gram)</span>
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
                                Berat Basah <span class="text-gray-400 text-xs">(gram)</span>
                            </label>
                            <input type="number" step="0.000001" name="berat_basah" id="berat_basah"
                                value="{{ old('berat_basah') }}" placeholder="0.000000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500
                                       @error('berat_basah') border-red-400 bg-red-50 @enderror">
                            @error('berat_basah')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cawan_sample_kering" class="block text-sm font-medium text-gray-700 mb-2">
                                Cawan + Sample Kering <span class="text-gray-400 text-xs">(gram)</span>
                            </label>
                            <input type="number" step="0.000001" name="cawan_sample_kering" id="cawan_sample_kering"
                                value="{{ old('cawan_sample_kering') }}" placeholder="0.000000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500
                                       @error('cawan_sample_kering') border-red-400 bg-red-50 @enderror">
                            @error('cawan_sample_kering')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 2: Labu Kosong, Oil Labu --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="labu_kosong" class="block text-sm font-medium text-gray-700 mb-2">
                                Labu Kosong <span class="text-gray-400 text-xs">(gram)</span>
                            </label>
                            <input type="number" step="0.000001" name="labu_kosong" id="labu_kosong"
                                value="{{ old('labu_kosong') }}" placeholder="0.000000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500
                                       @error('labu_kosong') border-red-400 bg-red-50 @enderror">
                            @error('labu_kosong')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="oil_labu" class="block text-sm font-medium text-gray-700 mb-2">
                                Oil + Labu <span class="text-gray-400 text-xs">(gram)</span>
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
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('lab.index') }}"
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

            // Dual-Mode Validation
            const form = document.getElementById('labForm');

            // Mode 1 fields (all non-numeric fields)
            const mode1Fields = ['kode', 'jenis', 'operator', 'sampel_boy', 'parameter_lain'];
            // Mode 2 fields (kode from Mode 2 dropdown + numeric fields)
            const mode2Fields = ['kode_mode2', 'cawan_kosong', 'berat_basah', 'cawan_sample_kering', 'labu_kosong', 'oil_labu'];

            // Function to check if any field in array has value
            function hasAnyValue(fieldNames) {
                return fieldNames.some(name => {
                    const field = document.getElementById(name) || document.getElementsByName(name)[0];
                    if (!field) return false;
                    return field.value && field.value.trim() !== '';
                });
            }

            // Form submit validation
            form.addEventListener('submit', function (e) {
                const isMode1Active = hasAnyValue(mode1Fields);
                const isMode2Active = hasAnyValue(mode2Fields);

                // Validasi: tidak boleh isi keduanya sekaligus
                if (isMode1Active && isMode2Active) {
                    e.preventDefault();
                    alert('❌ KESALAHAN:\n\nPilih SALAH SATU MODE saja:\n• Mode 1 (Non-Angka): Kode + Jenis\n• Mode 2 (Angka): Kode + Data Angka\n\nTidak boleh isi keduanya sekaligus!');
                    return false;
                }

                // Validasi: minimal salah satu harus diisi
                if (!isMode1Active && !isMode2Active) {
                    e.preventDefault();
                    alert('⚠️ PERHATIAN:\n\nMinimal salah satu mode harus diisi:\n• Mode 1 (Non-Angka): Pilih Kode\n• Mode 2 (Angka): Pilih Kode + Isi Data Angka');
                    return false;
                }

                // Validasi Mode 2: Kode harus diisi
                if (isMode2Active) {
                    const kodeMode2 = document.getElementById('kode_mode2');
                    if (!kodeMode2 || !kodeMode2.value) {
                        e.preventDefault();
                        alert('⚠️ PERHATIAN MODE ANGKA:\n\nKode harus dipilih untuk input data angka!');
                        return false;
                    }
                }

                return true;
            });

            // Visual feedback saat user mengetik
            const mode1Inputs = mode1Fields.map(name => document.getElementById(name) || document.getElementsByName(name)[0]).filter(el => el);
            const mode2Inputs = mode2Fields.map(name => document.getElementById(name)).filter(el => el);

            const mode1Section = document.getElementById('mode1Section');
            const mode2Section = document.getElementById('mode2Section');

            // Function to enable/disable fields
            function enableFields(fields) {
                fields.forEach(field => {
                    if (field) {
                        field.disabled = false;
                        field.classList.remove('cursor-not-allowed', 'bg-gray-100');
                        // Re-enable Select2 if it's a select element
                        if (field.tagName === 'SELECT' && $(field).hasClass('select2-hidden-accessible')) {
                            $(field).prop('disabled', false);
                        }
                    }
                });
            }

            function disableFields(fields) {
                fields.forEach(field => {
                    if (field) {
                        field.disabled = true;
                        field.classList.add('cursor-not-allowed', 'bg-gray-100');
                        // Disable Select2 if it's a select element
                        if (field.tagName === 'SELECT' && $(field).hasClass('select2-hidden-accessible')) {
                            $(field).prop('disabled', true);
                        }
                    }
                });
            }

            function updateVisualFeedback() {
                const isMode1Active = hasAnyValue(mode1Fields);
                const isMode2Active = hasAnyValue(mode2Fields);
                
                const mode1Status = document.getElementById('mode1Status');
                const mode2Status = document.getElementById('mode2Status');

                if (isMode1Active && !isMode2Active) {
                    // Mode 1 aktif - Disable Mode 2
                    mode1Section.classList.add('ring-4', 'ring-blue-400');
                    mode2Section.classList.remove('ring-4', 'ring-green-400', 'ring-red-400');
                    mode2Section.classList.add('opacity-50');
                    
                    enableFields(mode1Inputs);
                    disableFields(mode2Inputs);
                    
                    // Update status badges
                    if (mode1Status) {
                        mode1Status.textContent = 'AKTIF';
                        mode1Status.className = 'ml-auto px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800';
                    }
                    if (mode2Status) {
                        mode2Status.textContent = 'DISABLED';
                        mode2Status.className = 'ml-auto px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500';
                    }
                    
                } else if (isMode2Active && !isMode1Active) {
                    // Mode 2 aktif - Disable Mode 1
                    mode2Section.classList.add('ring-4', 'ring-green-400');
                    mode1Section.classList.remove('ring-4', 'ring-blue-400', 'ring-red-400');
                    mode1Section.classList.add('opacity-50');
                    
                    enableFields(mode2Inputs);
                    disableFields(mode1Inputs);
                    
                    // Update status badges
                    if (mode1Status) {
                        mode1Status.textContent = 'DISABLED';
                        mode1Status.className = 'ml-auto px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500';
                    }
                    if (mode2Status) {
                        mode2Status.textContent = 'AKTIF';
                        mode2Status.className = 'ml-auto px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
                    }
                    
                } else if (isMode1Active && isMode2Active) {
                    // Keduanya aktif (error state - seharusnya tidak terjadi dengan auto-disable)
                    mode1Section.classList.add('ring-4', 'ring-red-400');
                    mode2Section.classList.add('ring-4', 'ring-red-400');
                    mode1Section.classList.remove('opacity-50', 'ring-blue-400');
                    mode2Section.classList.remove('opacity-50', 'ring-green-400');
                    
                    enableFields(mode1Inputs);
                    enableFields(mode2Inputs);
                    
                    // Update status badges (error state)
                    if (mode1Status) {
                        mode1Status.textContent = 'ERROR';
                        mode1Status.className = 'ml-auto px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800';
                    }
                    if (mode2Status) {
                        mode2Status.textContent = 'ERROR';
                        mode2Status.className = 'ml-auto px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800';
                    }
                    
                } else {
                    // Belum ada yang aktif - Enable semua
                    mode1Section.classList.remove('ring-4', 'ring-blue-400', 'ring-red-400', 'opacity-50');
                    mode2Section.classList.remove('ring-4', 'ring-green-400', 'ring-red-400', 'opacity-50');
                    
                    enableFields(mode1Inputs);
                    enableFields(mode2Inputs);
                    
                    // Update status badges (ready state)
                    if (mode1Status) {
                        mode1Status.textContent = 'SIAP';
                        mode1Status.className = 'ml-auto px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800';
                    }
                    if (mode2Status) {
                        mode2Status.textContent = 'SIAP';
                        mode2Status.className = 'ml-auto px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800';
                    }
                }
            }

            // Attach event listeners
            [...mode1Inputs, ...mode2Inputs].forEach(input => {
                if (input) {
                    // Standard HTML events
                    input.addEventListener('input', updateVisualFeedback);
                    input.addEventListener('change', updateVisualFeedback);
                    input.addEventListener('keyup', updateVisualFeedback);
                    
                    // Special handling for Select2 dropdowns
                    if (input.tagName === 'SELECT' && $(input).hasClass('select2-hidden-accessible')) {
                        $(input).on('select2:select', updateVisualFeedback);
                        $(input).on('select2:clear', updateVisualFeedback);
                        $(input).on('select2:unselect', updateVisualFeedback);
                    }
                }
            });

            // Initial check
            updateVisualFeedback();

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
        });
    </script>

    {{-- Auto-dismiss flash messages --}}
    <script>
        const flashError = document.getElementById('flash-error');
        const flashSuccess = document.getElementById('flash-success');

        [flashError, flashSuccess].forEach(flashEl => {
            if (flashEl) {
                setTimeout(function () {
                    flashEl.style.transition = 'opacity 0.5s ease';
                    flashEl.style.opacity = '0';
                    setTimeout(function () {
                        flashEl.remove();
                    }, 500);
                }, 4000);
            }
        });
    </script>

</x-layouts.app>