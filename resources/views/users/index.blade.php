<x-layouts.app title="Kelola Pengguna">

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
    {{-- Form Tambah User --}}
    <x-ui.card title="Tambah User Baru">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Row 1: Nama & Username --}}

            <x-form.input label="Nama User" name="name" placeholder="Masukkan nama lengkap" :value="old('name')"
                required />

            <x-form.input label="Username" name="username" placeholder="Masukkan username" :value="old('username')"
                required />


            {{-- Row 2: Password & Konfirmasi Password --}}

            <x-form.input label="Password" name="password" type="password" placeholder="Min. 8 karakter"
                hint="Minimal 8 karakter" required />

            <x-form.input label="Konfirmasi Password" name="password_confirmation" type="password"
                placeholder="Ulangi password" required />


            {{-- Row 3: Role --}}
            <div>
                <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Role <span class="text-red-500 ml-0.5">*</span>
                </label>
                <select name="role_id" id="role_id" class="w-full px-4 py-2 border rounded-lg text-sm transition focus:outline-none focus:ring-2 
                           {{ $errors->has('role_id')
    ? 'border-red-400 bg-red-50 focus:ring-red-400 text-red-800'
    : 'border-gray-300 focus:ring-indigo-500 text-gray-900' }}" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $id => $name)
                        <option value="{{ $id }}" @selected(old('role_id') == $id)>{{ $name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-gray-700">
                <button type="reset"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                    Reset
                </button>

                <x-ui.button type="submit" variant="primary">
                    Simpan User
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    {{-- Auto-dismiss flash messages after 4 seconds --}}
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