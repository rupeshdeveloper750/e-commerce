@props([
    'eyebrowText',
    'heading',
    'description',
    'ctaText',
    'ctaLink',
    'brandFeatures'
])

<section class="w-full py-16 bg-white border-b border-[#E5E7EB]/80" aria-labelledby="why-shopme-title">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            {{-- LEFT Column (40% width -> 5 cols in grid-12) --}}
            <div class="lg:col-span-5 space-y-6 flex flex-col justify-center">
                <div class="space-y-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] block">
                        {{ $eyebrowText }}
                    </span>
                    <h2 id="why-shopme-title" class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-[#111827] leading-[1.1] tracking-tight">
                        {{ $heading }}
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-gray-500 font-medium leading-relaxed">
                    {{ $description }}
                </p>
                <div class="pt-2">
                    <a 
                        href="{{ $ctaLink }}" 
                        class="group inline-flex items-center gap-1.5 text-xs font-bold text-[#B88A44] transition duration-200 relative pb-1 overflow-hidden"
                    >
                        <span>{{ $ctaText }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right transition-transform group-hover:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        {{-- Hover Underline Slide Interaction --}}
                        <span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B88A44] translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-300"></span>
                    </a>
                </div>
            </div>

            {{-- Dynamic Divider Column (Border display) --}}
            <div class="hidden lg:block lg:col-span-1 h-48 border-l border-gray-200 justify-self-center"></div>

            {{-- RIGHT Column (60% width -> 6 cols in grid-12) --}}
            <div class="lg:col-span-6">
                {{-- 2x2 Grid of features (responsive: 1-col on mobile, 2-col on larger) --}}
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-8">
                    @foreach($brandFeatures as $feat)
                        <li class="space-y-3 group cursor-default">
                            {{-- Icon Wrapper --}}
                            <div 
                                class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-[#B88A44] shadow-sm transition-all duration-300 group-hover:-translate-y-0.5 group-hover:shadow-md group-hover:border-[#B88A44]/20"
                                aria-hidden="true"
                            >
                                @switch($feat->icon)
                                    @case('sparkles')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275Z"/><path d="m5 3 1 2.5L8.5 6 6 7 5 9.5 4 7 1.5 6 4 5Z"/><path d="m19 17 1 2.5 2.5.5-2.5 1-1 2.5-1-2.5-2.5-1 2.5-1Z"/></svg>
                                        @break
                                    @case('leaf')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-leaf"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 3.5 0 5.5l-5.5 5.5c-1.43 1.43-2.42 2.42-2.5 4v3z"/><path d="M19 2 10 11"/></svg>
                                        @break
                                    @case('shield-check')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 9.7a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 .76-.97l8-2a1 1 0 0 1 .48 0l8 2A1 1 0 0 1 20 6z"/><path d="m9 12 2 2 4-4"/></svg>
                                        @break
                                    @case('headset')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-headset"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
                                        @break
                                    @default
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.75z"/><path d="m9 12 2 2 4-4"/></svg>
                                @endswitch
                            </div>

                            {{-- Text Info --}}
                            <div class="space-y-1">
                                <h3 class="font-serif text-base font-bold text-[#111827] group-hover:text-[#B88A44] transition-colors duration-200">
                                    {{ $feat->title }}
                                </h3>
                                <p class="text-xs text-gray-500 font-normal leading-relaxed">
                                    {{ $feat->description }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</section>
