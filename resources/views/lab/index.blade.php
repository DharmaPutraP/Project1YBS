<x-layouts.app title="Data Lab - Oil Losses">

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

    @if (session('info'))
        <div id="flash-info" class="mb-6">
            <x-ui.alert type="info" title="Info">{{ session('info') }}</x-ui.alert>
        </div>
    @endif

    {{-- ── Statistics Cards ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Records</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $statistics['total_records'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Pending Approval</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $statistics['pending_approval'] }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Approved Today</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $statistics['approved_today'] }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div> -->
    </div>

    {{-- ── Main Card ─────────────────────────────────────────────────── --}}
    <x-ui.card title="Data Oil Losses">
        {{-- Action Buttons --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                @can('create lab results')
                    <a href="{{ route('lab.create') }}"
                        class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Input Data Baru
                    </a>
                @endcan
            </div>

            @can('export lab data')
                <a href="{{ route('lab.export') }}"
                    class="inline-flex items-center px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Data
                </a>
            @endcan
        </div>

        {{-- Filter Tabs --}}
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Filter Status">
                @php
                    $currentStatus = request('status', 'all');
                    $tabClass = 'whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition';
                    $activeClass = 'border-indigo-500 text-indigo-600';
                    $inactiveClass = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
                @endphp

                <a href="{{ route('lab.index') }}"
                    class="{{ $tabClass }} {{ $currentStatus === 'all' ? $activeClass : $inactiveClass }}">
                    Semua Data
                    <span
                        class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $currentStatus === 'all' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ $statistics['total_records'] }}
                    </span>
                </a>

                <a href="{{ route('lab.index', ['status' => 'submitted']) }}"
                    class="{{ $tabClass }} {{ $currentStatus === 'submitted' ? $activeClass : $inactiveClass }}">
                    Pending Approval
                    <span
                        class="ml-2 py-0.5 px-2 rounded-full text-xs {{ $currentStatus === 'submitted' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ $statistics['pending_approval'] }}
                    </span>
                </a>

                <a href="{{ route('lab.index', ['status' => 'approved']) }}"
                    class="{{ $tabClass }} {{ $currentStatus === 'approved' ? $activeClass : $inactiveClass }}">
                    Approved
                </a>

                <a href="{{ route('lab.index', ['status' => 'rejected']) }}"
                    class="{{ $tabClass }} {{ $currentStatus === 'rejected' ? $activeClass : $inactiveClass }}">
                    Rejected
                </a>

                <a href="{{ route('lab.index', ['status' => 'draft']) }}"
                    class="{{ $tabClass }} {{ $currentStatus === 'draft' ? $activeClass : $inactiveClass }}">
                    Draft
                </a>
            </nav>
        </div>

        @if ($oilLosses->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-lg text-gray-500">Belum ada data oil losses</p>
                @can('create lab results')
                    <a href="{{ route('lab.create') }}"
                        class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Input Data Pertama
                    </a>
                @endcan
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal/Jam
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Batch
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                TBS (kg)
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                CPO (kg)
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                OER (%)
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Losses (%)
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Input By
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($oilLosses as $oilLoss)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $oilLoss->analysis_date->format('d/m/Y') }}<br>
                                    <span class="text-gray-500">{{ $oilLoss->analysis_time->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $oilLoss->batch_number ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($oilLoss->tbs_weight, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($oilLoss->cpo_produced, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="font-semibold {{ $oilLoss->oil_to_tbs >= 22 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($oilLoss->oil_to_tbs, 2) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="font-semibold {{ $oilLoss->oil_losses <= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($oilLoss->oil_losses, 2) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($oilLoss->status === 'approved')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($oilLoss->status === 'submitted')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($oilLoss->status === 'rejected')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $oilLoss->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        @can('view lab results')
                                            <a href="{{ route('lab.show', $oilLoss->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900" title="Lihat Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        @endcan

                                        @can('approve lab results')
                                            @if ($oilLoss->status === 'submitted')
                                                <form action="{{ route('lab.approve', $oilLoss->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Approve">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $oilLosses->links() }}
            </div>
        @endif
    </x-ui.card>

    {{-- Auto-dismiss flash messages after 4 seconds --}}
    <script>
        ['flash-success', 'flash-error', 'flash-info'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                setTimeout(function () {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(function () {
                        el.remove();
                    }, 500);
                }, 4000);
            }
        });
    </script>

</x-layouts.app>