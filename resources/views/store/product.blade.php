@extends('layouts.store')

@section('title', $product->name)

@section('content')
@php
$catSlug = strtolower($product->category->slug ?? '');
$parentSlug = strtolower($product->category->parent->slug ?? '');

$productAttributes = [];
if ($product->variants) {
    foreach ($product->variants as $variant) {
        foreach ($variant->attributeValues as $av) {
            if (!$av->attribute) continue;
            $attrName = strtolower($av->attribute->name);
            if (!isset($productAttributes[$attrName])) {
                $productAttributes[$attrName] = [];
            }
            $productAttributes[$attrName][$av->id] = [
                'id' => $av->id,
                'value' => $av->value
            ];
        }
    }
}

// Parse specs or use fallback
$specs = $product->specs;
if (is_string($specs)) {
    $specs = json_decode($specs, true);
}
if (!is_array($specs)) {
    $specs = [
        'Capacity' => '24 Liters volume',
        'Material' => 'Waterproof Canvas with Leather Trims',
        'Laptop Fit' => 'Up to 16-inch laptops',
        'Warranty' => '1 Year Brand Warranty'
    ];
}

$specs = array_merge([
    'Product SKU' => $product->sku ?? 'N/A',
    'Category' => $product->category ? $product->category->name : 'Quiet Luxury',
    'Brand' => $product->brand ? $product->brand->name : 'ShopMe Signature',
    'Stock Status' => $product->quantity > 0 ? ($product->quantity . ' Units Available') : 'Out of Stock',
], $specs);
@endphp



