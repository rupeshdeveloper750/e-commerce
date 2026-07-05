@extends('layouts.store')

@section('title', 'Shopping Cart')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-black text-white">Your Shopping Cart</h1>

    @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart as $id => $item)
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-lg shadow-black/25">
                        <div class="flex items-center gap-4">
                            {{-- Product photo --}}
                            <div class="h-20 w-20 shrink-0 rounded-xl overflow-hidden bg-slate-950 border border-slate-800">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" class="h-full w-full object-cover" alt="">
                                @else
                                    <div class="h-full w-full bg-slate-900 text-slate-500 font-bold flex items-center justify-center text-sm">No Photo</div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-white text-sm truncate hover:text-amber-400">
                                    <a href="{{ route('store.product.show', $item['slug']) }}">{{ $item['name'] }}</a>
                                </h3>
                                <p class="text-sm font-semibold text-amber-500 mt-1">₹{{ number_format($item['price'], 2) }}</p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-between sm:justify-end gap-6">
                            {{-- Quantity Update --}}
                            <form action="{{ route('store.cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 rounded-xl border border-slate-850 bg-slate-950 px-2 py-1 text-center text-xs text-white focus:outline-none focus:border-amber-500">
                                <button type="submit" class="rounded-lg bg-slate-800 px-3 py-1.5 text-[11px] font-semibold text-slate-350 hover:bg-slate-750 transition">Update</button>
                            </form>

                            {{-- Remove --}}
                            <a href="{{ route('store.cart.remove', $id) }}" class="text-slate-500 hover:text-red-400 p-2 transition" title="Remove Item">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary Panel --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Order Summary --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 space-y-4 shadow-lg shadow-black/25">
                    <h3 class="text-base font-semibold text-white border-b border-slate-800 pb-3">Order Summary</h3>
                    
                    <div class="space-y-3 text-sm text-slate-350">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="text-white">₹{{ number_format($totals['subtotal'], 2) }}</span>
                        </div>
                        @if($totals['discount'] > 0)
                            <div class="flex justify-between">
                                <span>Coupon Discount</span>
                                <span class="text-red-400">-₹{{ number_format($totals['discount'], 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="text-white">₹{{ number_format($totals['shipping'], 2) }}</span>
                        </div>
                        <div class="border-t border-slate-800/80 pt-3 flex justify-between text-base font-bold text-white">
                            <span>Total</span>
                            <span class="text-amber-500 text-lg">₹{{ number_format($totals['total'], 2) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('store.checkout') }}" class="w-full inline-flex items-center justify-center rounded-xl bg-amber-500 py-3 text-sm font-semibold text-slate-950 shadow-sm hover:bg-amber-400 transition">
                        Proceed to Checkout
                    </a>
                </div>

                {{-- Apply Coupon Code --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 space-y-4 shadow-lg shadow-black/25">
                    <h4 class="text-sm font-semibold text-white">Promotional Coupon</h4>
                    @if(session()->has('coupon'))
                        <div class="bg-slate-950/60 border border-slate-800 rounded-xl p-3 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-white uppercase tracking-wider">{{ session('coupon.code') }}</p>
                                <p class="text-[10px] text-slate-500">Discount of ₹{{ number_format(session('coupon.discount'), 2) }}</p>
                            </div>
                            <a href="{{ route('store.coupon.remove') }}" class="text-xs font-semibold text-red-400 hover:underline">Remove</a>
                        </div>
                    @else
                        <form action="{{ route('store.coupon.apply') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="code" placeholder="Enter coupon..." class="flex-1 bg-slate-950 border border-slate-850 rounded-xl px-3 py-2 text-xs text-slate-200 placeholder-slate-600 focus:outline-none focus:border-amber-500">
                            <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-750 transition">Apply</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-16 text-center space-y-4 max-w-lg mx-auto shadow-xl">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-800/40 text-slate-500 mx-auto">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-white">Your Shopping Cart is Empty</h3>
            <p class="text-sm text-slate-400">Add products to your cart and they will display here.</p>
            <a href="{{ route('store.shop') }}" class="inline-flex rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-slate-950 hover:bg-amber-400 transition">Go to Catalog</a>
        </div>
    @endif
</div>
@endsection
