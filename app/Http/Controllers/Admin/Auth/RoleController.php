<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('manage-roles');

        $roles = Role::withCount('permissions')->paginate(10);
        return view('admin.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('manage-roles');

        $permissions = Permission::all();
        return view('admin.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-roles');

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        Gate::authorize('manage-roles');

        if ($role->slug === 'super-admin') {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Super Admin role cannot be edited.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        Gate::authorize('manage-roles');

        if ($role->slug === 'super-admin') {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Super Admin role cannot be updated.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Gate::authorize('manage-roles');

        if ($role->slug === 'super-admin') {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Super Admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
