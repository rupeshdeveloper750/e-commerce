{{-- ==========================================================================
    ShopMe Admin Panel — Premium Sidebar Navigation
    resources/views/admin/layouts/sidebar.blade.php
    Laravel 12 + Tailwind CSS 4 + Alpine.js + Heroicons
========================================================================== --}}

<div
    x-data="{
        openSections: {
            catalog: true,
            sales: true,
            marketing: true,
            system: true
        },
        profileMenuOpen: false,
        toggleSection(key) {
            this.openSections[key] = !this.openSections[key];
        }
    }"
    x-init="
        $watch('sidebarOpen', value => {
            document.body.style.overflow = value ? 'hidden' : '';
        });
    "
    @keydown.escape.window="sidebarOpen = false; profileMenuOpen = false"
    class="contents"
>
    {{-- ============================== --}}
    {{-- Mobile Backdrop Overlay --}}
    {{-- ============================== --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-in-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in-out duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
        aria-hidden="true"
        style="display: none;"
    ></div>

    {{-- ============================== --}}
    {{-- Sidebar --}}
    {{-- ============================== --}}
    <aside
        id="admin-sidebar"
        x-show="true"
        x-cloak
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed top-0 left-0 z-50 flex flex-col h-screen w-[280px] bg-white dark:bg-[#0F172A] border-r border-[#E5E7EB] dark:border-slate-800/80 transition-transform duration-300 ease-in-out will-change-transform lg:translate-x-0"
        aria-label="Primary Sidebar Navigation"
    >
        {{-- Mobile Close Button --}}
        <button
            type="button"
            @click="sidebarOpen = false"
            aria-label="Close sidebar navigation"
            class="lg:hidden absolute top-4 right-4 inline-flex items-center justify-center w-9 h-9 rounded-xl text-[#6B7280] dark:text-slate-400 hover:bg-[#F3F4F6] dark:hover:bg-slate-800 hover:text-[#111827] dark:hover:text-white transition-colors duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- ============================== --}}
        {{-- Logo Area --}}
        {{-- ============================== --}}
        <div class="flex-shrink-0 px-6 pt-7 pb-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2 rounded-xl">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#0F172A] dark:bg-slate-800 shadow-sm transition-transform duration-300 ease-in-out group-hover:scale-105">
                    <span class="text-[#B88A44] font-bold text-base tracking-tight">S</span>
                </div>
                <div class="flex flex-col leading-tight">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-[17px] text-[#111827] dark:text-white tracking-tight">SHOPME</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-semibold tracking-wide bg-[rgba(184,138,68,.10)] text-[#B88A44] border border-[#B88A44]/20">
                            v2.0
                        </span>
                    </div>
                    <span class="text-xs font-medium text-[#6B7280] dark:text-slate-400">Admin Dashboard</span>
                </div>
            </a>
        </div>

        <div class="flex-shrink-0 mx-6 border-t border-[#E5E7EB] dark:border-slate-800"></div>

        {{-- ============================== --}}
        {{-- Navigation --}}
        {{-- ============================== --}}
        <nav
            class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden px-4 py-6 space-y-7 admin-sidebar-scroll"
            aria-label="Admin navigation sections"
        >
            {{-- =============== MAIN =============== --}}
            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold tracking-widest text-[#6B7280] dark:text-slate-500 uppercase select-none">
                    Main
                </p>
                <ul class="space-y-1">
                    <li>
                        <a
                            href="{{ route('admin.dashboard') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.dashboard'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.dashboard'),
                            ])
                            aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- =============== CATALOG =============== --}}
            <div>
                <button
                    type="button"
                    @click="toggleSection('catalog')"
                    class="w-full flex items-center justify-between px-3 mb-2 group focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] rounded-md"
                    :aria-expanded="openSections.catalog"
                    aria-controls="section-catalog"
                >
                    <span class="text-[11px] font-semibold tracking-widest text-[#6B7280] dark:text-slate-500 uppercase select-none">
                        Catalog
                    </span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-3.5 h-3.5 text-[#6B7280] dark:text-slate-500 transition-transform duration-300 ease-in-out"
                        :class="openSections.catalog ? 'rotate-180' : 'rotate-0'"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <ul
                    id="section-catalog"
                    x-show="openSections.catalog"
                    x-collapse
                    class="space-y-1"
                >
                    {{-- Categories --}}
                    <li>
                        <a
                            href="{{ route('admin.categories.index', ['parent_id' => 'null']) }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.categories.*') && request('parent_id') === 'null',
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !(request()->routeIs('admin.categories.*') && request('parent_id') === 'null'),
                            ])
                            aria-current="{{ (request()->routeIs('admin.categories.*') && request('parent_id') === 'null') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ (request()->routeIs('admin.categories.*') && request('parent_id') === 'null') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span>Categories</span>
                        </a>
                    </li>

                    {{-- Sub Categories --}}
                    <li>
                        <a
                            href="{{ route('admin.categories.index', ['parent_id' => 'not_null']) }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.categories.*') && request('parent_id') === 'not_null',
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !(request()->routeIs('admin.categories.*') && request('parent_id') === 'not_null'),
                            ])
                            aria-current="{{ (request()->routeIs('admin.categories.*') && request('parent_id') === 'not_null') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ (request()->routeIs('admin.categories.*') && request('parent_id') === 'not_null') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <span>Sub Categories</span>
                        </a>
                    </li>

                    {{-- Brands --}}
                    <li>
                        <a
                            href="{{ route('admin.brands.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.brands.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.brands.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.brands.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.brands.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75a4.5 4.5 0 01-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 11-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 016.336-4.486l-3.276 3.276a3.004 3.004 0 002.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852z" />
                            </svg>
                            <span>Brands</span>
                        </a>
                    </li>

                    {{-- Products --}}
                    <li>
                        <a
                            href="{{ route('admin.products.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.products.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.products.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.products.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.products.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            <span>Products</span>
                        </a>
                    </li>

                    {{-- Inventory --}}
                    <li>
                        <a
                            href="{{ route('admin.inventory.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.inventory.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.inventory.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.inventory.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.inventory.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-9-4.5-9 4.5m18 0l-9 4.5m9-4.5v9l-9 4.5M3.75 7.5l9 4.5m-9-4.5v9l9 4.5m0-9v9" />
                            </svg>
                            <span>Inventory</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- =============== SALES =============== --}}
            <div>
                <button
                    type="button"
                    @click="toggleSection('sales')"
                    class="w-full flex items-center justify-between px-3 mb-2 group focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] rounded-md"
                    :aria-expanded="openSections.sales"
                    aria-controls="section-sales"
                >
                    <span class="text-[11px] font-semibold tracking-widest text-[#6B7280] dark:text-slate-500 uppercase select-none">
                        Sales
                    </span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-3.5 h-3.5 text-[#6B7280] dark:text-slate-500 transition-transform duration-300 ease-in-out"
                        :class="openSections.sales ? 'rotate-180' : 'rotate-0'"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <ul
                    id="section-sales"
                    x-show="openSections.sales"
                    x-collapse
                    class="space-y-1"
                >
                    {{-- Orders --}}
                    <li>
                        <a
                            href="{{ route('admin.orders.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.orders.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.orders.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.orders.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.836l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.98-4.804 2.545-7.394A.75.75 0 0018.5 5.25H5.106M7.5 14.25L5.106 5.25M7.5 14.25l-1.5 5.25m9.75-5.25l1.5 5.25m-9.75 0h9.75" />
                            </svg>
                            <span>Orders</span>
                        </a>
                    </li>

                    {{-- Transactions --}}
                    <li>
                        <a
                            href="{{ route('admin.transactions.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.transactions.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.transactions.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.transactions.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.transactions.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a1.5 1.5 0 001.5-1.5V6.75a1.5 1.5 0 00-1.5-1.5h-15a1.5 1.5 0 00-1.5 1.5v10.5a1.5 1.5 0 001.5 1.5z" />
                            </svg>
                            <span>Transactions</span>
                        </a>
                    </li>

                    {{-- Coupons --}}
                    <li>
                        <a
                            href="{{ route('admin.coupons.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.coupons.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.coupons.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.coupons.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.coupons.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l1.5 1.5 3-3.75M6.75 3v.75m12 0V3m-12 18v-.75m12 .75v-.75M4.5 12h.75m14.25 0h.75M6 8.25h12A2.25 2.25 0 0120.25 10.5v3A2.25 2.25 0 0118 15.75H6A2.25 2.25 0 013.75 13.5v-3A2.25 2.25 0 016 8.25z" />
                            </svg>
                            <span>Coupons</span>
                        </a>
                    </li>

                    {{-- Customers --}}
                    <li>
                        <a
                            href="{{ route('admin.customers.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.customers.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.customers.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.customers.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.customers.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span>Customers</span>
                        </a>
                    </li>

                    {{-- Reviews --}}
                    <li>
                        <a
                            href="{{ route('admin.reviews.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.reviews.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.reviews.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.reviews.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.reviews.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                            <span>Reviews</span>
                        </a>
                    </li>

                    {{-- Support --}}
                    <li>
                        <a
                            href="{{ route('admin.support.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.support.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.support.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.support.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.support.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                            </svg>
                            <span>Support</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- =============== MARKETING =============== --}}
            <div>
                <button
                    type="button"
                    @click="toggleSection('marketing')"
                    class="w-full flex items-center justify-between px-3 mb-2 group focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] rounded-md"
                    :aria-expanded="openSections.marketing"
                    aria-controls="section-marketing"
                >
                    <span class="text-[11px] font-semibold tracking-widest text-[#6B7280] dark:text-slate-500 uppercase select-none">
                        Marketing
                    </span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-3.5 h-3.5 text-[#6B7280] dark:text-slate-500 transition-transform duration-300 ease-in-out"
                        :class="openSections.marketing ? 'rotate-180' : 'rotate-0'"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <ul
                    id="section-marketing"
                    x-show="openSections.marketing"
                    x-collapse
                    class="space-y-1"
                >
                    {{-- Banners --}}
                    <li>
                        <a
                            href="{{ route('admin.banners.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.banners.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.banners.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.banners.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.banners.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <span>Banners & Sliders</span>
                        </a>
                    </li>

                    {{-- Blogs --}}
                    <li>
                        <a
                            href="{{ route('admin.blogs.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.blogs.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.blogs.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.blogs.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.blogs.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125H3.375A1.125 1.125 0 012.25 18V6.75c0-.621.504-1.125 1.125-1.125H5.25m11.25 0V4.5A1.5 1.5 0 0015 3h-3a1.5 1.5 0 00-1.5 1.5V6.75M2.25 7.5h19.5" />
                            </svg>
                            <span>Blogs & News</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- =============== SYSTEM =============== --}}
            <div>
                <button
                    type="button"
                    @click="toggleSection('system')"
                    class="w-full flex items-center justify-between px-3 mb-2 group focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] rounded-md"
                    :aria-expanded="openSections.system"
                    aria-controls="section-system"
                >
                    <span class="text-[11px] font-semibold tracking-widest text-[#6B7280] dark:text-slate-500 uppercase select-none">
                        System
                    </span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-3.5 h-3.5 text-[#6B7280] dark:text-slate-500 transition-transform duration-300 ease-in-out"
                        :class="openSections.system ? 'rotate-180' : 'rotate-0'"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <ul
                    id="section-system"
                    x-show="openSections.system"
                    x-collapse
                    class="space-y-1"
                >
                    {{-- Reports --}}
                    <li>
                        <a
                            href="{{ route('admin.reports.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.reports.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.reports.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.reports.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            <span>Reports</span>
                        </a>
                    </li>

                    {{-- Settings --}}
                    <li>
                        <a
                            href="{{ route('admin.settings.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.settings.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.settings.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.settings.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Settings</span>
                        </a>
                    </li>

                    {{-- Activity Logs --}}
                    <li>
                        <a
                            href="{{ route('admin.activity-logs.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.activity-logs.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.activity-logs.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.activity-logs.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.activity-logs.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span>Activity Logs</span>
                        </a>
                    </li>

                    {{-- Admins --}}
                    <li>
                        <a
                            href="{{ route('admin.admins.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.admins.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.admins.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.admins.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.admins.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.745 3.745 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                            <span>Admins</span>
                        </a>
                    </li>

                    {{-- Roles --}}
                    <li>
                        <a
                            href="{{ route('admin.roles.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.roles.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.roles.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.roles.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.roles.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.745 3.745 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                            <span>Roles</span>
                        </a>
                    </li>

                    {{-- Permissions --}}
                    <li>
                        <a
                            href="{{ route('admin.permissions.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.permissions.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.permissions.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.permissions.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.permissions.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            <span>Permissions</span>
                        </a>
                    </li>

                    {{-- Profile --}}
                    <li>
                        <a
                            href="{{ route('admin.profile.index') }}"
                            @class([
                                'group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2',
                                'bg-[rgba(184,138,68,.10)] text-[#B88A44] font-semibold' => request()->routeIs('admin.profile.*'),
                                'text-[#111827] dark:text-slate-300 hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 hover:text-[#111827] dark:hover:text-white' => !request()->routeIs('admin.profile.*'),
                            ])
                            aria-current="{{ request()->routeIs('admin.profile.*') ? 'page' : 'false' }}"
                        >
                            <span
                                x-show="{{ request()->routeIs('admin.profile.*') ? 'true' : 'false' }}"
                                class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-[3px] rounded-full bg-[#B88A44]"
                                aria-hidden="true"
                            ></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.964 0a9 9 0 10-11.964 0m11.964 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Profile</span>
                        </a>
                    </li>

                    {{-- Logout --}}
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="group relative w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#111827] dark:text-slate-300 transition-all duration-300 ease-in-out hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0110.5 3h6a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0116.5 21h-6a2.25 2.25 0 01-2.25-2.25V15m-3 0l-3-3m0 0l3-3m-3 3H15" />
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- ============================== --}}
        {{-- Profile Section (Bottom Fixed) --}}
        {{-- ============================== --}}
        <div class="flex-shrink-0 relative border-t border-[#E5E7EB] dark:border-slate-800 p-4">
            <button
                type="button"
                @click="profileMenuOpen = !profileMenuOpen"
                @click.outside="profileMenuOpen = false"
                aria-haspopup="true"
                :aria-expanded="profileMenuOpen"
                class="w-full flex items-center gap-3 px-2.5 py-2.5 rounded-xl transition-colors duration-300 ease-in-out hover:bg-[#F3F4F6] dark:hover:bg-slate-800/60 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#B88A44] focus-visible:ring-offset-2"
            >
                <span class="relative flex-shrink-0">
                    <img
                        src="{{ auth('admin')->user()?->profile_photo ? Storage::url(auth('admin')->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth('admin')->user()?->name ?? 'Admin') . '&background=B88A44&color=fff' }}"
                        alt="{{ auth('admin')->user()?->name ?? 'Admin' }} avatar"
                        class="w-9 h-9 rounded-full object-cover ring-2 ring-white dark:ring-[#0F172A]"
                    >
                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-[#0F172A]" aria-label="Online"></span>
                </span>

                <span class="flex flex-col items-start leading-tight min-w-0">
                    <span class="text-sm font-semibold text-[#111827] dark:text-white truncate max-w-[130px]">
                        {{ auth('admin')->user()?->name ?? 'Admin User' }}
                    </span>
                    <span class="text-xs font-medium text-[#6B7280] dark:text-slate-400 truncate max-w-[130px]">
                        {{ auth('admin')->user()?->role ?? 'Super Admin' }}
                    </span>
                </span>

                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-auto flex-shrink-0 text-[#6B7280] dark:text-slate-500 transition-transform duration-300 ease-in-out" :class="profileMenuOpen ? 'rotate-180' : 'rotate-0'" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 8.25l3.75 6.75" transform="rotate(180 12 12)" />
                </svg>
            </button>

            <div
                x-show="profileMenuOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="absolute bottom-full left-4 right-4 mb-2 rounded-xl bg-white dark:bg-slate-800 border border-[#E5E7EB] dark:border-slate-700 shadow-lg overflow-hidden"
                style="display: none;"
            >
                <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-[#111827] dark:text-slate-200 hover:bg-[#F3F4F6] dark:hover:bg-slate-700/60 transition-colors duration-300 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.964 0a9 9 0 10-11.964 0m11.964 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    View Profile
                </a>
                <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-[#111827] dark:text-slate-200 hover:bg-[#F3F4F6] dark:hover:bg-slate-700/60 transition-colors duration-300 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Account Settings
                </a>
                <div class="border-t border-[#E5E7EB] dark:border-slate-700"></div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0110.5 3h6a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0116.5 21h-6a2.25 2.25 0 01-2.25-2.25V15m-3 0l-3-3m0 0l3-3m-3 3H15" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>

{{-- ============================== --}}
{{-- Custom Scrollbar Styles --}}
{{-- ============================== --}}
<style>
    .admin-sidebar-scroll {
        scrollbar-width: thin;
        scrollbar-color: #E5E7EB transparent;
        overflow-x: hidden;
    }

    .admin-sidebar-scroll::-webkit-scrollbar {
        width: 5px;
        height: 0;
    }

    .admin-sidebar-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .admin-sidebar-scroll::-webkit-scrollbar-thumb {
        background-color: #E5E7EB;
        border-radius: 9999px;
    }

    .dark .admin-sidebar-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(148, 163, 184, 0.25);
    }

    [x-cloak] {
        display: none !important;
    }
</style>
