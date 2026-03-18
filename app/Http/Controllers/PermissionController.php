<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403, 'Akses ditolak.');

        $roles = Role::with('permissions')->where('name', '!=', 'Super Admin')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        $groupedPermissions = $this->groupPermissions($permissions);

        return view('permissions.index', compact('roles', 'permissions', 'groupedPermissions'));
    }

    public function edit(Role $role)
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403, 'Akses ditolak.');

        $permissions = Permission::orderBy('name')->get();

        $groupedPermissions = $this->groupPermissions($permissions);

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('permissions.edit', compact('role', 'permissions', 'groupedPermissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403, 'Akses ditolak.');

        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('permissions.index')->with('success', "Permission untuk role <strong>{$role->name}</strong> berhasil diperbarui.");
    }

    private function groupPermissions($permissions)
    {
        return $permissions->groupBy(function ($permission) {
            $name = strtolower($permission->name);

            if (str_contains($name, 'dashboard')) {
                return 'Dashboard';
            }

            if (str_contains($name, 'user')) {
                return 'Manajemen User';
            }

            if (str_contains($name, 'activity log')) {
                return 'Activity Log';
            }

            if (str_contains($name, 'kernel losses') || str_contains($name, 'rekap kernel')) {
                return 'Kernel Losses';
            }

            if (str_contains($name, 'oil losses')) {
                return 'Oil Losses';
            }

            if (str_contains($name, 'olwb')) {
                return 'OLWB';
            }

            if (str_contains($name, 'performance')) {
                return 'Performance';
            }

            if (str_contains($name, 'laporan')) {
                return 'Laporan';
            }

            if (str_contains($name, 'permission')) {
                return 'Permission';
            }

            return 'Lainnya';
        })->sortKeys();
    }
}
