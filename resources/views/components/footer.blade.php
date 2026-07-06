@props(['footerCategories', 'siteSettings'])

<footer class="bg-[#1A1815] text-[#F5F5F0] border-t border-gray-800" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>

    {{-- Row 1: Newsletter Strip --}}
    <div class="bg-[#24211D] border-b border-gray-800/80 py-12">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8" 
                 x-data="{ 
                    email: '', 
                    message: '', 
                    success: false, 
                    loading: false,
                    submitForm() {
                        if (!this.email || !this.email.includes('@')) {
                            this.message = 'Please enter a valid email address.';
                            this.success = false;
                            return;
                        }
                        this.loading = true;
                        this.message = '';
                        
                        fetch('{{ route('newsletter.subscribe') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ email: this.email })
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.loading = false;
                            this.success = data.success;
                            this.message = data.message;
                            if (data.success) this.email = '';
                        })
                        .catch(() => {
                            this.loading = false;
                            this.success = false;
                            this.message = 'Something went wrong. Please try again.';
                        });
                    }
                 }">
                
                {{-- Left Side --}}
                <div class="space-y-2 max-w-md">
                    <h3 class="font-serif text-xl sm:text-2xl font-bold text-white tracking-tight">Join the Inner Circle</h3>
                    <p class="text-xs text-gray-400 font-medium">Be first to know about new collections and private sales</p>
                </div>

                {{-- Right Side Form --}}
                <div class="w-full lg:w-auto max-w-md">
                    <form @submit.prevent="submitForm" class="flex gap-2">
                        <div class="relative flex-grow">
                            <input 
                                type="email" 
                                x-model="email"
                                placeholder="Enter your email address" 
                                class="w-full h-11 px-4 rounded-lg bg-[#1A1815] border border-gray-800 text-sm text-[#F5F5F0] placeholder-gray-500 focus:outline-none focus:border-[#B8935F] focus:ring-1 focus:ring-[#B8935F] transition-colors"
                                aria-label="Email address for newsletter"
                                :disabled="loading"
                                required
                            >
                        </div>
                        <button 
                            type="submit" 
                            class="h-11 px-6 rounded-lg bg-[#B8935F] hover:bg-[#A17F4F] text-white text-xs font-bold uppercase tracking-wider transition-colors duration-200 shrink-0 flex items-center justify-center gap-2"
                            :disabled="loading"
                        >
                            <span x-show="!loading">Subscribe</span>
                            <span x-show="loading" class="w-4 h-4 border-2 border-white/20 border-t-white rounded-full animate-spin"></span>
                        </button>
                    </form>

                    {{-- Success/Error Message --}}
                    <template x-if="message">
                        <p class="mt-2 text-xs font-medium" :class="success ? 'text-emerald-400' : 'text-rose-400'" x-text="message"></p>
                    </template>
                </div>

            </div>
        </div>
    </div>

    {{-- Row 2: Main Grid --}}
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 items-start">
            
            {{-- Column 1: Brand --}}
            <div class="space-y-6 self-start">
                <a href="{{ route('store.home') }}" class="inline-block">
                    <span class="font-serif text-2xl font-black text-white tracking-wider">ShopMe</span>
                </a>
                <p class="text-xs text-gray-400 leading-relaxed">
                    {{ $siteSettings['brand_description'] ?? 'Curators of premium quiet luxury apparel, accessories, and structural lifestyle collectibles.' }}
                </p>
                {{-- Social Icons --}}
                <div class="flex items-center gap-3">
                    @if($siteSettings['instagram_url'] ?? false)
                        <a href="{{ $siteSettings['instagram_url'] }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full border border-gray-800 flex items-center justify-center text-gray-400 hover:text-[#B8935F] hover:border-[#B8935F]/30 hover:scale-110 transition duration-200" aria-label="Follow us on Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                        </a>
                    @endif
                    @if($siteSettings['facebook_url'] ?? false)
                        <a href="{{ $siteSettings['facebook_url'] }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full border border-gray-800 flex items-center justify-center text-gray-400 hover:text-[#B8935F] hover:border-[#B8935F]/30 hover:scale-110 transition duration-200" aria-label="Follow us on Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                    @endif
                    @if($siteSettings['pinterest_url'] ?? false)
                        <a href="{{ $siteSettings['pinterest_url'] }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full border border-gray-800 flex items-center justify-center text-gray-400 hover:text-[#B8935F] hover:border-[#B8935F]/30 hover:scale-110 transition duration-200" aria-label="Follow us on Pinterest">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pinterest"><path d="M8 22a9 9 0 0 1-1.91-8.4c.5-2.5 2.1-4.7 4.5-5.9A9 9 0 1 1 8 22z"/><path d="M12 7a5 5 0 0 1 1.9 9.6c-.6 2.3-1.6 4.4-1.9 6.4h-.1a5 5 0 0 1 .1-16z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Column 2: Shop --}}
            <div class="space-y-4 self-start w-full" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between lg:block text-left focus:outline-none group">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-[#B8935F] mb-0 lg:mb-4">Shop</h3>
                    <span class="lg:hidden text-gray-500 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <nav class="hidden lg:block w-full" :class="open ? '!block' : 'hidden'">
                    <ul class="space-y-3 pt-2 lg:pt-0">
                        @foreach($footerCategories as $cat)
                            <li>
                                <a href="/shop?category={{ $cat->slug }}" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max">
                                    <span>{{ $cat->name }}</span>
                                    <span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span>
                                </a>
                            </li>
                        @endforeach
                        @if($footerCategories->isEmpty())
                            <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Women</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                            <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Men</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                            <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Accessories</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                            <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Footwear</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                            <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Timepieces</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                            <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>New Arrivals</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        @endif
                    </ul>
                </nav>
            </div>

            {{-- Column 3: Customer Care --}}
            <div class="space-y-4 self-start w-full" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between lg:block text-left focus:outline-none group">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-[#B8935F] mb-0 lg:mb-4">Customer Care</h3>
                    <span class="lg:hidden text-gray-500 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <nav class="hidden lg:block w-full" :class="open ? '!block' : 'hidden'">
                    <ul class="space-y-3 pt-2 lg:pt-0">
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Contact Us</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Track Your Order</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Shipping Policy</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Returns & Exchanges</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Size Guide</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>FAQs</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                    </ul>
                </nav>
            </div>

            {{-- Column 4: Company --}}
            <div class="space-y-4 self-start w-full" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between lg:block text-left focus:outline-none group">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-[#B8935F] mb-0 lg:mb-4">Company</h3>
                    <span class="lg:hidden text-gray-500 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <nav class="hidden lg:block w-full" :class="open ? '!block' : 'hidden'">
                    <ul class="space-y-3 pt-2 lg:pt-0">
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>About Us</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Our Story</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Sustainability</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Careers</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Press</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                    </ul>
                </nav>
            </div>

            {{-- Column 5: Concierge --}}
            <div class="space-y-4 self-start w-full" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between lg:block text-left focus:outline-none group">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-[#B8935F] mb-0 lg:mb-4">Concierge</h3>
                    <span class="lg:hidden text-gray-500 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <nav class="hidden lg:block w-full" :class="open ? '!block' : 'hidden'">
                    <ul class="space-y-3 pt-2 lg:pt-0">
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Personal Shopping</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Gift Cards</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Book an Appointment</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>WhatsApp Support</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                        <li><a href="#" class="group relative text-xs text-gray-400 hover:text-white transition-colors duration-200 pb-0.5 block w-max"><span>Store Locator</span><span class="absolute bottom-0 left-0 w-full h-[1px] bg-[#B8935F] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></span></a></li>
                    </ul>
                </nav>
            </div>

        </div>
    </div>

    {{-- Row 3: Trust/Payment Strip --}}
    <div class="border-t border-gray-800 py-4 bg-[#161412]">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 flex flex-col md:flex-row items-center justify-between gap-4">
            {{-- Trust info --}}
            <div class="flex items-center gap-2 text-xs text-gray-400 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>100% Encrypted & Secure Checkout</span>
            </div>

            {{-- Grayscale Payment SVGs --}}
            <div class="flex items-center gap-4 opacity-30 hover:opacity-50 transition-opacity duration-200">
                {{-- Visa --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                {{-- Mastercard --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="12" r="4"/><circle cx="16" cy="12" r="4"/></svg>
                {{-- UPI --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="16" x="4" y="4" rx="2"/><path d="m9 10 3 3 3-3"/></svg>
                {{-- PayPal --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2V9a2 2 0 0 1 2-2h1a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>
            </div>
        </div>
    </div>

    {{-- Row 4: Bottom Bar --}}
    <div class="border-t border-gray-800 py-4 bg-[#13110F]">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500 font-medium">
            <div>
                <span>&copy; {{ date('Y') }} ShopMe. All rights reserved.</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="/privacy" class="hover:text-gray-300 transition duration-200">Privacy Policy</a>
                <span>&bull;</span>
                <a href="/terms" class="hover:text-gray-300 transition duration-200">Terms of Service</a>
                <span>&bull;</span>
                <a href="/cookies" class="hover:text-gray-300 transition duration-200">Cookie Settings</a>
            </div>
        </div>
    </div>

</footer>
