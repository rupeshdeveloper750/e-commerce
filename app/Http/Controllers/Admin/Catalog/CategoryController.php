<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\Admin\CategoryRepository;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected CategoryService $categoryService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('manage-categories');

        $filters = $request->only(['search', 'status', 'parent_id', 'trashed', 'sort_by', 'sort_order']);
        $categories = $this->categoryRepository->getAllPaginated($filters, 10);
        $parentCategories = $this->categoryRepository->getParentCategories();

        return view('admin.category.index', compact('categories', 'parentCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('manage-categories');

        $categories = $this->categoryRepository->getParentCategories();
        return view('admin.category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Gate::authorize('manage-categories');

        $this->categoryService->store($request->validated());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        Gate::authorize('manage-categories');

        return view('admin.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        Gate::authorize('manage-categories');

        $categories = $this->categoryRepository->getParentCategories();

        return view('admin.category.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        Gate::authorize('manage-categories');

        $this->categoryService->update(
            $category,
            $request->validated()
        );

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage. (Soft Delete)
     */
    public function destroy(Category $category)
    {
        Gate::authorize('manage-categories');

        $this->categoryService->destroy($category);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category soft-deleted successfully.');
    }

    /**
     * Restore the specified soft-deleted resource.
     */
    public function restore($id)
    {
        Gate::authorize('manage-categories');

        $category = Category::onlyTrashed()->findOrFail($id);
        $this->categoryService->restore($category);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category restored successfully.');
    }

    /**
     * Permanently delete the specified resource.
     */
    public function forceDelete($id)
    {
        Gate::authorize('manage-categories');

        $category = Category::withTrashed()->findOrFail($id);
        $this->categoryService->forceDelete($category);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category permanently deleted.');
    }

    /**
     * Bulk actions (Soft Delete, Restore, Force Delete)
     */
    public function bulkAction(Request $request)
    {
        Gate::authorize('manage-categories');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'numeric',
            'action' => 'required|string|in:delete,restore,force_delete',
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $count = 0;

        if ($action === 'delete') {
            $count = $this->categoryService->bulkDelete($ids);
            $msg = "{$count} categories soft-deleted successfully.";
        } elseif ($action === 'restore') {
            $count = $this->categoryService->bulkRestore($ids);
            $msg = "{$count} categories restored successfully.";
        } elseif ($action === 'force_delete') {
            $count = $this->categoryService->bulkForceDelete($ids);
            $msg = "{$count} categories permanently deleted.";
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', $msg ?? 'Bulk action completed.');
    }

    /**
     * Export categories to CSV
     */
    public function export()
    {
        Gate::authorize('manage-categories');

        $csv = $this->categoryService->exportCsv();
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="categories-export-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * Import categories from CSV
     */
    public function import(Request $request)
    {
        Gate::authorize('manage-categories');

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $file = $request->file('csv_file');
        $count = $this->categoryService->importCsv($file->getRealPath());

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Successfully imported {$count} categories.");
    }
}