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

        // Clear container to prevent duplicate overlay renders
        chartEl.innerHTML = "";

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
                data: {!! json_encode($chartData) !!}
            }],
            xaxis: {
                categories: {!! json_encode($chartLabels) !!}
            },
            yaxis: {
                labels: {
                    formatter: function(val){ return "\u20B9" + Number(val).toLocaleString('en-IN'); }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val){ return "\u20B9" + Number(val).toLocaleString('en-IN'); }
                }
            }
        };

        salesChartInstance = new ApexCharts(chartEl, options);
        salesChartInstance.render();
    }

    // Run on initial page load if already loaded, otherwise listen for event
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        initSalesChart();
    } else {
        document.addEventListener('DOMContentLoaded', initSalesChart);
    }

    // Run after HTMX swaps the body (hx-boost navigation)
    document.addEventListener('htmx:afterSwap', initSalesChart);
})();
</script>

@endpush
