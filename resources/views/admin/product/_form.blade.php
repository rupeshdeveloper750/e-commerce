@php
    $isEdit        = isset($product);
    $formId        = 'product-form';
    $statusOld     = old('status', $isEdit ? ($product->status ? 1 : 0) : 1);
    $featuredOld   = old('is_featured', $isEdit ? ($product->is_featured ? 1 : 0) : 0);
@endphp

<form
    id="{{ $formId }}"
    action="{{ $isEdit ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
    method="POST"
    enctype="multipart/form-data"
    class="pb-28 lg:pb-8"
    novalidate
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        {{-- LEFT COLUMN (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Info --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Basic Information</h2>
                    <p class="mt-1 text-sm text-slate-400">Name, slug, category and brand assignment.</p>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-200 mb-1.5">Product Name <span class="text-red-400">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $isEdit ? $product->name : '') }}" placeholder="e.g. Air Max Shoes" class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror">
                        @error('name') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="slug" class="block text-sm font-medium text-slate-200 mb-1.5">Slug</label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $isEdit ? $product->slug : '') }}" placeholder="e.g. air-max-shoes" class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('slug') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror">
                            @error('slug') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="sku" class="block text-sm font-medium text-slate-200 mb-1.5">SKU (Stock Keeping Unit)</label>
                            <input type="text" id="sku" name="sku" value="{{ old('sku', $isEdit ? $product->sku : '') }}" placeholder="e.g. SM-NIKE-01" class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('sku') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror">
                            @error('sku') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-slate-200 mb-1.5">Category</label>
                            <select id="category_id" name="category_id" class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 border-slate-700">
                                <option value="" class="bg-slate-900">— Select Category —</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" class="bg-slate-900" @selected(old('category_id', $isEdit ? $product->category_id : '') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="brand_id" class="block text-sm font-medium text-slate-200 mb-1.5">Brand</label>
                            <select id="brand_id" name="brand_id" class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 border-slate-700">
                                <option value="" class="bg-slate-900">— Select Brand —</option>
                                @foreach ($brands as $br)
                                    <option value="{{ $br->id }}" class="bg-slate-900" @selected(old('brand_id', $isEdit ? $product->brand_id : '') == $br->id)>{{ $br->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- Price & Stock --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Pricing & Inventory</h2>
                    <p class="mt-1 text-sm text-slate-400">Regular price, discount pricing and stock quantities.</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-slate-200 mb-1.5">Regular Price (₹) <span class="text-red-400">*</span></label>
                            <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $isEdit ? $product->price : '') }}" placeholder="0.00" class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('price') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror">
                            @error('price') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="sale_price" class="block text-sm font-medium text-slate-200 mb-1.5">Sale Price (₹)</label>
                            <input type="number" step="0.01" id="sale_price" name="sale_price" value="{{ old('sale_price', $isEdit ? $product->sale_price : '') }}" placeholder="0.00" class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('sale_price') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror">
                            @error('sale_price') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-slate-200 mb-1.5">Stock Quantity <span class="text-red-400">*</span></label>
                            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $isEdit ? $product->quantity : '0') }}" placeholder="0" class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('quantity') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror">
                            @error('quantity') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- Description --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Description</h2>
                    <p class="mt-1 text-sm text-slate-400">Summaries and detail specifications.</p>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="short_description" class="block text-sm font-medium text-slate-200 mb-1.5">Short Description</label>
                        <textarea id="short_description" name="short_description" rows="3" placeholder="Brief summary of product features..." class="w-full rounded-xl border bg-slate-950/60 px-4 py-3 text-sm text-white placeholder-slate-500 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 border-slate-700">{{ old('short_description', $isEdit ? $product->short_description : '') }}</textarea>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-200 mb-1.5">Full Description</label>
                        <textarea id="description" name="description" rows="7" placeholder="Detailed product specifications, materials and info..." class="w-full rounded-xl border bg-slate-950/60 px-4 py-3 text-sm text-white placeholder-slate-500 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 border-slate-700">{{ old('description', $isEdit ? $product->description : '') }}</textarea>
                    </div>
                </div>
            </section>

            {{-- Product Variants Card --}}
            <section
                x-data="{
                    variants: @js($isEdit ? $product->variants->map(function($v) {
                        return [
                            'sku' => $v->sku,
                            'price' => $v->price,
                            'sale_price' => $v->sale_price,
                            'quantity' => $v->quantity,
                            'attribute_values' => $v->attributeValues->pluck('id')->toArray()
                        ];
                    }) : []),
                    addVariant() {
                        this.variants.push({
                            sku: '',
                            price: '',
                            sale_price: '',
                            quantity: 0,
                            attribute_values: []
                        });
                    },
                    removeVariant(index) {
                        this.variants.splice(index, 1);
                    }
                }"
                class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20"
            >
                <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-white">Product Variants</h2>
                        <p class="mt-1 text-sm text-slate-400">Generate variants for size, color, configuration, etc.</p>
                    </div>
                    <button
                        type="button"
                        @click="addVariant()"
                        class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-xs font-semibold text-amber-500 hover:bg-slate-700 transition"
                    >
                        + Add Variant
                    </button>
                </div>
                
                <div class="p-6 space-y-4">
                    <template x-for="(variant, index) in variants" :key="index">
                        <div class="p-4 rounded-xl border border-slate-800 bg-slate-950/40 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-white">Variant #<span x-text="index + 1"></span></span>
                                <button
                                    type="button"
                                    @click="removeVariant(index)"
                                    class="text-xs font-semibold text-red-400 hover:text-red-300 transition"
                                >
                                    Remove
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">SKU</label>
                                    <input
                                        type="text"
                                        x-model="variant.sku"
                                        :name="`variants[${index}][sku]`"
                                        placeholder="e.g. SKU-RED-SM"
                                        class="w-full rounded-lg border border-slate-800 bg-slate-900 px-3 py-1.5 text-xs text-white placeholder-slate-650 focus:outline-none focus:ring-1 focus:ring-amber-500"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Price (₹)</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        x-model="variant.price"
                                        :name="`variants[${index}][price]`"
                                        required
                                        placeholder="0.00"
                                        class="w-full rounded-lg border border-slate-800 bg-slate-900 px-3 py-1.5 text-xs text-white placeholder-slate-650 focus:outline-none focus:ring-1 focus:ring-amber-500"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Sale Price (₹)</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        x-model="variant.sale_price"
                                        :name="`variants[${index}][sale_price]`"
                                        placeholder="0.00"
                                        class="w-full rounded-lg border border-slate-800 bg-slate-900 px-3 py-1.5 text-xs text-white placeholder-slate-650 focus:outline-none focus:ring-1 focus:ring-amber-500"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-400 mb-1">Stock Quantity</label>
                                    <input
                                        type="number"
                                        x-model="variant.quantity"
                                        :name="`variants[${index}][quantity]`"
                                        required
                                        placeholder="0"
                                        class="w-full rounded-lg border border-slate-800 bg-slate-900 px-3 py-1.5 text-xs text-white placeholder-slate-650 focus:outline-none focus:ring-1 focus:ring-amber-500"
                                    >
                                </div>
                            </div>

                            {{-- Option Attributes selectors --}}
                            <div class="border-t border-slate-850 pt-3">
                                <label class="block text-xs font-medium text-slate-400 mb-2">Select Attribute Options</label>
                                <div class="flex flex-wrap gap-4">
                                    @foreach ($attributes as $attribute)
                                        <div class="space-y-1">
                                            <span class="block text-2xs uppercase tracking-wider text-slate-500 font-semibold">{{ $attribute->name }}</span>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($attribute->values as $val)
                                                    <label class="inline-flex items-center gap-1.5 rounded-lg border border-slate-800 bg-slate-900/50 px-2.5 py-1 hover:border-slate-700 transition cursor-pointer select-none text-2xs text-slate-300">
                                                        <input
                                                            type="checkbox"
                                                            :value="{{ $val->id }}"
                                                            :checked="variant.attribute_values.includes({{ $val->id }})"
                                                            @click="
                                                                const idx = variant.attribute_values.indexOf({{ $val->id }});
                                                                if (idx > -1) {
                                                                    variant.attribute_values.splice(idx, 1);
                                                                } else {
                                                                    variant.attribute_values.push({{ $val->id }});
                                                                }
                                                            "
                                                            :name="`variants[${index}][attribute_values][]`"
                                                            class="h-3 w-3 rounded border-slate-700 bg-slate-950 text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-900"
                                                        >
                                                        <span>{{ $val->value }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="variants.length === 0" class="py-8 text-center text-sm text-slate-500 bg-slate-950/20 rounded-xl border border-dashed border-slate-850">
                        No variants added yet. Click "+ Add Variant" to generate customize models.
                    </div>
                </div>
            </section>
        </div>

        {{-- RIGHT COLUMN (1/3) --}}
        <div class="space-y-6">
            {{-- Primary Image --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Featured Image</h2>
                    <p class="mt-1 text-sm text-slate-400">Main thumbnail for product listing.</p>
                </div>
                <div class="p-6">
                    <div id="image-dropzone" class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-700 bg-slate-950/40 px-4 py-8 text-center transition hover:border-amber-500 hover:bg-amber-500/5 cursor-pointer">
                        <div id="image-preview-wrapper" class="hidden w-full">
                            <img id="image-preview" src="" alt="Featured image preview" class="mx-auto max-h-40 rounded-lg object-cover shadow-sm">
                            <p id="image-filename" class="mt-3 text-xs font-medium text-slate-300 truncate"></p>
                            <button type="button" id="image-remove-btn" class="mt-3 inline-flex items-center gap-1 rounded-lg border border-slate-700 bg-slate-900 px-3 py-1.5 text-xs font-semibold text-red-400 transition hover:bg-red-500/10">Remove</button>
                        </div>
                        <div id="image-empty-state" class="flex flex-col items-center gap-2">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-500/10 text-amber-500">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <p class="text-xs font-semibold text-slate-200">Upload Main Image</p>
                        </div>
                        <input type="file" id="featured_image" name="featured_image" accept="image/*" class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
                    </div>
                    @error('featured_image') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror

                    @if ($isEdit && $product->featuredImage)
                        <div id="existing-image-wrapper" class="mt-4">
                            <p class="text-xs font-medium text-slate-400 mb-2">Current Featured Image</p>
                            <img src="{{ asset('storage/' . $product->featuredImage->image_path) }}" class="h-20 w-20 rounded-lg object-cover border border-slate-700">
                        </div>
                    @endif
                </div>
            </section>

            {{-- Gallery Images --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Gallery Images</h2>
                    <p class="mt-1 text-sm text-slate-400">Add extra showcase photos.</p>
                </div>
                <div class="p-6">
                    <input type="file" id="gallery_images" name="gallery_images[]" multiple accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-500/10 file:text-amber-500 hover:file:bg-amber-500/20">
                    @error('gallery_images') <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p> @enderror

                    @if ($isEdit && $product->images()->where('is_featured', false)->count() > 0)
                        <div class="mt-4 grid grid-cols-4 gap-2">
                            @foreach ($product->images()->where('is_featured', false)->get() as $gal)
                                <img src="{{ asset('storage/' . $gal->image_path) }}" class="h-12 w-12 rounded object-cover border border-slate-800">
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            {{-- Featured toggle --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-semibold text-white">Featured Product</span>
                        <span class="text-xs text-slate-400">Show on homepage collections.</span>
                    </div>
                    <button type="button" id="featured_toggle" role="switch" class="relative inline-flex h-7 w-12 shrink-0 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 {{ $featuredOld == 1 ? 'bg-amber-500' : 'bg-slate-700' }}">
                        <span id="featured_toggle_knob" class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform duration-200 {{ $featuredOld == 1 ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                    <input type="hidden" id="is_featured" name="is_featured" value="{{ $featuredOld }}">
                </div>
            </section>

            {{-- Status toggle --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-semibold text-white">Product Visibility</span>
                        <span class="text-xs text-slate-400">Control storefront publication.</span>
                    </div>
                    <button type="button" id="status_toggle" role="switch" class="relative inline-flex h-7 w-12 shrink-0 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 {{ $statusOld == 1 ? 'bg-amber-500' : 'bg-slate-700' }}">
                        <span id="status_toggle_knob" class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform duration-200 {{ $statusOld == 1 ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                    <input type="hidden" id="status" name="status" value="{{ $statusOld }}">
                </div>
            </section>

            {{-- SEO --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">SEO Settings</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-slate-200 mb-1.5">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $isEdit ? $product->meta_title : '') }}" placeholder="Meta search title" class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-slate-200 mb-1.5">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="3" placeholder="Meta search details description" class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500">{{ old('meta_description', $isEdit ? $product->meta_description : '') }}</textarea>
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- Action Bar --}}
    <div class="fixed inset-x-0 bottom-0 z-20 border-t border-slate-800 bg-slate-950/90 backdrop-blur lg:static lg:mt-8 lg:border-0 lg:bg-transparent lg:backdrop-blur-none">
        <div class="mx-auto flex max-w-screen-2xl items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:px-0">
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-700 bg-slate-900 px-5 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-amber-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                {{ $isEdit ? 'Update Product' : 'Save Product' }}
            </button>
        </div>
    </div>
</form>

<script>
    (function () {
        'use strict';
        // Slug generation
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        let slugManuallyEdited = slugInput.value.trim().length > 0;
        function slugify(text) {
            return text.toString().trim().toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
        slugInput.addEventListener('input', () => { slugManuallyEdited = slugInput.value.trim().length > 0; });
        nameInput.addEventListener('input', () => { if (!slugManuallyEdited) { slugInput.value = slugify(nameInput.value); } });

        // Primary Image upload zone preview
        const dropzone = document.getElementById('image-dropzone');
        const fileInput = document.getElementById('featured_image');
        const previewWrapper = document.getElementById('image-preview-wrapper');
        const previewImg = document.getElementById('image-preview');
        const filenameText = document.getElementById('image-filename');
        const emptyState = document.getElementById('image-empty-state');
        const removeBtn = document.getElementById('image-remove-btn');
        const existingImageWrap = document.getElementById('existing-image-wrapper');

        fileInput.addEventListener('change', () => {
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    filenameText.textContent = file.name;
                    previewWrapper.classList.remove('hidden');
                    emptyState.classList.add('hidden');
                    if (existingImageWrap) { existingImageWrap.classList.add('hidden'); }
                };
                reader.readAsDataURL(file);
            }
        });
        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            fileInput.value = '';
            previewImg.src = '';
            previewWrapper.classList.add('hidden');
            emptyState.classList.remove('hidden');
            if (existingImageWrap) { existingImageWrap.classList.remove('hidden'); }
        });

        // Status & Featured toggles
        const setupToggle = (btnId, knobId, inputId) => {
            const btn = document.getElementById(btnId);
            const knob = document.getElementById(knobId);
            const input = document.getElementById(inputId);
            btn.addEventListener('click', () => {
                const current = Number(input.value) === 1;
                const next = current ? 0 : 1;
                input.value = next;
                btn.setAttribute('aria-checked', next === 1 ? 'true' : 'false');
                if (next === 1) {
                    btn.classList.replace('bg-slate-700', 'bg-amber-500');
                    knob.classList.replace('translate-x-1', 'translate-x-6');
                } else {
                    btn.classList.replace('bg-amber-500', 'bg-slate-700');
                    knob.classList.replace('translate-x-6', 'translate-x-1');
                }
            });
        };
        setupToggle('featured_toggle', 'featured_toggle_knob', 'is_featured');
        setupToggle('status_toggle', 'status_toggle_knob', 'status');
    })();
</script>
