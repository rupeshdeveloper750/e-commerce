<div
    class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Sales Analytics
            </h3>

            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                Revenue overview for your store.
            </p>

        </div>

        <div class="flex gap-2">

            <button
                class="rounded-xl bg-[#B88A44] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#a67839]">

                7 Days

            </button>

            <button
                class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium dark:border-slate-700">

                30 Days

            </button>

            <button
                class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium dark:border-slate-700">

                12 Months

            </button>

        </div>

    </div>

    {{-- Chart --}}
    <div
        id="salesChart"
        class="mt-8 h-[380px]">
    </div>

</div>

@push('scripts')

<script>
(function () {
    var salesChartInstance = null;

    function initSalesChart() {
        var chartEl = document.querySelector("#salesChart");
        if (!chartEl) return;

        // Destroy existing chart if any (prevents duplicates on HTMX re-navigation)
        if (salesChartInstance) {
            salesChartInstance.destroy();
            salesChartInstance = null;
        }

        var options = {
            chart: {
                type: 'area',
                height: 380,
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            stroke: {
                curve: 'smooth',
                width: 4
            },
            colors: ['#B88A44'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: .35,
                    opacityTo: .03
                }
            },
            grid: {
                borderColor: '#ECECEC'
            },
            dataLabels: { enabled: false },
            series: [{
                name: 'Revenue',
                data: [20,35,28,45,65,72,90,84,100,110,130,145]
            }],
            xaxis: {
                categories: [
                    'Jan','Feb','Mar','Apr','May','Jun',
                    'Jul','Aug','Sep','Oct','Nov','Dec'
                ]
            },
            yaxis: {
                labels: {
                    formatter: function(val){ return "\u20B9"+val+"K"; }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val){ return "\u20B9"+val+"K"; }
                }
            }
        };

        salesChartInstance = new ApexCharts(chartEl, options);
        salesChartInstance.render();
    }

    // Run on initial full page load
    document.addEventListener('DOMContentLoaded', initSalesChart);

    // Run after HTMX swaps the body (hx-boost navigation)
    document.addEventListener('htmx:afterSwap', initSalesChart);
})();
</script>

@endpush
