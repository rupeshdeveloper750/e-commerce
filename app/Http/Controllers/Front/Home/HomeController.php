<?php

namespace App\Http\Controllers\Front\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', true)->orderBy('sort_order')->get();
        $featuredCategories = Category::where('status', true)->whereNull('parent_id')->orderBy('sort_order')->take(6)->get();
        $featuredProducts = Product::with('featuredImage')->where('status', true)->where('is_featured', true)->latest()->take(8)->get();
        $latestProducts = Product::with('featuredImage')->where('status', true)->latest()->take(8)->get();

        return view('store.home', compact('banners', 'featuredCategories', 'featuredProducts', 'latestProducts'));
    }
}
