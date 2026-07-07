@props(['categories', 'brands'])

<aside class="w-full bg-white border border-[#EAEAEA] rounded-[24px] p-6 space-y-6 transition-all duration-300 hover:shadow-md max-h-[80vh] flex flex-col">
    <div class="border-b border-[#EAEAEA] pb-3 flex items-center justify-between flex-shrink-0">
        <h2 class="font-serif text-lg font-bold text-[#111827]">Filters</h2>
        @if(request()->anyFilled(['category', 'brand', 'min_price', 'max_price', 'q', 'color', 'size', 'rating']))
            <a href="{{ route('store.shop') }}" class="text-[10px] font-bold text-[#B88A44] hover:text-[#A17F4F] uppercase tracking-wider underline">Reset Filters</a>
        @endif
    </div>

    <form action="{{ route('store.shop') }}" method="GET" class="space-y-6 overflow-y-auto pr-2 flex-grow scrollbar-thin scrollbar-thumb-gray-200" id="sidebar-filter-form">
        @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

        {{-- Categories Accordion --}}
        <div class="space-y-3" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none">
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#6B7280]">Category</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="space-y-2.5 pt-1">
                @foreach($categories as $cat)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input 
                            type="checkbox" 
                            name="category[]" 
                            value="{{ $cat->slug }}"
                            @checked(in_array($cat->slug, (array)request('category')) || request('category') === $cat->slug)
                            class="w-4.5 h-4.5 rounded border-gray-300 text-[#B88A44] focus:ring-[#B88A44] cursor-pointer"
                        >
                        <span class="text-xs text-gray-600 group-hover:text-black transition-colors font-medium">{{ $cat->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Brands Accordion --}}
        <div class="space-y-3 border-t border-[#EAEAEA] pt-4" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none">
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#6B7280]">Brand</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="space-y-2.5 pt-1">
                @foreach($brands as $br)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input 
                            type="checkbox" 
                            name="brand[]" 
                            value="{{ $br->slug }}"
                            @checked(in_array($br->slug, (array)request('brand')) || request('brand') === $br->slug)
                            class="w-4.5 h-4.5 rounded border-gray-300 text-[#B88A44] focus:ring-[#B88A44] cursor-pointer"
                        >
                        <span class="text-xs text-gray-600 group-hover:text-black transition-colors font-medium">{{ $br->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Price Accordion --}}
        <div class="space-y-4 border-t border-[#EAEAEA] pt-4" x-data="{ 
            open: true,
            min: {{ request('min_price', 0) }},
            max: {{ request('max_price', 150000) }},
            minLimit: 0,
            maxLimit: 150000
        }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none">
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#6B7280]">Price Range</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="space-y-4 pt-1">
                <div class="relative w-full h-1 bg-gray-200 rounded-full">
                    <div class="absolute h-full bg-[#B88A44] rounded-full" 
                         :style="`left: ${((min - minLimit) / (maxLimit - minLimit)) * 100}%; right: ${100 - (((max - minLimit) / (maxLimit - minLimit)) * 100)}%`">
                    </div>
                    <input type="range" x-model.number="min" :min="minLimit" :max="maxLimit" step="1000" class="absolute w-full h-1 pointer-events-none appearance-none bg-transparent top-0 left-0 z-20 cursor-pointer [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-[#B88A44] [&::-webkit-slider-thumb]:appearance-none">
                    <input type="range" x-model.number="max" :min="minLimit" :max="maxLimit" step="1000" class="absolute w-full h-1 pointer-events-none appearance-none bg-transparent top-0 left-0 z-20 cursor-pointer [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-[#B88A44] [&::-webkit-slider-thumb]:appearance-none">
                </div>
                <div class="flex items-center gap-2">
                    <div class="relative w-full">
                        <span class="absolute left-2 top-2 text-[8px] text-gray-400">MIN</span>
                        <input type="number" name="min_price" x-model.number="min" class="w-full h-9 pl-7 pr-2 rounded-lg bg-gray-50 border border-gray-200 text-xs text-gray-800 focus:outline-none">
                    </div>
                    <span class="text-gray-400 text-xs">—</span>
                    <div class="relative w-full">
                        <span class="absolute left-2 top-2 text-[8px] text-gray-400">MAX</span>
                        <input type="number" name="max_price" x-model.number="max" class="w-full h-9 pl-7 pr-2 rounded-lg bg-gray-50 border border-gray-200 text-xs text-gray-800 focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        {{-- Color Swatches --}}
        <div class="space-y-3 border-t border-[#EAEAEA] pt-4" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none">
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#6B7280]">Color Swatches</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="flex flex-wrap gap-2 pt-1">
                @foreach([
                    'cream' => '#F3EFE9', 'tan' => '#D2B48C', 'dark' => '#1F2937', 
                    'gold' => '#B88A44', 'stone' => '#78716C', 'neutral' => '#D4D4D8'
                ] as $colorName => $colorHex)
                    <label class="cursor-pointer relative group">
                        <input type="checkbox" name="color[]" value="{{ $colorName }}" @checked(in_array($colorName, (array)request('color'))) class="sr-only peer">
                        <span class="w-7 h-7 rounded-full border border-gray-300 block transition-transform group-hover:scale-110 peer-checked:ring-2 peer-checked:ring-[#B88A44] peer-checked:ring-offset-2" style="background-color: {{ $colorHex }};"></span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Sizes --}}
        <div class="space-y-3 border-t border-[#EAEAEA] pt-4" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none">
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#6B7280]">Available Sizes</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="flex flex-wrap gap-1.5 pt-1">
                @foreach(['XS', 'S', 'M', 'L', 'XL'] as $size)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="size[]" value="{{ $size }}" @checked(in_array($size, (array)request('size'))) class="sr-only peer">
                        <span class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-xs font-bold text-gray-700 bg-white peer-checked:bg-[#111827] peer-checked:text-white peer-checked:border-transparent transition-all">{{ $size }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Availability --}}
        <div class="space-y-3 border-t border-[#EAEAEA] pt-4" x-data="{ open: true }">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between text-left focus:outline-none">
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#6B7280]">Availability</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open" x-collapse class="space-y-2.5 pt-1">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="stock_status" value="in_stock" @checked(request('stock_status') === 'in_stock') class="w-4.5 h-4.5 rounded border-gray-300 text-[#B88A44] focus:ring-[#B88A44]">
                    <span class="text-xs text-gray-600 font-medium">In Stock Only</span>
                </label>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="pt-4 flex items-center gap-2 border-t border-[#EAEAEA] flex-shrink-0">
            <a href="{{ route('store.shop') }}" class="w-1/2 h-10 rounded-full border border-gray-200 text-xs font-bold uppercase tracking-wider text-gray-500 hover:bg-gray-50 transition duration-200 flex items-center justify-center">
                Reset
            </a>
            <button type="submit" class="w-1/2 h-10 rounded-full bg-[#B88A44] hover:bg-[#A37837] text-white text-xs font-bold uppercase tracking-wider transition duration-200 shadow-md">
                Apply
            </button>
        </div>

    </form>
</aside>
