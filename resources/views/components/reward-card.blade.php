@props([
    'points' => 0,
    'tier' => 'Gold Member',
    'nextTier' => 'Platinum Member',
    'progress' => 65,
    'pointsNeeded' => 350
])

<div class="bg-gradient-to-br from-brand-500 to-brand-700 rounded-[24px] p-5 sm:p-6 text-white relative overflow-hidden shadow-xl shadow-brand-500/10">
    {{-- Design accents --}}
    <div class="absolute -right-24 -bottom-24 w-64 h-64 rounded-full bg-white/5 blur-2xl"></div>
    <div class="absolute right-12 top-6 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>

    <div class="space-y-5 relative z-10">
        {{-- Total points --}}
        <div class="space-y-2">
            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-[9px] font-bold uppercase tracking-widest leading-none">
                {{ $tier }}
            </span>
            <div class="space-y-0.5">
                <span class="block text-[10px] font-semibold text-brand-100 uppercase tracking-widest">Available Balance</span>
                <div class="flex items-baseline gap-1">
                    <span class="font-serif font-black text-3xl sm:text-4xl tracking-tight leading-none">{{ number_format($points) }}</span>
                    <span class="text-[9px] font-bold text-brand-100 uppercase tracking-wider pl-1">PTS</span>
                </div>
            </div>
        </div>

        <div class="h-px bg-white/10"></div>

        {{-- Progress details --}}
        <div class="space-y-3">
            <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-widest">
                <span class="text-brand-100">Progress to {{ $nextTier }}</span>
                <span>{{ $progress }}%</span>
            </div>
            
            <div class="w-full h-1.5 bg-white/15 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ $progress }}%"></div>
            </div>

            <div class="flex flex-col gap-2 pt-1">
                <p class="text-[11px] text-brand-100 font-semibold leading-relaxed">
                    Earn {{ $pointsNeeded }} points to unlock tier benefits.
                </p>
                <a href="{{ route('store.shop') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-white hover:underline py-1 w-fit">
                    <span>Shop & Earn</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
