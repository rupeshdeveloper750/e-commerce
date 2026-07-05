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
        class="fixed bottom-6 left-1/2 z-50 flex -translate-x-1/2 items-center gap-4 rounded-2xl border border-slate-800 bg-slate-900/90 px-6 py-4 shadow-2xl backdrop-blur-md"
        style="display: none;"
    >
        <span class="text-sm font-medium text-slate-300">
            Selected: <span class="font-semibold text-white" x-text="selectedIds.length"></span>
        </span>
        <div class="h-4 w-px bg-slate-800"></div>
        <form action="{{ route('admin.categories.bulk-action') }}" method="POST" class="flex items-center gap-3">
            @csrf
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <select
                name="action"
                class="rounded-xl border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs text-slate-200 focus:border-[#B88A44] focus:outline-none"
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
    <div class="overflow-hidden rounded-3xl border border-slate-800 bg-[#111827] shadow-xl">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-5">
            <div>
                <h3 class="text-lg font-semibold text-white">Categories List</h3>
                <p class="mt-1 text-sm text-slate-400">Manage category structure, view active or deleted records.</p>
            </div>
            <div class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-sm text-slate-300">
                Total : <span class="font-semibold text-white">{{ $categories->total() }}</span>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-b border-slate-800 bg-slate-900">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input
                                type="checkbox"
                                :checked="selectAll"
                                @click="toggleAll()"
                                class="h-4 w-4 rounded border-slate-700 bg-slate-800 text-[#B88A44] focus:ring-[#B88A44]"
                            >
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Image</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Category</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Parent</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Sort Order</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-slate-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-slate-900/40 transition {{ $category->trashed() ? 'bg-red-950/5' : '' }}">
                            <td class="px-6 py-4">
                                <input
                                    type="checkbox"
                                    :value="{{ $category->id }}"
                                    :checked="selectedIds.includes({{ $category->id }})"
                                    @click="toggleItem({{ $category->id }})"
                                    class="item-checkbox h-4 w-4 rounded border-slate-700 bg-slate-800 text-[#B88A44] focus:ring-[#B88A44]"
                                >
                            </td>
                            <td class="px-6 py-4">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" class="h-10 w-10 rounded-lg object-cover border border-slate-800" alt="{{ $category->name }}">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-800 text-slate-500 font-bold border border-slate-700">
                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-white">
                                <div class="flex items-center gap-2">
                                    {{ $category->name }}
                                    @if ($category->trashed())
                                        <span class="inline-flex items-center rounded-md bg-red-400/10 px-1.5 py-0.5 text-2xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">
                                            Trashed
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500 font-normal mt-0.5">{{ $category->slug }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                {{ $category->parent ? $category->parent->name : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                {{ $category->sort_order }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $category->status ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-400 border border-slate-700/50' }}">
                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2.5">
                                    @if (!$category->trashed())
                                        <a
                                            href="{{ route('admin.categories.edit', $category->id) }}"
                                            class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:text-white transition"
                                        >
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to soft delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-rose-950 bg-rose-950/20 px-3 py-1.5 text-xs font-semibold text-rose-400 hover:bg-rose-950/50 hover:text-rose-300 transition"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-emerald-950 bg-emerald-950/20 px-3 py-1.5 text-xs font-semibold text-emerald-400 hover:bg-emerald-950/50 hover:text-emerald-300 transition"
                                            >
                                                Restore
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.categories.force-delete', $category->id) }}" method="POST" onsubmit="return confirm('WARNING: Permanently delete this category? All subcategories images will be deleted.');" class="inline">
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
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($categories->hasPages())
            <div class="border-t border-slate-800 bg-slate-900 px-6 py-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
