<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShopMe') - Premium E-Commerce Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-slate-950 text-slate-100 antialiased flex flex-col justify-between">

    {{-- Header --}}
    <header class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                
                {{-- Logo --}}
                <div class="flex items-center gap-6">
                    <a href="{{ route('store.home') }}" class="flex items-center gap-2 group">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-amber-500 text-slate-950 font-bold text-lg">
                            S
                        </div>
                        <span class="font-bold text-xl tracking-tight text-white group-hover:text-amber-400 transition">ShopMe</span>
                    </a>
                    
                    {{-- Nav links --}}
                    <nav class="hidden md:flex items-center gap-4 text-sm font-medium text-slate-300">
                        <a href="{{ route('store.shop') }}" class="hover:text-amber-400 transition">All Products</a>
                    </nav>
                </div>

                {{-- Search Bar --}}
                <div class="flex-1 max-w-md hidden sm:block">
                    <form action="{{ route('store.shop') }}" method="GET" class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="w-full bg-slate-950 border border-slate-800 rounded-xl py-2 pl-4 pr-10 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-amber-500">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-amber-500">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Right icons --}}
                <div class="flex items-center gap-4">
                    {{-- Wishlist --}}
                    <a href="{{ route('user.dashboard') }}" class="text-slate-400 hover:text-amber-400 transition p-1.5" title="Wishlist">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>

                    {{-- Cart Icon --}}
                    <a href="{{ route('store.cart') }}" class="text-slate-400 hover:text-amber-400 transition p-1.5 relative" title="Shopping Cart">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @php $cartCount = count(session()->get('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span class="absolute top-0 right-0 w-4 h-4 rounded-full bg-amber-500 text-slate-950 text-[10px] font-bold flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    {{-- User Login / Menu --}}
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-1.5 text-sm font-medium text-slate-300 hover:text-white transition focus:outline-none">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f59e0b&color=0f172a" class="w-8 h-8 rounded-lg" alt="">
                                <span class="hidden lg:inline">{{ auth()->user()->name }}</span>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-xl bg-slate-900 border border-slate-800 shadow-xl py-1 z-50 text-slate-300 text-sm" x-cloak>
                                <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 hover:bg-slate-850 hover:text-white">My Dashboard</a>
                                <div class="border-t border-slate-800 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-rose-500/10 hover:text-rose-400">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-amber-400 transition">Login</a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    {{-- Main Contents --}}
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-400 flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-xl border border-rose-500/30 bg-rose-500/10 p-4 text-sm text-rose-400 flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
        
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 border-t border-slate-800 py-8 text-center text-sm text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3">
            <p>&copy; {{ date('Y') }} ShopMe. All rights reserved.</p>
            <p class="text-xs text-slate-600">Built with Laravel, Tailwind CSS, Alpine.js</p>
        </div>
    </footer>

</body>
</html>
