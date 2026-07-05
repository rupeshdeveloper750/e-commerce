<div x-data="{ showImport: false }" class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
    {{-- Left --}}
    <div>
        {{-- Breadcrumb --}}
        <nav class="mb-3 flex items-center gap-2 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 transition hover:text-[#B88A44]">
                Dashboard
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-500">Catalog</span>
            <span class="text-gray-400">/</span>
            <span class="font-medium text-[#B88A44]">Brands</span>
        </nav>

        {{-- Title --}}
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            Brands
        </h1>

        {{-- Description --}}
        <p class="mt-2 text-sm text-gray-500 dark:text-slate-400">
            Manage all product brands, add logos and mark featured brands.
        </p>
    </div>

    {{-- Right --}}
    <div class="flex flex-wrap items-center gap-3">
        {{-- Import CSV --}}
        <button
            type="button"
            @click="showImport = true"
            class="inline-flex h-12 items-center gap-2 rounded-xl border border-slate-700 bg-slate-800 px-5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
            </svg>
            Import CSV
        </button>

        {{-- Export CSV --}}
        <a
            href="{{ route('admin.brands.export') }}"
            class="inline-flex h-12 items-center gap-2 rounded-xl border border-slate-700 bg-slate-800 px-5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Export CSV
        </a>

        {{-- Add Brand --}}
        <a
            href="{{ route('admin.brands.create') }}"
            class="inline-flex h-12 items-center gap-2 rounded-xl bg-[#B88A44] px-5 text-sm font-semibold text-white shadow-lg shadow-[#B88A44]/20 transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#a67936]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Brand
        </a>
    </div>

    {{-- Import Modal --}}
    <div
        x-show="showImport"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div
                x-show="showImport"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm"
                @click="showImport = false"
            ></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div
                x-show="showImport"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-slate-900 border border-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 space-y-6"
            >
                <div>
                    <h3 class="text-lg font-bold text-white">Import Brands from CSV</h3>
                    <p class="mt-1 text-sm text-slate-400">Upload a CSV file containing brand records.</p>
                </div>

                <form action="{{ route('admin.brands.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-200 mb-2">CSV File</label>
                        <input
                            type="file"
                            name="csv_file"
                            accept=".csv"
                            required
                            class="w-full rounded-xl border border-slate-700 bg-slate-950/60 px-4 py-3 text-sm text-white transition focus:outline-none"
                        >
                    </div>

                    <div class="mt-5 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="showImport = false"
                            class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-300 hover:text-white transition"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="rounded-xl bg-[#B88A44] px-5 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:bg-[#a67936]"
                        >
                            Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
