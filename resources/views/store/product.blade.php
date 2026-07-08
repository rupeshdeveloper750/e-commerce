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
@endphp

<!-- Hide global mobile tab bar on product detail page to display CTAs instead -->
<style>
    @media (max-width: 639px) {
        .mobile-bottom-nav {
            display: none !important;
        }
    }
</style>

<div class="bg-white -mx-6 sm:-mx-8 lg:-mx-12 -mt-12 sm:-mt-20 -mb-16 py-8 md:py-12 min-h-screen pt-6 px-6 sm:px-8 lg:px-12 text-stone-900"
    x-data="{ 
        mainImage: '{{ $product->featuredImage ? asset('storage/' . $product->featuredImage->image_path) : ($product->images->first() ? asset('storage/' . $product->images->first()->image_path) : 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=600') }}',
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
        }
     }">

    <div class="max-w-[1400px] mx-auto space-y-12">

        {{-- Breadcrumbs navigation --}}
        <nav class="text-[9px] uppercase tracking-widest text-stone-400 font-bold flex items-center gap-1.5" aria-label="Breadcrumb">
            <a href="{{ route('store.home') }}" class="hover:text-[#B88A44] transition-colors">Home</a>
            @if($product->category)
            <span>/</span>
            <a href="{{ route('store.shop', ['category' => $product->category->slug]) }}" class="hover:text-[#B88A44] transition-colors">{{ $product->category->name }}</a>
            @endif
            <span>/</span>
            <span class="text-stone-850 font-bold">{{ $product->name }}</span>
        </nav>

        {{-- Top Section: Gallery + Details Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-start">

            {{-- Left column: Image Gallery --}}
            <div class="lg:col-span-5 flex flex-col md:flex-row gap-4">
                {{-- Vertical Thumbnails --}}
                @if($product->images->count() > 0)
                <div class="hidden md:flex flex-col gap-3 w-16 shrink-0 pr-2 border-r border-stone-100 max-h-[260px] md:max-h-[300px] lg:max-h-[340px] xl:max-h-[380px] overflow-y-auto no-scrollbar">
                    @foreach($product->images as $img)
                    <button
                        @click="mainImage = '{{ asset('storage/' . $img->image_path) }}'"
                        class="aspect-[4/5] w-full rounded-lg overflow-hidden bg-stone-50 border transition-all duration-300 focus:outline-none relative group"
                        :class="mainImage === '{{ asset('storage/' . $img->image_path) }}' ? 'border-[#B88A44] ring-2 ring-[#B88A44]/10 scale-95' : 'border-stone-200/60 hover:border-stone-400'">
                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-contain p-1.5" alt="Thumbnail" loading="lazy">
                    </button>
                    @endforeach
                </div>
                @endif

                {{-- Main Image Showcase (Frameless and Responsive) --}}
                <div class="flex-grow flex items-center justify-center max-h-[260px] md:max-h-[300px] lg:max-h-[340px] xl:max-h-[380px] relative group bg-transparent">
                    <div class="relative w-fit h-fit mx-auto">
                        <img
                            :src="mainImage"
                            alt="{{ $product->name }}"
                            class="max-w-full max-h-[260px] md:max-h-[300px] lg:max-h-[340px] xl:max-h-[380px] object-contain scale-100 group-hover:scale-[1.03] transition-transform duration-700 ease-out"
                            id="showcase-img"
                            loading="lazy">

                        {{-- Badges --}}
                        @if($product->sale_price)
                        <span class="absolute top-2 left-2 z-10 rounded border border-[#B88A44] bg-white/95 px-2 py-0.5 text-[8px] font-bold text-[#B88A44] uppercase tracking-widest shadow-sm">Sale</span>
                        @endif
                        @if($product->is_bestseller)
                        <span class="absolute top-2 left-2 mt-6 z-10 rounded border border-stone-200 bg-white/95 px-2 py-0.5 text-[8px] font-bold text-stone-700 uppercase tracking-widest shadow-sm">Best Seller</span>
                        @endif

                        {{-- Pulse N Viewing --}}
                        <div class="absolute top-2 right-2 z-10 flex items-center gap-1 bg-white/95 border border-stone-200/50 px-2 py-0.5 rounded-full shadow-sm text-[8px] font-bold text-stone-500 uppercase tracking-widest">
                            <span class="w-1 h-1 bg-[#B88A44] rounded-full animate-ping pulse-dot"></span>
                            <span>15 Viewing</span>
                        </div>
                    </div>
                </div>

                {{-- Horizontal Thumbnails (Visible only on mobile) --}}
                @if($product->images->count() > 0)
                <div class="flex md:hidden items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                    @foreach($product->images as $img)
                    <button
                        @click="mainImage = '{{ asset('storage/' . $img->image_path) }}'"
                        class="aspect-[4/5] w-14 shrink-0 rounded-lg overflow-hidden bg-white border transition-all duration-300 focus:outline-none"
                        :class="mainImage === '{{ asset('storage/' . $img->image_path) }}' ? 'border-[#B88A44] ring-2 ring-[#B88A44]/5' : 'border-gray-150/40'">
                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-contain p-1" alt="Thumbnail" loading="lazy">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Right column: Product Info --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- Title & Social Proof --}}
                <div class="space-y-2">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-[#B88A44] block">{{ $product->brand ? $product->brand->name : 'Quiet Luxury' }}</span>

                    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 tracking-tight leading-tight">
                        {{ $product->name }}
                    </h1>

                    {{-- Rating --}}
                    @if($product->reviews->count() > 0)
                    <div class="flex items-center gap-2.5 text-[10px] pt-1 text-stone-500 font-bold uppercase tracking-widest">
                        <div class="flex items-center text-[#B88A44] gap-0.5">
                            @php
                                $rating = round($product->reviews->avg('rating') ?? 4.8, 1);
                            @endphp
                            @for($i=1; $i<=5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="{{ $i <= $rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                            @endfor
                            <span class="text-stone-850 ml-1">{{ $rating }}</span>
                        </div>
                        <span class="text-stone-300">|</span>
                        <span>{{ $product->reviews->count() }} Reviews</span>
                        <span class="text-stone-300">|</span>
                        <span>240 Sold This Week</span>
                    </div>
                    @endif
                </div>

                {{-- Price strip --}}
                <div class="py-4 border-y border-stone-100 space-y-1.5">
                    <div class="flex items-baseline gap-3">
                        <template x-if="variants.length > 0">
                            <div class="flex items-baseline gap-3">
                                <span class="font-serif text-2xl sm:text-3xl font-bold text-[#B88A44]" x-text="formatPrice(currentPrice)"></span>
                                <template x-if="originalPrice">
                                    <span class="text-sm text-gray-400 line-through" x-text="formatPrice(originalPrice)"></span>
                                </template>
                            </div>
                        </template>
                        <template x-if="variants.length === 0">
                            <div class="flex items-baseline gap-3">
                                @if($product->sale_price)
                                <span class="font-serif text-2xl sm:text-3xl font-bold text-[#B88A44]">₹{{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-sm text-gray-400 line-through">₹{{ number_format($product->price, 2) }}</span>
                                @else
                                <span class="font-serif text-2xl sm:text-3xl font-bold text-stone-900">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                        </template>
                        <div class="ml-1">
                            @if($product->quantity > 0)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded border border-emerald-250 bg-emerald-50 text-[8px] font-bold text-emerald-700 uppercase tracking-widest leading-none">In Stock</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded border border-red-250 bg-red-50 text-[8px] font-bold text-red-700 uppercase tracking-widest leading-none">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                    <span class="text-[9px] text-stone-400 font-bold uppercase tracking-widest block">All taxes included. EMI starting at ₹3,400/month.</span>
                </div>

                {{-- Short Description --}}
                <p class="text-xs text-stone-500 leading-relaxed font-medium">
                    {{ $product->short_description ?? 'Curated items made from sustainably sourced fabrics, designed with relaxed modern silhouettes and classic attention to details.' }}
                </p>

                {{-- Premium Stepper & Actions --}}
                @if($product->quantity > 0)
                <div class="space-y-5 pt-1">

                    {{-- DYNAMIC VARIANT OPTION SELECTORS --}}
                    @if(count($productAttributes) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-stone-100 pb-4">
                        @foreach($productAttributes as $attrName => $values)
                        <div class="space-y-2.5">
                            <label class="text-[9px] font-bold text-stone-400 uppercase tracking-widest block">Select {{ ucfirst($attrName) }}</label>
                            
                            {{-- Color Swatches --}}
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
                                    class="w-7 h-7 rounded-full border flex items-center justify-center transition-all duration-200 focus:outline-none"
                                    :class="selectedAttributes['{{ $attrName }}'] === '{{ strtolower($valData['value']) }}' ? 'border-stone-950 ring-1 ring-stone-950 ring-offset-2 scale-105' : 'border-stone-200 hover:scale-105'"
                                    title="{{ ucfirst($valData['value']) }}">
                                    <span class="w-5.5 h-5.5 rounded-full block" style="background-color: {{ $hex }}"></span>
                                </button>
                                @endforeach
                            </div>
                            
                            {{-- Size Buttons --}}
                            @else
                            <div class="flex items-center gap-2 flex-wrap">
                                @foreach($values as $valKey => $valData)
                                <button
                                    @click="selectedAttributes['{{ $attrName }}'] = '{{ strtolower($valData['value']) }}'"
                                    type="button"
                                    class="px-3 h-8.5 rounded border text-[10px] font-bold uppercase tracking-widest transition-all duration-250 focus:outline-none"
                                    :class="selectedAttributes['{{ $attrName }}'] === '{{ strtolower($valData['value']) }}' ? 'bg-stone-950 text-white border-transparent' : 'bg-white text-stone-600 border-stone-200 hover:border-stone-950'">
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
                    <form action="{{ route('store.cart.add', $product->id) }}" id="product-purchase-form" method="POST" class="flex flex-col sm:flex-row items-center gap-3 pt-1">
                        @csrf
                        <input type="hidden" name="quantity" :value="quantity">
                        <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">

                        {{-- Stepper input --}}
                        <div class="h-11 w-28 flex items-center border border-stone-200 rounded-[4px] overflow-hidden bg-white shrink-0 justify-between">
                            <button type="button" @click="if(quantity > 1) quantity--" class="w-10 h-full text-stone-400 hover:text-stone-950 flex items-center justify-center font-bold text-sm focus:outline-none">-</button>
                            <span class="text-xs font-bold text-stone-850" x-text="quantity"></span>
                            <button type="button" @click="if(quantity < maxQuantity) quantity++" class="w-10 h-full text-stone-400 hover:text-stone-950 flex items-center justify-center font-bold text-sm focus:outline-none">+</button>
                        </div>

                        <div class="flex-grow flex flex-col sm:flex-row items-center gap-3 w-full">
                            {{-- CTA: Add to cart --}}
                            <button type="submit" class="w-full sm:w-1/2 h-11 rounded-[4px] border border-stone-950 text-stone-950 hover:bg-stone-950 hover:text-white text-[10px] font-bold uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                                    <path d="M3 6h18" />
                                    <path d="M16 10a4 4 0 0 1-8 0" />
                                </svg>
                                <span>Add to Bag</span>
                            </button>

                            {{-- CTA: Buy Now --}}
                            <button type="submit" name="buy_now" value="1" class="w-full sm:w-1/2 h-11 rounded-[4px] bg-[#B88A44] hover:bg-[#A37837] text-white text-[10px] font-bold uppercase tracking-widest transition-colors duration-300 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                <span>Buy Now</span>
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                {{-- Delivery Check form --}}
                <div class="border border-stone-100 rounded-lg p-4 bg-stone-50/30 space-y-2.5" x-data="{ pincode: '', result: null, loading: false, check() { if(!this.pincode) return; this.loading=true; fetch(`/delivery-check?pincode=${this.pincode}`).then(r=>r.json()).then(d=>{this.result=d; this.loading=false;}).catch(e=>{this.result={success:false, message:'Service unavailable'}; this.loading=false;}) } }">
                    <label class="text-[9px] font-bold text-stone-400 uppercase tracking-widest block">Delivery Check</label>
                    <div class="flex gap-2 max-w-sm">
                        <input
                            type="text"
                            x-model="pincode"
                            placeholder="Enter Delivery Pincode"
                            class="h-10 px-3.5 rounded-[4px] bg-[#FAF9F6] border border-stone-200 text-xs text-stone-850 focus:outline-none w-full">
                        <button type="button" @click="check()" class="h-10 px-5 rounded-[4px] bg-stone-950 text-white text-[10px] font-bold uppercase tracking-widest hover:bg-[#B88A44] transition-colors shrink-0">
                            <span x-show="!loading">Check</span>
                            <span x-show="loading">...</span>
                        </button>
                    </div>
                    <p x-show="result" class="text-[10px] mt-1" :class="result?.success ? 'text-emerald-700' : 'text-red-600'" x-text="result?.message"></p>
                </div>

                {{-- Trust strip --}}
                <div class="trust-row grid grid-cols-3 gap-3 border-t border-stone-100 pt-5">
                    <div class="flex items-center gap-2 text-[9px] text-stone-500 font-bold uppercase tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#B88A44" stroke-width="2.5">
                            <rect width="20" height="14" x="2" y="5" rx="2" />
                            <line x1="2" x2="22" y1="10" y2="10" />
                        </svg>
                        <span>COD Available</span>
                    </div>
                    <div class="flex items-center gap-2 text-[9px] text-stone-500 font-bold uppercase tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#B88A44" stroke-width="2.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        <span>Genuine Product</span>
                    </div>
                    <div class="flex items-center gap-2 text-[9px] text-stone-500 font-bold uppercase tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#B88A44" stroke-width="2.5">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        </svg>
                        <span>7 Day Returns</span>
                    </div>
                </div>

            </div>

        </div>

        {{-- Description tabs and accordions --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 pt-6 border-t border-stone-100">

            {{-- Details accordions --}}
            <div class="lg:col-span-6 space-y-1">
                <!-- Accordion: Description -->
                <div class="border-b border-stone-100 py-3">
                    <button
                        @click="activeAccordion = activeAccordion === 'desc' ? '' : 'desc'"
                        class="w-full flex items-center justify-between py-2 text-left font-serif font-bold text-base text-stone-900 focus:outline-none">
                        <span>Product Story & Description</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200 text-stone-500" :class="activeAccordion === 'desc' ? 'rotate-180' : ''">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div x-show="activeAccordion === 'desc'" x-collapse class="pb-4 pt-1.5 text-xs text-stone-500 leading-relaxed font-medium whitespace-pre-line">
                        {{ $product->description ?? 'No specific narrative details provided.' }}
                    </div>
                </div>

                <!-- Accordion: Specs -->
                <div class="border-b border-stone-100 py-3">
                    <button
                        @click="activeAccordion = activeAccordion === 'specs' ? '' : 'specs'"
                        class="w-full flex items-center justify-between py-2 text-left font-serif font-bold text-base text-stone-900 focus:outline-none">
                        <span>Specifications & Material</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200 text-stone-500" :class="activeAccordion === 'specs' ? 'rotate-180' : ''">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div x-show="activeAccordion === 'specs'" x-collapse class="pb-4 pt-1.5 text-xs">
                        <table class="w-full text-xs specs-table">
                            <tbody>
                                @foreach($specs as $key => $val)
                                <tr class="border-b border-stone-100/60">
                                    <td class="py-2.5 font-bold text-stone-700 w-1/3">{{ $key }}</td>
                                    <td class="py-2.5 text-stone-500">{{ $val }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Accordion: Shipping -->
                <div class="border-b border-stone-100 py-3">
                    <button
                        @click="activeAccordion = activeAccordion === 'shipping' ? '' : 'shipping'"
                        class="w-full flex items-center justify-between py-2 text-left font-serif font-bold text-base text-stone-900 focus:outline-none">
                        <span>Shipping & Returns</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="transition-transform duration-200 text-stone-500" :class="activeAccordion === 'shipping' ? 'rotate-180' : ''">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                    <div x-show="activeAccordion === 'shipping'" x-collapse class="pb-4 pt-1.5 text-xs text-stone-500 leading-relaxed font-medium">
                        Standard delivery is free on all orders above ₹999. Orders are shipped in plastic-free recycled boxes and arrive within 2-4 business days. Easy returns are available within 7 days of delivery.
                    </div>
                </div>
            </div>

            {{-- Reviews segment --}}
            <div class="lg:col-span-6 space-y-6">
                <h3 class="font-serif text-xl font-bold text-stone-900">Customer Reviews</h3>

                {{-- Reviews List --}}
                <div class="space-y-3.5">
                    @forelse($product->reviews as $rev)
                    <div class="rounded-xl border border-stone-100 p-4.5 space-y-2 bg-stone-50/50">
                        <div class="flex items-center justify-between text-[9px] text-stone-400 font-bold uppercase tracking-widest">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-stone-200 text-stone-700 flex items-center justify-center font-bold text-[10px]">{{ strtoupper(substr($rev->user->name, 0, 1)) }}</div>
                                <span>{{ $rev->user->name }}</span>
                            </div>
                            <span>{{ $rev->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex text-[#B88A44] gap-0.5">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-3 h-3 {{ $i <= $rev->rating ? 'fill-current' : 'text-stone-200' }}" fill="{{ $i <= $rev->rating ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.17 0l-3.971 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.883c-.783-.57-.372-1.81.587-1.81h4.908a1 1 0 00.95-.69l1.518-4.674z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="text-xs text-stone-600 leading-relaxed font-medium">{{ $rev->comment }}</p>
                    </div>
                    @empty
                    <p class="text-xs text-stone-400 italic font-medium">No reviews for this product yet. Share your feedback below.</p>
                    @endforelse
                </div>

                {{-- Write review --}}
                <div class="border border-stone-100 rounded-xl p-5 bg-stone-50/50 space-y-4">
                    <h4 class="font-serif text-sm font-bold text-stone-900">Write a review</h4>
                    @auth
                    <form action="{{ route('store.product.review', $product->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="rating" class="block text-[9px] font-bold text-stone-400 uppercase tracking-widest mb-1.5">Rating</label>
                            <select id="rating" name="rating" class="w-full h-9 bg-white border border-stone-200 rounded-[4px] px-3 text-xs text-stone-700">
                                <option value="5">5 Stars (Excellent)</option>
                                <option value="4">4 Stars (Good)</option>
                                <option value="3">3 Stars (Average)</option>
                                <option value="2">2 Stars (Poor)</option>
                                <option value="1">1 Star (Terrible)</option>
                            </select>
                        </div>
                        <div>
                            <label for="comment" class="block text-[9px] font-bold text-stone-400 uppercase tracking-widest mb-1.5">Comment</label>
                            <textarea id="comment" name="comment" rows="4" placeholder="Share your experience with this piece..." class="w-full bg-white border border-stone-200 rounded-[4px] p-3 text-xs text-stone-700 resize-none focus:outline-none"></textarea>
                        </div>
                        <button type="submit" class="w-full h-9.5 rounded-full bg-stone-950 hover:bg-[#B88A44] text-white text-[10px] font-bold uppercase tracking-widest transition duration-200">
                            Submit Review
                        </button>
                    </form>
                    @else
                    <p class="text-xs text-stone-400 font-medium">
                        Please <a href="{{ route('login') }}" class="text-[#B88A44] hover:underline">login</a> to write a review.
                    </p>
                    @endauth
                </div>

            </div>

        </div>

        {{-- Recommendations (Related Products) --}}
        @if($relatedProducts->count() > 0)
        <div class="space-y-8 border-t border-stone-100 pt-10">
            <div class="text-center space-y-1">
                <span class="text-[9px] font-bold uppercase tracking-widest text-[#B88A44] block">You May Also Like</span>
                <h3 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900">Frequently Bought Together</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $prod)
                <x-shop.product-card :product="$prod" />
                @endforeach
            </div>
        </div>
        @endif

    </div>

</div>

{{-- Fixed bottom CTA bar on Mobile (<= 620px viewport) --}}
@if($product->quantity > 0)
<div class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-stone-100 px-4 py-3.5 shadow-[0_-8px_30px_rgba(0,0,0,0.08)] sm:hidden flex items-center justify-between gap-3">
    <div class="flex flex-col">
        <span class="text-[8px] uppercase tracking-widest text-stone-400 font-bold">Price</span>
        <span class="font-serif text-base font-bold text-[#B88A44]">
            @if($product->sale_price)
            ₹{{ number_format($product->sale_price, 2) }}
            @else
            ₹{{ number_format($product->price, 2) }}
            @endif
        </span>
    </div>
    <div class="flex gap-2.5">
        <button type="button" @click="document.getElementById('product-purchase-form').submit()" class="h-10 px-4 rounded-[4px] border border-stone-950 text-stone-950 text-[9px] font-bold uppercase tracking-widest transition-all">Add to Bag</button>
        <button type="button" @click="const f = document.getElementById('product-purchase-form'); const inp = document.createElement('input'); inp.type='hidden'; inp.name='buy_now'; inp.value='1'; f.appendChild(inp); f.submit();" class="h-10 px-4 rounded-[4px] bg-[#B88A44] hover:bg-[#A37837] text-white text-[9px] font-bold uppercase tracking-widest transition-colors">Buy Now</button>
    </div>
</div>
@endif

@endsection