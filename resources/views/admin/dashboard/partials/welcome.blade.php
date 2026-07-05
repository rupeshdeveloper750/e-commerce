<div
    class="relative overflow-hidden rounded-3xl border border-gray-200 bg-gradient-to-r from-white via-white to-amber-50 p-6 shadow-sm dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800">

    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <p class="text-sm font-medium text-[#B88A44]">

                👋 Welcome Back

            </p>

            <h2 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">

                {{ auth('admin')->user()->name ?? 'Admin' }}

            </h2>

            <p class="mt-2 text-gray-500 dark:text-slate-400">

                Manage your products, orders, customers and grow your business from one place.

            </p>

        </div>

        <div class="flex gap-3">

            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-[#B88A44] px-5 py-3 text-white transition hover:scale-105 hover:bg-[#a6793d]">

                + Add Product

            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-gray-700 dark:text-slate-300 transition hover:bg-gray-100 dark:border-slate-700 dark:bg-slate-900 dark:hover:bg-slate-800">

                View Orders

            </a>

        </div>

    </div>

</div>
