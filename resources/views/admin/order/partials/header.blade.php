<div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
    {{-- Left --}}
    <div>
        {{-- Breadcrumb --}}
        <nav class="mb-3 flex items-center gap-2 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 transition hover:text-[#B88A44]">
                Dashboard
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-500">Sales</span>
            <span class="text-gray-400">/</span>
            <span class="font-medium text-[#B88A44]">Orders</span>
        </nav>

        {{-- Title --}}
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            Orders Dashboard
        </h1>

        {{-- Description --}}
        <p class="mt-2 text-sm text-gray-500 dark:text-slate-400">
            Fulfill packages, track payment clearances and inspect customer invoices.
        </p>
    </div>

    {{-- Right --}}
    <div class="flex flex-wrap items-center gap-3">
        {{-- Export CSV --}}
        <a
            href="{{ route('admin.orders.export') }}"
            hx-boost="false"
            class="inline-flex h-12 items-center gap-2 rounded-xl border border-slate-700 bg-slate-800 px-5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Export Orders CSV
        </a>
    </div>
</div>
