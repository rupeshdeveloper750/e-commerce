@props(['categories'])

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none; /* Safari and Chrome */
    }
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
        -webkit-overflow-scrolling: touch; /* iOS momentum scrolling */
    }
    
    #category-scroll-container {
        max-height: 100px;
        transition: max-height 0.35s cubic-bezier(0.16, 1, 0.3, 1), 
                    opacity 0.3s ease, 
                    padding 0.35s cubic-bezier(0.16, 1, 0.3, 1), 
                    border-color 0.3s ease;
        overflow: hidden;
    }
</style>

<div id="category-scroll-container" class="w-full overflow-x-scroll no-scrollbar py-3 border-b border-gray-100/60 scroll-smooth cursor-grab active:cursor-grabbing">
    <div class="flex items-center gap-2.5 w-max px-1">
        <a 
            href="{{ route('store.shop') }}" 
            class="shrink-0 px-5 py-2 text-[10px] font-bold uppercase tracking-widest rounded-full transition-all duration-300 border select-none {{ !request('category') ? 'bg-stone-900 border-stone-900 text-white shadow-sm' : 'bg-[#FAF9F6] border-gray-150/60 text-gray-500 hover:bg-gray-50 hover:text-stone-900' }}"
        >
            <span>All Collection</span>
        </a>
        
        @foreach($categories as $cat)
            @php
                $isActive = request('category') === $cat->slug || (is_array(request('category')) && in_array($cat->slug, request('category')));
            @endphp
            <a 
                href="{{ route('store.shop', ['category' => $cat->slug]) }}" 
                class="shrink-0 px-5 py-2 text-[10px] font-bold uppercase tracking-widest rounded-full transition-all duration-300 border select-none {{ $isActive ? 'bg-stone-900 border-stone-900 text-white shadow-sm' : 'bg-[#FAF9F6] border-gray-150/60 text-gray-500 hover:bg-gray-50 hover:text-stone-900' }}"
            >
                <span>{{ $cat->name }}</span>
            </a>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('category-scroll-container');
        if (!slider) return;
        
        let isDown = false;
        let startX;
        let scrollLeft;

        // Mouse Drag Scrolling
        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
            slider.style.scrollBehavior = 'auto'; // Disable smooth scroll to avoid drag lag
        });
        
        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.style.scrollBehavior = 'smooth';
        });
        
        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.style.scrollBehavior = 'smooth';
        });
        
        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1.5; // Scroll speed factor
            slider.scrollLeft = scrollLeft - walk;
        });

        // Touch Swipe Gesture Fallback
        slider.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
            slider.style.scrollBehavior = 'auto';
        }, { passive: true });
        
        slider.addEventListener('touchend', () => {
            isDown = false;
            slider.style.scrollBehavior = 'smooth';
        }, { passive: true });
        
        slider.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            const x = e.touches[0].pageX - slider.offsetLeft;
            const walk = (x - startX) * 1.5;
            slider.scrollLeft = scrollLeft - walk;
        }, { passive: true });

        // Auto Hide Category Strip on Scroll Down, Show on Scroll Up
        window.addEventListener('scroll', () => {
            if (window.scrollY > 40) {
                slider.style.maxHeight = '0px';
                slider.style.opacity = '0';
                slider.style.paddingTop = '0px';
                slider.style.paddingBottom = '0px';
                slider.style.borderBottomColor = 'transparent';
                slider.style.pointerEvents = 'none';
            } else {
                slider.style.maxHeight = '100px';
                slider.style.opacity = '1';
                slider.style.paddingTop = '12px'; // Restore py-3
                slider.style.paddingBottom = '12px';
                slider.style.borderBottomColor = 'rgba(243, 244, 246, 0.6)'; // Restore border color
                slider.style.pointerEvents = 'auto';
            }
        }, { passive: true });
    });
</script>
