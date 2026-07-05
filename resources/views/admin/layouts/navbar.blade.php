<header
    x-data="{
        notificationsOpen: false,
        notifications: [
            { id: 1, title: 'Low Inventory Alert', desc: 'Product SKU SKU-RED-SHIRT-M stock is below alert threshold (5 left).', time: '10 mins ago', type: 'warn' },
            { id: 2, title: 'New Customer Registered', desc: 'A new user named John Doe registered a shopper account.', time: '1 hr ago', type: 'info' },
            { id: 3, title: 'Order #1204 Payment Completed', desc: 'Payment of ₹8,999.00 processed successfully.', time: '2 hrs ago', type: 'success' }
        ]
    }"
    class="sticky top-0 z-30 bg-white/90 dark:bg-[#111827]/90 backdrop-blur-xl border-b border-gray-200 dark:border-slate-800 transition-colors duration-300">

    <div class="flex items-center justify-between h-16 px-6">
        {{-- Left: Toggle and Page Title --}}
        <div class="flex items-center gap-4">
            <button
                @click="sidebarOpen = true"
                class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-slate-350 hover:text-gray-900 dark:hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div>
                <p class="text-[10px] uppercase font-semibold tracking-wider text-[#B88A44]">
                    ShopMe Administrator
                </p>
                <h1 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>
        </div>

        {{-- Right: Interactions & Profile --}}
        <div class="flex items-center gap-4">
            {{-- Global Search Bar trigger --}}
            <button
                @click="searchOpen = true"
                class="hidden md:flex items-center gap-3 w-64 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-100/80 dark:bg-slate-900/60 px-3.5 py-2 text-left text-xs text-gray-500 dark:text-slate-400 hover:border-gray-300 dark:hover:border-slate-700 hover:bg-gray-200/70 dark:hover:bg-slate-900 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Search console...</span>
                <span class="ml-auto rounded bg-gray-200 dark:bg-slate-800 border border-gray-300 dark:border-slate-700 px-1.5 py-0.5 text-3xs font-semibold text-gray-500 dark:text-slate-500">⌘K</span>
            </button>

            {{-- Theme Toggle --}}
            <button
                @click="darkMode = !darkMode"
                class="flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-100 dark:bg-slate-900/50 hover:bg-gray-200 dark:hover:bg-slate-900 hover:border-gray-300 dark:hover:border-slate-700 text-gray-500 dark:text-slate-400 hover:text-gray-800 dark:hover:text-white transition"
                title="Toggle Theme">
                <!-- Sun Icon for Dark Mode (switch to light) -->
                <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
                <!-- Moon Icon for Light Mode (switch to dark) -->
                <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 hover:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            {{-- Notification Dropdown --}}
            <div class="relative">
                <button
                    @click="notificationsOpen = !notificationsOpen"
                    @click.outside="notificationsOpen = false"
                    class="relative flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 dark:border-slate-800 bg-gray-100 dark:bg-slate-900/50 hover:bg-gray-200 dark:hover:bg-slate-900 hover:border-gray-300 dark:hover:border-slate-700 text-gray-500 dark:text-slate-400 hover:text-gray-800 dark:hover:text-white transition">
                    🔔
                    <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full border border-white dark:border-slate-950"></span>
                </button>

                <div
                    x-show="notificationsOpen"
                    x-transition
                    class="absolute right-0 mt-3 w-80 rounded-2xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#111827] p-4 shadow-2xl z-50 space-y-3"
                    style="display: none;">
                    <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-2">
                        <span class="text-xs font-semibold text-gray-900 dark:text-white">Notifications</span>
                        <span class="rounded bg-rose-500/10 px-2 py-0.5 text-3xs font-medium text-rose-500 dark:text-rose-400">3 New</span>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-slate-800 max-h-64 overflow-y-auto scrollbar-thin">
                        <template x-for="item in notifications" :key="item.id">
                            <div class="py-2.5 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-900 dark:text-white" x-text="item.title"></span>
                                    <span class="text-4xs text-gray-400 dark:text-slate-500" x-text="item.time"></span>
                                </div>
                                <p class="text-2xs text-gray-500 dark:text-slate-400 leading-normal" x-text="item.desc"></p>
                            </div>
                        </template>
                    </div>
                    <div class="border-t border-gray-100 dark:border-slate-800 pt-2 text-center">
                        <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-semibold text-[#B88A44] hover:text-[#a67936] transition">View all logs</a>
                    </div>
                </div>
            </div>

            {{-- Profile Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    class="flex items-center gap-2 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-900/60 p-1.5 transition">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth('admin')->user()->name ?? 'Admin') }}&background=B88A44&color=fff" class="w-8 h-8 rounded-full border border-gray-200 dark:border-slate-700">
                    <span class="hidden md:inline text-xs font-semibold text-gray-600 dark:text-slate-350 hover:text-gray-900 dark:hover:text-white" x-text="'{{ auth('admin')->user()->name ?? 'Admin' }}'"></span>
                </button>

                <div
                    x-show="open"
                    x-transition
                    class="absolute right-0 mt-3 w-56 rounded-2xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#111827] py-2 shadow-2xl z-50"
                    style="display: none;">
                    <div class="px-4 py-2 border-b border-gray-100 dark:border-slate-800">
                        <span class="block text-xs font-semibold text-gray-900 dark:text-white">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                        <span class="block text-3xs text-gray-400 dark:text-slate-500 mt-0.5">Administrator</span>
                    </div>
                    <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-900 hover:text-gray-900 dark:hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        My Profile
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-900 hover:text-gray-900 dark:hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        System Settings
                    </a>
                    <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-900 hover:text-gray-900 dark:hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Audit Logs
                    </a>
                    <hr class="border-gray-100 dark:border-slate-800">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-xs text-rose-500 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/20 hover:text-rose-600 dark:hover:text-rose-300 transition">
                            Logout Console
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
