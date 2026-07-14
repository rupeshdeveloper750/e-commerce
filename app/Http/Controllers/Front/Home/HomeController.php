<?php

namespace App\Http\Controllers\Front\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

use App\Models\BrandFeature;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', true)->orderBy('sort_order')->get();
        $featuredCategories = Category::where('status', true)->whereNull('parent_id')->orderBy('sort_order')->take(6)->get();
        $featuredProducts = Product::with('featuredImage')
            ->where('status', true)
            ->where('is_featured', true)
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByRaw('bestseller_sort_order IS NULL, bestseller_sort_order ASC')
            ->take(8)
            ->get();
        $latestProducts = Product::with(['featuredImage', 'category'])
            ->where('status', true)
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get()
            ->unique(function ($product) {
                return $product->category->parent_id ?? $product->category_id;
            })
            ->take(4);

        $brandFeatures = BrandFeature::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        $brandStory = [
            'eyebrow' => 'Why ShopMe',
            'heading' => 'Elevating eCommerce to Fine Art',
            'description' => 'We believe details are not details; they make the design. Every product is curated with quiet luxury standards.',
            'cta_text' => 'Discover Our Story',
            'cta_link' => '/about',
        ];

        return view('store.home', compact(
            'banners', 
            'featuredCategories', 
            'featuredProducts', 
            'latestProducts',
            'brandFeatures',
            'brandStory'
        ));
    }
}
