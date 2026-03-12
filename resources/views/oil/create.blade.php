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
                                Operator <span class="text-gray-400 text-xs">(wajib jika Kode diisi)</span>
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
                </div>
            </div>

            {{-- MODE 2: Angka (Hanya 1x per Kode per Day) --}}
            <div id="mode2Section" class="border-2 border-green-200 bg-green-50 rounded-lg p-5">
                <div class="flex items-center mb-4">
                    <div
                        class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-bold mr-3">
                        2
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Mode Angka <span class="text-sm text-gray-600 font-normal">(Hanya 1x per kode per hari)</span>
                    </h3>
                </div>

                <div class="space-y-4">
                    <div class="text-sm text-green-800 bg-green-100 border border-green-300 rounded-lg p-3">
                        <p>
                            ℹ️ <strong>Perhatian:</strong> Setiap kode hanya bisa diinput sekali per hari. Jika kode sudah ada di tanggal yang sama, data akan ditolak.
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
                        @error('kode_mode2')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Row 1: Cawan Kosong, Berat Sampel Basah, Cawan Sample Kering --}}
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

                    {{-- Row 2: Labu Kosong, Oil Labu --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
            </div>

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

            // Form validation: Jika 1 field di mode diisi, semua field di mode tersebut WAJIB diisi
            $('#labForm').on('submit', function(e) {
                let hasError = false;
                let errorMessages = [];

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
                    'cawan_sample_kering': 'Cawan + Sample Kering',
                    'labu_kosong': 'Labu Kosong',
                    'oil_labu': 'Oil + Labu'
                };

                const mode2Values = Object.keys(mode2Fields).map(field => {
                    const value = $(`#${field}`).val();
                    return value && value.trim() !== '';
                });

                const mode2HasAnyValue = mode2Values.some(v => v === true);

                if (mode2HasAnyValue) {
                    Object.keys(mode2Fields).forEach(field => {
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
        async function handleFormSubmit(event, form) {
            event.preventDefault();
            const confirmed = await window.confirmSave(form); // Pass form element untuk offline save
            if (confirmed) {
                form.submit();
            }
            return false;
        }
    </script>

</x-layouts.app>

