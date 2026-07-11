@extends('layouts.store')

@section('title', 'Checkout')

@section('content')
<div class="bg-[#FAFAFA] -mx-4 sm:-mx-6 lg:-mx-8 -mt-24 pt-28 pb-16 min-h-screen text-gray-900">
@php
if (!function_exists('getCheckoutItemImage')) {
    function getCheckoutItemImage($src) {
        if (!$src) {
            return 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=400';
        }
        if (str_starts_with($src, 'http')) {
            return $src;
        }
        $lower = strtolower($src);
        if (str_contains($lower, 'electronics') || str_contains($lower, 'phone') || str_contains($lower, 'tech')) {
            return 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400';
        }
        if (str_contains($lower, 'watch')) {
            return 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=400';
        }
        if (str_contains($lower, 'shoe') || str_contains($lower, 'footwear') || str_contains($lower, 'sneaker')) {
            return 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400';
        }
        if (str_contains($lower, 'bag') || str_contains($lower, 'backpack') || str_contains($lower, 'satchel')) {
            return 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=400';
        }
        if (str_contains($lower, 'iphone') || str_contains($lower, 'digital') || str_contains($lower, 'gold')) {
            return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80';
        }
        return asset('storage/' . $src);
    }
}
@endphp
    <div 
        x-data="{
            // Form properties bound with x-model
            form: {
                first_name: '{{ old('first_name', auth()->user()->name) }}',
                last_name: '{{ old('last_name') }}',
                email: '{{ old('email', auth()->user()->email) }}',
                phone: '{{ old('phone') }}',
                address: '{{ old('address') }}',
                city: '{{ old('city') }}',
                state: '{{ old('state') }}',
                zip_code: '{{ old('zip_code') }}',
                notes: '{{ old('notes') }}',
                landmark: '{{ old('landmark') }}',
                payment_method: 'cod', // cod or card
                delivery_method: 'standard' // standard, express, sameday
            },

            // Saved Addresses for fast auto-fill selection
            savedAddresses: [
                { label: 'Home Address', name: '{{ auth()->user()->name }}', street: '124, Luxury Boulevard, Bandra West', city: 'Mumbai', state: 'Maharashtra', zip_code: '400050', phone: '9876543210', is_default: true },
                { label: 'Office Address', name: '{{ auth()->user()->name }}', street: 'Level 14, Capital Tower, Outer Ring Road', city: 'Bangalore', state: 'Karnataka', zip_code: '560103', phone: '9876543211', is_default: false }
            ],
            selectedAddressIdx: -1,

            selectAddress(idx) {
                this.selectedAddressIdx = idx;
                const addr = this.savedAddresses[idx];
                const nameParts = addr.name.split(' ');
                this.form.first_name = nameParts[0] || '';
                this.form.last_name = nameParts.slice(1).join(' ') || '';
                this.form.address = addr.street;
                this.form.city = addr.city;
                this.form.state = addr.state;
                this.form.zip_code = addr.zip_code;
                this.form.phone = addr.phone;
            },

            // Delivery Costs and Estimates
            deliveryOptions: [
                { id: 'standard', name: 'Standard Delivery', price: 0, time: '3-5 Business Days', desc: 'Free air express shipping' },
                { id: 'express', name: 'Express Delivery', price: 150, time: '1-2 Business Days', desc: 'Next-day priority air flight' },
                { id: 'sameday', name: 'Next Day Delivery', price: 350, time: 'Tomorrow', desc: 'Instant local courier dispatch' }
            ],

            // 2 Payment Methods (COD and Online/Razorpay Card Payment)
            paymentOptions: [
                { id: 'cod', name: 'Cash on Delivery', method: 'cod', desc: 'Pay with cash upon arrival of your package.', badge: 'Pay on Arrival', icon: 'cash' },
                { id: 'card', name: 'Online Payment', method: 'card', desc: 'Pay securely via Credit/Debit Cards, UPI, Wallets or Netbanking.', badge: 'Secure Gateway', icon: 'card' }
            ],
            selectedPaymentOption: 'cod',

            selectPayment(option) {
                this.selectedPaymentOption = option.id;
                this.form.payment_method = option.method;
            },

            // Collapsible Summary State for Mobile Viewports
            summaryOpen: false
        }"
        class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8"
    >
        {{-- Refined Compact Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-150 pb-5 mb-8 font-sans">
            <div>
                <a href="{{ route('store.cart') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-gray-900 transition">
                    <svg class="w-4 h-4 text-gray-455" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Secure Checkout
                </a>
                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Complete your order safely</p>
            </div>
            
            <div class="flex items-center gap-4 text-xs font-bold">
                <div class="flex items-center gap-1.5 text-[#16A34A] bg-green-50 border border-green-150 px-3 py-1.5 rounded-xl">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    <span>SSL Protected Checkout</span>
                </div>
                <div class="bg-gray-55 border border-gray-150 px-3 py-1.5 rounded-xl text-gray-650">
                    <span class="text-gray-400 font-semibold text-[10px] uppercase">Delivery: </span>
                    <span class="text-gray-900">Tomorrow</span>
                </div>
            </div>
        </div>

        {{-- Elegant Segmented Checkout Steps Tracker --}}
        <div class="mb-10 bg-white border border-gray-150 rounded-2xl p-4 shadow-sm">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-0 sm:divide-x divide-gray-100 font-sans">
                <!-- Step 1: Cart (Completed) -->
                <div class="flex items-center gap-3 pl-2 sm:pl-4 py-1.5 sm:py-0">
                    <div class="w-8 h-8 rounded-xl bg-brand-50 text-[#B88A44] flex items-center justify-center shrink-0 border border-[#B88A44]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[8px] font-extrabold text-[#B88A44] uppercase tracking-wider">Step 01</span>
                        <span class="block text-[11px] font-extrabold text-gray-900 tracking-wide uppercase">Shopping Bag</span>
                    </div>
                </div>

                <!-- Step 2: Shipping Address (Active) -->
                <div class="flex items-center gap-3 pl-2 sm:pl-6 py-1.5 sm:py-0">
                    <div class="w-8 h-8 rounded-xl bg-[#B88A44] text-white flex items-center justify-center shrink-0 shadow-sm shadow-[#B88A44]/20 ring-4 ring-[#B88A44]/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[8px] font-extrabold text-[#B88A44] uppercase tracking-wider">Step 02</span>
                        <span class="block text-[11px] font-extrabold text-gray-900 tracking-wide uppercase">Delivery Info</span>
                    </div>
                </div>

                <!-- Step 3: Payment Gateway (Active/Pending) -->
                <div class="flex items-center gap-3 pl-2 sm:pl-6 py-1.5 sm:py-0">
                    <div class="w-8 h-8 rounded-xl bg-white border border-[#B88A44]/30 text-[#B88A44] flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[8px] font-extrabold text-[#B88A44] uppercase tracking-wider">Step 03</span>
                        <span class="block text-[11px] font-extrabold text-gray-900 tracking-wide uppercase">Payment Terminal</span>
                    </div>
                </div>

                <!-- Step 4: Final Review (Pending) -->
                <div class="flex items-center gap-3 pl-2 sm:pl-6 py-1.5 sm:py-0">
                    <div class="w-8 h-8 rounded-xl bg-gray-55 border border-gray-150 text-gray-400 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[8px] font-extrabold text-gray-400 uppercase tracking-wider">Step 04</span>
                        <span class="block text-[11px] font-bold text-gray-455 tracking-wide uppercase">Order Complete</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Collapsible Summary Header --}}
        <div 
            @click="summaryOpen = !summaryOpen"
            class="lg:hidden bg-white border border-gray-150 rounded-[18px] p-4 flex items-center justify-between cursor-pointer mb-6 select-none shadow-sm hover:shadow-md transition active:scale-99"
        >
            <div class="flex items-center gap-2 text-xs font-bold text-gray-900 uppercase tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#B88A44]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                <span x-text="summaryOpen ? 'Hide Order Summary' : 'Show Order Summary'"></span>
                <svg xmlns="http://www.w3.org/2000/svg" :class="summaryOpen ? 'rotate-180' : ''" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </div>
            <span class="text-sm font-black text-[#B88A44]">₹{{ number_format($totals['total'], 2) }}</span>
        </div>

        {{-- Mobile Collapsible Summary Content --}}
        <div 
            x-show="summaryOpen" 
            x-transition:enter="transition ease-out duration-300 transform origin-top"
            x-transition:enter-start="scale-y-95 opacity-0"
            x-transition:enter-end="scale-y-100 opacity-100"
            x-transition:leave="transition ease-in duration-205 transform origin-top"
            x-transition:leave-start="scale-y-100 opacity-100"
            x-transition:leave-end="scale-y-95 opacity-0"
            class="lg:hidden bg-white border border-gray-150 rounded-[18px] p-5 mb-6 space-y-4 shadow-sm"
            x-cloak
        >
            {{-- Items list --}}
            <div class="divide-y divide-gray-100 pr-1 max-h-60 overflow-y-auto no-scrollbar font-sans">
                @foreach($cart as $item)
                    <div class="flex items-center gap-3.5 py-3 text-xs">
                        {{-- Small product image preview --}}
                        <div class="w-11 h-11 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                            <img src="{{ getCheckoutItemImage($item['image']) }}" class="max-h-full max-w-full object-contain">
                        </div>
                        <div class="flex-grow min-w-0">
                            <span class="text-gray-900 font-extrabold block truncate text-xs">{{ $item['name'] }}</span>
                            <span class="text-gray-450 font-bold text-[10px]">Quantity: {{ $item['quantity'] }}</span>
                        </div>
                        <span class="font-extrabold text-gray-900 shrink-0">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Totals Breakdown --}}
            <div class="border-t border-gray-100 pt-3.5 space-y-2 text-xs font-semibold text-gray-500 font-sans">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span class="text-gray-900 font-bold">₹{{ number_format($totals['subtotal'], 2) }}</span>
                </div>
                @if(isset($totals['discount']) && $totals['discount'] > 0)
                    <div class="flex justify-between text-emerald-600">
                        <span>Promo Discount</span>
                        <span class="font-bold">-₹{{ number_format($totals['discount'], 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>Shipping Handling</span>
                    <span class="text-[#16A34A] font-extrabold">FREE</span>
                </div>
                <div class="flex justify-between border-t border-gray-100 pt-3 text-sm font-black text-gray-900">
                    <span class="font-serif">Grand Total</span>
                    <span class="text-[#B88A44]">₹{{ number_format($totals['total'], 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Checkout Form --}}
        <form id="checkout-form" action="{{ route('store.checkout.place') }}" method="POST">
            @csrf

            {{-- Dynamic binding targets for payment methods --}}
            <input type="hidden" name="payment_method" :value="form.payment_method">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- Left column form inputs (65%) --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- Customer Information --}}
                    <div class="bg-white border border-gray-150 rounded-[18px] p-6 sm:p-8 shadow-sm space-y-6">
                        <div class="border-b border-gray-100 pb-4 flex items-center justify-between">
                            <h3 class="text-base font-serif font-black text-gray-900">Personal Information</h3>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-[#B88A44]">Step 1 of 3</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- First name --}}
                            <div class="space-y-1.5 font-sans">
                                <label for="first_name" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                    First Name <span class="text-red-500 font-normal">*</span>
                                </label>
                                <div class="relative group/field transition-all duration-200">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="first_name" 
                                        name="first_name" 
                                        x-model="form.first_name" 
                                        class="peer block w-full h-14 pl-12 pr-4 text-xs font-bold text-gray-900 bg-[#FCFCFC] border border-[#E5E7EB] rounded-[18px] focus:border-[#B88A44] focus:bg-white focus:ring-0 focus:outline-none transition group-hover/field:border-gray-300 @error('first_name') border-red-500 @enderror"
                                    >
                                </div>
                                @error('first_name') <p class="text-[10px] text-red-500 font-semibold mt-1 px-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Last name --}}
                            <div class="space-y-1.5 font-sans">
                                <label for="last_name" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                    Last Name <span class="text-red-500 font-normal">*</span>
                                </label>
                                <div class="relative group/field transition-all duration-200">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="last_name" 
                                        name="last_name" 
                                        x-model="form.last_name" 
                                        class="peer block w-full h-14 pl-12 pr-4 text-xs font-bold text-gray-900 bg-[#FCFCFC] border border-[#E5E7EB] rounded-[18px] focus:border-[#B88A44] focus:bg-white focus:ring-0 focus:outline-none transition group-hover/field:border-gray-300 @error('last_name') border-red-500 @enderror"
                                    >
                                </div>
                                @error('last_name') <p class="text-[10px] text-red-500 font-semibold mt-1 px-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Email --}}
                            <div class="space-y-1.5 font-sans">
                                <label for="email" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                    Email Address <span class="text-red-500 font-normal">*</span>
                                </label>
                                <div class="relative group/field transition-all duration-200">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        x-model="form.email" 
                                        class="peer block w-full h-14 pl-12 pr-4 text-xs font-bold text-gray-900 bg-[#FCFCFC] border border-[#E5E7EB] rounded-[18px] focus:border-[#B88A44] focus:bg-white focus:ring-0 focus:outline-none transition group-hover/field:border-gray-300 @error('email') border-red-500 @enderror"
                                    >
                                </div>
                                @error('email') <p class="text-[10px] text-red-500 font-semibold mt-1 px-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="space-y-1.5 font-sans">
                                <label for="phone" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                    Phone Number <span class="text-red-500 font-normal">*</span>
                                </label>
                                <div class="relative group/field transition-all duration-200">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="phone" 
                                        name="phone" 
                                        x-model="form.phone" 
                                        class="peer block w-full h-14 pl-12 pr-4 text-xs font-bold text-gray-900 bg-[#FCFCFC] border border-[#E5E7EB] rounded-[18px] focus:border-[#B88A44] focus:bg-white focus:ring-0 focus:outline-none transition group-hover/field:border-gray-300 @error('phone') border-red-500 @enderror"
                                    >
                                </div>
                                @error('phone') <p class="text-[10px] text-red-500 font-semibold mt-1 px-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Shipping Address --}}
                    <div class="bg-white border border-gray-150 rounded-[18px] p-6 sm:p-8 shadow-sm space-y-6">
                        <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <h3 class="text-base font-serif font-black text-gray-900">Shipping Address</h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Step 2 of 3</p>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[9px] font-bold uppercase tracking-widest text-[#B88A44] bg-brand-50 px-2.5 py-1 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-[#B88A44]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Saved Addresses
                            </span>
                        </div>

                        {{-- Saved Address Cards --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <template x-for="(addr, idx) in savedAddresses" :key="idx">
                                <div 
                                    @click="selectAddress(idx)"
                                    :class="selectedAddressIdx === idx ? 'border-[#B88A44] bg-brand-50/10 ring-1 ring-[#B88A44]' : 'border-gray-200 bg-white hover:border-gray-300'"
                                    class="border rounded-[18px] p-4 cursor-pointer transition-all duration-200 flex flex-col justify-between min-h-[130px] relative group hover:-translate-y-0.5"
                                >
                                    <div class="space-y-1.5 font-sans">
                                        <div class="flex items-center justify-between">
                                            <span class="inline-flex items-center gap-1 text-[9px] font-extrabold uppercase tracking-widest text-[#B88A44]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                <span x-text="addr.label"></span>
                                            </span>
                                            <div class="flex items-center gap-1.5">
                                                <span x-show="addr.is_default" class="text-[8px] font-bold uppercase tracking-wider text-green-700 bg-green-50 border border-green-150 px-1.5 py-0.5 rounded">Default</span>
                                                <span x-show="selectedAddressIdx === idx" class="w-2.5 h-2.5 rounded-full bg-[#B88A44] ring-4 ring-brand-500/10"></span>
                                            </div>
                                        </div>
                                        <h4 class="text-xs font-bold text-gray-900" x-text="addr.name"></h4>
                                        <p class="text-[11px] text-gray-555 leading-relaxed font-semibold" x-text="addr.street"></p>
                                    </div>
                                    <div class="text-[10px] text-gray-400 font-semibold mt-2" x-text="addr.city + ', ' + addr.state + ' - ' + addr.zip_code"></div>
                                </div>
                            </template>
                        </div>

                        {{-- Form Address Fields --}}
                        <div class="space-y-5 pt-2">
                            {{-- Street address --}}
                            <div class="space-y-1.5 font-sans">
                                <label for="address" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                    Street Address <span class="text-red-400 font-normal">*</span>
                                </label>
                                <div class="relative group/field transition-all duration-200">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="address" 
                                        name="address" 
                                        x-model="form.address" 
                                        class="peer block w-full h-14 pl-12 pr-4 text-xs font-bold text-gray-900 bg-[#FCFCFC] border border-[#E5E7EB] rounded-[18px] focus:border-[#B88A44] focus:bg-white focus:ring-0 focus:outline-none transition group-hover/field:border-gray-300 @error('address') border-red-500 @enderror"
                                    >
                                </div>
                                @error('address') <p class="text-[10px] text-red-500 font-semibold mt-1 px-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                {{-- City --}}
                                <div class="space-y-1.5 font-sans">
                                    <label for="city" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                        City <span class="text-red-400 font-normal">*</span>
                                    </label>
                                    <div class="relative group/field transition-all duration-200">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <input 
                                            type="text" 
                                            id="city" 
                                            name="city" 
                                            x-model="form.city" 
                                            class="peer block w-full h-14 pl-12 pr-4 text-xs font-bold text-gray-900 bg-[#FCFCFC] border border-[#E5E7EB] rounded-[18px] focus:border-[#B88A44] focus:bg-white focus:ring-0 focus:outline-none transition group-hover/field:border-gray-300 @error('city') border-red-500 @enderror"
                                        >
                                    </div>
                                    @error('city') <p class="text-[10px] text-red-500 font-semibold mt-1 px-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- State --}}
                                <div class="space-y-1.5 font-sans">
                                    <label for="state" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                        State <span class="text-red-400 font-normal">*</span>
                                    </label>
                                    <div class="relative group/field transition-all duration-200">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                        </div>
                                        <input 
                                            type="text" 
                                            id="state" 
                                            name="state" 
                                            x-model="form.state" 
                                            class="peer block w-full h-14 pl-12 pr-4 text-xs font-bold text-gray-900 bg-[#FCFCFC] border border-[#E5E7EB] rounded-[18px] focus:border-[#B88A44] focus:bg-white focus:ring-0 focus:outline-none transition group-hover/field:border-gray-300 @error('state') border-red-500 @enderror"
                                        >
                                    </div>
                                    @error('state') <p class="text-[10px] text-red-500 font-semibold mt-1 px-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Zip code --}}
                                <div class="space-y-1.5 font-sans">
                                    <label for="zip_code" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                        ZIP Code <span class="text-red-400 font-normal">*</span>
                                    </label>
                                    <div class="relative group/field transition-all duration-200">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v4m16 0H4"/></svg>
                                        </div>
                                        <input 
                                            type="text" 
                                            id="zip_code" 
                                            name="zip_code" 
                                            x-model="form.zip_code" 
                                            class="peer block w-full h-14 pl-12 pr-4 text-xs font-bold text-gray-900 bg-[#FCFCFC] border border-[#E5E7EB] rounded-[18px] focus:border-[#B88A44] focus:bg-white focus:ring-0 focus:outline-none transition group-hover/field:border-gray-300 @error('zip_code') border-red-500 @enderror"
                                        >
                                    </div>
                                    @error('zip_code') <p class="text-[10px] text-red-500 font-semibold mt-1 px-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Landmark / Delivery notes --}}
                            <div class="space-y-1.5 font-sans">
                                <label for="landmark" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                                    Landmark (Optional)
                                </label>
                                <div class="relative group/field transition-all duration-200">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="landmark" 
                                        name="landmark" 
                                        x-model="form.landmark" 
                                        class="peer block w-full h-14 pl-12 pr-4 text-xs font-bold text-gray-900 bg-[#FCFCFC] border border-[#E5E7EB] rounded-[18px] focus:border-[#B88A44] focus:bg-white focus:ring-0 focus:outline-none transition group-hover/field:border-gray-300"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Delivery Method Options --}}
                    <div class="bg-white border border-gray-150 rounded-[18px] p-6 sm:p-8 shadow-sm space-y-6">
                        <div class="border-b border-gray-100 pb-4">
                            <h3 class="text-base font-serif font-black text-gray-900">Delivery Methods</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Select a shipping tier that fits your speed</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <template x-for="option in deliveryOptions" :key="option.id">
                                <div 
                                    @click="form.delivery_method = option.id"
                                    :class="form.delivery_method === option.id ? 'border-[#B88A44] bg-brand-50/10 ring-1 ring-[#B88A44]' : 'border-gray-200 bg-white hover:border-gray-350'"
                                    class="border rounded-[18px] p-4 cursor-pointer transition-all duration-200 flex flex-col justify-between min-h-[120px] relative group hover:-translate-y-0.5"
                                >
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-gray-900" x-text="option.name"></span>
                                            <span x-show="form.delivery_method === option.id" class="w-2.5 h-2.5 rounded-full bg-[#B88A44]"></span>
                                        </div>
                                        <p class="text-[10px] font-bold text-[#B88A44] uppercase tracking-wider animate-pulse" x-text="option.time"></p>
                                        <p class="text-[11px] text-gray-550 font-semibold leading-snug" x-text="option.desc"></p>
                                    </div>
                                    <div class="text-xs font-black text-gray-900 mt-3">
                                        <span x-text="option.price === 0 ? 'FREE' : '₹' + option.price"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Refined Selectable Payment Cards --}}
                    <div class="bg-white border border-gray-150 rounded-[18px] p-6 sm:p-8 shadow-sm space-y-6">
                        <div class="border-b border-gray-100 pb-4">
                            <h3 class="text-base font-serif font-black text-gray-900">Payment Option</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Select a payment gateway terminal</p>
                                                </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <template x-for="opt in paymentOptions" :key="opt.id">
                                <div 
                                    @click="selectPayment(opt)"
                                    :class="selectedPaymentOption === opt.id ? 'border-[#B88A44] bg-brand-50/15 ring-1 ring-[#B88A44] shadow-md shadow-brand-500/5' : 'border-gray-200 bg-[#FCFCFC] hover:border-gray-350 hover:bg-white'"
                                    class="border rounded-[22px] p-5 cursor-pointer transition-all duration-300 flex flex-col justify-between min-h-[160px] relative group hover:-translate-y-1"
                                >
                                    <div class="flex items-start justify-between">
                                        <div :class="selectedPaymentOption === opt.id ? 'bg-white border-brand-200 text-brand-500' : 'bg-white border-gray-100 text-gray-500'" class="w-11 h-11 rounded-2xl flex items-center justify-center border shadow-sm transition-all duration-300">
                                            <!-- COD icon -->
                                            <svg x-show="opt.icon === 'cash'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            
                                            <!-- Card/Online icon -->
                                            <svg x-show="opt.icon === 'card'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <span :class="selectedPaymentOption === opt.id ? 'bg-[#B88A44]/10 text-[#B88A44]' : 'bg-gray-100 text-gray-455'" class="text-[8px] font-extrabold uppercase tracking-widest px-2 py-0.5 rounded-md transition-colors" x-text="opt.badge"></span>
                                            <span :class="selectedPaymentOption === opt.id ? 'border-[#B88A44] bg-[#B88A44] scale-100' : 'border-gray-300 bg-transparent scale-90'" class="w-4 h-4 rounded-full border flex items-center justify-center transition-all duration-300">
                                                <svg x-show="selectedPaymentOption === opt.id" xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="space-y-1 mt-4">
                                        <h4 class="text-xs font-extrabold text-gray-900 uppercase tracking-wider" x-text="opt.name"></h4>
                                        <p class="text-[10px] text-gray-450 font-semibold leading-relaxed" x-text="opt.desc"></p>
                                    </div>

                                    <!-- Elegant payment logos for Online Pay -->
                                    <div x-show="opt.icon === 'card'" class="flex items-center gap-1.5 pt-2 border-t border-gray-100/50 mt-2.5 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <span class="text-[8px] text-gray-400 font-bold uppercase tracking-wide mr-1">Accepting:</span>
                                        <span class="text-[9px] font-extrabold text-gray-700 bg-white border border-gray-150 px-1 rounded font-mono">VISA</span>
                                        <span class="text-[9px] font-extrabold text-gray-700 bg-white border border-gray-150 px-1 rounded font-mono">MC</span>
                                        <span class="text-[9px] font-extrabold text-gray-700 bg-white border border-gray-150 px-1 rounded font-mono">UPI</span>
                                        <span class="text-[9px] font-extrabold text-gray-700 bg-white border border-gray-150 px-1 rounded font-mono">NET</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Order Notes --}}
                    <div class="bg-white border border-gray-150 rounded-[18px] p-6 sm:p-8 shadow-sm space-y-6">
                        <div class="border-b border-gray-100 pb-4">
                            <h3 class="text-base font-serif font-black text-gray-900">Order Notes (Optional)</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">Add instructions for shipping couriers</p>
                        </div>
                        <div class="relative">
                            <textarea 
                                id="notes" 
                                name="notes" 
                                rows="3" 
                                placeholder="E.g., Please leave the package with the concierge or ring the bell twice." 
                                x-model="form.notes" 
                                class="w-full bg-[#FCFCFC] border border-[#E5E7EB] rounded-[18px] p-4 text-xs font-bold text-gray-955 resize-none focus:outline-none focus:bg-white focus:border-[#B88A44] transition-all"
                            ></textarea>
                        </div>
                    </div>
                </div>

                {{-- Right column sticky order summary (35%) --}}
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-36">
                    <div class="bg-white border border-gray-150 rounded-[18px] p-6 space-y-5 shadow-sm">
                        <h3 class="text-base font-serif font-black text-gray-900 border-b border-gray-100 pb-3">Order Summary</h3>
                        
                        {{-- Dispatch timeline indicators --}}
                        <div class="grid grid-cols-2 gap-3.5 bg-gray-50 p-3 rounded-2xl text-[10px] font-bold font-sans">
                            <div class="space-y-0.5">
                                <span class="text-gray-400 uppercase text-[8px] tracking-wider block">Expected Dispatch</span>
                                <span class="text-gray-900 uppercase">Today</span>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-gray-400 uppercase text-[8px] tracking-wider block">Estimated Delivery</span>
                                <span class="text-[#16A34A] uppercase">Tomorrow</span>
                            </div>
                        </div>

                        {{-- Items list --}}
                        <div class="max-h-48 overflow-y-auto divide-y divide-gray-100 pr-1 no-scrollbar font-sans">
                            @foreach($cart as $item)
                                <div class="flex items-center justify-between py-3 text-xs">
                                    <span class="text-gray-600 truncate max-w-[170px] font-bold">{{ $item['name'] }} <span class="text-gray-455 font-extrabold ml-1">× {{ $item['quantity'] }}</span></span>
                                    <span class="font-bold text-gray-900">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Coupon code section --}}
                        <div id="coupon-section" class="space-y-3 border-t border-gray-100 pt-4">
                            @if(session()->has('coupon'))
                                {{-- Applied state --}}
                                <div id="coupon-applied" class="flex items-center justify-between bg-green-50 border border-green-150 text-green-700 px-3.5 py-2.5 rounded-xl text-xs font-bold">
                                    <div class="flex items-center gap-1.5 font-sans">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span>Applied: <span id="applied-code" class="uppercase font-extrabold tracking-wider text-green-800">{{ session()->get('coupon.code') }}</span></span>
                                    </div>
                                    <button type="button" id="coupon-remove-btn" class="text-[10px] uppercase font-extrabold text-red-500 hover:underline focus:outline-none">
                                        Remove
                                    </button>
                                </div>
                                <div id="coupon-input-wrap" class="hidden space-y-1 font-sans">
                            @else
                                <div id="coupon-applied" class="hidden flex items-center justify-between bg-green-50 border border-green-150 text-green-700 px-3.5 py-2.5 rounded-xl text-xs font-bold">
                                    <div class="flex items-center gap-1.5 font-sans">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span>Applied: <span id="applied-code" class="uppercase font-extrabold tracking-wider text-green-800"></span></span>
                                    </div>
                                    <button type="button" id="coupon-remove-btn" class="text-[10px] uppercase font-extrabold text-red-500 hover:underline focus:outline-none">
                                        Remove
                                    </button>
                                </div>
                                <div id="coupon-input-wrap" class="space-y-1 font-sans">
                            @endif
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest">Apply Promo Code</label>
                                    <div class="flex gap-2">
                                        <input
                                            type="text"
                                            id="coupon-code-input"
                                            placeholder="ENTER CODE"
                                            class="flex-grow h-9 px-3.5 text-xs font-extrabold uppercase tracking-widest text-gray-900 bg-[#FCFCFC] border border-gray-205 rounded-xl focus:border-[#B88A44] focus:ring-0 focus:outline-none transition"
                                        >
                                        <button
                                            type="button"
                                            id="coupon-apply-btn"
                                            class="h-9 px-4 text-xs font-bold text-white bg-[#B88A44] hover:bg-[#a67c3b] rounded-xl transition shadow-sm active:scale-95 focus:outline-none"
                                        >
                                            Apply
                                        </button>
                                    </div>
                                    <p id="coupon-msg" class="text-[10px] font-semibold hidden"></p>
                                </div>
                        </div>

                        {{-- Total calculations --}}
                        <div class="border-t border-gray-100 pt-4 space-y-2.5 text-xs text-gray-500 font-semibold font-sans">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span id="summary-subtotal" class="text-gray-900 font-bold">₹{{ number_format($totals['subtotal'], 2) }}</span>
                            </div>
                            @if($totals['discount'] > 0)
                                <div id="summary-discount-row" class="flex justify-between">
                                    <span>Promo Discount</span>
                                    <span id="summary-discount" class="text-red-500 font-bold">-₹{{ number_format($totals['discount'], 2) }}</span>
                                </div>
                            @else
                                <div id="summary-discount-row" class="hidden flex justify-between">
                                    <span>Promo Discount</span>
                                    <span id="summary-discount" class="text-red-500 font-bold"></span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span>Shipping Handling</span>
                                <span id="summary-shipping" class="text-gray-900 font-bold">₹{{ number_format($totals['shipping'], 2) }}</span>
                            </div>
                            <div class="border-t border-gray-100 pt-3 flex justify-between text-sm font-black text-gray-900">
                                <span>Grand Total</span>
                                <span class="summary-total text-[#B88A44]">₹{{ number_format($totals['total'], 2) }}</span>
                            </div>
                        </div>

                        {{-- Secure submit button --}}
                        <div class="pt-2 font-sans">
                            <button 
                                type="submit" 
                                class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#B88A44] hover:bg-[#a67c3b] h-10 text-[10px] font-bold uppercase tracking-[0.12em] text-white shadow-sm hover:shadow-[#B88A44]/20 transition focus:outline-none active:scale-[0.99]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                <span>Place Order Securely</span>
                            </button>
                        </div>

                        {{-- Trust Badges --}}
                        <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-5 font-sans">
                            <div class="flex items-center gap-1.5 text-[9px] font-extrabold uppercase tracking-wider text-gray-400 leading-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#B88A44] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                <span>SSL Protected</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[9px] font-extrabold uppercase tracking-wider text-gray-400 leading-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#B88A44] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" /></svg>
                                <span>Easy Returns</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[9px] font-extrabold uppercase tracking-wider text-gray-400 leading-none col-span-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#B88A44] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Genuine Products & Secure Payments</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

        {{-- Coupon routes for JS --}}
        <span id="coupon-apply-url" data-url="{{ route('store.coupon.apply') }}" class="hidden"></span>
        <span id="coupon-remove-url" data-url="{{ route('store.coupon.remove') }}" class="hidden"></span>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]').value;
        if (paymentMethod === 'card') {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            
            // Disable button & show loader
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="inline-flex items-center gap-2 font-sans"><svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Initializing...</span>';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.payment_required) {
                    const options = {
                        "key": data.key,
                        "amount": data.amount,
                        "currency": "INR",
                        "name": "ShopMe Store",
                        "description": "Secure Order Payment",
                        "order_id": data.razorpay_order_id,
                        "handler": function (response) {
                            submitBtn.innerHTML = '<span class="inline-flex items-center gap-2 font-sans"><svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Verifying...</span>';
                            
                            fetch("{{ route('store.checkout.verify') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                                },
                                body: JSON.stringify({
                                    laravel_order_id: data.order_id,
                                    razorpay_payment_id: response.razorpay_payment_id || 'pay_mock_' + Math.random().toString(36).substr(2, 9),
                                    razorpay_order_id: response.razorpay_order_id || data.razorpay_order_id,
                                    razorpay_signature: response.razorpay_signature || 'sig_mock_signature'
                                })
                            })
                            .then(res => res.json())
                            .then(verifyData => {
                                if (verifyData.success) {
                                    window.location.href = verifyData.redirect_url;
                                } else {
                                    alert(verifyData.message || 'Payment verification failed.');
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalBtnText;
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert('Error verifying payment.');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;
                            });
                        },
                        "prefill": {
                            "name": data.user.name,
                            "email": data.user.email,
                            "contact": data.user.phone
                        },
                        "theme": {
                            "color": "#B88A44"
                        },
                        "modal": {
                            "ondismiss": function() {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnText;
                                alert('Payment checkout closed. You can retry paying.');
                            }
                        }
                    };

                    if (data.razorpay_order_id.startsWith('order_mock_')) {
                        const confirmSimulated = confirm("Local Sandboxed Payment Simulation:\n\nClick OK to simulate a SUCCESSFUL Razorpay payment.\nClick Cancel to simulate a FAILED/CLOSED checkout.");
                        if (confirmSimulated) {
                            setTimeout(() => {
                                options.handler({
                                    razorpay_payment_id: 'pay_mock_' + Math.random().toString(36).substr(2, 9),
                                    razorpay_order_id: data.razorpay_order_id,
                                    razorpay_signature: 'sig_mock_signature'
                                });
                            }, 800);
                        } else {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        }
                    } else {
                        const rzp1 = new Razorpay(options);
                        rzp1.open();
                    }
                } else if (data.success && !data.payment_required) {
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message || 'Failed to place order.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error processing order submission.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        }
    });

    // ── AJAX Coupon Apply / Remove ─────────────────────────────────────────
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
        || document.querySelector('input[name="_token"]')?.value || '';

    function updateTotalsUI(totals) {
        // Subtotal
        const subEl = document.getElementById('summary-subtotal');
        if (subEl) subEl.textContent = '₹' + Number(totals.subtotal).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
        // Discount row
        const discountRow = document.getElementById('summary-discount-row');
        const discountEl  = document.getElementById('summary-discount');
        if (discountRow && discountEl) {
            if (totals.discount > 0) {
                discountEl.textContent = '-₹' + Number(totals.discount).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
                discountRow.classList.remove('hidden');
            } else {
                discountRow.classList.add('hidden');
            }
        }
        // Shipping
        const shipEl = document.getElementById('summary-shipping');
        if (shipEl) shipEl.textContent = totals.shipping === 0 ? '₹0.00' : '₹' + Number(totals.shipping).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
        // Grand Total (all instances)
        document.querySelectorAll('.summary-total').forEach(el => {
            el.textContent = '₹' + Number(totals.total).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
        });
    }

    function showCouponApplied(code) {
        document.getElementById('applied-code').textContent = code;
        document.getElementById('coupon-applied').classList.remove('hidden');
        document.getElementById('coupon-input-wrap').classList.add('hidden');
    }

    function showCouponInput() {
        document.getElementById('coupon-applied').classList.add('hidden');
        document.getElementById('coupon-input-wrap').classList.remove('hidden');
        const inp = document.getElementById('coupon-code-input');
        if (inp) inp.value = '';
    }

    function setCouponMsg(msg, isError) {
        const el = document.getElementById('coupon-msg');
        if (!el) return;
        el.textContent = msg;
        el.className = 'text-[10px] font-semibold mt-0.5 ' + (isError ? 'text-red-500' : 'text-green-600');
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 4000);
    }

    // Apply coupon
    const applyBtn = document.getElementById('coupon-apply-btn');
    if (applyBtn) {
        applyBtn.addEventListener('click', function () {
            const code = document.getElementById('coupon-code-input').value.trim();
            if (!code) { setCouponMsg('Please enter a coupon code.', true); return; }

            applyBtn.disabled = true;
            applyBtn.textContent = '...';

            fetch(document.getElementById('coupon-apply-url').dataset.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ code })
            })
            .then(r => r.json())
            .then(data => {
                applyBtn.disabled = false;
                applyBtn.textContent = 'Apply';
                if (data.success) {
                    showCouponApplied(data.coupon.code);
                    updateTotalsUI(data.totals);
                } else {
                    setCouponMsg(data.message || 'Invalid coupon.', true);
                }
            })
            .catch(() => {
                applyBtn.disabled = false;
                applyBtn.textContent = 'Apply';
                setCouponMsg('Something went wrong. Try again.', true);
            });
        });

        // Allow Enter key
        document.getElementById('coupon-code-input')?.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); applyBtn.click(); }
        });
    }

    // Remove coupon
    const removeBtn = document.getElementById('coupon-remove-btn');
    if (removeBtn) {
        removeBtn.addEventListener('click', function () {
            fetch(document.getElementById('coupon-remove-url').dataset.url, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showCouponInput();
                    updateTotalsUI(data.totals);
                }
            })
            .catch(() => {});
        });
    }
</script>
@endsection
