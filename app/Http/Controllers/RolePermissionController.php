<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        $roles = Role::where('guard_name', 'web')
            ->withCount('permissions')
            ->orderBy('name')
            ->get();

        return view('roles-permissions.index', compact('roles'));
    }

    public function edit(Role $role): View
    {
        if ($role->guard_name !== 'web') {
            abort(404);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = Permission::where('guard_name', 'web')->orderBy('name')->get();
        $role->load('permissions');
        $assignedIds = $role->permissions->pluck('id')->toArray();

        return view('roles-permissions.edit', compact('role', 'permissions', 'assignedIds'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        if ($role->guard_name !== 'web') {
            abort(404);
        }

        $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $permissionNames = $request->input('permissions', []);
        $role->syncPermissions($permissionNames);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('roles-permissions.index')
            ->with('success', "Permissions updated for role \"{$role->name}\".");
    }
}
