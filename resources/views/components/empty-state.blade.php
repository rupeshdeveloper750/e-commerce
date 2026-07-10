@props([
    'title' => 'No items found',
    'message' => 'Check back later or browse other sections.',
    'actionText' => 'Continue Shopping',
    'actionRoute' => null,
    'actionClick' => null,
    'type' => 'general'
])

@php
    $actionUrl = $actionRoute ? route($actionRoute) : null;
    $shopUrl = route('store.shop');
@endphp

<div class="bg-white rounded-[24px] border border-gray-150 p-8 md:p-12 text-center flex flex-col items-center justify-center space-y-6 max-w-xl mx-auto shadow-sm hover:shadow-md transition-shadow duration-300">
    <!-- Minimal Premium Illustration based on Type -->
    <div class="w-20 h-20 rounded-full bg-brand-50 flex items-center justify-center text-brand-500 border border-brand-100/50 relative">
        <div class="absolute inset-0 rounded-full bg-brand-200/10 animate-ping opacity-75" style="animation-duration: 3s;"></div>
        
        @if($type === 'orders')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        @elseif($type === 'wishlist')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        @elseif($type === 'coupons')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        @elseif($type === 'notifications')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        @elseif($type === 'rewards')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
        @endif
    </div>

    <!-- Message info -->
    <div class="space-y-2">
        <h3 class="font-serif font-bold text-xl text-gray-900 leading-tight">{{ $title }}</h3>
        <p class="text-xs text-gray-450 max-w-sm mx-auto leading-relaxed font-semibold">{{ $message }}</p>
    </div>

    <!-- Dual Actions -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 w-full sm:w-auto pt-2">
        @if($actionClick)
            <button @click="{{ $actionClick }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-6 py-3 text-xs font-bold text-white transition shadow-lg shadow-brand-500/10 focus:outline-none">
                <span>{{ $actionText }}</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </button>
        @elseif($actionUrl)
            <a href="{{ $actionUrl }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-6 py-3 text-xs font-bold text-white transition shadow-lg shadow-brand-500/10">
                <span>{{ $actionText }}</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </a>
        @endif

        <!-- Standard Continue Shopping secondary button -->
        <a href="{{ $shopUrl }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-150 px-6 py-3 text-xs font-bold text-gray-700 transition">
            <span>Continue Shopping</span>
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</div>
