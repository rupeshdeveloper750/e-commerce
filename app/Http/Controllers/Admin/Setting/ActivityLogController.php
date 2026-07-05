<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('manage-logs');

        $query = ActivityLog::with('causer');

        // Search action or details
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', '%' . $search . '%')
                  ->orWhere('model', 'like', '%' . $search . '%')
                  ->orWhere('details', 'like', '%' . $search . '%');
            });
        }

        // Causer Admin
        if ($request->filled('admin_id')) {
            $query->where('causer_id', $request->admin_id)
                  ->where('causer_type', Admin::class);
        }

        // Action type
        if ($request->filled('action_type')) {
            $query->where('action', $request->action_type);
        }

        $logs = $query->latest()->paginate(20)->withQueryString();
        $admins = Admin::all();

        return view('admin.activity-log.index', compact('logs', 'admins'));
    }
}
