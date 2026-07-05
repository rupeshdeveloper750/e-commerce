@extends('layouts.store')

@section('title', 'Order Placed successfully')

@section('content')
<div class="max-w-xl mx-auto rounded-3xl border border-slate-800 bg-slate-900/60 p-8 text-center space-y-6 shadow-2xl">
    
    {{-- Success Checkmark --}}
    <div class="h-16 w-16 bg-emerald-500/10 text-emerald-400 rounded-full flex items-center justify-center mx-auto border border-emerald-500/20">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <div class="space-y-2">
        <h1 class="text-2xl font-black text-white">Order Confirmed!</h1>
        <p class="text-sm text-slate-400">Thank you for your purchase. Your order has been placed successfully.</p>
        <div class="inline-block bg-slate-950 border border-slate-850 px-4 py-2 rounded-xl text-sm font-bold text-amber-500">
            Order ID: #{{ $order->order_number }}
        </div>
    </div>

    {{-- Details summary --}}
    <div class="border-t border-b border-slate-800 py-4 text-left text-xs text-slate-400 space-y-2.5">
        <div class="flex justify-between">
            <span>Customer Name:</span>
            <span class="text-slate-200 font-semibold">{{ $order->first_name }} {{ $order->last_name }}</span>
        </div>
        <div class="flex justify-between">
            <span>Payment Method:</span>
            <span class="text-slate-200 uppercase">{{ $order->payment_method }}</span>
        </div>
        <div class="flex justify-between">
            <span>Total Amount Paid:</span>
            <span class="text-amber-500 font-bold">₹{{ number_format($order->total, 2) }}</span>
        </div>
        <div class="flex justify-between">
            <span>Delivery Address:</span>
            <span class="text-slate-200 text-right truncate max-w-[200px]">{{ $order->address }}</span>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('store.shop') }}" class="flex-1 inline-flex items-center justify-center rounded-xl border border-slate-800 text-xs text-slate-400 py-3 hover:bg-slate-800 transition">Continue Shopping</a>
        <a href="{{ route('user.dashboard') }}" class="flex-1 inline-flex items-center justify-center rounded-xl bg-amber-500 text-slate-950 text-xs font-semibold py-3 hover:bg-amber-400 transition">Track Order</a>
    </div>

</div>
@endsection
