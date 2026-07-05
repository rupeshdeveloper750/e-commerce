@extends('layouts.store')

@section('title', $product->name)

@section('content')
<div class="space-y-12">
    
    {{-- Product detail top section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">
        
        {{-- Left: Image Gallery --}}
        <div class="space-y-4">
            <div class="aspect-square bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-xl flex items-center justify-center relative">
                @if($product->featuredImage)
                    <img id="main-product-image" src="{{ asset('storage/' . $product->featuredImage->image_path) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                @elseif($product->images->first())
                    <img id="main-product-image" src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-500 font-bold">No Image</div>
                @endif
            </div>
            
            {{-- Thumbnails --}}
            @if($product->images->count() > 1)
                <div class="grid grid-cols-5 gap-3">
                    @foreach($product->images as $img)
                        <button onclick="document.getElementById('main-product-image').src = '{{ asset('storage/' . $img->image_path) }}'" class="aspect-square bg-slate-900 border border-slate-800 rounded-xl overflow-hidden hover:border-amber-500 focus:outline-none transition">
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover" alt="">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right: Content Details --}}
        <div class="space-y-6">
            <div class="space-y-2">
                <span class="text-xs font-semibold text-amber-500 uppercase tracking-wider">{{ $product->brand ? $product->brand->name : 'General' }}</span>
                <h1 class="text-3xl font-extrabold text-white leading-tight">{{ $product->name }}</h1>
                <p class="text-xs text-slate-500">SKU: {{ $product->sku }} | Category: {{ $product->category ? $product->category->name : 'None' }}</p>
            </div>

            {{-- Price --}}
            <div class="text-2xl font-bold flex items-center gap-3">
                @if($product->sale_price)
                    <span class="text-amber-500">₹{{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-sm text-slate-500 line-through">₹{{ number_format($product->price, 2) }}</span>
                @else
                    <span class="text-white">₹{{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <div class="border-t border-b border-slate-800/60 py-4 space-y-2 text-sm text-slate-350">
                <p><span class="text-slate-500">Availability:</span> 
                    @if($product->quantity > 0)
                        <span class="text-emerald-400 font-semibold">In Stock ({{ $product->quantity }} available)</span>
                    @else
                        <span class="text-rose-400 font-semibold">Out of Stock</span>
                    @endif
                </p>
                <p class="leading-relaxed">{{ $product->short_description }}</p>
            </div>

            {{-- Cart actions --}}
            @if($product->quantity > 0)
                <form action="{{ route('store.cart.add', $product->id) }}" method="POST" class="flex flex-col sm:flex-row sm:items-center gap-4">
                    @csrf
                    <div class="w-32 flex items-center border border-slate-800 bg-slate-950 rounded-xl overflow-hidden">
                        <button type="button" onclick="const qty = document.getElementById('quantity'); if(qty.value > 1) qty.value--" class="w-10 h-10 text-slate-400 hover:text-white flex items-center justify-center font-bold">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity }}" class="w-12 h-10 bg-transparent text-center border-0 text-white text-sm focus:ring-0">
                        <button type="button" onclick="const qty = document.getElementById('quantity'); if(qty.value < {{ $product->quantity }}) qty.value++" class="w-10 h-10 text-slate-400 hover:text-white flex items-center justify-center font-bold">+</button>
                    </div>

                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-amber-500/20 hover:bg-amber-400 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Add to Shopping Cart
                    </button>
                </form>
            @endif

            {{-- Wishlist Button --}}
            <form action="{{ route('user.wishlist.add', $product->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-800 bg-slate-900/40 py-2.5 text-xs text-slate-300 hover:bg-slate-800 transition">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    Add to Wishlist
                </button>
            </form>
        </div>

    </div>

    {{-- Full Description Tab --}}
    @if($product->description)
        <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-6 md:p-8 space-y-4">
            <h3 class="text-lg font-semibold text-white border-b border-slate-800 pb-3">Product Description</h3>
            <p class="text-sm text-slate-350 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
        </div>
    @endif

    {{-- Reviews section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Reviews list --}}
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-lg font-semibold text-white">Customer Reviews</h3>
            <div class="space-y-4">
                @forelse($product->reviews as $rev)
                    <div class="rounded-xl border border-slate-800 bg-slate-900/30 p-5 space-y-2">
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span class="font-bold text-slate-300">{{ $rev->user->name }}</span>
                            <span>{{ $rev->created_at->format('M d, Y') }}</span>
                        </div>
                        {{-- Stars --}}
                        <div class="flex text-amber-500 gap-0.5">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $rev->rating ? 'fill-current' : 'text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.17 0l-3.971 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.883c-.783-.57-.372-1.81.587-1.81h4.908a1 1 0 00.95-.69l1.518-4.674z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-sm text-slate-400 leading-relaxed">{{ $rev->comment }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 italic">No reviews for this product yet. Be the first to share your opinion!</p>
                @endforelse
            </div>
        </div>

        {{-- Write a Review --}}
        <div class="lg:col-span-1">
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 p-6 space-y-4">
                <h3 class="text-base font-semibold text-white">Write a Review</h3>
                @auth
                    <form action="{{ route('store.product.review', $product->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="rating" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Rating</label>
                            <select id="rating" name="rating" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200">
                                <option value="5">5 Stars (Excellent)</option>
                                <option value="4">4 Stars (Good)</option>
                                <option value="3">3 Stars (Average)</option>
                                <option value="2">2 Stars (Poor)</option>
                                <option value="1">1 Star (Terrible)</option>
                            </select>
                        </div>
                        <div>
                            <label for="comment" class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Comment</label>
                            <textarea id="comment" name="comment" rows="4" placeholder="Write your review here..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-200 resize-none focus:outline-none focus:border-amber-500"></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-amber-500 py-2.5 text-xs font-semibold text-slate-950 shadow-sm hover:bg-amber-400 transition">Submit Review</button>
                    </form>
                @else
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Please <a href="{{ route('login') }}" class="text-amber-500 hover:underline">login</a> to write a customer review.
                    </p>
                @endauth
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <div class="space-y-6 border-t border-slate-800/60 pt-12">
            <h3 class="text-xl font-bold text-white">Related Products</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $prod)
                    <div class="group relative rounded-2xl border border-slate-800 bg-slate-900 overflow-hidden hover:border-slate-700 transition duration-300 flex flex-col justify-between">
                        <div>
                            <div class="aspect-square bg-slate-950 border-b border-slate-850 overflow-hidden relative">
                                @if($prod->featuredImage)
                                    <img src="{{ asset('storage/' . $prod->featuredImage->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $prod->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-650 font-bold bg-slate-950">No Image</div>
                                @endif
                            </div>
                            <div class="p-4 space-y-1">
                                <span class="text-[10px] text-amber-500 font-medium uppercase tracking-wide">{{ $prod->brand ? $prod->brand->name : 'General' }}</span>
                                <h4 class="font-semibold text-white group-hover:text-amber-400 transition text-sm truncate">
                                    <a href="{{ route('store.product.show', $prod->slug) }}">{{ $prod->name }}</a>
                                </h4>
                            </div>
                        </div>
                        <div class="p-4 pt-0 flex items-center justify-between gap-2">
                            <span class="font-bold text-white">₹{{ number_format($prod->price, 2) }}</span>
                            <form action="{{ route('store.cart.add', $prod->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="rounded-lg bg-amber-500 text-slate-950 p-2 hover:bg-amber-400 transition">
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
