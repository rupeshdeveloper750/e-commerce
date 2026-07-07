<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">

    @foreach([
        [
            'title' => 'Revenue',
            'value' => '₹' . number_format($totalRevenue, 2),
            'change' => $revenueGrowth,
            'color' => 'amber',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#B88A44]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        ],
        [
            'title' => 'Orders',
            'value' => number_format($totalOrders),
            'change' => $ordersGrowth,
            'color' => 'blue',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>'
        ],
        [
            'title' => 'Products',
            'value' => number_format($totalProducts),
            'change' => $productsGrowth,
            'color' => 'emerald',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7L12 3 4 7v10l8 4 8-4V7Z" /></svg>'
        ],
        [
            'title' => 'Customers',
            'value' => number_format($totalCustomers),
            'change' => $customersGrowth,
            'color' => 'purple',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>'
        ]
    ] as $card)

    @php
        $isNegative = str_starts_with($card['change'], '-');
        $badgeClass = $isNegative 
            ? 'bg-rose-500/10 text-rose-600 dark:text-rose-450 border border-rose-500/20' 
            : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20';
    @endphp

    <div
        class="relative overflow-hidden rounded-3xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:border-slate-800 dark:bg-slate-900/60 dark:backdrop-blur-md">
        
        {{-- Decorative Gradient Background Glow --}}
        <div class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-[#B88A44]/5 blur-2xl"></div>

        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-500 dark:text-slate-400">
                {{ $card['title'] }}
            </span>
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badgeClass }}">
                {{ $card['change'] }}
            </span>
        </div>

        <div class="mt-5 flex items-end justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="truncate text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white 2xl:text-3xl" title="{{ $card['value'] }}">
                    {{ $card['value'] }}
                </h2>
                <p class="mt-2 text-xs text-gray-400 dark:text-slate-500">
                    Compared to last month
                </p>
            </div>
            <div class="ml-4 flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gray-50 border border-gray-100 shadow-inner dark:border-slate-700/50 dark:bg-slate-800/80">
                {!! $card['icon'] !!}
            </div>
        </div>

    </div>

    @endforeach

</div>
