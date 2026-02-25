<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->whereNull('deleted_at');
        
        // Apply search filter if provided
        $search = $request->get('search');
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
        }
        
        // Apply role filter if provided
        $roleFilter = $request->get('role_filter');
        if ($roleFilter && $roleFilter !== 'all') {
            $query->whereHas('roles', function ($q) use ($roleFilter) {
                $q->where('roles.id', $roleFilter);
            });
        }
        
        $users = $query->paginate(10);
        $roles = Role::orderBy('name')->get();
        
        return view('users.index', compact('users', 'roles', 'roleFilter', 'search'));
    }

    public function edit($id)
    {
        $user = User::whereNull('deleted_at')->findOrFail($id);
        $roles = Role::orderBy('name')->get();
        $userRoleIds = $user->roles()->pluck('id')->toArray();
        return view('users.edit', compact('user', 'roles', 'userRoleIds'));
    }

    public function update(Request $request, $id)
    {
        $user = User::whereNull('deleted_at')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', "unique:users,username,{$id}"],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role_ids' => ['required', 'array', 'min:1', 'max:2'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ]);

        try {
            $user->update([
                'name' => $validated['name'],
                'username' => $validated['username'],
            ]);

            // Update password only jika diisi
            if (!empty($validated['password'])) {
                $user->update([
                    'password' => Hash::make($validated['password']),
                ]);
            }

            // Update multiple roles
            $roles = Role::whereIn('id', $validated['role_ids'])->get();
            $user->syncRoles($roles);
            
            $roleNames = $user->getRoleNames()->implode(', ');
            return redirect()->route('users.index')
                ->with('success', "User '{$user->name}' berhasil diupdate dengan role '{$roleNames}'.");

        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());

            return redirect()->route('users.index')
                ->with('error', 'Gagal mengupdate user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::whereNull('deleted_at')->findOrFail($id);
            $userName = $user->name;
            $user->delete();

            return redirect()->route('users.index')
                ->with('success', "User '{$userName}' berhasil dihapus.");

        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());

            return redirect()->route('users.index')
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
