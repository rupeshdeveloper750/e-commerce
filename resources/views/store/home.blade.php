@extends('layouts.store')

@section('title', 'Home')

@section('content')
<div class="space-y-12">
    
    {{-- Hero Section / Banner Slider --}}
    @if($banners->count() > 0)
        <div class="relative rounded-3xl overflow-hidden bg-slate-900 border border-slate-800 shadow-2xl h-[300px] md:h-[400px] flex items-center px-8 md:px-16">
            @php $hero = $banners->first(); @endphp
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('storage/' . $hero->image) }}" class="w-full h-full object-cover opacity-35" alt="">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950 to-transparent"></div>
            </div>
            <div class="relative z-10 max-w-lg space-y-4">
                <span class="text-amber-500 text-sm font-semibold tracking-wider uppercase">{{ $hero->sub_title }}</span>
                <h1 class="text-3xl md:text-5xl font-black text-white leading-tight">{{ $hero->title }}</h1>
                @if($hero->link)
                    <a href="{{ $hero->link }}" class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-6 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-amber-500/20 hover:bg-amber-400 transition">
                        Shop Collection
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="rounded-3xl bg-slate-900 border border-slate-800 p-12 text-center space-y-4 shadow-xl">
            <h1 class="text-3xl font-extrabold text-white">Welcome to ShopMe!</h1>
            <p class="text-slate-400 max-w-md mx-auto text-sm">Explore our catalog of premium brand items and find the best offers today.</p>
            <a href="{{ route('store.shop') }}" class="inline-flex rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-slate-950 hover:bg-amber-400 transition">Explore Shop</a>
        </div>
    @endif

    {{-- Featured Categories --}}
    @if($featuredCategories->count() > 0)
        <div class="space-y-6">
            <h2 class="text-2xl font-bold text-white tracking-tight">Shop by Category</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($featuredCategories as $cat)
                    <a href="{{ route('store.shop', ['category' => $cat->slug]) }}" class="group rounded-2xl bg-slate-900 border border-slate-800 p-4 text-center hover:border-amber-500 transition duration-300">
                        <div class="w-16 h-16 mx-auto rounded-full bg-slate-950 flex items-center justify-center overflow-hidden border border-slate-850">
                            @if($cat->image)
                                <img src="{{ asset('storage/' . $cat->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300" alt="">
                            @else
                                <span class="font-bold text-amber-500 text-lg">{{ strtoupper(substr($cat->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <h4 class="mt-3 font-semibold text-sm text-slate-200 group-hover:text-amber-400 transition">{{ $cat->name }}</h4>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Featured Products --}}
    @if($featuredProducts->count() > 0)
        <div class="space-y-6">
            <h2 class="text-2xl font-bold text-white tracking-tight">Featured Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $prod)
                    <div class="group relative rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden hover:border-slate-700 transition duration-300 flex flex-col justify-between">
                        <div>
                            {{-- Product Image --}}
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
                            {{-- Content --}}
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
        </div>
    @endif

    {{-- Latest Products --}}
    @if($latestProducts->count() > 0)
        <div class="space-y-6">
            <h2 class="text-2xl font-bold text-white tracking-tight">New Arrivals</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($latestProducts as $prod)
                    <div class="group relative rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden hover:border-slate-700 transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="aspect-square bg-slate-950 border-b border-slate-850 overflow-hidden relative">
                                @if($prod->featuredImage)
                                    <img src="{{ asset('storage/' . $prod->featuredImage->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $prod->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-600 font-bold bg-slate-950">No Image</div>
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
        </div>
    @endif

</div>
@endsection
