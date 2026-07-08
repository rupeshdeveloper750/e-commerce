@extends('layouts.store')

@section('title', 'Our Story & Philosophy')

@section('content')
<div class="bg-white -mx-6 sm:-mx-8 lg:-mx-12 -mt-12 sm:-mt-20 -mb-16 py-8 md:py-12 min-h-screen text-stone-900">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 space-y-16">

        {{-- Breadcrumbs --}}
        <nav class="text-[9px] uppercase tracking-widest text-stone-400 font-bold flex items-center gap-1.5" aria-label="Breadcrumb">
            <a href="{{ route('store.home') }}" class="hover:text-[#B88A44] transition-colors">Home</a>
            <span>/</span>
            <span class="text-stone-850">Our Story</span>
        </nav>

        {{-- Hero Header --}}
        <div class="max-w-3xl space-y-5">
            <span class="text-[9px] font-bold uppercase tracking-widest text-[#B88A44] block">Crafted with Purpose</span>
            <h1 class="font-serif text-3xl sm:text-5xl md:text-6xl font-bold tracking-tight text-stone-950 leading-tight">
                Quiet luxury essentials, made to transition with you.
            </h1>
            <p class="text-xs sm:text-sm text-stone-500 leading-relaxed font-medium">
                ShopMe was built on a simple premise: premium bags and laptop sleeves shouldn't compromise on durability or classic minimalist design. We design carrying essentials for creators, professionals, and daily commuters who value clean aesthetics and robust, functional craftsmanship.
            </p>
        </div>

        {{-- Brand Pillars Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="space-y-3">
                <span class="font-serif text-3xl font-bold text-[#B88A44]">01</span>
                <h3 class="font-serif text-lg font-bold">Premium Materials</h3>
                <p class="text-xs text-stone-500 leading-relaxed font-medium">
                    We source full-grain aniline leathers, heavy waxed canvases, and sturdy brass fixtures. Every hide is inspected for depth of grain, character, and age resistance.
                </p>
            </div>
            <div class="space-y-3">
                <span class="font-serif text-3xl font-bold text-[#B88A44]">02</span>
                <h3 class="font-serif text-lg font-bold">Unmatched Craft</h3>
                <p class="text-xs text-stone-500 leading-relaxed font-medium">
                    Our seamstresses sew each backpack using double-locked polyester stitching. Zippers and stress points are reinforced with hidden metal anchors and leather overlays.
                </p>
            </div>
            <div class="space-y-3">
                <span class="font-serif text-3xl font-bold text-[#B88A44]">03</span>
                <h3 class="font-serif text-lg font-bold">Sustainably Sourced</h3>
                <p class="text-xs text-stone-500 leading-relaxed font-medium">
                    We work with local tanneries utilizing eco-conscious vegetable tanning methods. All inner linings are made from 100% recycled PET water bottles.
                </p>
            </div>
        </div>

        {{-- Large Split Image + Text Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-center">
            <div class="lg:col-span-5 aspect-[4/5] rounded-xl overflow-hidden bg-stone-50 border border-stone-100 shadow-sm relative group">
                <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=600" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out" alt="Handcrafted Leather Stitching">
            </div>
            <div class="lg:col-span-7 space-y-6">
                <span class="text-[9px] font-bold uppercase tracking-widest text-[#B88A44] block">The Art of Assembly</span>
                <h2 class="font-serif text-2xl sm:text-4xl font-bold text-stone-900 leading-tight">
                    Behind every stitch, a standard of patience.
                </h2>
                <div class="text-xs text-stone-500 leading-relaxed space-y-4 font-medium">
                    <p>
                        A luxury accessory is only as good as the sum of its raw parts. That is why our design studio spends months prototype-testing handle configurations, drop heights, and compartment arrangements. 
                    </p>
                    <p>
                        We reject short-term trendy silhouettes. Instead, our sleeves and backpacks embrace clean horizontal lines, minimalist curves, and classic earth-toned details. Whether you're entering a boardroom, working at a coffee house, or boarding a flight, your ShopMe piece complements your posture and focus.
                    </p>
                </div>

                {{-- Key Figures --}}
                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-stone-100">
                    <div>
                        <span class="font-serif text-2xl sm:text-3xl font-bold text-stone-950">20k+</span>
                        <span class="text-[8px] uppercase tracking-widest text-stone-400 font-bold block mt-0.5">Bags Sewn</span>
                    </div>
                    <div>
                        <span class="font-serif text-2xl sm:text-3xl font-bold text-stone-950">99.4%</span>
                        <span class="text-[8px] uppercase tracking-widest text-stone-400 font-bold block mt-0.5">Satisfied</span>
                    </div>
                    <div>
                        <span class="font-serif text-2xl sm:text-3xl font-bold text-stone-950">5 Years</span>
                        <span class="text-[8px] uppercase tracking-widest text-stone-400 font-bold block mt-0.5">Warranty</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline / Journey Section --}}
        <div class="space-y-8 bg-[#FAF9F6]/40 p-6 md:p-10 rounded-2xl border border-stone-100/60">
            <div class="text-center space-y-1">
                <span class="text-[9px] font-bold uppercase tracking-widest text-[#B88A44] block">Our Journey</span>
                <h2 class="font-serif text-2xl sm:text-3xl font-bold">How We Built ShopMe</h2>
            </div>

            <div class="relative border-l border-stone-200 ml-4 md:ml-32 space-y-8">
                <!-- Timeline item 1 -->
                <div class="relative pl-6 md:pl-8">
                    <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full bg-[#B88A44] border-2 border-white ring-4 ring-[#B88A44]/15"></div>
                    <span class="font-serif text-sm font-bold text-[#B88A44] block">2022 — The Blueprint</span>
                    <h4 class="text-xs font-bold text-stone-850 mt-0.5">Founders sketch the first modular sleeve design.</h4>
                    <p class="text-[11px] text-stone-500 leading-relaxed mt-1 max-w-xl">Tired of bulky tech bags, we designed a slim sleeve that fits into commuter backpacks while still offering dedicated slots for chargers and writing utensils.</p>
                </div>
                <!-- Timeline item 2 -->
                <div class="relative pl-6 md:pl-8">
                    <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full bg-[#B88A44] border-2 border-white ring-4 ring-[#B88A44]/15"></div>
                    <span class="font-serif text-sm font-bold text-[#B88A44] block">2023 — Tanneries & Waxed Canvas</span>
                    <h4 class="text-xs font-bold text-stone-850 mt-0.5">Partnering with ethical materials suppliers.</h4>
                    <p class="text-[11px] text-stone-500 leading-relaxed mt-1 max-w-xl">Established raw materials pipelines with tanneries using vegetable- tanning extract instead of chrome, sourcing high-grade organic canvas.</p>
                </div>
                <!-- Timeline item 3 -->
                <div class="relative pl-6 md:pl-8">
                    <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full bg-[#B88A44] border-2 border-white ring-4 ring-[#B88A44]/15"></div>
                    <span class="font-serif text-sm font-bold text-[#B88A44] block">2024 — Launch & Community Expansion</span>
                    <h4 class="text-xs font-bold text-stone-850 mt-0.5">ShopMe store opens for orders.</h4>
                    <p class="text-[11px] text-stone-500 leading-relaxed mt-1 max-w-xl">Launched our flagship store online. Our canvas commuter backpacks sold out within the first two weeks of release.</p>
                </div>
                <!-- Timeline item 4 -->
                <div class="relative pl-6 md:pl-8">
                    <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full bg-[#B88A44] border-2 border-white ring-4 ring-[#B88A44]/15"></div>
                    <span class="font-serif text-sm font-bold text-[#B88A44] block">2026 — Quiet Luxury Standard</span>
                    <h4 class="text-xs font-bold text-stone-850 mt-0.5">Over 100,000 sleeves shipped.</h4>
                    <p class="text-[11px] text-stone-500 leading-relaxed mt-1 max-w-xl">Recognized as one of the premium accessory stores for professionals across the nation, constantly iterating on zero-plastic production.</p>
                </div>
            </div>
        </div>

        {{-- Founder's Quote Card --}}
        <div class="border border-stone-100 rounded-2xl p-6 md:p-12 bg-stone-50/50 flex flex-col items-center text-center space-y-4 max-w-4xl mx-auto">
            <span class="text-[8px] uppercase tracking-widest text-[#B88A44] font-bold">Founder's Note</span>
            <p class="font-serif text-lg md:text-2xl italic text-stone-900 leading-relaxed max-w-2xl">
                "We don't believe in fast fashion or disposable travel gear. Every bag we sew is an investment meant to develop a rich, personal patina over years of journeys."
            </p>
            <div class="space-y-0.5">
                <span class="text-xs font-bold text-stone-850 block">Aravind Sharma</span>
                <span class="text-[9px] uppercase tracking-widest text-stone-400 font-bold block">Creative Director & Founder</span>
            </div>
        </div>

    </div>
</div>
@endsection
