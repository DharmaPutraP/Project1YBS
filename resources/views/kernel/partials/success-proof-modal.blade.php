@php
    $successProof = $successProof ?? session('success_proof');
@endphp

@if($successProof)
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #success-proof-print,
            #success-proof-print * {
                visibility: visible;
            }

            #success-proof-print {
                position: absolute;
                inset: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
        }

        .success-proof-export-root {
            position: fixed;
            left: -10000px;
            top: 0;
            width: 1480px;
            padding: 32px;
            background: #f8fafc;
            z-index: -1;
        }

        .success-proof-export-root table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>

    <div class="success-proof-export-root" aria-hidden="true">
        <div id="success-proof-export" class="rounded-[28px] bg-white p-8 shadow-2xl">
            @include('kernel.partials.success-proof-content', ['successProof' => $successProof])
        </div>
    </div>

    <div id="successProofModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="w-full max-w-6xl rounded-2xl bg-white shadow-2xl">
            <div class="flex items-start justify-between border-b border-slate-200 px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Bukti Input Data</h2>
                    <p class="mt-1 text-sm text-slate-600">Simpan bukti input data atau unduh JPG.</p>
                </div>
                <button type="button" onclick="closeSuccessProofModal()" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="success-proof-print" class="max-h-[80vh] overflow-y-auto px-6 py-5">
                @include('kernel.partials.success-proof-content', ['successProof' => $successProof])
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">
                <button type="button" onclick="downloadSuccessProofImage()" class="rounded-lg border border-emerald-300 px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50">
                    Unduh Gambar (JPG)
                </button>
                <button type="button" onclick="closeSuccessProofModal()" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        async function downloadSuccessProofImage() {
            const target = document.getElementById('success-proof-export');
            if (!target || !window.htmlToImage || typeof window.htmlToImage.toJpeg !== 'function') {
                alert('Fitur unduh gambar belum siap. Coba refresh halaman.');
                return;
            }

            try {
                const dataUrl = await window.htmlToImage.toJpeg(target, {
                    backgroundColor: '#ffffff',
                    quality: 0.82,
                    pixelRatio: Math.min(2, window.devicePixelRatio || 1),
                    cacheBust: true,
                    skipAutoScale: false,
                });

                const link = document.createElement('a');
                const timestamp = new Date().toISOString().slice(0, 19).replace(/[:T]/g, '-');
                link.href = dataUrl;
                link.download = `bukti-input-kernel-${timestamp}.jpg`;
                document.body.appendChild(link);
                link.click();
                link.remove();
            } catch (error) {
                console.error('downloadSuccessProofImage', error);
                alert('Gagal mengunduh gambar. Silakan coba lagi.');
            }
        }

        function closeSuccessProofModal() {
            const modal = document.getElementById('successProofModal');
            if (modal) {
                modal.remove();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const successProofModal = document.getElementById('successProofModal');
            if (successProofModal) {
                successProofModal.addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeSuccessProofModal();
                    }
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.11/dist/html-to-image.js"></script>
@endif
