<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('manage-products');

        $query = Product::with(['variants', 'variants.attributeValues', 'category', 'brand']);

        // Search Product Name or SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%')
                  ->orWhereHas('variants', function ($v) use ($search) {
                      $v->where('sku', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter Stock Level
        if ($request->filled('stock_level')) {
            $stock = $request->stock_level;
            if ($stock === 'low') {
                $query->where(function ($q) {
                    $q->where('quantity', '<=', 5)
                      ->orWhereHas('variants', function ($v) {
                          $v->where('quantity', '<=', 5);
                      });
                });
            } elseif ($stock === 'out') {
                $query->where(function ($q) {
                    $q->where('quantity', '=', 0)
                      ->orWhereHas('variants', function ($v) {
                          $v->where('quantity', '=', 0);
                      });
                });
            } elseif ($stock === 'in_stock') {
                $query->where(function ($q) {
                    $q->where('quantity', '>', 5)
                      ->whereDoesntHave('variants')
                      ->orWhereHas('variants', function ($v) {
                          $v->where('quantity', '>', 5);
                      });
                });
            }
        }

        $products = $query->paginate(15)->withQueryString();

        return view('admin.inventory.index', compact('products'));
    }

    public function update(Request $request)
    {
        Gate::authorize('manage-products');

        $request->validate([
            'id' => 'required|numeric',
            'type' => 'required|string|in:product,variant',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($request->type === 'product') {
            $product = Product::findOrFail($request->id);
            $oldQty = $product->quantity;
            $product->quantity = $request->quantity;
            $product->save();

            ActivityLog::log('updated', Product::class, $product->id, [
                'field' => 'quantity',
                'old' => $oldQty,
                'new' => $request->quantity,
                'name' => $product->name,
            ]);
        } else {
            $variant = ProductVariant::findOrFail($request->id);
            $oldQty = $variant->quantity;
            $variant->quantity = $request->quantity;
            $variant->save();

            ActivityLog::log('updated', ProductVariant::class, $variant->id, [
                'field' => 'quantity',
                'old' => $oldQty,
                'new' => $request->quantity,
                'sku' => $variant->sku,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stock inventory updated successfully.'
            ]);
        }

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Stock inventory updated successfully.');
    }
}
