@props([
    'coupon',
    'index'
])

@php
    // Alternate card gradients for premium design rhythm
    $gradients = [
        0 => 'from-brand-500 via-brand-600 to-brand-700 text-white shadow-brand-500/10',
        1 => 'from-gray-900 via-gray-950 to-black text-white shadow-gray-950/20',
        2 => 'from-indigo-650 via-indigo-700 to-indigo-850 text-white shadow-indigo-600/10',
    ];
    $gradientClass = $gradients[$index % 3] ?? $gradients[0];
@endphp

<div class="relative rounded-[24px] bg-gradient-to-br {{ $gradientClass }} p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col justify-between min-h-[220px] group border border-white/5">
    <!-- Decorative Luxury Grid / Circles -->
    <div class="absolute inset-0 opacity-[0.06] bg-[radial-gradient(#FFF_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
    <div class="absolute -right-10 -bottom-10 w-32 h-32 rounded-full bg-white/5 group-hover:scale-125 transition duration-500 pointer-events-none"></div>
    <div class="absolute -left-10 -top-10 w-24 h-24 rounded-full bg-white/5 group-hover:scale-125 transition duration-500 pointer-events-none"></div>

    <div class="space-y-4 relative z-10">
        <div class="flex items-center justify-between">
            <span class="inline-flex items-center rounded-full bg-white/15 px-2.5 py-0.5 text-[9px] font-bold tracking-widest uppercase border border-white/10">
                {{ $coupon->type === 'percent' ? 'PERCENT' : 'FLAT' }}
            </span>
            <span class="text-[10px] text-white/80 font-semibold tracking-wide">Exp: {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('M d, Y') }}</span>
        </div>

        <div class="space-y-1">
            <h4 class="font-serif font-black text-3xl tracking-tight leading-none">
                {{ $coupon->type === 'percent' ? round($coupon->value) . '%' : '₹' . number_format($coupon->value) }} OFF
            </h4>
            <p class="text-[11px] text-white/80 font-medium tracking-wide">Min. Purchase: ₹{{ number_format($coupon->cart_value) }}</p>
        </div>
    </div>

    <!-- Coupon code & Copy / Apply Actions -->
    <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between gap-4 relative z-10">
        <div class="flex items-center gap-1.5 bg-white/10 border border-white/10 rounded-xl px-3 py-1.5">
            <code class="text-xs font-mono font-bold tracking-widest uppercase text-white" id="code-{{ $index }}">{{ $coupon->code }}</code>
        </div>
        
        <div class="flex items-center gap-3">
            <button 
                @click="copyCoupon('{{ $coupon->code }}', $event)" 
                class="text-[11px] font-bold text-white hover:text-brand-300 transition-colors duration-200 flex items-center gap-1 focus:outline-none"
                title="Copy Coupon Code"
            >
                <span>Copy</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                </svg>
            </button>
            <div class="w-px h-3.5 bg-white/20"></div>
            <button 
                @click="applyCoupon('{{ $coupon->code }}')" 
                class="text-[11px] font-bold text-brand-300 hover:text-white transition-colors duration-200 focus:outline-none"
            >
                Apply Now
            </button>
        </div>
    </div>
</div>
