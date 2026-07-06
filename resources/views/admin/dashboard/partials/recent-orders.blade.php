<div class="rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5 dark:border-slate-800">

        <div>

            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Recent Orders
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Latest customer purchases
            </p>

        </div>

        <a href="{{ route('admin.orders.index') }}"
            class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium transition hover:bg-gray-100 dark:border-slate-700 dark:hover:bg-slate-800">

            View All

        </a>

    </div>

    {{-- Responsive Table --}}
    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-gray-50 dark:bg-slate-800">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        Customer
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        Order
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        Amount
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        Payment
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        Status
                    </th>

                    <th class="px-6 py-4"></th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-slate-800">

                @foreach ($recentOrders as $order)

                <tr class="transition hover:bg-gray-50 dark:hover:bg-slate-800/40">

                    {{-- Customer --}}
                    <td class="px-6 py-5">

                        <div class="flex items-center gap-3">

                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode($order->first_name . ' ' . $order->last_name) }}"
                                class="h-10 w-10 rounded-full">

                            <div>

                                <h4 class="font-semibold text-gray-900 dark:text-white">

                                    {{ $order->first_name }} {{ $order->last_name }}

                                </h4>

                                <p class="text-xs text-gray-500">

                                    Customer

                                </p>

                            </div>

                        </div>

                    </td>

                    {{-- Order ID --}}
                    <td class="px-6 py-5 font-medium">

                        {{ $order->order_number }}

                    </td>

                    {{-- Amount --}}
                    <td class="px-6 py-5 font-semibold text-[#B88A44]">

                        ₹{{ number_format($order->total, 2) }}

                    </td>

                    {{-- Payment --}}
                    <td class="px-6 py-5">

                        @if($order->payment_status == 'paid')

                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-600">
                                Paid
                            </span>

                        @elseif($order->payment_status == 'pending')

                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-600">
                                Pending
                            </span>

                        @else

                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600">
                                Failed
                            </span>

                        @endif

                    </td>

                    {{-- Delivery --}}
                    <td class="px-6 py-5">

                        @if($order->status == 'delivered')
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-600">
                                Delivered
                            </span>
                        @elseif($order->status == 'cancelled')
                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600">
                                Cancelled
                            </span>
                        @elseif($order->status == 'shipped')
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-600">
                                Shipped
                            </span>
                        @else
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-600">
                                {{ ucfirst($order->status) }}
                            </span>
                        @endif

                    </td>

                    {{-- Action --}}
                    <td class="px-6 py-5 text-right">

                        <button
                            class="rounded-lg p-2 transition hover:bg-gray-100 dark:hover:bg-slate-700">

                            ⋮

                        </button>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>
