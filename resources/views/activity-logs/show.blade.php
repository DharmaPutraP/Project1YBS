<x-layouts.app title="Detail Activity Log">

    {{-- ── Header Section ────────────────────────────────────────────── --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('activity-logs.index') }}" class="text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Detail Activity Log</h1>
        </div>
        <p class="text-sm text-gray-600">
            Detail lengkap dari aktivitas yang tercatat
        </p>
    </div>

    {{-- ── Main Information ──────────────────────────────────────────── --}}
    <x-ui.card class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Umum</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">User</label>
                <p class="mt-1 text-base text-gray-900">{{ $log->user_name ?? 'System' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Aksi</label>
                <p class="mt-1">
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                        @if($log->action_badge_color == 'green') bg-green-100 text-green-800
                        @elseif($log->action_badge_color == 'yellow') bg-yellow-100 text-yellow-800
                        @elseif($log->action_badge_color == 'red') bg-red-100 text-red-800
                        @elseif($log->action_badge_color == 'blue') bg-blue-100 text-blue-800
                        @elseif($log->action_badge_color == 'indigo') bg-indigo-100 text-indigo-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $log->action_label }}
                    </span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Waktu</label>
                <p class="mt-1 text-base text-gray-900">
                    {{ $log->created_at->format('d F Y, H:i:s') }}
                    <span class="text-sm text-gray-500">({{ $log->created_at->diffForHumans() }})</span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">IP Address</label>
                <p class="mt-1 text-base text-gray-900 font-mono">{{ $log->ip_address }}</p>
            </div>

            @if($log->model_type)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Model</label>
                    <p class="mt-1 text-base text-gray-900">
                        {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                    </p>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-500">URL</label>
                <p class="mt-1 text-base text-gray-900 break-all">{{ $log->url }}</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500">Deskripsi</label>
                <p class="mt-1 text-base text-gray-900">{{ $log->description }}</p>
            </div>

            @if($log->user_agent)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500">User Agent (Browser)</label>
                    <p class="mt-1 text-sm text-gray-600 break-all">{{ $log->user_agent }}</p>
                </div>
            @endif
        </div>
    </x-ui.card>

    {{-- ── Data Changes ──────────────────────────────────────────────── --}}
    @if($log->old_values || $log->new_values)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Old Values --}}
            @if($log->old_values)
                <x-ui.card>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Data Sebelum Perubahan
                    </h3>
                    <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                        <pre
                            class="text-xs text-gray-800 overflow-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </x-ui.card>
            @endif

            {{-- New Values --}}
            @if($log->new_values)
                <x-ui.card>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Data Setelah Perubahan
                    </h3>
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <pre
                            class="text-xs text-gray-800 overflow-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </x-ui.card>
            @endif
        </div>
    @endif

    {{-- ── Metadata ──────────────────────────────────────────────────── --}}
    @if($log->metadata)
        <x-ui.card class="mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Metadata / Context</h3>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <pre
                    class="text-xs text-gray-800 overflow-auto">{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </x-ui.card>
    @endif

</x-layouts.app>