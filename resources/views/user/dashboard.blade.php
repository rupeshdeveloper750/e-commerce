@extends('layouts.store')

@section('title', 'Customer Dashboard')

@section('content')
@php
    $user = auth()->user();
@endphp

<div 
    class="max-w-[1440px] mx-auto min-h-screen pb-12"
    x-data="{ 
        // Tabs state
        activeTab: '{{ request()->query("tab", "dashboard") }}',
        drawerOpen: false,
        tabLoading: false,
        changeTab(tabId) {
            if (this.activeTab === tabId) return;
            this.tabLoading = true;
            this.activeTab = tabId;
            this.drawerOpen = false;
            setTimeout(() => { this.tabLoading = false; }, 300);
        },
        
        // Modal / Notification UI feedback
        toastMessage: '',
        toastType: 'info',
        showToast: false,
        triggerToast(msg, type = 'info') {
            this.toastMessage = msg;
            this.toastType = type;
            this.showToast = true;
            setTimeout(() => { this.showToast = false; }, 3000);
        },

        // Mocked Address State
        addresses: [
            { label: 'Home', name: '{{ $user->name }}', street: '124, Luxury Boulevard, Bandra West', city: 'Mumbai', state: 'Maharashtra', zip: '400050', phone: '9876543210', is_default: true },
            { label: 'Office', name: '{{ $user->name }}', street: 'Level 14, Capital Tower, Outer Ring Road', city: 'Bangalore', state: 'Karnataka', zip: '560103', phone: '9876543211', is_default: false }
        ],
        showAddressModal: false,
        addressForm: { index: -1, label: 'Home', name: '', street: '', city: '', state: '', zip: '', phone: '', is_default: false },
        
        addAddressOpen() {
            this.addressForm = { index: -1, label: 'Home', name: '{{ $user->name }}', street: '', city: '', state: '', zip: '', phone: '', is_default: false };
            this.showAddressModal = true;
        },
        editAddress(index) {
            const addr = this.addresses[index];
            this.addressForm = { index: index, ...addr };
            this.showAddressModal = true;
        },
        saveAddress() {
            if (!this.addressForm.name || !this.addressForm.street || !this.addressForm.city) {
                this.triggerToast('Please fill out all required fields.', 'error');
                return;
            }
            if (this.addressForm.is_default) {
                this.addresses.forEach(a => a.is_default = false);
            }
            if (this.addressForm.index === -1) {
                // Add new
                this.addresses.push({ ...this.addressForm });
                this.triggerToast('New address saved successfully.', 'success');
            } else {
                // Update existing
                this.addresses[this.addressForm.index] = { ...this.addressForm };
                this.triggerToast('Address updated successfully.', 'success');
            }
            this.showAddressModal = false;
        },
        deleteAddress(index) {
            if(confirm('Are you sure you want to delete this address?')) {
                this.addresses.splice(index, 1);
                this.triggerToast('Address deleted successfully.', 'success');
            }
        },

        // Mocked Cards State
        cards: [
            { id: 1, brand: 'visa', number: '•••• •••• •••• 4242', expiry: '12/28', holder: '{{ strtoupper($user->name) }}', is_default: true },
            { id: 2, brand: 'mastercard', number: '•••• •••• •••• 8855', expiry: '09/27', holder: '{{ strtoupper($user->name) }}', is_default: false }
        ],
        showCardModal: false,
        cardForm: { brand: 'visa', number: '', expiry: '', holder: '', is_default: false },
        
        saveCard() {
            if(!this.cardForm.number || !this.cardForm.expiry || !this.cardForm.holder) {
                this.triggerToast('Please complete the card details.', 'error');
                return;
            }
            if (this.cardForm.is_default) {
                this.cards.forEach(c => c.is_default = false);
            }
            // Format card number to mask
            let masked = '•••• •••• •••• ' + this.cardForm.number.slice(-4);
            this.cards.push({
                id: Date.now(),
                brand: this.cardForm.brand,
                number: masked,
                expiry: this.cardForm.expiry,
                holder: this.cardForm.holder.toUpperCase(),
                is_default: this.cardForm.is_default
            });
            this.showCardModal = false;
            this.triggerToast('Saved card successfully.', 'success');
        },
        deleteCard(id) {
            if (confirm('Delete this payment method?')) {
                this.cards = this.cards.filter(c => c.id !== id);
                this.triggerToast('Payment method removed.', 'success');
            }
        },

        // Mocked Notification Center
        notifications: [
            { title: 'Order Dispatched', message: 'Your order #ORD-YTF-987541 has been shipped and is on the way.', time: '2 hours ago', category: 'order', is_read: false },
            { title: 'Exclusive Coupon', message: 'A secret 20% discount coupon has been added to your vault.', time: '1 day ago', category: 'promo', is_read: false },
            { title: 'Review Approved', message: 'Your review for G-Shock Full Metal Gold has been published.', time: '3 days ago', category: 'general', is_read: true }
        ],
        markAsRead(index) {
            this.notifications[index].is_read = true;
            this.triggerToast('Marked as read.', 'success');
        },
        deleteNotification(index) {
            this.notifications.splice(index, 1);
            this.triggerToast('Notification deleted.', 'success');
        },

        // Coupon Copy Util
        copyCoupon(code, event) {
            navigator.clipboard.writeText(code);
            this.triggerToast('Coupon code copied: ' + code, 'success');
        },

        // Tracking timeline modal
        showTrackModal: false,
        trackOrderNum: '',
        trackStatus: 'shipped',
        trackDetails: null,
        getActiveStepIndex() {
            const status = this.trackStatus.toLowerCase();
            if (status === 'delivered') return 4;
            if (status === 'out_for_delivery' || status === 'delivering') return 3;
            if (status === 'shipped') return 2;
            if (status === 'processing') return 1;
            return 0; // pending
        },
        getTimelineSteps() {
            return [
                { title: 'Order Placed', time: 'Jul 09, 11:38 AM', location: 'Digital Hub', desc: 'Order logged and payment confirmed.' },
                { title: 'Packed & Dispatched', time: 'Jul 09, 03:15 PM', location: 'Warehouse Facility', desc: 'Packed and hand-over to courier partner.' },
                { title: 'In Transit (Shipped)', time: 'Jul 10, 09:00 AM', location: 'Cargo Hub', desc: 'In-transit via Air Express Cargo.' },
                { title: 'Out For Delivery', time: 'Jul 11, 08:30 AM', location: 'Bandra Center', desc: 'Package out with delivery executive Rohan.' },
                { title: 'Delivered', time: 'Jul 11, 01:45 PM', location: 'Customer Doorstep', desc: 'Delivered and verified by signature.' }
            ];
        },
        openTrackModal(orderNum, status) {
            this.trackOrderNum = orderNum;
            this.trackStatus = status;
            
            const courier = 'Delhivery Express';
            const trackingNo = 'DEL' + orderNum + 'IN';
            
            this.trackDetails = {
                order_num: orderNum,
                status: status,
                tracking_no: trackingNo,
                courier: courier,
                weight: '1.25 kg',
                address: 'Roopesh Kumar, 124 Luxury Boulevard, Bandra West, Mumbai, MH - 400050',
                method: 'Premium Air Delivery',
                progress: status === 'delivered' ? 100 : (status === 'shipped' ? 65 : (status === 'processing' ? 35 : 15)),
                product: {
                    name: 'Vintage Digital Gold D182',
                    brand: 'ShopMe Classic',
                    price: '₹4,795.00',
                    quantity: 1,
                    image: 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=150&q=80'
                }
            };
            this.showTrackModal = true;
        },

        // Support tickets simulation
        tickets: [
            { id: 'TKT-991', subject: 'Refund Query', status: 'resolved', date: 'Jul 05, 2026' },
            { id: 'TKT-885', subject: 'Exchange Size issue', status: 'open', date: 'Jul 08, 2026' }
        ],
        showSupportModal: false,
        supportForm: { subject: '', priority: 'medium', message: '' },
        submitTicket() {
            if(!this.supportForm.subject || !this.supportForm.message) {
                this.triggerToast('Please fill out the ticket fields.', 'error');
                return;
            }
            this.tickets.unshift({
                id: 'TKT-' + Math.floor(100 + Math.random() * 900),
                subject: this.supportForm.subject,
                status: 'open',
                date: 'Just now'
            });
            this.showSupportModal = false;
            this.triggerToast('Support ticket raised successfully.', 'success');
        },
        downloadInvoice(orderNum) {
            this.triggerToast('Preparing invoice download for #' + orderNum + '...', 'info');
            setTimeout(() => {
                this.triggerToast('Invoice #' + orderNum + ' downloaded successfully.', 'success');
            }, 1000);
        },
        applyCoupon(code) {
            this.triggerToast('Applying coupon: ' + code + '...', 'info');
            setTimeout(() => {
                this.triggerToast('Coupon ' + code + ' applied successfully!', 'success');
                window.location.href = '{{ route('store.shop') }}?applied_coupon=' + encodeURIComponent(code);
            }, 1000);
        },
        quickViewProduct: null,
        showQuickViewModal: false,
        openQuickView(product) {
            this.quickViewProduct = product;
            this.showQuickViewModal = true;
        },
        closeQuickView() {
            this.showQuickViewModal = false;
            this.quickViewProduct = null;
        }
    }"
