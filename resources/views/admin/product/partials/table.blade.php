<div x-data="{
    selectedIds: [],
    selectAll: false,
    toggleAll() {
        this.selectAll = !this.selectAll;
        this.selectedIds = [];
        if (this.selectAll) {
            document.querySelectorAll('.item-checkbox').forEach(el => {
                this.selectedIds.push(parseInt(el.value));
            });
        }
    },
    toggleItem(id) {
        id = parseInt(id);
        const index = this.selectedIds.indexOf(id);
        if (index > -1) {
            this.selectedIds.splice(index, 1);
        } else {
            this.selectedIds.push(id);
        }
        this.selectAll = this.selectedIds.length === document.querySelectorAll('.item-checkbox').length;
    }
}" class="mt-8 relative">

    {{-- Bulk Action Floating Bar --}}
    <div
        x-show="selectedIds.length > 0"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        class="fixed bottom-6 left-1/2 z-50 flex -translate-x-1/2 items-center gap-4 rounded-2xl border border-gray-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 px-6 py-4 shadow-2xl backdrop-blur-md"
        style="display: none;"
    >
        <span class="text-sm font-medium text-gray-600 dark:text-slate-300">
            Selected: <span class="font-semibold text-gray-900 dark:text-white" x-text="selectedIds.length"></span>
        </span>
        <div class="h-4 w-px bg-gray-200 dark:bg-slate-800"></div>
        <form action="{{ route('admin.products.bulk-action') }}" method="POST" class="flex items-center gap-3">
            @csrf
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <select
                name="action"
                class="rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-1.5 text-xs text-gray-700 dark:text-slate-200 focus:border-[#B88A44] focus:outline-none"
            >
                <option value="delete">Soft Delete Selected</option>
                <option value="restore">Restore Selected</option>
                <option value="force_delete">Force Delete Selected</option>
            </select>
            <button
                type="submit"
                class="rounded-xl bg-amber-500 px-4 py-1.5 text-xs font-semibold text-slate-950 hover:bg-amber-400 transition"
            >
                Apply
            </button>
        </form>
    </div>

    {{-- Table Box --}}
    <div class="overflow-hidden rounded-3xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-[#111827] shadow-sm dark:shadow-xl transition-colors duration-300">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-slate-800 px-6 py-5">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Products List</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">View status, check inventory alerts, and configure item models.</p>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 px-4 py-2 text-sm text-gray-600 dark:text-slate-300">
                Total : <span class="font-semibold text-gray-900 dark:text-white">{{ $products->total() }}</span>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-b border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-900">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input
                                type="checkbox"
                                :checked="selectAll"
                                @click="toggleAll()"
                                class="h-4 w-4 rounded border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-[#B88A44] focus:ring-[#B88A44]"
                            >
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-slate-300">Image</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-slate-300">Product</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-slate-300">Category / Brand</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-slate-300">Price</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-slate-300">Stock Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-slate-300">Visibility</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600 dark:text-slate-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-900/40 transition {{ $product->trashed() ? 'bg-red-50/50 dark:bg-red-950/5' : '' }}">
                            <td class="px-6 py-4">
                                <input
                                    type="checkbox"
                                    :value="{{ $product->id }}"
                                    :checked="selectedIds.includes({{ $product->id }})"
                                    @click="toggleItem({{ $product->id }})"
                                    class="item-checkbox h-4 w-4 rounded border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-[#B88A44] focus:ring-[#B88A44]"
                                >
                            </td>
                            <td class="px-6 py-4">
                                @if ($product->featuredImage)
                                    <img src="{{ asset('storage/' . $product->featuredImage->image_path) }}" class="h-10 w-10 rounded-lg object-cover border border-gray-200 dark:border-slate-800" alt="{{ $product->name }}">
                                @elseif ($product->images->first())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="h-10 w-10 rounded-lg object-cover border border-gray-200 dark:border-slate-800" alt="{{ $product->name }}">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-500 font-bold border border-gray-200 dark:border-slate-700">
                                        {{ strtoupper(substr($product->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    {{ $product->name }}
                                    @if ($product->trashed())
                                        <span class="inline-flex items-center rounded-md bg-red-400/10 px-1.5 py-0.5 text-2xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">
                                            Trashed
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-400 dark:text-slate-500 font-normal mt-0.5">SKU: {{ $product->sku }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-slate-400">
                                <div>{{ $product->category ? $product->category->name : '-' }}</div>
                                <div class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">{{ $product->brand ? $product->brand->name : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                @if ($product->sale_price)
                                    <span class="text-amber-500">₹{{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-xs text-gray-400 dark:text-slate-500 line-through ml-1.5">₹{{ number_format($product->price, 2) }}</span>
                                @else
                                    ₹{{ number_format($product->price, 2) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-slate-400">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-700 dark:text-slate-200">{{ $product->quantity }} units</span>
                                    @if ($product->quantity <= 0)
                                        <span class="inline-flex items-center rounded bg-red-400/10 px-1.5 py-0.5 text-2xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Out of Stock</span>
                                    @elseif ($product->quantity <= 5)
                                        <span class="inline-flex items-center rounded bg-amber-400/10 px-1.5 py-0.5 text-2xs font-medium text-amber-400 ring-1 ring-inset ring-amber-400/20">Low Stock</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $product->status ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-500 border border-gray-200 dark:border-slate-800' }}">
                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2.5">
                                    @if (!$product->trashed())
                                        <a
                                            href="{{ route('admin.products.edit', $product->id) }}"
                                            class="rounded-lg border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white transition"
                                        >
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to soft delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-rose-200 dark:border-rose-950 bg-rose-50 dark:bg-rose-950/20 px-3 py-1.5 text-xs font-semibold text-rose-500 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-950/50 hover:text-rose-600 dark:hover:text-rose-300 transition"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-emerald-950 bg-emerald-950/20 px-3 py-1.5 text-xs font-semibold text-emerald-400 hover:bg-emerald-950/50 hover:text-emerald-300 transition"
                                            >
                                                Restore
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST" onsubmit="return confirm('WARNING: Permanently delete this product? All image files, attributes, and variants will be deleted.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-red-950 bg-red-950/30 px-3 py-1.5 text-xs font-semibold text-red-500 hover:bg-red-950/60 hover:text-red-400 transition"
                                            >
                                                Force Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-slate-500">
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="border-t border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-900 px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
