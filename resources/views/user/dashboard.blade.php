@extends('layouts.store')

@section('title', 'My Dashboard')

@section('content')
<div class="space-y-8" x-data="{ tab: 'orders' }">
    
    {{-- Header --}}
    <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-lg shadow-black/20">
        <div class="flex items-center gap-4">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f59e0b&color=0f172a" class="w-14 h-14 rounded-2xl" alt="">
            <div>
                <h1 class="text-xl font-bold text-white">{{ auth()->user()->name }}</h1>
                <p class="text-xs text-slate-400">Customer account created {{ auth()->user()->created_at->format('M Y') }}</p>
            </div>
        </div>

        {{-- Nav Tabs --}}
        <div class="flex border border-slate-800 bg-slate-950 p-1 rounded-xl">
            <button @click="tab = 'orders'" :class="tab === 'orders' ? 'bg-slate-800 text-amber-500 font-semibold' : 'text-slate-400 hover:text-slate-200'" class="px-4 py-2 rounded-lg text-xs transition">My Orders</button>
            <button @click="tab = 'wishlist'" :class="tab === 'wishlist' ? 'bg-slate-800 text-amber-500 font-semibold' : 'text-slate-400 hover:text-slate-200'" class="px-4 py-2 rounded-lg text-xs transition">My Wishlist</button>
        </div>
    </div>

    {{-- Tabs Content --}}
    
    {{-- tab: Orders --}}
    <div x-show="tab === 'orders'" class="space-y-6">
        <h2 class="text-lg font-bold text-white">Order History</h2>
        
        <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-900/40">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-950 border-b border-slate-850">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Order Number</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Placed Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Payment Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Order Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wide">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850/50">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-900/60 transition">
                            <td class="px-6 py-4 font-bold text-white tracking-wider">#{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 capitalize">
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium 
                                    @if($order->payment_status == 'paid') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                    @elseif($order->payment_status == 'pending') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                    @else bg-rose-500/10 text-rose-400 border border-rose-500/20 @endif">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 capitalize">
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium 
                                    @if($order->status == 'delivered') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                    @elseif($order->status == 'pending') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                    @elseif($order->status == 'cancelled') bg-rose-500/10 text-rose-400 border border-rose-500/20
                                    @else bg-blue-500/10 text-blue-400 border border-blue-500/20 @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-white">₹{{ number_format($order->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">You haven't placed any orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- tab: Wishlist --}}
    <div x-show="tab === 'wishlist'" class="space-y-6" x-cloak>
        <h2 class="text-lg font-bold text-white">My Wishlist</h2>
        
        @if($wishlist->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($wishlist as $item)
                    @if($item->product)
                        <div onclick="if(!event.target.closest('.no-card-redirect')){ window.location='{{ route('store.product.show', $item->product->slug) }}' }" class="group relative cursor-pointer rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden hover:border-slate-700 transition duration-300 flex flex-col justify-between">
                            <div>
                                <div class="aspect-square bg-slate-950 border-b border-slate-850 overflow-hidden relative">
                                    @if($item->product->featuredImage)
                                        <img src="{{ asset('storage/' . $item->product->featuredImage->image_path) }}" class="w-full h-full object-cover" alt="">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-700 font-bold bg-slate-950">No Image</div>
                                    @endif
                                    
                                    {{-- Remove button --}}
                                    <form action="{{ route('user.wishlist.remove', $item->product_id) }}" method="POST" class="absolute top-3 right-3 no-card-redirect">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-slate-950/80 p-1.5 text-rose-500 hover:bg-rose-500 hover:text-white transition shadow" title="Remove">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="p-4 space-y-1">
                                    <h4 class="font-semibold text-white truncate hover:text-amber-400">
                                        <a href="{{ route('store.product.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                    </h4>
                                    <p class="text-sm font-semibold text-amber-500">₹{{ number_format($item->product->price, 2) }}</p>
                                </div>
                            </div>
                            <div class="p-4 pt-0 no-card-redirect">
                                <form action="{{ route('store.cart.add', $item->product_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 py-2 text-xs font-semibold text-slate-950 hover:bg-amber-400 transition">
                                        Move to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-12 text-center text-slate-500 italic">
                Your wishlist is empty. Explore catalog and add items you love!
            </div>
        @endif
    </div>

</div>
@endsection
