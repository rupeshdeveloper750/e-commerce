@extends('layouts.store')

@section('title', $product->name)

@section('content')
@php
$catSlug = strtolower($product->category->slug ?? '');
$parentSlug = strtolower($product->category->parent->slug ?? '');

$isElectronics = str_contains($catSlug, 'electronics') || str_contains($catSlug, 'tech') || str_contains($parentSlug, 'electronics') || str_contains($parentSlug, 'tech') || str_contains($catSlug, 'watch') || str_contains($parentSlug, 'watch');
$isShoes = str_contains($catSlug, 'shoe') || str_contains($catSlug, 'footwear') || str_contains($parentSlug, 'shoe') || str_contains($parentSlug, 'footwear');
$isBags = str_contains($catSlug, 'bag') || str_contains($parentSlug, 'bag');
$isFurniture = str_contains($catSlug, 'furniture') || str_contains($parentSlug, 'furniture');
$isFashion = !$isElectronics && !$isShoes && !$isBags && !$isFurniture;

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
@endphp

<div class="bg-white -mx-6 sm:-mx-8 lg:-mx-12 -mt-40 -mb-16 py-12 md:py-16 min-h-screen pt-40 px-6 sm:px-8 lg:px-12"
    x-data="{ 
        mainImage: '{{ $product->featuredImage ? asset('storage/' . $product->featuredImage->image_path) : ($product->images->first() ? asset('storage/' . $product->images->first()->image_path) : 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=600') }}',
        activeTab: 'desc',
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
        
        get saveAmount() {
            const op = this.originalPrice;
            const cp = this.currentPrice;
            if (op && op > cp) {
                return op - cp;
            }
            return null;
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
        }
     }">

    <div class="max-w-[1500px] mx-auto space-y-16">

        {{-- Breadcrumbs navigation --}}
        <nav class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold" aria-label="Breadcrumb">
            <a href="{{ route('store.home') }}" class="hover:text-[#B88A44] transition-colors">Home</a>
            @if($product->category)
            <span class="mx-1.5">&bull;</span>
            <a href="{{ route('store.shop', ['category' => $product->category->slug]) }}" class="hover:text-[#B88A44] transition-colors">{{ $product->category->name }}</a>
            @endif
            <span class="mx-1.5">&bull;</span>
            <span class="text-gray-600">{{ $product->name }}</span>
        </nav>

        {{-- Top Section: Gallery + Details Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">

            {{-- Left column: Image Gallery (45%) --}}
            <div class="lg:col-span-5 space-y-6">
                {{-- Main Image Showcase --}}
                <div class="aspect-[4/5] rounded-[28px] overflow-hidden bg-[#F8F8F8] border border-[#EAEAEA] relative group">
                    <img
                        :src="mainImage"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover scale-100 group-hover:scale-105 transition-transform duration-500 ease-out"
                        id="showcase-img">

                    {{-- Badges --}}
                    @if($product->sale_price)
                    <span class="absolute top-4 left-4 z-10 rounded border border-[#B88A44] bg-white/95 px-3 py-1 text-[9px] font-bold text-[#B88A44] uppercase tracking-widest shadow-sm">Sale</span>
                    @endif
                    @if($product->is_bestseller)
                    <span class="absolute top-4 left-4 z-10 mt-8 rounded border border-gray-300 bg-white/95 px-3 py-1 text-[9px] font-bold text-gray-700 uppercase tracking-widest shadow-sm">Best Seller</span>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if($product->images->count() > 0)
                <div class="grid grid-cols-5 gap-3.5">
                    @foreach($product->images as $img)
                    <button
                        @click="mainImage = '{{ asset('storage/' . $img->image_path) }}'"
                        class="aspect-[4/5] rounded-xl overflow-hidden bg-[#F8F8F8] border transition-all duration-200 focus:outline-none"
                        :class="mainImage === '{{ asset('storage/' . $img->image_path) }}' ? 'border-[#B88A44] ring-2 ring-[#B88A44]/10' : 'border-[#EAEAEA] hover:border-gray-400'">
                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover" alt="Thumbnail">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Right column: Product Info (55%) --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- Title & Social Proof --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-widest text-[#B88A44]">{{ $product->brand ? $product->brand->name : 'Quiet Luxury' }}</span>

                        {{-- Live views --}}
                        <div class="flex items-center gap-1.5 text-[10px] text-amber-600 font-bold uppercase tracking-wider bg-amber-50 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                            <span>15 People Viewing</span>
                        </div>
                    </div>

                    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#111827] tracking-tight leading-tight">
                        {{ $product->name }}
                    </h1>

                    {{-- Rating --}}
                    <div class="flex items-center gap-3 text-xs pt-1.5">
                        <div class="flex items-center text-[#B88A44] gap-0.5">
                            @for($i=1; $i<=5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>
                                @endfor
                                <span class="font-bold text-gray-800 ml-1">4.8</span>
                        </div>
                        <span class="text-gray-400 font-semibold">|</span>
                        <span class="text-gray-500 font-medium">{{ $product->reviews->count() }} Verified Reviews</span>
                        <span class="text-gray-400 font-semibold">|</span>
                        <span class="text-gray-500 font-medium">240 Sold This Week</span>
                    </div>
                </div>

                {{-- Price strip --}}
                <div class="bg-[#F8F8F8] border border-[#EAEAEA] rounded-2xl p-5 flex flex-wrap items-center justify-between gap-4">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Price</span>
                        <div class="flex items-baseline gap-2.5">
                            {{-- Dynamic pricing for products with variants --}}
                            <template x-if="variants.length > 0">
                                <div class="flex items-baseline gap-2.5">
                                    <span class="font-serif text-2xl sm:text-3xl font-bold text-[#B88A44]" x-text="formatPrice(currentPrice)"></span>
                                    <template x-if="originalPrice">
                                        <span class="text-sm text-gray-400 line-through" x-text="formatPrice(originalPrice)"></span>
                                    </template>
                                    <template x-if="saveAmount">
                                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                            Save <span x-text="formatPrice(saveAmount)"></span>
                                        </span>
                                    </template>
                                </div>
                            </template>
                            
                            {{-- Fallback static pricing when no variants exist --}}
                            <template x-if="variants.length === 0">
                                <div class="flex items-baseline gap-2.5">
                                    @if($product->sale_price)
                                    <span class="font-serif text-2xl sm:text-3xl font-bold text-[#B88A44]">₹{{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-sm text-gray-400 line-through">₹{{ number_format($product->price, 2) }}</span>
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Save ₹{{ number_format($product->price - $product->sale_price, 0) }}</span>
                                    @else
                                    <span class="font-serif text-2xl sm:text-3xl font-bold text-[#111827]">₹{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                            </template>
                        </div>
                        <span class="text-[10px] text-gray-400 font-medium block">All taxes included. EMI starting at ₹3,400/month.</span>
                    </div>

                    {{-- Stock availability tag --}}
                    <div>
                        @if($product->quantity > 0)
                        @if($product->quantity <= 5)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-50 border border-rose-200 text-[10px] font-bold text-rose-700 uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                            Only {{ $product->quantity }} Left
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-[10px] font-bold text-emerald-700 uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                In Stock
                            </span>
                            @endif
                            @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-100 border border-gray-200 text-[10px] font-bold text-gray-600 uppercase tracking-wider">
                                Out of Stock
                            </span>
                            @endif
                    </div>
                </div>

                {{-- Short Description --}}
                <p class="text-xs text-gray-500 leading-relaxed font-medium">
                    {{ $product->short_description ?? 'Curated items made from sustainably sourced fabrics, designed with relaxed modern silhouettes and classic attention to details.' }}
                </p>

                {{-- Premium Stepper & Actions --}}
                @if($product->quantity > 0)
                <div class="space-y-4 pt-2">

                    {{-- DYNAMIC VARIANT OPTION SELECTORS FROM DATABASE --}}
                    @if(count($productAttributes) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-[#EAEAEA] pb-4">
                        @foreach($productAttributes as $attrName => $values)
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Select {{ ucfirst($attrName) }}</label>
                            
                            {{-- Color / Wood Stain Color Swatches --}}
                            @if(str_contains($attrName, 'color') || str_contains($attrName, 'finish') || str_contains($attrName, 'stain'))
                            <div class="flex items-center gap-2">
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
                                    class="w-8 h-8 rounded-full border flex items-center justify-center transition-all duration-200 focus:outline-none"
                                    :class="selectedAttributes['{{ $attrName }}'] === '{{ strtolower($valData['value']) }}' ? 'border-[#B88A44] ring-2 ring-[#B88A44]/10 scale-105' : 'border-gray-200 hover:scale-105'"
                                    title="{{ ucfirst($valData['value']) }}">
                                    <span class="w-6 h-6 rounded-full block" style="background-color: {{ $hex }}"></span>
                                </button>
                                @endforeach
                            </div>
                            
                            {{-- Size / Storage Buttons --}}
                            @else
                            <div class="flex items-center gap-2 flex-wrap">
                                @foreach($values as $valKey => $valData)
                                <button
                                    @click="selectedAttributes['{{ $attrName }}'] = '{{ strtolower($valData['value']) }}'"
                                    type="button"
                                    class="px-3.5 h-9 rounded-lg border text-xs font-bold transition-all focus:outline-none"
                                    :class="selectedAttributes['{{ $attrName }}'] === '{{ strtolower($valData['value']) }}' ? 'bg-[#111827] text-white border-transparent' : 'bg-white text-gray-700 border-gray-200 hover:border-black'">
                                    {{ $valData['value'] }}
                                </button>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Quantity Stepper & Buttons --}}
                    <form action="{{ route('store.cart.add', $product->id) }}" method="POST" class="flex flex-col sm:flex-row items-stretch gap-4 pt-2">
                        @csrf
                        <input type="hidden" name="quantity" :value="quantity">
                        <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">

                        {{-- Stepper input --}}
                        <div class="h-12 w-32 flex items-center border border-gray-200 rounded-xl overflow-hidden bg-white shrink-0 justify-between self-center sm:self-auto">
                            <button type="button" @click="if(quantity > 1) quantity--" class="w-10 h-full text-gray-400 hover:text-black flex items-center justify-center font-bold text-sm focus:outline-none">-</button>
                            <span class="text-xs font-bold text-gray-800" x-text="quantity"></span>
                            <button type="button" @click="if(quantity < maxQuantity) quantity++" class="w-10 h-full text-gray-400 hover:text-black flex items-center justify-center font-bold text-sm focus:outline-none">+</button>
                        </div>

                        {{-- CTA: Add to cart --}}
                        <button type="submit" class="flex-grow h-12 rounded-xl bg-[#B88A44] hover:bg-[#A37837] text-white text-xs font-bold uppercase tracking-wider transition-colors duration-200 shadow-md flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                                <path d="M3 6h18" />
                                <path d="M16 10a4 4 0 0 1-8 0" />
                            </svg>
                            <span>Add to Shopping Bag</span>
                        </button>
                    </form>
                </div>
                @endif

                {{-- Pincode Delivery Check --}}
                <div class="border-t border-[#EAEAEA] pt-6 space-y-3">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Delivery Check</label>
                    <div class="flex gap-2 max-w-sm">
                        <input
                            type="text"
                            placeholder="Enter Pincode"
                            class="h-10 px-4 rounded-lg bg-gray-50 border border-gray-200 text-xs text-gray-800 focus:outline-none focus:border-[#B88A44] w-full">
                        <button class="h-10 px-5 rounded-lg bg-[#111827] text-white text-[10px] font-bold uppercase tracking-wider hover:bg-black transition-colors shrink-0">
                            Check
                        </button>
                    </div>
                </div>

                {{-- Trust strip --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 border-t border-[#EAEAEA] pt-6">
                    <div class="flex items-center gap-2 text-[10px] text-gray-500 font-semibold uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#B88A44" stroke-width="2.5">
                            <rect width="20" height="14" x="2" y="5" rx="2" />
                            <line x1="2" x2="22" y1="10" y2="10" />
                        </svg>
                        <span>COD Available</span>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] text-gray-500 font-semibold uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#B88A44" stroke-width="2.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        <span>Authentic Product</span>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] text-gray-500 font-semibold uppercase tracking-wider col-span-2 sm:col-span-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#B88A44" stroke-width="2.5">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        </svg>
                        <span>7 Day Easy Return</span>
                    </div>
                </div>

            </div>

        </div>

        {{-- Description tabs and accordions --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 pt-8 border-t border-[#EAEAEA]">

            {{-- Details accordions (6 cols) --}}
            <div class="lg:col-span-6 space-y-4" x-data="{ active: 'desc' }">
                <div class="border border-[#EAEAEA] rounded-2xl overflow-hidden bg-white">
                    <button
                        @click="active = active === 'desc' ? '' : 'desc'"
                        class="w-full flex items-center justify-between p-5 text-left font-serif font-bold text-sm text-[#111827] focus:outline-none">
                        <span>Product Story & Description</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200" :class="active === 'desc' ? 'rotate-180' : ''">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div x-show="active === 'desc'" x-collapse class="px-5 pb-5 pt-1 text-xs text-gray-500 leading-relaxed font-medium whitespace-pre-line border-t border-[#FAF8F5]/80">
                        {{ $product->description ?? 'No specific narrative details provided.' }}
                    </div>
                </div>

                <div class="border border-[#EAEAEA] rounded-2xl overflow-hidden bg-white">
                    <button
                        @click="active = active === 'specs' ? '' : 'specs'"
                        class="w-full flex items-center justify-between p-5 text-left font-serif font-bold text-sm text-[#111827] focus:outline-none">
                        <span>Specifications & Material</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200" :class="active === 'specs' ? 'rotate-180' : ''">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div x-show="active === 'specs'" x-collapse class="px-5 pb-5 pt-1 text-xs text-gray-500 leading-relaxed font-medium border-t border-[#FAF8F5]/80 space-y-2">

                        {{-- Render specs list dynamically based on category --}}
                        @if($isElectronics)
                        <p><span class="font-bold text-gray-700">Processor:</span> High-performance Octa-Core chip</p>
                        <p><span class="font-bold text-gray-700">Warranty:</span> 1 Year Brand Warranty</p>
                        <p><span class="font-bold text-gray-700">Network:</span> 5G / Wi-Fi 6 Enabled</p>
                        <p><span class="font-bold text-gray-700">Battery Capacity:</span> 4500 mAh</p>
                        <p><span class="font-bold text-gray-700">Model Number:</span> SM-{{ $product->sku }}</p>
                        @elseif($isFashion)
                        <p><span class="font-bold text-gray-700">Material:</span> 100% Organic Linen / Cotton blends</p>
                        <p><span class="font-bold text-gray-700">Fit Type:</span> Premium Editorial Custom fit</p>
                        <p><span class="font-bold text-gray-700">Care Instructions:</span> Gentle machine wash cold</p>
                        @elseif($isShoes)
                        <p><span class="font-bold text-gray-700">Sole Material:</span> Pure handcrafted crepe rubber</p>
                        <p><span class="font-bold text-gray-700">Upper Material:</span> Full grain italian leather</p>
                        <p><span class="font-bold text-gray-700">Width:</span> Standard / Wide options</p>
                        @elseif($isBags)
                        <p><span class="font-bold text-gray-700">Capacity:</span> 24 Liters volume</p>
                        <p><span class="font-bold text-gray-700">Material:</span> Heavy-duty canvas with metal clips</p>
                        <p><span class="font-bold text-gray-700">Strap details:</span> Removable padded leather shoulder straps</p>
                        @elseif($isFurniture)
                        <p><span class="font-bold text-gray-700">Dimensions:</span> 180cm x 90cm x 75cm</p>
                        <p><span class="font-bold text-gray-700">Primary Material:</span> Seasoned teak oak wood</p>
                        <p><span class="font-bold text-gray-700">Stain Finish:</span> Natural matte wax</p>
                        @else
                        <p><span class="font-bold text-gray-700">SKU:</span> {{ $product->sku }}</p>
                        <p><span class="font-bold text-gray-700">Category:</span> {{ $product->category ? $product->category->name : 'None' }}</p>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Reviews segment (6 cols) --}}
            <div class="lg:col-span-6 space-y-6">
                <h3 class="font-serif text-xl font-bold text-[#111827]">Customer Reviews</h3>

                {{-- Reviews List --}}
                <div class="space-y-4">
                    @forelse($product->reviews as $rev)
                    <div class="rounded-2xl border border-[#EAEAEA] p-5 space-y-2 bg-[#F8F8F8]">
                        <div class="flex items-center justify-between text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                            <span>{{ $rev->user->name }}</span>
                            <span>{{ $rev->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex text-[#B88A44] gap-0.5">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-3.5 h-3.5 {{ $i <= $rev->rating ? 'fill-current' : 'text-gray-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.17 0l-3.971 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.883c-.783-.57-.372-1.81.587-1.81h4.908a1 1 0 00.95-.69l1.518-4.674z" />
                                </svg>
                                @endfor
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed font-medium">{{ $rev->comment }}</p>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 italic font-medium">No reviews for this product yet. Share your feedback below.</p>
                    @endforelse
                </div>

                {{-- Write review --}}
                <div class="border border-[#EAEAEA] rounded-[24px] p-6 bg-[#F8F8F8] space-y-4">
                    <h4 class="font-serif text-sm font-bold text-[#111827]">Write a review</h4>
                    @auth
                    <form action="{{ route('store.product.review', $product->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="rating" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Rating</label>
                            <select id="rating" name="rating" class="w-full h-10 bg-white border border-[#EAEAEA] rounded-lg px-3 text-xs text-gray-700">
                                <option value="5">5 Stars (Excellent)</option>
                                <option value="4">4 Stars (Good)</option>
                                <option value="3">3 Stars (Average)</option>
                                <option value="2">2 Stars (Poor)</option>
                                <option value="1">1 Star (Terrible)</option>
                            </select>
                        </div>
                        <div>
                            <label for="comment" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Comment</label>
                            <textarea id="comment" name="comment" rows="4" placeholder="Share your experience with this piece..." class="w-full bg-white border border-[#EAEAEA] rounded-lg p-3 text-xs text-gray-700 resize-none focus:outline-none"></textarea>
                        </div>
                        <button type="submit" class="w-full h-10 rounded-full bg-[#111827] text-white text-xs font-bold uppercase tracking-wider hover:bg-black transition duration-200">
                            Submit Review
                        </button>
                    </form>
                    @else
                    <p class="text-xs text-gray-400 font-medium">
                        Please <a href="{{ route('login') }}" class="text-[#B88A44] hover:underline">login</a> to write a review.
                    </p>
                    @endauth
                </div>

            </div>

        </div>

        {{-- Recommendations (Related Products) --}}
        @if($relatedProducts->count() > 0)
        <div class="space-y-8 border-t border-[#EAEAEA] pt-12">
            <div class="text-center space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44] block">You May Also Like</span>
                <h3 class="font-serif text-2xl sm:text-3xl font-bold text-[#111827]">Frequently Bought Together</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($relatedProducts as $prod)
                <x-shop.product-card :product="$prod" />
                @endforeach
            </div>
        </div>
        @endif

    </div>

</div>
@endsection