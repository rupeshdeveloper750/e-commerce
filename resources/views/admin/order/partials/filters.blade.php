<div class="rounded-3xl border border-slate-800 bg-[#111827] p-6 shadow-xl">
    <form action="{{ route('admin.orders.index') }}" method="GET">
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
            {{-- Search --}}
            <div class="lg:col-span-4">
                <label class="mb-2 block text-sm font-semibold text-slate-300">Search</label>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Order #, customer name/email..."
                        class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 pl-12 pr-5 text-white placeholder:text-slate-500 transition-all duration-300 focus:border-[#B88A44] focus:ring-4 focus:ring-[#B88A44]/20">
                </div>
            </div>

            {{-- Fulfillment status --}}
            <div class="lg:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-slate-300">Fulfillment</label>
                <select
                    name="status"
                    class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white transition-all duration-300 focus:border-[#B88A44] focus:ring-4 focus:ring-[#B88A44]/20">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Payment status --}}
            <div class="lg:col-span-3">
                <label class="mb-2 block text-sm font-semibold text-slate-300">Payment Status</label>
                <select
                    name="payment_status"
                    class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white transition-all duration-300 focus:border-[#B88A44] focus:ring-4 focus:ring-[#B88A44]/20">
                    <option value="">All Payments</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            {{-- Trash state --}}
            <div class="lg:col-span-3">
                <label class="mb-2 block text-sm font-semibold text-slate-300">Trash State</label>
                <select
                    name="trashed"
                    class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white transition-all duration-300 focus:border-[#B88A44] focus:ring-4 focus:ring-[#B88A44]/20">
                    <option value="">Active Only</option>
                    <option value="with" {{ request('trashed') === 'with' ? 'selected' : '' }}>Include Trashed</option>
                    <option value="only" {{ request('trashed') === 'only' ? 'selected' : '' }}>Trashed Only</option>
                </select>
            </div>
        </div>

        {{-- Sorting & Submit --}}
        <div class="mt-8 flex flex-col gap-4 border-t border-slate-800 pt-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <select
                    name="sort_by"
                    class="h-10 rounded-xl border border-slate-700 bg-slate-800 px-3 text-sm text-slate-300 focus:border-[#B88A44] focus:ring-2 focus:ring-[#B88A44]/20">
                    <option value="order_number" {{ request('sort_by') === 'order_number' ? 'selected' : '' }}>Sort by Order #</option>
                    <option value="total" {{ request('sort_by') === 'total' ? 'selected' : '' }}>Sort by Total Amount</option>
                    <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Sort by Date</option>
                </select>
                <select
                    name="sort_order"
                    class="h-10 rounded-xl border border-slate-700 bg-slate-800 px-3 text-sm text-slate-300 focus:border-[#B88A44] focus:ring-2 focus:ring-[#B88A44]/20">
                    <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort_order', 'desc') === 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>

            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('admin.orders.index') }}"
                    class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 px-6 text-sm font-semibold text-slate-300 transition-all duration-300 hover:border-slate-500 hover:bg-slate-700">
                    Reset
                </a>
                <button
                    type="submit"
                    class="inline-flex h-12 items-center justify-center rounded-xl bg-[#B88A44] px-8 text-sm font-semibold text-white shadow-lg shadow-[#B88A44]/20 transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#a87832]">
                    Search
                </button>
            </div>
        </div>
    </form>
</div>
