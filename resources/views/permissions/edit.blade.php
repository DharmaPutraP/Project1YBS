<x-layouts.app title="Edit Permission — {{ $role->name }}">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
                <a href="{{ route('permissions.index') }}" class="hover:text-indigo-600 transition">Permission
                    Control</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-600 font-medium">{{ $role->name }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Permission</h1>
            <p class="mt-1 text-sm text-gray-500">Atur hak akses untuk role <strong>{{ $role->name }}</strong>.</p>
        </div>
        <a href="{{ route('permissions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 bg-white
                   text-sm font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div id="flashMessage"
            class="mb-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 shadow-sm">
            <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span>{!! session('success') !!}</span>
        </div>
    @endif

    <form action="{{ route('permissions.update', $role) }}" method="POST" id="permissionForm">
        @csrf
        @method('PUT')

        {{-- Summary bar --}}
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-xl
                    border border-gray-200 bg-white px-5 py-3 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $role->name }}</p>
                    <p class="text-xs text-gray-400">
                        <span id="selectedCount" class="font-semibold text-indigo-600">0</span>
                        / {{ $permissions->count() }} permission aktif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="btnSelectAll"
                    class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-medium
                           text-gray-600 hover:bg-gray-50 transition">
                    Pilih Semua
                </button>
                <button type="button" id="btnClearAll"
                    class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-medium
                           text-gray-600 hover:bg-gray-50 transition">
                    Hapus Semua
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-indigo-600
                           hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>

        {{-- Permission Groups --}}
        <div class="space-y-4">
            @foreach($groupedPermissions as $module => $perms)
                @if($module === 'Lainnya') @continue @endif
                <x-ui.card>
                    {{-- Module header with select-all toggle --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-indigo-500"></span>
                            <h3 class="text-sm font-bold text-gray-800">{{ $module }}</h3>
                            <span class="text-[11px] text-gray-400 font-normal">
                                ({{ $perms->count() }} permission)
                            </span>
                        </div>
                        <button type="button" class="btn-toggle-module text-xs text-indigo-500 hover:text-indigo-700 font-medium transition"
                            data-permissions='@json($perms->pluck('name')->toArray())'>
                            Toggle modul
                        </button>
                    </div>

                    {{-- Permission checkboxes --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($perms->sortBy('name') as $permission)
                            <label class="permission-label flex items-center gap-3 px-3 py-2 rounded-lg border cursor-pointer select-none
                                       transition hover:border-indigo-300 hover:bg-indigo-50/40 border-gray-200 bg-white">
                                <input type="checkbox" 
                                    name="permissions[]" 
                                    value="{{ $permission->name }}"
                                    class="permission-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600
                                           focus:ring-indigo-500 cursor-pointer flex-shrink-0"
                                    {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                />
                                <span class="text-xs font-mono text-gray-700 leading-tight">
                                    {{ $permission->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        {{-- Bottom Save Button --}}
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('permissions.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-medium
                       text-gray-600 hover:bg-gray-50 transition shadow-sm">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600
                       hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Perubahan
            </button>
        </div>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const form = document.getElementById('permissionForm');
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            const selectedCount = document.getElementById('selectedCount');
            const btnSelectAll = document.getElementById('btnSelectAll');
            const btnClearAll = document.getElementById('btnClearAll');
            const btnToggleModules = document.querySelectorAll('.btn-toggle-module');
            const flashMessage = document.getElementById('flashMessage');

            // Auto hide flash message after 4 seconds
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.transition = 'opacity 0.3s ease';
                    flashMessage.style.opacity = '0';
                    setTimeout(() => flashMessage.remove(), 300);
                }, 4000);
            }

            // Update counter
            function updateCounter() {
                const checked = document.querySelectorAll('.permission-checkbox:checked').length;
                selectedCount.textContent = checked;
            }

            // Update label styling based on checkbox state
            function updateLabelStyling(checkbox) {
                const label = checkbox.closest('.permission-label');
                if (checkbox.checked) {
                    label.classList.remove('border-gray-200', 'bg-white');
                    label.classList.add('border-indigo-300', 'bg-indigo-50');
                } else {
                    label.classList.remove('border-indigo-300', 'bg-indigo-50');
                    label.classList.add('border-gray-200', 'bg-white');
                }
            }

            // Initialize: Update count and styling for all checkboxes
            checkboxes.forEach(checkbox => {
                updateLabelStyling(checkbox);
                
                checkbox.addEventListener('change', function() {
                    updateCounter();
                    updateLabelStyling(this);
                });
            });
            
            // Initial count
            updateCounter();

            // Select All button
            btnSelectAll.addEventListener('click', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    updateLabelStyling(checkbox);
                });
                updateCounter();
            });

            // Clear All button
            btnClearAll.addEventListener('click', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    updateLabelStyling(checkbox);
                });
                updateCounter();
            });

            // Toggle Module buttons
            btnToggleModules.forEach(btn => {
                btn.addEventListener('click', function() {
                    const permissions = JSON.parse(this.dataset.permissions);
                    const moduleCheckboxes = Array.from(checkboxes).filter(cb => 
                        permissions.includes(cb.value)
                    );
                    
                    // Check if all in module are checked
                    const allChecked = moduleCheckboxes.every(cb => cb.checked);
                    
                    // Toggle: if all checked, uncheck all; otherwise check all
                    moduleCheckboxes.forEach(cb => {
                        cb.checked = !allChecked;
                        updateLabelStyling(cb);
                    });
                    
                    updateCounter();
                });
            });
        });
    </script>

</x-layouts.app>