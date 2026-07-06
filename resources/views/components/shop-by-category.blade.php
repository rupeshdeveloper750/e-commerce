@props(['categories'])

<section class="w-full pt-0 pb-12 bg-white" aria-labelledby="category-heading">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 space-y-10 md:space-y-12">
        
        {{-- Elegant Header --}}
        <div class="text-center space-y-2">
            <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] block">Curated For You</span>
            <h2 id="category-heading" class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-[#111827] tracking-tight">Shop by Category</h2>
        </div>

        {{-- Categories Grid Layout (Perfect fit for 5 items) --}}
        <ul class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 md:gap-8">
            @foreach($categories as $cat)
                @php
                    // Exact keyword image mapping for database records
                    $imageUrl = 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=400'; // Default Fallback
                    $name = strtolower($cat->name);
                    
                    if ($cat->image && !str_starts_with($cat->image, 'http')) {
                        $imageUrl = asset('storage/' . $cat->image);
                    } else {
                        if (str_contains($name, 'fashion') || str_contains($name, 'clothing') || str_contains($name, 'women') || str_contains($name, 'men')) {
                            $imageUrl = 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=400';
                        } elseif (str_contains($name, 'electronics') || str_contains($name, 'tech')) {
                            $imageUrl = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400';
                        } elseif (str_contains($name, 'footwear') || str_contains($name, 'shoe')) {
                            $imageUrl = 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400';
                        } elseif (str_contains($name, 'watch') || str_contains($name, 'time')) {
                            $imageUrl = 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=400';
                        } elseif (str_contains($name, 'bag') || str_contains($name, 'luggage')) {
                            $imageUrl = 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=400';
                        }
                    }
                @endphp
                <li class="@if($loop->last && ($loop->count % 2 !== 0)) col-span-2 md:col-span-1 @endif">
                    <a 
                        href="/shop?category={{ $cat->slug }}" 
                        class="group block relative aspect-[3/4] @if($loop->last && ($loop->count % 2 !== 0)) aspect-[16/10] md:aspect-[3/4] @endif w-full rounded-2xl overflow-hidden border border-[#E5E7EB]/60 bg-[#FAF9F6] shadow-sm hover:shadow-xl transition-all duration-[350ms] ease-out focus:outline-none focus:ring-2 focus:ring-[#B88A44] focus:ring-offset-2"
                        aria-label="Shop {{ $cat->name }} collection"
                    >
                        {{-- Background Image --}}
                        <div class="absolute inset-0 w-full h-full z-0 overflow-hidden">
                            <img 
                                src="{{ $imageUrl }}" 
                                alt=""
                                loading="lazy"
                                class="w-full h-full object-cover scale-100 group-hover:scale-105 transition-transform duration-[450ms] ease-out"
                            >
                            {{-- Visual Gradient for Contrast Security --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent transition-opacity duration-[350ms] group-hover:opacity-90"></div>
                        </div>

                        {{-- Category Info Overlay --}}
                        <div class="absolute inset-0 z-10 flex flex-col justify-end p-5 sm:p-6 text-white">
                            <div class="space-y-1">
                                <h3 class="font-serif text-lg md:text-xl font-bold tracking-wide">
                                    {{ $cat->name }}
                                </h3>
                                <span class="inline-flex items-center gap-1 text-[9px] font-bold uppercase tracking-widest text-[#B88A44] opacity-80 group-hover:opacity-100 transition-opacity duration-200">
                                    <span>Explore</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right transition-transform duration-200 group-hover:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </span>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

    </div>
</section>
