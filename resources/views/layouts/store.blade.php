<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShopMe') - Premium E-Commerce Store</title>
    
    <!-- Google Fonts: Inter (Body) & Playfair Display (Headings) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,450..900;1,450..900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

    <!-- Custom CSS for Premium Design Transitions -->
    <style>
        [x-cloak] {
            display: none !important;
        }
        body {
            font-family: 'Playfair Display', serif;
        }
        
        /* Premium Toast Animation Styles */
        #cart-toast {
            transition: all 500ms cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        @media (max-width: 639px) {
            #cart-toast {
                top: 1rem !important;
                left: 1rem !important;
                right: 1rem !important;
                bottom: auto !important;
                transform: translateY(-150%) !important;
            }
            #cart-toast.active {
                transform: translateY(0) !important;
                opacity: 1 !important;
            }
        }
        @media (min-width: 640px) {
            #cart-toast {
                bottom: 1.5rem !important;
                right: 1.5rem !important;
                top: auto !important;
                left: auto !important;
                transform: translateY(150%) !important;
            }
            #cart-toast.active {
                transform: translateY(0) !important;
                opacity: 1 !important;
            }
        }
        h1, h2, h3, h4, h5, h6, .font-serif {
            font-family: 'Playfair Display', serif;
        }

        /* Apple/Stripe-like Expo Easing Curve */
        .premium-transition {
            transition: all 220ms cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Glassmorphism Classes */
        .glass-surface {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
        }

        .dark .glass-surface {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
        }

        /* Hover glows */
        .hover-gold-glow:hover {
            box-shadow: 0 0 20px rgba(184, 138, 68, 0.15);
        }

        /* Hide scrollbars but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="h-full {{ request()->routeIs('user.dashboard') ? 'bg-[#FAFAFA]' : 'bg-white' }} text-[#111827] antialiased flex flex-col justify-between"
      x-data="{ 
          scrolled: false, 
          activeMenu: null, 
          searchOpen: false, 
          mobileOpen: false, 
          activeAccordion: null,
          recentSearches: ['Slim Fit Shirt', 'Air Force 1', 'Apple Watch SE', 'Travel Bag'],
          popularSuggestions: ['Watches', 'Electronics', 'Footwear', 'Bags']
      }"
      @scroll.window="scrolled = window.scrollY > 25"
      @keydown.escape.window="searchOpen = false">

    {{-- Floating Sticky Navbar Wrapper --}}
    <div class="fixed top-4 left-0 right-0 z-50 w-full px-6 md:px-10 lg:px-16 transition-transform duration-300">
        <header 
            class="max-w-[1400px] mx-auto rounded-2xl border transition-all duration-300 glass-surface"
            :class="scrolled 
                ? 'border-[#E5E7EB] shadow-2xl shadow-gray-200/40 py-2.5 px-6 md:px-10' 
                : 'border-transparent py-4 px-4'"
        >
            <div class="flex items-center justify-between h-14 gap-6">
                
                {{-- Logo (Left) --}}
                <div class="flex items-center">
                    <a href="{{ route('store.home') }}" class="flex items-center gap-3 group">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-[#B88A44] text-white font-serif font-black text-lg shadow-md shadow-[#B88A44]/20 transition-all duration-300 group-hover:scale-105 group-hover:rotate-3">
                            S
                        </div>
                        <span class="font-serif font-bold text-xl tracking-tight text-[#111827] group-hover:text-[#B88A44] transition-colors duration-300">ShopMe</span>
                    </a>
                </div>

                {{-- Navigation Links (Centered) --}}
                <nav class="hidden md:flex items-center gap-10">
                    @foreach([
                        ['name' => 'Home', 'route' => 'store.home', 'trigger' => 'none'],
                        ['name' => 'Shop', 'route' => 'store.shop', 'trigger' => 'none'],
                        ['name' => 'Categories', 'route' => 'store.shop', 'trigger' => 'categories'],
                        ['name' => 'Deals', 'route' => 'store.deals', 'trigger' => 'none'],
                        ['name' => 'About', 'route' => 'store.about', 'trigger' => 'none']
                    ] as $link)
                        @php
                            $isActive = false;
                            if ($link['name'] === 'Home' && request()->routeIs('store.home')) {
                                $isActive = true;
                            } elseif ($link['name'] === 'Shop' && request()->routeIs('store.shop') && !request()->has('category')) {
                                $isActive = true;
                            } elseif ($link['name'] === 'Categories' && request()->routeIs('store.shop') && request()->has('category')) {
                                $isActive = true;
                            } elseif ($link['name'] === 'Deals' && request()->routeIs('store.deals')) {
                                $isActive = true;
                            } elseif ($link['name'] === 'About' && request()->routeIs('store.about')) {
                                $isActive = true;
                            }
                        @endphp
                        @if($link['trigger'] === 'categories')
                            <div class="relative" @mouseenter="activeMenu = 'categories'" @mouseleave="activeMenu = null">
                                <a 
                                    href="{{ route($link['route']) }}" 
                                    class="relative py-2 text-xs font-semibold tracking-widest uppercase {{ $isActive ? 'text-[#B88A44]' : 'text-[#111827]' }} hover:text-[#B88A44] transition duration-200 flex items-center gap-1.5 focus:outline-none"
                                >
                                    <span>{{ $link['name'] }}</span>
                                    <svg class="w-3 h-3 transition-transform duration-300" :class="activeMenu === 'categories' ? 'rotate-180 text-[#B88A44]' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <span class="absolute bottom-0 left-0 w-full h-[2px] bg-[#B88A44] scale-x-0 transition-transform duration-300 origin-left" :class="activeMenu === 'categories' || {{ $isActive ? 'true' : 'false' }} ? 'scale-x-100' : ''"></span>
                                </a>
                            </div>
                        @else
                            <a 
                                href="{{ route($link['route']) }}" 
                                class="relative py-2 text-xs font-semibold tracking-widest uppercase {{ $isActive ? 'text-[#B88A44]' : 'text-[#111827]' }} hover:text-[#B88A44] transition duration-200 group"
                            >
                                <span>{{ $link['name'] }}</span>
                                <span class="absolute bottom-0 left-0 w-full h-[2px] bg-[#B88A44] {{ $isActive ? 'scale-x-100' : 'scale-x-0' }} group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                            </a>
                        @endif
                    @endforeach
                </nav>

                {{-- Action Icons (Right) --}}
                <div class="flex items-center gap-[6px] xs:gap-[10px] md:gap-[14px]">
                    {{-- Search Trigger --}}
                    <button 
                        @click="searchOpen = true"
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/70 backdrop-blur-md border border-black/[0.05] flex items-center justify-center text-gray-700 hover:text-[#B88A44] hover:bg-[#FAF9F6] shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-[220ms] focus:outline-none"
                        title="Search Products"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </button>

                    {{-- Wishlist --}}
                    @php
                        $wishlistCount = auth()->check() 
                            ? \App\Models\Wishlist::where('user_id', auth()->id())->count() 
                            : 0;
                    @endphp
                    <a 
                        href="{{ route('user.dashboard', ['tab' => 'wishlist']) }}" 
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-[#B88A44] to-[#A77933] flex items-center justify-center text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-[220ms] relative"
                        title="Wishlist"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="{{ $wishlistCount > 0 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart text-white"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                        @if($wishlistCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 rounded-full bg-white text-[#B88A44] text-[10px] font-bold flex items-center justify-center border-2 border-[#B88A44] shadow-sm">
                                {{ $wishlistCount }}
                            </span>
                        @endif
                    </a>

                    {{-- Cart Icon (live reactive badge) --}}
                    @php
                        $cartQuery = auth()->check() 
                            ? \App\Models\CartItem::where('user_id', auth()->id()) 
                            : \App\Models\CartItem::where('session_id', session()->getId());
                        $initialCartCount = $cartQuery->where('is_saved', false)->count();
                    @endphp
                    <a 
                        href="{{ route('store.cart') }}" 
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/70 backdrop-blur-md border border-black/[0.05] flex items-center justify-center text-gray-700 hover:text-[#B88A44] hover:bg-[#FAF9F6] shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-[220ms] relative"
                        title="Shopping Cart"
                        x-data="{ cartCount: {{ $initialCartCount }} }"
                        @cart-count-updated.window="cartCount = $event.detail.count"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        <span 
                            x-show="cartCount > 0"
                            x-text="cartCount"
                            class="absolute -top-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-[#B88A44] text-white text-[8px] font-bold flex items-center justify-center shadow-md shadow-[#B88A44]/30 transition-all duration-300"
                            :class="cartCount > 0 ? 'scale-100 opacity-100' : 'scale-0 opacity-0'"
                        ></span>
                    </a>

                    {{-- User Account / Login Button --}}
                    @auth
                        <div x-data="{ open: false }" class="relative ml-[2px]">
                            <button @click="open = !open" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full overflow-hidden border border-black/[0.05] hover:border-[#B88A44]/35 hover:scale-105 shadow-sm transition-all duration-[220ms] focus:outline-none">
                                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=B88A44&color=ffffff' }}" class="w-full h-full object-cover" alt="">
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-3 w-52 rounded-2xl bg-white border border-[#E5E7EB] shadow-2xl py-2 z-50 text-gray-700 text-sm overflow-hidden" x-cloak>
                                <div class="px-4 py-2 bg-gray-50/50 border-b border-[#E5E7EB] mb-1">
                                    <span class="block font-semibold text-[#111827] truncate">{{ auth()->user()->name }}</span>
                                    <span class="block text-xs text-gray-400 truncate">{{ auth()->user()->email }}</span>
                                </div>
                                <a href="{{ route('user.dashboard') }}" class="block px-4 py-2.5 hover:bg-[#B88A44]/5 hover:text-[#B88A44] transition font-medium">My Dashboard</a>
                                <div class="border-t border-[#E5E7EB] my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 hover:bg-rose-50 hover:text-rose-600 transition font-medium">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div x-data="{ open: false }" class="relative ml-[2px]" @mouseenter="open = true" @mouseleave="open = false">
                            <button @click="open = !open" class="group/login-btn inline-flex items-center justify-center h-9 sm:h-[42px] px-3.5 sm:px-[22px] rounded-full text-[10px] sm:text-xs font-semibold tracking-[0.3px] uppercase text-white bg-gradient-to-r from-[#B88A44] to-[#A77933] hover:from-[#A77933] hover:to-[#8E6226] ring-1 ring-white/10 ring-inset shadow-md shadow-[#B88A44]/15 hover:shadow-lg transition-all duration-[220ms] focus:outline-none">
                                <span>Login</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="absolute right-0 mt-3 w-64 rounded-2xl bg-white border border-[#E5E7EB] shadow-2xl p-5 z-50 text-gray-700 text-sm overflow-hidden flex flex-col gap-4" @click.away="open = false">
                                <div class="text-center space-y-1">
                                    <span class="block font-serif font-bold text-stone-900 text-base">Welcome to ShopMe</span>
                                    <span class="block text-[8px] text-stone-400 font-bold uppercase tracking-widest leading-none">Premium Tech Accessories</span>
                                </div>
                                
                                <a href="{{ route('login') }}" class="w-full h-10 rounded-xl bg-[#B88A44] hover:bg-[#A77933] text-white text-[10px] font-bold uppercase tracking-widest flex items-center justify-center transition shadow-md shadow-[#B88A44]/15">
                                    Sign In
                                </a>

                                <div class="text-center text-xs font-semibold text-stone-500">
                                    New Customer? <a href="{{ route('register') }}" class="text-[#B88A44] hover:underline font-bold">Sign Up</a>
                                </div>

                                <div class="border-t border-[#E5E7EB] pt-3 flex flex-col gap-3 text-xs font-semibold text-stone-600">
                                    <a href="{{ route('store.shop') }}" class="hover:text-[#B88A44] transition flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-stone-400"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <span>Order Tracking</span>
                                    </a>
                                    <a href="{{ route('store.cart') }}" class="hover:text-[#B88A44] transition flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-stone-400"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                        <span>My Shopping Bag</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endauth

                    {{-- Mobile Toggle --}}
                    <button 
                        @click="mobileOpen = true"
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex lg:hidden items-center justify-center text-gray-700 hover:text-[#B88A44] hover:bg-[#FAF9F6] border border-black/[0.05] hover:border-[#B88A44]/35 hover:-translate-y-0.5 transition duration-[220ms] focus:outline-none"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                    </button>
                </div>

            </div>
        </header>

        {{-- FLOATING PREMIUM MEGA MENU PANEL --}}
        <div 
            x-show="activeMenu === 'categories'" 
            x-cloak
            @mouseenter="activeMenu = 'categories'" 
            @mouseleave="activeMenu = null"
            x-transition:enter="transition ease-out duration-250 transform"
            x-transition:enter-start="opacity-0 -translate-y-4 scale-98"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-4 scale-98"
            class="max-w-[1400px] mx-auto mt-2 rounded-[32px] border border-[#E5E7EB] bg-white/95 backdrop-blur-xl shadow-2xl p-8 z-40 overflow-hidden"
        >
            <div class="grid grid-cols-12 gap-8">
                
                {{-- Category Navigation Cards (1 to 5) --}}
                <div class="col-span-8 grid grid-cols-5 gap-6">
                    @foreach([
                        [
                            'title' => 'Fashion',
                            'desc' => 'Luxury clothing & apparel.',
                            'slug' => 'fashion',
                            'items' => ['Men Clothing', 'Women Clothing', 'Kids Wear', 'Accessories'],
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 009 11V7a5 5 0 00-10 0v4c0 3.86 3.14 7 7 7zm6.57 2.04A13.978 13.978 0 0021 11V7a5 5 0 00-10 0v4c0 3.86 3.14 7 7 7h.57z" />'
                        ],
                        [
                            'title' => 'Electronics',
                            'desc' => 'High tech & innovations.',
                            'slug' => 'electronics',
                            'items' => ['Mobiles', 'Laptops', 'Tablets', 'Headphones'],
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />'
                        ],
                        [
                            'title' => 'Footwear',
                            'desc' => 'Crafted shoes for steps.',
                            'slug' => 'footwear',
                            'items' => ['Sneakers', 'Sports Shoes', 'Formal Shoes', 'Sandals'],
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />'
                        ],
                        [
                            'title' => 'Watches',
                            'desc' => 'Precision luxury items.',
                            'slug' => 'watches',
                            'items' => ['Analog', 'Digital', 'Smart Watches', 'Luxury'],
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />'
                        ],
                        [
                            'title' => 'Bags',
                            'desc' => 'Premium utility packs.',
                            'slug' => 'bags',
                            'items' => ['Hand Bags', 'Backpacks', 'Travel Bags', 'Laptop Bags'],
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />'
                        ]
                    ] as $category)
                        <div class="flex flex-col space-y-4 group/card p-3 rounded-2xl hover:bg-gray-50/50 hover:shadow-md hover:shadow-gray-100/50 border border-transparent hover:border-gray-200/60 transition-all duration-300">
                            
                            {{-- Category Title card --}}
                            <div>
                                <a 
                                    href="{{ route('store.shop', ['category' => $category['slug']]) }}"
                                    class="flex items-center gap-2 group/title"
                                >
                                    <div class="w-7 h-7 rounded-lg bg-[#B88A44]/5 flex items-center justify-center border border-[#B88A44]/10 group-hover/card:bg-[#B88A44] group-hover/card:text-white transition duration-300">
                                        <svg class="w-4 h-4 text-[#B88A44] group-hover/card:text-white transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            {!! $category['icon'] !!}
                                        </svg>
                                    </div>
                                    <h3 class="font-serif font-bold text-base text-[#111827] group-hover/title:text-[#B88A44] transition-colors duration-300">
                                        {{ $category['title'] }}
                                    </h3>
                                </a>
                                <p class="text-[10px] text-gray-400 mt-2 font-medium leading-normal h-7 overflow-hidden">{{ $category['desc'] }}</p>
                            </div>
                            
                            <div class="h-px bg-gradient-to-r from-gray-200/80 to-transparent"></div>
                            
                            {{-- Category Items list --}}
                            <ul class="space-y-1">
                                @foreach($category['items'] as $item)
                                    <li>
                                        <a 
                                            href="{{ route('store.shop', ['category' => Str::slug($item)]) }}"
                                            class="flex items-center justify-between text-xs font-semibold text-gray-500 hover:text-[#B88A44] hover:bg-[#B88A44]/5 px-2 py-1.5 rounded-lg transition-all duration-200 group/link"
                                        >
                                            <span class="group-hover/link:translate-x-0.5 transition-transform duration-200">{{ $item }}</span>
                                            <svg class="w-3 h-3 opacity-0 group-hover/link:opacity-100 group-hover/link:translate-x-0.5 text-[#B88A44] transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>

                {{-- Luxury Editorial Card (Right Side) --}}
                <div class="col-span-4 pl-6 border-l border-[#E5E7EB] flex flex-col justify-between">
                    
                    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-[#B88A44]/20 via-white to-gray-50 border border-[#E5E7EB]/80 p-6 flex flex-col justify-between min-h-[220px] shadow-sm group/promo">
                        <div class="absolute -right-16 -bottom-16 w-52 h-52 rounded-full bg-[#B88A44]/5 blur-3xl group-hover/promo:scale-110 transition duration-500"></div>
                        
                        <div class="space-y-3 relative z-10">
                            <span class="inline-flex items-center rounded-full bg-[#B88A44]/10 border border-[#B88A44]/20 px-2.5 py-0.5 text-[9px] font-bold text-[#B88A44] uppercase tracking-widest">
                                NEW COLLECTION
                            </span>
                            <h4 class="font-serif font-black text-2xl text-[#111827] leading-tight">
                                Summer Collection 2026
                            </h4>
                            <p class="text-xs text-gray-500 max-w-[260px] leading-relaxed">
                                Experience fashion minimalism inspired by Apple design and COS tailoring.
                            </p>
                        </div>

                        <div class="relative z-10 pt-4">
                            <a 
                                href="{{ route('store.shop') }}" 
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#B88A44] px-5 py-2.5 text-xs font-bold text-white hover:bg-[#A77933] shadow-md shadow-[#B88A44]/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                            >
                                <span>Explore Campaign</span>
                                <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover/promo:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Mega Menu Premium Bottom Ticker --}}
            <div class="mt-8 pt-6 border-t border-[#E5E7EB]/80 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Featured Tags:</span>
                    <div class="flex items-center gap-4">
                        @foreach([
                            ['name' => 'G-Shock Full Metal Gold', 'badge' => '🔥 Trending', 'price' => '₹44,995.00', 'slug' => 'g-shock-full-metal-gold'],
                            ['name' => 'iPhone 15 Pro', 'badge' => '⭐ Best Seller', 'price' => '₹1,29,900.00', 'slug' => 'iphone-15-pro'],
                            ['name' => 'Air Force 1 \'07', 'badge' => '🆕 New Arrival', 'price' => '₹8,995.00', 'slug' => 'air-force-1-07'],
                            ['name' => 'Galaxy S24 Ultra', 'badge' => '🏆 Top Rated', 'price' => '₹1,19,999.00', 'slug' => 'galaxy-s24-ultra']
                        ] as $item)
                            <a 
                                href="{{ route('store.product.show', $item['slug']) }}" 
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-100 hover:border-[#B88A44]/30 hover:bg-[#B88A44]/5 px-3 py-1.5 transition-all duration-300 group/chip"
                            >
                                <span class="text-[10px] font-bold text-[#111827]">{{ $item['badge'] }}</span>
                                <span class="text-xs text-gray-500 font-medium group-hover/chip:text-[#B88A44] transition-colors duration-200">{{ $item['name'] }}</span>
                                <span class="text-xs text-gray-450 font-bold ml-1">{{ $item['price'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('store.shop') }}" class="text-xs font-semibold text-[#B88A44] hover:text-[#A77933] flex items-center gap-1 group/all">
                    <span>View All Products</span>
                    <svg class="w-3.5 h-3.5 group-hover/all:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

        </div>
    </div>

    {{-- Modern Floating Search Modal Overlay --}}
    <div 
        x-show="searchOpen" 
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 bg-[#111827]/40 backdrop-blur-md flex items-start justify-center pt-24 px-4"
    >
        <div 
            @click.outside="searchOpen = false"
            x-transition:enter="transition ease-out duration-250 transform"
            x-transition:enter-start="opacity-0 translate-y-4 scale-98"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            class="w-full max-w-2xl bg-white rounded-3xl border border-[#E5E7EB] shadow-2xl overflow-hidden"
        >
            {{-- Input Header --}}
            <div class="flex items-center border-b border-[#E5E7EB] px-5 py-4">
                <svg class="w-5 h-5 text-gray-400 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <form action="{{ route('store.shop') }}" method="GET" class="flex-grow">
                    <input 
                        type="text" 
                        name="q"
                        placeholder="Type to search: 'Mobiles', 'Denim Shirt', 'Sneakers'..."
                        class="h-10 w-full bg-transparent text-sm text-[#111827] placeholder-gray-400 focus:outline-none"
                        autofocus
                    >
                </form>
                <button 
                    @click="searchOpen = false"
                    class="rounded-xl border border-[#E5E7EB] bg-gray-50 px-3 py-1.5 text-xs font-semibold text-gray-500 hover:text-[#111827] transition"
                >
                    ESC
                </button>
            </div>

            {{-- Suggestions --}}
            <div class="p-6 space-y-6">
                
                {{-- Recent Searches --}}
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-3">Recent Searches</span>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="item in recentSearches" :key="item">
                            <a 
                                :href="'/shop?q=' + encodeURIComponent(item)" 
                                class="inline-flex items-center gap-1.5 rounded-xl border border-[#E5E7EB] bg-gray-50 hover:bg-[#B88A44]/5 hover:border-[#B88A44]/20 px-3.5 py-1.5 text-xs text-gray-600 hover:text-[#B88A44] font-medium transition"
                            >
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span x-text="item"></span>
                            </a>
                        </template>
                    </div>
                </div>

                {{-- Popular Categories --}}
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-3">Popular Categories</span>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="item in popularSuggestions" :key="item">
                            <a 
                                :href="'/shop?category=' + encodeURIComponent(item.toLowerCase())" 
                                class="inline-flex items-center rounded-xl bg-gray-50 hover:bg-[#B88A44]/5 border border-[#E5E7EB] hover:border-[#B88A44]/20 px-4 py-2 text-xs text-gray-700 hover:text-[#B88A44] font-semibold transition"
                            >
                                <span x-text="'# ' + item"></span>
                            </a>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Premium Mobile Menu Drawer --}}
    <div 
        x-show="mobileOpen" 
        x-cloak
        class="fixed inset-0 z-50 flex lg:hidden"
    >
        {{-- Overlay background --}}
        <div 
            x-show="mobileOpen"
            x-transition.opacity
            @click="mobileOpen = false"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm"
        ></div>

        {{-- Slide Drawer --}}
        <div 
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative w-full max-w-xs bg-white h-full flex flex-col justify-between py-6 px-6 shadow-2xl z-50 border-r border-[#E5E7EB]"
        >
            <div class="space-y-6 overflow-y-auto max-h-[85vh] pr-2">
                {{-- Drawer Header --}}
                <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-4">
                    <span class="font-serif font-bold text-xl tracking-tight text-[#111827]">ShopMe</span>
                    <button 
                        @click="mobileOpen = false"
                        class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center border border-[#E5E7EB]"
                    >
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Accordion Categories / Links --}}
                <nav class="space-y-4">
                    <a href="{{ route('store.home') }}" class="block text-base font-semibold text-[#111827] hover:text-[#B88A44] py-1 border-b border-[#E5E7EB]/55">Home</a>
                    <a href="{{ route('store.shop') }}" class="block text-base font-semibold text-[#111827] hover:text-[#B88A44] py-1 border-b border-[#E5E7EB]/55">Shop</a>
                    
                    {{-- Categories Accordion --}}
                    <div>
                        <button 
                            @click="activeAccordion = (activeAccordion === 'categories' ? null : 'categories')"
                            class="w-full flex items-center justify-between text-base font-semibold text-[#111827] hover:text-[#B88A44] py-1 border-b border-[#E5E7EB]/55 focus:outline-none"
                        >
                            <span>Categories</span>
                            <svg class="w-4 h-4 text-gray-400 transition" :class="activeAccordion === 'categories' ? 'rotate-180 text-[#B88A44]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div x-show="activeAccordion === 'categories'" x-collapse class="pl-4 pt-3 space-y-2.5" x-cloak>
                            @foreach([
                                'Fashion' => 'fashion',
                                'Electronics' => 'electronics',
                                'Footwear' => 'footwear',
                                'Watches' => 'watches',
                                'Bags' => 'bags'
                            ] as $catName => $catSlug)
                                <a 
                                    href="{{ route('store.shop', ['category' => $catSlug]) }}" 
                                    class="block text-sm font-medium text-gray-600 hover:text-[#B88A44]"
                                >
                                    {{ $catName }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('store.deals') }}" class="block text-base font-semibold text-[#111827] hover:text-[#B88A44] py-1 border-b border-[#E5E7EB]/55">Deals</a>
                    <a href="{{ route('store.about') }}" class="block text-base font-semibold text-[#111827] hover:text-[#B88A44] py-1 border-b border-[#E5E7EB]/55">About</a>
                </nav>
            </div>

            {{-- Mobile Drawer Footer Actions --}}
            <div class="border-t border-[#E5E7EB] pt-5 space-y-3">
                @auth
                    <a 
                        href="{{ route('user.dashboard') }}" 
                        class="flex items-center justify-center w-full rounded-xl bg-gray-50 border border-[#E5E7EB] py-3 text-sm font-semibold text-[#111827] hover:bg-[#B88A44]/5 hover:text-[#B88A44] transition"
                    >
                        My Account
                    </a>
                @else
                    <a 
                        href="{{ route('login') }}" 
                        class="flex items-center justify-center w-full rounded-xl bg-[#B88A44] py-3 text-sm font-semibold text-white hover:bg-[#A77933] transition shadow-md shadow-[#B88A44]/10"
                    >
                        Login / Register
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Main Content Section (Padded to clear fixed navbar) --}}
    <main class="flex-grow max-w-7xl w-full mx-auto px-6 sm:px-8 lg:px-12 pt-32 md:pt-40 pb-16">
        
        @if(session('success'))
            <div class="mb-8 rounded-2xl border border-emerald-500/20 bg-emerald-500/[0.04] p-4 text-xs font-bold text-emerald-800 flex items-center gap-3 shadow-sm shadow-emerald-500/5 font-sans">
                <div class="p-1 bg-emerald-500/10 rounded-lg text-emerald-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 rounded-2xl border border-rose-500/20 bg-rose-500/[0.04] p-4 text-xs font-bold text-rose-800 flex items-center gap-3 shadow-sm shadow-rose-500/5 font-sans">
                <div class="p-1 bg-rose-500/10 rounded-lg text-rose-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
        
    </main>

    {{-- Footer --}}
    <x-footer :footerCategories="$footerCategories" :siteSettings="$siteSettings" />

    {{-- Global Mobile App-Like Bottom Navigation Tab Bar (Flipkart/Meesho style) --}}
    @php
        $cartQuery = auth()->check() 
            ? \App\Models\CartItem::where('user_id', auth()->id()) 
            : \App\Models\CartItem::where('session_id', session()->getId());
        $initialCartCount = $cartQuery->where('is_saved', false)->count();
    @endphp
    <div class="mobile-bottom-nav fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-stone-150/70 sm:hidden flex items-center justify-around py-2 px-3 shadow-[0_-4px_25px_rgba(0,0,0,0.06)] pb-safe">
        <a href="{{ route('store.home') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('store.home') ? 'text-[#B88A44]' : 'text-stone-400' }} hover:text-[#B88A44] transition-colors py-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="{{ request()->routeIs('store.home') ? 'rgba(184,138,68,0.15)' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-home"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span class="text-[8px] font-bold uppercase tracking-widest">Home</span>
            @if(request()->routeIs('store.home'))
                <span class="w-1 h-1 rounded-full bg-[#B88A44] mt-0.5"></span>
            @endif
        </a>
        <a href="{{ route('store.shop') }}" class="flex flex-col items-center gap-0.5 {{ (request()->routeIs('store.shop') && !request()->routeIs('store.deals')) ? 'text-[#B88A44]' : 'text-stone-400' }} hover:text-[#B88A44] transition-colors py-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="{{ (request()->routeIs('store.shop') && !request()->routeIs('store.deals')) ? 'rgba(184,138,68,0.15)' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <span class="text-[8px] font-bold uppercase tracking-widest">Shop</span>
            @if(request()->routeIs('store.shop') && !request()->routeIs('store.deals'))
                <span class="w-1 h-1 rounded-full bg-[#B88A44] mt-0.5"></span>
            @endif
        </a>
        <a href="{{ route('store.cart') }}" 
           x-data="{ cartCount: {{ $initialCartCount }} }"
           @cart-count-updated.window="cartCount = $event.detail.count"
           class="flex flex-col items-center gap-0.5 {{ request()->routeIs('store.cart') ? 'text-[#B88A44]' : 'text-stone-400' }} hover:text-[#B88A44] transition-colors py-1 relative">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="{{ request()->routeIs('store.cart') ? 'rgba(184,138,68,0.15)' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <span x-show="cartCount > 0" x-text="cartCount" class="absolute top-0 right-1 w-3.5 h-3.5 rounded-full bg-[#B88A44] text-white text-[8px] font-bold flex items-center justify-center shadow-md"></span>
            <span class="text-[8px] font-bold uppercase tracking-widest">Bag</span>
            @if(request()->routeIs('store.cart'))
                <span class="w-1 h-1 rounded-full bg-[#B88A44] mt-0.5"></span>
            @endif
        </a>
        @auth
        <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('user.dashboard') ? 'text-[#B88A44]' : 'text-stone-400' }} hover:text-[#B88A44] transition-colors py-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="{{ request()->routeIs('user.dashboard') ? 'rgba(184,138,68,0.15)' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span class="text-[8px] font-bold uppercase tracking-widest">Account</span>
            @if(request()->routeIs('user.dashboard'))
                <span class="w-1 h-1 rounded-full bg-[#B88A44] mt-0.5"></span>
            @endif
        </a>
        @else
        <a href="{{ route('login') }}" class="flex flex-col items-center gap-0.5 {{ (request()->routeIs('login') || request()->routeIs('register')) ? 'text-[#B88A44]' : 'text-stone-400' }} hover:text-[#B88A44] transition-colors py-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="{{ (request()->routeIs('login') || request()->routeIs('register')) ? 'rgba(184,138,68,0.15)' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
            <span class="text-[8px] font-bold uppercase tracking-widest">Login</span>
            @if(request()->routeIs('login') || request()->routeIs('register'))
                <span class="w-1 h-1 rounded-full bg-[#B88A44] mt-0.5"></span>
            @endif
        </a>
        @endauth
    </div>

    {{-- Premium Dynamic Cart Notification Toast --}}
    <div id="cart-toast" class="fixed z-[9999] opacity-0 pointer-events-none max-w-sm w-full mx-auto sm:mx-0">
        <div class="bg-stone-900/95 backdrop-blur-xl border border-white/10 text-white shadow-[0_24px_60px_rgba(0,0,0,0.35)] rounded-2xl p-4 flex flex-col gap-3 max-w-sm pointer-events-auto relative overflow-hidden min-w-[320px]">
            
            <!-- Toast Header -->
            <div class="flex items-center justify-between border-b border-white/5 pb-2">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[9px] font-bold uppercase tracking-widest text-emerald-400">Added to Bag</span>
                </div>
                <button onclick="hideCartToast()" class="text-white/40 hover:text-white transition-colors focus:outline-none" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Rich Details Layout -->
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 bg-white/5 rounded-xl border border-white/10 overflow-hidden flex items-center justify-center p-1 shrink-0">
                    <img id="cart-toast-image" src="" alt="Product" class="max-h-full max-w-full object-contain">
                </div>
                <div class="flex-grow min-w-0">
                    <p id="cart-toast-brand" class="text-[8px] font-bold text-[#B88A44] uppercase tracking-widest leading-none mb-1">Premium Product</p>
                    <p id="cart-toast-title" class="text-xs font-semibold truncate text-white">Product Name</p>
                    <p id="cart-toast-price" class="text-xs font-medium text-white/60 mt-0.5">Price</p>
                </div>
            </div>

            <!-- Quick Navigation Shortcuts -->
            <div class="flex items-center gap-2 pt-1">
                <a href="{{ route('store.cart') }}" class="flex-1 h-9 rounded-lg bg-[#B88A44] hover:bg-[#A37837] text-white text-[10px] font-bold uppercase tracking-wider transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                    View Bag
                </a>
                <a href="{{ route('store.checkout') }}" class="flex-1 h-9 rounded-lg bg-white/10 hover:bg-white/20 border border-white/10 text-white text-[10px] font-bold uppercase tracking-wider transition-colors flex items-center justify-center">
                    Checkout
                </a>
            </div>

            <!-- Auto-dismiss animated progress bar -->
            <div class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-white/5">
                <div id="cart-toast-progress" class="h-full bg-[#B88A44] w-full origin-left transition-all duration-[4500ms] linear"></div>
            </div>
        </div>
    </div>

    {{-- AJAX Cart Form Interceptor & Toast Controller --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (form.action && (form.action.includes('/cart/add/') || form.action.includes('/cart/add'))) {
                    if (e.submitter && e.submitter.name === 'buy_now') {
                        return; // Let the form submit natively
                    }
                    e.preventDefault();
                    
                    const formData = new FormData(form);
                    const submitBtn = form.querySelector('[type="submit"]');
                    const originalBtnContent = submitBtn ? submitBtn.innerHTML : '';
                    
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.style.opacity = '0.75';
                    }
                    
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': formData.get('_token')
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response error');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Update navbar cartCount state
                            window.dispatchEvent(new CustomEvent('cart-count-updated', { 
                                detail: { count: data.cart_count } 
                            }));
                            
                            // Show premium rich toast popup
                            showCartToast(data.message, form);
                        }
                    })
                    .catch(error => {
                        console.error('Error adding product to cart:', error);
                    })
                    .finally(() => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.style.opacity = '1';
                            submitBtn.innerHTML = originalBtnContent;
                        }
                    });
                }
            });
        });

        let toastTimeout;
        let progressTimeout;

        function showCartToast(message, form) {
            const toast = document.getElementById('cart-toast');
            const progress = document.getElementById('cart-toast-progress');
            
            // Rich elements
            const toastImg = document.getElementById('cart-toast-image');
            const toastBrand = document.getElementById('cart-toast-brand');
            const toastTitle = document.getElementById('cart-toast-title');
            const toastPrice = document.getElementById('cart-toast-price');
            
            let name = 'Product added to bag';
            let image = 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=150';
            let price = '';
            let brand = 'Premium Collection';

            // Scrape data from form context
            if (form) {
                const card = form.closest('.group') || form.closest('li') || form.closest('.bg-white') || form.closest('.grid');
                if (card) {
                    const imgEl = card.querySelector('img');
                    const nameEl = card.querySelector('h3') || card.querySelector('h4');
                    const priceEl = card.querySelector('.font-serif') || card.querySelector('.text-amber-500') || card.querySelector('.font-sans.font-bold');
                    const brandEl = card.querySelector('span');

                    if (imgEl) image = imgEl.src;
                    if (nameEl) name = nameEl.textContent.trim();
                    if (priceEl) price = priceEl.textContent.trim();
                    if (brandEl) brand = brandEl.textContent.trim();
                }
            }

            // Assign variables to popup
            if (toastImg) toastImg.src = image;
            if (toastBrand) toastBrand.textContent = brand;
            if (toastTitle) toastTitle.textContent = name;
            if (toastPrice) toastPrice.textContent = price;

            if (toast) {
                // Reset progress bar before animating
                if (progress) {
                    progress.style.transition = 'none';
                    progress.style.width = '100%';
                    // Trigger reflow to reset
                    void progress.offsetWidth; 
                }

                toast.classList.add('active');
                
                // Start animating progress bar width to 0
                if (progress) {
                    progress.style.transition = 'width 4500ms linear';
                    progress.style.width = '0%';
                }

                clearTimeout(toastTimeout);
                toastTimeout = setTimeout(hideCartToast, 4500);
            }
        }

        function hideCartToast() {
            const toast = document.getElementById('cart-toast');
            if (toast) {
                toast.classList.remove('active');
            }
        }
    </script>

</body>
</html>
