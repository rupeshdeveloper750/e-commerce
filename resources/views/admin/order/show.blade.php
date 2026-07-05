@extends('admin.layouts.app')

@section('title', 'Order Details - #' . $order->order_number)

@section('content')
<div class="mx-auto max-w-screen-2xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <nav class="flex items-center gap-1.5 text-sm text-slate-400">
                <a href="{{ route('admin.orders.index') }}" class="hover:text-amber-500">Orders</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
                <span class="font-medium text-slate-200">#{{ $order->order_number }}</span>
            </nav>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-white">Order Details</h1>
            <p class="mt-1 text-sm text-slate-400">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-700 bg-slate-900 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-slate-800 transition">
                Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Items & Shipping --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Items Card --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Ordered Items</h2>
                </div>
                <div class="divide-y divide-slate-800/60">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 p-6">
                            <div class="h-16 w-16 shrink-0 rounded-lg overflow-hidden border border-slate-800 bg-slate-950">
                                @if($item->product && $item->product->images->first())
                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" class="h-full w-full object-cover" alt="{{ $item->product_name }}">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-xs text-slate-500 font-bold bg-slate-900">
                                        {{ strtoupper(substr($item->product_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-white truncate">{{ $item->product_name }}</h4>
                                <p class="mt-1 text-xs text-slate-400">
                                    ₹{{ number_format($item->price, 2) }} × {{ $item->quantity }}
                                </p>
                            </div>
                            <div class="text-sm font-bold text-white">
                                ₹{{ number_format($item->price * $item->quantity, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="bg-slate-950/40 p-6 border-t border-slate-800 space-y-2">
                    <div class="flex justify-between text-sm text-slate-400">
                        <span>Subtotal</span>
                        <span class="text-slate-200">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-sm text-slate-400">
                            <span>Discount</span>
                            <span class="text-red-400">-₹{{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm text-slate-400">
                        <span>Shipping</span>
                        <span class="text-slate-200">₹{{ number_format($order->shipping, 2) }}</span>
                    </div>
                    <div class="border-t border-slate-800/60 pt-2 flex justify-between text-base font-bold text-white">
                        <span>Grand Total</span>
                        <span class="text-amber-500 text-lg">₹{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Shipping Address Card --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20 p-6 space-y-4">
                <h3 class="text-base font-semibold text-white">Delivery Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-300">
                    <div class="space-y-1.5">
                        <p class="text-xs text-slate-500 font-semibold uppercase">Recipient</p>
                        <p class="font-medium text-white">{{ $order->first_name }} {{ $order->last_name }}</p>
                        <p>Phone: {{ $order->phone }}</p>
                        <p>Email: {{ $order->email }}</p>
                    </div>
                    <div class="space-y-1.5">
                        <p class="text-xs text-slate-500 font-semibold uppercase">Delivery Address</p>
                        <p class="leading-relaxed">{{ $order->address }}</p>
                        <p>{{ $order->city }}, {{ $order->state }} - {{ $order->zip_code }}</p>
                    </div>
                </div>
                @if($order->notes)
                    <div class="border-t border-slate-800 pt-4">
                        <p class="text-xs text-slate-500 font-semibold uppercase mb-1">Customer Notes</p>
                        <p class="text-sm text-slate-400 leading-relaxed italic">"{{ $order->notes }}"</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Status updates --}}
        <div class="space-y-6">
            {{-- Update Status Card --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20 p-6 space-y-6">
                <h3 class="text-base font-semibold text-white">Process Order</h3>

                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    {{-- Order Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-200 mb-1.5">Order Status</label>
                        <select id="status" name="status" class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                            <option value="pending" @selected($order->status == 'pending')>Pending</option>
                            <option value="processing" @selected($order->status == 'processing')>Processing</option>
                            <option value="shipped" @selected($order->status == 'shipped')>Shipped</option>
                            <option value="delivered" @selected($order->status == 'delivered')>Delivered</option>
                            <option value="cancelled" @selected($order->status == 'cancelled')>Cancelled</option>
                        </select>
                    </div>

                    {{-- Payment Status --}}
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-slate-200 mb-1.5">Payment Status</label>
                        <select id="payment_status" name="payment_status" class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-2.5 text-sm text-white focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                            <option value="pending" @selected($order->payment_status == 'pending')>Pending</option>
                            <option value="paid" @selected($order->payment_status == 'paid')>Paid</option>
                            <option value="failed" @selected($order->payment_status == 'failed')>Failed</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 py-3 text-sm font-semibold text-slate-950 shadow-sm hover:bg-amber-400 transition">
                        Save Status Updates
                    </button>
                </form>
            </div>

            {{-- Summary details --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20 p-6 space-y-4">
                <h3 class="text-base font-semibold text-white">Payment Summary</h3>
                <div class="text-sm space-y-2.5 text-slate-300">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Payment Method:</span>
                        <span class="font-medium text-white uppercase">{{ $order->payment_method }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Items Count:</span>
                        <span class="font-medium text-white">{{ $order->items->sum('quantity') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
