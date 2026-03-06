{{--
Component: Offline Indicator
Shows connection status and pending data sync button

Usage:
<x-offline-indicator />
--}}

<!-- Offline Indicator Banner -->
<div id="offline-indicator" class="hidden fixed top-0 left-0 right-0 bg-yellow-500 text-white px-4 py-2 z-50 shadow-lg">
    <div class="container mx-auto flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="font-semibold">Mode Offline</span>
            <span class="text-sm opacity-90">| Data akan disimpan sementara</span>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="text-sm">Data Pending:</span>
                <span id="pending-count"
                    class="hidden bg-white text-yellow-600 font-bold px-2 py-1 rounded-full text-xs">0</span>
            </div>

            <button type="button" id="sync-pending-btn" onclick="window.offlineManager.syncPending()"
                class="bg-white text-yellow-600 hover:bg-yellow-50 px-4 py-1 rounded font-semibold text-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Sinkronisasi
            </button>
        </div>
    </div>
</div>

<!-- Online Status Mini Indicator (Optional) -->
<div class="fixed bottom-4 right-4 z-40 hidden" id="online-status-mini">
    <div class="bg-white shadow-lg rounded-lg px-4 py-2 flex items-center gap-2">
        <div id="connection-dot" class="w-3 h-3 rounded-full bg-green-500"></div>
        <span id="connection-text" class="text-sm font-medium text-gray-700">Online</span>
    </div>
</div>

<script>
    // Update mini indicator
    function updateMiniIndicator() {
        const mini = document.getElementById('online-status-mini');
        const dot = document.getElementById('connection-dot');
        const text = document.getElementById('connection-text');

        if (navigator.onLine) {
            dot.classList.remove('bg-red-500');
            dot.classList.add('bg-green-500');
            text.textContent = 'Online';
        } else {
            dot.classList.remove('bg-green-500');
            dot.classList.add('bg-red-500');
            text.textContent = 'Offline';
        }
    }

    window.addEventListener('online', updateMiniIndicator);
    window.addEventListener('offline', updateMiniIndicator);
    document.addEventListener('DOMContentLoaded', updateMiniIndicator);
</script>