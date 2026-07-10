@extends('layouts.auth')

@section('title', 'Sign In')
@section('panel_image', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=1200')
@section('panel_quote', '"The finest details make all the difference."')
@section('panel_desc', 'Join thousands of customers who trust ShopMe for premium tech accessories and curated collections.')

@section('form_content')
<div class="space-y-6">
    
    {{-- Header --}}
    <div class="space-y-1.5">
        <span class="text-[9px] font-bold uppercase tracking-[0.18em] text-[#B88A44] block">Welcome Back</span>
        <h1 class="font-serif text-2xl sm:text-[26px] font-black text-stone-950 leading-tight">Sign in to ShopMe</h1>
        <p class="text-xs text-stone-400 font-medium">Enter your credentials to access your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-xs text-emerald-600 font-medium" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div class="space-y-1.5">
            <label for="email" class="block text-[9px] font-bold text-stone-400 uppercase tracking-widest">Email Address</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="yourname@example.com"
                class="w-full h-11 px-4 rounded-xl border border-stone-200 bg-white text-[12.5px] font-medium text-stone-800 placeholder-stone-350 transition-all duration-150 @error('email') border-red-400 @enderror"
            >
            @error('email')
                <p class="text-[10px] text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-[9px] font-bold text-stone-400 uppercase tracking-widest">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[9px] font-bold uppercase tracking-widest text-[#B88A44] hover:text-[#A77933] transition-colors">Forgot?</a>
                @endif
            </div>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                placeholder="••••••••"
                class="w-full h-11 px-4 rounded-xl border border-stone-200 bg-white text-[12.5px] font-medium text-stone-800 placeholder-stone-350 transition-all duration-150 @error('password') border-red-400 @enderror"
            >
            @error('password')
                <p class="text-[10px] text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer gap-2">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    name="remember"
                    class="w-4 h-4 rounded border-stone-300 text-[#B88A44] focus:ring-[#B88A44]/30 cursor-pointer"
                >
                <span class="text-xs font-semibold text-stone-500">Remember me</span>
            </label>
        </div>

        {{-- Submit --}}
        <button 
            type="submit" 
            class="group w-full h-11 rounded-xl bg-[#B88A44] hover:bg-[#A77933] active:bg-[#8E6226] text-white text-[10px] font-bold uppercase tracking-[0.15em] transition-all duration-200 shadow-lg shadow-[#B88A44]/20 flex items-center justify-center gap-2"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="group-hover:translate-x-0.5 transition-transform duration-200"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
            Sign In
        </button>
    </form>

    {{-- Divider --}}
    <div class="relative flex items-center gap-4">
        <div class="flex-grow h-px bg-stone-200"></div>
        <span class="text-[9px] font-bold uppercase tracking-widest text-stone-300 whitespace-nowrap">New to ShopMe?</span>
        <div class="flex-grow h-px bg-stone-200"></div>
    </div>

    {{-- Register CTA --}}
    <a 
        href="{{ route('register') }}" 
        class="group flex items-center justify-center w-full h-11 rounded-xl border-2 border-stone-900 text-stone-900 hover:bg-stone-900 hover:text-white text-[10px] font-bold uppercase tracking-[0.15em] transition-all duration-200 gap-2"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
        Create Account
    </a>

</div>
@endsection
