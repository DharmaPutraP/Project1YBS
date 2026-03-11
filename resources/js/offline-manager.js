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
     * Prompt user for auto-sync
     */
    async promptAutoSync(count) {
        const result = await Swal.fire({
            title: 'Sinkronisasi Otomatis?',
            html: `
                <p>Koneksi internet tersedia.</p>
                <p class="mt-2">Kirim <strong>${count} data pending</strong> ke server sekarang?</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim Sekarang!',
            cancelButtonText: 'Nanti Saja',
            confirmButtonColor: '#4F46E5',
            cancelButtonColor: '#6B7280',
            timer: 10000,
            timerProgressBar: true
        });

        if (result.isConfirmed) {
            this.syncPending();
        }
    }

    /**
     * Update online/offline indicator in UI
     */
    updateOnlineStatus() {
        const indicator = document.getElementById('offline-indicator');
        const syncBtn = document.getElementById('sync-pending-btn');
        
        if (indicator) {
            if (this.isOnline) {
                indicator.classList.add('hidden');
            } else {
                indicator.classList.remove('hidden');
            }
        }

        if (syncBtn) {
            syncBtn.disabled = !this.isOnline || this.getPendingCount() === 0;
        }

        this.updatePendingCount();
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
     * View all pending items
     */
    viewPendingData() {
        const queue = this.getQueue().filter(item => item.status === 'pending');
        
        if (queue.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Data Pending Kosong',
                text: 'Tidak ada data yang menunggu sinkronisasi.',
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
            return `
                <div class="text-left p-3 bg-gray-50 rounded mb-2">
                    <div class="font-semibold text-sm">#${index + 1} - ${formattedDate}</div>
                    <div class="text-xs text-gray-600 mt-1">
                        ${item.data.method} → ${new URL(item.data.url).pathname}
                    </div>
                </div>
            `;
        }).join('');

        Swal.fire({
            icon: 'info',
            title: `Data Pending (${queue.length})`,
            html: `
                <div class="max-h-96 overflow-y-auto">
                    ${itemsList}
                </div>
                <p class="mt-4 text-sm text-gray-600">Data akan dikirim otomatis saat koneksi tersedia.</p>
            `,
            width: '600px',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#4F46E5'
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
        const response = await fetch(item.data.url, {
            method: item.data.method || 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(item.data.body)
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        return response.json();
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
