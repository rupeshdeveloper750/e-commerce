@extends('layouts.store')

@section('title', 'The Collection - Shop')

@section('content')
<div class="bg-white -mx-6 sm:-mx-8 lg:-mx-12 -mt-12 sm:-mt-20 -mb-16 py-4 md:py-6 min-h-screen pt-4 px-6 sm:px-8 lg:px-12"
     x-data="{ 
        mobileFiltersOpen: false,
        gridCols: 4,
        quickViewOpen: false,
        quickViewProduct: null
     }"
     @open-quickview.window="quickViewProduct = $event.detail; quickViewOpen = true"
     x-effect="document.body.style.overflow = (quickViewOpen || mobileFiltersOpen) ? 'hidden' : ''">
    
    <div class="max-w-[1550px] mx-auto space-y-3.5">
        
        {{-- Category Pills Strip --}}
        <x-shop.category-strip :categories="$categories" />

        {{-- Top Toolbar Control Panel --}}
        <x-shop.toolbar :products="$products" />

        {{-- Active Filter Chips Bar --}}
        @if(request()->anyFilled(['category', 'brand', 'min_price', 'max_price', 'q', 'color', 'size']))
            <div class="flex flex-wrap items-center gap-2 bg-gray-50/60 border border-gray-100 rounded-2xl p-4 transition-all duration-300">
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mr-2">Active Filters:</span>
                
                @if(request('q'))
                    <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white border border-gray-100 text-[10px] text-gray-600 hover:text-rose-500 hover:border-rose-100 transition-all duration-300">
                        <span>Search: "{{ request('q') }}"</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </a>
                @endif

                @foreach((array)request('category') as $c)
                    @if($c)
                        <a href="{{ request()->fullUrlWithQuery(['category' => array_diff((array)request('category'), [$c])]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white border border-gray-100 text-[10px] text-gray-600 hover:text-rose-500 hover:border-rose-100 transition-all duration-300">
                            <span class="capitalize">{{ str_replace('-', ' ', $c) }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                        </a>
                    @endif
                @endforeach

                @foreach((array)request('brand') as $b)
                    @if($b)
                        <a href="{{ request()->fullUrlWithQuery(['brand' => array_diff((array)request('brand'), [$b])]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white border border-gray-100 text-[10px] text-gray-600 hover:text-rose-500 hover:border-rose-100 transition-all duration-300">
                            <span class="uppercase">{{ $b }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                        </a>
                    @endif
                @endforeach

                @if(request('min_price') || request('max_price'))
                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white border border-gray-100 text-[10px] text-gray-600 hover:text-rose-500 hover:border-rose-100 transition-all duration-300">
                        <span>₹{{ request('min_price', 0) }} - ₹{{ request('max_price', 150000) }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </a>
                @endif

                @foreach((array)request('color') as $col)
                    @if($col)
                        <a href="{{ request()->fullUrlWithQuery(['color' => array_diff((array)request('color'), [$col])]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white border border-gray-100 text-[10px] text-gray-600 hover:text-rose-500 hover:border-rose-100 transition-all duration-300">
                            <span class="capitalize">{{ $col }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                        </a>
                    @endif
                @endforeach

                @foreach((array)request('size') as $sz)
                    @if($sz)
                        <a href="{{ request()->fullUrlWithQuery(['size' => array_diff((array)request('size'), [$sz])]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white border border-gray-100 text-[10px] text-gray-600 hover:text-rose-500 hover:border-rose-100 transition-all duration-300">
                            <span class="uppercase">{{ $sz }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                        </a>
                    @endif
                @endforeach
                
                <a href="{{ route('store.shop') }}" class="text-[9px] font-bold text-[#B88A44] hover:text-[#A37837] uppercase tracking-widest underline ml-3 transition-colors duration-300">Clear All</a>
            </div>
        @endif

        {{-- Columns Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
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

                            {{-- Render Editorial Ad Banner between items --}}
                            @if($index === 3 && $products->count() > 4)
                                <x-shop.promotional-banner />
                            @endif
                        @endforeach
                    </div>

                    {{-- Dynamic Pagination --}}
                    <div class="pt-10 border-t border-gray-50 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="rounded-[22px] border border-gray-100 bg-gray-50/50 p-16 text-center space-y-5 max-w-xl mx-auto transition-all duration-300">
                        <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center mx-auto text-[#B88A44] shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-code"><path d="m13 13.5 2-2.5-2-2.5"/><path d="m21 21-4.3-4.3"/><circle cx="11" cy="11" r="8"/><path d="m8 8.5-2 2.5 2 2.5"/></svg>
                        </div>
                        <div class="space-y-2">
                            <h3 class="font-serif text-lg font-semibold text-gray-900">No pieces match your filters</h3>
                            <p class="text-xs text-gray-400 font-normal leading-relaxed max-w-xs mx-auto">We couldn't find any products matching your active filters. Try clearing some selections.</p>
                        </div>
                        <a href="{{ route('store.shop') }}" class="inline-flex items-center justify-center h-10 px-6 rounded-full text-[10px] font-bold uppercase tracking-widest text-white bg-[#B88A44] hover:bg-[#A37837] transition duration-300 shadow-sm">
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
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="mobileFiltersOpen = false"></div>
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
                <div class="flex items-center justify-between border-b border-gray-50 pb-4">
                    <h2 class="font-serif text-lg font-semibold text-gray-900">Filters</h2>
                    <button @click="mobileFiltersOpen = false" class="text-gray-400 hover:text-black transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
                
                <x-shop.filter-sidebar :categories="$categories" :brands="$brands" />
            </div>
        </div>
    </div>

    {{-- Quick View Modal --}}
    <div 
        x-show="quickViewOpen" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" 
        x-cloak
    >
        {{-- Backdrop --}}
        <div 
            class="fixed inset-0 bg-black/40 backdrop-blur-sm" 
            @click="quickViewOpen = false" 
            x-show="quickViewOpen" 
            x-transition.opacity
        ></div>
        
        {{-- Modal Box --}}
        <div 
            class="bg-white rounded-[24px] max-w-4xl w-full overflow-hidden shadow-[0_25px_60px_rgba(0,0,0,0.1)] relative z-10 flex flex-col md:flex-row border border-gray-100 max-h-[90vh] md:max-h-none overflow-y-auto md:overflow-visible transition-all duration-300"
            x-show="quickViewOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        >
            {{-- Close Button --}}
            <button 
                @click="quickViewOpen = false" 
                class="absolute top-4 right-4 z-30 w-9 h-9 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center text-gray-500 hover:text-gray-900 border border-gray-100 shadow-sm active:scale-95 transition-all"
                aria-label="Close modal"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
            
            <template x-if="quickViewProduct">
                <div class="flex flex-col md:flex-row w-full">
                    {{-- Left: Image --}}
                    <div class="w-full md:w-1/2 bg-gray-50/50 aspect-square md:aspect-auto md:h-[480px] relative overflow-hidden flex items-center justify-center border-r border-gray-50">
                        <img :src="quickViewProduct.image" :alt="quickViewProduct.name" class="w-full h-full object-cover">
                    </div>
                    
                    {{-- Right: Content --}}
                    <div class="w-full md:w-1/2 p-8 flex flex-col justify-between space-y-6">
                        <div class="space-y-4">
                            <div>
                                <span class="text-[9px] font-bold uppercase tracking-widest text-[#B88A44]" x-text="quickViewProduct.brand"></span>
                                <h2 class="font-serif text-2xl font-semibold text-gray-900 mt-1" x-text="quickViewProduct.name"></h2>
                                <span class="text-[10px] text-gray-400 font-medium" x-text="quickViewProduct.category"></span>
                            </div>
                            
                            {{-- Price structure --}}
                            <div class="flex items-baseline gap-2 pt-1">
                                <template x-if="quickViewProduct.sale_price">
                                    <div class="flex items-baseline gap-2">
                                        <span class="font-serif text-xl font-bold text-[#B88A44]" x-text="'₹' + quickViewProduct.sale_price"></span>
                                        <span class="text-xs text-gray-400 line-through" x-text="'₹' + quickViewProduct.price"></span>
                                    </div>
                                </template>
                                <template x-if="!quickViewProduct.sale_price">
                                    <span class="font-serif text-xl font-bold text-gray-900" x-text="'₹' + quickViewProduct.price"></span>
                                </template>
                            </div>
                            
                            <p class="text-xs text-gray-500 leading-relaxed font-light" x-text="quickViewProduct.description || 'No description available for this luxury selection.'"></p>
                            
                            {{-- Stock Indicator --}}
                            <div class="pt-2">
                                <template x-if="!quickViewProduct.in_stock">
                                    <span class="inline-flex items-center rounded-full bg-red-50 border border-red-150/45 px-3 py-0.5 text-[8px] font-bold text-red-600 uppercase tracking-widest">Out of Stock</span>
                                </template>
                                <template x-if="quickViewProduct.in_stock && quickViewProduct.stock_count <= 5">
                                    <span class="inline-flex items-center rounded-full bg-amber-50 border border-amber-200/50 px-3 py-0.5 text-[8px] font-bold text-amber-700 uppercase tracking-widest" x-text="'Only ' + quickViewProduct.stock_count + ' pieces left'"></span>
                                </template>
                                <template x-if="quickViewProduct.in_stock && quickViewProduct.stock_count > 5">
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-150/40 px-3 py-0.5 text-[8px] font-bold text-emerald-700 uppercase tracking-widest">In Stock</span>
                                </template>
                            </div>
                        </div>
                        
                        {{-- Actions --}}
                        <div class="space-y-3 pt-6 border-t border-gray-50">
                            <form :action="'/cart/add/' + quickViewProduct.id" method="POST" class="w-full">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button 
                                    type="submit" 
                                    class="w-full h-11 bg-gray-900 hover:bg-[#B88A44] active:scale-98 transition-all duration-300 text-[10px] font-bold uppercase tracking-widest text-white rounded-xl flex items-center justify-center gap-2"
                                    :disabled="!quickViewProduct.in_stock"
                                    :class="!quickViewProduct.in_stock ? 'opacity-50 cursor-not-allowed' : ''"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    <span>Add to Cart</span>
                                </button>
                            </form>
                            
                            <a :href="'/product/' + quickViewProduct.slug" class="w-full h-11 rounded-xl border border-gray-150 flex items-center justify-center text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:text-gray-900 hover:bg-gray-50 active:scale-98 transition-all duration-300">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

</div>
@endsection
