/**
 * SweetAlert2 Confirmation Helpers
 * Provides reusable confirmation dialogs for CRUD operations
 */

import Swal from 'sweetalert2';

/**
 * Confirm before saving new data
 */
export async function confirmSave(formElement) {
    const result = await Swal.fire({
        title: 'Simpan Data?',
        text: 'Pastikan data yang Anda masukkan sudah benar.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#4F46E5',
        cancelButtonColor: '#6B7280',
        reverseButtons: true
    });

    if (result.isConfirmed) {
        // Check if online
        if (!navigator.onLine) {
            const offlineResult = await Swal.fire({
                title: 'Mode Offline',
                text: 'Data akan disimpan sementara dan dikirim saat online. Lanjutkan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Simpan Offline',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#F59E0B',
                cancelButtonColor: '#6B7280'
            });

            if (offlineResult.isConfirmed) {
                saveOffline(formElement);
                return false; // Prevent normal form submission
            }
            return false;
        }

        return true; // Allow form submission
    }

    return false;
}

/**
 * Confirm before updating data
 */
export async function confirmUpdate() {
    const result = await Swal.fire({
        title: 'Update Data?',
        text: 'Data akan diperbarui dengan informasi yang baru.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#4F46E5',
        cancelButtonColor: '#6B7280',
        reverseButtons: true
    });

    if (result.isConfirmed && !navigator.onLine) {
        const offlineResult = await Swal.fire({
            title: 'Mode Offline',
            text: 'Perubahan akan disimpan sementara. Lanjutkan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Update Offline',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#F59E0B',
            cancelButtonColor: '#6B7280'
        });

        return offlineResult.isConfirmed;
    }

    return result.isConfirmed;
}

/**
 * Confirm before deleting data
 */
export async function confirmDelete(itemName = '') {
    const result = await Swal.fire({
        title: 'Hapus Data?',
        html: itemName 
            ? `Data <strong>${itemName}</strong> akan dihapus permanen!`
            : 'Data akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        reverseButtons: true
    });

    return result.isConfirmed;
}

/**
 * Show success message
 */
export function showSuccess(message = 'Berhasil!', title = 'Sukses') {
    return Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        confirmButtonColor: '#4F46E5',
        timer: 2000
    });
}

/**
 * Show error message
 */
export function showError(message = 'Terjadi kesalahan!', title = 'Error') {
    return Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        confirmButtonColor: '#EF4444'
    });
}

/**
 * Show loading
 */
export function showLoading(message = 'Memproses...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

/**
 * Save form data offline
 */
function saveOffline(formElement) {
    const formData = new FormData(formElement);
    const data = {
        url: formElement.action,
        method: formElement.method.toUpperCase(),
        body: Object.fromEntries(formData)
    };

    window.offlineManager.saveToQueue(data);

    Swal.fire({
        icon: 'success',
        title: 'Disimpan Offline',
        text: 'Data akan dikirim otomatis saat koneksi tersedia.',
        confirmButtonColor: '#4F46E5'
    });
}

// Make functions available globally
window.confirmSave = confirmSave;
window.confirmUpdate = confirmUpdate;
window.confirmDelete = confirmDelete;
window.showSuccess = showSuccess;
window.showError = showError;
window.showLoading = showLoading;
