@php
    $isEdit    = isset($coupon);
    $formId    = 'coupon-form';
    $statusOld = old('status', $isEdit ? ($coupon->status ? 1 : 0) : 1);
@endphp

<form
    id="{{ $formId }}"
    action="{{ $isEdit ? route('admin.coupons.update', $coupon->id) : route('admin.coupons.store') }}"
    method="POST"
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
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Coupon Details</h2>
                    <p class="mt-1 text-sm text-slate-400">Coupon code, type and discount configuration.</p>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Code --}}
                    <div>
                        <label for="code" class="block text-sm font-medium text-slate-200 mb-1.5">Coupon Code <span class="text-red-400">*</span></label>
                        <input
                            type="text"
                            id="code"
                            name="code"
                            autocomplete="off"
                            value="{{ old('code', $isEdit ? $coupon->code : '') }}"
                            placeholder="e.g. SAVE20"
                            class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('code') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                        >
                        @error('code')
                            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Type --}}
                        <div>
                            <label for="type" class="block text-sm font-medium text-slate-200 mb-1.5">Discount Type <span class="text-red-400">*</span></label>
                            <select
                                id="type"
                                name="type"
                                class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 border-slate-700"
                            >
                                <option value="fixed" @selected(old('type', $isEdit ? $coupon->type : '') == 'fixed')>Fixed Amount (₹)</option>
                                <option value="percent" @selected(old('type', $isEdit ? $coupon->type : '') == 'percent')>Percentage (%)</option>
                            </select>
                            @error('type')
                                <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Value --}}
                        <div>
                            <label for="value" class="block text-sm font-medium text-slate-200 mb-1.5">Discount Value <span class="text-red-400">*</span></label>
                            <input
                                type="number"
                                step="0.01"
                                id="value"
                                name="value"
                                value="{{ old('value', $isEdit ? $coupon->value : '') }}"
                                placeholder="0.00"
                                class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('value') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                            >
                            @error('value')
                                <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Minimum Cart Value --}}
                        <div>
                            <label for="cart_value" class="block text-sm font-medium text-slate-200 mb-1.5">Minimum Cart Value (₹)</label>
                            <input
                                type="number"
                                step="0.01"
                                id="cart_value"
                                name="cart_value"
                                value="{{ old('cart_value', $isEdit ? $coupon->cart_value : '0.00') }}"
                                placeholder="0.00"
                                class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('cart_value') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                            >
                            @error('cart_value')
                                <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Expiry Date --}}
                        <div>
                            <label for="expiry_date" class="block text-sm font-medium text-slate-200 mb-1.5">Expiry Date <span class="text-red-400">*</span></label>
                            <input
                                type="date"
                                id="expiry_date"
                                name="expiry_date"
                                value="{{ old('expiry_date', $isEdit && $coupon->expiry_date ? $coupon->expiry_date->format('Y-m-d') : '') }}"
                                class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('expiry_date') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                            >
                            @error('expiry_date')
                                <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- RIGHT COLUMN (1/3) --}}
        <div class="space-y-6">
            {{-- Status --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Status</h2>
                    <p class="mt-1 text-sm text-slate-400">Control coupon availability.</p>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="status_toggle" class="text-sm font-medium text-slate-200">
                                <span id="status-label-text">{{ $statusOld == 1 ? 'Active' : 'Inactive' }}</span>
                            </label>
                            <p class="text-xs text-slate-400">{{ $statusOld == 1 ? 'Available for checkouts' : 'Disabled' }}</p>
                        </div>
                        <button
                            type="button"
                            id="status_toggle"
                            role="switch"
                            aria-checked="{{ $statusOld == 1 ? 'true' : 'false' }}"
                            class="relative inline-flex h-7 w-12 shrink-0 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 {{ $statusOld == 1 ? 'bg-amber-500' : 'bg-slate-700' }}"
                        >
                            <span id="status_toggle_knob" class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200 {{ $statusOld == 1 ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                        <input type="hidden" id="status" name="status" value="{{ $statusOld }}">
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- Actions --}}
    <div class="fixed inset-x-0 bottom-0 z-20 border-t border-slate-800 bg-slate-950/90 backdrop-blur lg:static lg:mt-8 lg:border-0 lg:bg-transparent lg:backdrop-blur-none">
        <div class="mx-auto flex max-w-screen-2xl items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:px-0">
            <a href="{{ route('admin.coupons.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-700 bg-slate-900 px-5 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-amber-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                {{ $isEdit ? 'Update Coupon' : 'Save Coupon' }}
            </button>
        </div>
    </div>
</form>

<script>
    (function () {
        'use strict';
        const toggleBtn = document.getElementById('status_toggle');
        const toggleKnob = document.getElementById('status_toggle_knob');
        const statusInput = document.getElementById('status');
        const statusLabel = document.getElementById('status-label-text');
        const statusDesc = statusLabel.closest('div').querySelector('p');

        toggleBtn.addEventListener('click', () => {
            const current = Number(statusInput.value) === 1;
            const next = current ? 0 : 1;
            statusInput.value = next;
            toggleBtn.setAttribute('aria-checked', next === 1 ? 'true' : 'false');
            if (next === 1) {
                toggleBtn.classList.replace('bg-slate-700', 'bg-amber-500');
                toggleKnob.classList.replace('translate-x-1', 'translate-x-6');
                statusLabel.textContent = 'Active';
                statusDesc.textContent = 'Available for checkouts';
            } else {
                toggleBtn.classList.replace('bg-amber-500', 'bg-slate-700');
                toggleKnob.classList.replace('translate-x-6', 'translate-x-1');
                statusLabel.textContent = 'Inactive';
                statusDesc.textContent = 'Disabled';
            }
        });
    })();
</script>
