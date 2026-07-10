@props([
    'item'
])

@php
    $product = $item->product;
@endphp

@if($product)
<div class="group relative bg-white rounded-[20px] border border-gray-150 overflow-hidden hover:border-brand-300 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-full">
    
    {{-- Card Header & Image --}}
    <div>
        <div class="aspect-square bg-gray-50 border-b border-gray-100 overflow-hidden relative group/img">
            @if($product->featuredImage)
                <img src="{{ asset('storage/' . $product->featuredImage->image_path) }}" class="w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-700" alt="">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold bg-gray-50">No Image</div>
            @endif

            {{-- Badges --}}
            @if($product->sale_price)
                @php
                    $discountPct = round((($product->price - $product->sale_price) / $product->price) * 100);
                @endphp
                <span class="absolute top-4 left-4 inline-flex items-center rounded-full bg-red-50 border border-red-150 px-2.5 py-0.5 text-[9px] font-bold text-red-650 tracking-wider">
                    -{{ $discountPct }}% OFF
                </span>
            @endif

            {{-- Quick remove --}}
            <form action="{{ route('user.wishlist.remove', $product->id) }}" method="POST" class="absolute top-4 right-4 z-10">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-8 h-8 rounded-full bg-white/95 backdrop-blur shadow hover:bg-red-50 hover:text-red-500 text-gray-500 flex items-center justify-center transition-all duration-200" title="Remove from Wishlist">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                </button>
            </form>
        </div>

        {{-- Details --}}
        <div class="p-5 space-y-2">
            {{-- Rating --}}
            <div class="flex items-center gap-1">
                <div class="flex items-center text-amber-400">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-[10px] text-gray-400 font-bold">(4.9)</span>
            </div>

            <h4 class="font-serif font-bold text-base text-gray-900 truncate hover:text-brand-500 transition-colors duration-200">
                <a href="{{ route('store.product.show', $product->slug) }}">{{ $product->name }}</a>
            </h4>

            <div class="flex items-baseline gap-2">
                @if($product->sale_price)
                    <span class="font-serif font-black text-base text-gray-900">₹{{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-xs text-gray-400 line-through font-medium">₹{{ number_format($product->price, 2) }}</span>
                @else
                    <span class="font-serif font-black text-base text-gray-900">₹{{ number_format($product->price, 2) }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="p-5 pt-0 flex gap-2">
        <form action="{{ route('store.cart.add', $product->id) }}" method="POST" class="flex-grow">
            @csrf
            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-brand-500 py-2.5 text-xs font-bold text-white hover:bg-brand-600 transition shadow-md shadow-brand-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Move to Bag</span>
            </button>
        </form>
        <a href="{{ route('store.product.show', $product->slug) }}" class="p-2.5 rounded-xl bg-gray-50 border border-gray-100 text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition" title="Quick View">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </a>
    </div>
</div>
@endif
