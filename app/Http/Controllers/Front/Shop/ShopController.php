<?php

namespace App\Http\Controllers\Front\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'featuredImage'])->where('status', true);

        // Search Filter
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $categoriesInput = is_array($request->category) ? $request->category : explode(',', $request->category);
            $categoryIds = Category::whereIn('slug', $categoriesInput)->pluck('id')->toArray();
            
            if (!empty($categoryIds)) {
                $allCategoryIds = Category::whereIn('parent_id', $categoryIds)
                    ->pluck('id')
                    ->merge($categoryIds)
                    ->unique()
                    ->toArray();
                $query->whereIn('category_id', $allCategoryIds);
            }
        }

        // Brand Filter
        if ($request->filled('brand')) {
            $brandsInput = is_array($request->brand) ? $request->brand : explode(',', $request->brand);
            $query->whereHas('brand', function($q) use ($brandsInput) {
                $q->whereIn('slug', $brandsInput);
            });
        }

        // Price Filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('is_bestseller', 'desc')
                      ->orderByRaw('id % 5 ASC')
                      ->latest();
                break;
        }

        $products = $query->paginate(24)->withQueryString();
        $categories = Category::where('status', true)->orderBy('name')->get();
        $brands = Brand::where('status', true)->orderBy('name')->get();

        return view('store.shop', compact('products', 'categories', 'brands'));
    }
}
