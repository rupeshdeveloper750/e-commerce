@props(['products'])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 py-5 border-b border-[#EAEAEA] bg-white sticky top-[80px] z-30 transition-all duration-300">
    {{-- Left: Count & Search Input --}}
    <div class="flex flex-wrap items-center gap-4">
        <span class="text-xs text-gray-500 font-medium">
            Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} pieces
        </span>

        {{-- Embedded Search Input --}}
        <form action="{{ route('store.shop') }}" method="GET" class="relative">
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
                placeholder="Search products..." 
                class="h-9 pl-8 pr-4 w-48 rounded-full bg-[#F8F8F8] border border-[#EAEAEA] text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-[#B88A44] focus:ring-1 focus:ring-[#B88A44] transition-all"
                aria-label="Search"
            >
            <span class="absolute left-3 top-2.5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </span>
        </form>
    </div>

    {{-- Right: View Toggle, Sort, Filters Trigger --}}
    <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto">
        {{-- Mobile Filter Trigger --}}
        <button 
            @click="mobileFiltersOpen = true" 
            class="lg:hidden inline-flex items-center justify-center gap-2 h-10 px-5 rounded-full text-xs font-bold uppercase tracking-wider text-[#111827] border border-[#EAEAEA] bg-[#F8F8F8] hover:bg-[#F5F5F5] transition-colors"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="21" x2="14" y1="4" y2="4"/><line x1="10" x2="3" y1="4" y2="4"/><line x1="21" x2="12" y1="12" y2="12"/><line x1="8" x2="3" y1="12" y2="12"/><line x1="21" x2="16" y1="20" y2="20"/><line x1="12" x2="3" y1="20" y2="20"/><line x1="14" x2="14" y1="2" y2="6"/><line x1="8" x2="8" y1="10" y2="14"/><line x1="16" x2="16" y1="18" y2="22"/></svg>
            <span>Filters</span>
        </button>

        <div class="flex items-center gap-4">
            {{-- View Column Toggles (4 cols vs 5 cols) --}}
            <div class="hidden xl:flex items-center border border-[#EAEAEA] rounded-full p-1 bg-[#F8F8F8]">
                <button 
                    @click="gridCols = 4" 
                    class="p-1.5 rounded-full transition-all duration-200"
                    :class="gridCols === 4 ? 'bg-white text-[#B88A44] shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                    title="4 Column Grid"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/></svg>
                </button>
                <button 
                    @click="gridCols = 5" 
                    class="p-1.5 rounded-full transition-all duration-200"
                    :class="gridCols === 5 ? 'bg-white text-[#B88A44] shadow-sm' : 'text-gray-400 hover:text-gray-600'"
                    title="5 Column Grid"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="5" height="7" x="2" y="3" rx="1"/><rect width="5" height="7" x="9" y="3" rx="1"/><rect width="5" height="7" x="16" y="3" rx="1"/><rect width="7" height="7" x="2" y="14" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/></svg>
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
                    class="h-10 px-4 rounded-full bg-white border border-[#EAEAEA] text-xs font-bold uppercase tracking-wider text-[#111827] focus:outline-none focus:border-[#B88A44] focus:ring-1 focus:ring-[#B88A44] transition-colors cursor-pointer"
                >
                    <option value="latest" @selected(request('sort') == 'latest')>Newest</option>
                    <option value="price_low" @selected(request('sort') == 'price_low')>Price: Low to High</option>
                    <option value="price_high" @selected(request('sort') == 'price_high')>Price: High to Low</option>
                    <option value="name_asc" @selected(request('sort') == 'name_asc')>Name: A to Z</option>
                </select>
            </form>
        </div>
    </div>
</div>
