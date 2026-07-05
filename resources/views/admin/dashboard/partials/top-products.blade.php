<div class="rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5 dark:border-slate-800">

        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Top Products
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Best selling this month
            </p>
        </div>

        <button
            class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium transition hover:bg-gray-100 dark:border-slate-700 dark:hover:bg-slate-800">
            View All
        </button>

    </div>

    {{-- Products --}}
    <div class="divide-y divide-gray-100 dark:divide-slate-800">

        @foreach([
            [
                'name'=>'Nike Air Max',
                'category'=>'Shoes',
                'price'=>'₹4,999',
                'sales'=>92
            ],
            [
                'name'=>'Apple Watch',
                'category'=>'Electronics',
                'price'=>'₹28,999',
                'sales'=>80
            ],
            [
                'name'=>'Leather Backpack',
                'category'=>'Fashion',
                'price'=>'₹2,499',
                'sales'=>68
            ],
            [
                'name'=>'Wireless Headphones',
                'category'=>'Electronics',
                'price'=>'₹6,999',
                'sales'=>60
            ],
        ] as $product)

        <div class="flex items-center gap-4 p-5 transition hover:bg-gray-50 dark:hover:bg-slate-800/40">

            {{-- Product Image --}}
            <div
                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 dark:bg-slate-800">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-7 w-7 text-[#B88A44]"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M20 7L12 3 4 7v10l8 4 8-4V7Z"/>

                </svg>

            </div>

            {{-- Details --}}
            <div class="flex-1">

                <div class="flex items-center justify-between">

                    <div>

                        <h4 class="font-semibold text-gray-900 dark:text-white">
                            {{ $product['name'] }}
                        </h4>

                        <p class="text-sm text-gray-500">
                            {{ $product['category'] }}
                        </p>

                    </div>

                    <div class="text-right">

                        <p class="font-bold text-[#B88A44]">
                            {{ $product['price'] }}
                        </p>

                    </div>

                </div>

                {{-- Progress --}}
                <div class="mt-4">

                    <div class="mb-2 flex justify-between text-xs text-gray-500">

                        <span>Sales</span>

                        <span>{{ $product['sales'] }}%</span>

                    </div>

                    <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-slate-700">

                    <div
                    class="h-full rounded-full bg-[#B88A44]"
                    style="--progress: {{ $product['sales'] }}%; width: var(--progress);">
                    </div>

                    </div>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>
