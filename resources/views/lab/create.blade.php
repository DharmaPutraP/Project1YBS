<x-layouts.app title="Input Data Oil Losses">

    {{-- ── Flash Messages ───────────────────────────────────────────── --}}
    @if (session('error'))
        <div id="flash-error" class="mb-6">
            <x-ui.alert type="error" title="Gagal">{{ session('error') }}</x-ui.alert>
        </div>
    @endif

    {{-- Form Input Oil Losses --}}
    <x-ui.card title="Input Data Oil Losses">
        <form action="{{ route('lab.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Info Standard OER --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-900 mb-1">Standard Nilai</h4>
                        <p class="text-sm text-blue-800">
                            • Standard OER (Oil Extraction Rate): <strong>{{ $standardOER }}%</strong><br>
                            • Standard KER (Kernel Extraction Rate): <strong>{{ $standardKER }}%</strong><br>
                            <span class="text-xs text-blue-700 italic">Oil Losses akan dihitung otomatis: Losses =
                                Standard OER - Actual OER</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Row 1: Tanggal & Jam --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="analysis_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Analisa <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <input type="date" name="analysis_date" id="analysis_date" value="{{ old('analysis_date') }}"
                        max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border rounded-lg text-sm transition focus:outline-none focus:ring-2 
                               {{ $errors->has('analysis_date') ? 'border-red-400 bg-red-50 focus:ring-red-400' : 'border-gray-300 focus:ring-indigo-500' }}"
                        required>
                    @error('analysis_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="analysis_time" class="block text-sm font-medium text-gray-700 mb-1">
                        Jam Analisa <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <input type="time" name="analysis_time" id="analysis_time" value="{{ old('analysis_time') }}"
                        class="w-full px-4 py-2 border rounded-lg text-sm transition focus:outline-none focus:ring-2 
                               {{ $errors->has('analysis_time') ? 'border-red-400 bg-red-50 focus:ring-red-400' : 'border-gray-300 focus:ring-indigo-500' }}" required>
                    @error('analysis_time')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Row 2: Batch Number --}}
            <div>
                <label for="batch_number" class="block text-sm font-medium text-gray-700 mb-1">
                    Nomor Batch <span class="text-gray-400 text-xs">(opsional)</span>
                </label>
                <input type="text" name="batch_number" id="batch_number" value="{{ old('batch_number') }}"
                    placeholder="Contoh: BATCH-2026-001"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('batch_number')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <hr class="my-6">

            {{-- Section: Data Input --}}
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Produksi</h3>

            {{-- Row 3: TBS Weight --}}
            <div>
                <label for="tbs_weight" class="block text-sm font-medium text-gray-700 mb-1">
                    Berat TBS (kg) <span class="text-red-500 ml-0.5">*</span>
                </label>
                <input type="number" step="0.01" name="tbs_weight" id="tbs_weight" value="{{ old('tbs_weight') }}"
                    placeholder="Masukkan berat TBS dalam kg"
                    class="w-full px-4 py-2 border rounded-lg text-sm transition focus:outline-none focus:ring-2 
                           {{ $errors->has('tbs_weight') ? 'border-red-400 bg-red-50 focus:ring-red-400' : 'border-gray-300 focus:ring-indigo-500' }}" required>
                <p class="mt-1 text-xs text-gray-500">Total berat Tandan Buah Segar (TBS)</p>
                @error('tbs_weight')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Row 4: CPO & Kernel Produced --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="cpo_produced" class="block text-sm font-medium text-gray-700 mb-1">
                        CPO yang Dihasilkan (kg) <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <input type="number" step="0.01" name="cpo_produced" id="cpo_produced"
                        value="{{ old('cpo_produced') }}" placeholder="Masukkan berat CPO"
                        class="w-full px-4 py-2 border rounded-lg text-sm transition focus:outline-none focus:ring-2 
                               {{ $errors->has('cpo_produced') ? 'border-red-400 bg-red-50 focus:ring-red-400' : 'border-gray-300 focus:ring-indigo-500' }}" required>
                    @error('cpo_produced')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kernel_produced" class="block text-sm font-medium text-gray-700 mb-1">
                        Kernel yang Dihasilkan (kg) <span class="text-gray-400 text-xs">(opsional)</span>
                    </label>
                    <input type="number" step="0.01" name="kernel_produced" id="kernel_produced"
                        value="{{ old('kernel_produced') }}" placeholder="Masukkan berat kernel"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('kernel_produced')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <hr class="my-6">

            {{-- Section: Quality Parameters --}}
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Parameter Kualitas <span class="text-sm text-gray-500 font-normal">(Opsional)</span>
            </h3>

            {{-- Row 5: Moisture & FFA --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="moisture_content" class="block text-sm font-medium text-gray-700 mb-1">
                        Kadar Air (%) <span class="text-gray-400 text-xs">(opsional)</span>
                    </label>
                    <input type="number" step="0.01" name="moisture_content" id="moisture_content"
                        value="{{ old('moisture_content') }}" placeholder="Contoh: 0.15"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-gray-500">Moisture content dalam persen</p>
                    @error('moisture_content')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ffa_content" class="block text-sm font-medium text-gray-700 mb-1">
                        Free Fatty Acid (%) <span class="text-gray-400 text-xs">(opsional)</span>
                    </label>
                    <input type="number" step="0.01" name="ffa_content" id="ffa_content"
                        value="{{ old('ffa_content') }}" placeholder="Contoh: 3.5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-gray-500">FFA content dalam persen</p>
                    @error('ffa_content')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <hr class="my-6">

            {{-- Row 6: Notes --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Catatan <span class="text-gray-400 text-xs">(opsional)</span>
                </label>
                <textarea name="notes" id="notes" rows="3" placeholder="Tambahkan catatan jika ada..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                <a href="{{ route('lab.index') }}"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                    Batal
                </a>

                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    Simpan Data
                </button>
            </div>
        </form>
    </x-ui.card>

    {{-- Auto-dismiss flash messages after 4 seconds --}}
    <script>
        const flashEl = document.getElementById('flash-error');
        if (flashEl) {
            setTimeout(function () {
                flashEl.style.transition = 'opacity 0.5s ease';
                flashEl.style.opacity = '0';
                setTimeout(function () {
                    flashEl.remove();
                }, 500);
            }, 4000);
        }
    </script>

</x-layouts.app>