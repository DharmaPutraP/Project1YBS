<x-layouts.app title="Data Analisa FFA dan Moisture">
    <x-process.analisa-moisture.page-shell
        eyebrow="Analisa Moisture & Spintes"
        title="Data Analisa FFA dan Moisture"
        description="Menampilkan data tersimpan untuk periode yang dipilih."
        :input-url="route('analisa-moisture.input')"
        :filter-url="route('analisa-moisture.ffa-moisture')"
        :export-url="route('analisa-moisture.ffa-moisture.export')"
        :start-date="$startDate"
        :end-date="$endDate"
        :rows="$rows"
    >
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold">Jam</th>
                            <th class="px-4 py-3 text-left font-semibold">Moisture</th>
                            <th class="px-4 py-3 text-left font-semibold">BST1 (FFA)</th>
                            <th class="px-4 py-3 text-left font-semibold">BST2 (FFA)</th>
                            <th class="px-4 py-3 text-left font-semibold">BST3 (FFA)</th>
                            <th class="px-4 py-3 text-left font-semibold">Impurities</th>
                            <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($rows as $row)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-3 text-slate-700">{{ $row->tanggal?->format('d-m-Y') }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->jam }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->moisture }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->bst1_ffa }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->bst2_ffa }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->bst3_ffa }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->impurities }}</td>
                                <td class="px-4 py-3 text-slate-700">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('analisa-moisture.ffa-moisture.edit', $row) }}" class="inline-flex items-center rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-200">Edit</a>
                                        <form method="POST" action="{{ route('analisa-moisture.ffa-moisture.destroy', $row) }}" onsubmit="return confirm('Hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-lg bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-200">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                    @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-500">Belum ada data pada periode ini.</td>
                            </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 px-4 py-4">
            {{ $rows->links() }}
        </div>
    </x-process.analisa-moisture.page-shell>
</x-layouts.app>
