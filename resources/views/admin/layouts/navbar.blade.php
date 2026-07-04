
<header
    class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-gray-200 dark:bg-slate-900/80 dark:border-slate-800">

    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">

        {{-- Left --}}
        <div class="flex items-center gap-4">

            {{-- Mobile Sidebar Toggle --}}
            <button
                @click="sidebarOpen = true"
                class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />

                </svg>

            </button>

            {{-- Breadcrumb --}}
            <div>

                <p class="text-xs uppercase tracking-widest text-gray-400">
                    ShopMe Admin
                </p>

                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">

                    @yield('page-title','Dashboard')

                </h1>

            </div>

        </div>

        {{-- Right --}}
        <div class="flex items-center gap-3">

            {{-- Search --}}
            <div class="hidden md:flex relative">

                <input
                    type="text"
                    placeholder="Search..."
                    class="w-64 rounded-xl border border-gray-200 bg-gray-50 py-2 pl-10 pr-4 text-sm outline-none focus:border-[#B88A44] focus:ring-2 focus:ring-[#B88A44]/20 dark:bg-slate-800 dark:border-slate-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>

                </svg>

            </div>

            {{-- Dark Mode --}}
            <button
                @click="
                    darkMode=!darkMode;
                    localStorage.setItem('darkMode',darkMode);
                    document.documentElement.classList.toggle('dark');
                "
                class="flex items-center justify-center w-10 h-10 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 transition">

                🌙

            </button>

            {{-- Notification --}}
            <button
                class="relative flex items-center justify-center w-10 h-10 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 transition">

                🔔

                <span
                    class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white">
                </span>

            </button>

            {{-- Profile --}}
            <div x-data="{open:false}" class="relative">

                <button
                    @click="open=!open"
                    class="flex items-center gap-3 rounded-xl px-2 py-2 hover:bg-gray-100 dark:hover:bg-slate-800 transition">

                    <img
                        src="https://ui-avatars.com/api/?name=Admin"
                        class="w-10 h-10 rounded-full">

                    <div class="hidden sm:block text-left">

                        <h3 class="text-sm font-semibold">

                            {{ auth('admin')->user()->name ?? 'Admin' }}

                        </h3>

                        <p class="text-xs text-gray-500">

                            Administrator

                        </p>

                    </div>

                </button>

                {{-- Dropdown --}}
                <div
                    x-show="open"
                    @click.outside="open=false"
                    x-transition
                    class="absolute right-0 mt-3 w-56 rounded-2xl bg-white border border-gray-200 shadow-xl dark:bg-slate-900 dark:border-slate-700">

                    <a href="#"
                        class="block px-5 py-3 hover:bg-gray-50 dark:hover:bg-slate-800">

                        My Profile

                    </a>

                    <a href="#"
                        class="block px-5 py-3 hover:bg-gray-50 dark:hover:bg-slate-800">

                        Settings

                    </a>

                    <hr class="dark:border-slate-700">

                    <form method="POST" action="#">

                        @csrf

                        <button
                            class="w-full text-left px-5 py-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">

                            Logout

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</header>