@props(['categories'])

<div class="w-full overflow-x-auto scrollbar-none py-4 border-b border-[#EAEAEA]">
    <div class="flex items-center gap-3 w-max max-w-full px-1">
        <a 
            href="{{ route('store.shop') }}" 
            class="h-10 px-5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center justify-center transition-all duration-200 {{ !request('category') ? 'bg-[#111827] text-white' : 'bg-[#F8F8F8] text-gray-700 hover:bg-[#F5F5F5]' }}"
        >
            All Products
        </a>
        
        @foreach($categories as $cat)
            @php
                $isActive = request('category') === $cat->slug || (is_array(request('category')) && in_array($cat->slug, request('category')));
            @endphp
            <a 
                href="{{ route('store.shop', ['category' => $cat->slug]) }}" 
                class="h-10 px-5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center justify-center transition-all duration-200 {{ $isActive ? 'bg-[#B88A44] text-white' : 'bg-[#F8F8F8] text-gray-700 hover:bg-[#F5F5F5]' }}"
            >
                {{ $cat->name }}
            </a>
        @endforeach
    </div>
</div>
