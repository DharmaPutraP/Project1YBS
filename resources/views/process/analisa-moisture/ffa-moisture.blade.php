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
                            <th rowspan="2" class="px-4 py-3 text-left font-semibold align-middle">Tanggal</th>
                            <th rowspan="2" class="px-4 py-3 text-left font-semibold align-middle">Jam</th>
                            <th rowspan="2" class="px-4 py-3 text-left font-semibold align-middle">Created By</th>
                            <th rowspan="2" class="px-4 py-3 text-left font-semibold align-middle">Office</th>
                            <th rowspan="2" class="px-4 py-3 text-left font-semibold align-middle">Moisture</th>
                            <th colspan="{{ $isSunOffice ? 2 : 3 }}" class="px-4 py-2 text-center font-semibold">FFA</th>
                            <th rowspan="2" class="px-4 py-3 text-left font-semibold align-middle">Impurities</th>
                            @role('Super Admin')
                                <th rowspan="2" class="px-4 py-3 text-left font-semibold align-middle">Aksi</th>
                            @endrole
                        </tr>
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">BST1</th>
                            <th class="px-4 py-3 text-left font-semibold">BST2</th>
                            @unless($isSunOffice)
                                <th class="px-4 py-3 text-left font-semibold">BST3</th>
                            @endunless
                        </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($rows as $row)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-4 py-3 text-slate-700">{{ $row->tanggal?->format('d-m-Y') }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $row->jam }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $row->created_by ?: ($row->user?->name ?? '-') }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $row->office ?: '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $row->moisture }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $row->bst1_ffa }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $row->bst2_ffa }}</td>
                            @unless($isSunOffice)
                                <td class="px-4 py-3 text-slate-700">{{ $row->bst3_ffa }}</td>
                            @endunless
                            <td class="px-4 py-3 text-slate-700">{{ $row->impurities }}</td>
                            @role('Super Admin')
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
                            @endrole
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isSunOffice ? (auth()->user()?->hasRole('Super Admin') ? 9 : 8) : (auth()->user()?->hasRole('Super Admin') ? 10 : 9) }}" class="px-4 py-8 text-center text-slate-500">Belum ada data pada periode ini.</td>
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
