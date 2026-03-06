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

        const result = await Swal.fire({
            title: 'Sinkronisasi Data',
            text: `Kirim ${queue.length} data pending ke server?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#4F46E5',
            cancelButtonColor: '#6B7280'
        });

        if (!result.isConfirmed) return;

        let successCount = 0;
        let failCount = 0;

        for (const item of queue) {
            try {
                await this.sendToServer(item);
                this.markAsSynced(item.id);
                successCount++;
            } catch (error) {
                console.error('Sync error:', error);
                failCount++;
            }
        }

        this.updatePendingCount();

        if (failCount === 0) {
            this.showNotification(`${successCount} data berhasil disinkronkan!`, 'success');
        } else {
            this.showNotification(
                `${successCount} berhasil, ${failCount} gagal`,
                'warning'
            );
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
