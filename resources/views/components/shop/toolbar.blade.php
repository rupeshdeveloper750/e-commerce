@props(['products'])

<!-- DESKTOP TOOLBAR (Hidden on mobile, flex on md and up) -->
<div class="hidden md:flex items-center justify-between gap-6 py-3 border-b border-gray-100/60 bg-white transition-all duration-300">
    {{-- Left side: Count and Search inline --}}
    <div class="flex items-center gap-5">
        <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] leading-none shrink-0">
            {{ $products->total() }} pieces curated
        </span>
        <form action="{{ route('store.shop') }}" method="GET" class="relative group">
            @foreach(request()->except('q', 'page') as $key => $val)
                @if(is_array($val))
                    @foreach($val as $v)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endif
            @endforeach
            <input 
                type="text" 
                name="q" 
                value="{{ request('q') }}"
                placeholder="Search collection..." 
                class="h-9.5 pl-10 pr-4 w-60 rounded-full bg-gray-50/80 border border-gray-150/50 text-[11px] font-medium text-stone-800 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#B88A44] focus:ring-1 focus:ring-[#B88A44] transition-all duration-300 shadow-sm"
                aria-label="Search"
            >
            <span class="absolute left-3.5 top-2.5 text-gray-400 group-focus-within:text-[#B88A44] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </span>
        </form>
    </div>

    {{-- Right side: Grid toggles and sort selection --}}
    <div class="flex items-center gap-4">
        {{-- View Column Toggles (4 cols vs 5 cols) --}}
        <div class="hidden xl:flex items-center border border-gray-150/40 rounded-full p-1 bg-[#FAF9F6] shadow-sm">
            <button 
                @click="gridCols = 4" 
                class="p-1.5 rounded-full transition-all duration-300"
                :class="gridCols === 4 ? 'bg-white text-stone-900 shadow-sm' : 'text-gray-400 hover:text-stone-800'"
                title="4 Column Grid"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/></svg>
            </button>
            <button 
                @click="gridCols = 5" 
                class="p-1.5 rounded-full transition-all duration-300"
                :class="gridCols === 5 ? 'bg-white text-stone-900 shadow-sm' : 'text-gray-400 hover:text-stone-800'"
                title="5 Column Grid"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="5" height="7" x="2" y="3" rx="1"/><rect width="5" height="7" x="9" y="3" rx="1"/><rect width="5" height="7" x="16" y="3" rx="1"/><rect width="7" height="7" x="2" y="14" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/></svg>
            </button>
        </div>

        {{-- Sort Form --}}
        <form action="{{ route('store.shop') }}" method="GET" id="toolbar-sort-form">
            @foreach(request()->except('sort', 'page') as $key => $value)
                @if(is_array($value))
                    @foreach($value as $v)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <select 
                id="sort" 
                name="sort" 
                onchange="document.getElementById('toolbar-sort-form').submit()" 
                class="h-9.5 px-4 rounded-full bg-[#FAF9F6] border border-gray-150/50 text-[10px] font-bold uppercase tracking-widest text-stone-800 focus:outline-none focus:border-[#B88A44] focus:ring-1 focus:ring-[#B88A44] transition-colors cursor-pointer shadow-sm"
            >
                <option value="latest" @selected(request('sort') == 'latest')>New Arrival</option>
                <option value="price_low" @selected(request('sort') == 'price_low')>Price: Low to High</option>
                <option value="price_high" @selected(request('sort') == 'price_high')>Price: High to Low</option>
                <option value="name_asc" @selected(request('sort') == 'name_asc')>Name: A to Z</option>
            </select>
        </form>
    </div>
</div>

<!-- MOBILE TOOLBAR (Visible on mobile, hidden on md and up) -->
<div class="flex md:hidden flex-col gap-3 py-3 border-b border-gray-100/60 bg-white transition-all duration-300">
    <div class="flex items-center justify-between px-1">
        <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] leading-none">
            {{ $products->total() }} pieces curated
        </span>
    </div>

    <!-- Search Input full-width -->
    <form action="{{ route('store.shop') }}" method="GET" class="relative group w-full">
        @foreach(request()->except('q', 'page') as $key => $val)
            @if(is_array($val))
                @foreach($val as $v)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endif
        @endforeach
        <input 
            type="text" 
            name="q" 
            value="{{ request('q') }}"
            placeholder="Search collection..." 
            class="h-9.5 pl-10 pr-4 w-full rounded-full bg-gray-50/80 border border-gray-150/50 text-[11px] font-medium text-stone-800 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#B88A44] focus:ring-1 focus:ring-[#B88A44] transition-all duration-300 shadow-sm"
            aria-label="Search"
        >
        <span class="absolute left-3.5 top-2.5 text-gray-400 group-focus-within:text-[#B88A44] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </span>
    </form>

    <!-- Mobile Action Strip (Filters | Sort) -->
    <div class="flex items-center w-full border-t border-gray-50 pt-2.5">
        <div class="flex items-center w-full border border-gray-150/60 rounded-full bg-[#FAF9F6] p-0.5 divide-x divide-gray-200/80">
            <!-- Filter button (50% width) -->
            <button 
                @click="mobileFiltersOpen = true" 
                class="flex-1 inline-flex items-center justify-center gap-2 h-9 text-[10px] font-bold uppercase tracking-widest text-stone-800 focus:outline-none"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="21" x2="14" y1="4" y2="4"/><line x1="10" x2="3" y1="4" y2="4"/><line x1="21" x2="12" y1="12" y2="12"/><line x1="8" x2="3" y1="12" y2="12"/><line x1="21" x2="16" y1="20" y2="20"/><line x1="12" x2="3" y1="20" y2="20"/><line x1="14" x2="14" y1="2" y2="6"/><line x1="8" x2="8" y1="10" y2="14"/><line x1="16" x2="16" y1="18" y2="22"/></svg>
                <span>Filters</span>
            </button>
            
            <!-- Sort dropdown (50% width) -->
            <div class="flex-1 relative flex items-center justify-center">
                <form action="{{ route('store.shop') }}" method="GET" id="toolbar-sort-form-mobile" class="w-full h-full flex items-center justify-center">
                    @foreach(request()->except('sort', 'page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <select 
                        id="sort-mobile" 
                        name="sort" 
                        onchange="document.getElementById('toolbar-sort-form-mobile').submit()" 
                        class="w-full h-full bg-transparent text-center border-0 text-[10px] font-bold uppercase tracking-widest text-stone-800 focus:outline-none cursor-pointer pr-4"
                    >
                        <option value="latest" @selected(request('sort') == 'latest')>Sort By</option>
                        <option value="price_low" @selected(request('sort') == 'price_low')>Price: Low-High</option>
                        <option value="price_high" @selected(request('sort') == 'price_high')>Price: High-Low</option>
                        <option value="name_asc" @selected(request('sort') == 'name_asc')>Name: A-Z</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>
