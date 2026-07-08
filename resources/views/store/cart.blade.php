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
    --shadow-sm: 0 4px 15px rgba(0,0,0,0.015);
    --shadow-md: 0 10px 30px rgba(0,0,0,0.03);
    --shadow-lg: 0 20px 40px rgba(0,0,0,0.04);
}

.cart-page { font-family: 'Inter', sans-serif; background: #FAF9F6; }
.cart-serif { font-family: 'Playfair Display', serif; }

/* Cards */
.cart-card {
    background: var(--white);
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    transition: all 0.3s ease;
}
.cart-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    border-color: transparent;
}

/* Premium Gold Button */
.btn-gold {
    background: #111827;
    color: white;
    border: none;
    border-radius: 14px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-gold:hover {
    background: var(--gold);
    transform: translateY(-1.5px);
    box-shadow: 0 8px 24px rgba(184,138,68,0.2);
}

/* Stepper */
.stepper-line { height: 1.5px; background: var(--border); position: relative; }
.stepper-line-fill { height: 100%; background: var(--gold); width: 25%; transition: width 0.6s ease; }
.step-circle {
    width: 38px; height: 38px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 12px;
    transition: all 0.3s ease;
    position: relative; z-index: 2;
}
.step-active { background: var(--gold); color: white; box-shadow: 0 0 0 4px rgba(201,151,63,0.15); }
.step-done { background: #111827; color: white; }
.step-upcoming { background: var(--white); color: var(--muted); border: 1.5px solid var(--border); }

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
    width: 30px; height: 30px;
    border: none; background: transparent;
    color: var(--muted); cursor: pointer; font-size: 14px; font-weight: 600;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
}
.qty-btn:hover { background: #faf9f6; color: var(--charcoal); }
.qty-num { min-width: 26px; text-align: center; font-size: 11.5px; font-weight: 700; color: var(--charcoal); }

/* Product Image */
.product-img-wrap {
    width: 90px; height: 90px; flex-shrink: 0;
    border-radius: 12px; overflow: hidden;
    border: 1px solid var(--border);
    background: #f5f4f0;
    position: relative;
}
.product-img-wrap img { width: 100%; height: 100%; object-fit: cover; }

/* Tags */
.tag-sale {
    position: absolute; top: 5px; left: 5px;
    background: var(--white); border: 1px solid var(--gold);
    color: var(--gold); border-radius: 100px;
    font-size: 7px; font-weight: 850; letter-spacing: 0.08em;
    padding: 1.5px 5.5px; text-transform: uppercase;
}
.tag-stock {
    display: inline-flex; align-items: center; gap: 4px;
    background: #edf7f1; border: 1px solid rgba(47,158,91,0.2);
    color: #2f9e5b; border-radius: 100px;
    font-size: 8px; font-weight: 700; letter-spacing: 0.08em;
    padding: 2.5px 8px; text-transform: uppercase;
}
.tag-stock::before { content: ''; display: block; width: 4px; height: 4px; background: #2f9e5b; border-radius: 50%; }
.tag-variant {
    display: inline-flex; align-items: center; gap: 3px;
    background: #f5f4f0; border: 1px solid var(--border);
    color: var(--muted); border-radius: 5px;
    font-size: 8.5px; font-weight: 700; letter-spacing: 0.06em;
    padding: 2px 7px; text-transform: uppercase;
}
.tag-save {
    background: #edf7f1; border: 1px solid rgba(47,158,91,0.2);
    color: #2f9e5b; border-radius: 100px;
    font-size: 8.5px; font-weight: 800; letter-spacing: 0.04em;
    padding: 1.5px 8px; display: inline-block;
}

/* Stars */
.stars { color: var(--gold); font-size: 10px; letter-spacing: 0.5px; }

/* Coupon chips */
.coupon-chip {
    border: 1.5px dashed var(--border);
    border-radius: 8px; padding: 5px 12px;
    font-size: 9.5px; font-weight: 700; color: var(--gold);
    letter-spacing: 0.05em; background: transparent; cursor: pointer;
    transition: all 0.2s;
}
.coupon-chip:hover { border-color: var(--gold); background: rgba(201,151,63,0.04); }

/* Trust badge icon circle */
.trust-icon {
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
}

/* Summary row */
.summary-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; font-size: 12px; }
.summary-label { text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700; color: var(--muted); font-size: 9px; }
.summary-value { font-weight: 700; color: var(--charcoal); font-variant-numeric: tabular-nums; }
.summary-total-label { text-transform: uppercase; letter-spacing: 0.08em; font-weight: 800; color: var(--charcoal); font-size: 10px; }
.summary-total-value { font-size: 24px; font-weight: 800; color: var(--gold); line-height: 1; font-family: 'Playfair Display', serif; }

/* Action links */
.action-link {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10.5px; font-weight: 600; color: var(--muted);
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
    background: var(--white);
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    overflow: hidden;
    transition: all 0.3s ease;
}
.saved-card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    border-color: transparent;
}

/* Gift wrap row */
.gift-row {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 10px; margin-top: 10px;
    border-top: 1px dashed var(--border);
    font-size: 10.5px; font-weight: 600; color: var(--muted);
    letter-spacing: 0.04em;
}
.gift-row label { cursor: pointer; display: flex; align-items: center; gap: 6px; }

/* Input styling */
.coupon-input {
    flex: 1;
    background: #faf9f6;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 8px 14px;
    font-size: 11px;
    color: var(--charcoal);
    outline: none;
    transition: border-color 0.2s;
    font-family: 'Inter', sans-serif;
}
.coupon-input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(201,151,63,0.1); }

