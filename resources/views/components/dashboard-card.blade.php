@props([
    'title' => '',
    'value' => '0',
    'description' => '',
    'trend' => null,
    'trendUp' => true,
    'icon' => null
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-[20px] border border-gray-150 p-4 sm:p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 group']) }}>
    <div class="flex items-start justify-between">
        <div class="space-y-4">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block">{{ $title }}</span>
            <div class="flex items-baseline gap-2">
                <span class="font-serif font-black text-3xl text-gray-900 tracking-tight">{{ $value }}</span>
                @if($trend !== null)
                    <span class="inline-flex items-center gap-0.5 rounded-full px-2 py-0.5 text-[10px] font-bold {{ $trendUp ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-red-50 text-red-600 border border-red-100' }}">
                        @if($trendUp)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        @endif
                        {{ $trend }}
                    </span>
                @endif
            </div>
            @if($description)
                <p class="text-xs text-gray-400 font-medium">{{ $description }}</p>
            @endif
        </div>
        @if($icon)
            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-brand-50 group-hover:text-brand-500 transition-colors duration-300">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
