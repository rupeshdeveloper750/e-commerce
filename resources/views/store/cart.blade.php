@extends('layouts.store')

@section('title', 'Shopping Cart')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">

<style>
:root {
    --cream: #FAF9F6;
    --white: #ffffff;
    --gold: #B88A44;
    --gold-light: #Cda86e;
    --gold-dark: #A17F4F;
    --charcoal: #111827;
    --ink: #1F2937;
    --muted: #6B7280;
    --border: #F0EDE8;
    --shadow-sm: 0 4px 15px rgba(0,0,0,0.02);
    --shadow-md: 0 10px 30px rgba(184,138,68,0.06);
    --shadow-lg: 0 20px 40px rgba(0,0,0,0.04);
}

.cart-page { font-family: 'Inter', sans-serif; background: #FAF9F6; }
.cart-serif { font-family: 'Playfair Display', serif; }

/* Cards */
.cart-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 24px;
    box-shadow: var(--shadow-sm);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.cart-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-4px);
    border-color: rgba(184,138,68,0.2);
}

/* Gold Gradient Button */
.btn-gold {
    background: linear-gradient(135deg, #Cda86e 0%, #B88A44 50%, #A17F4F 100%);
    color: white;
    border: none;
    border-radius: 16px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 6px 20px rgba(184,138,68,0.25);
}
.btn-gold:hover {
    background: linear-gradient(135deg, #Ddb97f 0%, #Cda86e 50%, #B88A44 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(184,138,68,0.35);
}

/* Stepper */
.stepper-line { height: 2px; background: var(--border); position: relative; }
.stepper-line-fill { height: 100%; background: var(--gold); width: 25%; transition: width 0.6s ease; }
.step-circle {
    width: 44px; height: 44px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 13px;
    transition: all 0.3s ease;
    position: relative; z-index: 2;
}
.step-active { background: var(--gold); color: white; box-shadow: 0 0 0 4px rgba(201,151,63,0.18); }
.step-done { background: #2f9e5b; color: white; }
.step-upcoming { background: var(--white); color: var(--muted); border: 2px solid var(--border); }

/* Quantity stepper */
.qty-control {
    display: inline-flex;
    align-items: center;
    border: 1.5px solid var(--border);
    border-radius: 100px;
    overflow: hidden;
    background: var(--white);
}
.qty-btn {
    width: 36px; height: 36px;
    border: none; background: transparent;
    color: var(--muted); cursor: pointer; font-size: 16px; font-weight: 600;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
}
.qty-btn:hover { background: #faf9f6; color: var(--charcoal); }
.qty-num { min-width: 32px; text-align: center; font-size: 13px; font-weight: 700; color: var(--charcoal); }

/* Product Image */
.product-img-wrap {
    width: 110px; height: 110px; flex-shrink: 0;
    border-radius: 14px; overflow: hidden;
    border: 1px solid var(--border);
    background: #f5f4f0;
    position: relative;
}
.product-img-wrap img { width: 100%; height: 100%; object-fit: cover; }

/* Tags */
.tag-sale {
    position: absolute; top: 8px; left: 8px;
    background: var(--white); border: 1.5px solid var(--gold);
    color: var(--gold); border-radius: 100px;
    font-size: 8px; font-weight: 800; letter-spacing: 0.1em;
    padding: 2px 7px; text-transform: uppercase;
    box-shadow: 0 2px 8px rgba(201,151,63,0.18);
}
.tag-stock {
    display: inline-flex; align-items: center; gap: 5px;
    background: #edf7f1; border: 1px solid rgba(47,158,91,0.25);
    color: #2f9e5b; border-radius: 100px;
    font-size: 9px; font-weight: 700; letter-spacing: 0.1em;
    padding: 3px 10px; text-transform: uppercase;
}
.tag-stock::before { content: ''; display: block; width: 5px; height: 5px; background: #2f9e5b; border-radius: 50%; }
.tag-variant {
    display: inline-flex; align-items: center; gap: 4px;
    background: #f5f4f0; border: 1px solid var(--border);
    color: var(--muted); border-radius: 6px;
    font-size: 9px; font-weight: 700; letter-spacing: 0.08em;
    padding: 3px 8px; text-transform: uppercase;
}
.tag-save {
    background: #edf7f1; border: 1px solid rgba(47,158,91,0.25);
    color: #2f9e5b; border-radius: 100px;
    font-size: 9px; font-weight: 800; letter-spacing: 0.06em;
    padding: 2px 9px; display: inline-block;
}

/* Stars */
.stars { color: var(--gold); font-size: 11px; letter-spacing: 1px; }

/* Coupon chips */
.coupon-chip {
    border: 1.5px dashed var(--border);
    border-radius: 8px; padding: 6px 14px;
    font-size: 10px; font-weight: 700; color: var(--gold);
    letter-spacing: 0.06em; background: transparent; cursor: pointer;
    transition: all 0.2s;
}
.coupon-chip:hover { border-color: var(--gold); background: rgba(201,151,63,0.05); }

/* Trust badge icon circle */
.trust-icon {
    width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-center: center;
    font-size: 18px; flex-shrink: 0;
}

/* Summary row */
.summary-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; font-size: 12px; }
.summary-label { text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; color: var(--muted); font-size: 9px; }
.summary-value { font-weight: 700; color: var(--charcoal); font-variant-numeric: tabular-nums; }
.summary-total-label { text-transform: uppercase; letter-spacing: 0.1em; font-weight: 800; color: var(--charcoal); font-size: 10px; }
.summary-total-value { font-size: 28px; font-weight: 800; color: var(--gold); line-height: 1; font-family: 'Playfair Display', serif; }

/* Action links */
.action-link {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; font-weight: 600; color: var(--muted);
    cursor: pointer; background: none; border: none; padding: 0;
    transition: color 0.2s; letter-spacing: 0.02em;
}
.action-link:hover { color: var(--gold); }
.action-link.danger:hover { color: #d93025; }

/* Loading spinner */
@keyframes spin { to { transform: rotate(360deg); } }
.spinner { animation: spin 0.8s linear infinite; }

/* Slide down animation for alerts */
@keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
.slide-down { animation: slideDown 0.25s ease; }

/* Saved items grid */
.saved-card {
    background: var(--white); border: 1px solid var(--border);
    border-radius: 16px; box-shadow: var(--shadow-sm);
    overflow: hidden; transition: all 0.3s ease;
}
.saved-card:hover { box-shadow: var(--shadow-md); transform: translateY(-3px); }

/* Gift wrap row */
.gift-row {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 12px; margin-top: 12px;
    border-top: 1px dashed var(--border);
    font-size: 11px; font-weight: 600; color: var(--muted);
    letter-spacing: 0.04em;
}
gift-row label { cursor: pointer; display: flex; align-items: center; gap: 8px; }

/* Input styling */
.coupon-input {
    flex: 1;
    background: #faf9f6;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 10px 16px;
    font-size: 12px;
    color: var(--charcoal);
    outline: none;
    transition: border-color 0.2s;
    font-family: 'Inter', sans-serif;
}
.coupon-input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(201,151,63,0.12); }

/* Section micro label */
.section-label {
    text-transform: uppercase;
    letter-spacing: 0.18em;
    font-size: 9px;
    font-weight: 800;
    color: var(--gold);
    margin-bottom: 4px;
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .product-img-wrap { width: 80px; height: 80px; }
    .cart-card { border-radius: 14px; }
}
</style>

<div class="cart-page py-14 md:py-20 -mx-6 sm:-mx-8 lg:-mx-12 px-4 sm:px-8 lg:px-12 -mt-16 pt-14 min-h-screen"
     x-data="{
         loading: true,
         error: false,
         items: [],
         savedItems: [],
         giftWrap: {},
         giftWrapPrice: 150.00,
         couponCode: '',
         appliedCoupon: null,
         couponError: '',
         couponSuccess: '',

         fetchCart() {
             this.loading = true;
             this.error = false;
             fetch('{{ route('api.cart.get') }}')
                 .then(r => r.json())
                 .then(data => {
                     if (data.success) {
                         this.items = data.items.filter(i => !i.is_saved);
                         this.savedItems = data.items.filter(i => i.is_saved);
                     } else {
                         this.error = true;
                     }
                     this.loading = false;
                 })
                 .catch(() => { this.error = true; this.loading = false; });
         },

         updateQuantity(item, delta) {
             const newQty = item.quantity + delta;
             if (newQty < 1) return;
             item.quantity = newQty;
             fetch(`/api/cart/${item.id}`, {
                 method: 'PATCH',
                 headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                 body: JSON.stringify({ quantity: newQty })
             });
         },

         removeItem(id) {
             this.items = this.items.filter(i => i.id !== id);
             fetch(`/api/cart/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
             // Update navbar badge instantly
             this.$dispatch('cart-count-updated', { count: this.items.length });
         },

         moveToSaved(id) {
             const item = this.items.find(i => i.id === id);
             if (item) {
                 item.is_saved = true;
                 this.savedItems.push(item);
                 this.items = this.items.filter(i => i.id !== id);
                 fetch(`/api/cart/save-later/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                 // Update navbar badge instantly
                 this.$dispatch('cart-count-updated', { count: this.items.length });
             }
         },

         moveToBag(id) {
             const item = this.savedItems.find(i => i.id === id);
             if (item) {
                 item.is_saved = false;
                 this.items.push(item);
                 this.savedItems = this.savedItems.filter(i => i.id !== id);
                 fetch(`/api/cart/move-bag/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                 // Update navbar badge instantly
                 this.$dispatch('cart-count-updated', { count: this.items.length });
             }
         },

         applyCoupon() {
             this.couponError = '';
             this.couponSuccess = '';
             const code = this.couponCode.trim().toUpperCase();
             if (!code) return;
             fetch('{{ route('api.cart.applyCoupon') }}', {
                 method: 'POST',
                 headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                 body: JSON.stringify({ code })
             })
             .then(r => r.json())
             .then(data => {
                 if (data.success) { this.appliedCoupon = data.coupon; this.couponSuccess = `Coupon applied: ${data.coupon.description}`; }
                 else { this.couponError = data.message; }
             });
         },

         selectCoupon(code) { this.couponCode = code; this.applyCoupon(); },
         removeCoupon() { this.appliedCoupon = null; this.couponCode = ''; this.couponSuccess = ''; this.couponError = ''; },

         get subtotal() { return this.items.reduce((s, i) => s + i.price * i.quantity, 0); },
         get giftWrapTotal() {
             let t = 0;
             Object.entries(this.giftWrap).forEach(([id, on]) => {
                 if (on) { const item = this.items.find(i => i.id == id); if (item) t += this.giftWrapPrice * item.quantity; }
             });
             return t;
         },
         get shipping() { return this.subtotal === 0 ? 0 : (this.subtotal >= 1000 ? 0 : 99); },
         get discount() {
             if (!this.appliedCoupon) return 0;
             return this.appliedCoupon.type === 'percent'
                 ? this.subtotal * (this.appliedCoupon.value / 100)
                 : Math.min(this.appliedCoupon.value, this.subtotal);
         },
         get total() { return Math.max(0, this.subtotal + this.shipping + this.giftWrapTotal - this.discount); },

         fmt(v) { return '₹' + Number(v).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); },

         img(src) {
             if (!src) return 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=400&q=80';
             const map = { 'electronics_1': 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=400&q=80', 'bags': 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&q=80', 'footwear': 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&q=80', 'watch': 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80' };
             for (const [k, v] of Object.entries(map)) { if (src.includes(k)) return v; }
             if (src.startsWith('http')) return src;
             return '/storage/' + src;
         },

         init() { this.fetchCart(); }
     }">

    <div class="max-w-[1300px] mx-auto px-0">

        {{-- ── BREADCRUMB ── --}}
        <nav class="mb-8 flex items-center gap-2 text-[11px] font-semibold uppercase tracking-widest text-[#6b6b76]">
            <a href="{{ route('store.home') }}" class="hover:text-[#c9973f] transition-colors">Home</a>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
            <span class="text-[#1c1c1e]">Shopping Cart</span>
        </nav>

        {{-- ── PAGE HEADER ── --}}
        <div class="mb-8 pb-6 border-b border-[#e8e6e1] flex flex-wrap items-baseline gap-3">
            <h1 class="cart-serif text-4xl md:text-5xl font-bold text-[#1c1c1e] tracking-tight leading-none">Shopping Bag</h1>
            <span class="cart-serif text-2xl italic font-semibold text-[#c9973f]" x-text="`(${items.length} ${items.length === 1 ? 'item' : 'items'})`"></span>
        </div>

        {{-- ── STEPPER (below header) ── --}}
        <div class="mb-10">
            <div class="relative flex items-start justify-between max-w-2xl">
                {{-- Connecting line --}}
                <div class="stepper-line absolute left-0 right-0 top-[22px] mx-8">
                    <div class="stepper-line-fill"></div>
                </div>

                @foreach([['Bag', '1', true], ['Delivery', '2', false], ['Payment', '3', false], ['Confirmed', '4', false]] as [$label, $num, $active])
                <div class="flex flex-col items-center gap-2 relative z-10 bg-[#faf9f6] px-3">
                    <div class="step-circle {{ $active ? 'step-active' : 'step-upcoming' }}">
                        @if($active)
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M9 12l2 2 4-4"/></svg>
                        @else
                        {{ $num }}
                        @endif
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider {{ $active ? 'text-[#c9973f]' : 'text-[#6b6b76]' }}">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ── LOADING ── --}}
        <div x-show="loading" class="flex flex-col items-center justify-center py-32 gap-6">
            <svg class="spinner w-12 h-12 text-[#c9973f]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
            </svg>
            <p class="text-[11px] uppercase tracking-widest font-bold text-[#6b6b76]">Loading your bag...</p>
        </div>

        {{-- ── ERROR ── --}}
        <div x-show="!loading && error" class="max-w-sm mx-auto py-20 text-center" style="display:none;">
            <div class="cart-card p-10">
                <div class="text-4xl mb-4">⚠️</div>
                <h3 class="cart-serif text-xl font-bold text-[#1c1c1e] mb-2">Failed to load</h3>
                <p class="text-sm text-[#6b6b76] mb-6">Could not connect to the server.</p>
                <button @click="fetchCart()" class="btn-gold h-11 px-8">Retry</button>
            </div>
        </div>

        {{-- ── MAIN CONTENT ── --}}
        <div x-show="!loading && !error" style="display:none;">

            {{-- Has items --}}
            <template x-if="items.length > 0">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-10 items-start">

                    {{-- ════ LEFT: CART ITEMS ════ --}}
                    <div class="space-y-5">
                        <template x-for="(item, idx) in items" :key="item.id">
                            <div class="cart-card p-7 flex flex-col sm:flex-row gap-6">

                                {{-- Image --}}
                                <div class="product-img-wrap">
                                    <img :src="img(item.image)" :alt="item.name" loading="lazy">
                                    <span class="tag-sale">SALE</span>
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 flex flex-col justify-between min-w-0 gap-4">

                                    {{-- Top row: name + stock --}}
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <h3 class="cart-serif text-[20px] font-bold text-[#1c1c1e] leading-snug hover:text-[#c9973f] transition-colors truncate">
                                                <a :href="'/product/' + item.slug" x-text="item.name"></a>
                                            </h3>
                                            {{-- Variant tags --}}
                                            <div class="flex flex-wrap gap-1.5 mt-2">
                                                <template x-for="[k,v] in Object.entries(item.options || {})">
                                                    <span class="tag-variant" x-text="k + ': ' + v"></span>
                                                </template>
                                            </div>
                                            {{-- Stars --}}
                                            <div class="flex items-center gap-2 mt-2.5 text-[11px]">
                                                <span class="stars">★★★★★</span>
                                                <span class="font-bold text-[#1c1c1e]">4.8</span>
                                                <span class="text-[#6b6b76]">·</span>
                                                <span class="text-[#6b6b76]">124 reviews</span>
                                                <span class="text-[#6b6b76]">·</span>
                                                <span class="text-amber-600 bg-amber-50 text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded">🔥 240 sold</span>
                                            </div>
                                        </div>
                                        <span class="tag-stock shrink-0">In Stock</span>
                                    </div>

                                    {{-- Price --}}
                                    <div class="flex items-baseline flex-wrap gap-2">
                                        <span class="cart-serif text-2xl font-bold text-[#B88A44]" x-text="fmt(item.price)"></span>
                                        <span class="text-[13px] text-gray-400 line-through" x-text="fmt(item.original_price || item.price * 1.1)"></span>
                                        <span class="tag-save" x-text="'Save ' + fmt(Math.abs((item.original_price || item.price*1.1) - item.price))"></span>
                                    </div>

                                    {{-- Actions row --}}
                                    <div class="flex flex-wrap items-center gap-5 pt-4 border-t border-[#EAEAEA]">

                                        {{-- Qty stepper --}}
                                        <div class="qty-control">
                                            <button class="qty-btn" @click="updateQuantity(item, -1)" aria-label="Decrease">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/></svg>
                                            </button>
                                            <span class="qty-num" x-text="item.quantity"></span>
                                            <button class="qty-btn" @click="updateQuantity(item, 1)" aria-label="Increase">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                                            </button>
                                        </div>

                                        {{-- Save/Remove links --}}
                                        <button class="action-link" @click="moveToSaved(item.id)">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                                            Save for later
                                        </button>
                                        <span class="text-[#EAEAEA] text-lg font-thin">|</span>
                                        <button class="action-link danger" @click="removeItem(item.id)">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                            Remove
                                        </button>

                                        {{-- Subtotal --}}
                                        <div class="ml-auto text-right">
                                            <span class="block text-[9px] uppercase tracking-widest font-bold text-[#6B7280] mb-0.5">Subtotal</span>
                                            <span class="cart-serif text-[18px] font-bold text-[#111827]" x-text="fmt(item.price * item.quantity)"></span>
                                        </div>
                                    </div>

                                    {{-- Gift wrap --}}
                                    <div class="gift-row">
                                        <label :for="'gift-' + item.id" class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" :id="'gift-' + item.id" x-model="giftWrap[item.id]" class="h-4 w-4 rounded border-[#d0cec9] accent-[#B88A44]">
                                            <span>🎁 Premium Gift Wrapping <span class="text-[#B88A44] font-bold">(+{{ '₹150.00' }})</span></span>
                                        </label>
                                        <span x-show="giftWrap[item.id]" class="text-[#B88A44] font-bold text-xs" x-text="fmt(150 * item.quantity)"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Continue Shopping --}}
                        <div class="flex justify-start pt-2">
                            <a href="{{ route('store.shop') }}" class="action-link text-[11px]">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                                Continue Shopping
                            </a>
                        </div>
                    </div>

                    {{-- ════ RIGHT: SIDEBAR ════ --}}
                    <div class="space-y-5 lg:sticky lg:top-24">

                        {{-- ── Order Summary ── --}}
                        <div class="cart-card p-7 space-y-5">
                            <div class="pb-4 border-b border-[#e8e6e1]">
                                <span class="section-label">Order Summary</span>
                                <h3 class="cart-serif text-[17px] font-bold text-[#1c1c1e]">Your Bill</h3>
                            </div>

                            <div class="space-y-1 divide-y divide-[#f0ede8]">
                                <div class="summary-row">
                                    <span class="summary-label">Subtotal</span>
                                    <span class="summary-value" x-text="fmt(subtotal)"></span>
                                </div>
                                <template x-if="giftWrapTotal > 0">
                                    <div class="summary-row">
                                        <span class="summary-label">Gift Wrapping</span>
                                        <span class="summary-value" x-text="fmt(giftWrapTotal)"></span>
                                    </div>
                                </template>
                                <div class="summary-row">
                                    <span class="summary-label">Shipping</span>
                                    <span x-show="shipping === 0" class="text-[10px] font-bold text-[#2f9e5b] bg-[#edf7f1] px-2.5 py-1 rounded-full uppercase tracking-wider">FREE</span>
                                    <span x-show="shipping > 0" class="summary-value" x-text="fmt(shipping)"></span>
                                </div>
                                <template x-if="discount > 0">
                                    <div class="summary-row bg-[#edf7f1] rounded-xl px-3 -mx-1">
                                        <span class="summary-label text-[#2f9e5b]">Coupon Discount</span>
                                        <span class="font-bold text-[#2f9e5b] text-[13px]" x-text="'- ' + fmt(discount)"></span>
                                    </div>
                                </template>
                            </div>

                            <div class="pt-4 border-t border-[#e8e6e1] flex justify-between items-baseline gap-4">
                                <span class="summary-total-label">Total Amount</span>
                                <span class="summary-total-value" x-text="fmt(total)"></span>
                            </div>

                            {{-- CTA Button --}}
                            <button class="btn-gold w-full h-[52px] text-[12px] flex items-center justify-center gap-2.5 rounded-2xl">
                                <span>Proceed to Checkout</span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </button>

                            {{-- Secure note --}}
                            <div class="flex items-center justify-center gap-1.5 text-[10px] text-[#6b6b76]">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                <span>Secure Checkout · 256-bit SSL · All taxes included</span>
                            </div>

                            {{-- Payment icons --}}
                            <div class="flex items-center justify-center gap-3 pt-3 border-t border-[#f0ede8]">
                                <span class="text-[9px] font-bold text-[#6b6b76] uppercase tracking-wider">We accept</span>
                                <img src="https://img.icons8.com/color/36/visa.png" alt="Visa" class="h-5 opacity-70 hover:opacity-100 transition-opacity">
                                <img src="https://img.icons8.com/color/36/mastercard.png" alt="Mastercard" class="h-5 opacity-70 hover:opacity-100 transition-opacity">
                                <img src="https://img.icons8.com/color/48/upi.png" alt="UPI" class="h-5 opacity-70 hover:opacity-100 transition-opacity">
                            </div>
                        </div>

                        {{-- ── Promo Code ── --}}
                        <div class="cart-card p-6 space-y-4">
                            <div class="flex items-center gap-2 mb-1">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#B88A44" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                <span class="text-[11px] font-bold uppercase tracking-widest text-[#111827]">Promo Code</span>
                            </div>

                             <div class="flex gap-2">
                                 <input x-model="couponCode" type="text" placeholder="Enter coupon code..." class="coupon-input" @keydown.enter="applyCoupon()">
                                 <button @click="applyCoupon()" class="shrink-0 h-[42px] px-5 rounded-xl border-2 border-[#B88A44] text-[#B88A44] text-[11px] font-bold uppercase tracking-wider hover:bg-[#B88A44] hover:text-white transition-all duration-200">Apply</button>
                             </div>

                             <div class="flex flex-wrap gap-2">
                                 <button @click="selectCoupon('SHOPME20')" class="coupon-chip">SHOPME20 · 20% Off</button>
                                 <button @click="selectCoupon('GOLD1000')" class="coupon-chip">GOLD1000 · ₹1,000 Off</button>
                             </div>

                             {{-- Success alert --}}
                             <div x-show="couponSuccess" class="slide-down flex items-center justify-between gap-3 bg-[#edf7f1] border border-[#2f9e5b]/30 rounded-xl px-4 py-3" style="display:none;">
                                 <div class="flex items-center gap-2 text-[11px] font-semibold text-[#2f9e5b]">
                                     <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                     <span x-text="couponSuccess"></span>
                                 </div>
                                 <button @click="removeCoupon()" class="text-[#6B7280] hover:text-red-500 font-bold text-base leading-none">×</button>
                             </div>

                            {{-- Error alert --}}
                            <div x-show="couponError" class="slide-down flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-[11px] font-semibold text-red-600" style="display:none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                                <span x-text="couponError"></span>
                            </div>
                        </div>

                        {{-- ── Trust Badges ── --}}
                        <div class="cart-card p-6">
                            <div class="space-y-4">
                                <div class="flex items-start gap-3.5">
                                    <div class="trust-icon bg-[#edf7f1] flex items-center justify-center rounded-full w-11 h-11 text-xl">🔄</div>
                                    <div>
                                        <h5 class="text-[11px] font-bold uppercase tracking-wider text-[#1c1c1e]">7-Day Easy Returns</h5>
                                        <p class="text-[10px] text-[#6b6b76] mt-0.5 leading-relaxed">No-hassle returns within 7 days for a full refund.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3.5">
                                    <div class="trust-icon bg-[#f0f5ff] flex items-center justify-center rounded-full w-11 h-11 text-xl">🚚</div>
                                    <div>
                                        <h5 class="text-[11px] font-bold uppercase tracking-wider text-[#1c1c1e]">Free Delivery Above ₹1,000</h5>
                                        <p class="text-[10px] text-[#6b6b76] mt-0.5 leading-relaxed">Express delivery in 2-4 business days.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3.5">
                                    <div class="trust-icon bg-[#fff8ed] flex items-center justify-center rounded-full w-11 h-11 text-xl">🛡️</div>
                                    <div>
                                        <h5 class="text-[11px] font-bold uppercase tracking-wider text-[#1c1c1e]">1-Year Brand Warranty</h5>
                                        <p class="text-[10px] text-[#6b6b76] mt-0.5 leading-relaxed">All electronics come with manufacturer warranty.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- End right sidebar --}}
                </div>
            </template>

            {{-- ══ EMPTY STATE ══ --}}
            <template x-if="items.length === 0">
                <div class="flex flex-col items-center justify-center py-28 text-center max-w-md mx-auto">
                    <div class="w-28 h-28 bg-white rounded-full flex items-center justify-center text-5xl mb-6 shadow-[0_8px_32px_rgba(0,0,0,0.08)] border border-[#e8e6e1]">👜</div>
                    <h3 class="cart-serif text-3xl font-bold text-[#1c1c1e] mb-3">Your bag is empty</h3>
                    <p class="text-sm text-[#6b6b76] leading-relaxed mb-8 max-w-xs">Explore our premium curated collection and add products to your shopping bag.</p>
                    <a href="{{ route('store.shop') }}" class="btn-gold inline-flex items-center gap-2 px-8 h-12">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        Continue Shopping
                    </a>
                </div>
            </template>

            {{-- ══ SAVED FOR LATER ══ --}}
            <div class="mt-16 pt-12 border-t border-[#e8e6e1]">
                <div class="flex items-center gap-5 mb-8">
                    <div>
                        <span class="section-label">Saved items</span>
                        <h3 class="cart-serif text-2xl font-bold text-[#1c1c1e]">
                            Saved For Later <span class="text-[#c9973f] italic font-semibold text-xl" x-text="`(${savedItems.length})`"></span>
                        </h3>
                    </div>
                    <div class="flex-1 h-[1px] bg-[#e8e6e1]"></div>
                </div>

                <div x-show="savedItems.length > 0">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                        <template x-for="(item, idx) in savedItems" :key="item.id">
                            <div class="saved-card p-4">
                                <div class="aspect-[3/4] rounded-xl overflow-hidden bg-[#f5f4f0] border border-[#e8e6e1] mb-4">
                                    <img :src="img(item.image)" :alt="item.name" class="w-full h-full object-cover" loading="lazy">
                                </div>
                                <div class="space-y-1 mb-3">
                                    <h4 class="cart-serif font-bold text-[14px] text-[#1c1c1e] leading-snug line-clamp-2">
                                        <a :href="'/product/' + item.slug" x-text="item.name"></a>
                                    </h4>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-for="[k,v] in Object.entries(item.options || {})">
                                            <span class="tag-variant text-[8px]" x-text="k + ': ' + v"></span>
                                        </template>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-3 border-t border-[#e8e6e1]">
                                    <span class="cart-serif font-bold text-[15px] text-[#c9973f]" x-text="fmt(item.price)"></span>
                                    <button @click="moveToBag(item.id)"
                                        class="text-[9px] font-bold uppercase tracking-wider text-[#c9973f] border-2 border-[#c9973f] rounded-full px-3 h-8 hover:bg-[#c9973f] hover:text-white transition-all duration-200">
                                        Add to Bag
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="savedItems.length === 0" class="py-8 text-center text-xs text-[#6b6b76] italic">
                    No saved items. Use "Save for later" on cart items to save them here.
                </div>
            </div>

        </div>
        {{-- End main content --}}

    </div>
</div>
@endsection
