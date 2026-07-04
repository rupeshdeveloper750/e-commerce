<footer class="border-t border-gray-200 bg-white dark:border-slate-800 dark:bg-slate-900">

    <div class="px-4 py-4 sm:px-6 lg:px-8">

        <div class="flex flex-col items-center justify-between gap-3 text-sm md:flex-row">

            {{-- Copyright --}}
            <div class="flex items-center gap-2 text-gray-500 dark:text-slate-400">

                <span class="font-medium text-gray-700 dark:text-slate-200">
                    © {{ date('Y') }}
                </span>

                <span>
                    Shop<span class="font-semibold text-[#B88A44]">Me</span>
                </span>

                <span class="hidden sm:inline">
                    • All rights reserved.
                </span>

            </div>

            {{-- Version --}}
            <div
                class="flex items-center gap-2 rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs font-medium text-gray-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">

                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                Version 1.0.0

            </div>

            {{-- Credits --}}
            <div class="flex items-center gap-2 text-gray-500 dark:text-slate-400">

                <span>Crafted with</span>

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4 fill-red-500"
                     viewBox="0 0 24 24">

                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>

                </svg>

                <span>
                    by
                    <span class="font-semibold text-[#B88A44]">
                        Rupesh Kumar
                    </span>
                </span>

            </div>

        </div>

    </div>

</footer>