<x-layouts.app title="Detail Informasi Proses">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Informasi Proses</h1>
            <p class="mt-2 text-sm text-gray-600">Rincian data proses tanggal {{ $record->process_date?->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('process.index') }}"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if (!$visibleTeam || $visibleTeam === 'Tim 1')
        <x-ui.card title="{{ $team1['team_name'] }}">
            <div class="space-y-3 text-sm">
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Tanggal</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $team1['process_date'] }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Jam Mulai Proses</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $team1['process_start'] ?: '-' }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Jam Akhir Proses</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $team1['process_end'] ?: '-' }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3 bg-blue-50">
                    <p class="text-gray-500 text-xs">Total Jam Produksi</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $team1['production_hours'] }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Anggota Tim</p>
                    @if (!empty($team1['members']))
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ($team1['members'] as $member)
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                    {{ $member }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-1 font-semibold text-gray-900">-</p>
                    @endif
                </div>
            </div>
        </x-ui.card>
        @endif

        @if (!$visibleTeam || $visibleTeam === 'Tim 2')
        <x-ui.card title="{{ $team2['team_name'] }}">
            <div class="space-y-3 text-sm">
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Tanggal</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $team2['process_date'] }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Jam Mulai Proses</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $team2['process_start'] ?: '-' }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Jam Akhir Proses</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $team2['process_end'] ?: '-' }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3 bg-emerald-50">
                    <p class="text-gray-500 text-xs">Total Jam Produksi</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $team2['production_hours'] }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-3">
                    <p class="text-gray-500 text-xs">Anggota Tim</p>
                    @if (!empty($team2['members']))
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ($team2['members'] as $member)
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                    {{ $member }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-1 font-semibold text-gray-900">-</p>
                    @endif
                </div>
            </div>
        </x-ui.card>
        @endif
    </div>
</x-layouts.app>
