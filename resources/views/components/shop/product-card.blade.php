@props(['product'])

@php
    // Image fallback resolution
    $imagePath = $product->featuredImage ? $product->featuredImage->image_path : '';
    if (str_starts_with($imagePath, 'http')) {
        $imgUrl = $imagePath;
        $hoverImgUrl = $imagePath;
    } elseif ($imagePath) {
        if (file_exists(public_path('storage/' . $imagePath))) {
            $imgUrl = asset('storage/' . $imagePath);
            $hoverImgUrl = asset('storage/' . $imagePath);
        } else {
            $parentNameLower = strtolower($product->category->parent->name ?? $product->category->name);
            if (str_contains($parentNameLower, 'electronics') || str_contains($parentNameLower, 'tech')) {
                $imgUrl = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400';
                $hoverImgUrl = 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?q=80&w=400';
            } elseif (str_contains($parentNameLower, 'watch')) {
                $imgUrl = 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=400';
                $hoverImgUrl = 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?q=80&w=400';
            } elseif (str_contains($parentNameLower, 'footwear') || str_contains($parentNameLower, 'shoe')) {
                $imgUrl = 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400';
                $hoverImgUrl = 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?q=80&w=400';
            } elseif (str_contains($parentNameLower, 'bag')) {
                $imgUrl = 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=400';
                $hoverImgUrl = 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=400';
            } else {
                $imgUrl = 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=400';
                $hoverImgUrl = 'https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=400';
            }
        }
    } else {
        $imgUrl = 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=400';
        $hoverImgUrl = 'https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=400';
    }
@endphp

<div onclick="if(!event.target.closest('.no-card-redirect')){ window.location='{{ route('store.product.show', $product->slug) }}' }" class="group relative cursor-pointer flex flex-col justify-between rounded-2xl border border-[#E5E7EB]/60 bg-white p-4 hover:shadow-xl hover:border-transparent transition-all duration-300">
    <div>
        {{-- Image Wrapper --}}
        <div class="aspect-[4/5] bg-gray-50 rounded-xl overflow-hidden relative border border-gray-150/40">
            <img 
                src="{{ $imgUrl }}" 
                alt="{{ $product->name }}"
                loading="lazy"
                class="w-full h-full object-cover scale-100 group-hover:scale-105 transition-transform duration-500 ease-out"
            >

            {{-- Muted Outlined Sale Badge --}}
            @if($product->sale_price)
                <div class="absolute top-3 left-3 z-10">
                    <span class="rounded border border-[#B88A44] bg-white/95 px-2.5 py-0.5 text-[8px] font-bold text-[#B88A44] uppercase tracking-widest shadow-sm">Sale</span>
                </div>
            @endif

            {{-- Wishlist Toggle --}}
            <div class="absolute top-3 right-3 z-10 no-card-redirect" x-data="{ wishlisted: false }">
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

            {{-- Add to Cart Overlay on Hover --}}
            <div class="no-card-redirect absolute inset-x-3 bottom-3 z-10 translate-y-2 opacity-0 lg:group-hover:translate-y-0 lg:group-hover:opacity-100 transition-all duration-300">
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

        {{-- Card details --}}
        <div class="pt-4 space-y-1">
            <span class="text-[9px] text-[#B88A44] font-bold uppercase tracking-widest">{{ $product->brand ? $product->brand->name : 'Quiet Luxury' }}</span>
            <h3 class="font-serif font-bold text-base text-[#111827] hover:text-[#B88A44] transition-colors duration-200 line-clamp-1">
                <a href="{{ route('store.product.show', $product->slug) }}">{{ $product->name }}</a>
            </h3>
        </div>
    </div>

    <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-3">
        <div class="flex items-baseline gap-1.5">
            @if($product->sale_price)
                <span class="font-serif font-bold text-sm text-[#B88A44]">₹{{ number_format($product->sale_price, 2) }}</span>
                <span class="text-xs text-gray-400 line-through">₹{{ number_format($product->price, 2) }}</span>
            @else
                <span class="font-serif font-bold text-sm text-[#111827]">₹{{ number_format($product->price, 2) }}</span>
            @endif
        </div>

        {{-- Mobile Add to Cart trigger --}}
        <div class="no-card-redirect lg:hidden">
            <form action="{{ route('store.cart.add', $product->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-150/40 flex items-center justify-center text-gray-700 hover:bg-[#B88A44] hover:text-white transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </button>
            </form>
        </div>
    </div>
</div>
