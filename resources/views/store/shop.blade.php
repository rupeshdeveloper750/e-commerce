@extends('layouts.store')

@section('title', 'The Collection - Shop')

@section('content')
<div class="bg-white -mx-6 sm:-mx-8 lg:-mx-12 -mt-5 -mb-16 py-2 md:py-4 min-h-screen pt-14 px-6 sm:px-8 lg:px-12"
     x-data="{ 
        mobileFiltersOpen: false,
        gridCols: 4
     }">
    
    <div class="max-w-[1500px] mx-auto space-y-8">
        
        {{-- Header and Breadcrumbs removed --}}

        {{-- Category Pills Strip --}}
        <x-shop.category-strip :categories="$categories" />

        {{-- Top Toolbar Control Panel --}}
        <x-shop.toolbar :products="$products" />

        {{-- Active Filter Chips Bar --}}
        @if(request()->anyFilled(['category', 'brand', 'min_price', 'max_price', 'q', 'color', 'size']))
            <div class="flex flex-wrap items-center gap-2 bg-[#F8F8F8] border border-[#EAEAEA] rounded-xl p-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mr-2">Active Filters:</span>
                
                @if(request('q'))
                    <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-[#EAEAEA] text-xs text-gray-600 hover:text-rose-500 hover:border-rose-200 transition-colors">
                        <span>Search: "{{ request('q') }}"</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </a>
                @endif

                @foreach((array)request('category') as $c)
                    @if($c)
                        <a href="{{ request()->fullUrlWithQuery(['category' => array_diff((array)request('category'), [$c])]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-[#EAEAEA] text-xs text-gray-600 hover:text-rose-500 hover:border-rose-200 transition-colors">
                            <span class="capitalize">{{ str_replace('-', ' ', $c) }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                        </a>
                    @endif
                @endforeach

                @foreach((array)request('brand') as $b)
                    @if($b)
                        <a href="{{ request()->fullUrlWithQuery(['brand' => array_diff((array)request('brand'), [$b])]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-[#EAEAEA] text-xs text-gray-600 hover:text-rose-500 hover:border-rose-200 transition-colors">
                            <span class="uppercase">{{ $b }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                        </a>
                    @endif
                @endforeach

                @if(request('min_price') || request('max_price'))
                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-[#EAEAEA] text-xs text-gray-600 hover:text-rose-500 hover:border-rose-200 transition-colors">
                        <span>₹{{ request('min_price', 0) }} - ₹{{ request('max_price', 150000) }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </a>
                @endif

                @foreach((array)request('color') as $col)
                    @if($col)
                        <a href="{{ request()->fullUrlWithQuery(['color' => array_diff((array)request('color'), [$col])]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-[#EAEAEA] text-xs text-gray-600 hover:text-rose-500 hover:border-rose-200 transition-colors">
                            <span class="capitalize">{{ $col }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                        </a>
                    @endif
                @endforeach

                @foreach((array)request('size') as $sz)
                    @if($sz)
                        <a href="{{ request()->fullUrlWithQuery(['size' => array_diff((array)request('size'), [$sz])]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-[#EAEAEA] text-xs text-gray-600 hover:text-rose-500 hover:border-rose-200 transition-colors">
                            <span class="uppercase">{{ $sz }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                        </a>
                    @endif
                @endforeach
                
                <a href="{{ route('store.shop') }}" class="text-[10px] font-bold text-[#B88A44] hover:text-[#A37837] uppercase tracking-wider underline ml-2">Clear All</a>
            </div>
        @endif

        {{-- Columns Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Left Sidebar: Filters --}}
            <div class="hidden lg:block lg:col-span-3 sticky top-28 self-start">
                <x-shop.filter-sidebar :categories="$categories" :brands="$brands" />
            </div>

            {{-- Right Main: Product Listing --}}
            <main class="lg:col-span-9 space-y-12">
                @if($products->isNotEmpty())
                    <div 
                        :class="gridCols === 4 
                            ? 'grid grid-cols-2 md:grid-cols-2 xl:grid-cols-4 gap-8' 
                            : 'grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-6'"
                    >
                        @foreach($products as $index => $prod)
                            <x-shop.product-card :product="$prod" />

                            {{-- Render Editorial Ad Banner between items (e.g. after index 3/4) --}}
                            @if($index === 3 && $products->count() > 4)
                                <x-shop.promotional-banner />
                            @endif
                        @endforeach
                    </div>

                    {{-- Dynamic Pagination --}}
                    <div class="pt-8 border-t border-[#EAEAEA] flex justify-center">
                        {{ $products->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="rounded-[24px] border border-[#EAEAEA] bg-[#F8F8F8] p-16 text-center space-y-4 max-w-xl mx-auto">
                        <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mx-auto text-[#B88A44] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-code"><path d="m13 13.5 2-2.5-2-2.5"/><path d="m21 21-4.3-4.3"/><circle cx="11" cy="11" r="8"/><path d="m8 8.5-2 2.5 2 2.5"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-serif text-lg font-bold text-[#111827]">No pieces match your filters</h3>
                            <p class="text-xs text-gray-500 font-medium">We couldn't find any products matching your active filters. Try clearing some selections.</p>
                        </div>
                        <a href="{{ route('store.shop') }}" class="inline-flex items-center justify-center h-11 px-6 rounded-full text-xs font-bold uppercase tracking-wider text-white bg-[#B88A44] hover:bg-[#A37837] transition duration-200">
                            Reset Filters
                        </a>
                    </div>
                @endif
            </main>

        </div>

    </div>

    {{-- Mobile Filter Drawer Modal --}}
    <div 
        x-show="mobileFiltersOpen" 
        class="fixed inset-0 z-50 lg:hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;"
    >
        <div class="fixed inset-0 bg-black/45 backdrop-blur-sm" @click="mobileFiltersOpen = false"></div>
        <div 
            class="fixed inset-y-0 left-0 max-w-xs w-full bg-white shadow-2xl p-6 flex flex-col justify-between overflow-y-auto"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
        >
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-[#EAEAEA] pb-3">
                    <h2 class="font-serif text-lg font-bold text-[#111827]">Filters</h2>
                    <button @click="mobileFiltersOpen = false" class="text-gray-400 hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
                
                <x-shop.filter-sidebar :categories="$categories" :brands="$brands" />
            </div>
        </div>
    </div>

</div>
@endsection
