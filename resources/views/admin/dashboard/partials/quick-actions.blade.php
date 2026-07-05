<div class="rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

    {{-- Header --}}
    <div class="border-b border-gray-200 px-6 py-5 dark:border-slate-800">

        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            Quick Actions
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Frequently used admin shortcuts
        </p>

    </div>

    {{-- Actions --}}
    <div class="grid grid-cols-2 gap-4 p-6">

        {{-- Add Product --}}
        <a href="{{ route('admin.products.create') }}"
            class="group rounded-2xl border border-gray-200 p-5 transition-all duration-300 hover:-translate-y-1 hover:border-[#B88A44] hover:shadow-lg dark:border-slate-700 dark:hover:border-[#B88A44]">

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#B88A44]/10 text-[#B88A44]">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 4v16m8-8H4"/>

                </svg>

            </div>

            <h4 class="mt-4 font-semibold text-gray-900 dark:text-white">
                Add Product
            </h4>

            <p class="mt-1 text-sm text-gray-500">
                Create new product
            </p>

        </a>

        {{-- Categories --}}
        <a href="{{ route('admin.categories.index', ['parent_id' => 'null']) }}"
            class="group rounded-2xl border border-gray-200 p-5 transition-all duration-300 hover:-translate-y-1 hover:border-[#B88A44] hover:shadow-lg dark:border-slate-700 dark:hover:border-[#B88A44]">

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600">

                📂

            </div>

            <h4 class="mt-4 font-semibold dark:text-white">
                Categories
            </h4>

            <p class="mt-1 text-sm text-gray-500">
                Manage categories
            </p>

        </a>

        {{-- Orders --}}
        <a href="{{ route('admin.orders.index') }}"
            class="group rounded-2xl border border-gray-200 p-5 transition-all duration-300 hover:-translate-y-1 hover:border-[#B88A44] hover:shadow-lg dark:border-slate-700 dark:hover:border-[#B88A44]">

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-600">

                🛒

            </div>

            <h4 class="mt-4 font-semibold dark:text-white">
                Orders
            </h4>

            <p class="mt-1 text-sm text-gray-500">
                View orders
            </p>

        </a>

        {{-- Customers --}}
        <a href="{{ route('admin.customers.index') }}"
            class="group rounded-2xl border border-gray-200 p-5 transition-all duration-300 hover:-translate-y-1 hover:border-[#B88A44] hover:shadow-lg dark:border-slate-700 dark:hover:border-[#B88A44]">

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-600">

                👥

            </div>

            <h4 class="mt-4 font-semibold dark:text-white">
                Customers
            </h4>

            <p class="mt-1 text-sm text-gray-500">
                Manage customers
            </p>

        </a>

    </div>

</div>
