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

        $variantId = $request->get('variant_id');
        $price = $product->sale_price ?? $product->price;
        $sku = $product->sku;
        $options = [];
        $image = $product->images->first()?->image_path ?? '';

        if ($variantId) {
            $variant = $product->variants()->with('attributeValues.attribute')->find($variantId);
            if ($variant) {
                $price = $variant->sale_price ?? $variant->price;
                $sku = $variant->sku;
                foreach ($variant->attributeValues as $av) {
                    $options[$av->attribute->name] = $av->value;
                }
            }
        }

        $cartKey = $variantId ? $product->id . '-' . $variantId : $product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'variant_id' => $variantId,
                'name' => $product->name,
                'price' => (float) $price,
                'quantity' => (int) $quantity,
                'image' => $image,
                'slug' => $product->slug,
                'options' => $options,
                'sku' => $sku,
            ];
        }

        session()->put('cart', $cart);

        // Also save to DB Cart Items
        $userId = auth()->check() ? auth()->id() : null;
        $sessionId = session()->getId();
        
        $dbItem = \App\Models\CartItem::where([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'product_id' => $product->id,
            'product_variant_id' => $variantId,
            'is_saved' => false,
        ])->first();
        
        if ($dbItem) {
            $dbItem->increment('quantity', (int)$quantity);
        } else {
            \App\Models\CartItem::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $product->id,
                'product_variant_id' => $variantId,
                'quantity' => (int)$quantity,
                'is_saved' => false,
            ]);
        }

        $dbCartCount = \App\Models\CartItem::where([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'is_saved' => false,
        ])->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cart_count' => $dbCartCount,
            ]);
        }

        if ($request->has('buy_now')) {
            if (!auth()->check()) {
                // Store the checkout URL as intended, so login redirects there
                session()->put('url.intended', route('store.checkout'));
                return redirect()->route('login');
            }
            return redirect()->route('store.checkout')->with('success', 'Product added to cart!');
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

    protected function getCartQuery()
    {
        if (auth()->check()) {
            return \App\Models\CartItem::where('user_id', auth()->id());
        }
        return \App\Models\CartItem::where('session_id', session()->getId());
    }

    public function apiGet()
    {
        $query = $this->getCartQuery();
        
        // Auto-seed if empty for first time demo view
        if ($query->count() === 0) {
            $user = auth()->user();
            $userId = $user ? $user->id : null;
            $sessionId = session()->getId();
            
            $iphone = \App\Models\Product::where('slug', 'iphone-15-pro')->first();
            $duffel = \App\Models\Product::where('slug', 'premium-leather-duffel')->first() ?: \App\Models\Product::first();
            
            if ($iphone) {
                $variant = $iphone->variants->first();
                \App\Models\CartItem::create([
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'product_id' => $iphone->id,
                    'product_variant_id' => $variant ? $variant->id : null,
                    'quantity' => 1,
                    'is_saved' => false
                ]);
            }
            if ($duffel) {
                \App\Models\CartItem::create([
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'product_id' => $duffel->id,
                    'product_variant_id' => null,
                    'quantity' => 2,
                    'is_saved' => false
                ]);
                \App\Models\CartItem::create([
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'product_id' => $duffel->id,
                    'product_variant_id' => null,
                    'quantity' => 1,
                    'is_saved' => true
                ]);
            }
        }
        
        $items = $this->getCartQuery()->with(['product.featuredImage', 'variant.attributeValues.attribute'])->get()->map(function($item) {
            $options = [];
            if ($item->variant) {
                foreach ($item->variant->attributeValues as $av) {
                    $options[$av->attribute->name] = $av->value;
                }
            } else {
                $options['Color'] = 'Cognac Tan';
            }
            
            $price = $item->variant ? ($item->variant->sale_price ?? $item->variant->price) : ($item->product->sale_price ?? $item->product->price);
            $originalPrice = $item->variant ? $item->variant->price : $item->product->price;
            
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'price' => (float)$price,
                'original_price' => (float)$originalPrice,
                'quantity' => $item->quantity,
                'is_saved' => $item->is_saved,
                'image' => $item->product->featuredImage ? $item->product->featuredImage->image_path : ($item->product->images->first()?->image_path ?? ''),
                'slug' => $item->product->slug,
                'options' => $options
            ];
        });
        
        return response()->json([
            'success' => true,
            'items' => $items
        ]);
    }

    public function apiUpdate(Request $request, $id)
    {
        $cartItem = \App\Models\CartItem::findOrFail($id);
        $qty = $request->input('quantity', 1);
        if ($qty < 1) $qty = 1;
        $cartItem->update(['quantity' => $qty]);
        return response()->json(['success' => true]);
    }

    public function apiRemove($id)
    {
        $cartItem = \App\Models\CartItem::findOrFail($id);
        $cartItem->delete();
        return response()->json(['success' => true]);
    }

    public function apiSaveLater($id)
    {
        $cartItem = \App\Models\CartItem::findOrFail($id);
        $cartItem->update(['is_saved' => true]);
        return response()->json(['success' => true]);
    }

    public function apiMoveToBag($id)
    {
        $cartItem = \App\Models\CartItem::findOrFail($id);
        $cartItem->update(['is_saved' => false]);
        return response()->json(['success' => true]);
    }

    public function apiApplyCoupon(Request $request)
    {
        $code = trim(strtoupper($request->input('code')));
        $coupon = \App\Models\Coupon::where('code', $code)->where('status', true)->first();
        
        if (!$coupon) {
            if ($code === 'SHOPME20') {
                return response()->json([
                    'success' => true,
                    'coupon' => [
                        'code' => 'SHOPME20',
                        'type' => 'percent',
                        'value' => 20,
                        'description' => '20% Off Entire Order'
                    ]
                ]);
            }
            if ($code === 'GOLD1000') {
                return response()->json([
                    'success' => true,
                    'coupon' => [
                        'code' => 'GOLD1000',
                        'type' => 'fixed',
                        'value' => 1000,
                        'description' => 'Flat ₹1,000 Off'
                    ]
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Invalid coupon code. Try SHOPME20']);
        }
        
        return response()->json([
            'success' => true,
            'coupon' => [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => (float)$coupon->value,
                'description' => $coupon->type === 'percent' ? $coupon->value . '% Off entire order' : '₹' . number_format($coupon->value) . ' Off'
            ]
        ]);
    }
}
