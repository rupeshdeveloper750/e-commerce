@props(['items'])

<section 
    class="w-full bg-[#F8F8F8] border-y border-[#E5E7EB]/80 py-10 md:py-12"
    aria-label="Trust Indicators"
>
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <ul class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-y-8 gap-x-6 md:gap-x-10 lg:gap-x-12">
            @foreach($items as $item)
                <li class="flex items-center gap-4 group cursor-default">
                    {{-- Icon Container with Subtle Hover Lift --}}
                    <div 
                        class="w-12 h-12 rounded-xl bg-white border border-[#E5E7EB] flex items-center justify-center shrink-0 shadow-sm text-[#B88A44] transition-all duration-300 lg:group-hover:-translate-y-1 lg:group-hover:shadow-md lg:group-hover:border-[#B88A44]/20"
                        aria-hidden="true"
                    >
                        @switch($item['icon'])
                            @case('truck')
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M19 18h2a1 1 0 0 0 1-1v-5.14a1 1 0 0 0-.293-.707l-3.86-3.86A1 1 0 0 0 15.14 7H12"/><circle cx="7.5" cy="18.5" r="2.5"/><circle cx="16.5" cy="18.5" r="2.5"/></svg>
                                @break
                            @case('refresh')
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                @break
                            @case('shield-check')
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 9.7a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 .76-.97l8-2a1 1 0 0 1 .48 0l8 2A1 1 0 0 1 20 6z"/><path d="m9 12 2 2 4-4"/></svg>
                                @break
                            @case('headset')
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-headset"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
                                @break
                            @default
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.75z"/><path d="m9 12 2 2 4-4"/></svg>
                        @endswitch
                    </div>

                    {{-- Text Info Column --}}
                    <div class="space-y-1">
                        <span class="block text-sm font-semibold text-[#111827] group-hover:text-[#B88A44] transition-colors duration-200">
                            {{ $item['title'] }}
                        </span>
                        <span class="block text-xs text-gray-500 font-normal leading-normal">
                            {{ $item['subtitle'] }}
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</section>
