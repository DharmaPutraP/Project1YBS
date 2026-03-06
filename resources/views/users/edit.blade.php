<x-layouts.app title="Edit Pengguna">

    {{-- Modal Background --}}
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        
        {{-- Modal Content --}}
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-screen overflow-y-auto">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 sticky top-0 bg-white">
                <h3 class="text-lg font-semibold text-gray-900">Edit User</h3>
                <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            {{-- Modal Body --}}
            <div class="p-4 sm:p-6">
                <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4 sm:space-y-6" onsubmit="return handleUpdateUserSubmit(event, this)">
                    @csrf
                    @method('PUT')

                    {{-- Nama User --}}
                    <x-form.input label="Nama User" name="name" placeholder="Masukkan nama lengkap" 
                        :value="old('name', $user->name)" required />

                    {{-- Username --}}
                    <x-form.input label="Username" name="username" placeholder="Masukkan username" 
                        :value="old('username', $user->username)" required />

                    {{-- Role (Multi-select dengan max 2) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500 ml-0.5">*</span>
                            <span class="text-gray-500 text-xs font-normal">(Pilih maksimal 2)</span>
                        </label>
                        <div class="space-y-2 p-3 border border-gray-300 rounded-lg bg-gray-50">
                            @forelse ($roles as $role)
                                <div class="flex items-center">
                                    <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" 
                                        id="role_{{ $role->id }}" class="role-checkbox"
                                        @checked(in_array($role->id, old('role_ids', $userRoleIds)))
                                        onchange="limitRoleCheckboxes(2)">
                                    <label for="role_{{ $role->id }}" class="ml-2 text-sm text-gray-900 cursor-pointer">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Tidak ada role tersedia</p>
                            @endforelse
                        </div>
                        @if ($errors->has('role_ids'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('role_ids') }}</p>
                        @endif
                        @if ($errors->has('role_ids.*'))
                            <p class="mt-1 text-xs text-red-600">{{ $errors->first('role_ids.*') }}</p>
                        @endif
                    </div>

                    {{-- Password Info --}}
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Note:</strong> Kosongkan field password jika tidak ingin mengubah password.
                        </p>
                    </div>

                    {{-- Password --}}
                    <x-form.input label="Password Baru (Opsional)" name="password" type="password" 
                        placeholder="Biarkan kosong jika tidak ingin ubah" hint="Minimal 3 karakter" />

                    {{-- Konfirmasi Password --}}
                    <x-form.input label="Konfirmasi Password" name="password_confirmation" type="password"
                        placeholder="Ulangi password baru" />

                    {{-- Action Buttons --}}
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4 sm:pt-6 border-t border-gray-200">
                        <a href="{{ route('users.index') }}"
                            class="w-full sm:w-auto px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium text-sm text-center">
                            Batal
                        </a>

                        <x-ui.button type="submit" variant="primary" class="w-full sm:w-auto">
                            Simpan Perubahan
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Limit Role Checkboxes (Max 2)
        function limitRoleCheckboxes(maxRoles) {
            const checkboxes = document.querySelectorAll('.role-checkbox');
            const checkedCount = document.querySelectorAll('.role-checkbox:checked').length;
            
            checkboxes.forEach(checkbox => {
                if (!checkbox.checked && checkedCount >= maxRoles) {
                    checkbox.disabled = true;
                } else {
                    checkbox.disabled = false;
                }
            });
        }

        // Initialize checkbox limit on page load
        document.addEventListener('DOMContentLoaded', function () {
            limitRoleCheckboxes(2);
        });

        // Confirmation handler for update form submission
        async function handleUpdateUserSubmit(event, form) {
            event.preventDefault();
            const confirmed = await window.confirmUpdate();
            if (confirmed) {
                form.submit();
            }
            return false;
        }
    </script>

</x-layouts.app>
