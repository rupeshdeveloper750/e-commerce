<?php

namespace App\Http\Controllers\Front\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('store.cart')->with('error', 'Your cart is empty.');
        }

        $totals = $this->getCheckoutTotals();
        return view('store.checkout', compact('cart', 'totals'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))
            ->where('status', true)
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        if (!$coupon->isValid($subtotal)) {
            return back()->with('error', 'This coupon is either expired or does not meet minimum cart requirements.');
        }

        // Calculate discount
        $discount = 0;
        if ($coupon->type === 'percent') {
            $discount = ($subtotal * $coupon->value) / 100;
        } else {
            $discount = $coupon->value;
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => (float) $discount,
        ]);

        return back()->with('success', 'Coupon applied successfully!');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Coupon removed successfully.');
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'string', 'in:cod,card'],
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('store.cart')->with('error', 'Your cart is empty.');
        }

        $totals = $this->getCheckoutTotals();

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'shipping' => $totals['shipping'],
            'total' => $totals['total'],
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method === 'card' ? 'paid' : 'pending',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'notes' => $request->notes,
        ]);

        // Create order items & reduce product quantities
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);

            $product = Product::find($item['id']);
            if ($product) {
                $product->decrement('quantity', $item['quantity']);
            }
        }

        // Clear session cart & coupon
        session()->forget(['cart', 'coupon']);

        return redirect()->route('store.order.success', $order->id)
            ->with('success', 'Order placed successfully! Your Order number is #' . $order->order_number);
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        return view('store.success', compact('order'));
    }

    protected function getCheckoutTotals(): array
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = session()->get('coupon.discount', 0.00);
        $shipping = $subtotal > 1000 || $subtotal == 0 ? 0.00 : 99.00;
        $total = max(0, ($subtotal - $discount) + $shipping);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'total' => $total,
        ];
    }
}
