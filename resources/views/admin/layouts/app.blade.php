<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'ShopMe Admin')</title>

    <meta name="description" content="@yield('meta_description', 'ShopMe Admin Dashboard')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body
    x-data="{ sidebarOpen: false, darkMode: false }"
    x-init="
        darkMode = localStorage.getItem('darkMode') === 'true';
        if(darkMode){
            document.documentElement.classList.add('dark');
        }
    "
    :class="{ 'dark': darkMode }"
    class="h-full bg-gray-100 text-gray-800 antialiased dark:bg-slate-950 dark:text-white">

    <div class="min-h-screen">

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

    </div>

    @stack('scripts')

</body>

</html>
