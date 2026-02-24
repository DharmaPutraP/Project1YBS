<x-layouts.app title="Detail Oil Losses">

    {{-- ── Flash Messages ───────────────────────────────────────────── --}}
    @if (session('success'))
        <div id="flash-success" class="mb-6">
            <x-ui.alert type="success" title="Berhasil">{{ session('success') }}</x-ui.alert>
        </div>
    @endif

    @if (session('error'))
        <div id="flash-error" class="mb-6">
            <x-ui.alert type="error" title="Gagal">{{ session('error') }}</x-ui.alert>
        </div>
    @endif

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('lab.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    {{-- Main Card --}}
    <x-ui.card title="Detail Data Oil Losses">

        {{-- Status Badge & Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-6 border-b">
            <div>
                @if ($oilLoss->status === 'approved')
                    <span
                        class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Approved
                    </span>
                    <p class="text-xs text-gray-500 mt-2">
                        Diapprove oleh {{ $oilLoss->approver->name ?? '-' }} pada
                        {{ $oilLoss->approved_at?->format('d/m/Y H:i') }}
                    </p>
                @elseif($oilLoss->status === 'submitted')
                    <span
                        class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Menunggu Approval
                    </span>
                @elseif($oilLoss->status === 'rejected')
                    <span
                        class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Rejected
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-3 py-1.5 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                        Draft
                    </span>
                @endif
            </div>

            <div class="flex items-center gap-2 mt-4 sm:mt-0">
                @can('edit lab results')
                    @if ($oilLoss->canBeEdited())
                        <a href="{{ route('lab.edit', $oilLoss->id) }}"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                            Edit
                        </a>
                    @endif
                @endcan

                @can('approve lab results')
                    @if ($oilLoss->status === 'submitted')
                        <form action="{{ route('lab.approve', $oilLoss->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                                Approve
                            </button>
                        </form>
                    @endif
                @endcan

                @can('delete lab results')
                    @if ($oilLoss->canBeEdited())
                        <form action="{{ route('lab.destroy', $oilLoss->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                Hapus
                            </button>
                        </form>
                    @endif
                @endcan
            </div>
        </div>

        {{-- General Information --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Umum</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Tanggal Analisa</p>
                    <p class="text-base font-medium text-gray-900">{{ $oilLoss->analysis_date->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jam Analisa</p>
                    <p class="text-base font-medium text-gray-900">{{ $oilLoss->analysis_time->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nomor Batch</p>
                    <p class="text-base font-medium text-gray-900">{{ $oilLoss->batch_number ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Input Oleh</p>
                    <p class="text-base font-medium text-gray-900">{{ $oilLoss->user->name }}</p>
                </div>
            </div>
        </div>

        <hr class="my-6">

        {{-- Production Data --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Produksi</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-blue-600 font-medium mb-1">Berat TBS</p>
                    <p class="text-2xl font-bold text-blue-900">{{ number_format($oilLoss->tbs_weight, 2) }}</p>
                    <p class="text-xs text-blue-700 mt-1">kilogram</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-green-600 font-medium mb-1">CPO Dihasilkan</p>
                    <p class="text-2xl font-bold text-green-900">{{ number_format($oilLoss->cpo_produced, 2) }}</p>
                    <p class="text-xs text-green-700 mt-1">kilogram</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <p class="text-sm text-yellow-600 font-medium mb-1">Kernel Dihasilkan</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ number_format($oilLoss->kernel_produced ?? 0, 2) }}
                    </p>
                    <p class="text-xs text-yellow-700 mt-1">kilogram</p>
                </div>
            </div>
        </div>

        <hr class="my-6">

        {{-- Calculated Results --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Hasil Perhitungan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-600">Oil to TBS (OER)</p>
                        <span class="text-xs text-gray-400">Standard: 22%</span>
                    </div>
                    <p class="text-3xl font-bold {{ $oilLoss->oil_to_tbs >= 22 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($oilLoss->oil_to_tbs, 2) }}%
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        @if($oilLoss->oil_to_tbs >= 22)
                            ✓ Di atas standard
                        @else
                            ✗ Di bawah standard
                        @endif
                    </p>
                </div>

                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-gray-600">Kernel to TBS (KER)</p>
                        <span class="text-xs text-gray-400">Standard: 5%</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ number_format($oilLoss->kernel_to_tbs, 2) }}%
                    </p>
                </div>

                <div
                    class="border-2 {{ $oilLoss->oil_losses <= 0 ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' }} rounded-lg p-4">
                    <p
                        class="text-sm {{ $oilLoss->oil_losses <= 0 ? 'text-green-700' : 'text-red-700' }} font-medium mb-2">
                        Oil Losses (%)
                    </p>
                    <p class="text-3xl font-bold {{ $oilLoss->oil_losses <= 0 ? 'text-green-700' : 'text-red-700' }}">
                        {{ number_format($oilLoss->oil_losses, 2) }}%
                    </p>
                    <p class="text-xs {{ $oilLoss->oil_losses <= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        @if($oilLoss->oil_losses <= 0)
                            Performa bagus! Produksi melebihi standard.
                        @else
                            Terdapat losses dari standard produksi.
                        @endif
                    </p>
                </div>

                <div
                    class="border-2 {{ $oilLoss->oil_losses <= 0 ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' }} rounded-lg p-4">
                    <p
                        class="text-sm {{ $oilLoss->oil_losses <= 0 ? 'text-green-700' : 'text-red-700' }} font-medium mb-2">
                        Total Losses (kg)
                    </p>
                    <p class="text-3xl font-bold {{ $oilLoss->oil_losses <= 0 ? 'text-green-700' : 'text-red-700' }}">
                        {{ number_format($oilLoss->total_losses, 2) }}
                    </p>
                    <p class="text-xs {{ $oilLoss->oil_losses <= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        kilogram
                    </p>
                </div>
            </div>
        </div>

        @if($oilLoss->moisture_content || $oilLoss->ffa_content)
            <hr class="my-6">

            {{-- Quality Parameters --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Parameter Kualitas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($oilLoss->moisture_content)
                        <div>
                            <p class="text-sm text-gray-500">Kadar Air (Moisture Content)</p>
                            <p class="text-base font-medium text-gray-900">{{ number_format($oilLoss->moisture_content, 2) }}%
                            </p>
                        </div>
                    @endif
                    @if($oilLoss->ffa_content)
                        <div>
                            <p class="text-sm text-gray-500">Free Fatty Acid (FFA)</p>
                            <p class="text-base font-medium text-gray-900">{{ number_format($oilLoss->ffa_content, 2) }}%</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($oilLoss->notes)
            <hr class="my-6">

            {{-- Notes --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Catatan</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $oilLoss->notes }}</p>
                </div>
            </div>
        @endif

        {{-- Timestamps --}}
        <div class="mt-8 pt-6 border-t text-xs text-gray-500">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <p>Dibuat: {{ $oilLoss->created_at->format('d/m/Y H:i') }}</p>
                @if($oilLoss->updated_at != $oilLoss->created_at)
                    <p>Terakhir diupdate: {{ $oilLoss->updated_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>

    </x-ui.card>

    {{-- Auto-dismiss flash messages --}}
    <script>
        ['flash-success', 'flash-error'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                setTimeout(function () {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(function () { el.remove(); }, 500);
                }, 4000);
            }
        });
    </script>

</x-layouts.app>