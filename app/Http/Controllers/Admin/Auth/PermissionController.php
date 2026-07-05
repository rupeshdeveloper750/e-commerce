<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-roles');

        $permissions = Permission::paginate(15);
        return view('admin.permission.index', compact('permissions'));
    }
}
