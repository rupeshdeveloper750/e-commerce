<?php

namespace App\Http\Controllers\Front\Cart;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $totals = $this->getCartTotals();
        return view('store.cart', compact('cart', 'totals'));
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->get('quantity', 1);
        $cart = session()->get('cart', []);

        $price = $product->sale_price ?? $product->price;
        $image = $product->images->first()?->image_path ?? '';

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $price,
                'quantity' => (int) $quantity,
                'image' => $image,
                'slug' => $product->slug,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cart_count' => count($cart),
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = (int) $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    public function remove(int $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Product removed from cart.');
    }

    protected function getCartTotals(): array
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = session()->get('coupon.discount', 0.00);
        $shipping = $subtotal > 1000 || $subtotal == 0 ? 0.00 : 99.00; // Free shipping above ₹1000
        $total = max(0, ($subtotal - $discount) + $shipping);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'total' => $total,
        ];
    }
}
