<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShopMe') – Premium E-Commerce Store</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,450..900;1,450..900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        html, body { height: 100%; margin: 0; padding: 0; overflow: hidden; }
        body { font-family: 'Inter', sans-serif; }
        
        /* Responsive: allow scroll on small screens */
        @media (max-width: 1023px) {
            html, body { overflow: auto; }
        }

        .auth-bg-image {
            background-image: url('@yield('bg_image')');
            background-size: cover;
            background-position: center;
        }

        .glass-brand {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.15);
        }

        /* Focus ring using gold */
        input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(184, 138, 68, 0.15);
        }
    </style>
</head>
<body class="h-full bg-stone-950">

<div class="flex h-full w-full">
    
    {{-- ===== LEFT: Brand/Visual Panel ===== --}}
    <div class="hidden lg:flex lg:w-[52%] xl:w-[55%] relative overflow-hidden flex-col">
        {{-- Background Image --}}
        <div class="absolute inset-0">
            <img 
                src="@yield('panel_image', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=1200')" 
                alt="" 
                class="w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-stone-950/80 via-stone-950/50 to-stone-950/10"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-stone-950/70 via-transparent to-stone-950/20"></div>
        </div>

        {{-- Brand Logo Top Left --}}
        <div class="relative z-10 p-8 xl:p-10">
            <a href="{{ route('store.home') }}" class="inline-flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-[#B88A44] flex items-center justify-center font-serif font-black text-white text-lg shadow-lg shadow-[#B88A44]/30 group-hover:scale-105 transition-transform duration-200">S</div>
                <span class="font-serif font-bold text-xl text-white tracking-tight">ShopMe</span>
            </a>
        </div>

        {{-- Center: Quote / Content --}}
        <div class="relative z-10 flex-grow flex flex-col justify-center px-8 xl:px-12 pb-16">
            <div class="max-w-sm space-y-5">
                {{-- Decorative line --}}
                <div class="w-10 h-[2px] bg-[#B88A44] rounded-full"></div>
                
                <h2 class="font-serif text-3xl xl:text-4xl font-bold italic leading-tight text-white">
                    @yield('panel_quote', '"Crafted for the journey ahead."')
                </h2>
                
                <p class="text-sm text-stone-300 leading-relaxed font-medium">
                    @yield('panel_desc', 'Premium accessories for modern lifestyles. Join thousands of customers who trust ShopMe.')
                </p>

                {{-- Trust badges --}}
                <div class="flex flex-col gap-3 pt-2">
                    @foreach([['🛡️', 'Secure Checkout', '256-bit SSL encryption'], ['🚚', 'Fast Delivery', 'Pan India delivery in 2-5 days'], ['🔄', 'Easy Returns', '30-day no-questions return policy']] as $badge)
                    <div class="glass-brand rounded-2xl px-4 py-3 flex items-center gap-3">
                        <span class="text-xl">{{ $badge[0] }}</span>
                        <div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-[#B88A44]">{{ $badge[1] }}</div>
                            <div class="text-[11px] text-stone-300 font-medium">{{ $badge[2] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Bottom: small footer text --}}
        <div class="relative z-10 p-8 xl:p-10">
            <p class="text-[10px] text-stone-500 font-medium">© {{ date('Y') }} ShopMe. All rights reserved.</p>
        </div>
    </div>

    {{-- ===== RIGHT: Form Panel ===== --}}
    <div class="w-full lg:w-[48%] xl:w-[45%] bg-[#FAF9F6] flex flex-col overflow-y-auto">
        
        {{-- Mobile only: brand header --}}
        <div class="lg:hidden flex items-center justify-between px-6 py-5 border-b border-stone-200/60 bg-white">
            <a href="{{ route('store.home') }}" class="inline-flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-[#B88A44] flex items-center justify-center font-serif font-black text-white text-base shadow-md">S</div>
                <span class="font-serif font-bold text-lg text-stone-900">ShopMe</span>
            </a>
            <a href="{{ route('store.home') }}" class="text-[9px] font-bold uppercase tracking-widest text-stone-400 hover:text-[#B88A44] transition-colors">← Back to Store</a>
        </div>

        {{-- Form Content --}}
        <div class="flex-grow flex items-center justify-center p-6 sm:p-10 xl:p-14">
            <div class="w-full max-w-sm">
                @yield('form_content')
            </div>
        </div>

        {{-- Bottom nav back link (desktop) --}}
        <div class="hidden lg:flex items-center justify-center pb-6">
            <a href="{{ route('store.home') }}" class="text-[9px] font-bold uppercase tracking-widest text-stone-400 hover:text-[#B88A44] transition-colors">← Back to Store</a>
        </div>

    </div>
</div>

</body>
</html>
