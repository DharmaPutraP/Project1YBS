<x-layouts.app title="Kelola Pengguna">
    <x-ui.card title="Kelola Pengguna">
        <p class="text-gray-500 text-sm">Halaman ini sedang dalam pengembangan.</p>
    </x-ui.card>
    {{-- Form Tambah User --}}
    <x-ui.card title="Tambah User Baru" class="mt-8">
        {{-- Alert validasi --}}
        @if ($errors->any())
            <div class="mb-6">
                <x-ui.alert type="error">{{ $errors->first() }}</x-ui.alert>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Row 1: Nama & Username --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-form.input label="Nama User" name="name" placeholder="Masukkan nama lengkap" :value="old('name')"
                    required />

                <x-form.input label="Username" name="username" placeholder="Masukkan username" :value="old('username')"
                    required />
            </div>

            {{-- Row 2: Password & Konfirmasi Password --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-form.input label="Password" name="password" type="password" placeholder="Min. 8 karakter"
                    hint="Minimal 8 karakter" required />

                <x-form.input label="Konfirmasi Password" name="password_confirmation" type="password"
                    placeholder="Ulangi password" required />
            </div>

            {{-- Row 3: Role --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role_id" id="role_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $id => $name)
                            <option value="{{ $id }}" @selected(old('role_id') == $id)>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 pt-6 ">
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
</x-layouts.app>