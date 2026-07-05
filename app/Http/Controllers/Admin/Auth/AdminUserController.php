<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('manage-admins');

        $query = Admin::with('roles');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', (bool)$request->status);
        }

        // Role
        if ($request->filled('role_id')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        // Trashed
        if ($request->trashed === 'only') {
            $query->onlyTrashed();
        } elseif ($request->trashed === 'with') {
            $query->withTrashed();
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $admins = $query->paginate(10)->withQueryString();
        $roles = Role::all();

        return view('admin.admin-user.index', compact('admins', 'roles'));
    }

    public function create()
    {
        Gate::authorize('manage-admins');

        $roles = Role::all();
        return view('admin.admin-user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-admins');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|boolean',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        $admin->roles()->sync($request->roles);

        ActivityLog::log('created', Admin::class, $admin->id, [
            'name' => $admin->name,
            'email' => $admin->email,
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin user created successfully.');
    }

    public function edit($id)
    {
        Gate::authorize('manage-admins');

        $admin = Admin::withTrashed()->with('roles')->findOrFail($id);
        $roles = Role::all();

        return view('admin.admin-user.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('manage-admins');

        $admin = Admin::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|boolean',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $original = $admin->only(['name', 'email', 'status']);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->status = $request->status;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();
        $admin->roles()->sync($request->roles);

        ActivityLog::log('updated', Admin::class, $admin->id, [
            'old' => $original,
            'new' => $admin->only(['name', 'email', 'status']),
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin user updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        Gate::authorize('manage-admins');

        $admin->delete();

        ActivityLog::log('deleted', Admin::class, $admin->id, [
            'name' => $admin->name,
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin user soft-deleted successfully.');
    }

    public function restore($id)
    {
        Gate::authorize('manage-admins');

        $admin = Admin::onlyTrashed()->findOrFail($id);
        $admin->restore();

        ActivityLog::log('restored', Admin::class, $admin->id, [
            'name' => $admin->name,
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin user restored successfully.');
    }

    public function forceDelete($id)
    {
        Gate::authorize('manage-admins');

        $admin = Admin::withTrashed()->findOrFail($id);
        $adminName = $admin->name;
        $adminId = $admin->id;

        $admin->roles()->detach();
        $admin->forceDelete();

        ActivityLog::log('force_deleted', Admin::class, $adminId, [
            'name' => $adminName,
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin user permanently deleted.');
    }

    public function bulkAction(Request $request)
    {
        Gate::authorize('manage-admins');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'numeric',
            'action' => 'required|string|in:delete,restore,force_delete,block,activate',
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $count = 0;

        foreach ($ids as $id) {
            if ($action === 'delete') {
                $admin = Admin::find($id);
                if ($admin && $admin->delete()) $count++;
            } elseif ($action === 'restore') {
                $admin = Admin::onlyTrashed()->find($id);
                if ($admin && $admin->restore()) $count++;
            } elseif ($action === 'force_delete') {
                $admin = Admin::withTrashed()->find($id);
                if ($admin) {
                    $admin->roles()->detach();
                    if ($admin->forceDelete()) $count++;
                }
            } elseif ($action === 'block') {
                $admin = Admin::find($id);
                if ($admin && $admin->update(['status' => false])) $count++;
            } elseif ($action === 'activate') {
                $admin = Admin::find($id);
                if ($admin && $admin->update(['status' => true])) $count++;
            }
        }

        return redirect()
            ->route('admin.admins.index')
            ->with('success', "Bulk action '{$action}' successfully applied to {$count} admin users.");
    }
}
