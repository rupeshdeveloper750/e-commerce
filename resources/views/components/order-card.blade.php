@props([
    'order'
])

@php
    $deliveryStatus = $order->status ?? 'pending';
    $paymentStatus = $order->payment_status ?? 'pending';
    
    // Status color mapping - colorful but elegant pastels
    $statusColors = [
        'pending' => 'bg-amber-50 text-amber-700 border border-amber-200/50',
        'processing' => 'bg-blue-50 text-blue-700 border border-blue-200/50',
        'shipped' => 'bg-indigo-50 text-indigo-700 border border-indigo-200/50',
        'delivered' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/50',
        'cancelled' => 'bg-rose-50 text-rose-700 border border-rose-200/50',
    ];
    
    $paymentColors = [
        'paid' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/50',
        'pending' => 'bg-amber-50 text-amber-700 border border-amber-200/50',
        'failed' => 'bg-rose-50 text-rose-700 border border-rose-200/50',
    ];

    $firstItem = $order->items->first();
    $itemCount = $order->items->count();
@endphp

<div 
    x-data="{ expanded: false }"
    class="bg-white rounded-[24px] border border-gray-150 p-5 md:p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 space-y-5 group/card relative overflow-hidden"
>
    <!-- Card Header / Order Meta -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
        <div class="space-y-1">
            <div class="flex items-center gap-3">
                <span class="font-serif font-black text-lg text-gray-900 tracking-tight">Order #{{ $order->order_number }}</span>
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider leading-none {{ $statusColors[$deliveryStatus] ?? $statusColors['pending'] }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-1 animate-pulse"></span>
                    {{ $deliveryStatus }}
                </span>
            </div>
            <p class="text-[11px] text-gray-400 font-semibold tracking-wide">Placed on {{ $order->created_at->format('M d, Y') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $paymentColors[$paymentStatus] ?? $paymentColors['pending'] }}">
                Payment: {{ $paymentStatus }}
            </span>
            <span class="font-serif font-black text-xl text-gray-900">₹{{ number_format($order->total, 2) }}</span>
        </div>
    </div>

    <!-- Main Card Body (Product Centric Layout) -->
    <div class="flex flex-col md:flex-row gap-5 items-start">
        <!-- Large Main Image Preview -->
        <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden shrink-0 relative shadow-sm group-hover/card:scale-[1.02] transition duration-500">
            @if($firstItem && $firstItem->product && $firstItem->product->featuredImage)
                <img src="{{ asset('storage/' . $firstItem->product->featuredImage->image_path) }}" class="w-full h-full object-cover group-hover/card:scale-105 transition duration-700" alt="{{ $firstItem->product_name }}">
            @else
                <div class="w-full h-full flex items-center justify-center text-[10px] font-bold text-gray-400 bg-gray-50 uppercase tracking-widest">No Image</div>
            @endif
            @if($itemCount > 1)
                <div class="absolute bottom-2 right-2 bg-gray-900/90 text-white font-mono text-[10px] font-bold px-2 py-0.5 rounded-lg border border-white/10 backdrop-blur-sm">
                    +{{ $itemCount - 1 }}
                </div>
            @endif
        </div>

        <!-- Product details summary -->
        <div class="flex-grow min-w-0 space-y-2">
            <div class="space-y-1">
                <span class="text-[9px] font-bold text-brand-500 uppercase tracking-widest leading-none">
                    {{ $firstItem && $firstItem->product && $firstItem->product->brand ? $firstItem->product->brand->name : 'ShopMe Exclusive' }}
                </span>
                <h3 class="font-serif font-bold text-base md:text-lg text-gray-900 leading-snug truncate">
                    @if($firstItem && $firstItem->product)
                        <a href="{{ route('store.product.show', $firstItem->product->slug) }}" class="hover:text-brand-500 transition-colors duration-200">
                            {{ $firstItem->product_name }}
                        </a>
                    @else
                        {{ $firstItem->product_name ?? 'Premium Item' }}
                    @endif
                </h3>
                @if($itemCount > 1)
                    <p class="text-[11px] text-gray-400 font-semibold italic">And {{ $itemCount - 1 }} other luxury product{{ $itemCount > 2 ? 's' : '' }} in this consignment</p>
                @endif
            </div>

            <!-- Delivery schedule -->
            <div class="flex items-center gap-1.5 text-xs text-gray-450 font-semibold pt-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>
                    @if($deliveryStatus === 'delivered')
                        Delivered on {{ $order->updated_at->format('M d, Y') }}
                    @elseif($deliveryStatus === 'cancelled')
                        Consignment Cancelled
                    @else
                        Expected Delivery: {{ $order->created_at->addDays(4)->format('M d, Y') }}
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Actions & Timeline trigger -->
    <div class="pt-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
        <!-- Collapsible Details Toggle -->
        <button 
            @click="expanded = !expanded" 
            class="inline-flex items-center gap-1 text-xs font-bold text-gray-500 hover:text-gray-900 transition-colors focus:outline-none"
        >
            <span x-text="expanded ? 'Hide Details' : 'View Details'"></span>
            <svg 
                class="w-3.5 h-3.5 transform transition-transform duration-300"
                :class="expanded ? 'rotate-180 text-brand-500' : ''"
                fill="none" 
                stroke="currentColor" 
                stroke-width="2.5" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- CTAs -->
        <div class="flex items-center gap-3">
            @if($deliveryStatus !== 'cancelled')
                <button 
                    @click.prevent="openTrackModal('{{ $order->order_number }}', '{{ $deliveryStatus }}')" 
                    class="px-4 py-2 rounded-xl bg-gray-50 hover:bg-brand-50 border border-gray-150 hover:border-brand-200 text-[11px] font-bold text-gray-700 hover:text-brand-500 transition-all shadow-sm"
                >
                    Track Order
                </button>
            @endif

            <button 
                @click.prevent="downloadInvoice('{{ $order->order_number }}')" 
                class="p-2 rounded-xl bg-gray-50 hover:bg-brand-50 border border-gray-150 hover:border-brand-200 text-gray-550 hover:text-brand-500 transition-all shadow-sm" 
                title="Download Invoice"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </button>

            @if($firstItem && $firstItem->product)
                <form action="{{ route('store.cart.add', $firstItem->product_id) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-500 hover:bg-brand-600 active:scale-95 text-[11px] font-bold text-white transition-all shadow-md shadow-brand-500/10 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Buy Again
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Collapsible Tray (Alpine.js) -->
    <div 
        x-show="expanded" 
        x-collapse 
        class="pt-5 border-t border-gray-100 space-y-5 text-xs font-semibold text-gray-500"
        x-cloak
    >
        <h4 class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Order Consignment Items</h4>
        <!-- Consignment list -->
        <div class="space-y-4">
            @foreach($order->items as $item)
                <div class="flex items-center justify-between gap-4 p-3 bg-gray-50 rounded-xl border border-gray-100 hover:border-gray-200 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-white border border-gray-150 overflow-hidden shrink-0">
                            @if($item->product && $item->product->featuredImage)
                                <img src="{{ asset('storage/' . $item->product->featuredImage->image_path) }}" class="w-full h-full object-cover" alt="">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[8px] font-bold text-gray-400">No Image</div>
                            @endif
                        </div>
                        <div class="space-y-0.5">
                            <h5 class="font-serif font-bold text-gray-900 hover:text-brand-500 truncate max-w-[200px] sm:max-w-xs md:max-w-md">
                                @if($item->product)
                                    <a href="{{ route('store.product.show', $item->product->slug) }}">{{ $item->product_name }}</a>
                                @else
                                    {{ $item->product_name }}
                                @endif
                            </h5>
                            <p class="text-[10px] text-gray-400 font-semibold">Qty: {{ $item->quantity }} • Price: ₹{{ number_format($item->price, 2) }}</p>
                        </div>
                    </div>
                    <span class="font-serif font-black text-gray-900">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                </div>
            @endforeach
        </div>

        <!-- Breakdown & Shipping coordinates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4">
            <!-- Shipping details -->
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-2">
                <span class="text-[9px] font-bold uppercase tracking-widest text-gray-450 block">Shipping Coordinates</span>
                <div class="space-y-1 text-gray-650 font-medium">
                    <p class="font-bold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] leading-relaxed text-gray-500">
                        {{ $order->shipping_address ?? '124, Luxury Boulevard, Bandra West, Mumbai, Maharashtra - 400050' }}
                    </p>
                </div>
            </div>

            <!-- Financial breakdown -->
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-150 space-y-2.5">
                <span class="text-[9px] font-bold uppercase tracking-widest text-gray-450 block">Consignment Cost Summary</span>
                <div class="space-y-1.5 font-semibold text-gray-650">
                    <div class="flex justify-between items-center text-[11px]">
                        <span>Subtotal</span>
                        <span class="font-mono text-gray-900">₹{{ number_format($order->subtotal ?? ($order->total - 100), 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between items-center text-[11px] text-brand-600">
                            <span>Promo Discount</span>
                            <span class="font-mono">-₹{{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center text-[11px]">
                        <span>Standard Delivery</span>
                        <span class="text-green-600 font-bold uppercase tracking-wide">FREE</span>
                    </div>
                    <div class="h-px bg-gray-200 my-1.5"></div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-gray-900">Final Total</span>
                        <span class="font-serif font-black text-gray-900 text-sm">₹{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
