<x-layouts.app title="Edit Analisa Spintest COT">
    <div class="mx-auto max-w-3xl space-y-6">
        <x-ui.card title="Edit Analisa Spintest COT">
            <form action="{{ route('analisa-moisture.spintest-cot.update', $row) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="tanggal" class="mb-2 block text-sm font-medium text-slate-700">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $row->tanggal?->format('Y-m-d')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5" required>
                    </div>
                    <div>
                        <label for="jam" class="mb-2 block text-sm font-medium text-slate-700">Jam</label>
                        <input type="time" id="jam" name="jam" value="{{ old('jam', $row->jam) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5" required>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="oil" class="mb-2 block text-sm font-medium text-slate-700">OIL</label>
                        <input type="number" step="0.01" id="oil" name="oil" value="{{ old('oil', $row->oil) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    </div>
                    <div>
                        <label for="emulsi" class="mb-2 block text-sm font-medium text-slate-700">EMULSI</label>
                        <input type="number" step="0.01" id="emulsi" name="emulsi" value="{{ old('emulsi', $row->emulsi) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    </div>
                    <div>
                        <label for="air" class="mb-2 block text-sm font-medium text-slate-700">AIR</label>
                        <input type="number" step="0.01" id="air" name="air" value="{{ old('air', $row->air) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    </div>
                    <div>
                        <label for="nos" class="mb-2 block text-sm font-medium text-slate-700">NOS</label>
                        <input type="number" step="0.01" id="nos" name="nos" value="{{ old('nos', $row->nos) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-200 pt-4">
                    <a href="{{ route('analisa-moisture.spintest-cot') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Batal</a>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Simpan Perubahan</button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-layouts.app>
