<header
    x-data="{
        searchOpen: false,
        searchQuery: '',
        searchResults: [],
        notificationsOpen: false,
        notifications: [
            { id: 1, title: 'Low Inventory Alert', desc: 'Product SKU SKU-RED-SHIRT-M stock is below alert threshold (5 left).', time: '10 mins ago', type: 'warn' },
            { id: 2, title: 'New Customer Registered', desc: 'A new user named John Doe registered a shopper account.', time: '1 hr ago', type: 'info' },
            { id: 3, title: 'Order #1204 Payment Completed', desc: 'Payment of ₹8,999.00 processed successfully.', time: '2 hrs ago', type: 'success' }
        ],
        fetchSearch() {
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                return;
            }
            fetch('{{ route('admin.search') }}?q=' + encodeURIComponent(this.searchQuery))
                .then(r => r.json())
                .then(data => {
                    this.searchResults = data;
                })
                .catch(err => console.error(err));
        }
    }"
    @keydown.window.prevent.cmd.k="searchOpen = true"
    @keydown.window.prevent.ctrl.k="searchOpen = true"
    class="sticky top-0 z-30 bg-[#111827]/85 backdrop-blur-xl border-b border-slate-800">

    <div class="flex items-center justify-between h-16 px-6">
        {{-- Left: Toggle and Page Title --}}
        <div class="flex items-center gap-4">
            <button
                @click="sidebarOpen = true"
                class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 text-slate-350 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div>
                <p class="text-[10px] uppercase font-semibold tracking-wider text-[#B88A44]">
                    ShopMe Administrator
                </p>
                <h1 class="text-lg font-bold text-white tracking-tight">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>
        </div>

        {{-- Right: Interactions & Profile --}}
        <div class="flex items-center gap-4">
            {{-- Global Search Bar trigger --}}
            <button
                @click="searchOpen = true"
                class="hidden md:flex items-center gap-3 w-64 rounded-xl border border-slate-800 bg-slate-900/60 px-3.5 py-2 text-left text-xs text-slate-400 hover:border-slate-700 hover:bg-slate-900 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Search console...</span>
                <span class="ml-auto rounded bg-slate-800 border border-slate-700 px-1.5 py-0.5 text-3xs font-semibold text-slate-500">⌘K</span>
            </button>

            {{-- Notification Dropdown --}}
            <div class="relative">
                <button
                    @click="notificationsOpen = !notificationsOpen"
                    @click.outside="notificationsOpen = false"
                    class="relative flex items-center justify-center w-10 h-10 rounded-xl border border-slate-800 bg-slate-900/50 hover:bg-slate-900 hover:border-slate-700 text-slate-400 hover:text-white transition">
                    🔔
                    <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full border border-slate-950"></span>
                </button>

                <div
                    x-show="notificationsOpen"
                    x-transition
                    class="absolute right-0 mt-3 w-80 rounded-2xl border border-slate-800 bg-[#111827] p-4 shadow-2xl z-50 space-y-3"
                    style="display: none;">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                        <span class="text-xs font-semibold text-white">Notifications</span>
                        <span class="rounded bg-rose-500/10 px-2 py-0.5 text-3xs font-medium text-rose-400">3 New</span>
                    </div>
                    <div class="divide-y divide-slate-850 max-h-64 overflow-y-auto scrollbar-thin">
                        <template x-for="item in notifications" :key="item.id">
                            <div class="py-2.5 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-white" x-text="item.title"></span>
                                    <span class="text-4xs text-slate-500" x-text="item.time"></span>
                                </div>
                                <p class="text-2xs text-slate-400 leading-normal" x-text="item.desc"></p>
                            </div>
                        </template>
                    </div>
                    <div class="border-t border-slate-800 pt-2 text-center">
                        <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-semibold text-[#B88A44] hover:text-[#a67936] transition">View all logs</a>
                    </div>
                </div>
            </div>

            {{-- Profile Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    class="flex items-center gap-2 rounded-xl hover:bg-slate-900/60 p-1.5 transition">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth('admin')->user()->name ?? 'Admin') }}&background=B88A44&color=fff" class="w-8 h-8 rounded-full border border-slate-700">
                    <span class="hidden md:inline text-xs font-semibold text-slate-350 hover:text-white" x-text="'{{ auth('admin')->user()->name ?? 'Admin' }}'"></span>
                </button>

                <div
                    x-show="open"
                    x-transition
                    class="absolute right-0 mt-3 w-56 rounded-2xl border border-slate-800 bg-[#111827] py-2 shadow-2xl z-50"
                    style="display: none;">
                    <div class="px-4 py-2 border-b border-slate-850">
                        <span class="block text-xs font-semibold text-white">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                        <span class="block text-3xs text-slate-500 mt-0.5">Administrator</span>
                    </div>
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-slate-300 hover:bg-slate-900 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        My Profile
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-slate-300 hover:bg-slate-900 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        System Settings
                    </a>
                    <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-slate-300 hover:bg-slate-900 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Audit Logs
                    </a>
                    <hr class="border-slate-800">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-xs text-rose-400 hover:bg-rose-950/20 hover:text-rose-300 transition">
                            Logout Console
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Global Search / Command Palette Overlay Modal --}}
    <div
        x-show="searchOpen"
        class="fixed inset-0 z-50 flex items-start justify-center pt-24 px-4 bg-slate-950/70 backdrop-blur-sm"
        style="display: none;"
        @keydown.escape.window="searchOpen = false">

        <div
            @click.outside="searchOpen = false"
            class="w-full max-w-2xl rounded-3xl border border-slate-800 bg-[#111827] shadow-2xl overflow-hidden">

            <div class="flex items-center border-b border-slate-800 px-4 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    x-model="searchQuery"
                    @input.debounce.300ms="fetchSearch()"
                    placeholder="Search anything: 'Shirt', 'Order #1002', 'Active'..."
                    class="h-10 w-full bg-transparent text-sm text-white placeholder-slate-500 focus:outline-none">
                <button
                    @click="searchOpen = false"
                    class="rounded-lg border border-slate-700 bg-slate-800 px-2 py-1 text-3xs font-semibold text-slate-400 hover:text-white">
                    ESC
                </button>
            </div>

            {{-- Results --}}
            <div class="max-h-96 overflow-y-auto p-4 space-y-4">
                {{-- Quick Navigations --}}
                <div x-show="searchQuery.length < 2">
                    <span class="block text-4xs uppercase tracking-wider text-slate-505 font-bold mb-2">Quick Commands / Navigation</span>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 rounded-xl bg-slate-900/60 hover:bg-slate-900 p-3 border border-slate-850 hover:border-slate-800 text-xs text-slate-300 font-semibold transition">
                            📦 Products Directory
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 rounded-xl bg-slate-900/60 hover:bg-slate-900 p-3 border border-slate-850 hover:border-slate-800 text-xs text-slate-300 font-semibold transition">
                            🛒 Orders Management
                        </a>
                        <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-2 rounded-xl bg-slate-900/60 hover:bg-slate-900 p-3 border border-slate-850 hover:border-slate-800 text-xs text-slate-300 font-semibold transition">
                            👥 Customers shopper accounts
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 rounded-xl bg-slate-900/60 hover:bg-slate-900 p-3 border border-slate-850 hover:border-slate-800 text-xs text-slate-300 font-semibold transition">
                            ⚙️ System Settings
                        </a>
                    </div>
                </div>

                {{-- Dynamic Search Matches --}}
                <div x-show="searchQuery.length >= 2">
                    <span class="block text-4xs uppercase tracking-wider text-slate-505 font-bold mb-2">Search Results</span>
                    <div class="space-y-1.5">
                        <template x-for="item in searchResults" :key="item.url">
                            <a :href="item.url" class="flex items-center justify-between rounded-xl bg-slate-900/50 hover:bg-[#B88A44]/10 p-3 border border-slate-850 hover:border-[#B88A44]/20 transition group">
                                <div>
                                    <span class="block text-xs font-semibold text-white group-hover:text-white" x-text="item.title"></span>
                                    <span class="block text-3xs text-slate-500 mt-0.5" x-text="item.meta"></span>
                                </div>
                                <span class="rounded bg-slate-800 border border-slate-700 px-2 py-0.5 text-4xs font-bold text-slate-400 uppercase" x-text="item.category"></span>
                            </a>
                        </template>
                        <div x-show="searchResults.length === 0" class="text-center py-6 text-xs text-slate-500">
                            No match results found. Try search query 'shirt' or 'order'.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
