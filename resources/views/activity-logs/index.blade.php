<x-layouts.app title="Activity Log">

    {{-- ── Header Section ────────────────────────────────────────────── --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Activity Log</h1>
        <p class="mt-2 text-sm text-gray-600">
            Riwayat aktivitas pengguna dalam sistem (input, edit, delete, export, dll)
        </p>
    </div>

    {{-- ── Filter Section ─────────────────────────────────────────────── --}}
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- User Filter --}}
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        User
                    </label>
                    <select name="user_id" class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                        <option value="all">-- Semua User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Filter --}}
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Aksi
                    </label>
                    <select name="action" class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                        <option value="all">-- Semua Aksi --</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Model Type Filter --}}
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tipe Data
                    </label>
                    <select name="model_type" class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                        <option value="all">-- Semua Tipe --</option>
                        @foreach($modelTypes as $type)
                            <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Search --}}
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Cari Deskripsi
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Start Date --}}
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" name="start_date"
                        value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                </div>

                {{-- End Date --}}
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Tanggal Akhir
                    </label>
                    <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}"
                        class="w-full px-3 py-1.5 sm:px-4 sm:py-2 border rounded-lg text-sm">
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row gap-2">
                <button type="submit"
                    class="px-4 sm:px-6 py-1.5 sm:py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>

                @if(request()->hasAny(['user_id', 'action', 'model_type', 'search', 'start_date', 'end_date']))
                    <a href="{{ route('activity-logs.index') }}"
                        class="px-4 sm:px-6 py-1.5 sm:py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium text-center">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </x-ui.card>

    {{-- ── Activity Logs Table ───────────────────────────────────────── --}}
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Waktu
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IP Address
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Detail
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                                <div>{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $log->user_name ?? 'System' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($log->action_badge_color == 'green') bg-green-100 text-green-800
                                        @elseif($log->action_badge_color == 'yellow') bg-yellow-100 text-yellow-800
                                        @elseif($log->action_badge_color == 'red') bg-red-100 text-red-800
                                        @elseif($log->action_badge_color == 'blue') bg-blue-100 text-blue-800
                                        @elseif($log->action_badge_color == 'indigo') bg-indigo-100 text-indigo-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                    {{ $log->action_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <div class="max-w-md truncate">{{ $log->description }}</div>
                                @if($log->model_type)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 font-mono">
                                {{ $log->ip_address }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center">
                                <a href="{{ route('activity-logs.show', $log->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>Tidak ada activity log yang ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div class="mt-4 px-4">
                {{ $logs->links() }}
            </div>
        @endif
    </x-ui.card>

</x-layouts.app>