<x-layouts.app title="Edit Permission — {{ $role->name }}">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
                <a href="{{ route('permissions.index') }}" class="hover:text-indigo-600 transition">Permission Control</a>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-600 font-medium">{{ $role->name }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Permission</h1>
            <p class="mt-1 text-sm text-gray-500">Atur hak akses untuk role <strong>{{ $role->name }}</strong>.</p>
        </div>
        <a href="{{ route('permissions.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 bg-white
                   text-sm font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mb-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 shadow-sm">
            <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span>{!! session('success') !!}</span>
        </div>
    @endif

    <form action="{{ route('permissions.update', $role) }}" method="POST"
        x-data="editManager({{ json_encode($rolePermissions) }})"
        @submit.prevent="submitForm($el)">
        @csrf
        @method('PUT')

        {{-- Summary bar --}}
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-xl
                    border border-gray-200 bg-white px-5 py-3 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $role->name }}</p>
                    <p class="text-xs text-gray-400">
                        <span x-text="selected.length" class="font-semibold text-indigo-600"></span>
                        / {{ $permissions->count() }} permission aktif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="selectAll()"
                    class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-medium
                           text-gray-600 hover:bg-gray-50 transition">
                    Pilih Semua
                </button>
                <button type="button" @click="clearAll()"
                    class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-medium
                           text-gray-600 hover:bg-gray-50 transition">
                    Hapus Semua
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-indigo-600
                           hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
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
                        <button type="button"
                            @click="toggleModule({{ json_encode($perms->pluck('name')->toArray()) }})"
                            class="text-xs text-indigo-500 hover:text-indigo-700 font-medium transition">
                            Toggle modul
                        </button>
                    </div>

                    {{-- Permission checkboxes --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($perms->sortBy('name') as $permission)
                            <label
                                class="flex items-center gap-3 px-3 py-2 rounded-lg border cursor-pointer select-none
                                       transition hover:border-indigo-300 hover:bg-indigo-50/40"
                                :class="selected.includes('{{ $permission->name }}')
                                    ? 'border-indigo-300 bg-indigo-50'
                                    : 'border-gray-200 bg-white'">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->name }}"
                                    x-model="selected"
                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600
                                           focus:ring-indigo-500 cursor-pointer flex-shrink-0"
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
            <a href="{{ route('permissions.index') }}"
                class="px-5 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-medium
                       text-gray-600 hover:bg-gray-50 transition shadow-sm">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600
                       hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>

    </form>

    <script>
        function editManager(initial) {
            return {
                selected: [...initial],
                allPerms: {{ json_encode($permissions->pluck('name')->toArray()) }},

                selectAll() {
                    this.selected = [...this.allPerms];
                },

                clearAll() {
                    this.selected = [];
                },

                toggleModule(perms) {
                    const allChecked = perms.every(p => this.selected.includes(p));
                    if (allChecked) {
                        this.selected = this.selected.filter(p => !perms.includes(p));
                    } else {
                        perms.forEach(p => {
                            if (!this.selected.includes(p)) this.selected.push(p);
                        });
                    }
                },

                submitForm(form) {
                    form.submit();
                },
            };
        }
    </script>

</x-layouts.app>
