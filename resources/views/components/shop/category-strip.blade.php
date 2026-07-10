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
        display: flex;
        overflow-x: auto;
        border-bottom: 1px solid rgba(243, 244, 246, 0.8);
        scroll-behavior: smooth;
        max-height: 110px;
        transition: max-height 0.35s cubic-bezier(0.16, 1, 0.3, 1), 
                    opacity 0.3s ease, 
                    padding 0.35s cubic-bezier(0.16, 1, 0.3, 1), 
                    border-color 0.3s ease;
    }

    .category-circle-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        flex-shrink: 0;
        cursor: pointer;
    }
    .category-circle-img-wrap {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #E5E7EB;
        background-color: #FAF9F6;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    @media (min-width: 640px) {
        .category-circle-img-wrap {
            width: 52px;
            height: 52px;
        }
    }
    .category-circle-item:hover .category-circle-img-wrap {
        border-color: #B88A44;
        transform: scale(1.05);
    }
    .category-circle-img-wrap.active {
        border-color: #B88A44;
        box-shadow: 0 4px 6px -1px rgba(184, 138, 68, 0.15);
        transform: scale(1.05);
    }
    .category-circle-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .category-circle-item:hover .category-circle-img {
        transform: scale(1.1);
    }
    .category-circle-label {
        font-size: 8px;
        font-weight: 600;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        text-align: center;
        color: #6B7280;
        transition: color 0.3s ease;
        max-width: 58px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    @media (min-width: 640px) {
        .category-circle-label {
            font-size: 9px;
            letter-spacing: 0.05em;
            max-width: none;
            white-space: normal;
        }
    }
    .category-circle-label.active {
        color: #B88A44;
        font-weight: 700;
    }
    .category-circle-item:hover .category-circle-label {
        color: #111827;
    }
</style>

@php
    $getCategoryImage = function($cat) {
        if (!$cat) {
            // All Collection
            return 'https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=120&auto=format&fit=crop&q=80';
        }
        
        if (!empty($cat->image)) {
            return asset('storage/' . $cat->image);
        }
        
        $slug = strtolower($cat->slug);
        $images = [
            'fashion' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=120&auto=format&fit=crop&q=80',
            'electronics' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=120&auto=format&fit=crop&q=80',
            'footwear' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=120&auto=format&fit=crop&q=80',
            'watches' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=120&auto=format&fit=crop&q=80',
            'bags' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=120&auto=format&fit=crop&q=80',
            
            'men-clothing' => 'https://images.unsplash.com/photo-1488161628813-04466f872be2?w=120&auto=format&fit=crop&q=80',
            'women-clothing' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=120&auto=format&fit=crop&q=80',
            'kids-wear' => 'https://images.unsplash.com/photo-1519457431-44ccd64a579b?w=120&auto=format&fit=crop&q=80',
            'accessories' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=120&auto=format&fit=crop&q=80',
            
            'mobiles' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=120&auto=format&fit=crop&q=80',
            'laptops' => 'https://images.unsplash.com/photo-1496181130204-7552cc14ac1b?w=120&auto=format&fit=crop&q=80',
            'tablets' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=120&auto=format&fit=crop&q=80',
            'headphones' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=120&auto=format&fit=crop&q=80',
            
            'sneakers' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=120&auto=format&fit=crop&q=80',
            'sports-shoes' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=120&auto=format&fit=crop&q=80',
            'formal-shoes' => 'https://images.unsplash.com/photo-1539185441755-769473a23570?w=120&auto=format&fit=crop&q=80',
            'sandals' => 'https://images.unsplash.com/photo-1603808033192-082d69f9d3d1?w=120&auto=format&fit=crop&q=80',
            
            'analog' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=120&auto=format&fit=crop&q=80',
            'digital' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=120&auto=format&fit=crop&q=80',
            'smart-watches' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=120&auto=format&fit=crop&q=80',
            'luxury' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=120&auto=format&fit=crop&q=80',
            
            'hand-bags' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=120&auto=format&fit=crop&q=80',
            'backpacks' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=120&auto=format&fit=crop&q=80',
            'travel-bags' => 'https://images.unsplash.com/photo-1581605405669-fcdf81165afa?w=120&auto=format&fit=crop&q=80',
            'laptop-bags' => 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=120&auto=format&fit=crop&q=80'
        ];
        
        return $images[$slug] ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=120&auto=format&fit=crop&q=80';
    };
@endphp

<div id="category-scroll-container" class="w-full no-scrollbar py-3 border-b border-gray-100/60 scroll-smooth cursor-grab active:cursor-grabbing">
    <div class="flex items-center gap-3.5 sm:gap-6 md:gap-8 w-max px-3">
        {{-- All Collection --}}
        <a href="{{ route('store.shop') }}" class="category-circle-item">
            <div class="category-circle-img-wrap {{ !request('category') ? 'active' : '' }}">
                <img src="{{ $getCategoryImage(null) }}" alt="All Collection" class="category-circle-img" loading="lazy" />
            </div>
            <span class="category-circle-label {{ !request('category') ? 'active' : '' }}">
                All Collection
            </span>
        </a>
        
        @foreach($categories as $cat)
            @php
                $isActive = request('category') === $cat->slug || (is_array(request('category')) && in_array($cat->slug, request('category')));
            @endphp
            <a href="{{ route('store.shop', ['category' => $cat->slug]) }}" class="category-circle-item">
                <div class="category-circle-img-wrap {{ $isActive ? 'active' : '' }}">
                    <img src="{{ $getCategoryImage($cat) }}" alt="{{ $cat->name }}" class="category-circle-img" loading="lazy" />
                </div>
                <span class="category-circle-label {{ $isActive ? 'active' : '' }}">
                    {{ $cat->name }}
                </span>
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
            slider.style.scrollBehavior = 'auto';
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
            const walk = (x - startX) * 1.5;
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
                slider.style.maxHeight = '110px';
                slider.style.opacity = '1';
                slider.style.paddingTop = '14px';
                slider.style.paddingBottom = '14px';
                slider.style.borderBottomColor = 'rgba(243, 244, 246, 0.8)';
                slider.style.pointerEvents = 'auto';
            }
        }, { passive: true });
    });
</script>
