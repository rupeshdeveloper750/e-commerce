<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Brand;
use App\Repositories\Admin\BrandRepository;
use App\Services\Admin\BrandService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class BrandController extends Controller
{
    public function __construct(
        protected BrandRepository $brandRepository,
        protected BrandService $brandService
    ) {}

    public function index(Request $request)
    {
        Gate::authorize('manage-brands');

        $filters = $request->only(['search', 'status', 'is_featured', 'trashed', 'sort_by', 'sort_order']);
        $brands = $this->brandRepository->getAllPaginated($filters, 10);

        return view('admin.brand.index', compact('brands'));
    }

    public function create()
    {
        Gate::authorize('manage-brands');

        return view('admin.brand.create');
    }

    public function store(StoreBrandRequest $request)
    {
        Gate::authorize('manage-brands');

        $this->brandService->store($request->validated());

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand created successfully.');
    }

    public function show(Brand $brand)
    {
        Gate::authorize('manage-brands');

        return view('admin.brand.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        Gate::authorize('manage-brands');

        return view('admin.brand.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        Gate::authorize('manage-brands');

        $this->brandService->update($brand, $request->validated());

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand)
    {
        Gate::authorize('manage-brands');

        $this->brandService->destroy($brand);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand soft-deleted successfully.');
    }

    public function restore($id)
    {
        Gate::authorize('manage-brands');

        $brand = Brand::onlyTrashed()->findOrFail($id);
        $this->brandService->restore($brand);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand restored successfully.');
    }

    public function forceDelete($id)
    {
        Gate::authorize('manage-brands');

        $brand = Brand::withTrashed()->findOrFail($id);
        $this->brandService->forceDelete($brand);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand permanently deleted.');
    }

    public function bulkAction(Request $request)
    {
        Gate::authorize('manage-brands');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'numeric',
            'action' => 'required|string|in:delete,restore,force_delete',
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $count = 0;

        if ($action === 'delete') {
            $count = $this->brandService->bulkDelete($ids);
            $msg = "{$count} brands soft-deleted successfully.";
        } elseif ($action === 'restore') {
            $count = $this->brandService->bulkRestore($ids);
            $msg = "{$count} brands restored successfully.";
        } elseif ($action === 'force_delete') {
            $count = $this->brandService->bulkForceDelete($ids);
            $msg = "{$count} brands permanently deleted.";
        }

        return redirect()
            ->route('admin.brands.index')
            ->with('success', $msg);
    }

    public function export()
    {
        Gate::authorize('manage-brands');

        $csv = $this->brandService->exportCsv();

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="brands-export-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    public function import(Request $request)
    {
        Gate::authorize('manage-brands');

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $file = $request->file('csv_file');
        $count = $this->brandService->importCsv($file->getRealPath());

        return redirect()
            ->route('admin.brands.index')
            ->with('success', "Successfully imported {$count} brands.");
    }
}
