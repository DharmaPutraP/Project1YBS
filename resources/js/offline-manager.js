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
        const syncResults = [];

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
                const proof = await this.sendToServer(item);
                this.markAsSynced(item.id);
                successCount++;
                syncResults.push({ status: 'success', item, proof });
            } catch (error) {
                console.error('Sync error:', error);
                this.markAsSynced(item.id);
                failCount++;
                syncResults.push({ status: 'failed', item, error: error.message });
            }
        }

        this.updatePendingCount();
        this.updateOnlineStatus(); // Update banner and floating badge

        // Store for image download
        this._lastSyncResults = { syncResults, successCount, failCount };

        Swal.fire({
            icon: failCount === 0 ? 'success' : 'warning',
            title: failCount === 0 ? 'Sinkronisasi Selesai!' : 'Sinkronisasi Sebagian',
            html: this.buildSyncResultHtml(syncResults),
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#4F46E5',
            width: '750px',
            customClass: { htmlContainer: 'text-left' }
        });
    }

    /**
     * Send item to server
     * Returns proof data (or null) on success; throws Error with user-friendly message on failure.
     */
    async sendToServer(item) {
        const response = await fetch(item.data.url, {
            method: item.data.method || 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams(item.data.body)
        });

        if (!response.ok) {
            const data = await response.json().catch(() => null);
            if (data && data.errors) {
                // Laravel validation errors: flatten all messages
                const msgs = Object.values(data.errors).flat().join('; ');
                throw new Error(msgs);
            }
            throw new Error(data?.message || `HTTP ${response.status}`);
        }

        const data = await response.json().catch(() => ({}));
        // Return proof data from server (may be null for non-oil endpoints)
        return data.proof || null;
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
    /**
     * Build HTML for sync result table shown in Swal dialog
     */
    buildSyncResultHtml(syncResults) {
        const successCount = syncResults.filter(r => r.status === 'success').length;
        const failCount    = syncResults.filter(r => r.status === 'failed').length;

        const rows = syncResults.map((result, i) => {
            const body     = result.item.data.body;
            const ts       = new Date(result.item.timestamp);
            const tsStr    = ts.toLocaleString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            const kode     = body.kode || body.kode_mode2 || '-';
            const operator = body.operator || '-';
            const jenis    = body.jenis || '-';

            if (result.status === 'success') {
                const msg     = result.proof?.message || 'Berhasil disimpan';
                const m2      = result.proof?.mode2;
                const hasCalc = !!m2;

                // Build hidden calculation detail row
                let detailRow = '';
                if (hasCalc) {
                    const olwbOk = parseFloat(m2.olwb) <= parseFloat(m2.limitOLWB);
                    const oldbOk = parseFloat(m2.oldb) <= parseFloat(m2.limitOLDB);
                    const olOk   = parseFloat(m2.oil_losses) <= parseFloat(m2.limitOL);
                    detailRow = `
                    <tr id="sync-calc-${i}" style="display:none;background:#f0f9ff;">
                        <td colspan="7" style="border:1px solid #bae6fd;padding:10px 14px;">
                            <div style="font-size:11px;font-weight:600;color:#0369a1;margin-bottom:6px;">📊 Hasil Perhitungan — ${m2.kode_label || kode}</div>
                            <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:4px;">
                                <span style="font-size:12px;font-weight:700;color:${olwbOk ? '#16a34a' : '#dc2626'};">
                                    OL/WB: ${m2.olwb}% <span style="font-weight:400;font-size:11px;">(Limit ≤${m2.limitOLWB}%)</span> ${olwbOk ? '✓' : '✗'}
                                </span>
                                <span style="font-size:12px;font-weight:700;color:${oldbOk ? '#16a34a' : '#dc2626'};">
                                    OL/DB: ${m2.oldb}% <span style="font-weight:400;font-size:11px;">(Limit ≤${m2.limitOLDB}%)</span> ${oldbOk ? '✓' : '✗'}
                                </span>
                                <span style="font-size:12px;font-weight:700;color:${olOk ? '#16a34a' : '#dc2626'};">
                                    Oil Losses: ${m2.oil_losses}% <span style="font-weight:400;font-size:11px;">(Limit ≤${m2.limitOL}%)</span> ${olOk ? '✓' : '✗'}
                                </span>
                            </div>
                            <div style="font-size:11px;color:#64748b;">Moist: ${m2.moist ?? '-'}% &nbsp;|&nbsp; DM/WM: ${m2.dmwm ?? '-'}% &nbsp;|&nbsp; Input oleh: ${m2.input_by ?? '-'}</div>
                        </td>
                    </tr>`;
                }

                const ketHtml = hasCalc
                    ? `<span style="font-size:11px;color:#4b5563;">${msg}</span>
                       <button type="button"
                           onclick="(function(el){el.style.display=el.style.display==='none'?'table-row':'none'})(document.getElementById('sync-calc-${i}'))"
                           style="display:block;margin-top:3px;color:#0369a1;font-size:11px;font-weight:600;text-decoration:underline;background:none;border:none;cursor:pointer;padding:0;">
                           ▼ Lihat Hasil
                       </button>`
                    : `<span style="font-size:11px;color:#4b5563;">${msg}</span>`;

                return `<tr style="background:#f0fdf4">
                    <td class="border border-gray-200 px-2 py-1.5 text-xs text-center">${i + 1}</td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs">${tsStr}</td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs font-semibold">${kode}</td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs">${operator}</td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs">${jenis}</td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs text-center">
                        <span class="bg-green-100 text-green-700 px-1.5 py-0.5 rounded text-xs font-semibold">✓ Diterima</span>
                    </td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs">${ketHtml}</td>
                </tr>${detailRow}`;
            } else {
                return `<tr style="background:#fef2f2">
                    <td class="border border-gray-200 px-2 py-1.5 text-xs text-center">${i + 1}</td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs">${tsStr}</td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs font-semibold">${kode}</td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs">${operator}</td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs">${jenis}</td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs text-center">
                        <span class="bg-red-100 text-red-700 px-1.5 py-0.5 rounded text-xs font-semibold">✗ Ditolak</span>
                    </td>
                    <td class="border border-gray-200 px-2 py-1.5 text-xs">
                        <span style="color:#dc2626;font-size:11px;">${result.error || 'Gagal'}</span>
                        <div style="color:#92400e;font-size:10px;font-weight:600;margin-top:2px;">Dikeluarkan dari antrian setelah ditolak</div>
                    </td>
                </tr>`;
            }
        }).join('');

        const failNote = failCount > 0
            ? `<div style="margin-top:8px;font-size:12px;color:#92400e;font-weight:500;">⚠ ${failCount} data ditolak dan sudah dikeluarkan dari waiting list sinkronisasi.</div>`
            : '';

        return `
            <div class="mb-3 flex gap-4 text-sm font-semibold">
                <span class="text-green-600">✓ ${successCount} Diterima</span>
                ${failCount > 0 ? `<span class="text-red-600">✗ ${failCount} Ditolak</span>` : ''}
            </div>
            <div class="overflow-x-auto max-h-72 overflow-y-auto rounded border border-gray-200">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100 sticky top-0">
                            <th class="border border-gray-300 px-2 py-1.5 text-xs text-center">#</th>
                            <th class="border border-gray-300 px-2 py-1.5 text-xs">Waktu</th>
                            <th class="border border-gray-300 px-2 py-1.5 text-xs">Kode</th>
                            <th class="border border-gray-300 px-2 py-1.5 text-xs">Operator</th>
                            <th class="border border-gray-300 px-2 py-1.5 text-xs">Jenis</th>
                            <th class="border border-gray-300 px-2 py-1.5 text-xs text-center">Status</th>
                            <th class="border border-gray-300 px-2 py-1.5 text-xs">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
            ${failNote}
            <div class="mt-3 flex justify-end">
                <button type="button" onclick="window.offlineManager.downloadSyncResultImage()"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Unduh Gambar
                </button>
            </div>`;
    }

    /**
     * Download sync result as JPG image using html-to-image (lazy-loaded)
     */
    async downloadSyncResultImage() {
        const data = this._lastSyncResults;
        if (!data) return;

        // Lazily load html-to-image if not already available
        if (!window.htmlToImage) {
            try {
                await new Promise((resolve, reject) => {
                    const s = document.createElement('script');
                    s.src = 'https://cdn.jsdelivr.net/npm/html-to-image@1.11.11/dist/html-to-image.js';
                    s.onload = resolve;
                    s.onerror = () => reject(new Error('Gagal memuat library gambar'));
                    document.head.appendChild(s);
                });
            } catch (e) {
                Swal.fire('Error', e.message, 'error');
                return;
            }
        }

        const { syncResults } = data;
        const successCount = syncResults.filter(r => r.status === 'success').length;
        const failCount    = syncResults.filter(r => r.status === 'failed').length;
        const ts = new Date().toLocaleString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' });

        const exportRows = syncResults.map((result, i) => {
            const body      = result.item.data.body;
            const itemTs    = new Date(result.item.timestamp);
            const itemTsStr = itemTs.toLocaleString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            const kode      = body.kode || body.kode_mode2 || '-';
            const operator  = body.operator || '-';
            const jenis     = body.jenis || '-';
            const isSuccess = result.status === 'success';
            const rowBg     = isSuccess ? '#f0fdf4' : '#fef2f2';
            const statusTxt = isSuccess ? '✓ Diterima' : '✗ Ditolak';
            const statusClr = isSuccess ? '#15803d' : '#dc2626';

            let ketCell = '';
            if (isSuccess) {
                ketCell = `<td style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;color:#475569;">${result.proof?.message || 'Berhasil disimpan'}</td>`;
            } else {
                ketCell = `<td style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;">
                    <span style="color:#dc2626;">${result.error || 'Gagal'}</span>
                    <div style="color:#92400e;font-size:11px;font-weight:600;margin-top:3px;">Dikeluarkan dari antrian setelah ditolak</div>
                </td>`;
            }

            const mainRow = `<tr style="background:${rowBg}">
                <td style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;text-align:center;">${i + 1}</td>
                <td style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;">${itemTsStr}</td>
                <td style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;font-weight:600;">${kode}</td>
                <td style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;">${operator}</td>
                <td style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;">${jenis}</td>
                <td style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;text-align:center;font-weight:600;color:${statusClr};">${statusTxt}</td>
                ${ketCell}
            </tr>`;

            // Calculation detail row (always expanded in export image)
            let calcRow = '';
            if (isSuccess && result.proof?.mode2) {
                const m2     = result.proof.mode2;
                const olwbOk = parseFloat(m2.olwb) <= parseFloat(m2.limitOLWB);
                const oldbOk = parseFloat(m2.oldb) <= parseFloat(m2.limitOLDB);
                const olOk   = parseFloat(m2.oil_losses) <= parseFloat(m2.limitOL);
                calcRow = `<tr style="background:#f0f9ff;">
                    <td style="border:1px solid #bae6fd;padding:2px 10px 10px 10px;"></td>
                    <td colspan="6" style="border:1px solid #bae6fd;padding:6px 10px 10px 10px;">
                        <div style="font-size:11px;font-weight:600;color:#0369a1;margin-bottom:5px;">📊 Hasil Perhitungan — ${m2.kode_label || kode}</div>
                        <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:4px;">
                            <span style="font-size:12px;font-weight:700;color:${olwbOk ? '#16a34a' : '#dc2626'};">OL/WB: ${m2.olwb}% (Limit ≤${m2.limitOLWB}%) ${olwbOk ? '✓' : '✗'}</span>
                            <span style="font-size:12px;font-weight:700;color:${oldbOk ? '#16a34a' : '#dc2626'};">OL/DB: ${m2.oldb}% (Limit ≤${m2.limitOLDB}%) ${oldbOk ? '✓' : '✗'}</span>
                            <span style="font-size:12px;font-weight:700;color:${olOk ? '#16a34a' : '#dc2626'};">Oil Losses: ${m2.oil_losses}% (Limit ≤${m2.limitOL}%) ${olOk ? '✓' : '✗'}</span>
                        </div>
                        <div style="font-size:11px;color:#64748b;">Moist: ${m2.moist ?? '-'}% &nbsp;|&nbsp; DM/WM: ${m2.dmwm ?? '-'}% &nbsp;|&nbsp; Input oleh: ${m2.input_by ?? '-'}</div>
                    </td>
                </tr>`;
            }

            return mainRow + calcRow;
        }).join('');

        const failNoteExport = failCount > 0
            ? `<div style="margin-top:12px;font-size:12px;color:#92400e;font-weight:500;">⚠ ${failCount} data ditolak dan sudah dikeluarkan dari waiting list sinkronisasi.</div>`
            : '';

        const exportHtml = `<div style="font-family:ui-sans-serif,system-ui,sans-serif;padding:32px;background:#f8fafc;width:1040px;box-sizing:border-box;">
            <div style="background:white;border-radius:16px;padding:32px;box-shadow:0 4px 24px rgba(0,0,0,0.10);">
                <div style="text-align:center;margin-bottom:20px;padding-bottom:16px;border-bottom:2px solid #e2e8f0;">
                    <h2 style="font-size:20px;font-weight:700;color:#1e293b;margin:0 0 6px;">Hasil Sinkronisasi Data Offline</h2>
                    <p style="color:#64748b;margin:0;font-size:13px;">Diekspor pada: ${ts}</p>
                </div>
                <div style="display:flex;gap:16px;margin-bottom:16px;">
                    <span style="color:#16a34a;font-weight:600;font-size:13px;padding:4px 12px;background:#f0fdf4;border-radius:8px;">✓ ${successCount} Diterima</span>
                    ${failCount > 0 ? `<span style="color:#dc2626;font-weight:600;font-size:13px;padding:4px 12px;background:#fef2f2;border-radius:8px;">✗ ${failCount} Ditolak</span>` : ''}
                </div>
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f1f5f9;">
                            <th style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;text-align:center;">#</th>
                            <th style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;">Waktu Input</th>
                            <th style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;">Kode</th>
                            <th style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;">Operator</th>
                            <th style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;">Jenis</th>
                            <th style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;text-align:center;">Status</th>
                            <th style="border:1px solid #cbd5e1;padding:8px 10px;font-size:12px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>${exportRows}</tbody>
                </table>
                ${failNoteExport}
            </div>
        </div>`;

        const container = document.createElement('div');
        container.style.cssText = 'position:fixed;left:-12000px;top:0;z-index:-1;';
        container.innerHTML = exportHtml;
        document.body.appendChild(container);

        try {
            const dataUrl = await window.htmlToImage.toJpeg(container.firstElementChild, {
                backgroundColor: '#f8fafc',
                quality: 0.85,
                pixelRatio: 1.5,
                cacheBust: true
            });
            const link = document.createElement('a');
            link.download = `sinkronisasi-${Date.now()}.jpg`;
            link.href = dataUrl;
            link.click();
        } catch (err) {
            console.error('downloadSyncResultImage error:', err);
            Swal.fire('Gagal', 'Gagal mengunduh gambar: ' + err.message, 'error');
        } finally {
            document.body.removeChild(container);
        }
    }
}

// Initialize offline manager
window.offlineManager = new OfflineManager();

// Export for use in other files
export default OfflineManager;
