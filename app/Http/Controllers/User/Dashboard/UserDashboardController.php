<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product.featuredImage'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
            
        $wishlist = Wishlist::with(['product.featuredImage'])
            ->where('user_id', auth()->id())
            ->get();

        // Load reviews submitted by this customer
        $reviews = \App\Models\Review::with(['product.featuredImage'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        // Load active coupons
        $coupons = \App\Models\Coupon::where('status', true)
            ->where('expiry_date', '>=', now())
            ->take(6)
            ->get();

        // Load recently viewed products
        $recentlyViewed = \App\Models\Product::with('featuredImage')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        // Load recommended products
        $recommendedProducts = \App\Models\Product::with(['featuredImage', 'brand', 'reviews'])
            ->where('status', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();
        if ($recommendedProducts->isEmpty()) {
            $recommendedProducts = \App\Models\Product::with(['featuredImage', 'brand', 'reviews'])
                ->where('status', true)
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        return view('user.dashboard', compact('orders', 'wishlist', 'reviews', 'coupons', 'recentlyViewed', 'recommendedProducts'));
    }

    public function addToWishlist(Product $product)
    {
        $exists = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
            ]);
            return back()->with('success', 'Product added to wishlist!');
        }

        return back()->with('info', 'Product is already in your wishlist.');
    }

    public function removeFromWishlist(int $id)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return back()->with('success', 'Product removed from wishlist.');
        }

        return back()->with('error', 'Item not found.');
    }
}
