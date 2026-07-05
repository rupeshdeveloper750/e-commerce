<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'ShopMe Admin')</title>

    <meta name="description" content="@yield('meta_description', 'ShopMe Admin Dashboard')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    {{-- HTMX for Instant SPA Transitions --}}
    <script src="https://unpkg.com/htmx.org@1.9.12" defer></script>
</head>

<body
    x-data="{
        sidebarOpen: false,
        darkMode: false,
        searchOpen: false,
        searchQuery: '',
        searchResults: [],
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
    x-init="
        darkMode = localStorage.getItem('darkMode') !== 'false';
        $watch('darkMode', value => {
            localStorage.setItem('darkMode', value);
            if(value){
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
        if(darkMode){
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    "
    @keydown.window.prevent.meta.k="searchOpen = true"
    @keydown.window.prevent.ctrl.k="searchOpen = true"
    :class="{ 'dark': darkMode }"
    class="h-full bg-gray-100 text-gray-800 antialiased dark:bg-slate-950 dark:text-white">

    <div class="min-h-screen" hx-boost="true">

        {{-- Mobile Overlay --}}
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
            x-cloak>
        </div>

        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        {{-- Main Content --}}
        <div class="lg:pl-72">

            {{-- Navbar --}}
            @include('admin.layouts.navbar')

            {{-- Page --}}
            <main class="min-h-screen p-4 sm:p-6 lg:p-8">

                @yield('content')

            </main>

            {{-- Footer --}}
            @include('admin.layouts.footer')

    </div>

    {{-- Global Search / Command Palette Overlay Modal (Root Level) --}}
    <div
        x-show="searchOpen"
        x-effect="if (searchOpen) { $nextTick(() => { $refs.searchInput.focus() }) }"
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
                    x-ref="searchInput"
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

    @stack('scripts')

</body>

</html>
