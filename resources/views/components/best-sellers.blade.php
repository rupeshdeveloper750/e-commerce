@props(['products'])

@if($products->isNotEmpty())
<section class="w-full pt-0 pb-12 bg-white" aria-labelledby="bestsellers-heading">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 space-y-10 md:space-y-12">
        
        {{-- Section Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-[#E5E7EB]/60 pb-6">
            <div class="space-y-2">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] block">Most Loved</span>
                <h2 id="bestsellers-heading" class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-[#111827] tracking-tight">Best Sellers</h2>
            </div>
            
            <a 
                href="{{ route('store.shop') }}" 
                class="inline-flex items-center justify-center h-11 px-6 rounded-full text-xs font-bold tracking-wider uppercase text-[#111827] border border-[#E5E7EB] bg-white hover:bg-[#FAF9F6] hover:border-[#B88A44]/30 hover:-translate-y-0.5 transition-all duration-200"
            >
                View All Collection
            </a>
        </div>

        {{-- Product Grid --}}
        <ul class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @foreach($products->take(4) as $product)
                @php
                    $imagePath = $product->featuredImage ? $product->featuredImage->image_path : '';
                    if (str_starts_with($imagePath, 'http')) {
                        $imgUrl = $imagePath;
                    } elseif ($imagePath) {
                        if (file_exists(public_path('storage/' . $imagePath))) {
                            $imgUrl = asset('storage/' . $imagePath);
                        } else {
                            $parentNameLower = strtolower($product->category->parent->name ?? $product->category->name);
                            if (str_contains($parentNameLower, 'electronics') || str_contains($parentNameLower, 'tech')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400';
                            } elseif (str_contains($parentNameLower, 'watch')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=400';
                            } elseif (str_contains($parentNameLower, 'footwear') || str_contains($parentNameLower, 'shoe')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400';
                            } elseif (str_contains($parentNameLower, 'bag')) {
                                $imgUrl = 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=400';
                            } else {
                                $imgUrl = 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=400';
                            }
                        }
                    } else {
                        $imgUrl = 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=400';
                    }
                @endphp
                <li class="group relative flex flex-col justify-between rounded-2xl border border-[#E5E7EB]/60 bg-white p-4 hover:shadow-xl hover:border-transparent transition-all duration-300">
                    
                    {{-- Image Container (CLS Safe) --}}
                    <div class="aspect-[3/4] w-full rounded-xl overflow-hidden relative bg-gray-50 border border-gray-150/40">
                        <img 
                            src="{{ $imgUrl }}" 
                            alt="{{ $product->name }}"
                            loading="lazy"
                            class="w-full h-full object-cover scale-100 group-hover:scale-105 transition-transform duration-500 ease-out"
                        >

                        {{-- Badges --}}
                        <div class="absolute top-3 left-3 z-10">
                            <span class="rounded bg-[#B88A44] px-2.5 py-0.5 text-[8px] font-bold text-white uppercase tracking-widest shadow-sm">Bestseller</span>
                        </div>

                        {{-- Wishlist Toggle --}}
                        <div class="absolute top-3 right-3 z-10" x-data="{ wishlisted: false }">
                            @auth
                                <form action="{{ route('user.wishlist.add', $product->id) }}" method="POST" @submit.prevent="fetch($el.action, {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(() => wishlisted = !wishlisted)">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center shadow-sm transition duration-200 hover:scale-110 focus:outline-none"
                                        :class="wishlisted ? 'text-rose-500' : 'text-gray-600 hover:text-rose-500'"
                                        aria-label="Add {{ $product->name }} to wishlist"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" :fill="wishlisted ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                                    </button>
                                </form>
                            @else
                                <a 
                                    href="{{ route('login') }}" 
                                    class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center text-gray-600 hover:text-rose-500 hover:scale-110 shadow-sm transition duration-200 focus:outline-none"
                                    aria-label="Login to add {{ $product->name }} to wishlist"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                                </a>
                            @endauth
                        </div>

                        {{-- Add to Cart Overlay (Desktop slides up, Mobile always shown at bottom) --}}
                        <div class="absolute inset-x-3 bottom-3 z-10 translate-y-2 opacity-0 lg:group-hover:translate-y-0 lg:group-hover:opacity-100 transition-all duration-300">
                            <form action="{{ route('store.cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="w-full h-10 rounded-xl bg-[#111827] text-white hover:bg-[#B88A44] text-xs font-bold uppercase tracking-wider transition-colors duration-200 shadow-lg"
                                >
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Card Details --}}
                    <div class="pt-4 space-y-1.5 flex-grow flex flex-col justify-between">
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                <span>{{ $product->brand ? $product->brand->name : 'Quiet Luxury' }}</span>
                                
                                {{-- Star Rating --}}
                                <div class="flex items-center gap-0.5 text-[#B88A44]">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" class="lucide lucide-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <span class="text-[9px] font-bold text-gray-500">
                                        {{ number_format($product->reviews_avg_rating ?? 5.0, 1) }}
                                        <span class="text-gray-400 font-normal">({{ $product->reviews_count ?? 1 }})</span>
                                    </span>
                                </div>
                            </div>

                            <h3 class="font-serif font-bold text-base text-[#111827] hover:text-[#B88A44] transition-colors duration-200 line-clamp-1">
                                <a href="{{ route('store.product.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                        </div>

                        {{-- Pricing and Stock --}}
                        <div class="pt-3 border-t border-gray-50 flex items-center justify-between mt-3">
                            <div class="flex items-baseline gap-1.5">
                                @if($product->sale_price)
                                    <span class="font-serif font-bold text-sm text-[#B88A44]">₹{{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-xs text-gray-400 line-through">₹{{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="font-serif font-bold text-sm text-[#111827]">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>

                            {{-- Mobile Cart Trigger (Visible only on mobile screen widths) --}}
                            <div class="lg:hidden">
                                <form action="{{ route('store.cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-700 hover:bg-[#B88A44] hover:text-white transition duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </li>
            @endforeach
        </ul>

    </div>
</section>
@endif
