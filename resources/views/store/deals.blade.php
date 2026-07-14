@extends('layouts.store')

@section('title', 'Exclusive Deals')

@section('content')
<div class="bg-white -mx-6 sm:-mx-8 lg:-mx-12 -mt-12 sm:-mt-20 -mb-16 py-8 md:py-12 min-h-screen text-stone-900">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 space-y-12">

        {{-- Breadcrumbs --}}
        <nav class="text-[9px] uppercase tracking-widest text-stone-400 font-bold flex items-center gap-1.5" aria-label="Breadcrumb">
            <a href="{{ route('store.home') }}" class="hover:text-[#B88A44] transition-colors">Home</a>
            <span>/</span>
            <span class="text-stone-800">Exclusive Deals</span>
        </nav>

        {{-- Hero Section --}}
        <div class="relative rounded-[32px] overflow-hidden bg-gradient-to-br from-[#FCFAF7] via-[#F4EFE6] to-[#EBE3D5] border border-[#B88A44]/15 text-[#111827] p-8 md:p-14 flex flex-col justify-center min-h-[300px] shadow-xl relative">
            {{-- Glowing Ambient Bubbles --}}
            <div class="absolute -top-16 -right-16 w-80 h-80 rounded-full bg-amber-400/20 blur-3xl pointer-events-none z-0 animate-[pulse_5s_infinite]"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full bg-[#B88A44]/15 blur-3xl pointer-events-none z-0 animate-[pulse_7s_infinite]"></div>
            <div class="absolute top-1/4 left-1/3 w-64 h-64 rounded-full bg-rose-300/20 blur-2xl pointer-events-none z-0 animate-[pulse_6s_infinite]"></div>
            <div class="absolute bottom-1/4 right-1/4 w-48 h-48 rounded-full bg-amber-200/30 blur-2xl pointer-events-none z-0 animate-[pulse_8s_infinite]"></div>

            <div class="relative z-10 max-w-xl space-y-4">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] bg-[#B88A44]/10 border border-[#B88A44]/25 px-3 py-1 rounded-full w-fit block font-semibold">Limited Time Offers</span>
                <h1 class="font-serif text-3xl md:text-5xl font-bold tracking-tight leading-tight text-gray-900">
                    Season End Luxury Deals
                </h1>
                <p class="text-xs md:text-sm text-gray-500 leading-relaxed font-medium">
                    Handcrafted premium bags, canvas backpacks, and leather sleeves. Save up to 40% on timeless essentials. No coupon required.
                </p>

                {{-- Live Countdown Timer --}}
                <div class="pt-2 flex items-center gap-2 sm:gap-4" x-data="{
                    days: 0, hours: 0, minutes: 0, seconds: 0,
                    init() {
                        const target = new Date();
                        target.setDate(target.getDate() + 3); // 3 days from now
                        setInterval(() => {
                            const diff = target.getTime() - new Date().getTime();
                            if (diff <= 0) return;
                            this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                            this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        }, 1000);
                    }
                }">
                    <div class="text-center bg-white/60 backdrop-blur-md border border-[#B88A44]/10 rounded-2xl p-3 min-w-[70px] shadow-sm">
                        <span class="block text-xl md:text-2xl font-mono font-bold text-gray-900 tracking-tight" x-text="String(days).padStart(2, '0')">00</span>
                        <span class="block text-[8px] font-bold uppercase tracking-widest text-[#B88A44] mt-1">Days</span>
                    </div>
                    <span class="text-lg text-gray-400 font-light">:</span>
                    <div class="text-center bg-white/60 backdrop-blur-md border border-[#B88A44]/10 rounded-2xl p-3 min-w-[70px] shadow-sm">
                        <span class="block text-xl md:text-2xl font-mono font-bold text-gray-900 tracking-tight" x-text="String(hours).padStart(2, '0')">00</span>
                        <span class="block text-[8px] font-bold uppercase tracking-widest text-[#B88A44] mt-1">Hours</span>
                    </div>
                    <span class="text-lg text-gray-400 font-light">:</span>
                    <div class="text-center bg-white/60 backdrop-blur-md border border-[#B88A44]/10 rounded-2xl p-3 min-w-[70px] shadow-sm">
                        <span class="block text-xl md:text-2xl font-mono font-bold text-gray-900 tracking-tight" x-text="String(minutes).padStart(2, '0')">00</span>
                        <span class="block text-[8px] font-bold uppercase tracking-widest text-[#B88A44] mt-1">Mins</span>
                    </div>
                    <span class="text-lg text-gray-400 font-light">:</span>
                    <div class="text-center bg-white/60 backdrop-blur-md border border-[#B88A44]/10 rounded-2xl p-3 min-w-[70px] shadow-sm">
                        <span class="block text-xl md:text-2xl font-mono font-bold text-[#B88A44] tracking-tight animate-pulse" x-text="String(seconds).padStart(2, '0')">00</span>
                        <span class="block text-[8px] font-bold uppercase tracking-widest text-gray-500 mt-1">Secs</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hot Deals / Flash Offer Cards --}}
        <div class="space-y-6">
            <div class="flex items-end justify-between border-b border-stone-100 pb-3">
                <div class="space-y-1">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-[#B88A44] block">Flash Savings</span>
                    <h2 class="font-serif text-xl md:text-2xl font-bold">Exclusive Premium Drops</h2>
                </div>
                <div class="flex items-center gap-1 text-[9px] font-bold text-emerald-600 uppercase tracking-widest bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>1.2k Shoppers Live</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Flash Deal Item 1 -->
                <div class="rounded-xl border border-stone-200/60 p-5 flex flex-col sm:flex-row gap-5 items-center hover:shadow-[0_15px_40px_rgba(0,0,0,0.03)] transition-all duration-300 bg-[#FAF9F6]/20">
                    <div class="w-32 h-36 bg-white border border-stone-200/50 rounded-lg overflow-hidden shrink-0 flex items-center justify-center p-2 relative">
                        <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=250" class="max-h-full max-w-full object-contain" alt="Leather Backpack">
                        <span class="absolute top-2 left-2 bg-[#B88A44] text-white text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 rounded shadow-sm">Save 35%</span>
                    </div>
                    <div class="flex-grow space-y-3.5 w-full">
                        <div class="space-y-1">
                            <span class="text-[8px] font-bold uppercase tracking-widest text-[#B88A44]">Signature Series</span>
                            <h3 class="font-serif text-lg font-bold text-stone-900 leading-tight">Vintage Waxed Canvas Backpack</h3>
                            <div class="flex items-center gap-2">
                                <span class="font-serif text-base font-bold text-[#B88A44]">₹4,499.00</span>
                                <span class="text-xs text-stone-400 line-through">₹6,999.00</span>
                            </div>
                        </div>
                        {{-- Claim progress bar --}}
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-[9px] font-bold text-stone-400 uppercase tracking-widest">
                                <span>82% Claimed</span>
                                <span class="text-amber-600">Only 8 left in stock</span>
                            </div>
                            <div class="w-full h-1.5 bg-stone-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-[#B88A44] to-amber-500 rounded-full" style="width: 82%"></div>
                            </div>
                        </div>
                        <a href="{{ route('store.shop') }}" class="w-full h-9 bg-stone-950 hover:bg-[#B88A44] text-white text-[9px] font-bold uppercase tracking-widest transition-colors duration-300 rounded-[4px] flex items-center justify-center gap-1.5">
                            Claim Offer
                        </a>
                    </div>
                </div>

                <!-- Flash Deal Item 2 -->
                <div class="rounded-xl border border-stone-200/60 p-5 flex flex-col sm:flex-row gap-5 items-center hover:shadow-[0_15px_40px_rgba(0,0,0,0.03)] transition-all duration-300 bg-[#FAF9F6]/20">
                    <div class="w-32 h-36 bg-white border border-stone-200/50 rounded-lg overflow-hidden shrink-0 flex items-center justify-center p-2 relative">
                        <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=250" class="max-h-full max-w-full object-contain" alt="Leather Sleeve">
                        <span class="absolute top-2 left-2 bg-[#B88A44] text-white text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 rounded shadow-sm">Save 40%</span>
                    </div>
                    <div class="flex-grow space-y-3.5 w-full">
                        <div class="space-y-1">
                            <span class="text-[8px] font-bold uppercase tracking-widest text-[#B88A44]">Sleeve Series</span>
                            <h3 class="font-serif text-lg font-bold text-stone-900 leading-tight">Genuine Tan Leather Laptop Sleeve</h3>
                            <div class="flex items-center gap-2">
                                <span class="font-serif text-base font-bold text-[#B88A44]">₹2,399.00</span>
                                <span class="text-xs text-stone-400 line-through">₹3,999.00</span>
                            </div>
                        </div>
                        {{-- Claim progress bar --}}
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-[9px] font-bold text-stone-400 uppercase tracking-widest">
                                <span>65% Claimed</span>
                                <span class="text-amber-600">Only 14 left in stock</span>
                            </div>
                            <div class="w-full h-1.5 bg-stone-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-[#B88A44] to-amber-500 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                        <a href="{{ route('store.shop') }}" class="w-full h-9 bg-stone-950 hover:bg-[#B88A44] text-white text-[9px] font-bold uppercase tracking-widest transition-colors duration-300 rounded-[4px] flex items-center justify-center gap-1.5">
                            Claim Offer
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Shop On Sale Items Grid --}}
        <div class="space-y-8">
            <div class="text-center space-y-1">
                <span class="text-[9px] font-bold uppercase tracking-widest text-[#B88A44] block">Shop Catalog Deals</span>
                <h2 class="font-serif text-2xl md:text-3xl font-bold">Timeless Design, Limited Prices</h2>
            </div>

            @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $prod)
                <x-shop.product-card :product="$prod" />
                @endforeach
            </div>
            @else
            <div class="text-center py-16 border border-dashed border-stone-200 rounded-xl bg-[#FAF9F6]/30">
                <p class="text-xs text-stone-400 italic">No seasonal catalog items are currently on sale. Check back soon!</p>
            </div>
            @endif
        </div>

        {{-- Features & Benefits Strip --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-stone-100">
            <div class="rounded-xl border border-stone-100 p-5 bg-[#FAF9F6]/20 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-[#B88A44]/10 flex items-center justify-center text-[#B88A44] shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
                <div class="space-y-1">
                    <h4 class="font-serif text-sm font-bold">Handcrafted Quality</h4>
                    <p class="text-[11px] text-stone-500 leading-relaxed">Every bag is crafted with double-stiff stitch lines, full-grain leather edges, and heavy-duty steel zippers.</p>
                </div>
            </div>
            <div class="rounded-xl border border-stone-100 p-5 bg-[#FAF9F6]/20 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-[#B88A44]/10 flex items-center justify-center text-[#B88A44] shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/></svg>
                </div>
                <div class="space-y-1">
                    <h4 class="font-serif text-sm font-bold">Fast Insured Shipping</h4>
                    <p class="text-[11px] text-stone-500 leading-relaxed">Free standard delivery nationwide on all checkout totals above ₹999. Packed securely in plastic-free boxes.</p>
                </div>
            </div>
            <div class="rounded-xl border border-stone-100 p-5 bg-[#FAF9F6]/20 flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-[#B88A44]/10 flex items-center justify-center text-[#B88A44] shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="space-y-1">
                    <h4 class="font-serif text-sm font-bold">7-Day Return Guarantee</h4>
                    <p class="text-[11px] text-stone-500 leading-relaxed">Unsatisfied with your purchase? Send it back in original packaging within 7 days for a hassle-free exchange or full refund.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