/* Section micro label */
.section-label {
    text-transform: uppercase;
    letter-spacing: 0.15em;
    font-size: 8.5px;
    font-weight: 800;
    color: var(--gold);
    margin-bottom: 4px;
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .product-img-wrap { width: 80px; height: 80px; }
    .cart-card { border-radius: 16px; }
}
</style>

<div class="cart-page py-6 md:py-8 -mx-6 sm:-mx-8 lg:-mx-12 px-4 sm:px-8 lg:px-12 -mt-16 pt-4 min-h-screen"
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
              this.$dispatch('cart-count-updated', { count: this.items.length });
         },

         moveToSaved(id) {
              const item = this.items.find(i => i.id === id);
              if (item) {
                  item.is_saved = true;
                  this.savedItems.push(item);
                  this.items = this.items.filter(i => i.id !== id);
                  fetch(`/api/cart/save-later/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
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
              if (!src) return 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=400';
              if (src.startsWith('http')) return src;
              
              const lowerSrc = src.toLowerCase();
              if (lowerSrc.includes('electronics') || lowerSrc.includes('phone') || lowerSrc.includes('tech')) {
                  return 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400';
              }
              if (lowerSrc.includes('watch')) {
                  return 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=400';
              }
              if (lowerSrc.includes('shoe') || lowerSrc.includes('footwear') || lowerSrc.includes('sneaker')) {
                  return 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=400';
              }
              if (lowerSrc.includes('bag') || lowerSrc.includes('backpack')) {
                  return 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=400';
              }
              if (lowerSrc.includes('iphone') || lowerSrc.includes('digital') || lowerSrc.includes('gold')) {
                  return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80';
              }
              
              return '/storage/' + src;
         },

         init() { this.fetchCart(); }
     }">

    <div class="max-w-[1300px] mx-auto px-0">

        {{-- ── BREADCRUMB ── --}}
        <nav class="mb-3.5 flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-[#6b6b76]">
            <a href="{{ route('store.home') }}" class="hover:text-[#c9973f] transition-colors">Home</a>
            <span class="text-gray-300">/</span>
            <span class="text-[#1c1c1e]">Shopping Cart</span>
        </nav>

        {{-- ── PAGE HEADER & STEPPER ── --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 mb-6 pb-4 border-b border-[#e8e6e1]">
            <div class="flex items-baseline gap-3">
                <h1 class="cart-serif text-3xl md:text-4xl font-bold text-[#1c1c1e] tracking-tight leading-none">Shopping Bag</h1>
                <span class="cart-serif text-xl italic font-semibold text-[#c9973f]" x-text="`(${items.length} ${items.length === 1 ? 'item' : 'items'})`"></span>
            </div>
            
            {{-- DESKTOP STEPPER (Hidden on mobile) --}}
            <div class="hidden sm:block w-full lg:w-auto min-w-[280px]">
                <div class="relative flex items-start justify-between">
                    {{-- Connecting line --}}
                    <div class="stepper-line absolute left-0 right-0 top-[19px] mx-6">
                        <div class="stepper-line-fill"></div>
                    </div>

                    @foreach([['Bag', '1', true], ['Delivery', '2', false], ['Payment', '3', false], ['Confirmed', '4', false]] as [$label, $num, $active])
                    <div class="flex flex-col items-center gap-1.5 relative z-10 bg-[#faf9f6] px-2.5">
                        <div class="step-circle {{ $active ? 'step-active' : 'step-upcoming' }}">
                            @if($active)
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M9 12l2 2 4-4"/></svg>
                            @else
                            {{ $num }}
                            @endif
                        </div>
                        <span class="text-[9px] font-bold uppercase tracking-wider {{ $active ? 'text-[#c9973f]' : 'text-[#6b6b76]' }}">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- MOBILE STEPPER (Visible only on mobile) --}}
            <div class="block sm:hidden w-full">
                <div class="flex items-center justify-between text-[9px] font-bold uppercase tracking-widest leading-none mb-2">
                    <span class="text-[#B88A44]">Shopping Bag</span>
                    <span class="text-[#6b6b76]">Step 1 of 4</span>
                </div>
                <div class="w-full h-1 bg-stone-200/50 rounded-full overflow-hidden">
                    <div class="h-full bg-[#B88A44] w-1/4 rounded-full"></div>
                </div>
            </div>
        </div>

        {{-- ── LOADING ── --}}
        <div x-show="loading" class="flex flex-col items-center justify-center py-28 gap-4">
            <svg class="spinner w-10 h-10 text-[#c9973f]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
            </svg>
            <p class="text-[10px] uppercase tracking-widest font-bold text-[#6b6b76]">Loading bag items...</p>
        </div>

        {{-- ── ERROR ── --}}
        <div x-show="!loading && error" class="max-w-sm mx-auto py-16 text-center" style="display:none;">
            <div class="cart-card p-8">
                <div class="text-3xl mb-3">⚠️</div>
                <h3 class="cart-serif text-lg font-bold text-[#1c1c1e] mb-1">Failed to load</h3>
                <p class="text-xs text-[#6b6b76] mb-5">Could not connect to the server.</p>
                <button @click="fetchCart()" class="btn-gold h-10 px-6">Retry</button>
            </div>
        </div>

        {{-- ── MAIN CONTENT ── --}}
        <div x-show="!loading && !error" style="display:none;">

            {{-- Has items --}}
            <template x-if="items.length > 0">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start">

                    {{-- ════ LEFT: CART ITEMS ════ --}}
                    <div class="space-y-4">
                        <template x-for="(item, idx) in items" :key="item.id">
                            <div class="cart-card p-4 sm:p-5 flex flex-row gap-4 sm:gap-5 items-start">

                                {{-- Image --}}
                                <div class="product-img-wrap shrink-0">
                                    <img :src="img(item.image)" :alt="item.name" loading="lazy">
                                    <span class="tag-sale">SALE</span>
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 flex flex-col justify-between min-w-0 gap-3">

                                    {{-- Top row: name + stock --}}
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <h3 class="cart-serif text-[16px] sm:text-[18px] font-bold text-[#1c1c1e] leading-snug hover:text-[#c9973f] transition-colors truncate">
                                                <a :href="'/product/' + item.slug" x-text="item.name"></a>
                                            </h3>
                                            {{-- Variant tags --}}
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                <template x-for="[k,v] in Object.entries(item.options || {})">
                                                    <span class="tag-variant" x-text="k + ': ' + v"></span>
                                                </template>
                                            </div>
                                            {{-- Stars --}}
                                            <div class="hidden sm:flex items-center gap-1.5 mt-1.5 text-[10px]">
                                                <span class="stars">★★★★★</span>
                                                <span class="font-bold text-[#1c1c1e]">4.8</span>
                                                <span class="text-[#6b6b76]">·</span>
                                                <span class="text-[#6b6b76]">124 reviews</span>
                                                <span class="text-[#6b6b76]">·</span>
                                                <span class="text-amber-600 bg-amber-50/50 text-[8px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded">🔥 240 sold</span>
                                            </div>
                                        </div>
                                        <span class="tag-stock shrink-0 text-[7.5px] sm:text-[8px]">In Stock</span>
                                    </div>

                                    {{-- Price --}}
                                    <div class="flex items-baseline flex-wrap gap-1.5">
                                        <span class="cart-serif text-lg sm:text-xl font-bold text-[#B88A44]" x-text="fmt(item.price)"></span>
                                        <span class="text-[10px] sm:text-[11px] text-gray-400 line-through" x-text="fmt(item.original_price || item.price * 1.1)"></span>
                                        <span class="tag-save text-[7.5px] sm:text-[8.5px]" x-text="'Save ' + fmt(Math.abs((item.original_price || item.price*1.1) - item.price))"></span>
                                    </div>

                                    {{-- Actions row --}}
                                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 pt-3 border-t border-[#EAEAEA]">

                                        {{-- Qty stepper --}}
                                        <div class="qty-control">
                                            <button class="qty-btn" @click="updateQuantity(item, -1)" aria-label="Decrease">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/></svg>
                                            </button>
                                            <span class="qty-num" x-text="item.quantity"></span>
                                            <button class="qty-btn" @click="updateQuantity(item, 1)" aria-label="Increase">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                                            </button>
                                        </div>

                                        {{-- Save/Remove links --}}
                                        <button class="action-link text-[10px] sm:text-[10.5px]" @click="moveToSaved(item.id)">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                                            <span class="hidden xs:inline">Save for later</span>
                                            <span class="xs:hidden">Save</span>
                                        </button>
                                        <span class="text-[#EAEAEA] text-xs">|</span>
                                        <button class="action-link danger text-[10px] sm:text-[10.5px]" @click="removeItem(item.id)">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                            <span>Remove</span>
                                        </button>

                                        {{-- Subtotal --}}
                                        <div class="ml-auto text-right">
                                            <span class="block text-[7.5px] uppercase tracking-widest font-bold text-[#6B7280] mb-0.5">Subtotal</span>
                                            <span class="cart-serif text-[14px] sm:text-[16px] font-bold text-[#111827]" x-text="fmt(item.price * item.quantity)"></span>
                                        </div>
                                    </div>

                                    {{-- Gift wrap --}}
                                    <div class="gift-row text-[9.5px] sm:text-[10.5px]">
                                        <label :for="'gift-' + item.id" class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" :id="'gift-' + item.id" x-model="giftWrap[item.id]" class="h-3.5 w-3.5 rounded border-[#d0cec9] accent-[#B88A44]">
                                            <span>🎁 Gift Wrapping <span class="text-[#B88A44] font-bold">(+{{ '₹150.00' }})</span></span>
                                        </label>
                                        <span x-show="giftWrap[item.id]" class="text-[#B88A44] font-bold text-[11px]" x-text="fmt(150 * item.quantity)"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Continue Shopping --}}
                        <div class="flex justify-start pt-2">
                            <a href="{{ route('store.shop') }}" class="action-link text-[10px] sm:text-[10.5px]">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                                Continue Shopping
                            </a>
                        </div>
                    </div>

                    {{-- ════ RIGHT: SIDEBAR ════ --}}
                    <div class="space-y-4 lg:sticky lg:top-24">

                        {{-- ── Order Summary ── --}}
                        <div class="cart-card p-5 sm:p-6 space-y-4">
                            <div class="pb-3 border-b border-[#e8e6e1]">
                                <span class="section-label">Order Summary</span>
                                <h3 class="cart-serif text-[16px] font-bold text-[#1c1c1e]">Your Bill</h3>
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
                                    <span x-show="shipping === 0" class="text-[9px] font-bold text-[#2f9e5b] bg-[#edf7f1] px-2.5 py-0.5 rounded-full uppercase tracking-wider">FREE</span>
                                    <span x-show="shipping > 0" class="summary-value" x-text="fmt(shipping)"></span>
                                </div>
                                <template x-if="discount > 0">
                                    <div class="summary-row bg-[#edf7f1] rounded-xl px-2.5 -mx-1">
                                        <span class="summary-label text-[#2f9e5b]">Coupon Discount</span>
                                        <span class="font-bold text-[#2f9e5b] text-[12px]" x-text="'- ' + fmt(discount)"></span>
                                    </div>
                                </template>
                            </div>

                            <div class="pt-3 border-t border-[#e8e6e1] flex justify-between items-baseline gap-4">
                                <span class="summary-total-label">Total Amount</span>
                                <span class="summary-total-value" x-text="fmt(total)"></span>
                            </div>

                            {{-- CTA Button --}}
                            <a href="{{ route('store.checkout') }}" class="btn-gold w-full h-[46px] sm:h-[48px] text-[11px] flex items-center justify-center gap-2 rounded-xl no-card-redirect">
                                <span>Proceed to Checkout</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>

                            {{-- Secure note --}}
                            <div class="flex items-center justify-center gap-1 text-[9px] text-[#6b6b76]">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                <span>Secure Checkout · 256-bit SSL</span>
                            </div>

                            {{-- Payment icons --}}
                            <div class="flex items-center justify-center gap-2 pt-2.5 border-t border-[#f0ede8]">
                                <span class="text-[8px] font-bold text-[#6b6b76] uppercase tracking-wider">We accept</span>
                                <img src="https://img.icons8.com/color/36/visa.png" alt="Visa" class="h-4 opacity-60 hover:opacity-100 transition-opacity">
                                <img src="https://img.icons8.com/color/36/mastercard.png" alt="Mastercard" class="h-4 opacity-60 hover:opacity-100 transition-opacity">
                                <img src="https://img.icons8.com/color/48/upi.png" alt="UPI" class="h-4 opacity-60 hover:opacity-100 transition-opacity">
                            </div>
                        </div>

                        {{-- ── Promo Code ── --}}
                        <div class="cart-card p-5 space-y-3">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B88A44" stroke-width="2.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-[#111827]">Promo Code</span>
                            </div>

                             <div class="flex gap-2">
                                 <input x-model="couponCode" type="text" placeholder="Enter coupon code..." class="coupon-input" @keydown.enter="applyCoupon()">
                                 <button @click="applyCoupon()" class="shrink-0 h-[38px] px-4 rounded-lg border border-[#B88A44] text-[#B88A44] text-[10px] font-bold uppercase tracking-wider hover:bg-[#B88A44] hover:text-white transition-all duration-200">Apply</button>
                             </div>

                             <div class="flex flex-wrap gap-1.5 pt-0.5">
                                 <button @click="selectCoupon('SHOPME20')" class="coupon-chip">SHOPME20 · 20% Off</button>
                                 <button @click="selectCoupon('GOLD1000')" class="coupon-chip">GOLD1000 · ₹1,000 Off</button>
                             </div>

                             {{-- Success alert --}}
                             <div x-show="couponSuccess" class="slide-down flex items-center justify-between gap-3 bg-[#edf7f1] border border-[#2f9e5b]/20 rounded-lg px-3 py-2" style="display:none;">
                                 <div class="flex items-center gap-1.5 text-[10px] font-semibold text-[#2f9e5b]">
                                     <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                     <span x-text="couponSuccess"></span>
                                 </div>
                                 <button @click="removeCoupon()" class="text-[#6B7280] hover:text-red-500 font-bold text-sm leading-none">×</button>
                             </div>

                             {{-- Error alert --}}
                             <div x-show="couponError" class="slide-down flex items-center gap-1.5 bg-red-50 border border-red-200/55 rounded-lg px-3 py-2 text-[10px] font-semibold text-red-600" style="display:none;">
                                 <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                                 <span x-text="couponError"></span>
                             </div>
                        </div>

                        {{-- ── Trust Badges ── --}}
                        <div class="cart-card p-5">
                            <div class="space-y-3.5">
                                <div class="flex items-start gap-3">
                                    <div class="trust-icon bg-[#edf7f1] text-[#2f9e5b]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                                    </div>
                                    <div>
                                        <h5 class="text-[10px] font-bold uppercase tracking-wider text-[#1c1c1e]">7-Day Easy Returns</h5>
                                        <p class="text-[9.5px] text-[#6b6b76] mt-0.5 leading-relaxed">No-hassle returns within 7 days for a full refund.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="trust-icon bg-[#f0f5ff] text-[#3b82f6]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="13" x="3" y="6" rx="2"/><path d="M12 12h.01M16 12h.01M8 12h.01M3 9h18"/></svg>
                                    </div>
                                    <div>
                                        <h5 class="text-[10px] font-bold uppercase tracking-wider text-[#1c1c1e]">Free Delivery Above ₹1,000</h5>
                                        <p class="text-[9.5px] text-[#6b6b76] mt-0.5 leading-relaxed">Express delivery in 2-4 business days.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="trust-icon bg-[#fff8ed] text-[#f59e0b]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    </div>
                                    <div>
                                        <h5 class="text-[10px] font-bold uppercase tracking-wider text-[#1c1c1e]">1-Year Brand Warranty</h5>
                                        <p class="text-[9.5px] text-[#6b6b76] mt-0.5 leading-relaxed">All electronics come with manufacturer warranty.</p>
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
                <div class="flex flex-col items-center justify-center py-20 text-center max-w-sm mx-auto">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-3xl mb-5 shadow-[0_8px_32px_rgba(0,0,0,0.04)] border border-[#e8e6e1]">👜</div>
                    <h3 class="cart-serif text-2xl font-bold text-[#1c1c1e] mb-2">Your bag is empty</h3>
                    <p class="text-xs text-[#6b6b76] leading-relaxed mb-6">Explore our premium curated collection and add products to your shopping bag.</p>
                    <a href="{{ route('store.shop') }}" class="btn-gold inline-flex items-center gap-1.5 px-6 h-11 rounded-xl">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        <span>Continue Shopping</span>
                    </a>
                </div>
            </template>

            {{-- ══ SAVED FOR LATER ══ --}}
            <div class="mt-14 pt-10 border-t border-[#e8e6e1]">
                <div class="flex items-center gap-4 mb-6">
                    <div>
                        <span class="section-label">Saved items</span>
                        <h3 class="cart-serif text-xl font-bold text-[#1c1c1e]">
                            Saved For Later <span class="text-[#c9973f] italic font-semibold text-lg" x-text="`(${savedItems.length})`"></span>
                        </h3>
                    </div>
                    <div class="flex-1 h-[1px] bg-[#e8e6e1]"></div>
                </div>

                <div x-show="savedItems.length > 0">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                        <template x-for="(item, idx) in savedItems" :key="item.id">
                            <div class="group relative cursor-pointer flex flex-col justify-between rounded-2xl border border-[#E5E7EB]/60 bg-white p-4 hover:shadow-xl hover:border-transparent transition-all duration-300">
                                <div>
                                    {{-- Image Wrapper --}}
                                    <div class="aspect-[4/5] bg-gray-50 rounded-xl overflow-hidden relative border border-gray-150/40">
                                        <img :src="img(item.image)" :alt="item.name" class="w-full h-full object-cover scale-100 group-hover:scale-105 transition-transform duration-500 ease-out" loading="lazy">
                                        
                                        {{-- Sale tag --}}
                                        <div class="absolute top-3 left-3 z-10">
                                            <span class="rounded border border-[#B88A44] bg-white/95 px-2.5 py-0.5 text-[8px] font-bold text-[#B88A44] uppercase tracking-widest shadow-sm">Sale</span>
                                        </div>

                                        {{-- Trash Remove Trigger in top right --}}
                                        <div class="absolute top-3 right-3 z-10 no-card-redirect">
                                            <button @click="removeItem(item.id)" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center text-gray-600 hover:text-red-500 hover:scale-110 shadow-sm transition duration-200 focus:outline-none" aria-label="Remove item">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                            </button>
                                        </div>

                                        {{-- Add to Bag Overlay on Hover --}}
                                        <div class="no-card-redirect absolute inset-x-3 bottom-3 z-10 translate-y-2 opacity-0 lg:group-hover:translate-y-0 lg:group-hover:opacity-100 transition-all duration-300">
                                            <button @click="moveToBag(item.id)" class="w-full h-10 rounded-xl bg-[#111827] text-white hover:bg-[#B88A44] text-xs font-bold uppercase tracking-wider transition-colors duration-200 shadow-lg">
                                                Add to Bag
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Card details --}}
                                    <div class="pt-4 space-y-1">
                                        <span class="text-[9px] text-[#B88A44] font-bold uppercase tracking-widest" x-text="item.brand || 'Premium'"></span>
                                        <h3 class="font-serif font-bold text-base text-[#111827] hover:text-[#B88A44] transition-colors duration-200 line-clamp-1">
                                            <a :href="'/product/' + item.slug" x-text="item.name"></a>
                                        </h3>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-3">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="font-serif font-bold text-sm text-[#B88A44]" x-text="fmt(item.price)"></span>
                                        <span class="text-xs text-gray-400 line-through" x-text="fmt(item.original_price || item.price * 1.1)"></span>
                                    </div>

                                    {{-- Mobile Add to Bag Trigger --}}
                                    <div class="no-card-redirect lg:hidden">
                                        <button @click="moveToBag(item.id)" class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-150/40 flex items-center justify-center text-gray-700 hover:bg-[#B88A44] hover:text-white transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="savedItems.length === 0" class="py-6 text-center text-xs text-[#6b6b76] italic">
                    No saved items. Use "Save for later" on cart items to save them here.
                </div>
            </div>

        </div>
        {{-- End main content --}}

    </div>
</div>
@endsection
