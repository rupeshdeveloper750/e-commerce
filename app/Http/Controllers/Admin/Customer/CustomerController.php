<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('manage-users');

        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', (bool)$request->status);
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

        $customers = $query->paginate(10)->withQueryString();

        return view('admin.customer.index', compact('customers'));
    }

    public function create()
    {
        Gate::authorize('manage-users');

        return view('admin.customer.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|boolean',
        ]);

        $customer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

        ActivityLog::log('created', User::class, $customer->id, [
            'name' => $customer->name,
            'email' => $customer->email,
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function edit($id)
    {
        Gate::authorize('manage-users');

        $customer = User::withTrashed()->findOrFail($id);

        return view('admin.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('manage-users');

        $customer = User::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|boolean',
        ]);

        $original = $customer->only(['name', 'email', 'status']);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->status = $request->status;

        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
        }

        $customer->save();

        ActivityLog::log('updated', User::class, $customer->id, [
            'old' => $original,
            'new' => $customer->only(['name', 'email', 'status']),
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(User $customer)
    {
        Gate::authorize('manage-users');

        $customer->delete();

        ActivityLog::log('deleted', User::class, $customer->id, [
            'name' => $customer->name,
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer soft-deleted successfully.');
    }

    public function restore($id)
    {
        Gate::authorize('manage-users');

        $customer = User::onlyTrashed()->findOrFail($id);
        $customer->restore();

        ActivityLog::log('restored', User::class, $customer->id, [
            'name' => $customer->name,
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer restored successfully.');
    }

    public function forceDelete($id)
    {
        Gate::authorize('manage-users');

        $customer = User::withTrashed()->findOrFail($id);
        $custName = $customer->name;
        $custId = $customer->id;

        $customer->forceDelete();

        ActivityLog::log('force_deleted', User::class, $custId, [
            'name' => $custName,
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer permanently deleted.');
    }

    public function bulkAction(Request $request)
    {
        Gate::authorize('manage-users');

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
                $customer = User::find($id);
                if ($customer && $customer->delete()) $count++;
            } elseif ($action === 'restore') {
                $customer = User::onlyTrashed()->find($id);
                if ($customer && $customer->restore()) $count++;
            } elseif ($action === 'force_delete') {
                $customer = User::withTrashed()->find($id);
                if ($customer && $customer->forceDelete()) $count++;
            } elseif ($action === 'block') {
                $customer = User::find($id);
                if ($customer && $customer->update(['status' => false])) $count++;
            } elseif ($action === 'activate') {
                $customer = User::find($id);
                if ($customer && $customer->update(['status' => true])) $count++;
            }
        }

        return redirect()
            ->route('admin.customers.index')
            ->with('success', "Bulk action '{$action}' successfully applied to {$count} customers.");
    }
}
