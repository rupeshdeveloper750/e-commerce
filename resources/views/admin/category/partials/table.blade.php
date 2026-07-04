<div class="mt-8 overflow-hidden rounded-3xl border border-slate-800 bg-[#111827] shadow-xl">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-800 px-6 py-5">

        <div>
            <h3 class="text-lg font-semibold text-white">
                Categories
            </h3>

            <p class="mt-1 text-sm text-slate-400">
                Manage all categories from here.
            </p>
        </div>

        <div
            class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-sm text-slate-300">

            Total : <span class="font-semibold text-white">0</span>

        </div>

    </div>

    {{-- Responsive --}}
    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="border-b border-slate-800 bg-slate-900">

                <tr>

                    <th class="px-6 py-4 text-left">

                        <input
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-[#B88A44]">

                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">

                        Image

                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">

                        Category

                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">

                        Parent

                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">

                        Products

                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">

                        Status

                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">

                        Created

                    </th>

                    <th class="px-6 py-4 text-center text-sm font-semibold text-slate-300">

                        Action

                    </th>

                </tr>

            </thead>

            <tbody>

                {{-- Empty State --}}
                <tr>

                    <td colspan="8">

                        <div class="flex flex-col items-center justify-center py-20">

                            <div
                                class="flex h-20 w-20 items-center justify-center rounded-full bg-slate-800">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-10 w-10 text-slate-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M3 7l9-4 9 4-9 4-9-4zm0 5l9 4 9-4m-9 4v8"/>

                                </svg>

                            </div>

                            <h4
                                class="mt-6 text-xl font-semibold text-white">

                                No Categories Found

                            </h4>

                            <p
                                class="mt-2 text-center text-sm text-slate-400">

                                Start by creating your first category.

                            </p>

                        <a href="{{ route('admin.categories.create') }}"
                        class="inline-flex items-center rounded-xl bg-[#B88A44] px-6 py-3 font-medium text-white transition hover:bg-[#a67839]">
                        + Add Category
                        </a>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>