>
    <!-- POLISHED HERO GREETING BANNER -->
    <div class="mb-6 bg-[#FBF8F3] border border-[#F6ECD9] rounded-[24px] p-4 sm:p-6 lg:p-8 relative overflow-hidden flex flex-col lg:flex-row lg:items-center justify-between gap-4 lg:gap-8 hover:shadow-md transition-all duration-300 group/hero">
        {{-- Decorative luxury background pattern --}}
        <div class="absolute inset-0 opacity-[0.03] bg-[radial-gradient(#B88A44_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
        <div class="absolute -right-24 -bottom-24 w-72 h-72 rounded-full bg-brand-200/10 blur-3xl group-hover/hero:scale-110 transition duration-700 pointer-events-none"></div>

        {{-- Left side information --}}
        <div class="space-y-3 lg:space-y-4 max-w-xl relative z-10">
            <div class="space-y-1">
                <span class="inline-flex items-center rounded-full bg-brand-500/10 border border-brand-500/20 px-2.5 py-0.5 text-[9px] font-bold text-brand-700 uppercase tracking-widest leading-none">
                    ⭐ VIP Member
                </span>
                <h1 class="font-serif font-black text-xl sm:text-2xl lg:text-3xl text-gray-900 leading-tight">
                    Good Morning, <br class="sm:hidden">
                    <span class="text-brand-500">{{ $user->name }}</span> 👋
                </h1>
            </div>

            {{-- Premium Badges Section --}}
            <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                <span class="inline-flex items-center gap-1 rounded-lg bg-white border border-gray-150 px-2.5 py-1 text-[10px] font-bold text-gray-650 shadow-sm hidden sm:inline-flex">
                    <span class="text-gray-400">Since:</span> {{ $user->created_at->format('M Y') }}
                </span>
                <span class="inline-flex items-center gap-1 rounded-lg bg-white border border-gray-150 px-2.5 py-1 text-[10px] font-bold text-gray-650 shadow-sm hidden xs:inline-flex">
                    <span class="text-gray-400">Last Order:</span> #{{ $orders->first()->order_number ?? 'None' }}
                </span>
                <span class="inline-flex items-center gap-1 rounded-lg bg-white border border-gray-150 px-2.5 py-1 text-[10px] font-bold text-gray-650 shadow-sm">
                    <span class="text-gray-400">Saved:</span> ₹{{ number_format($orders->sum('discount'), 2) }}
                </span>
                <span class="inline-flex items-center gap-1 rounded-lg bg-white border border-gray-150 px-2.5 py-1 text-[10px] font-bold text-gray-650 shadow-sm">
                    <span class="text-gray-400">Tier:</span> Gold
                </span>
            </div>
            
            {{-- Quick navigation shortcut chips --}}
            <div class="flex flex-wrap gap-2 pt-1">
                <a href="{{ route('store.shop') }}" class="inline-flex items-center justify-center rounded-xl bg-white hover:bg-brand-50 border border-gray-150 hover:border-brand-200 px-4 h-12 text-[10px] font-bold text-gray-700 hover:text-brand-500 transition shadow-sm hover:shadow">
                    Continue Shopping
                </a>
                <button @click="changeTab('orders')" class="inline-flex items-center justify-center rounded-xl bg-white hover:bg-brand-50 border border-gray-150 hover:border-brand-200 px-4 h-12 text-[10px] font-bold text-gray-700 hover:text-brand-500 transition shadow-sm hover:shadow">
                    Track Orders
                </button>
            </div>
        </div>

        {{-- Right side membership info card --}}
        <div class="w-full lg:w-[350px] relative z-10 shrink-0">
            <x-reward-card 
                points="1250" 
                tier="Gold Member" 
                nextTier="Platinum Member" 
                progress="68" 
                pointsNeeded="250" 
            />
        </div>
    </div>

    <!-- MAIN TWO COLUMN RESPONSIVE GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT SIDEBAR NAVIGATION -->
        <aside class="hidden lg:block lg:col-span-3 sticky top-24 bg-white border border-gray-150 rounded-[24px] p-4 space-y-2 shadow-sm">
            <span class="block text-[9px] font-bold uppercase tracking-widest text-gray-400 px-3 mb-2">Membership Portal</span>
            <nav class="space-y-1">
                @foreach([
                    ['id' => 'dashboard', 'name' => 'Dashboard Overview', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />'],
                    ['id' => 'orders', 'name' => 'My Purchase History', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />'],
                    ['id' => 'wishlist', 'name' => 'Saved Collection', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />'],
                    ['id' => 'addresses', 'name' => 'Saved Addresses', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />'],
                    ['id' => 'cards', 'name' => 'Payment Cards', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />'],
                    ['id' => 'coupons', 'name' => 'Available Coupons', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />'],
                    ['id' => 'rewards', 'name' => 'Rewards Wallet', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                    ['id' => 'reviews', 'name' => 'Product Feedback', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />'],
                    ['id' => 'notifications', 'name' => 'Notification Center', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />'],
                    ['id' => 'help', 'name' => 'Support Concierge', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />'],
                    ['id' => 'settings', 'name' => 'Account Settings', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />']
                ] as $item)
                    <button 
                        @click="changeTab('{{ $item['id'] }}')"
                        :class="activeTab === '{{ $item['id'] }}' 
                            ? 'bg-brand-50 text-brand-500 font-bold border-l-2 border-brand-500 pl-3.5' 
                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 border-l-2 border-transparent pl-4'"
                        class="w-full flex items-center justify-between h-10 rounded-r-xl transition-all duration-200 group text-left focus:outline-none"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-brand-500 transition-colors" :class="activeTab === '{{ $item['id'] }}' ? 'text-brand-500' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                {!! $item['icon'] !!}
                            </svg>
                            <span class="text-[10px] font-bold uppercase tracking-widest">{{ $item['name'] }}</span>
                        </div>
                        <svg class="w-3 h-3 text-gray-300 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @endforeach
 
                <div class="h-px bg-gray-100 my-3"></div>
 
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 h-10 rounded-xl text-red-500 hover:bg-red-50 transition focus:outline-none">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- RIGHT FLUID MAIN CONTENT WINDOW -->
        <main class="lg:col-span-9">

            {{-- DYNAMIC SKELETON LOADER PANEL --}}
            <div x-show="tabLoading" class="space-y-8 min-h-[50vh] pb-12" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak>
                <template x-if="activeTab === 'dashboard'">
                    <div class="space-y-8">
                        <x-loading-skeleton type="cards" />
                        <x-loading-skeleton type="orders" />
                    </div>
                </template>
                <template x-if="activeTab === 'orders'">
                    <x-loading-skeleton type="orders" />
                </template>
                <template x-if="activeTab === 'wishlist'">
                    <x-loading-skeleton type="products" />
                </template>
                <template x-if="['addresses', 'cards', 'coupons', 'rewards'].includes(activeTab)">
                    <x-loading-skeleton type="cards" />
                </template>
                <template x-if="['reviews', 'notifications', 'help', 'settings'].includes(activeTab)">
                    <x-loading-skeleton type="default" />
                </template>
            </div>

            {{-- ACTUAL TAB CONTENTS WITH PREMIUM TRANSITIONS --}}
            <div x-show="!tabLoading" class="space-y-10" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak>
                
                <!-- TAB: DASHBOARD OVERVIEW -->
                <div x-show="activeTab === 'dashboard'" class="space-y-10" x-cloak>
                
                {{-- STATS CARDS --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                    <x-dashboard-card 
                        title="Total Orders" 
                        value="{{ $orders->count() }}" 
                        description="View order histories" 
                        trend="+3 new"
                        :trendUp="true"
                    >
                        <x-slot name="icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </x-slot>
                    </x-dashboard-card>

                    <x-dashboard-card 
                        title="Wishlist" 
                        value="{{ $wishlist->count() }}" 
                        description="Saved products" 
                        trend="In stock"
                        :trendUp="true"
                    >
                        <x-slot name="icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </x-slot>
                    </x-dashboard-card>

                    <x-dashboard-card 
                        title="Saved Coupons" 
                        value="{{ $coupons->count() }}" 
                        description="Claim exclusive discounts" 
                        trend="Save up to 20%"
                        :trendUp="true"
                    >
                        <x-slot name="icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </x-slot>
                    </x-dashboard-card>

                    <x-dashboard-card 
                        title="Rewards Wallet" 
                        value="1,250" 
                        description="Points available" 
                        trend="+150 pts this month"
                        :trendUp="true"
                    >
                        <x-slot name="icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </x-slot>
                    </x-dashboard-card>
                </div>

                {{-- SECTION 1: QUICK ACTIONS --}}
                <div class="space-y-6">
                    <h3 class="font-serif font-bold text-xl text-gray-900 tracking-tight">Quick Actions Portal</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        @foreach([
                            ['id' => 'shop', 'title' => 'Shop Store', 'desc' => 'Explore luxury items', 'route' => 'store.shop', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />', 'tab' => null, 'bg' => 'bg-amber-50 border-amber-100 text-brand-500'],
                            ['id' => 'track', 'title' => 'Track Orders', 'desc' => 'Active shipment logs', 'route' => null, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />', 'tab' => 'orders', 'bg' => 'bg-blue-50 border-blue-100 text-blue-500'],
                            ['id' => 'wish', 'title' => 'Saved Collection', 'desc' => 'Saved style vault', 'route' => null, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />', 'tab' => 'wishlist', 'bg' => 'bg-rose-50 border-rose-100 text-rose-500'],
                            ['id' => 'deals', 'title' => 'Exclusive Deals', 'desc' => 'Redeem cart savings', 'route' => null, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />', 'tab' => 'coupons', 'bg' => 'bg-purple-50 border-purple-100 text-purple-500'],
                            ['id' => 'returns', 'title' => 'Returns Policy', 'desc' => 'Refund queries desk', 'route' => null, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 15.89M9 11l3-3m0 0l3 3m-3-3v12" />', 'tab' => 'help', 'bg' => 'bg-orange-50 border-orange-100 text-orange-500'],
                            ['id' => 'support', 'title' => 'Help Desk', 'desc' => '24/7 client managers', 'route' => null, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />', 'tab' => 'help', 'bg' => 'bg-emerald-50 border-emerald-100 text-emerald-500']
                        ] as $act)
                            <div 
                                @click="{{ $act['tab'] ? "changeTab('" . $act['tab'] . "')" : "window.location='" . ($act['route'] ? route($act['route']) : '#') . "'" }}"
                                class="bg-white border border-gray-150 rounded-[20px] p-5 hover:border-brand-300 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer flex flex-col justify-between h-[140px] group/act"
                            >
                                <div class="w-10 h-10 rounded-xl {{ $act['bg'] }} flex items-center justify-center transition-transform duration-300 group-hover/act:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        {!! $act['icon'] !!}
                                    </svg>
                                </div>
                                <div class="space-y-1 relative">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-[11px] font-bold uppercase tracking-widest text-gray-900 group-hover/act:text-brand-500 transition-colors">{{ $act['title'] }}</h4>
                                        <svg class="w-3.5 h-3.5 text-gray-400 group-hover/act:translate-x-1 group-hover/act:text-brand-500 transition-all" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-semibold leading-relaxed truncate">{{ $act['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- SECTION 2: RECENT ORDERS --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="font-serif font-bold text-xl text-gray-900 tracking-tight">Recent Consignments</h2>
                        <button @click="activeTab = 'orders'" class="text-xs font-bold text-brand-500 hover:text-brand-650 hover:underline flex items-center gap-1">
                            <span>All History</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        @forelse($orders->take(2) as $order)
                            <x-order-card :order="$order" />
                        @empty
                            <x-empty-state 
                                title="No Orders Placed Yet" 
                                message="Start exploring our curated premium brand catalog." 
                                actionText="Shop Now"
                                actionRoute="store.shop"
                                type="orders"
                            />
                        @endforelse
                    </div>
                </div>

                {{-- SECTION 5: ACTIVE COUPONS --}}
                <div class="space-y-6 pt-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-serif font-bold text-xl text-gray-900 tracking-tight">Active Coupons & Deals</h2>
                            <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">Claim secret discount codes at checkout</p>
                        </div>
                        <button @click="activeTab = 'coupons'" class="text-xs font-bold text-brand-500 hover:text-brand-650 hover:underline flex items-center gap-1">
                            <span>Manage Coupons</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    @if($coupons->isNotEmpty())
                        <div class="flex gap-4 overflow-x-auto no-scrollbar pb-4 pt-1 snap-x scroll-smooth -mx-4 px-4 sm:mx-0 sm:px-0 sm:grid sm:grid-cols-2 lg:grid-cols-3 sm:gap-6">
                            @foreach($coupons->take(3) as $idx => $coupon)
                                <div class="w-[280px] sm:w-auto shrink-0 snap-start">
                                    <x-coupon-card :coupon="$coupon" :index="$idx" />
                                </div>
                            @endforeach
                        </div>
                    @else
                        <x-empty-state 
                            title="No Coupons Vaulted" 
                            message="Join our newsletter and VIP channel to get monthly savings code vouchers." 
                            actionText="Browse Shop"
                            actionRoute="store.shop"
                            type="coupons"
                        />
                    @endif
                </div>

                {{-- SECTION 6: REWARDS & MEMBERSHIP --}}
                <div class="space-y-6 pt-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-serif font-bold text-xl text-gray-900 tracking-tight">Rewards & Membership</h2>
                            <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">Track your VIP loyalty status and saving growth</p>
                        </div>
                        <button @click="activeTab = 'rewards'" class="text-xs font-bold text-brand-500 hover:text-brand-650 hover:underline flex items-center gap-1">
                            <span>Points Wallet</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Points and Tier Card -->
                        <div class="lg:col-span-2 bg-[#FBF8F3] border border-[#F6ECD9] rounded-[24px] p-6 flex flex-col justify-between shadow-sm relative overflow-hidden group">
                            <div class="absolute inset-0 opacity-[0.03] bg-[radial-gradient(#B88A44_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
                            <div class="absolute -right-20 -bottom-20 w-48 h-48 rounded-full bg-brand-200/10 blur-2xl group-hover:scale-110 transition duration-700 pointer-events-none"></div>

                            <div class="flex flex-col sm:flex-row justify-between gap-6 border-b border-brand-200/20 pb-5">
                                <div class="space-y-1.5">
                                    <span class="inline-flex items-center rounded-full bg-brand-500/10 border border-brand-500/20 px-2.5 py-0.5 text-[9px] font-bold text-brand-700 uppercase tracking-widest">
                                        ⭐ VIP Gold Level
                                    </span>
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Available Points Balance</span>
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="font-serif font-black text-4xl text-gray-900 tracking-tight">1,250</span>
                                        <span class="text-xs font-bold text-brand-500 uppercase tracking-wider">PTS</span>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest text-left sm:text-right">Lifetime Savings</span>
                                    <div class="flex items-baseline gap-1 sm:justify-end">
                                        <span class="font-serif font-black text-4xl text-gray-900 tracking-tight">₹12,450</span>
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-semibold tracking-wide text-left sm:text-right">Saved via VIP codes & rewards</p>
                                </div>
                            </div>

                            <div class="pt-5 space-y-3.5">
                                <div class="flex items-center justify-between text-xs font-semibold">
                                    <span class="text-gray-900 font-bold uppercase tracking-wider text-[10px]">Next Tier Progress (Platinum)</span>
                                    <span class="text-brand-500">68% Completed</span>
                                </div>
                                
                                <div class="w-full h-2.5 bg-gray-200/50 border border-gray-150 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand-500 rounded-full transition-all duration-1000" style="width: 68%"></div>
                                </div>

                                <p class="text-[11px] text-gray-400 font-semibold leading-relaxed">Earn 250 more points by shopping or writing reviews to unlock Platinum Concierge benefits (FREE Express Shipping, 10% Flat VIP Discounts).</p>
                            </div>
                        </div>

                        <!-- Infographic Info panel / Quick Rewards Log -->
                        <div class="bg-white border border-gray-150 rounded-[24px] p-6 space-y-4 shadow-sm">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-gray-450">Point Allocation History</h4>
                            
                            <div class="space-y-3">
                                @foreach([
                                    ['name' => 'Order #ORD-987541', 'date' => 'Jul 09, 2026', 'val' => '+150 PTS', 'color' => 'text-emerald-600 bg-emerald-50 border-emerald-100'],
                                    ['name' => 'Review: G-Shock Metal', 'date' => 'Jul 05, 2026', 'val' => '+50 PTS', 'color' => 'text-emerald-600 bg-emerald-50 border-emerald-100'],
                                    ['name' => 'Anniversary Bonus', 'date' => 'Jun 15, 2026', 'val' => '+500 PTS', 'color' => 'text-emerald-600 bg-emerald-50 border-emerald-100'],
                                    ['name' => 'Order #ORD-845112', 'date' => 'Jun 10, 2026', 'val' => '+550 PTS', 'color' => 'text-emerald-600 bg-emerald-50 border-emerald-100']
                                ] as $log)
                                    <div class="flex items-center justify-between gap-3 p-2 rounded-xl bg-gray-50 border border-gray-100">
                                        <div class="min-w-0">
                                            <p class="text-[11px] font-bold text-gray-900 truncate leading-snug">{{ $log['name'] }}</p>
                                            <p class="text-[9px] text-gray-400 font-semibold">{{ $log['date'] }}</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-lg px-2 py-0.5 text-[9px] font-mono font-bold border {{ $log['color'] }}">{{ $log['val'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 7: NOTIFICATIONS --}}
                <div class="space-y-6 pt-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-serif font-bold text-xl text-gray-900 tracking-tight">Notification Center</h2>
                            <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">Real-time alerts, logistics updates, and promotional drops</p>
                        </div>
                        <button @click="activeTab = 'notifications'" class="text-xs font-bold text-brand-500 hover:text-brand-650 hover:underline flex items-center gap-1">
                            <span>Notification Vault</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <div class="bg-white border border-gray-150 rounded-[24px] divide-y divide-gray-100 overflow-hidden shadow-sm">
                        <!-- Use notifications state array in dynamic template for real-time reactivity -->
                        <template x-for="(notif, idx) in notifications.slice(0, 3)" :key="idx">
                            <div class="p-5 flex items-start gap-4 hover:bg-gray-50/50 transition-all duration-200 group">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 transition-colors"
                                    :class="
                                        notif.category === 'order' ? 'bg-amber-50 border-amber-100 text-brand-500' :
                                        notif.category === 'promo' ? 'bg-indigo-50 border-indigo-100 text-indigo-500' :
                                        'bg-gray-50 border-gray-100 text-gray-400'
                                    "
                                >
                                    <template x-if="notif.category === 'order'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </template>
                                    <template x-if="notif.category === 'promo'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </template>
                                    <template x-if="notif.category !== 'order' && notif.category !== 'promo'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    </template>
                                </div>
                                <div class="flex-grow min-w-0 space-y-1">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h4 class="text-xs text-gray-900 leading-normal" :class="!notif.is_read ? 'font-bold' : 'font-semibold'" x-text="notif.title"></h4>
                                            <template x-if="!notif.is_read">
                                                <span class="inline-flex items-center rounded-full bg-brand-500/10 px-2 py-0.5 text-[8px] font-bold text-brand-650 tracking-wider uppercase">New</span>
                                            </template>
                                        </div>
                                        <span class="text-[9px] text-gray-400 font-bold whitespace-nowrap pt-0.5" x-text="notif.time"></span>
                                    </div>
                                    <p class="text-xs text-gray-450 leading-relaxed font-semibold" x-text="notif.message"></p>
                                    
                                    <div class="pt-2 flex items-center gap-3">
                                        <template x-if="!notif.is_read">
                                            <button @click="markAsRead(idx)" class="text-[9px] font-bold text-brand-500 hover:text-brand-650 transition tracking-wider uppercase focus:outline-none">
                                                Mark as Read
                                            </button>
                                        </template>
                                        <template x-if="!notif.is_read">
                                            <div class="w-1 h-1 rounded-full bg-gray-200"></div>
                                        </template>
                                        <button @click="deleteNotification(idx)" class="text-[9px] font-bold text-red-500 hover:text-red-650 transition tracking-wider uppercase focus:outline-none">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="notifications.length === 0" class="p-6">
                            <x-empty-state 
                                title="No Alerts in Vault" 
                                message="All caught up! New order tracker notifications and discount deals will appear here." 
                                actionText="Shop Campaign"
                                actionRoute="store.shop"
                                type="notifications"
                            />
                        </div>
                    </div>
                </div>

                {{-- SECTION 8: ACCOUNT SECURITY --}}
                <div class="space-y-6 pt-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-serif font-bold text-xl text-gray-900 tracking-tight">Account Security Center</h2>
                            <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">Secure your authentication profile factors and active access logins</p>
                        </div>
                        <button @click="activeTab = 'settings'" class="text-xs font-bold text-brand-500 hover:text-brand-650 hover:underline flex items-center gap-1">
                            <span>Edit Settings</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <div class="bg-white border border-gray-150 rounded-[24px] p-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-8 group/security">
                        <!-- Info Grid -->
                        <div class="flex-grow w-full space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="flex items-center justify-between p-3.5 bg-gray-50 border border-gray-100 rounded-[20px] hover:border-brand-200 transition duration-300">
                                    <div class="min-w-0">
                                        <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Two-Factor Authentication</span>
                                        <span class="block text-xs font-bold text-gray-900">Disabled (Recommended)</span>
                                    </div>
                                    <span class="inline-flex items-center rounded-full bg-amber-50 border border-amber-100 px-2.5 py-0.5 text-[8px] font-bold text-amber-700 tracking-wider">OFF</span>
                                </div>
                                <div class="flex items-center justify-between p-3.5 bg-gray-50 border border-gray-100 rounded-[20px] hover:border-brand-200 transition duration-300">
                                    <div class="min-w-0">
                                        <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Verified Email</span>
                                        <span class="block text-xs font-bold text-gray-900 truncate max-w-[120px] sm:max-w-xs">{{ auth()->user()->email }}</span>
                                    </div>
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 text-[8px] font-bold text-emerald-700 tracking-wider">VERIFIED</span>
                                </div>
                                <div class="flex items-center justify-between p-3.5 bg-gray-50 border border-gray-100 rounded-[20px] hover:border-brand-200 transition duration-300">
                                    <div class="min-w-0">
                                        <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Trusted Devices</span>
                                        <span class="block text-xs font-bold text-gray-900">Windows PC • Chrome</span>
                                    </div>
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 text-[8px] font-bold text-emerald-700 tracking-wider">ACTIVE</span>
                                </div>
                                <div class="flex items-center justify-between p-3.5 bg-gray-50 border border-gray-100 rounded-[20px] hover:border-brand-200 transition duration-300">
                                    <div class="min-w-0">
                                        <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Security Score</span>
                                        <span class="block text-xs font-bold text-gray-900">85% Secured Profile</span>
                                    </div>
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 text-[8px] font-bold text-emerald-700 tracking-wider">EXCELLENT</span>
                                </div>
                            </div>

                            <p class="text-[10px] text-gray-400 font-semibold leading-relaxed">Recent account activity: Password changed 3 months ago. Verified login session authenticated from Pune, India.</p>
                        </div>

                        <!-- Radial Score Infographic -->
                        <div class="w-32 h-32 shrink-0 relative flex items-center justify-center border border-gray-100 rounded-full bg-gray-50 shadow-inner group-hover/security:scale-105 transition duration-500">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                <circle class="text-gray-200" stroke-width="6" stroke="currentColor" fill="transparent" r="40" cx="50" cy="50" />
                                <circle class="text-brand-500 transition-all duration-1000" stroke-width="6" stroke-dasharray="251.2" stroke-dashoffset="37.6" stroke-linecap="round" stroke="currentColor" fill="transparent" r="40" cx="50" cy="50" />
                            </svg>
                            <div class="absolute text-center">
                                <span class="block font-serif font-black text-2xl text-gray-900 leading-none">85%</span>
                                <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1 block">SECURE</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 9: HELP CENTER & SUPPORT --}}
                <div class="space-y-6 pt-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-serif font-bold text-xl text-gray-900 tracking-tight">Help Center & Support Concierge</h2>
                            <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">Find immediate answers or speak to our customer managers</p>
                        </div>
                        <button @click="showSupportModal = true" class="px-4 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-[11px] font-bold text-white transition shadow-md shadow-brand-500/10">
                            Raise Support Ticket
                        </button>
                    </div>

                    <!-- Grid of support options -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach([
                            ['title' => 'Live Chat', 'desc' => 'Available 24x7', 'action' => "triggerToast('Connecting to a live concierge manager...', 'info')", 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />', 'bg' => 'bg-emerald-50/50 hover:border-emerald-300 text-emerald-600'],
                            ['title' => 'Raise Ticket', 'desc' => 'Response in 1hr', 'action' => "showSupportModal = true", 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />', 'bg' => 'bg-blue-50/50 hover:border-blue-300 text-blue-600'],
                            ['title' => 'Returns Policy', 'desc' => '14 days return policy', 'action' => "triggerToast('Return center policy: Items can be returned within 14 days with tags intact.', 'info')", 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 15.89M9 11l3-3m0 0l3 3m-3-3v12" />', 'bg' => 'bg-orange-50/50 hover:border-orange-300 text-orange-650'],
                            ['title' => 'Refunds & Claims', 'desc' => 'Track money flow', 'action' => "triggerToast('Refund center: Standard refunds processed to original source within 5-7 business days.', 'info')", 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />', 'bg' => 'bg-purple-50/50 hover:border-purple-300 text-purple-650'],
                            ['title' => 'Contact Support', 'desc' => 'concierge@shopme.com', 'action' => "window.location.href = 'mailto:concierge@shopme.com'", 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />', 'bg' => 'bg-pink-50/50 hover:border-pink-300 text-pink-600'],
                            ['title' => 'Shipping Policy', 'desc' => 'Express air tracking', 'action' => "triggerToast('Shipping Policy: Free air express shipping on all customer orders above ₹1,000.', 'info')", 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />', 'bg' => 'bg-yellow-50/50 hover:border-yellow-300 text-yellow-600'],
                            ['title' => 'Phone Helpline', 'desc' => '1-800-555-SHOP', 'action' => "window.location.href = 'tel:18005557467'", 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />', 'bg' => 'bg-teal-50/50 hover:border-teal-300 text-teal-600'],
                            ['title' => 'FAQ Concierge', 'desc' => 'Self-help queries', 'action' => "activeTab = 'help'", 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 3v-1.5m0 3v-1.5m0 3v-1.5m-9-3h18" />', 'bg' => 'bg-amber-50/50 hover:border-amber-300 text-amber-600']
                        ] as $scard)
                            <div 
                                @click="{{ $scard['action'] }}"
                                class="bg-white border border-gray-150 rounded-[20px] p-4 text-center cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex flex-col items-center justify-center space-y-2 group/scard {{ $scard['bg'] }}"
                            >
                                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center border border-gray-150 transition-colors shadow-sm group-hover/scard:text-brand-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        {!! $scard['icon'] !!}
                                    </svg>
                                </div>
                                <div class="space-y-0.5">
                                    <h4 class="text-[11px] font-bold text-gray-900">{{ $scard['title'] }}</h4>
                                    <p class="text-[9px] text-gray-400 font-semibold leading-none">{{ $scard['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- FAQs List Accordion -->
                    <div class="bg-white border border-gray-150 rounded-[24px] p-6 space-y-4 shadow-sm">
                        <h3 class="font-serif font-bold text-lg text-gray-900">Frequently Asked Questions</h3>
                        <div class="space-y-3" x-data="{ activeFaq: null }">
                            @foreach([
                                ['q' => 'How can I return or exchange my premium style product?', 'a' => 'Simply contact our support concierge or raise a return ticket in the help desk within 14 days of delivery. Keep original clothing labels and luxury boxes intact.'],
                                ['q' => 'Is international air-express shipment tracked?', 'a' => 'Yes, every shipment travels via premier express carriers (DHL/FedEx). Tracking timelines are updated live on your order card tracker button.'],
                                ['q' => 'How do I redeem my loyalty rewards wallet points?', 'a' => 'Reward points are redeemable directly during checkout as direct flat discount coupons. Every 100 points matches a ₹10 cart deduction.']
                            ] as $fidx => $faqItem)
                                <div class="border border-gray-150 rounded-2xl overflow-hidden transition-all duration-300">
                                    <button 
                                        @click="activeFaq = (activeFaq === {{ $fidx }} ? null : {{ $fidx }})" 
                                        class="w-full flex items-center justify-between p-4 text-left font-bold text-xs sm:text-sm text-gray-900 focus:outline-none bg-gray-50/50 hover:bg-gray-50 transition"
                                    >
                                        <span>{{ $faqItem['q'] }}</span>
                                        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-300" :class="activeFaq === {{ $fidx }} ? 'rotate-180 text-brand-500' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="activeFaq === {{ $fidx }}" x-collapse class="px-4 pb-4 pt-1 text-xs text-gray-450 leading-relaxed font-semibold">
                                        {{ $faqItem['a'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- TAB: MY ORDERS -->
            <div x-show="activeTab === 'orders'" class="space-y-6" x-cloak>
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="font-serif font-bold text-2xl text-gray-900 tracking-tight">Order History</h2>
                    <p class="text-xs text-gray-400 font-medium">Manage and track your premium orders details.</p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    @forelse($orders as $order)
                        <x-order-card :order="$order" />
                    @empty
                        <x-empty-state 
                            title="No Orders Placed" 
                            message="You haven't bought anything yet. Explore our top brand listings today." 
                            actionText="Browse Shop"
                        />
                    @endforelse
                </div>
            </div>

            <!-- TAB: WISHLIST -->
            <div x-show="activeTab === 'wishlist'" class="space-y-6" x-cloak>
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="font-serif font-bold text-2xl text-gray-900 tracking-tight">My Wishlist</h2>
                    <p class="text-xs text-gray-400 font-medium">Curate and save your next premium style acquisitions.</p>
                </div>

                @if($wishlist->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($wishlist as $item)
                            <x-wishlist-card :item="$item" />
                        @endforeach
                    </div>
                @else
                    <x-empty-state 
                        title="Wishlist is Empty" 
                        message="Save items you like and track exclusive drops & restock deals." 
                        actionText="Explore Campaign"
                    />
                @endif
            </div>

            <!-- TAB: SAVED ADDRESSES -->
            <div x-show="activeTab === 'addresses'" class="space-y-6" x-cloak>
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="font-serif font-bold text-2xl text-gray-900 tracking-tight">Saved Addresses</h2>
                        <p class="text-xs text-gray-400 font-medium">Manage multiple delivery coordinates.</p>
                    </div>
                    <button @click="addAddressOpen()" class="inline-flex items-center gap-1.5 rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2.5 text-xs font-bold text-white transition shadow-md shadow-brand-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Add New Address</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-for="(addr, idx) in addresses" :key="idx">
                        <!-- Note: Using dynamic rendering logic since Alpine manages addresses array -->
                        <div class="bg-white rounded-[20px] border border-gray-150 p-6 shadow-sm hover:shadow-md transition-all duration-300 relative flex flex-col justify-between min-h-[180px]">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-gray-900 uppercase tracking-widest">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span x-text="addr.label"></span>
                                    </span>
                                    <span x-show="addr.is_default" class="inline-flex items-center rounded-full bg-brand-50 border border-brand-100 px-2.5 py-0.5 text-[9px] font-bold text-brand-700 tracking-wider">
                                        DEFAULT
                                    </span>
                                </div>
                                <div class="space-y-1 text-sm text-gray-600 font-medium">
                                    <p class="font-bold text-gray-900" x-text="addr.name"></p>
                                    <p class="text-xs text-gray-450 leading-relaxed" x-text="addr.street"></p>
                                    <p class="text-xs text-gray-450 leading-none" x-text="addr.city + ', ' + addr.state + ' - ' + addr.zip"></p>
                                    <p class="text-xs text-gray-450 pt-2 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <span x-text="addr.phone"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                <button @click="editAddress(idx)" class="text-xs font-semibold text-gray-500 hover:text-brand-500 transition">
                                    Edit
                                </button>
                                <div class="w-px h-3 bg-gray-200"></div>
                                <button @click="deleteAddress(idx)" class="text-xs font-semibold text-red-500 hover:text-red-650 transition">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TAB: SAVED CARDS -->
            <div x-show="activeTab === 'cards'" class="space-y-6" x-cloak>
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="font-serif font-bold text-2xl text-gray-900 tracking-tight">Saved Cards</h2>
                        <p class="text-xs text-gray-450 font-medium">Verify secured payment details.</p>
                    </div>
                    <button @click="showCardModal = true" class="inline-flex items-center gap-1.5 rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2.5 text-xs font-bold text-white transition shadow-md shadow-brand-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Add New Card</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-for="card in cards" :key="card.id">
                        <div class="bg-gradient-to-br from-gray-900 to-gray-950 rounded-[24px] p-6 text-white relative overflow-hidden shadow-lg min-h-[180px] flex flex-col justify-between">
                            {{-- card top banner --}}
                            <div class="flex items-start justify-between relative z-10">
                                <div>
                                    <span class="block text-[8px] font-bold uppercase tracking-widest text-gray-400">Card Issuer</span>
                                    <span class="text-sm font-semibold uppercase tracking-wider" x-text="card.brand"></span>
                                </div>
                                <span x-show="card.is_default" class="inline-flex items-center rounded-full bg-white/15 px-2.5 py-0.5 text-[8px] font-bold tracking-wider leading-none">
                                    DEFAULT
                                </span>
                            </div>

                            {{-- card number --}}
                            <div class="relative z-10 my-4">
                                <span class="font-mono text-lg tracking-widest block text-gray-100" x-text="card.number"></span>
                            </div>

                            {{-- card footer details --}}
                            <div class="flex items-end justify-between relative z-10">
                                <div class="space-y-0.5">
                                    <span class="block text-[8px] font-bold uppercase tracking-widest text-gray-500">Card Holder</span>
                                    <span class="text-xs font-bold tracking-wider text-gray-200" x-text="card.holder"></span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div>
                                        <span class="block text-[8px] font-bold uppercase tracking-widest text-gray-500">Expires</span>
                                        <span class="text-xs font-bold text-gray-200" x-text="card.expiry"></span>
                                    </div>
                                    <button @click="deleteCard(card.id)" class="text-red-400 hover:text-red-500 p-1.5 rounded-lg hover:bg-white/5 transition" title="Remove Card">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TAB: MY COUPONS -->
            <div x-show="activeTab === 'coupons'" class="space-y-6" x-cloak>
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="font-serif font-bold text-2xl text-gray-900 tracking-tight">Premium Coupons</h2>
                    <p class="text-xs text-gray-400 font-medium">Use exclusive discount codes for your cart savings.</p>
                </div>

                @if($coupons->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($coupons as $idx => $coupon)
                            <x-coupon-card :coupon="$coupon" :index="$idx" />
                        @endforeach
                    </div>
                @else
                    <x-empty-state 
                        title="No Coupons Available" 
                        message="Join the membership club to acquire monthly store vouchers." 
                        actionText="Shop Now"
                    />
                @endif
            </div>

            <!-- TAB: REWARDS VAULT -->
            <div x-show="activeTab === 'rewards'" class="space-y-8" x-cloak>
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="font-serif font-bold text-2xl text-gray-900 tracking-tight">Rewards Vault</h2>
                    <p class="text-xs text-gray-400 font-medium">Unlock loyalty discount steps and earn badges.</p>
                </div>

                {{-- Detailed Rewards Information --}}
                <div class="bg-white border border-gray-150 rounded-[24px] p-6 sm:p-8 space-y-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between gap-6 border-b border-gray-100 pb-6">
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-gray-450 uppercase tracking-widest block">Available Balance</span>
                            <span class="font-serif font-black text-4xl text-gray-900 tracking-tight">1,250 PTS</span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-gray-450 uppercase tracking-widest block">Lifetime Earned</span>
                            <span class="font-serif font-black text-4xl text-brand-500 tracking-tight">4,850 PTS</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="font-serif font-bold text-lg text-gray-900">How to Earn More Points</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 border border-gray-100 rounded-[20px] p-5 space-y-2">
                                <span class="text-xs font-bold text-gray-900">Shopping Purchase</span>
                                <p class="text-xs text-gray-450 font-medium">Earn 5 points for every ₹100 spent on all luxury tag categories.</p>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-[20px] p-5 space-y-2">
                                <span class="text-xs font-bold text-gray-900">Submit Reviews</span>
                                <p class="text-xs text-gray-450 font-medium">Earn 50 points for every verified product review with images.</p>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-[20px] p-5 space-y-2">
                                <span class="text-xs font-bold text-gray-900">Member Anniversary</span>
                                <p class="text-xs text-gray-450 font-medium">Get 500 bonus points credited to your wallet on every account birthday.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB: REVIEWS -->
            <div x-show="activeTab === 'reviews'" class="space-y-6" x-cloak>
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="font-serif font-bold text-2xl text-gray-900 tracking-tight">My Product Reviews</h2>
                    <p class="text-xs text-gray-400 font-medium">Check ratings & feedback comments you posted.</p>
                </div>

                @if($reviews->isNotEmpty())
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($reviews as $review)
                            <div class="bg-white border border-gray-150 rounded-[20px] p-6 shadow-sm flex flex-col md:flex-row gap-6">
                                {{-- Product info --}}
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 shrink-0 border border-gray-100">
                                    @if($review->product && $review->product->featuredImage)
                                        <img src="{{ asset('storage/' . $review->product->featuredImage->image_path) }}" class="w-full h-full object-cover" alt="">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[10px] font-bold text-gray-400 bg-gray-100">No Image</div>
                                    @endif
                                </div>
                                {{-- Feedback info --}}
                                <div class="flex-grow space-y-2">
                                    <div class="flex items-center justify-between gap-4">
                                        <h4 class="text-sm font-bold text-gray-900 hover:text-brand-500 transition-colors">
                                            @if($review->product)
                                                <a href="{{ route('store.product.show', $review->product->slug) }}">{{ $review->product->name }}</a>
                                            @else
                                                Removed Product
                                            @endif
                                        </h4>
                                        <span class="text-[10px] text-gray-400 font-semibold">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                    {{-- Stars --}}
                                    <div class="flex items-center text-amber-400 gap-0.5">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i < $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="text-xs text-gray-450 leading-relaxed font-medium">{{ $review->comment }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state 
                        title="No Reviews Found" 
                        message="Submit reviews for items you bought and unlock 50 points per feedback." 
                        actionText="Rate Products"
                    />
                @endif
            </div>

            <!-- TAB: NOTIFICATIONS -->
            <div x-show="activeTab === 'notifications'" class="space-y-6" x-cloak>
                <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="font-serif font-bold text-2xl text-gray-900 tracking-tight">Notification Vault</h2>
                        <p class="text-xs text-gray-400 font-medium">Your personalized store updates, tracking alerts, and loyalty rewards.</p>
                    </div>
                    <button x-show="notifications.filter(n => !n.is_read).length > 0" @click="notifications.forEach(n => n.is_read = true); triggerToast('All notifications marked as read.', 'success');" class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-150 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 transition focus:outline-none">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Mark All as Read</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(notif, idx) in notifications" :key="idx">
                        <div 
                            class="relative bg-white border rounded-[20px] p-5 transition-all duration-300 flex items-start gap-4 group"
                            :class="!notif.is_read 
                                ? 'border-brand-200/80 shadow-md shadow-brand-500/[0.02] bg-gradient-to-r from-brand-50/[0.15] to-white' 
                                : 'border-gray-150 hover:shadow-md bg-white'"
                        >
                            {{-- Category Indicator Icon with customized colors --}}
                            <div class="w-12 h-12 rounded-xl shrink-0 flex items-center justify-center border transition-all duration-300"
                                :class="
                                    notif.category === 'order' ? 'bg-amber-50 border-amber-100 text-brand-500' :
                                    notif.category === 'promo' ? 'bg-indigo-50 border-indigo-100 text-indigo-500' :
                                    'bg-gray-50 border-gray-100 text-gray-400'
                                "
                            >
                                <template x-if="notif.category === 'order'">
                                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </template>
                                <template x-if="notif.category === 'promo'">
                                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></template>
                                <template x-if="notif.category !== 'order' && notif.category !== 'promo'">
                                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </template>
                            </div>

                            {{-- Text & Details Area --}}
                            <div class="flex-grow min-w-0 space-y-1">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-sm text-gray-900 leading-snug" :class="!notif.is_read ? 'font-bold' : 'font-semibold'" x-text="notif.title"></h4>
                                        <template x-if="!notif.is_read">
                                            <span class="inline-flex items-center rounded-full bg-brand-500/10 px-2 py-0.5 text-[8px] font-bold text-brand-650 tracking-wider uppercase">New</span>
                                        </template>
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-bold whitespace-nowrap pt-0.5" x-text="notif.time"></span>
                                </div>
                                <p class="text-xs text-gray-450 leading-relaxed font-medium" x-text="notif.message"></p>
                                
                                {{-- Actions --}}
                                <div class="pt-3 flex items-center gap-3">
                                    <template x-if="!notif.is_read">
                                        <button @click="markAsRead(idx)" class="text-[10px] font-bold text-brand-500 hover:text-brand-650 transition tracking-wider uppercase focus:outline-none">
                                            Mark as Read
                                        </button>
                                    </template>
                                    <template x-if="!notif.is_read">
                                        <div class="w-1 h-1 rounded-full bg-gray-200"></div>
                                    </template>
                                    <button @click="deleteNotification(idx)" class="text-[10px] font-bold text-red-500 hover:text-red-650 transition tracking-wider uppercase focus:outline-none">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="notifications.length === 0" class="p-12 text-center text-gray-450 italic font-medium">
                        No notifications to display.
                    </div>
                </div>
            </div>

            <!-- TAB: HELP CENTER -->
            <div x-show="activeTab === 'help'" class="space-y-10" x-cloak>
                <div class="flex flex-col sm:flex-row justify-between sm:items-center border-b border-gray-100 pb-4 gap-4">
                    <div>
                        <h2 class="font-serif font-bold text-2xl text-gray-900 tracking-tight">Help Center</h2>
                        <p class="text-xs text-gray-400 font-medium">Find swift solutions or speak to a support concierge manager.</p>
                    </div>
                    <button @click="showSupportModal = true" class="inline-flex items-center gap-1.5 rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2.5 text-xs font-bold text-white transition shadow-md shadow-brand-500/10">
                        Raise Support Ticket
                    </button>
                </div>

                {{-- Support Action options --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="mailto:support@shopme.com" class="bg-white border border-gray-150 rounded-[20px] p-6 hover:shadow-md transition duration-300 space-y-4">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center text-brand-500 border border-brand-100/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-widest">Email Support</h4>
                            <p class="text-[10px] text-gray-400 font-medium">support@shopme.com</p>
                        </div>
                    </a>

                    <a href="tel:+18005559876" class="bg-white border border-gray-150 rounded-[20px] p-6 hover:shadow-md transition duration-300 space-y-4">
                        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600 border border-green-100/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-widest">Call Concierge</h4>
                            <p class="text-[10px] text-gray-400 font-medium">+1 (800) 555-9876</p>
                        </div>
                    </a>

                    <div class="bg-white border border-gray-150 rounded-[20px] p-6 hover:shadow-md transition duration-300 space-y-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-widest">Live Chat</h4>
                            <p class="text-[10px] text-gray-400 font-medium">Available 24x7</p>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-150 rounded-[20px] p-6 hover:shadow-md transition duration-300 space-y-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-widest">Active Tickets</h4>
                            <p class="text-[10px] text-gray-400 font-medium" x-text="tickets.length + ' support queries'"></p>
                        </div>
                    </div>
                </div>

                {{-- Support tickets list --}}
                <div class="space-y-4">
                    <h3 class="font-serif font-bold text-xl text-gray-900 tracking-tight">Your Support Tickets</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <template x-for="t in tickets" :key="t.id">
                            <div class="bg-white border border-gray-150 rounded-[20px] p-5 hover:border-brand-200 hover:shadow-md transition-all duration-300 shadow-sm flex flex-col justify-between gap-4 group">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono text-xs text-gray-900 font-bold" x-text="t.id"></span>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider"
                                        :class="t.status === 'resolved' ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-brand-50 text-brand-500 border border-brand-100'">
                                        <span x-text="t.status"></span>
                                    </span>
                                </div>
                                <h4 class="font-serif font-bold text-sm text-gray-900 leading-snug" x-text="t.subject"></h4>
                                <div class="flex items-center justify-between text-[10px] text-gray-400 font-semibold pt-2 border-t border-gray-100">
                                    <span>Date Raised</span>
                                    <span class="font-bold text-gray-750" x-text="t.date"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- FAQs --}}
                <div class="space-y-6">
                    <h3 class="font-serif font-bold text-xl text-gray-900 tracking-tight">Frequently Asked Questions</h3>
                    <div class="space-y-4" x-data="{ activeFaq: null }">
                        @foreach([
                            ['q' => 'What is the standard shipping timeframe?', 'a' => 'Our luxury products are shipped with express air delivery. Standard shipments arrive within 2-4 business days.'],
                            ['q' => 'How does the Return Process work?', 'a' => 'You can request returns or size exchanges within 14 days of delivery. Keep tags attached and products unused.'],
                            ['q' => 'How can I redeem my loyalty reward points?', 'a' => 'Reward points can be converted directly into discount coupons at the checkout stage. Every 100 points equals ₹10 discount.']
                        ] as $faqIdx => $faqItem)
                            <div class="bg-white border border-gray-150 rounded-[20px] overflow-hidden transition-all">
                                <button @click="activeFaq = (activeFaq === {{ $faqIdx }} ? null : {{ $faqIdx }})" class="w-full flex items-center justify-between p-5 text-left font-bold text-sm text-gray-900 focus:outline-none">
                                    <span>{{ $faqItem['q'] }}</span>
                                    <svg class="w-4 h-4 text-gray-400 transition" :class="activeFaq === {{ $faqIdx }} ? 'rotate-180 text-brand-500' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="activeFaq === {{ $faqIdx }}" class="px-5 pb-5 pt-0 text-xs text-gray-450 leading-relaxed font-medium">
                                    {{ $faqItem['a'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- TAB: PROFILE SETTINGS -->
            <div x-show="activeTab === 'settings'" class="space-y-10" x-cloak>
                <div class="border-b border-gray-100 pb-4">
                    <h2 class="font-serif font-bold text-2xl text-gray-900 tracking-tight">Profile Settings</h2>
                    <p class="text-xs text-gray-400 font-medium">Manage your personal information, address, and credentials.</p>
                </div>

                {{-- Account Details Form --}}
                <div class="bg-white border border-gray-150 rounded-[24px] p-6 sm:p-8 space-y-8 shadow-sm">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PATCH')

                        {{-- Avatar settings --}}
                        <div class="flex flex-col sm:flex-row sm:items-center gap-6" x-data="{
                            previewUrl: '{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=B88A44&color=ffffff&size=128' }}',
                            cameraStream: null,
                            showCameraModal: false,
                            avatarBase64: '',
                            
                            triggerFileInput() {
                                this.avatarBase64 = '';
                                this.$refs.avatarFile.click();
                            },
                            handleFileChange(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    this.avatarBase64 = '';
                                    this.previewUrl = URL.createObjectURL(file);
                                }
                            },
                            async startCamera() {
                                this.showCameraModal = true;
                                try {
                                    this.cameraStream = await navigator.mediaDevices.getUserMedia({ video: { width: 400, height: 400 } });
                                    this.$refs.video.srcObject = this.cameraStream;
                                    this.$refs.video.play();
                                } catch (err) {
                                    alert('Unable to access camera. Please allow camera permissions.');
                                    this.showCameraModal = false;
                                }
                            },
                            stopCamera() {
                                if (this.cameraStream) {
                                    this.cameraStream.getTracks().forEach(track => track.stop());
                                }
                                this.showCameraModal = false;
                            },
                            capturePhoto() {
                                const canvas = this.$refs.canvas;
                                const video = this.$refs.video;
                                canvas.width = 400;
                                canvas.height = 400;
                                const ctx = canvas.getContext('2d');
                                // Draw mirror image
                                ctx.translate(400, 0);
                                ctx.scale(-1, 1);
                                ctx.drawImage(video, 0, 0, 400, 400);
                                
                                // Set base64 for reliable submission
                                const dataUrl = canvas.toDataURL('image/png');
                                this.avatarBase64 = dataUrl;
                                
                                canvas.toBlob((blob) => {
                                    const file = new File([blob], 'captured-avatar.png', { type: 'image/png' });
                                    try {
                                        const container = new DataTransfer();
                                        container.items.add(file);
                                        this.$refs.avatarFile.files = container.files;
                                    } catch (e) {
                                        console.log('DataTransfer fallback.');
                                    }
                                    
                                    this.previewUrl = URL.createObjectURL(blob);
                                    this.stopCamera();
                                }, 'image/png');
                            }
                        }">
                            <div class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-[24px] bg-[#B88A44] border-2 border-brand-100 overflow-hidden shadow-lg group/avatar">
                                <img :src="previewUrl" class="w-full h-full object-cover group-hover/avatar:scale-105 transition-transform duration-500" alt="">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/avatar:opacity-100 flex items-center justify-center transition-all duration-200">
                                    <button type="button" @click="triggerFileInput()" class="text-white hover:text-brand-200 transition" title="Upload Photo">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <span class="block text-xs font-bold text-gray-900 uppercase tracking-widest leading-none">Profile Image Container</span>
                                <p class="text-[10px] text-gray-400 font-medium max-w-xs leading-normal">Upload a high resolution JPEG/PNG files or take a fresh selfie via camera stream.</p>
                                
                                <div class="flex flex-wrap items-center gap-3">
                                    <input type="file" x-ref="avatarFile" name="avatar" class="hidden" accept="image/*" @change="handleFileChange($event)">
                                    <input type="hidden" name="avatar_base64" :value="avatarBase64">
                                    
                                    <button type="button" @click="triggerFileInput()" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-xs font-bold text-white rounded-xl transition shadow-md shadow-brand-500/10 focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span>Upload File</span>
                                    </button>

                                    <button type="button" @click="startCamera()" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-900 hover:bg-gray-850 text-xs font-bold text-white rounded-xl transition shadow-md focus:outline-none">
                                        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>Take Selfie</span>
                                    </button>
                                </div>
                            </div>

                            <!-- CAMERA CONCIERGE STREAM MODAL -->
                            <div x-show="showCameraModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-950/70 md:bg-gray-950/40 backdrop-blur-none md:backdrop-blur-sm" x-cloak>
                                <div class="bg-white rounded-[28px] border border-gray-150 w-full max-w-md p-6 space-y-6 shadow-2xl relative">
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                        <h3 class="font-serif font-bold text-lg text-gray-900">Live Camera Stream</h3>
                                        <button type="button" @click="stopCamera()" class="text-gray-400 hover:text-gray-600 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>

                                    <div class="relative w-full aspect-square rounded-2xl bg-gray-100 overflow-hidden border border-gray-150">
                                        <video x-ref="video" class="w-full h-full object-cover transform -scale-x-100" autoplay playsinline></video>
                                        <canvas x-ref="canvas" class="hidden"></canvas>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 pt-2">
                                        <button type="button" @click="stopCamera()" class="px-4 py-2.5 rounded-xl border border-gray-150 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                                            Cancel
                                        </button>
                                        <button type="button" @click="capturePhoto()" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-xs font-bold text-white transition shadow-md shadow-brand-500/10">
                                            Capture & Set Photo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-gray-100"></div>

                        {{-- Form entries --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="profile-name" class="block text-[10px] font-bold text-gray-450 uppercase tracking-widest">Full Name</label>
                                <input type="text" id="profile-name" name="name" value="{{ old('name', $user->name) }}" class="h-11 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-4 text-xs font-semibold text-gray-900 focus:outline-none transition" required>
                            </div>
                            <div class="space-y-2">
                                <label for="profile-email" class="block text-[10px] font-bold text-gray-450 uppercase tracking-widest">Email Address</label>
                                <input type="email" id="profile-email" name="email" value="{{ old('email', $user->email) }}" class="h-11 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-4 text-xs font-semibold text-gray-900 focus:outline-none transition" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4">
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-brand-500 hover:bg-brand-600 px-6 py-3 text-xs font-bold text-white transition shadow-md shadow-brand-500/10">
                                Save Profile Info
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Update Password Form --}}
                <div class="bg-white border border-gray-150 rounded-[24px] p-6 sm:p-8 space-y-6 shadow-sm">
                    <h3 class="font-serif font-bold text-lg text-gray-900">Change Password</h3>
                    
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label for="current_password" class="block text-[10px] font-bold text-gray-450 uppercase tracking-widest">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="h-11 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-4 text-xs font-semibold text-gray-900 focus:outline-none transition" required>
                            </div>
                            <div class="space-y-2">
                                <label for="password" class="block text-[10px] font-bold text-gray-450 uppercase tracking-widest">New Password</label>
                                <input type="password" id="password" name="password" class="h-11 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-4 text-xs font-semibold text-gray-900 focus:outline-none transition" required>
                            </div>
                            <div class="space-y-2">
                                <label for="password_confirmation" class="block text-[10px] font-bold text-gray-450 uppercase tracking-widest">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="h-11 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-4 text-xs font-semibold text-gray-900 focus:outline-none transition" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4">
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-brand-500 hover:bg-brand-600 px-6 py-3 text-xs font-bold text-white transition shadow-md shadow-brand-500/10">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ACCOUNT SECURITY CARD --}}
                <div class="bg-white border border-gray-150 rounded-[24px] p-6 sm:p-8 space-y-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <div>
                            <h3 class="font-serif font-bold text-lg text-gray-900">Account Security Concierge</h3>
                            <p class="text-xs text-gray-400 font-medium">Verify login device access parameters and authentication factors.</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-green-50 border border-green-100 px-3 py-1 text-xs font-bold text-green-600">
                            Score: 85% Secured
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-100 rounded-[20px] hover:-translate-y-0.5 transition duration-300">
                                <div class="space-y-0.5">
                                    <span class="block text-xs font-bold text-gray-900">Two-Factor Authentication (2FA)</span>
                                    <span class="block text-[10px] text-gray-400 font-medium">Provide code during access logs</span>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-amber-50 border border-amber-150 px-2.5 py-0.5 text-[9px] font-bold text-amber-700 tracking-wider">
                                    RECOMMENDED
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-100 rounded-[20px] hover:-translate-y-0.5 transition duration-300">
                                <div class="space-y-0.5">
                                    <span class="block text-xs font-bold text-gray-900">Verified Email Status</span>
                                    <span class="block text-[10px] text-gray-400 font-medium">{{ $user->email }}</span>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-green-50 border border-green-150 px-2.5 py-0.5 text-[9px] font-bold text-green-700 tracking-wider">
                                    VERIFIED
                                </span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-100 rounded-[20px] hover:-translate-y-0.5 transition duration-300">
                                <div class="space-y-0.5">
                                    <span class="block text-xs font-bold text-gray-900">Trusted Device List</span>
                                    <span class="block text-[10px] text-gray-400 font-medium">Windows PC • Chrome browser</span>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-green-50 border border-green-150 px-2.5 py-0.5 text-[9px] font-bold text-green-700 tracking-wider">
                                    ACTIVE
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-100 rounded-[20px] hover:-translate-y-0.5 transition duration-300">
                                <div class="space-y-0.5">
                                    <span class="block text-xs font-bold text-gray-900">Last Password Change</span>
                                    <span class="block text-[10px] text-gray-400 font-medium">Last updated: 3 months ago</span>
                                </div>
                                <span class="text-xs font-semibold text-gray-500">Normal</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Delete Account Section --}}
                <div class="bg-red-50/20 border border-red-200/40 rounded-[24px] p-6 sm:p-8 space-y-6 shadow-sm">
                    <div class="space-y-2">
                        <h3 class="font-serif font-bold text-lg text-red-650">Deactivate Account</h3>
                        <p class="text-xs text-gray-450 font-medium">Deactivating your customer profile deletes order details, coupons, and wallet points. This operation is permanent.</p>
                    </div>

                    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to deactivate your premium ShopMe profile?')">
                        @csrf
                        @method('DELETE')

                        <div class="max-w-md space-y-4">
                            <div class="space-y-2">
                                <label for="delete_password" class="block text-[10px] font-bold text-gray-450 uppercase tracking-widest">Enter Password to Confirm</label>
                                <input type="password" id="delete_password" name="password" class="h-11 w-full bg-white border border-red-200 focus:border-red-300 rounded-xl px-4 text-xs font-semibold text-gray-900 focus:outline-none transition" required>
                            </div>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-red-600 hover:bg-red-700 px-6 py-3 text-xs font-bold text-white transition shadow-md shadow-red-500/10">
                                Permanent Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            </div>
        </main>
    </div>

    <!-- GLOBAL DYNAMIC NOTIFICATION TOAST -->
    <div 
        x-show="showToast" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-8 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-8 opacity-0"
        class="fixed bottom-6 right-6 z-50 bg-gray-950/95 backdrop-blur-md text-white rounded-2xl px-5 py-3.5 shadow-2xl flex items-center gap-3.5 text-xs font-bold border transition-all duration-300"
        :class="{
            'border-emerald-500/30 shadow-emerald-500/5': toastType === 'success',
            'border-rose-500/30 shadow-rose-500/5': toastType === 'error',
            'border-[#B88A44]/30 shadow-[#B88A44]/5': toastType === 'info'
        }"
        x-cloak
    >
        <!-- Success Icon -->
        <template x-if="toastType === 'success'">
            <div class="p-1 bg-emerald-500/10 rounded-lg text-emerald-400 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </template>
        
        <!-- Error Icon -->
        <template x-if="toastType === 'error'">
            <div class="p-1 bg-rose-500/10 rounded-lg text-rose-400 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </template>

        <!-- Info Icon -->
        <template x-if="toastType === 'info'">
            <div class="p-1 bg-[#B88A44]/10 rounded-lg text-[#B88A44] shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </template>

        <span x-text="toastMessage"></span>
    </div>

    <!-- MODAL: ADD / EDIT ADDRESS -->
    <div x-show="showAddressModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-950/70 md:bg-gray-950/40 backdrop-blur-none md:backdrop-blur-sm" x-cloak>
        <div @click.away="showAddressModal = false" class="bg-white rounded-[28px] border border-gray-150 w-full max-w-lg p-6 sm:p-8 space-y-6 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <h3 class="font-serif font-bold text-lg text-gray-900" x-text="addressForm.index === -1 ? 'Add Delivery Address' : 'Edit Address Details'"></h3>
                <button @click="showAddressModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4 text-xs font-semibold text-gray-500">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5 col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Contact Full Name</label>
                        <input type="text" x-model="addressForm.name" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Street Address</label>
                    <input type="text" x-model="addressForm.street" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">City</label>
                        <input type="text" x-model="addressForm.city" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">State</label>
                        <input type="text" x-model="addressForm.state" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Pincode</label>
                        <input type="text" x-model="addressForm.zip" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Phone Number</label>
                        <input type="text" x-model="addressForm.phone" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Address Label</label>
                        <select x-model="addressForm.label" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                            <option value="Home">Home</option>
                            <option value="Office">Office</option>
                            <option value="Billing">Billing</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="addr-default" x-model="addressForm.is_default" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                    <label for="addr-default" class="text-xs text-gray-600 font-bold select-none cursor-pointer">Set as default address</label>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <button @click="showAddressModal = false" class="px-4 py-2.5 rounded-xl border border-gray-150 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button @click="saveAddress()" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-xs font-bold text-white transition shadow-md shadow-brand-500/10">
                    Save Address
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: ADD CARD -->
    <div x-show="showCardModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-950/70 md:bg-gray-950/40 backdrop-blur-none md:backdrop-blur-sm" x-cloak>
        <div @click.away="showCardModal = false" class="bg-white rounded-[28px] border border-gray-150 w-full max-w-md p-6 sm:p-8 space-y-6 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <h3 class="font-serif font-bold text-lg text-gray-900">Add New Card</h3>
                <button @click="showCardModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4 text-xs font-semibold text-gray-500">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Card Brand</label>
                    <select x-model="cardForm.brand" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                        <option value="visa">Visa</option>
                        <option value="mastercard">MasterCard</option>
                        <option value="american-express">American Express</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Cardholder Name</label>
                    <input type="text" x-model="cardForm.holder" placeholder="John Doe" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Card Number</label>
                    <input type="text" x-model="cardForm.number" maxlength="16" placeholder="4242 4242 4242 4242" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Expiry Date</label>
                        <input type="text" x-model="cardForm.expiry" placeholder="MM/YY" maxlength="5" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">CVV</label>
                        <input type="password" maxlength="3" placeholder="•••" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="card-default" x-model="cardForm.is_default" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                    <label for="card-default" class="text-xs text-gray-600 font-bold select-none cursor-pointer">Set as default payment card</label>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <button @click="showCardModal = false" class="px-4 py-2.5 rounded-xl border border-gray-150 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button @click="saveCard()" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-xs font-bold text-white transition shadow-md shadow-brand-500/10">
                    Save Card
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: TRACK ORDER TIMELINE -->
    <div 
        x-show="showTrackModal" 
        class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4 bg-gray-950/40 backdrop-blur-sm transition-opacity" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            @click.away="showTrackModal = false" 
            class="bg-white rounded-t-[28px] sm:rounded-[28px] border border-gray-150 w-full sm:max-w-4xl max-h-[92vh] sm:max-h-[85vh] overflow-y-auto p-6 sm:p-8 space-y-6 shadow-2xl flex flex-col transition-all duration-300"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
        >
            <!-- Swipe Close Indicator for Mobile -->
            <div class="sm:hidden flex justify-center pb-2">
                <div class="w-12 h-1 bg-gray-200 rounded-full" @click="showTrackModal = false"></div>
            </div>

            <!-- Header Panel -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-5 font-sans">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center border border-gray-100 shrink-0 overflow-hidden">
                        <img :src="trackDetails?.product.image" alt="Product Thumbnail" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="font-serif font-black text-base text-gray-900" x-text="trackDetails?.product.name"></h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5" x-text="trackDetails?.product.brand"></p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <div class="bg-gray-55 border border-gray-150 px-3 py-1.5 rounded-xl font-bold">
                        <span class="text-gray-400 font-semibold uppercase tracking-wider text-[9px]">Order: </span>
                        <span class="text-gray-900" x-text="'#' + trackOrderNum"></span>
                    </div>
                    <button @click="showTrackModal = false" class="text-gray-400 hover:text-gray-650 transition shrink-0 hidden sm:block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Main Content Container -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 overflow-y-auto pr-1 no-scrollbar flex-grow">
                
                <!-- Left Column (Status Card & Timeline) -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Shipment Status Card -->
                    <div class="bg-gradient-to-br from-brand-50/10 to-brand-100/5 border border-brand-200/50 rounded-2xl p-5 space-y-4 font-sans">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-[9px] font-extrabold uppercase tracking-widest text-[#B88A44]">Current Status</span>
                                <h4 class="text-xs font-black text-gray-900 capitalize" x-text="trackStatus"></h4>
                            </div>
                            <span class="text-xs font-black text-[#B88A44]" x-text="trackDetails?.progress + '%'"></span>
                        </div>
                        
                        <!-- Animated Progress Bar -->
                        <div class="w-full h-1.5 bg-gray-200/60 rounded-full overflow-hidden">
                            <div 
                                class="h-full bg-[#B88A44] rounded-full transition-all duration-750 ease-out" 
                                :style="'width: ' + trackDetails?.progress + '%'"
                            ></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-xs font-semibold pt-1">
                            <div>
                                <span class="text-[9px] text-gray-400 uppercase tracking-wider font-bold">Estimated Delivery</span>
                                <p class="text-gray-950 font-bold" x-text="trackDetails?.estimate"></p>
                            </div>
                            <div>
                                <span class="text-[9px] text-gray-400 uppercase tracking-wider font-bold">Delivery Partner</span>
                                <p class="text-gray-950 font-bold" x-text="trackDetails?.courier"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Details -->
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-extrabold uppercase tracking-widest text-gray-400">Live Shipment Timeline</h4>
                        <div class="space-y-6">
                            <template x-for="(step, idx) in getTimelineSteps()" :key="idx">
                                <div class="flex gap-4 relative">
                                    {{-- vertical connecting line --}}
                                    <div 
                                        x-show="idx < 4" 
                                        class="absolute left-[15px] top-8 w-0.5 h-12"
                                        :class="idx < getActiveStepIndex() ? 'bg-[#B88A44]' : 'bg-gray-200'"
                                    ></div>
                                    
                                    {{-- Icon circle indicator --}}
                                    <div class="relative shrink-0 z-10 font-sans">
                                        <!-- Completed steps -->
                                        <div x-show="idx < getActiveStepIndex()" class="w-8 h-8 rounded-full bg-[#B88A44] text-white flex items-center justify-center shadow-md shadow-[#B88A44]/15">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        
                                        <!-- Current active step (Pulsing ring) -->
                                        <div x-show="idx === getActiveStepIndex()" class="w-8 h-8 rounded-full bg-[#B88A44] text-white flex items-center justify-center shadow-md shadow-[#B88A44]/35 relative ring-4 ring-[#B88A44]/15">
                                            <span class="absolute inset-0 rounded-full bg-[#B88A44] opacity-75 animate-ping"></span>
                                            <span class="w-2.5 h-2.5 rounded-full bg-white relative z-10"></span>
                                        </div>
                                        
                                        <!-- Pending steps -->
                                        <div x-show="idx > getActiveStepIndex()" class="w-8 h-8 rounded-full bg-white border-2 border-gray-200 text-gray-400 flex items-center justify-center">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                                        </div>
                                    </div>
                                    
                                    {{-- step description --}}
                                    <div class="space-y-0.5 pt-0.5">
                                        <div class="flex items-center gap-2">
                                            <h5 class="text-xs font-bold text-gray-900" x-text="step.title"></h5>
                                            <span x-show="idx === getActiveStepIndex()" class="inline-flex items-center rounded-full bg-brand-50 border border-brand-100 px-1.5 py-0.5 text-[7px] font-extrabold text-[#B88A44] uppercase tracking-widest leading-none">In Transit</span>
                                        </div>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider font-sans" x-text="step.time + ' • ' + step.location"></p>
                                        <p class="text-[11px] text-gray-550 font-semibold leading-relaxed" x-text="step.desc"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Consignment Specifications & Actions) -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Shipment Specifications Card -->
                    <div class="bg-white border border-gray-250 rounded-2xl p-5 space-y-4 shadow-sm font-sans">
                        <h4 class="text-[10px] font-extrabold uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-2">Shipment Details</h4>
                        
                        <div class="space-y-3.5 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 font-semibold">Courier Partner</span>
                                <span class="font-bold text-gray-900" x-text="trackDetails?.courier"></span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 font-semibold">Tracking ID</span>
                                <div class="flex items-center gap-1.5 font-sans">
                                    <span class="font-extrabold text-gray-900 tracking-wider" x-text="trackDetails?.tracking_no"></span>
                                    <button 
                                        @click="navigator.clipboard.writeText(trackDetails?.tracking_no); triggerToast('Tracking ID copied!', 'success')"
                                        class="text-gray-400 hover:text-[#B88A44] transition active:scale-90"
                                        title="Copy tracking number"
                                        type="button"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 font-semibold">Package Weight</span>
                                <span class="font-bold text-gray-900" x-text="trackDetails?.weight"></span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 font-semibold">Shipping Tier</span>
                                <span class="font-bold text-gray-900" x-text="trackDetails?.method"></span>
                            </div>

                            <div class="border-t border-gray-105 pt-3 space-y-1">
                                <span class="text-[9px] text-gray-400 uppercase tracking-wider font-bold">Delivery Address</span>
                                <p class="text-[11px] text-gray-600 font-semibold leading-relaxed" x-text="trackDetails?.address"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Card -->
                    <div class="bg-white border border-gray-250 rounded-2xl p-5 space-y-3.5 shadow-sm font-sans">
                        <h4 class="text-[10px] font-extrabold uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-2">Order Items</h4>
                        
                        <div class="flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center border border-gray-100 shrink-0 overflow-hidden">
                                    <img :src="trackDetails?.product.image" alt="Product thumbnail" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h5 class="font-bold text-gray-900 truncate max-w-[135px]" x-text="trackDetails?.product.name"></h5>
                                    <span class="text-[9px] text-gray-400 font-extrabold uppercase" x-text="'QTY: ' + trackDetails?.product.quantity"></span>
                                </div>
                            </div>
                            <span class="font-black text-gray-900" x-text="trackDetails?.product.price"></span>
                        </div>

                        <div class="border-t border-gray-105 pt-3 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-1.5 font-bold">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-green-700 font-extrabold text-[10px]">Payment Paid</span>
                            </div>
                            <button 
                                @click="downloadInvoice(trackOrderNum)" 
                                class="text-[10px] font-extrabold uppercase tracking-wider text-[#B88A44] hover:underline"
                                type="button"
                            >
                                Invoice Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Footer Action Panel -->
            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-3 items-center justify-between bg-white sticky bottom-0 z-20 font-sans">
                <a 
                    href="mailto:support@shopme.com?subject=Tracking Query" 
                    class="text-xs font-bold text-gray-500 hover:text-gray-800 transition active:scale-95"
                >
                    Need Help? Contact Support
                </a>
                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <button 
                        @click="showTrackModal = false" 
                        class="flex-grow sm:flex-grow-0 px-5 py-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 text-xs font-bold text-gray-600 transition active:scale-95"
                        type="button"
                    >
                        Close
                    </button>
                    <button 
                        @click="window.location.href = '{{ route('store.shop') }}'; showTrackModal = false"
                        class="flex-grow sm:flex-grow-0 px-5 py-2.5 rounded-xl bg-[#B88A44] hover:bg-[#a67c3b] text-xs font-bold text-white transition active:scale-95 hover-gold-glow"
                        type="button"
                    >
                        Continue Shopping
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: RAISE TICKET -->
    <div x-show="showSupportModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-950/70 md:bg-gray-950/40 backdrop-blur-none md:backdrop-blur-sm" x-cloak>
        <div @click.away="showSupportModal = false" class="bg-white rounded-[28px] border border-gray-150 w-full max-w-md p-6 sm:p-8 space-y-6 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <h3 class="font-serif font-bold text-lg text-gray-900">Raise Support Ticket</h3>
                <button @click="showSupportModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4 text-xs font-semibold text-gray-500">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Subject / Concern</label>
                    <input type="text" x-model="supportForm.subject" placeholder="Exchange Size Issue" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Priority Level</label>
                    <select x-model="supportForm.priority" class="h-10 w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl px-3 text-xs font-bold text-gray-900 focus:outline-none transition">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-450">Detailed Message</label>
                    <textarea x-model="supportForm.message" rows="4" placeholder="Explain your concern details..." class="w-full bg-gray-50 border border-gray-150 focus:border-brand-300 focus:bg-white rounded-xl p-3 text-xs font-bold text-gray-900 focus:outline-none transition"></textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <button @click="showSupportModal = false" class="px-4 py-2.5 rounded-xl border border-gray-150 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button @click="submitTicket()" class="px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-xs font-bold text-white transition shadow-md shadow-brand-500/10">
                    Submit Ticket
                </button>
            </div>
        </div>
    </div>

    <div 
        x-show="showQuickViewModal" 
        class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4 bg-gray-950/60" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-32 md:translate-y-0 md:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
        x-transition:leave-end="opacity-0 translate-y-32 md:translate-y-0 md:scale-95"
        x-cloak
    >
        <div @click.away="closeQuickView()" class="bg-white rounded-t-[32px] md:rounded-[28px] border border-gray-150 w-full max-w-lg md:max-w-2xl p-5 md:p-8 shadow-2xl relative flex flex-col max-h-[85vh] md:max-h-[90vh] z-10 transform-gpu">
            {{-- Drag indicator bar on mobile --}}
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto -mt-2 mb-4 md:hidden"></div>

            {{-- Close Button (Touch target >= 48px) --}}
            <button @click="closeQuickView()" type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-650 transition z-10 w-12 h-12 flex items-center justify-center focus:outline-none" aria-label="Close modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Scrollable Content Body --}}
            <div class="overflow-y-auto pr-1 -mr-2 flex-grow space-y-4 no-scrollbar">
                <template x-if="quickViewProduct">
                    <div class="space-y-4">
                        {{-- Row layout for Mobile (flex), grid for Desktop (md:grid md:grid-cols-2) --}}
                        <div class="flex gap-4 items-start md:grid md:grid-cols-2 md:gap-6">
                            {{-- Image Container: w-24 h-24 on mobile, aspect-square on desktop --}}
                            <div class="w-24 h-24 sm:w-32 sm:h-32 md:w-full md:h-auto md:aspect-square bg-gray-50 rounded-2xl overflow-hidden border border-gray-150 relative shrink-0 shadow-sm group/img">
                                <img 
                                    :src="quickViewProduct.featured_image ? '/storage/' + quickViewProduct.featured_image.image_path : 'https://placehold.co/400x400?text=No+Image'" 
                                    class="w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-500" 
                                    alt="Product Image"
                                >
                            </div>

                            {{-- Product Details: Name, Brand, Rating, Price --}}
                            <div class="space-y-2 flex-grow min-w-0 md:space-y-4 text-left">
                                <div class="space-y-0.5 md:space-y-1 pr-8">
                                    <span class="text-[9px] font-bold text-brand-500 uppercase tracking-widest block" x-text="quickViewProduct.brand"></span>
                                    <h3 class="font-serif font-black text-sm sm:text-base md:text-xl text-gray-900 leading-snug truncate md:whitespace-normal" x-text="quickViewProduct.name"></h3>
                                </div>

                                {{-- Rating --}}
                                <div class="flex items-center gap-1">
                                    <div class="flex items-center text-amber-400">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="w-3 h-3 md:w-3.5 md:h-3.5 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-400">(4.8)</span>
                                </div>

                                {{-- Price --}}
                                <div class="flex items-baseline gap-1.5">
                                    <span class="font-serif font-black text-base md:text-xl text-gray-900" x-text="'₹' + quickViewProduct.price"></span>
                                    <template x-if="quickViewProduct.original_price">
                                        <span class="text-xs text-gray-400 line-through font-medium" x-text="'₹' + quickViewProduct.original_price"></span>
                                    </template>
                                </div>

                                {{-- Stock & SKU --}}
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 pt-1.5 border-t border-gray-100 text-[10px] uppercase font-bold tracking-wider">
                                    <template x-if="quickViewProduct.quantity > 0">
                                        <span class="inline-flex items-center text-emerald-600 gap-1 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            In Stock
                                        </span>
                                    </template>
                                    <template x-if="quickViewProduct.quantity <= 0">
                                        <span class="inline-flex items-center text-rose-600 gap-1 bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-100/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Out of Stock
                                        </span>
                                    </template>
                                    <span class="text-gray-400 font-semibold" x-text="'SKU: ' + quickViewProduct.sku"></span>
                                </div>

                                {{-- Premium Trust highlights --}}
                                <div class="space-y-1.5 pt-2 text-[10px] md:text-[11px] font-bold text-gray-450 border-t border-dashed border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span>100% Certified Authentic Product</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <span>Complimentary Signature Gift Box</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Free Insured Delivery & Easy Returns</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Description Block --}}
                        <div class="space-y-1 text-left pt-3 border-t border-gray-100">
                            <span class="block text-[9px] font-bold text-gray-450 uppercase tracking-widest">Description Summary</span>
                            <p class="text-xs text-gray-450 leading-relaxed font-semibold" x-text="quickViewProduct.description"></p>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Sticky Action buttons (Touch targets >= 48px) --}}
            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
                <template x-if="quickViewProduct">
                    <div class="w-full flex flex-col sm:flex-row gap-3">
                        <form :action="'/cart/add/' + quickViewProduct.id" method="POST" class="flex-grow">
                            @csrf
                            <button type="submit" class="w-full h-12 inline-flex items-center justify-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-xs font-bold text-white transition shadow-md shadow-brand-500/10 focus:outline-none group/addbtn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover/addbtn:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span>Add to Bag</span>
                            </button>
                        </form>
                        
                        <a :href="'/product/' + quickViewProduct.slug" class="h-12 px-6 rounded-xl border border-gray-150 hover:bg-gray-50 text-xs font-bold text-gray-700 transition flex items-center justify-center gap-1.5 shrink-0">
                            <span>Details Page</span>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>

<!-- BACKDROP -->
<div 
    x-show="drawerOpen" 
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-180"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="drawerOpen = false" 
    class="fixed inset-0 z-40 bg-gray-950/60 backdrop-blur-sm lg:hidden"
    x-cloak
></div>

<!-- SLIDE-UP DRAWER -->
<div 
    x-show="drawerOpen" 
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition ease-in duration-220 transform"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="translate-y-full"
    class="fixed bottom-0 left-0 right-0 z-50 bg-white rounded-t-[28px] shadow-2xl lg:hidden overflow-hidden"
    style="max-height: 82vh;"
    x-cloak
>
    {{-- Scrollable inner --}}
    <div class="overflow-y-auto" style="max-height: 82vh;">

        {{-- Drag handle --}}
        <div class="flex justify-center pt-3 pb-1">
            <div class="w-10 h-1 bg-gray-200 rounded-full"></div>
        </div>

        {{-- Profile Header --}}
        <div class="px-5 pt-2 pb-4">
            <div class="flex items-center gap-3.5 bg-gradient-to-r from-[#FBF8F3] to-[#F6ECD9] border border-[#F0E0C0] rounded-2xl p-3.5">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#B88A44] to-[#8E6226] flex items-center justify-center text-white font-serif font-black text-lg shadow-md shrink-0">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5">
                        <span class="font-serif font-black text-sm text-gray-900 leading-none truncate">{{ $user->name }}</span>
                        <span class="inline-flex items-center rounded-full bg-[#B88A44]/15 border border-[#B88A44]/30 px-1.5 py-0.5 text-[7.5px] font-bold text-[#8E6226] uppercase tracking-widest leading-none shrink-0">VIP</span>
                    </div>
                    <p class="text-[10px] text-gray-400 font-medium truncate mt-0.5">{{ $user->email }}</p>
                </div>
                <button @click="drawerOpen = false" class="w-8 h-8 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition shrink-0 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Nav Section Label --}}
        <div class="px-5 pb-1">
            <span class="text-[8.5px] font-bold uppercase tracking-[0.18em] text-gray-400">My Account</span>
        </div>

        {{-- Navigation Items --}}
        <nav class="px-3 pb-2 space-y-0.5">
            @foreach([
                ['id' => 'dashboard', 'name' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />'],
                ['id' => 'orders', 'name' => 'My Orders', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />'],
                ['id' => 'wishlist', 'name' => 'Wishlist', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />'],
                ['id' => 'addresses', 'name' => 'Addresses', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />'],
                ['id' => 'cards', 'name' => 'Payment Cards', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />'],
                ['id' => 'coupons', 'name' => 'Coupons', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />'],
                ['id' => 'rewards', 'name' => 'Rewards', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                ['id' => 'reviews', 'name' => 'Reviews', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />'],
                ['id' => 'notifications', 'name' => 'Notifications', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />'],
                ['id' => 'help', 'name' => 'Support', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />'],
                ['id' => 'settings', 'name' => 'Settings', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />']
            ] as $item)
                <button 
                    @click="changeTab('{{ $item['id'] }}')"
                    :class="activeTab === '{{ $item['id'] }}' ? 'bg-[#B88A44]/8 text-[#B88A44]' : 'text-gray-600 hover:bg-gray-50'"
                    class="w-full flex items-center gap-3 px-3 h-11 rounded-xl transition-all duration-150 text-left focus:outline-none"
                >
                    <div :class="activeTab === '{{ $item['id'] }}' ? 'bg-[#B88A44]/15' : 'bg-gray-100'" class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors duration-150">
                        <svg class="w-4 h-4 transition-colors" :class="activeTab === '{{ $item['id'] }}' ? 'text-[#B88A44]' : 'text-gray-400'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            {!! $item['icon'] !!}
                        </svg>
                    </div>
                    <span class="text-[11px] font-semibold leading-none">{{ $item['name'] }}</span>
                    <svg class="w-3.5 h-3.5 text-gray-300 ml-auto" :class="activeTab === '{{ $item['id'] }}' ? 'text-[#B88A44]/50' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @endforeach
        </nav>

        {{-- Divider + Logout --}}
        <div class="mx-5 mb-3 mt-1">
            <div class="h-px bg-gray-100 mb-2"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 h-11 rounded-xl text-red-500 hover:bg-red-50 transition focus:outline-none">
                    <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <span class="text-[11px] font-semibold">Sign Out</span>
                </button>
            </form>
        </div>

        {{-- Safe area bottom spacer --}}
        <div class="h-6"></div>
    </div>
</div>

<!-- BOTTOM NAV BAR (Mobile) -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-100 shadow-[0_-4px_24px_rgba(0,0,0,0.06)]" style="padding-bottom: env(safe-area-inset-bottom, 0px);">
    <div class="flex items-center justify-around px-2 py-1.5">

        {{-- Home --}}
        <button @click="changeTab('dashboard')" class="flex flex-col items-center justify-center gap-1 min-w-[56px] py-1.5 px-2 rounded-xl transition-all duration-200 focus:outline-none" :class="activeTab === 'dashboard' ? 'text-[#B88A44]' : 'text-gray-400'">
            <div :class="activeTab === 'dashboard' ? 'bg-[#B88A44]/12 scale-110' : ''" class="w-9 h-7 rounded-lg flex items-center justify-center transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
            </div>
            <span class="text-[8.5px] font-bold leading-none" :class="activeTab === 'dashboard' ? 'text-[#B88A44]' : 'text-gray-400'">Home</span>
        </button>

        {{-- Orders --}}
        <button @click="changeTab('orders')" class="flex flex-col items-center justify-center gap-1 min-w-[56px] py-1.5 px-2 rounded-xl transition-all duration-200 focus:outline-none" :class="activeTab === 'orders' ? 'text-[#B88A44]' : 'text-gray-400'">
            <div :class="activeTab === 'orders' ? 'bg-[#B88A44]/12 scale-110' : ''" class="w-9 h-7 rounded-lg flex items-center justify-center transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
            <span class="text-[8.5px] font-bold leading-none" :class="activeTab === 'orders' ? 'text-[#B88A44]' : 'text-gray-400'">Orders</span>
        </button>

        {{-- Wishlist --}}
        <button @click="changeTab('wishlist')" class="flex flex-col items-center justify-center gap-1 min-w-[56px] py-1.5 px-2 rounded-xl transition-all duration-200 focus:outline-none" :class="activeTab === 'wishlist' ? 'text-[#B88A44]' : 'text-gray-400'">
            <div :class="activeTab === 'wishlist' ? 'bg-[#B88A44]/12 scale-110' : ''" class="w-9 h-7 rounded-lg flex items-center justify-center transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
            </div>
            <span class="text-[8.5px] font-bold leading-none" :class="activeTab === 'wishlist' ? 'text-[#B88A44]' : 'text-gray-400'">Wishlist</span>
        </button>

        {{-- Notifications --}}
        <button @click="changeTab('notifications')" class="flex flex-col items-center justify-center gap-1 min-w-[56px] py-1.5 px-2 rounded-xl transition-all duration-200 focus:outline-none" :class="activeTab === 'notifications' ? 'text-[#B88A44]' : 'text-gray-400'">
            <div :class="activeTab === 'notifications' ? 'bg-[#B88A44]/12 scale-110' : ''" class="w-9 h-7 rounded-lg flex items-center justify-center transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
            </div>
            <span class="text-[8.5px] font-bold leading-none" :class="activeTab === 'notifications' ? 'text-[#B88A44]' : 'text-gray-400'">Alerts</span>
        </button>

        {{-- More (Profile Drawer) --}}
        <button @click="drawerOpen = !drawerOpen" class="flex flex-col items-center justify-center gap-1 min-w-[56px] py-1.5 px-2 rounded-xl transition-all duration-200 focus:outline-none" :class="drawerOpen ? 'text-[#B88A44]' : 'text-gray-400'">
            <div :class="drawerOpen ? 'bg-[#B88A44]/12 scale-110' : ''" class="w-9 h-7 rounded-lg flex items-center justify-center transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </div>
            <span class="text-[8.5px] font-bold leading-none" :class="drawerOpen ? 'text-[#B88A44]' : 'text-gray-400'">More</span>
        </button>

    </div>
</div>
@endsection
