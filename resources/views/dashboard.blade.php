{{-- Menggunakan layout app (sidebar + navbar) --}}
<x-layouts.app title="Dashboard">

    {{-- Kartu ringkasan selamat datang --}}
    <x-ui.card title="Selamat Datang" class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-lg">
                    Halo, <strong class="text-indigo-600">{{ Auth::user()->name }}</strong>! 👋
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Anda terhubung sebagai <x-ui.badge color="indigo">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</x-ui.badge>
                </p>
            </div>
        </div>
    </x-ui.card>

    {{-- Form Tambah User --}}
    <x-ui.card title="Tambah User Baru" class="mb-8">
        {{-- Alert validasi --}}
        @if ($errors->any())
            <div class="mb-6">
                <x-ui.alert type="error">{{ $errors->first() }}</x-ui.alert>
            </div>
        @endif

        <form action="" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-form.input 
                    label="Nama User" 
                    name="name" 
                    placeholder="Masukkan nama lengkap"
                    :value="old('name')"
                    required 
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-form.input 
                    label="Username" 
                    name="username" 
                    placeholder="Masukkan username"
                    :value="old('username')"
                    required 
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-form.input 
                    label="Password" 
                    name="password" 
                    type="password"
                    placeholder="Masukkan password"
                    required 
                />
            </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                        <option value="user" @selected(old('role') == 'user')>User</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            {{-- Action Buttons --}}
            <div class="flex justify-end gap-3 pt-6 ">
                <button type="reset" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                    Reset
                </button>

                <x-ui.button type="submit" variant="primary">
                    Simpan User
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

</x-layouts.app>