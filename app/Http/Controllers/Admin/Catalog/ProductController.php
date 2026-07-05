<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Repositories\Admin\ProductRepository;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductService $productService
    ) {}

    public function index(Request $request)
    {
        Gate::authorize('manage-products');

        $filters = $request->only(['search', 'category_id', 'brand_id', 'status', 'is_featured', 'stock_level', 'trashed', 'sort_by', 'sort_order']);
        $products = $this->productRepository->getAllPaginated($filters, 10);
        
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();

        return view('admin.product.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        Gate::authorize('manage-products');

        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $attributes = Attribute::with('values')->get();

        return view('admin.product.create', compact('categories', 'brands', 'attributes'));
    }

    public function store(StoreProductRequest $request)
    {
        Gate::authorize('manage-products');

        $this->productService->store($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show($id)
    {
        Gate::authorize('manage-products');

        $product = $this->productRepository->find($id, true);

        return view('admin.product.show', compact('product'));
    }

    public function edit($id)
    {
        Gate::authorize('manage-products');

        $product = $this->productRepository->find($id, true);
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $attributes = Attribute::with('values')->get();

        return view('admin.product.edit', compact('product', 'categories', 'brands', 'attributes'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        Gate::authorize('manage-products');

        $product = Product::withTrashed()->findOrFail($id);
        $this->productService->update($product, $request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        Gate::authorize('manage-products');

        $this->productService->destroy($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product soft-deleted successfully.');
    }

    public function restore($id)
    {
        Gate::authorize('manage-products');

        $product = Product::onlyTrashed()->findOrFail($id);
        $this->productService->restore($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product restored successfully.');
    }

    public function forceDelete($id)
    {
        Gate::authorize('manage-products');

        $product = Product::withTrashed()->findOrFail($id);
        $this->productService->forceDelete($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product permanently deleted.');
    }

    public function bulkAction(Request $request)
    {
        Gate::authorize('manage-products');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'numeric',
            'action' => 'required|string|in:delete,restore,force_delete',
        ]);

        $ids = $request->ids;
        $action = $request->action;
        $count = 0;

        if ($action === 'delete') {
            $count = $this->productService->bulkDelete($ids);
            $msg = "{$count} products soft-deleted successfully.";
        } elseif ($action === 'restore') {
            $count = $this->productService->bulkRestore($ids);
            $msg = "{$count} products restored successfully.";
        } elseif ($action === 'force_delete') {
            $count = $this->productService->bulkForceDelete($ids);
            $msg = "{$count} products permanently deleted.";
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', $msg);
    }

    public function export()
    {
        Gate::authorize('manage-products');

        $csv = $this->productService->exportCsv();

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products-export-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    public function import(Request $request)
    {
        Gate::authorize('manage-products');

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $file = $request->file('csv_file');
        $count = $this->productService->importCsv($file->getRealPath());

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Successfully imported {$count} products.");
    }
}
