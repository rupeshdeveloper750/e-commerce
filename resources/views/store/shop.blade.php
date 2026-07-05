@extends('layouts.store')

@section('title', 'Shop Catalog')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    
    {{-- Sidebar Filters --}}
    <div class="lg:col-span-1 space-y-6">
        <form action="{{ route('store.shop') }}" method="GET" class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-6">
            <h3 class="text-base font-semibold text-white border-b border-slate-800 pb-3">Filters</h3>
            
            {{-- Search parameter (carried over) --}}
            @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif

            {{-- Categories --}}
            <div class="space-y-2">
                <label for="category" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide">Category</label>
                <select id="category" name="category" onchange="this.form.submit()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Brands --}}
            <div class="space-y-2">
                <label for="brand" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide">Brand</label>
                <select id="brand" name="brand" onchange="this.form.submit()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200">
                    <option value="">All Brands</option>
                    @foreach($brands as $br)
                        <option value="{{ $br->slug }}" @selected(request('brand') == $br->slug)>{{ $br->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Price range --}}
            <div class="space-y-3">
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wide">Price Range</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-200 placeholder-slate-650">
                    <span class="text-slate-650 text-xs">to</span>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-200 placeholder-slate-650">
                </div>
            </div>

            <div class="pt-2 flex gap-2">
                <a href="{{ route('store.shop') }}" class="w-1/2 inline-flex items-center justify-center rounded-xl border border-slate-800 text-xs text-slate-400 py-2.5 hover:bg-slate-800 transition">Reset</a>
                <button type="submit" class="w-1/2 inline-flex items-center justify-center rounded-xl bg-amber-500 text-slate-950 text-xs font-semibold py-2.5 hover:bg-amber-400 transition">Apply</button>
            </div>
        </form>
    </div>

    {{-- Product Grid --}}
    <div class="lg:col-span-3 space-y-6">
        
        {{-- Catalog Controls / Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-800 pb-4">
            <div>
                <h2 class="text-xl font-semibold text-white">Catalog</h2>
                @if(request('q') || request('category') || request('brand'))
                    <p class="text-xs text-slate-400 mt-1">Showing search results for active filters.</p>
                @endif
            </div>

            {{-- Sorting --}}
            <form action="{{ route('store.shop') }}" method="GET" class="flex items-center gap-2">
                @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif
                @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                
                <label for="sort" class="text-xs font-semibold text-slate-500 whitespace-nowrap">Sort By:</label>
                <select id="sort" name="sort" onchange="this.form.submit()" class="bg-slate-900 border border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-300">
                    <option value="latest" @selected(request('sort') == 'latest')>Newest</option>
                    <option value="price_low" @selected(request('sort') == 'price_low')>Price: Low to High</option>
                    <option value="price_high" @selected(request('sort') == 'price_high')>Price: High to Low</option>
                    <option value="name_asc" @selected(request('sort') == 'name_asc')>Name: A to Z</option>
                    <option value="name_desc" @selected(request('sort') == 'name_desc')>Name: Z to A</option>
                </select>
            </form>
        </div>

        {{-- Products --}}
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($products as $prod)
                    <div class="group relative rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden hover:border-slate-700 transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="aspect-square bg-slate-950 border-b border-slate-850 overflow-hidden relative">
                                @if($prod->featuredImage)
                                    <img src="{{ asset('storage/' . $prod->featuredImage->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $prod->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-600 font-bold bg-slate-950">No Image</div>
                                @endif
                                @if($prod->sale_price)
                                    <span class="absolute top-3 left-3 bg-red-500 text-white font-bold text-[10px] px-2 py-0.5 rounded uppercase">Sale</span>
                                @endif
                            </div>
                            <div class="p-4 space-y-1">
                                <span class="text-[10px] text-amber-500 font-medium uppercase tracking-wide">{{ $prod->brand ? $prod->brand->name : 'General' }}</span>
                                <h3 class="font-semibold text-white group-hover:text-amber-400 transition text-sm truncate">
                                    <a href="{{ route('store.product.show', $prod->slug) }}">{{ $prod->name }}</a>
                                </h3>
                                <p class="text-xs text-slate-400 line-clamp-2">{{ $prod->short_description }}</p>
                            </div>
                        </div>
                        <div class="p-4 pt-0 flex items-center justify-between gap-2">
                            <span class="font-bold text-white">
                                @if($prod->sale_price)
                                    ₹{{ number_format($prod->sale_price, 2) }}
                                @else
                                    ₹{{ number_format($prod->price, 2) }}
                                @endif
                            </span>
                            <form action="{{ route('store.cart.add', $prod->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="rounded-lg bg-amber-500 text-slate-950 p-2 hover:bg-amber-400 transition" title="Add to Cart">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination --}}
            <div class="pt-6">
                {{ $products->links() }}
            </div>
        @else
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-12 text-center space-y-3">
                <svg class="w-12 h-12 mx-auto text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                <h3 class="text-lg font-semibold text-white">No Products Found</h3>
                <p class="text-sm text-slate-400 max-w-sm mx-auto">We couldn't find any products matching your active filters. Try resetting search parameters.</p>
                <a href="{{ route('store.shop') }}" class="inline-flex rounded-xl bg-amber-500 px-5 py-2 text-xs font-semibold text-slate-950 hover:bg-amber-400 transition">View All Products</a>
            </div>
        @endif

    </div>

</div>
@endsection
