<div
    class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

    {{-- Left --}}
    <div>

        {{-- Breadcrumb --}}
        <nav class="mb-3 flex items-center gap-2 text-sm">

            <a href="{{ route('admin.dashboard') }}"
                class="text-gray-500 transition hover:text-[#B88A44]">

                Dashboard

            </a>

            <span class="text-gray-400">

                /

            </span>

            <span class="text-gray-500">

                Catalog

            </span>

            <span class="text-gray-400">

                /

            </span>

            <span class="font-medium text-[#B88A44]">

                Categories

            </span>

        </nav>

        {{-- Title --}}
        <h1
            class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">

            Categories

        </h1>

        {{-- Description --}}
        <p
            class="mt-2 text-sm text-gray-500 dark:text-slate-400">

            Manage all product categories, organize your catalog and improve navigation.

        </p>

    </div>

    {{-- Right --}}
    <div
        class="flex flex-wrap items-center gap-3">

        {{-- Export (Future) --}}
        <button
            type="button"
            disabled
            class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-gray-400 shadow-sm cursor-not-allowed dark:border-slate-700 dark:bg-slate-900">

            Export

        </button>

        {{-- Add Category --}}
        <button
            type="button"
            data-modal-target="categoryModal"
            data-modal-toggle="categoryModal"
            class="inline-flex items-center gap-2 rounded-2xl bg-[#B88A44] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-[#B88A44]/20 transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#a67936]">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4"/>

            </svg>

            Add Category

        </button>

    </div>

</div>