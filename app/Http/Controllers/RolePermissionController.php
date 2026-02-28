<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
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

        $permissions = Permission::where('guard_name', 'web')->orderBy('name')->get();

        return view('roles-permissions.index', compact('roles', 'permissions'));
    }

    public function createPermission(): View
    {
        return view('roles-permissions.permissions.create');
    }

    public function storePermission(StorePermissionRequest $request): RedirectResponse
    {
        Permission::create([
            'name' => $request->validated('name'),
            'guard_name' => 'web',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('roles-permissions.index')
            ->with('success', 'Permission created successfully. You can now assign it to roles.');
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
