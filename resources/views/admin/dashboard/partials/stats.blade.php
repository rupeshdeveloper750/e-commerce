<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">

    @foreach([
        ['title'=>'Revenue','value'=>'₹2,45,680','change'=>'+18%','color'=>'green'],
        ['title'=>'Orders','value'=>'1,248','change'=>'+12%','color'=>'blue'],
        ['title'=>'Products','value'=>'1,032','change'=>'+8%','color'=>'amber'],
        ['title'=>'Customers','value'=>'856','change'=>'+25%','color'=>'purple']
    ] as $card)

    <div
        class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:border-slate-800 dark:bg-slate-900">

        <div class="flex items-center justify-between">

            <span class="text-sm text-gray-500">

                {{ $card['title'] }}

            </span>

            <span
                class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-600">

                {{ $card['change'] }}

            </span>

        </div>

        <h2 class="mt-5 text-4xl font-bold text-gray-900 dark:text-white">

            {{ $card['value'] }}

        </h2>

        <p class="mt-3 text-sm text-gray-500">

            Compared to last month

        </p>

    </div>

    @endforeach

</div>