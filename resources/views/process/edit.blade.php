<x-layouts.app title="Edit Informasi Proses">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Informasi Proses</h1>
            <p class="mt-2 text-sm text-gray-600">Ubah data untuk Tim 1 dan Tim 2.</p>
        </div>
        <a href="{{ route('process.index') }}"
            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Kembali
        </a>
    </div>

    <x-ui.card title="Form Edit Informasi Proses">
        <form action="{{ route('process.update', ['kernelProsses' => $record->id]) }}" method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="process_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal <span class="text-red-500">*</span>
                </label>
                <input type="date" id="process_date" name="process_date"
                    value="{{ old('process_date', optional($record->process_date)->format('Y-m-d')) }}"
                    class="w-full md:w-72 px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 @error('process_date') border-red-400 bg-red-50 @enderror">
                @error('process_date')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if (!$visibleTeam || $visibleTeam === 'Tim 1')
                <div class="rounded-xl border border-blue-200 bg-blue-50 p-5 space-y-4">
                    <h2 class="text-lg font-semibold text-blue-900">Tim 1</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="team_1_start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Mulai Proses <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_1_start_time" name="team_1_start_time"
                                value="{{ old('team_1_start_time', $team1['process_start']) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 @error('team_1_start_time') border-red-400 bg-red-50 @enderror">
                            @error('team_1_start_time')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="team_1_end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Akhir Proses <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_1_end_time" name="team_1_end_time"
                                value="{{ old('team_1_end_time', $team1['process_end']) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500 @error('team_1_end_time') border-red-400 bg-red-50 @enderror">
                            @error('team_1_end_time')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <p class="block text-sm font-medium text-gray-700 mb-2">Anggota Tim (Checklist)</p>
                        @if (count($teamMembers) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach ($teamMembers as $member)
                                    <label class="inline-flex items-center gap-2 rounded-lg border border-blue-100 bg-white px-3 py-2 text-sm text-gray-700">
                                        <input type="checkbox" name="team_1_members[]" value="{{ $member }}" data-team="1"
                                            @checked(in_array($member, old('team_1_members', $team1['members']), true))
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span>{{ $member }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                        @error('team_1_members')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @endif

                @if (!$visibleTeam || $visibleTeam === 'Tim 2')
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 space-y-4">
                    <h2 class="text-lg font-semibold text-emerald-900">Tim 2</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="team_2_start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Mulai Proses <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_2_start_time" name="team_2_start_time"
                                value="{{ old('team_2_start_time', $team2['process_start']) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('team_2_start_time') border-red-400 bg-red-50 @enderror">
                            @error('team_2_start_time')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="team_2_end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Jam Akhir Proses <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="team_2_end_time" name="team_2_end_time"
                                value="{{ old('team_2_end_time', $team2['process_end']) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('team_2_end_time') border-red-400 bg-red-50 @enderror">
                            @error('team_2_end_time')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <p class="block text-sm font-medium text-gray-700 mb-2">Anggota Tim (Checklist)</p>
                        @if (count($teamMembers) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach ($teamMembers as $member)
                                    <label class="inline-flex items-center gap-2 rounded-lg border border-emerald-100 bg-white px-3 py-2 text-sm text-gray-700">
                                        <input type="checkbox" name="team_2_members[]" value="{{ $member }}" data-team="2"
                                            @checked(in_array($member, old('team_2_members', $team2['members']), true))
                                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                        <span>{{ $member }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                        @error('team_2_members')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @endif
            </div>

            @error('team_2_members')
                <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    {{ $message }}
                </div>
            @enderror

            <div class="pt-4">
                <button type="submit"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </x-ui.card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const team1Checks = Array.from(document.querySelectorAll('input[name="team_1_members[]"]'));
            const team2Checks = Array.from(document.querySelectorAll('input[name="team_2_members[]"]'));

            function syncTeams() {
                const selectedTeam1 = new Set(team1Checks.filter(input => input.checked).map(input => input.value));
                const selectedTeam2 = new Set(team2Checks.filter(input => input.checked).map(input => input.value));

                team1Checks.forEach(input => {
                    if (!input.checked) {
                        input.disabled = selectedTeam2.has(input.value);
                    }
                });

                team2Checks.forEach(input => {
                    if (!input.checked) {
                        input.disabled = selectedTeam1.has(input.value);
                    }
                });
            }

            [...team1Checks, ...team2Checks].forEach(input => {
                input.addEventListener('change', syncTeams);
            });

            syncTeams();
        });
    </script>
</x-layouts.app>
