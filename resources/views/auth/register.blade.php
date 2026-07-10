@extends('layouts.auth')

@section('title', 'Create Account')
@section('panel_image', 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=1200')
@section('panel_quote', '"Every detail holds a promise of strength."')
@section('panel_desc', 'Join over 1,00,000 travelers and creatives. Unlock exclusive member discounts, lifetime warranty coverage, and more.')

@section('form_content')
<div class="space-y-5">
    
    {{-- Header --}}
    <div class="space-y-1.5">
        <span class="text-[9px] font-bold uppercase tracking-[0.18em] text-[#B88A44] block">Join ShopMe</span>
        <h1 class="font-serif text-2xl sm:text-[26px] font-black text-stone-950 leading-tight">Create Account</h1>
        <p class="text-xs text-stone-400 font-medium">Fill in the details below to get started</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
        @csrf

        {{-- Name --}}
        <div class="space-y-1.5">
            <label for="name" class="block text-[9px] font-bold text-stone-400 uppercase tracking-widest">Full Name</label>
            <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="John Doe"
                class="w-full h-11 px-4 rounded-xl border border-stone-200 bg-white text-[12.5px] font-medium text-stone-800 placeholder-stone-350 transition-all duration-150 @error('name') border-red-400 @enderror"
            >
            @error('name')
                <p class="text-[10px] text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="space-y-1.5">
            <label for="email" class="block text-[9px] font-bold text-stone-400 uppercase tracking-widest">Email Address</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
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
            <label for="password" class="block text-[9px] font-bold text-stone-400 uppercase tracking-widest">Password</label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="Min. 8 characters"
                class="w-full h-11 px-4 rounded-xl border border-stone-200 bg-white text-[12.5px] font-medium text-stone-800 placeholder-stone-350 transition-all duration-150 @error('password') border-red-400 @enderror"
            >
            @error('password')
                <p class="text-[10px] text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="space-y-1.5">
            <label for="password_confirmation" class="block text-[9px] font-bold text-stone-400 uppercase tracking-widest">Confirm Password</label>
            <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="Re-enter your password"
                class="w-full h-11 px-4 rounded-xl border border-stone-200 bg-white text-[12.5px] font-medium text-stone-800 placeholder-stone-350 transition-all duration-150 @error('password_confirmation') border-red-400 @enderror"
            >
            @error('password_confirmation')
                <p class="text-[10px] text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button 
            type="submit" 
            class="group w-full h-11 rounded-xl bg-[#B88A44] hover:bg-[#A77933] active:bg-[#8E6226] text-white text-[10px] font-bold uppercase tracking-[0.15em] transition-all duration-200 shadow-lg shadow-[#B88A44]/20 flex items-center justify-center gap-2 mt-1"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="group-hover:translate-x-0.5 transition-transform duration-200"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
            Sign Up
        </button>
    </form>

    {{-- Divider --}}
    <div class="relative flex items-center gap-4">
        <div class="flex-grow h-px bg-stone-200"></div>
        <span class="text-[9px] font-bold uppercase tracking-widest text-stone-300 whitespace-nowrap">Already have an account?</span>
        <div class="flex-grow h-px bg-stone-200"></div>
    </div>

    {{-- Login CTA --}}
    <a 
        href="{{ route('login') }}" 
        class="group flex items-center justify-center w-full h-11 rounded-xl border-2 border-stone-900 text-stone-900 hover:bg-stone-900 hover:text-white text-[10px] font-bold uppercase tracking-[0.15em] transition-all duration-200 gap-2"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
        Sign In Instead
    </a>

</div>
@endsection
