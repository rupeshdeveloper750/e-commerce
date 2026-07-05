@extends('layouts.store')

@section('title', 'Checkout')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-black text-white">Secure Checkout</h1>

    <form action="{{ route('store.checkout.place') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Billing / Shipping Fields --}}
            <div class="lg:col-span-2 space-y-6 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-lg shadow-black/25">
                <h3 class="text-base font-semibold text-white border-b border-slate-800 pb-3">Delivery Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">First Name <span class="text-red-400">*</span></label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', auth()->user()->name) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-amber-500 @error('first_name') border-red-500 @enderror">
                        @error('first_name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Last Name <span class="text-red-400">*</span></label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-amber-500 @error('last_name') border-red-500 @enderror">
                        @error('last_name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Email Address <span class="text-red-400">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-amber-500 @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Phone Number <span class="text-red-400">*</span></label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-amber-500 @error('phone') border-red-500 @enderror">
                        @error('phone') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Street Address <span class="text-red-400">*</span></label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Apartment, suite, unit, building, street, etc." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-amber-500 @error('address') border-red-500 @enderror">
                    @error('address') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">City <span class="text-red-400">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-amber-500 @error('city') border-red-500 @enderror">
                        @error('city') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="state" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">State <span class="text-red-400">*</span></label>
                        <input type="text" id="state" name="state" value="{{ old('state') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-amber-500 @error('state') border-red-500 @enderror">
                        @error('state') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="zip_code" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Zip/Postal Code <span class="text-red-400">*</span></label>
                        <input type="text" id="zip_code" name="zip_code" value="{{ old('zip_code') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-amber-500 @error('zip_code') border-red-500 @enderror">
                        @error('zip_code') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Order Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Notes about your order, e.g. special instructions for delivery." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200 resize-none focus:outline-none focus:border-amber-500"></textarea>
                </div>

                {{-- Payment Method --}}
                <div class="space-y-3">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wide">Payment Method <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 border border-slate-800 bg-slate-950 rounded-xl p-4 cursor-pointer hover:border-amber-500 transition">
                            <input type="radio" name="payment_method" value="cod" checked class="text-amber-500 bg-slate-900 border-slate-850 focus:ring-amber-500 focus:ring-offset-slate-950">
                            <div>
                                <p class="text-sm font-semibold text-white">Cash on Delivery (COD)</p>
                                <p class="text-xs text-slate-500">Pay cash upon delivery.</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 border border-slate-800 bg-slate-950 rounded-xl p-4 cursor-pointer hover:border-amber-500 transition">
                            <input type="radio" name="payment_method" value="card" class="text-amber-500 bg-slate-900 border-slate-850 focus:ring-amber-500 focus:ring-offset-slate-950">
                            <div>
                                <p class="text-sm font-semibold text-white">Credit / Debit Card</p>
                                <p class="text-xs text-slate-500">Pay securely with card.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Summary & Place Order --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-4 shadow-lg shadow-black/25">
                    <h3 class="text-base font-semibold text-white border-b border-slate-800 pb-3">Your Order</h3>
                    
                    {{-- Order Items --}}
                    <div class="max-h-48 overflow-y-auto divide-y divide-slate-800/60 pr-1">
                        @foreach($cart as $item)
                            <div class="flex items-center justify-between py-3 text-xs">
                                <span class="text-slate-300 truncate max-w-[150px]">{{ $item['name'] }} <span class="text-slate-500 font-bold ml-1">× {{ $item['quantity'] }}</span></span>
                                <span class="font-bold text-white">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-slate-800 pt-3 space-y-2 text-sm text-slate-350">
                        <div class="flex justify-between text-xs">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($totals['subtotal'], 2) }}</span>
                        </div>
                        @if($totals['discount'] > 0)
                            <div class="flex justify-between text-xs">
                                <span>Discount</span>
                                <span class="text-red-400">-₹{{ number_format($totals['discount'], 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-xs">
                            <span>Shipping</span>
                            <span>₹{{ number_format($totals['shipping'], 2) }}</span>
                        </div>
                        <div class="border-t border-slate-800/80 pt-2 flex justify-between text-sm font-bold text-white">
                            <span>Grand Total</span>
                            <span class="text-amber-500">₹{{ number_format($totals['total'], 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-amber-500 py-3 text-sm font-semibold text-slate-950 shadow-sm hover:bg-amber-400 transition">
                        Place Order
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
