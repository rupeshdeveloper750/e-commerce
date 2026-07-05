<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        $wishlist = Wishlist::with('product.featuredImage')
            ->where('user_id', auth()->id())
            ->get();

        return view('user.dashboard', compact('orders', 'wishlist'));
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
