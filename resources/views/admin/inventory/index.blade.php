@extends('admin.layouts.app')

@section('title', 'Premium Stock Control')

@section('content')
<div class="space-y-6" x-data="{
    search: '{{ request('search', '') }}',
    stockLevel: '{{ request('stock_level', '') }}',
    updatingId: null,
    updatingType: null,
    toastMessage: '',
    toastType: 'success',
    showToast: false,

    triggerToast(message, type = 'success') {
        this.toastMessage = message;
        this.toastType = type;
        this.showToast = true;
        setTimeout(() => { this.showToast = false; }, 3000);
    },

    updateStock(id, type, elementId) {
        const input = document.getElementById(elementId);
        const quantity = parseInt(input.value);
        if (isNaN(quantity) || quantity < 0) {
            this.triggerToast('Please enter a valid stock number', 'error');
            return;
        }

        this.updatingId = id;
        this.updatingType = type;

        fetch('{{ route('admin.inventory.update') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id, type, quantity })
        })
        .then(res => {
            if (!res.ok) throw new Error();
            this.triggerToast('Stock quantity updated successfully!');
            // Update the quantity display badge dynamically
            const badge = document.getElementById('badge-' + type + '-' + id);
            if (badge) {
                badge.innerText = quantity;
                // Update status badge
                const statusBadge = document.getElementById('status-' + type + '-' + id);
                if (statusBadge) {
                    if (quantity === 0) {
                        statusBadge.className = 'inline-flex items-center rounded-full bg-rose-500/10 px-2.5 py-0.5 text-xs font-medium text-rose-400 border border-rose-500/20';
                        statusBadge.innerText = 'Out of Stock';
                    } else if (quantity <= 5) {
                        statusBadge.className = 'inline-flex items-center rounded-full bg-amber-500/10 px-2.5 py-0.5 text-xs font-medium text-amber-400 border border-amber-500/20';
                        statusBadge.innerText = 'Low Stock';
                    } else {
                        statusBadge.className = 'inline-flex items-center rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-medium text-emerald-400 border border-emerald-500/20';
                        statusBadge.innerText = 'In Stock';
                    }
                }
            }
        })
        .catch(() => {
            this.triggerToast('Failed to update stock. Try again.', 'error');
        })
        .finally(() => {
            this.updatingId = null;
            this.updatingType = null;
        });
    },

    adjustQty(elementId, amount) {
        const input = document.getElementById(elementId);
        let val = parseInt(input.value) || 0;
        val = Math.max(0, val + amount);
        input.value = val;
    }
}">
    {{-- Toast Notification --}}
    <div
        x-show="showToast"
        x-transition:enter="transition ease-out duration-350"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-2xl border px-5 py-4 shadow-2xl backdrop-blur-md"
        :class="toastType === 'success' ? 'bg-emerald-950/90 border-emerald-800 text-emerald-300' : 'bg-rose-950/90 border-rose-800 text-rose-300'"
        style="display: none;">
        <span x-text="toastType === 'success' ? '✓' : '✗'" class="font-bold text-lg"></span>
        <span class="text-xs font-semibold" x-text="toastMessage"></span>
    </div>

    {{-- Header --}}
    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <nav class="mb-3 flex items-center gap-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 transition hover:text-[#B88A44]">Dashboard</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-500">Catalog</span>
                <span class="text-gray-400">/</span>
                <span class="font-medium text-[#B88A44]">Inventory Control</span>
            </nav>
            <h1 class="text-3xl font-bold tracking-tight text-white">Stock Control Console</h1>
            <p class="mt-2 text-sm text-slate-400">Perform inline stock quantity increments and manage threshold notifications.</p>
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="rounded-3xl border border-slate-800 bg-[#111827] p-6 shadow-xl">
        <form action="{{ route('admin.inventory.index') }}" method="GET">
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
                <div class="lg:col-span-6">
                    <label class="mb-2 block text-sm font-semibold text-slate-300 font-sans">Search Products</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, SKU..." class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 pl-12 pr-4 text-white focus:border-[#B88A44] focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <div class="lg:col-span-6">
                    <label class="mb-2 block text-sm font-semibold text-slate-300 font-sans">Stock Filters</label>
                    <select name="stock_level" class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        <option value="">All Stock Levels</option>
                        <option value="in_stock" {{ request('stock_level') === 'in_stock' ? 'selected' : '' }}>In Stock (> 5)</option>
                        <option value="low" {{ request('stock_level') === 'low' ? 'selected' : '' }}>Low Stock Alerts (≤ 5)</option>
                        <option value="out" {{ request('stock_level') === 'out' ? 'selected' : '' }}>Out of Stock (= 0)</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3 border-t border-slate-800 pt-4">
                <a href="{{ route('admin.inventory.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 px-5 text-sm font-semibold text-slate-300 hover:text-white transition">Reset</a>
                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-[#B88A44] px-6 text-sm font-semibold text-white transition hover:bg-[#a67936]">Apply Filters</button>
            </div>
        </form>
    </div>

    {{-- Inventory Grid --}}
    <div class="overflow-hidden rounded-3xl border border-slate-800 bg-[#111827] shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-b border-slate-800 bg-slate-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Product details</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-400">SKU Code</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Status Alert</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-400">Stock Count</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-400">Adjust Stock levels</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($products as $product)
                        {{-- Row for product --}}
                        <tr class="hover:bg-slate-900/10 transition border-b border-slate-800/50">
                            <td class="px-6 py-5 text-sm font-semibold text-white">
                                <div class="flex flex-col">
                                    <span>{{ $product->name }}</span>
                                    <span class="text-3xs text-slate-500 font-normal mt-0.5">Category: {{ $product->category->name ?? '-' }} | Brand: {{ $product->brand->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-xs font-mono text-slate-400">
                                {{ $product->sku }}
                            </td>
                            <td class="px-6 py-5">
                                @if($product->variants->isEmpty())
                                    <span id="status-product-{{ $product->id }}" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium 
                                        @if($product->quantity === 0) bg-rose-500/10 text-rose-400 border border-rose-500/20
                                        @elseif($product->quantity <= 5) bg-amber-500/10 text-amber-400 border border-amber-500/20
                                        @else bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 @endif">
                                        @if($product->quantity === 0) Out of Stock @elseif($product->quantity <= 5) Low Stock @else In Stock @endif
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-blue-500/10 px-2 py-0.5 text-3xs font-medium text-blue-400 border border-blue-500/20">
                                        {{ $product->variants->count() }} Variants List
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center text-sm font-semibold text-white">
                                @if($product->variants->isEmpty())
                                    <span id="badge-product-{{ $product->id }}">{{ $product->quantity }}</span>
                                @else
                                    <span class="text-slate-600 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                @if($product->variants->isEmpty())
                                    <div class="flex items-center justify-center gap-2.5">
                                        {{-- Custom Stepper --}}
                                        <div class="flex h-10 items-center rounded-xl border border-slate-700 bg-slate-900 p-1">
                                            <button type="button" @click="adjustQty('qty-product-{{ $product->id }}', -1)" class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition focus:outline-none font-bold text-sm">-</button>
                                            <input type="number" id="qty-product-{{ $product->id }}" value="{{ $product->quantity }}" min="0" class="w-12 bg-transparent text-center text-xs font-semibold text-white focus:outline-none border-0 p-0 select-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            <button type="button" @click="adjustQty('qty-product-{{ $product->id }}', 1)" class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition focus:outline-none font-bold text-sm">+</button>
                                        </div>
                                        
                                        <button
                                            type="button"
                                            @click="updateStock({{ $product->id }}, 'product', 'qty-product-{{ $product->id }}')"
                                            :disabled="updatingId === {{ $product->id }} && updatingType === 'product'"
                                            class="inline-flex items-center gap-1.5 h-10 rounded-xl bg-[#B88A44] hover:bg-[#a67936] text-white px-4 text-xs font-semibold shadow-md shadow-[#B88A44]/10 transition disabled:opacity-50">
                                            <span x-show="updatingId === {{ $product->id }} && updatingType === 'product'" class="animate-spin h-3.5 w-3.5 border-2 border-white border-t-transparent rounded-full"></span>
                                            <span x-show="!(updatingId === {{ $product->id }} && updatingType === 'product')">Update</span>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-500 block text-center italic">Controlled via attribute configurations</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Variants lists --}}
                        @if($product->variants->isNotEmpty())
                            @foreach($product->variants as $variant)
                                <tr class="bg-slate-900/15 border-b border-slate-800/40 hover:bg-slate-900/35 transition">
                                    <td class="pl-12 pr-6 py-4 text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="text-slate-600 font-normal">└</span>
                                            <span class="font-semibold text-slate-350 bg-slate-900/60 border border-slate-800 px-2.5 py-1 rounded-lg">
                                                @foreach($variant->attributeValues as $val)
                                                    {{ $val->attribute->name }}: <span class="text-white">{{ $val->value }}</span>@if(!$loop->last), @endif
                                                @endforeach
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                        {{ $variant->sku }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span id="status-variant-{{ $variant->id }}" class="inline-flex items-center rounded-full px-2 py-0.5 text-3xs font-medium 
                                            @if($variant->quantity === 0) bg-rose-500/10 text-rose-400 border border-rose-500/20
                                            @elseif($variant->quantity <= 5) bg-amber-500/10 text-amber-400 border border-amber-500/20
                                            @else bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 @endif">
                                            @if($variant->quantity === 0) Out of Stock @elseif($variant->quantity <= 5) Low Stock @else In Stock @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-xs font-semibold text-slate-300">
                                        <span id="badge-variant-{{ $variant->id }}">{{ $variant->quantity }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- Variant Stepper --}}
                                            <div class="flex h-9 items-center rounded-lg border border-slate-700 bg-slate-900 p-0.5">
                                                <button type="button" @click="adjustQty('qty-variant-{{ $variant->id }}', -1)" class="w-6 h-6 rounded text-slate-400 hover:bg-slate-800 hover:text-white transition focus:outline-none font-bold text-xs">-</button>
                                                <input type="number" id="qty-variant-{{ $variant->id }}" value="{{ $variant->quantity }}" min="0" class="w-10 bg-transparent text-center text-xs font-semibold text-white focus:outline-none border-0 p-0 select-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                <button type="button" @click="adjustQty('qty-variant-{{ $variant->id }}', 1)" class="w-6 h-6 rounded text-slate-400 hover:bg-slate-800 hover:text-white transition focus:outline-none font-bold text-xs">+</button>
                                            </div>

                                            <button
                                                type="button"
                                                @click="updateStock({{ $variant->id }}, 'variant', 'qty-variant-{{ $variant->id }}')"
                                                :disabled="updatingId === {{ $variant->id }} && updatingType === 'variant'"
                                                class="inline-flex items-center gap-1 h-9 rounded-lg bg-slate-800 border border-slate-700 hover:border-[#B88A44] text-slate-300 hover:text-white px-3 text-2xs font-semibold transition disabled:opacity-50">
                                                <span x-show="updatingId === {{ $variant->id }} && updatingType === 'variant'" class="animate-spin h-3 w-3 border-2 border-white border-t-transparent rounded-full"></span>
                                                <span x-show="!(updatingId === {{ $variant->id }} && updatingType === 'variant')">Save</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">No stock products configured in store registry.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->hasPages())
            <div class="border-t border-slate-800 bg-slate-900 px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
