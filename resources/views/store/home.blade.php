@extends('layouts.store')

@section('title', 'Home')

@section('content')
<div class="space-y-28 -mt-6">
    
    {{-- 1. Cinematic Luxury Hero Section --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-gradient-to-br from-[#FAF9F6] via-white to-amber-50/10 border border-[#E5E7EB]/80 rounded-[40px] p-6 sm:p-8 md:p-10 lg:p-12 overflow-hidden relative shadow-2xl shadow-gray-100/50">
        
        {{-- Ambient Orbs --}}
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-[#B88A44]/5 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-amber-50/25 blur-3xl"></div>
        
        {{-- Left Content Column (7/12) --}}
        <div class="lg:col-span-7 space-y-4 relative z-10 flex flex-col justify-between h-full">
            <div>
                {{-- Season Badge --}}
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#B88A44]/10 border border-[#B88A44]/20 text-[10px] font-semibold text-[#B88A44] uppercase tracking-widest mb-3">
                    <span>NEW SEASON COLLECTION 2026</span>
                </div>

                {{-- Luxury Editorial Title --}}
                <h1 class="font-serif font-black text-3xl sm:text-4xl lg:text-5xl xl:text-6xl text-[#111827] leading-[1.08] tracking-tight">
                    The Art of <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#B88A44] to-[#A77933]">Quiet Luxury</span>
                </h1>

                {{-- Emotional Copy --}}
                <p class="text-gray-500 font-medium text-xs sm:text-sm max-w-md mt-4 leading-relaxed">
                    Curating refined fashion, premium innovations, and bespoke timepieces handcrafted for those who appreciate the poetry of details.
                </p>

                {{-- Customer Count / Social Proof --}}
                <div class="flex items-center gap-3 mt-4">
                    <div class="flex -space-x-2">
                        <img class="w-7 h-7 rounded-full border-2 border-white" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop" alt="">
                        <img class="w-7 h-7 rounded-full border-2 border-white" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100&auto=format&fit=crop" alt="">
                        <img class="w-7 h-7 rounded-full border-2 border-white" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=100&auto=format&fit=crop" alt="">
                    </div>
                    <span class="text-xs font-semibold text-gray-500"><strong class="text-[#111827]">10k+</strong> collectors worldwide</span>
                </div>

                {{-- CTA Action Buttons --}}
                <div class="flex flex-wrap items-center gap-4 mt-6">
                    <a href="{{ route('store.shop') }}" class="group/cta-btn inline-flex items-center justify-center gap-2 h-11 px-6 rounded-full text-xs font-bold tracking-wider uppercase text-white bg-gradient-to-r from-[#B88A44] to-[#A77933] hover:from-[#A77933] hover:to-[#8E6226] ring-1 ring-white/10 ring-inset shadow-lg shadow-[#B88A44]/15 hover:shadow-xl hover:shadow-[#B88A44]/25 hover:-translate-y-0.5 transition-all duration-200">
                        <span>Shop Collection</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right transition-transform duration-200 group-hover/cta-btn:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('store.shop') }}" class="inline-flex items-center justify-center h-11 px-6 rounded-full text-xs font-bold tracking-wider uppercase text-[#111827] border border-[#E5E7EB] bg-white/50 backdrop-blur-sm hover:bg-[#FAF9F6] hover:border-[#B88A44]/30 hover:-translate-y-0.5 transition-all duration-200">
                        Explore Lookbook
                    </a>
                </div>
            </div>

            {{-- Trust Indicators / Satisfaction Metrics --}}
            <div class="grid grid-cols-3 gap-6 pt-6 mt-6 border-t border-[#E5E7EB]/80">
                <div>
                    <span class="block font-serif text-xl font-bold text-[#111827]">99.8%</span>
                    <span class="block text-[9px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Satisfaction Rate</span>
                </div>
                <div>
                    <span class="block font-serif text-xl font-bold text-[#111827]">5-Year</span>
                    <span class="block text-[9px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Bespoke Warranty</span>
                </div>
                <div>
                    <span class="block font-serif text-xl font-bold text-[#111827]">Express</span>
                    <span class="block text-[9px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Global Delivery</span>
                </div>
            </div>
        </div>

        {{-- Right Content Column (5/12) --}}
        <div class="lg:col-span-5 relative flex justify-center items-center">
            
            {{-- Main Asymmetrical Image Container --}}
            <div class="w-full max-w-[290px] aspect-[4/5] rounded-[24px] overflow-hidden border border-[#E5E7EB] shadow-xl relative group/img-panel">
                <img 
                    src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=800" 
                    class="w-full h-full object-cover group-hover/img-panel:scale-105 transition-transform duration-700" 
                    alt="Premium Luxury Fashion Campaign"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
            </div>

            {{-- Floating Glass Info Card 1 --}}
            <div class="absolute -left-2 top-1/4 bg-white/80 backdrop-blur-md border border-[#E5E7EB]/70 rounded-xl p-3 shadow-lg hover:-translate-y-1 hover:scale-105 transition-all duration-300 z-10 max-w-[150px]">
                <div class="flex items-center gap-1.5">
                    <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[8px] font-bold uppercase tracking-wider text-gray-450">Pure Wool Coat</span>
                </div>
                <span class="block text-[11px] font-bold text-[#111827] mt-1 leading-tight">Quiet Luxury Tailoring</span>
            </div>

            {{-- Floating Glass Info Card 2 --}}
            <div class="absolute -right-2 bottom-4 bg-white/80 backdrop-blur-md border border-[#E5E7EB]/70 rounded-xl p-3 shadow-lg hover:-translate-y-1 hover:scale-105 transition-all duration-300 z-10 max-w-[150px]">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[8px] font-bold uppercase tracking-wider text-gray-455">Bespoke Chrono</span>
                    <span class="text-[8px] font-bold text-[#B88A44]">₹44,995</span>
                </div>
                <span class="block text-[11px] font-bold text-[#111827] mt-1 leading-tight">Full Metal Gold Precision</span>
            </div>

        </div>

    </section>

    @php
    $trustItems = [
        ['icon' => 'truck', 'title' => 'Free Delivery', 'subtitle' => 'On orders above ₹499'],
        ['icon' => 'refresh', 'title' => 'Easy Returns', 'subtitle' => '7-day return policy'],
        ['icon' => 'shield-check', 'title' => 'Secure Payment', 'subtitle' => '100% secure checkout'],
        ['icon' => 'headset', 'title' => '24/7 Support', 'subtitle' => 'Dedicated support team'],
    ];
    @endphp

    <x-trust-strip :items="$trustItems" />

    <x-shop-by-category :categories="$featuredCategories" />

    {{-- 3. Trending Products (Premium Horizontal Experience) --}}
    <x-best-sellers :products="$featuredProducts" />

    {{-- 4. Why ShopMe (Brand Story + USP Section) --}}
    <x-why-shopme 
        :eyebrowText="$brandStory['eyebrow']"
        :heading="$brandStory['heading']"
        :description="$brandStory['description']"
        :ctaText="$brandStory['cta_text']"
        :ctaLink="$brandStory['cta_link']"
        :brandFeatures="$brandFeatures"
    />

    {{-- 5. Editor's Picks (Editorial Alternating Layout) --}}
    <section class="space-y-16">
        <div class="text-center max-w-md mx-auto space-y-2">
            <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44]">Curators Journal</span>
            <h2 class="font-serif text-3xl sm:text-4xl text-[#111827] font-bold">Editor's Picks</h2>
        </div>

        <div class="space-y-20">
            @foreach([
                [
                    'title' => 'Minimalism & Tailored Suits',
                    'subtitle' => 'REDEFINED WARDROBE STRUCTURES',
                    'img' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?q=80&w=700',
                    'desc' => 'Each coat is double-brushed cashmere and structured shoulder pads, giving a sleek drape silhouette suited for modern corporate spaces or minimalist outdoor weekends.',
                    'btn' => 'Discover Outerwear',
                    'align' => 'left'
                ],
                [
                    'title' => 'Metallic Innovations & Tech',
                    'subtitle' => 'THE DIGITAL SHELF',
                    'img' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?q=80&w=700',
                    'desc' => 'Minimalist matte titanium structures enclosing high-speed processors and high-fidelity sound. Handcrafted luxury casings built to optimize your workspace layout.',
                    'btn' => 'Shop Electronics',
                    'align' => 'right'
                ]
            ] as $pick)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-6 {{ $pick['align'] === 'right' ? 'lg:order-2' : '' }} aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl relative group">
                        <img src="{{ $pick['img'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="">
                    </div>
                    <div class="lg:col-span-6 space-y-6">
                        <span class="text-[9px] font-bold uppercase tracking-widest text-[#B88A44]">{{ $pick['subtitle'] }}</span>
                        <h3 class="font-serif text-3xl sm:text-4xl font-bold text-[#111827] leading-tight">{{ $pick['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $pick['desc'] }}</p>
                        <div class="pt-4">
                            <a href="/shop" class="group/pick-btn inline-flex items-center justify-center gap-2.5 h-11 px-6 rounded-full text-xs font-bold tracking-wider uppercase text-white bg-[#111827] hover:bg-[#B88A44] transition-all duration-200">
                                <span>{{ $pick['btn'] }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right transition-transform duration-200 group-hover/pick-btn:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <x-new-arrivals :products="$latestProducts" />

    {{-- 6. Limited Collection & Countdown Banner --}}
    <section 
        class="rounded-[40px] overflow-hidden bg-gradient-to-br from-[#111827] to-[#1F2937] border border-gray-800 p-8 sm:p-12 md:p-16 lg:p-20 relative text-white grid grid-cols-1 lg:grid-cols-12 gap-8 items-center"
        x-data="{
            expiry: new Date().getTime() + (2 * 24 * 60 * 60 * 1000), // 2 Days from now
            days: '00', hours: '00', minutes: '00', seconds: '00',
            updateTimer() {
                let diff = this.expiry - new Date().getTime();
                if (diff <= 0) return;
                this.days = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
                this.hours = String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                this.minutes = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                this.seconds = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
            }
        }"
        x-init="updateTimer(); setInterval(() => updateTimer(), 1000)"
    >
        <div class="absolute -right-24 -top-24 w-80 h-80 rounded-full bg-[#B88A44]/5 blur-3xl"></div>
        
        <div class="lg:col-span-7 space-y-6 relative z-10">
            <span class="inline-flex items-center rounded-full bg-[#B88A44]/20 border border-[#B88A44]/30 px-3 py-1 text-[9px] font-bold text-[#B88A44] uppercase tracking-widest">
                EXCLUSIVE DROPS
            </span>
            <h2 class="font-serif text-3xl sm:text-5xl font-black leading-tight">
                Tuscan Leather Bag <br>
                <span class="text-[#B88A44]">Limited Drop 2026</span>
            </h2>
            <p class="text-sm text-gray-400 max-w-md leading-relaxed">
                Handcrafted in Tuscany. Stitched with gold silk fibers. Individually numbered edition. Only 50 units remaining globally.
            </p>
            
            {{-- Countdown Ticker --}}
            <div class="flex items-center gap-4 pt-4">
                <div class="text-center">
                    <span class="block text-3xl sm:text-4xl font-serif font-black text-white" x-text="days">00</span>
                    <span class="block text-[8px] font-bold uppercase tracking-wider text-gray-500 mt-1">Days</span>
                </div>
                <span class="text-xl text-[#B88A44] font-bold pb-4">:</span>
                <div class="text-center">
                    <span class="block text-3xl sm:text-4xl font-serif font-black text-white" x-text="hours">00</span>
                    <span class="block text-[8px] font-bold uppercase tracking-wider text-gray-500 mt-1">Hours</span>
                </div>
                <span class="text-xl text-[#B88A44] font-bold pb-4">:</span>
                <div class="text-center">
                    <span class="block text-3xl sm:text-4xl font-serif font-black text-white" x-text="minutes">00</span>
                    <span class="block text-[8px] font-bold uppercase tracking-wider text-gray-500 mt-1">Mins</span>
                </div>
                <span class="text-xl text-[#B88A44] font-bold pb-4">:</span>
                <div class="text-center">
                    <span class="block text-3xl sm:text-4xl font-serif font-black text-white" x-text="seconds">00</span>
                    <span class="block text-[8px] font-bold uppercase tracking-wider text-gray-500 mt-1">Secs</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5 relative z-10 flex flex-col items-center lg:items-end justify-between h-full space-y-6 lg:space-y-0">
            <div class="rounded-3xl border border-gray-800 bg-[#1F2937]/50 backdrop-blur-md p-6 max-w-sm flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl overflow-hidden border border-gray-700 shrink-0">
                    <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=200" class="w-full h-full object-cover" alt="">
                </div>
                <div class="space-y-1">
                    <span class="text-[9px] font-bold text-[#B88A44] uppercase tracking-wider"> Tuscan Heritage </span>
                    <h4 class="text-xs font-bold text-white leading-normal truncate">Florence Heritage Duffel Bag</h4>
                    <span class="text-xs font-bold text-gray-300">₹32,999.00</span>
                </div>
            </div>
            
            <a href="/shop" class="group/limited inline-flex items-center justify-center gap-2.5 h-12 px-8 rounded-full text-xs font-bold tracking-wider uppercase text-[#111827] bg-white hover:bg-[#B88A44] hover:text-white transition-all duration-200 shadow-xl shadow-black/10">
                <span>Reserve Drop</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right transition-transform duration-200 group-hover/limited:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </section>



    {{-- 8. Customer Stories (Luxury Review Cards) --}}
    <section class="space-y-12">
        <div class="text-center max-w-md mx-auto space-y-2">
            <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44]">Voices of Collectors</span>
            <h2 class="font-serif text-3xl sm:text-4xl text-[#111827] font-bold">Customer Stories</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach([
                [
                    'name' => 'Alessandro Rossi',
                    'quote' => 'The precision of the timepiece movement combined with the Florence Heritage leather strap is unmatched. It feels custom tailored.',
                    'role' => 'Horology Collector, Italy'
                ],
                [
                    'name' => 'Sophia Lindstrom',
                    'quote' => 'Minimalist coat structured exactly like luxury tailor shops. Delivery was fast, and the customer concierge helped with size fittings.',
                    'role' => 'Fashion Architect, Sweden'
                ],
                [
                    'name' => 'Julian Drake',
                    'quote' => 'Quiet luxury is details. From gold thread stitch details to micro cogs, ShopMe offers genuine craftsmanship. Exceptionally done.',
                    'role' => 'Principal Designer, UK'
                ]
            ] as $story)
                <div class="rounded-3xl border border-[#E5E7EB]/80 bg-white p-8 flex flex-col justify-between min-h-[220px] shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="space-y-4">
                        <div class="flex gap-1 text-[#B88A44]">
                            @for($i = 0; $i < 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" class="lucide lucide-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endfor
                        </div>
                        <p class="text-sm font-medium text-[#111827] leading-relaxed italic">"{{ $story['quote'] }}"</p>
                    </div>
                    <div class="pt-6">
                        <span class="block text-xs font-bold text-[#111827]">{{ $story['name'] }}</span>
                        <span class="block text-[10px] text-gray-400 mt-1 uppercase font-semibold tracking-wider">{{ $story['role'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 9. Instagram Experience (Luxury Masonry Gallery) --}}
    <section class="space-y-8">
        <div class="text-center max-w-md mx-auto space-y-2">
            <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44]">Visual Journal</span>
            <h2 class="font-serif text-3xl sm:text-4xl text-[#111827] font-bold">@ShopMeOnSet</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach([
                'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=300',
                'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=300',
                'https://images.unsplash.com/photo-1496181130204-7552cc14542e?q=80&w=300',
                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=300',
                'https://images.unsplash.com/photo-1572635196237-14b3f281503f?q=80&w=300',
                'https://images.unsplash.com/photo-1560343090-f0409e92791a?q=80&w=300'
            ] as $idx => $img)
                <div class="aspect-square rounded-2xl overflow-hidden border border-[#E5E7EB]/80 relative group cursor-pointer">
                    <img src="{{ $img }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="">
                    <div class="absolute inset-0 bg-[#B88A44]/15 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram text-white"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</div>
@endsection
