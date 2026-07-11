@extends('layouts.store')

@section('title', 'Order Placed Successfully')

@section('content')
@php
if (!function_exists('getCheckoutItemImage')) {
function getCheckoutItemImage($src) {
if (!$src) return 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=400';
if (str_starts_with($src, 'http')) return $src;
$lower = strtolower($src);
if (str_contains($lower, 'electronics') || str_contains($lower, 'phone') || str_contains($lower, 'tech'))
    return 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400';
if (str_contains($lower, 'watch'))
    return 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=400';
if (str_contains($lower, 'shoe') || str_contains($lower, 'footwear') || str_contains($lower, 'sneaker'))
    return 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400';
if (str_contains($lower, 'bag') || str_contains($lower, 'backpack') || str_contains($lower, 'satchel'))
    return 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=400';
if (str_contains($lower, 'iphone') || str_contains($lower, 'digital') || str_contains($lower, 'gold'))
    return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80';
return asset('storage/' . $src);
}
}
@endphp

<div class="flex items-center justify-center py-4 px-4" style="min-height: calc(100vh - 80px);">
    <div class="w-full max-w-md">

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_8px_40px_rgba(0,0,0,0.06)] overflow-hidden">

            {{-- Top accent bar --}}
            <div class="h-[3px] w-full bg-gradient-to-r from-[#B88A44] via-[#d4a855] to-[#B88A44]"></div>

            <div class="px-6 py-4 space-y-3.5">

                {{-- Success icon + heading --}}
                <div class="text-center space-y-2">
                    <div class="relative flex items-center justify-center h-12 w-12 mx-auto">
                        <span class="absolute animate-ping inline-flex h-9 w-9 rounded-full bg-[#B88A44]/12 opacity-60"></span>
                        <div class="relative h-11 w-11 bg-[#B88A44]/10 rounded-full flex items-center justify-center border border-[#B88A44]/25">
                            <svg class="w-5 h-5 text-[#B88A44]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-lg font-serif font-black text-gray-900 leading-tight">Order Confirmed!</h1>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.16em]">Thank you for your purchase</p>
                    </div>
                    {{-- Order ID badge --}}
                    <div class="inline-flex items-center gap-1 bg-[#B88A44]/8 border border-[#B88A44]/20 px-2.5 py-1 rounded-lg text-[10px] font-bold text-[#B88A44] font-mono">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>#{{ $order->order_number }}</span>
                    </div>
                </div>

                {{-- Status pill --}}
                <div class="flex items-center justify-between bg-emerald-50 border border-emerald-100 rounded-xl px-3.5 py-2">
                    <span class="text-[9px] font-extrabold text-emerald-700 uppercase tracking-[0.14em]">Order Status</span>
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[9px] font-extrabold text-emerald-700 uppercase tracking-wider">Placed & Confirmed</span>
                    </div>
                </div>

                {{-- Order details grid --}}
                <div class="divide-y divide-gray-75 border border-gray-100 rounded-xl overflow-hidden text-xs font-sans">
                    <div class="flex justify-between items-center px-4 py-2.5 bg-gray-50/50">
                        <span class="text-[8.5px] font-extrabold text-gray-400 uppercase tracking-widest">Customer</span>
                        <span class="text-gray-800 font-bold text-[11px]">{{ $order->first_name }} {{ $order->last_name }}</span>
                    </div>
                    <div class="flex justify-between items-center px-4 py-2.5">
                        <span class="text-[8.5px] font-extrabold text-gray-400 uppercase tracking-widest">Payment</span>
                        <span class="text-gray-800 font-bold text-[11px] uppercase tracking-wide">
                            {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center px-4 py-2.5 bg-gray-50/50">
                        <span class="text-[8.5px] font-extrabold text-gray-400 uppercase tracking-widest">Amount Paid</span>
                        <span class="text-[#B88A44] font-black text-sm">₹{{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-start px-4 py-2.5">
                        <span class="text-[8.5px] font-extrabold text-gray-400 uppercase tracking-widest mt-0.5 shrink-0">Delivery To</span>
                        <span class="text-gray-700 font-semibold text-right max-w-[230px] leading-snug text-[11px] ml-4">{{ $order->address }}, {{ $order->city }}</span>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="grid grid-cols-2 gap-2.5">
                    <a href="{{ route('store.shop') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-gray-200 text-[9.5px] font-bold uppercase tracking-[0.1em] text-gray-600 h-9 hover:bg-gray-50 hover:border-gray-300 transition-all duration-150 active:scale-[0.98]">
                        Continue Shopping
                    </a>
                    <a href="{{ route('user.dashboard') }}"
                       class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#B88A44] hover:bg-[#a67c3b] text-white text-[9.5px] font-bold uppercase tracking-[0.1em] h-9 shadow-sm shadow-[#B88A44]/20 transition-all duration-150 active:scale-[0.98]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                        </svg>
                        Track Order
                    </a>
                </div>

                {{-- Trust footer --}}
                <div class="flex items-center justify-center gap-4 border-t border-gray-100 pt-3">
                    <div class="flex items-center gap-1 text-[8.5px] font-bold text-gray-350 uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 text-[#B88A44]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        SSL Secured
                    </div>
                    <div class="w-px h-3 bg-gray-200"></div>
                    <div class="flex items-center gap-1 text-[8.5px] font-bold text-gray-350 uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 text-[#B88A44]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        Genuine Products
                    </div>
                    <div class="w-px h-3 bg-gray-200"></div>
                    <div class="flex items-center gap-1 text-[8.5px] font-bold text-gray-350 uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 text-[#B88A44]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                        </svg>
                        Easy Returns
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection