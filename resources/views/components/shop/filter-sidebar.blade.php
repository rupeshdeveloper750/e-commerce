@props(['categories', 'brands'])

<aside class="w-full bg-transparent space-y-6 max-h-[80vh] flex flex-col">
    <!-- Header: Frameless and Minimal -->
    <div class="pb-4 flex items-center justify-between flex-shrink-0 border-b border-stone-100">
        <h2 class="text-xs font-bold uppercase tracking-widest text-stone-900">Filter By</h2>
        @if(request()->anyFilled(['category', 'brand', 'min_price', 'max_price', 'q', 'color', 'size', 'rating']))
            <a href="{{ route('store.shop') }}" class="text-[9px] font-bold text-[#B88A44] hover:text-stone-900 uppercase tracking-widest transition-colors duration-250">Clear All</a>
        @endif
    </div>

    <!-- Filter Form -->
    <form action="{{ route('store.shop') }}" method="GET" class="flex flex-col flex-grow overflow-hidden h-full" id="sidebar-filter-form">
        @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

        <!-- Scrollable fields wrapper -->
        <div class="space-y-6 overflow-y-auto pr-1 flex-grow no-scrollbar">

        {{-- Categories Accordion --}}
        <div class="space-y-3" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none group">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] group-hover:text-stone-950 transition-colors duration-200">Category</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-300 text-stone-400 group-hover:text-stone-950" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="space-y-2.5 pt-1">
                @foreach($categories as $cat)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input 
                                type="checkbox" 
                                name="category[]" 
                                value="{{ $cat->slug }}"
                                @checked(in_array($cat->slug, (array)request('category')) || request('category') === $cat->slug)
                                class="sr-only peer"
                            >
                            <!-- Custom Luxury Checkbox Box -->
                            <div class="w-4 h-4 border border-stone-250 rounded bg-white transition-all duration-200 peer-checked:bg-stone-950 peer-checked:border-stone-950 flex items-center justify-center group-hover:border-stone-400">
                                <svg class="w-2.5 h-2.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                        </div>
                        <span class="text-[12px] font-medium text-stone-550 group-hover:text-stone-950 transition-colors duration-200 select-none">{{ $cat->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Brands Accordion --}}
        <div class="space-y-3 border-t border-stone-100 pt-5" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none group">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] group-hover:text-stone-950 transition-colors duration-200">Brand</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-300 text-stone-400 group-hover:text-stone-950" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="space-y-2.5 pt-1">
                @foreach($brands as $br)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input 
                                type="checkbox" 
                                name="brand[]" 
                                value="{{ $br->slug }}"
                                @checked(in_array($br->slug, (array)request('brand')) || request('brand') === $br->slug)
                                class="sr-only peer"
                            >
                            <!-- Custom Luxury Checkbox Box -->
                            <div class="w-4 h-4 border border-stone-250 rounded bg-white transition-all duration-200 peer-checked:bg-stone-950 peer-checked:border-stone-950 flex items-center justify-center group-hover:border-stone-400">
                                <svg class="w-2.5 h-2.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                        </div>
                        <span class="text-[12px] font-medium text-stone-550 group-hover:text-stone-950 transition-colors duration-200 select-none">{{ $br->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Price Accordion --}}
        <div class="space-y-4 border-t border-stone-100 pt-5" x-data="{ 
            open: true,
            min: {{ request('min_price', 0) }},
            max: {{ request('max_price', 150000) }},
            minLimit: 0,
            maxLimit: 150000
        }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none group">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] group-hover:text-stone-950 transition-colors duration-200">Price Range</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-300 text-stone-400 group-hover:text-stone-950" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="space-y-4 pt-1">
                <div class="relative w-full h-1 bg-stone-100 rounded-full">
                    <div class="absolute h-full bg-[#B88A44] rounded-full" 
                         :style="`left: ${((min - minLimit) / (maxLimit - minLimit)) * 100}%; right: ${100 - (((max - minLimit) / (maxLimit - minLimit)) * 100)}%`">
                    </div>
                    <input type="range" x-model.number="min" :min="minLimit" :max="maxLimit" step="1000" class="absolute w-full h-1 pointer-events-none appearance-none bg-transparent top-0 left-0 z-20 cursor-pointer [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:w-3.5 [&::-webkit-slider-thumb]:h-3.5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-[#B88A44] [&::-webkit-slider-thumb]:appearance-none">
                    <input type="range" x-model.number="max" :min="minLimit" :max="maxLimit" step="1000" class="absolute w-full h-1 pointer-events-none appearance-none bg-transparent top-0 left-0 z-20 cursor-pointer [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:w-3.5 [&::-webkit-slider-thumb]:h-3.5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-[#B88A44] [&::-webkit-slider-thumb]:appearance-none">
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative w-full">
                        <span class="absolute left-2.5 top-1.5 text-[7px] text-stone-400 font-bold leading-none">MIN</span>
                        <input type="number" name="min_price" x-model.number="min" class="w-full h-8 pl-7 pr-2 rounded bg-stone-50 border border-transparent text-[11px] font-medium text-stone-700 focus:outline-none focus:bg-white focus:border-stone-200 transition-all duration-300">
                    </div>
                    <span class="text-gray-300 text-xs">—</span>
                    <div class="relative w-full">
                        <span class="absolute left-2.5 top-1.5 text-[7px] text-stone-400 font-bold leading-none">MAX</span>
                        <input type="number" name="max_price" x-model.number="max" class="w-full h-8 pl-7 pr-2 rounded bg-stone-50 border border-transparent text-[11px] font-medium text-stone-700 focus:outline-none focus:bg-white focus:border-stone-200 transition-all duration-300">
                    </div>
                </div>
            </div>
        </div>

        {{-- Color Swatches --}}
        <div class="space-y-3 border-t border-stone-100 pt-5" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none group">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] group-hover:text-stone-950 transition-colors duration-200">Colors</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-300 text-stone-400 group-hover:text-stone-950" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="flex flex-wrap gap-2.5 pt-1">
                @foreach([
                    'cream' => '#F3EFE9', 'tan' => '#D2B48C', 'dark' => '#1F2937', 
                    'gold' => '#B88A44', 'stone' => '#78716C', 'neutral' => '#D4D4D8'
                ] as $colorName => $colorHex)
                    <label class="cursor-pointer relative group select-none">
                        <input type="checkbox" name="color[]" value="{{ $colorName }}" @checked(in_array($colorName, (array)request('color'))) class="sr-only peer">
                        <span class="w-6 h-6 rounded-full border border-stone-200 block transition-all duration-200 group-hover:scale-105 peer-checked:ring-1 peer-checked:ring-stone-950 peer-checked:ring-offset-1.5" style="background-color: {{ $colorHex }};"></span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Sizes --}}
        <div class="space-y-3 border-t border-stone-100 pt-5" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none group">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] group-hover:text-stone-950 transition-colors duration-200">Sizes</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-300 text-stone-400 group-hover:text-stone-950" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="flex flex-wrap gap-2 pt-1">
                @foreach(['XS', 'S', 'M', 'L', 'XL'] as $size)
                    <label class="cursor-pointer select-none">
                        <input type="checkbox" name="size[]" value="{{ $size }}" @checked(in_array($size, (array)request('size'))) class="sr-only peer">
                        <span class="w-8.5 h-8.5 rounded border border-stone-200 flex items-center justify-center text-[10px] font-bold text-stone-600 bg-white peer-checked:bg-stone-950 peer-checked:text-white peer-checked:border-transparent transition-all duration-200">{{ $size }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Availability --}}
        <div class="space-y-3 border-t border-stone-100 pt-5" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none group">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] group-hover:text-stone-950 transition-colors duration-200">Availability</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-300 text-stone-400 group-hover:text-stone-950" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="space-y-2.5 pt-1">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center justify-center">
                        <input 
                            type="checkbox" 
                            name="stock_status" 
                            value="in_stock" 
                            @checked(request('stock_status') === 'in_stock') 
                            class="sr-only peer"
                        >
                        <!-- Custom Luxury Checkbox Box -->
                        <div class="w-4 h-4 border border-stone-250 rounded bg-white transition-all duration-200 peer-checked:bg-stone-950 peer-checked:border-stone-950 flex items-center justify-center group-hover:border-stone-400">
                            <svg class="w-2.5 h-2.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                    </div>
                    <span class="text-[12px] font-medium text-stone-550 group-hover:text-stone-950 transition-colors duration-200 select-none">In Stock Only</span>
                </label>
            </div>
        </div>

        </div>

        {{-- Action Buttons (Sticky at bottom, outside the scrollable div) --}}
        <div class="pt-4 mt-2 flex items-center gap-2.5 border-t border-stone-100 flex-shrink-0 bg-white">
            <a href="{{ route('store.shop') }}" class="w-1/2 h-10 border border-stone-250 hover:border-stone-950 text-stone-850 hover:text-stone-950 text-[10px] font-bold uppercase tracking-widest transition-all duration-300 rounded-[4px] bg-white flex items-center justify-center">
                Clear
            </a>
            <button type="submit" class="w-1/2 h-10 bg-stone-950 hover:bg-[#B88A44] text-white text-[10px] font-bold uppercase tracking-widest transition-all duration-300 rounded-[4px] flex items-center justify-center">
                Apply
            </button>
        </div>

     </form>
</aside>
