<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('manage-blogs');

        $query = Blog::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
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

        $blogs = $query->paginate(10)->withQueryString();

        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        Gate::authorize('manage-blogs');

        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-blogs');

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $data = $request->only(['title', 'slug', 'content', 'status', 'meta_title', 'meta_description']);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        $blog = Blog::create($data);

        ActivityLog::log('created', Blog::class, $blog->id, [
            'title' => $blog->title,
        ]);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog article created successfully.');
    }

    public function edit($id)
    {
        Gate::authorize('manage-blogs');

        $blog = Blog::withTrashed()->findOrFail($id);

        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('manage-blogs');

        $blog = Blog::withTrashed()->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $data = $request->only(['title', 'slug', 'content', 'status', 'meta_title', 'meta_description']);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        $original = $blog->only(['title', 'status']);
        $blog->update($data);

        ActivityLog::log('updated', Blog::class, $blog->id, [
            'old' => $original,
            'new' => $blog->only(['title', 'status']),
        ]);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog article updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        Gate::authorize('manage-blogs');

        $blog->delete();

        ActivityLog::log('deleted', Blog::class, $blog->id, [
            'title' => $blog->title,
        ]);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog article soft-deleted successfully.');
    }

    public function restore($id)
    {
        Gate::authorize('manage-blogs');

        $blog = Blog::onlyTrashed()->findOrFail($id);
        $blog->restore();

        ActivityLog::log('restored', Blog::class, $blog->id, [
            'title' => $blog->title,
        ]);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog article restored successfully.');
    }

    public function forceDelete($id)
    {
        Gate::authorize('manage-blogs');

        $blog = Blog::withTrashed()->findOrFail($id);
        $blogTitle = $blog->title;
        $blogId = $blog->id;

        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->forceDelete();

        ActivityLog::log('force_deleted', Blog::class, $blogId, [
            'title' => $blogTitle,
        ]);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog article permanently deleted.');
    }

    public function bulkAction(Request $request)
    {
        Gate::authorize('manage-blogs');

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
                $blog = Blog::find($id);
                if ($blog && $blog->delete()) $count++;
            } elseif ($action === 'restore') {
                $blog = Blog::onlyTrashed()->find($id);
                if ($blog && $blog->restore()) $count++;
            } elseif ($action === 'force_delete') {
                $blog = Blog::withTrashed()->find($id);
                if ($blog) {
                    if ($blog->image) {
                        Storage::disk('public')->delete($blog->image);
                    }
                    if ($blog->forceDelete()) $count++;
                }
            } elseif ($action === 'block') {
                $blog = Blog::find($id);
                if ($blog && $blog->update(['status' => false])) $count++;
            } elseif ($action === 'activate') {
                $blog = Blog::find($id);
                if ($blog && $blog->update(['status' => true])) $count++;
            }
        }

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', "Bulk action '{$action}' successfully applied to {$count} blog articles.");
    }
}
