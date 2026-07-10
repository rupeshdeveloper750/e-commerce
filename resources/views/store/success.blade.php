@extends('layouts.store')

@section('title', 'Order Placed successfully')

@section('content')
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

<div class="min-h-[64vh] flex items-center justify-center py-6 sm:py-10">
    <div class="max-w-md w-full mx-auto rounded-[28px] border border-gray-150 bg-white p-6 text-center space-y-5.5 shadow-[0_20px_50px_rgba(0,0,0,0.04)]">

        {{-- Success Checkmark with subtle pulse --}}
        <div class="relative flex items-center justify-center h-14 w-14 mx-auto">
            <span class="absolute animate-ping inline-flex h-10 w-10 rounded-full bg-[#B88A44]/15 opacity-75"></span>
            <div class="relative h-11 w-11 bg-[#B88A44]/10 text-[#B88A44] rounded-full flex items-center justify-center border border-[#B88A44]/20 shadow-sm">
                <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <div class="space-y-1.5">
            <h1 class="text-xl font-serif font-black text-gray-900 leading-tight">Order Confirmed!</h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Thank you for your purchase</p>
            <div class="inline-flex items-center gap-1.5 bg-[#B88A44]/10 border border-[#B88A44]/20 px-3.5 py-2 rounded-xl text-xs font-bold text-[#B88A44] font-mono mt-1 shadow-sm shadow-[#B88A44]/5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-[#B88A44]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>ORDER ID: #{{ $order->order_number }}</span>
            </div>
        </div>

        {{-- Clean Compact Status Banner --}}
        <div class="bg-emerald-500/[0.03] border border-emerald-500/10 rounded-2xl p-3.5 flex items-center justify-between text-xs font-sans">
            <span class="text-emerald-800/80 font-extrabold uppercase tracking-widest text-[9px]">Timeline Status</span>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-emerald-800 font-extrabold uppercase tracking-wider text-[10px]">Placed & Confirmed</span>
            </div>
        </div>

        {{-- Details summary --}}
        <div class="border-t border-b border-gray-100 py-5 text-left text-xs font-sans space-y-3.5">
            <div class="flex justify-between items-center">
                <span class="text-gray-455 font-bold uppercase text-[9px] tracking-widest">Customer Name</span>
                <span class="text-gray-900 font-extrabold">{{ $order->first_name }} {{ $order->last_name }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-455 font-bold uppercase text-[9px] tracking-widest">Payment Method</span>
                <span class="text-gray-900 font-extrabold uppercase tracking-wider">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-455 font-bold uppercase text-[9px] tracking-widest">Total Amount Paid</span>
                <span class="text-[#B88A44] font-black text-sm">₹{{ number_format($order->total, 2) }}</span>
            </div>
            <div class="flex justify-between items-start">
                <span class="text-gray-455 font-bold uppercase text-[9px] tracking-widest mt-0.5">Delivery Address</span>
                <span class="text-gray-900 font-bold text-right truncate max-w-[220px]">{{ $order->address }}</span>
            </div>
        </div>

        {{-- Sleek Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3.5 pt-1.5">
            <a href="{{ route('store.shop') }}" class="flex-1 inline-flex items-center justify-center rounded-xl border border-gray-200 text-xs font-extrabold text-gray-700 h-11 hover:bg-gray-55 hover:border-gray-350 transition active:scale-97">
                Continue Shopping
            </a>
            <a href="{{ route('user.dashboard') }}" class="flex-1 inline-flex items-center justify-center rounded-xl bg-[#B88A44] hover:bg-[#a67c3b] text-white text-xs font-extrabold h-11 shadow-md shadow-[#B88A44]/10 transition active:scale-97 hover-gold-glow">
                Track Order
            </a>
        </div>

    </div>
</div>
@endsection