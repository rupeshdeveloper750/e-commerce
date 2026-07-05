<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('manage-banners');

        $query = Banner::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('sub_title', 'like', '%' . $search . '%');
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
        $sortBy = $request->input('sort_by', 'sort_order');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $banners = $query->paginate(10)->withQueryString();

        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        Gate::authorize('manage-banners');

        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-banners');

        $request->validate([
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'link' => 'nullable|string|max:1000',
            'status' => 'required|boolean',
            'sort_order' => 'required|integer',
        ]);

        $data = $request->only(['title', 'sub_title', 'link', 'status', 'sort_order']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner = Banner::create($data);

        ActivityLog::log('created', Banner::class, $banner->id, [
            'title' => $banner->title ?? 'Promo Banner',
        ]);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Promo banner created successfully.');
    }

    public function edit($id)
    {
        Gate::authorize('manage-banners');

        $banner = Banner::withTrashed()->findOrFail($id);

        return view('admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('manage-banners');

        $banner = Banner::withTrashed()->findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'link' => 'nullable|string|max:1000',
            'status' => 'required|boolean',
            'sort_order' => 'required|integer',
        ]);

        $data = $request->only(['title', 'sub_title', 'link', 'status', 'sort_order']);

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $original = $banner->only(['title', 'status']);
        $banner->update($data);

        ActivityLog::log('updated', Banner::class, $banner->id, [
            'old' => $original,
            'new' => $banner->only(['title', 'status']),
        ]);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Promo banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        Gate::authorize('manage-banners');

        $banner->delete();

        ActivityLog::log('deleted', Banner::class, $banner->id, [
            'title' => $banner->title ?? 'Promo Banner',
        ]);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Promo banner soft-deleted successfully.');
    }

    public function restore($id)
    {
        Gate::authorize('manage-banners');

        $banner = Banner::onlyTrashed()->findOrFail($id);
        $banner->restore();

        ActivityLog::log('restored', Banner::class, $banner->id, [
            'title' => $banner->title ?? 'Promo Banner',
        ]);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Promo banner restored successfully.');
    }

    public function forceDelete($id)
    {
        Gate::authorize('manage-banners');

        $banner = Banner::withTrashed()->findOrFail($id);
        $bannerTitle = $banner->title ?? 'Promo Banner';
        $bannerId = $banner->id;

        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->forceDelete();

        ActivityLog::log('force_deleted', Banner::class, $bannerId, [
            'title' => $bannerTitle,
        ]);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Promo banner permanently deleted.');
    }

    public function bulkAction(Request $request)
    {
        Gate::authorize('manage-banners');

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
                $banner = Banner::find($id);
                if ($banner && $banner->delete()) $count++;
            } elseif ($action === 'restore') {
                $banner = Banner::onlyTrashed()->find($id);
                if ($banner && $banner->restore()) $count++;
            } elseif ($action === 'force_delete') {
                $banner = Banner::withTrashed()->find($id);
                if ($banner) {
                    if ($banner->image) {
                        Storage::disk('public')->delete($banner->image);
                    }
                    if ($banner->forceDelete()) $count++;
                }
            } elseif ($action === 'block') {
                $banner = Banner::find($id);
                if ($banner && $banner->update(['status' => false])) $count++;
            } elseif ($action === 'activate') {
                $banner = Banner::find($id);
                if ($banner && $banner->update(['status' => true])) $count++;
            }
        }

        return redirect()
            ->route('admin.banners.index')
            ->with('success', "Bulk action '{$action}' successfully applied to {$count} banners.");
    }
}
