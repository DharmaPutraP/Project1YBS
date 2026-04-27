<x-layouts.app title="Edit Analisa FFA dan Moisture">
    <div class="mx-auto max-w-3xl space-y-6">
        <x-ui.card title="Edit Analisa FFA dan Moisture">
            <form action="{{ route('analisa-moisture.ffa-moisture.update', $row) }}" method="POST" class="space-y-4">
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

                <div>
                    <label for="moisture" class="mb-2 block text-sm font-medium text-slate-700">Moisture</label>
                    <input type="text" id="moisture" name="moisture" value="{{ old('moisture', $row->moisture) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="bst1_ffa" class="mb-2 block text-sm font-medium text-slate-700">BST1 (FFA)</label>
                        <input type="number" step="0.01" id="bst1_ffa" name="bst1_ffa" value="{{ old('bst1_ffa', $row->bst1_ffa) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    </div>
                    <div>
                        <label for="bst2_ffa" class="mb-2 block text-sm font-medium text-slate-700">BST2 (FFA)</label>
                        <input type="number" step="0.01" id="bst2_ffa" name="bst2_ffa" value="{{ old('bst2_ffa', $row->bst2_ffa) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    </div>
                    <div>
                        <label for="bst3_ffa" class="mb-2 block text-sm font-medium text-slate-700">BST3 (FFA)</label>
                        <input type="number" step="0.01" id="bst3_ffa" name="bst3_ffa" value="{{ old('bst3_ffa', $row->bst3_ffa) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    </div>
                    <div>
                        <label for="impurities" class="mb-2 block text-sm font-medium text-slate-700">Impurities</label>
                        <input type="number" step="0.01" id="impurities" name="impurities" value="{{ old('impurities', $row->impurities) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-200 pt-4">
                    <a href="{{ route('analisa-moisture.ffa-moisture') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Batal</a>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Simpan Perubahan</button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-layouts.app>