<div class="bg-white -mx-6 sm:-mx-8 lg:-mx-12 -mt-12 sm:-mt-20 -mb-16 py-8 md:py-12 min-h-screen pt-6 px-6 sm:px-8 lg:px-12 text-stone-900 lumina-product-page"
    x-data="{ 
        mainImage: '{{ $product->featuredImage ? asset('storage/' . $product->featuredImage->image_path) : ($product->images->first() ? asset('storage/' . $product->images->first()->image_path) : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=600') }}',
        activeAccordion: 'desc',
        quantity: 1,
        maxQuantity: {{ $product->quantity }},
        wishlisted: false,
        
        selectedAttributes: {},
        
        variants: @js($product->variants->map(function($v) {
            return [
                'id' => $v->id,
                'sku' => $v->sku,
                'price' => (float)$v->price,
                'sale_price' => $v->sale_price ? (float)$v->sale_price : null,
                'quantity' => $v->quantity,
                'image_path' => $v->image_path ? asset('storage/' . $v->image_path) : null,
                'attributes' => $v->attributeValues->mapWithKeys(function($av) {
                    return [strtolower($av->attribute->name) => strtolower($av->value)];
                })->toArray()
            ];
        })),
        
        get selectedVariant() {
            if (!this.variants || this.variants.length === 0) return null;
            return this.variants.find(v => {
                return Object.entries(this.selectedAttributes).every(([attrName, value]) => {
                    return !value || v.attributes[attrName] === value;
                });
            });
        },
        
        get currentPrice() {
            const v = this.selectedVariant;
            if (v) {
                return v.sale_price ? v.sale_price : v.price;
            }
            return {{ $product->sale_price ? $product->sale_price : $product->price }};
        },
        
        get originalPrice() {
            const v = this.selectedVariant;
            if (v) {
                return v.sale_price ? v.price : null;
            }
            return {{ $product->sale_price ? $product->price : 'null' }};
        },
        
        formatPrice(amount) {
            if (!amount) return '';
            return '₹' + Number(amount).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        
        init() {
            if (this.variants && this.variants.length > 0) {
                const firstVariant = this.variants[0];
                Object.entries(firstVariant.attributes).forEach(([key, val]) => {
                    this.selectedAttributes[key] = val;
                });
            }
            this.$watch('selectedVariant', (variant) => {
                if (variant && variant.image_path) {
                    this.mainImage = variant.image_path;
                }
            });
        }
     }">

    <div class="max-w-[1200px] mx-auto space-y-12 pb-16">

        {{-- Top Grid: Product Gallery + Info Panel --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 items-start">

            {{-- Gallery Column --}}
            <div class="lg:col-span-5 flex flex-col gap-5">
                {{-- Main Image Box (Responsive, height-bounded for perfect fitting) --}}
                <div class="w-full aspect-[4/5] sm:aspect-square lg:h-[480px] bg-[#F3F4F6] rounded-xl flex items-center justify-center p-6 relative overflow-hidden">
                    <img :src="mainImage" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain transition-transform duration-500 hover:scale-[1.02]" loading="lazy">
                    @if($product->sale_price)
                    <span class="absolute top-4 left-4 rounded bg-black px-2 py-0.5 text-[9px] font-bold text-white uppercase tracking-wider">Sale</span>
                    @endif
                </div>

                {{-- Horizontal Thumbnails Row --}}
                @if($product->images->count() > 0)
                <div class="grid grid-cols-4 gap-3 sm:gap-4">
                    @foreach($product->images as $img)
                    <button 
                        @click="mainImage = '{{ asset('storage/' . $img->image_path) }}'"
                        class="aspect-square w-full rounded-lg bg-[#F3F4F6] border-2 transition-all flex items-center justify-center p-1 overflow-hidden"
                        :class="mainImage === '{{ asset('storage/' . $img->image_path) }}' ? 'border-black ring-1 ring-black' : 'border-transparent hover:border-gray-300'"
                    >
                        <img src="{{ asset('storage/' . $img->image_path) }}" class="max-h-full max-w-full object-contain" alt="Thumbnail">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Product Info Column --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- Breadcrumbs --}}
                <nav class="text-[10px] uppercase tracking-wider text-gray-400 font-bold flex items-center gap-1.5" aria-label="Breadcrumb">
                    <a href="{{ route('store.home') }}" class="hover:text-black transition-colors">Home</a>
                    @if($product->category)
                    <span class="text-gray-300">&gt;</span>
                    <a href="{{ route('store.shop', ['category' => $product->category->slug]) }}" class="hover:text-black transition-colors">{{ $product->category->name }}</a>
                    @endif
                    <span class="text-gray-300">&gt;</span>
                    <span class="text-gray-900 font-extrabold">{{ $product->name }}</span>
                </nav>

                {{-- Title & Social Proof --}}
                <div class="space-y-2">
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        {{ $product->name }}
                    </h1>

                    @php
                        $avgRating = $product->reviews->avg('rating') ?: 4.8;
                        $reviewCount = $product->reviews->count() ?: 124;
                    @endphp
                    <div class="flex items-center gap-2 text-xs text-gray-500 font-semibold pt-1">
                        <div class="flex items-center text-black gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($avgRating))
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                @else
                                    <svg class="w-3.5 h-3.5 text-gray-305" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span>{{ number_format($avgRating, 1) }} ({{ $reviewCount }} Reviews)</span>
                    </div>
                </div>

                {{-- Price strip --}}
                <div class="py-4 border-y border-gray-150">
                    <div class="flex items-baseline gap-3">
                        <template x-if="variants.length > 0">
                            <div class="flex items-baseline gap-3">
                                <span class="text-2xl font-extrabold text-gray-900" x-text="formatPrice(currentPrice)"></span>
                                <template x-if="originalPrice">
                                    <span class="text-sm text-gray-400 line-through font-semibold" x-text="formatPrice(originalPrice)"></span>
                                </template>
                            </div>
                        </template>
                        <template x-if="variants.length === 0">
                            <div class="flex items-baseline gap-3">
                                @if($product->sale_price)
                                <span class="text-2xl font-extrabold text-gray-900">₹{{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-sm text-gray-400 line-through font-semibold">₹{{ number_format($product->price, 2) }}</span>
                                @else
                                <span class="text-2xl font-extrabold text-gray-900">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Description --}}
                <p class="text-sm text-gray-500 leading-relaxed font-medium">
                    {{ $product->short_description ?? 'Experience unparalleled clarity and performance, featuring a meticulously crafted design for all-day comfort.' }}
                </p>

                {{-- Dynamic Variant Selector --}}
                @if(count($productAttributes) > 0)
                <div class="border-t border-gray-150 pt-5 space-y-4">
                    @foreach($productAttributes as $attrName => $values)
                    <div class="space-y-2.5">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1.5">
                            <span>Select {{ ucfirst($attrName) }}:</span>
                            <span class="text-gray-900 font-extrabold capitalize" x-text="selectedAttributes['{{ $attrName }}'] || 'None'"></span>
                        </div>
                        
                        @if(str_contains($attrName, 'color') || str_contains($attrName, 'finish') || str_contains($attrName, 'stain'))
                        <div class="flex items-center gap-3">
                            @foreach($values as $valKey => $valData)
                            @php
                                $colorMap = [
                                    'silver' => '#E5E7EB',
                                    'space-gray' => '#4B5563',
                                    'gold' => '#F59E0B',
                                    'cream' => '#F3EFE9',
                                    'tan' => '#D2B48C',
                                    'dark' => '#1F2937',
                                    'black' => '#111827',
                                    'midnight black' => '#0F172A',
                                    'brown' => '#78350F',
                                    'white' => '#F9FAFB',
                                    'natural' => '#EAE0D5',
                                    'walnut' => '#5C4033',
                                    'matte-black' => '#1F2937',
                                    'olive' => '#3F6212'
                                ];
                                $hex = $colorMap[strtolower($valData['value'])] ?? '#CCCCCC';
                            @endphp
                            <button
                                @click="selectedAttributes['{{ $attrName }}'] = '{{ strtolower($valData['value']) }}'"
                                type="button"
                                class="w-8 h-8 rounded-full border flex items-center justify-center transition-all focus:outline-none"
                                :class="selectedAttributes['{{ $attrName }}'] === '{{ strtolower($valData['value']) }}' ? 'border-black ring-1 ring-black' : 'border-transparent hover:scale-105'"
                                title="{{ ucfirst($valData['value']) }}"
                            >
                                <span class="w-6 h-6 rounded-full block border border-black/5" style="background-color: {{ $hex }}"></span>
                            </button>
                            @endforeach
                        </div>
                        @else
                        <div class="flex items-center gap-2 flex-wrap">
                            @foreach($values as $valKey => $valData)
                            <button
                                @click="selectedAttributes['{{ $attrName }}'] = '{{ strtolower($valData['value']) }}'"
                                type="button"
                                class="px-4 py-2 rounded border text-xs font-bold uppercase tracking-wider transition-all focus:outline-none"
                                :class="selectedAttributes['{{ $attrName }}'] === '{{ strtolower($valData['value']) }}' ? 'bg-black text-white border-black' : 'bg-white text-gray-700 border-gray-200 hover:border-gray-450'"
                            >
                                {{ $valData['value'] }}
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Variant Specifications Panel --}}
                <template x-if="selectedVariant">
                    <div class="flex items-center gap-4 text-[10px] font-bold text-gray-400 bg-gray-50 px-4 py-2.5 rounded-lg border border-gray-150 transition-all duration-300">
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-black"></span>
                            <span>SKU: <span class="text-gray-900 font-mono uppercase" x-text="selectedVariant.sku"></span></span>
                        </div>
                        <div class="text-gray-200 font-normal">|</div>
                        <div class="flex items-center gap-1.5">
                            <span :class="selectedVariant.quantity > 0 ? 'bg-emerald-500' : 'bg-red-500'" class="w-1.5 h-1.5 rounded-full"></span>
                            <span>Stock: <span :class="selectedVariant.quantity > 0 ? 'text-emerald-700' : 'text-red-600'" class="font-extrabold" x-text="selectedVariant.quantity > 0 ? selectedVariant.quantity + ' Units available' : 'Out of Stock'"></span></span>
                        </div>
                    </div>
                </template>

                {{-- Action Buttons & Quantity Form --}}
                <div class="border-t border-gray-150 pt-5">
                    <form action="{{ route('store.cart.add', $product->id) }}" id="product-purchase-form" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="quantity" :value="quantity">
                        <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">

                        {{-- Quantity Stepper --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Quantity</label>
                            <div class="h-10 w-32 flex items-center border border-gray-300 rounded-md overflow-hidden bg-white">
                                <button type="button" @click="if(quantity > 1) quantity--" class="w-10 h-full flex items-center justify-center text-gray-500 hover:text-black font-semibold focus:outline-none transition active:scale-95">-</button>
                                <span class="flex-1 text-center text-xs font-bold text-gray-900" x-text="quantity"></span>
                                <button type="button" @click="if(quantity < maxQuantity) quantity++" class="w-10 h-full flex items-center justify-center text-gray-500 hover:text-black font-semibold focus:outline-none transition active:scale-95">+</button>
                            </div>
                        </div>

                        {{-- Cart Actions --}}
                        @if($product->quantity > 0)
                        <div class="flex flex-col sm:flex-row gap-4 pt-2">
                            <button type="submit" class="w-full sm:flex-1 h-12 bg-black hover:bg-neutral-900 text-white text-xs font-bold uppercase tracking-widest rounded transition duration-300">
                                Add to Cart
                            </button>
                            <button type="submit" name="buy_now" value="1" class="w-full sm:flex-1 h-12 bg-white border border-gray-300 hover:bg-gray-50 text-black text-xs font-bold uppercase tracking-widest rounded transition duration-300">
                                Buy Now
                            </button>
                        </div>
                        @else
                        <div class="pt-2">
                            <button type="button" disabled class="w-full h-12 bg-gray-200 text-gray-400 text-xs font-bold uppercase tracking-widest rounded cursor-not-allowed">
                                Out of Stock
                            </button>
                        </div>
                        @endif
                    </form>
                </div>

                {{-- Trust Checklist --}}
                <div class="border-t border-gray-150 pt-5 space-y-3.5">
                    <div class="flex items-center gap-3 text-xs text-gray-600 font-medium">
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        <span>Free standard shipping on orders over ₹999</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-600 font-medium">
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>2-year limited warranty included</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-600 font-medium">
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2"/>
                        </svg>
                        <span>30-day hassle-free returns</span>
                    </div>
                </div>

            </div>

        </div>

        {{-- Tabs Component --}}
        <div class="border-t border-gray-200 mt-16 pt-12" x-data="{ currentTab: 'overview' }">
            {{-- Tab Headers --}}
            <div class="flex border-b border-gray-200 gap-8 justify-start">
                <button 
                    @click="currentTab = 'overview'" 
                    class="pb-3 text-xs font-bold uppercase tracking-widest transition-all focus:outline-none relative"
                    :class="currentTab === 'overview' ? 'text-black' : 'text-gray-400 hover:text-black'"
                >
                    <span>Overview</span>
                    <div x-show="currentTab === 'overview'" class="absolute bottom-0 left-0 right-0 h-[2px] bg-black"></div>
                </button>
                <button 
                    @click="currentTab = 'specifications'" 
                    class="pb-3 text-xs font-bold uppercase tracking-widest transition-all focus:outline-none relative"
                    :class="currentTab === 'specifications' ? 'text-black' : 'text-gray-400 hover:text-black'"
                >
                    <span>Specifications</span>
                    <div x-show="currentTab === 'specifications'" class="absolute bottom-0 left-0 right-0 h-[2px] bg-black"></div>
                </button>
                <button 
                    @click="currentTab = 'shipping'" 
                    class="pb-3 text-xs font-bold uppercase tracking-widest transition-all focus:outline-none relative"
                    :class="currentTab === 'shipping' ? 'text-black' : 'text-gray-400 hover:text-black'"
                >
                    <span>Shipping Info</span>
                    <div x-show="currentTab === 'shipping'" class="absolute bottom-0 left-0 right-0 h-[2px] bg-black"></div>
                </button>
            </div>

            {{-- Tab Contents --}}
            <div class="mt-8">
                {{-- Overview Tab --}}
                <div x-show="currentTab === 'overview'" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-5 space-y-4">
                        <h3 class="text-2xl font-bold text-gray-900">Sound, Redefined.</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-medium">
                            {{ $product->description ?? 'This product represents the pinnacle of our design engineering. Crafted with attention to detail and utilizing aerospace-grade materials, it offers an outstanding visual and functional experience for daily use.' }}
                        </p>
                    </div>
                    <div class="lg:col-span-7">
                        <div class="w-full rounded-xl overflow-hidden shadow-sm bg-[#F3F4F6] flex items-center justify-center p-6">
                            <img src="{{ $product->images->count() > 1 ? asset('storage/' . $product->images[1]->image_path) : ($product->featuredImage ? asset('storage/' . $product->featuredImage->image_path) : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80') }}" class="max-h-[300px] object-contain rounded-lg" alt="Product Detail Banner">
                        </div>
                    </div>
                </div>

                {{-- Specs Tab --}}
                <div x-show="currentTab === 'specifications'" class="max-w-2xl">
                    <table class="w-full text-xs">
                        <tbody>
                            @foreach($specs as $key => $val)
                            <tr class="border-b border-gray-100">
                                <td class="py-3.5 font-bold text-gray-800 w-1/3">{{ $key }}</td>
                                <td class="py-3.5 text-gray-500">{{ $val }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Shipping Tab --}}
                <div x-show="currentTab === 'shipping'" class="max-w-2xl text-sm text-gray-500 leading-relaxed space-y-4 font-medium">
                    <p>Standard delivery is free on all orders above ₹999. Orders are shipped in plastic-free recycled boxes and arrive within 2-4 business days.</p>
                    <p>Easy returns are available within 7 days of delivery. For items with a manufacturer's warranty, details are enclosed inside the product packaging.</p>
                </div>
            </div>
        </div>

        {{-- Reviews Section --}}
        <div class="border-t border-gray-200 mt-16 pt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Customer Reviews</h2>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                
                {{-- Rating Summary --}}
                <div class="lg:col-span-4 bg-[#F9FAF9] border border-gray-200 rounded-xl p-8 flex flex-col items-center justify-center text-center">
                    <span class="text-6xl font-extrabold text-gray-900 mb-2">4.8</span>
                    <div class="flex items-center text-black gap-0.5 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-500 font-semibold">Based on 124 reviews</span>
                </div>

                {{-- Reviews List Stack --}}
                <div class="lg:col-span-8 space-y-6">
                    @php
                        $reviews = $product->reviews;
                    @endphp

                    @if($reviews->count() > 0)
                        @foreach($reviews as $rev)
                        <div class="border-b border-gray-150 pb-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-sm font-bold text-gray-900">{{ $rev->user->name }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider ml-3">{{ $rev->created_at->format('F d, Y') }}</span>
                                </div>
                                <div class="flex text-black gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= $rev->rating ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 24 24" fill="{{ $i <= $rev->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm font-bold text-gray-900 mt-2">{{ $rev->rating >= 4 ? 'Great quality and experience' : 'Satisfactory purchase' }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed font-medium mt-1">{{ $rev->comment }}</p>
                        </div>
                        @endforeach
                    @else
                        {{-- Mock Reviews exactly as shown in screenshot --}}
                        <div class="border-b border-gray-150 pb-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-sm font-bold text-gray-900">Alex M.</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider ml-3">October 12, 2023</span>
                                </div>
                                <div class="flex text-black gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm font-bold text-gray-900 mt-2">Exceptional build quality and sound</p>
                            <p class="text-xs text-gray-500 leading-relaxed font-medium mt-1">I've owned many high-end headphones, but this model stands out. The drivers provide incredible clarity in the highs without being harsh, and the bass is tight and controlled. The noise cancellation is also top-tier.</p>
                        </div>

                        <div class="border-b border-gray-150 pb-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-sm font-bold text-gray-900">Sarah J.</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider ml-3">September 28, 2023</span>
                                </div>
                                <div class="flex text-black gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm font-bold text-gray-900 mt-2">Very comfortable for long sessions</p>
                            <p class="text-xs text-gray-500 leading-relaxed font-medium mt-1">I wear these for 8 hours a day while working. The clamping force is just right, and the earpads are incredibly soft. My only minor complaint is that the case is a bit bulky for travel.</p>
                        </div>
                    @endif

                    <div class="pt-4">
                        <a href="#" class="text-xs font-bold text-gray-900 hover:text-gray-600 flex items-center gap-2 tracking-wider uppercase">
                            <span>View All Reviews</span>
                            <svg class="w-3.5 h-3.5 animate-pulse" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Submission form --}}
                    <div class="bg-[#F9FAF9] border border-gray-200 rounded-xl p-6 space-y-4 mt-8">
                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Write a review</h4>
                        @auth
                        <form action="{{ route('store.product.review', $product->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="rating" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Rating</label>
                                <select id="rating" name="rating" class="w-full h-10 bg-white border border-gray-300 rounded px-3 text-xs font-semibold text-gray-700 focus:outline-none">
                                    <option value="5">5 Stars (Excellent)</option>
                                    <option value="4">4 Stars (Good)</option>
                                    <option value="3">3 Stars (Average)</option>
                                    <option value="2">2 Stars (Poor)</option>
                                    <option value="1">1 Star (Terrible)</option>
                                </select>
                            </div>
                            <div>
                                <label for="comment" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Comment</label>
                                <textarea id="comment" name="comment" rows="4" placeholder="Share your experience..." class="w-full bg-white border border-gray-300 rounded p-3 text-xs text-gray-700 resize-none focus:outline-none font-medium"></textarea>
                            </div>
                            <button type="submit" class="w-full h-10 bg-black hover:bg-neutral-900 text-white text-xs font-bold uppercase tracking-widest rounded transition duration-200">
                                Submit Review
                            </button>
                        </form>
                        @else
                        <p class="text-xs text-gray-400 font-semibold">
                            Please <a href="{{ route('login') }}" class="text-black hover:underline font-bold">login</a> to write a review.
                        </p>
                        @endauth
                    </div>
                </div>

            </div>
        </div>

        {{-- Recommendations (Related Products) --}}
        @if($relatedProducts->count() > 0)
        <div class="space-y-8 border-t border-gray-200 pt-12">
            <div class="space-y-1">
                <h3 class="text-2xl font-extrabold text-gray-900">Frequently Bought Together</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $prod)
                <x-shop.product-card :product="$prod" />
                @endforeach
            </div>
        </div>
        @endif

    </div>

</div>



@endsection