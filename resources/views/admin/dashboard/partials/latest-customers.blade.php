<div class="rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5 dark:border-slate-800">

        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Latest Customers
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Recently joined customers
            </p>
        </div>

        <a href="{{ route('admin.customers.index') }}"
            class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium transition hover:bg-gray-100 dark:border-slate-700 dark:hover:bg-slate-800">

            View All

        </a>

    </div>

    {{-- Customers --}}
    <div class="divide-y divide-gray-100 dark:divide-slate-800">

        @foreach($latestCustomers as $customer)

        <div class="flex items-center justify-between p-5 transition hover:bg-gray-50 dark:hover:bg-slate-800/40">

            <div class="flex items-center gap-4">

                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}"
                    class="h-12 w-12 rounded-full">

                <div>

                    <h4 class="font-semibold text-gray-900 dark:text-white">
                        {{ $customer->name }}
                    </h4>

                    <p class="text-sm text-gray-500">
                        {{ $customer->email }}
                    </p>

                </div>

            </div>

            @if($customer->status)

                <span
                    class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-600">

                    Active

                </span>

            @else

                <span
                    class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">

                    Inactive

                </span>

            @endif

        </div>

        @endforeach

    </div>

</div>
