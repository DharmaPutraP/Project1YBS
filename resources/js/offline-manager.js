/**
 * Offline Data Manager
 * Handles offline data storage and synchronization
 */

class OfflineManager {
    constructor() {
        this.QUEUE_KEY = 'ybs_offline_queue';
        this.isOnline = navigator.onLine;
        this.setupEventListeners();
        this.updateOnlineStatus();
    }

    /**
     * Setup online/offline event listeners
     */
    setupEventListeners() {
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.updateOnlineStatus();
            this.showNotification('Koneksi internet tersedia!', 'success');
            
            // Auto-sync if there are pending items
            const pendingCount = this.getPendingCount();
            if (pendingCount > 0) {
                setTimeout(() => {
                    this.promptAutoSync(pendingCount);
                }, 1500);
            }
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.updateOnlineStatus();
            this.showNotification('Mode Offline: Data akan disimpan sementara', 'warning');
        });

        // Update status on page load
        document.addEventListener('DOMContentLoaded', () => {
            this.updateOnlineStatus();
            this.updatePendingCount();
        });
    }

    /**
     * Prompt user for auto-sync with data preview
     */
    async promptAutoSync(count) {
        const queue = this.getQueue().filter(item => item.status === 'pending');
        
        // Generate preview list (first 3 items)
        const previewList = queue.slice(0, 3).map((item, index) => {
            const date = new Date(item.timestamp);
            const formattedDate = date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Extract key data
            const data = item.data.body;
            const details = [];
            if (data.kode) details.push(`Kode: ${data.kode}`);
            if (data.operator) details.push(`Operator: ${data.operator}`);
            if (data.kode_mode2) details.push(`Kode: ${data.kode_mode2}`);
            
            return `
                <div class="text-sm text-left bg-gray-50 p-2 rounded mb-1">
                    <strong>#${index + 1}</strong> - ${formattedDate}
                    ${details.length > 0 ? `<div class="text-xs text-gray-600">${details.slice(0, 2).join(' • ')}</div>` : ''}
                </div>
            `;
        }).join('');
        
        const moreText = count > 3 ? `<p class="text-xs text-gray-500 mt-2">...dan ${count - 3} data lainnya</p>` : '';

        const result = await Swal.fire({
            title: '🌐 Koneksi Tersedia!',
            html: `
                <p class="text-gray-700 mb-3">Kirim <strong>${count} data pending</strong> ke server sekarang?</p>
                <div class="max-h-48 overflow-y-auto mb-2">
                    ${previewList}
                </div>
                ${moreText}
                <p class="text-xs text-blue-600 mt-3">💡 Tip: Klik \"Nanti Saja\" untuk sync manual nanti</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '✓ Ya, Kirim Sekarang!',
            cancelButtonText: '⏰ Nanti Saja',
            confirmButtonColor: '#4F46E5',
            cancelButtonColor: '#6B7280',
            timer: 15000,
            timerProgressBar: true,
            width: '500px'
        });

        if (result.isConfirmed) {
            this.syncPending();
        } else if (result.dismiss === Swal.DismissReason.cancel || result.dismiss === Swal.DismissReason.timer) {
            // User chose "Nanti Saja" or timeout - show persistent reminder
            this.showSyncReminder();
        }
    }

    /**
     * Show sync reminder (persistent badge when online but has pending data)
     */
    showSyncReminder() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: true,
            confirmButtonText: 'Sync',
            timer: 5000,
            timerProgressBar: true
        });

        Toast.fire({
            icon: 'info',
            title: 'Data pending belum terkirim',
            text: 'Klik Sync untuk mengirim sekarang'
        }).then((result) => {
            if (result.isConfirmed) {
                this.syncPending();
            }
        });
        
        // Always show persistent badge
        this.updatePersistentBadge();
    }

    /**
     * Update online/offline indicator in UI
     */
    updateOnlineStatus() {
        const indicator = document.getElementById('offline-indicator');
        const syncBtn = document.getElementById('sync-pending-btn');
        const indicatorTitle = indicator?.querySelector('.font-semibold');
        const indicatorDesc = indicator?.querySelector('.opacity-90');
        const pendingCount = this.getPendingCount();
        
        if (indicator) {
            // Show banner if offline OR if online but has pending data
            if (!this.isOnline) {
                indicator.classList.remove('hidden', 'bg-blue-500');
                indicator.classList.add('bg-yellow-500');
                if (indicatorTitle) indicatorTitle.textContent = 'Mode Offline';
                if (indicatorDesc) indicatorDesc.textContent = '| Data disimpan di browser sementara';
            } else if (pendingCount > 0) {
                // Online but has pending - show different style
                indicator.classList.remove('hidden', 'bg-yellow-500');
                indicator.classList.add('bg-blue-500');
                if (indicatorTitle) indicatorTitle.textContent = 'Data Menunggu Sinkronisasi';
                if (indicatorDesc) indicatorDesc.textContent = `| ${pendingCount} data belum terkirim ke server`;
            } else {
                // Online and no pending - hide
                indicator.classList.add('hidden');
                indicator.classList.remove('bg-blue-500');
                indicator.classList.add('bg-yellow-500');
                // Reset to default text
                if (indicatorTitle) indicatorTitle.textContent = 'Mode Offline';
                if (indicatorDesc) indicatorDesc.textContent = '| Data disimpan di browser sementara';
            }
        }

        if (syncBtn) {
            syncBtn.disabled = !this.isOnline || pendingCount === 0;
        }

        this.updatePendingCount();
        this.updatePersistentBadge();
    }

    /**
     * Update persistent badge (always visible when has pending data)
     */
    updatePersistentBadge() {
        const count = this.getPendingCount();
        
        // Update floating badge if exists
        let floatingBadge = document.getElementById('floating-sync-badge');
        
        if (count > 0 && this.isOnline) {
            // Create floating badge if doesn't exist
            if (!floatingBadge) {
                floatingBadge = document.createElement('div');
                floatingBadge.id = 'floating-sync-badge';
                floatingBadge.className = 'fixed bottom-4 right-4 z-50';
                floatingBadge.innerHTML = `
                    <button onclick="window.offlineManager.syncPending()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 shadow-lg transition-all hover:scale-110 relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span id="floating-badge-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">${count}</span>
                    </button>
                `;
                document.body.appendChild(floatingBadge);
            } else {
                // Update count
                const badgeCount = document.getElementById('floating-badge-count');
                if (badgeCount) {
                    badgeCount.textContent = count;
                }
                floatingBadge.classList.remove('hidden');
            }
        } else if (floatingBadge) {
            // Hide floating badge when no pending or offline
            floatingBadge.classList.add('hidden');
        }
    }

    /**
     * Save data to offline queue
     */
    saveToQueue(data) {
        const queue = this.getQueue();
        const item = {
            id: Date.now() + Math.random(),
            timestamp: new Date().toISOString(),
            data: data,
            status: 'pending'
        };
        
        queue.push(item);
        localStorage.setItem(this.QUEUE_KEY, JSON.stringify(queue));
        this.updatePendingCount();
        
        return item.id;
    }

    /**
     * Get all queued items
     */
    getQueue() {
        const stored = localStorage.getItem(this.QUEUE_KEY);
        return stored ? JSON.parse(stored) : [];
    }

    /**
     * Get count of pending items
     */
    getPendingCount() {
        return this.getQueue().filter(item => item.status === 'pending').length;
    }

    /**
     * Update pending count badge
     */
    updatePendingCount() {
        const badge = document.getElementById('pending-count');
        const count = this.getPendingCount();
        
        if (badge) {
            badge.textContent = count;
            badge.classList.toggle('hidden', count === 0);
        }
    }

    /**
     * View all pending items with detailed data
     */
    viewPendingData() {
        const queue = this.getQueue().filter(item => item.status === 'pending');
        
        if (queue.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Data Pending Kosong',
                html: `
                    <p class="text-gray-600 mb-3">Tidak ada data yang menunggu sinkronisasi.</p>
                    <div class="text-xs text-left bg-blue-50 p-3 rounded">
                        <strong>ℹ️ Informasi:</strong> Data offline disimpan di <code>localStorage</code> browser.
                        Ketika Anda input data saat offline, data akan tersimpan sementara dan otomatis
                        dikirim ke server saat koneksi internet tersedia.
                    </div>
                `,
                confirmButtonColor: '#4F46E5'
            });
            return;
        }

        const itemsList = queue.map((item, index) => {
            const date = new Date(item.timestamp);
            const formattedDate = date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Extract data details
            const data = item.data.body;
            const details = [];
            
            // Mode 1 data
            if (data.kode) details.push(`Kode: ${data.kode}`);
            if (data.operator) details.push(`Operator: ${data.operator}`);
            if (data.jenis) details.push(`Jenis: ${data.jenis}`);
            
            // Mode 2 data  
            if (data.kode_mode2) details.push(`Kode: ${data.kode_mode2}`);
            if (data.cawan_kosong) details.push(`Cawan Kosong: ${data.cawan_kosong}`);
            if (data.berat_basah) details.push(`Berat Basah: ${data.berat_basah}`);
            
            const detailsHtml = details.length > 0 
                ? `<div class="text-xs text-gray-500 mt-1">${details.slice(0, 3).join(' • ')}</div>`
                : '';
            
            return `
                <div class="text-left p-3 bg-gray-50 rounded mb-2 hover:bg-gray-100 transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="font-semibold text-sm text-gray-800">#${index + 1} - ${formattedDate}</div>
                            ${detailsHtml}
                            <div class="text-xs text-blue-600 mt-1">
                                <span class="font-medium">${item.data.method}</span> → ${new URL(item.data.url).pathname}
                            </div>
                        </div>
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Pending</span>
                    </div>
                </div>
            `;
        }).join('');

        Swal.fire({
            icon: 'info',
            title: `📋 Data Pending (${queue.length})`,
            html: `
                <div class="max-h-96 overflow-y-auto">
                    ${itemsList}
                </div>
                <p class="mt-4 text-sm text-gray-600">Data akan dikirim otomatis saat koneksi tersedia,<br>atau klik tombol <strong>Sinkronisasi</strong> untuk kirim manual.</p>
            `,
            width: '650px',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#4F46E5',
            customClass: {
                htmlContainer: 'text-left'
            }
        });
    }

    /**
     * Sync all pending items
     */
    async syncPending() {
        if (!this.isOnline) {
            this.showNotification('Tidak ada koneksi internet!', 'error');
            return;
        }

        const queue = this.getQueue().filter(item => item.status === 'pending');
        
        if (queue.length === 0) {
            this.showNotification('Tidak ada data pending', 'info');
            return;
        }

        // Show preview before syncing
        const itemsList = queue.map((item, index) => {
            const date = new Date(item.timestamp);
            const formattedDate = date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            return `<div class="text-sm text-left">${index + 1}. ${formattedDate}</div>`;
        }).join('');

        const result = await Swal.fire({
            title: 'Sinkronisasi Data',
            html: `
                <p>Kirim ${queue.length} data pending ke server?</p>
                <div class="mt-3 p-3 bg-gray-50 rounded max-h-48 overflow-y-auto">
                    ${itemsList}
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim Semua!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#4F46E5',
            cancelButtonColor: '#6B7280',
            width: '500px'
        });

        if (!result.isConfirmed) return;

        // Show loading with progress
        Swal.fire({
            title: 'Mengirim Data...',
            html: `
                <div class="mb-4">
                    <div class="text-lg font-bold" id="sync-progress">0 / ${queue.length}</div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                        <div id="sync-progress-bar" class="bg-indigo-600 h-2.5 rounded-full transition-all" style="width: 0%"></div>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-3" id="sync-status">Memproses...</p>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        let successCount = 0;
        let failCount = 0;
        const failedItems = [];

        for (let i = 0; i < queue.length; i++) {
            const item = queue[i];
            
            // Update progress
            const progressEl = document.getElementById('sync-progress');
            const progressBar = document.getElementById('sync-progress-bar');
            const statusEl = document.getElementById('sync-status');
            
            if (progressEl) {
                progressEl.textContent = `${i + 1} / ${queue.length}`;
            }
            if (progressBar) {
                const percentage = ((i + 1) / queue.length) * 100;
                progressBar.style.width = `${percentage}%`;
            }
            if (statusEl) {
                statusEl.textContent = `Mengirim data #${i + 1}...`;
            }

            try {
                await this.sendToServer(item);
                this.markAsSynced(item.id);
                successCount++;
            } catch (error) {
                console.error('Sync error:', error);
                failCount++;
                failedItems.push({ index: i + 1, error: error.message });
            }
        }

        this.updatePendingCount();
        this.updateOnlineStatus(); // Update banner and floating badge

        // Show final result
        if (failCount === 0) {
            Swal.fire({
                icon: 'success',
                title: 'Sinkronisasi Selesai!',
                html: `
                    <p class="text-lg"><strong>${successCount} data</strong> berhasil dikirim ke server.</p>
                    <p class="text-sm text-gray-600 mt-2">Semua data offline telah tersinkronisasi.</p>
                `,
                confirmButtonColor: '#4F46E5',
                confirmButtonText: 'OK'
            });
        } else {
            const failedList = failedItems.map(item => 
                `<div class="text-sm text-left">• Data #${item.index}: ${item.error}</div>`
            ).join('');
            
            Swal.fire({
                icon: 'warning',
                title: 'Sinkronisasi Sebagian',
                html: `
                    <p><strong>${successCount} berhasil</strong>, <strong>${failCount} gagal</strong></p>
                    <div class="mt-3 p-3 bg-red-50 rounded max-h-48 overflow-y-auto text-left">
                        <div class="font-semibold text-sm mb-2">Data yang gagal:</div>
                        ${failedList}
                    </div>
                    <p class="text-sm text-gray-600 mt-3">Coba sinkronisasi lagi nanti.</p>
                `,
                confirmButtonColor: '#F59E0B',
                confirmButtonText: 'OK',
                width: '500px'
            });
        }
    }

    /**
     * Send item to server
     */
    async sendToServer(item) {
        try {
            const response = await fetch(item.data.url, {
                method: item.data.method || 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'text/html,application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(item.data.body)
            });

            // Check if response is OK (200-299)
            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`HTTP ${response.status}: ${errorText.substring(0, 100)}`);
            }

            // Try to parse as JSON, if fails assume it's a redirect (success)
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            } else {
                // HTML response (likely redirect after success)
                return { success: true, redirect: true };
            }
        } catch (error) {
            console.error('Send to server error:', error);
            throw error;
        }
    }

    /**
     * Mark item as synced
     */
    markAsSynced(itemId) {
        const queue = this.getQueue();
        const index = queue.findIndex(item => item.id === itemId);
        
        if (index !== -1) {
            queue.splice(index, 1);
            localStorage.setItem(this.QUEUE_KEY, JSON.stringify(queue));
        }
    }

    /**
     * Clear all synced items from queue
     */
    clearSynced() {
        const queue = this.getQueue().filter(item => item.status === 'pending');
        localStorage.setItem(this.QUEUE_KEY, JSON.stringify(queue));
        this.updatePendingCount();
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        Toast.fire({
            icon: type,
            title: message
        });
    }
}

// Initialize offline manager
window.offlineManager = new OfflineManager();

// Export for use in other files
export default OfflineManager;
