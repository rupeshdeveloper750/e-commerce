<div class="rounded-3xl border border-slate-800 bg-[#111827] p-6 shadow-xl">

    <form action="{{ route('admin.categories.index') }}" method="GET">

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">

            {{-- Search --}}
            <div class="lg:col-span-6">

                <label class="mb-2 block text-sm font-semibold text-slate-300">
                    Search
                </label>

                <div class="relative">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>

                    </svg>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search category..."
                        class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 pl-12 pr-5 text-white placeholder:text-slate-500 transition-all duration-300 focus:border-[#B88A44] focus:ring-4 focus:ring-[#B88A44]/20">

                </div>

            </div>

            {{-- Parent Category --}}
            <div class="lg:col-span-3">

                <label class="mb-2 block text-sm font-semibold text-slate-300">
                    Parent Category
                </label>

                <select
                    name="parent"
                    class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white transition-all duration-300 focus:border-[#B88A44] focus:ring-4 focus:ring-[#B88A44]/20">

                    <option value="">All Categories</option>

                    {{-- Future --}}
                    {{-- @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach --}}

                </select>

            </div>

            {{-- Status --}}
            <div class="lg:col-span-3">

                <label class="mb-2 block text-sm font-semibold text-slate-300">
                    Status
                </label>

                <select
                    name="status"
                    class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white transition-all duration-300 focus:border-[#B88A44] focus:ring-4 focus:ring-[#B88A44]/20">

                    <option value="">All</option>

                    <option value="1"
                        {{ request('status') == '1' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ request('status') == '0' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

            </div>

        </div>

        {{-- Footer --}}
        <div
            class="mt-8 flex flex-col gap-4 border-t border-slate-800 pt-6 sm:flex-row sm:items-center sm:justify-between">

            {{-- Left --}}
            <div class="text-sm text-slate-400">

                Search categories by name, status or parent category.

            </div>

            {{-- Right --}}
            <div class="flex flex-wrap justify-end gap-3">

                <a
                    href="{{ route('admin.categories.index') }}"
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