<x-layouts.app title="Data Analisa Spintest COT">
    <x-process.analisa-moisture.page-shell
        eyebrow="Analisa Moisture & Spintes"
        title="Data Analisa Spintest COT"
        description="Menampilkan data tersimpan untuk periode yang dipilih."
        :input-url="route('analisa-moisture.input')"
        :filter-url="route('analisa-moisture.spintest-cot')"
        :export-url="route('analisa-moisture.spintest-cot.export')"
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
                            <th class="px-4 py-3 text-left font-semibold">Created By</th>
                            <th class="px-4 py-3 text-left font-semibold">Office</th>
                            <th class="px-4 py-3 text-left font-semibold">OIL</th>
                            <th class="px-4 py-3 text-left font-semibold">EMULSI</th>
                            <th class="px-4 py-3 text-left font-semibold">AIR</th>
                            <th class="px-4 py-3 text-left font-semibold">NOS</th>
                            @role('Super Admin')
                                <th class="px-4 py-3 text-left font-semibold">Aksi</th>
                            @endrole
                        </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($rows as $row)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-3 text-slate-700">{{ $row->tanggal?->format('d-m-Y') }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->jam }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->created_by ?: ($row->user?->name ?? '-') }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->office ?: '-' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->oil }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->emulsi }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->air }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $row->nos }}</td>
                                @role('Super Admin')
                                    <td class="px-4 py-3 text-slate-700">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('analisa-moisture.spintest-cot.edit', $row) }}" class="inline-flex items-center rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-200">Edit</a>
                                            <form method="POST" action="{{ route('analisa-moisture.spintest-cot.destroy', $row) }}" onsubmit="return confirm('Hapus data ini?')">
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
                                <td colspan="{{ auth()->user()?->hasRole('Super Admin') ? 9 : 8 }}" class="px-4 py-8 text-center text-slate-500">Belum ada data pada periode ini.</td>
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
