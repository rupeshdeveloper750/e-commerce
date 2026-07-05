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
            $categorySlug = $request->category;
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Brand Filter
        if ($request->filled('brand')) {
            $brandSlug = $request->brand;
            $query->whereHas('brand', function($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
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
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', true)->orderBy('name')->get();
        $brands = Brand::where('status', true)->orderBy('name')->get();

        return view('store.shop', compact('products', 'categories', 'brands'));
    }
}
