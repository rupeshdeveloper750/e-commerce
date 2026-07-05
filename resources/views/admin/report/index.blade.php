@extends('admin.layouts.app')
@section('title', 'Reports & Analytics')

@section('content')
<div class="space-y-6" x-data="reportsPage()">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <nav class="mb-3 flex items-center gap-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-500 hover:text-[#B88A44] transition">Dashboard</a>
                <span class="text-slate-600">/</span>
                <span class="font-medium text-[#B88A44]">Reports</span>
            </nav>
            <h1 class="text-3xl font-bold tracking-tight text-white">Reports & Analytics</h1>
            <p class="mt-1 text-sm text-slate-400">Business performance overview and key metrics.</p>
        </div>
        {{-- Period Selector --}}
        <form method="GET" class="flex items-center gap-2">
            <label class="text-sm text-slate-400 font-medium">Period:</label>
            <select name="period" onchange="this.form.submit()"
                    class="h-10 rounded-xl border border-slate-700 bg-slate-800 px-3 text-sm text-white focus:border-[#B88A44] focus:outline-none transition">
                <option value="7"  {{ $period == 7  ? 'selected' : '' }}>Last 7 days</option>
                <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 days</option>
                <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 days</option>
                <option value="365" {{ $period == 365 ? 'selected' : '' }}>Last Year</option>
            </select>
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Revenue --}}
        <div class="rounded-2xl border border-[#B88A44]/30 bg-gradient-to-br from-[#B88A44]/10 to-transparent p-6">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-[#B88A44]">Revenue</p>
                <span class="text-xs font-bold {{ $revenueGrowth >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                    {{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}%
                </span>
            </div>
            <p class="text-2xl font-bold text-white">₹{{ number_format($totalRevenue, 0) }}</p>
            <p class="mt-1 text-xs text-slate-500">vs previous {{ $period }} days</p>
        </div>

        {{-- Orders --}}
        <div class="rounded-2xl border border-blue-800/30 bg-blue-950/20 p-6">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-400">Orders</p>
                <span class="text-xs font-bold {{ $ordersGrowth >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                    {{ $ordersGrowth >= 0 ? '+' : '' }}{{ $ordersGrowth }}%
                </span>
            </div>
            <p class="text-2xl font-bold text-white">{{ number_format($totalOrders) }}</p>
            <p class="mt-1 text-xs text-slate-500">in last {{ $period }} days</p>
        </div>

        {{-- Customers --}}
        <div class="rounded-2xl border border-violet-800/30 bg-violet-950/20 p-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-violet-400 mb-3">New Customers</p>
            <p class="text-2xl font-bold text-white">{{ number_format($newCustomers) }}</p>
            <p class="mt-1 text-xs text-slate-500">{{ number_format($totalCustomers) }} total</p>
        </div>

        {{-- Avg Order --}}
        <div class="rounded-2xl border border-emerald-800/30 bg-emerald-950/20 p-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-400 mb-3">Avg Order Value</p>
            <p class="text-2xl font-bold text-white">₹{{ number_format($avgOrderValue, 0) }}</p>
            <p class="mt-1 text-xs text-slate-500">per transaction</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Revenue Chart --}}
        <div class="lg:col-span-2 rounded-2xl border border-slate-800 bg-[#111827] p-6 shadow-xl">
            <h3 class="text-base font-bold text-white mb-6">Revenue Over Time</h3>
            @if($revenueChart->count())
            <div class="relative h-48 flex items-end gap-1">
                @php
                    $maxRev = $revenueChart->max('revenue') ?: 1;
                @endphp
                @foreach($revenueChart as $point)
                    @php $pct = ($point->revenue / $maxRev) * 100; @endphp
                    <div class="relative flex-1 group" title="{{ $point->date }}: ₹{{ number_format($point->revenue, 0) }}">
                        <div class="absolute bottom-0 w-full rounded-t-lg bg-gradient-to-t from-[#B88A44] to-[#B88A44]/40 transition-all duration-300 group-hover:opacity-80"
                             style="height: {{ max(4, $pct) }}%">
                        </div>
                        <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 hidden group-hover:block z-10">
                            <div class="bg-slate-800 border border-slate-700 rounded-lg px-2 py-1 text-xs text-white whitespace-nowrap">
                                ₹{{ number_format($point->revenue, 0) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 flex items-center justify-between text-xs text-slate-600">
                <span>{{ $revenueChart->first()?->date }}</span>
                <span>{{ $revenueChart->last()?->date }}</span>
            </div>
            @else
                <div class="flex items-center justify-center h-48 text-slate-600">No revenue data for this period</div>
            @endif
        </div>

        {{-- Orders by Status --}}
        <div class="rounded-2xl border border-slate-800 bg-[#111827] p-6 shadow-xl">
            <h3 class="text-base font-bold text-white mb-6">Orders by Status</h3>
            @php
                $statusColors = ['delivered'=>'emerald','shipped'=>'blue','processing'=>'amber','pending'=>'slate','cancelled'=>'rose'];
                $total = $ordersByStatus->sum();
            @endphp
            <div class="space-y-4">
                @foreach($ordersByStatus as $status => $count)
                    @php
                        $color = $statusColors[$status] ?? 'slate';
                        $pct = $total > 0 ? round(($count / $total) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm text-slate-300 capitalize">{{ $status }}</span>
                            <span class="text-sm font-bold text-white">{{ $count }} <span class="text-xs text-slate-500">({{ $pct }}%)</span></span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-800">
                            <div class="h-2 rounded-full bg-{{ $color }}-500 transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
                @if($ordersByStatus->isEmpty())
                    <p class="text-slate-600 text-sm text-center py-8">No orders data</p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top Products --}}
        <div class="rounded-2xl border border-slate-800 bg-[#111827] p-6 shadow-xl">
            <h3 class="text-base font-bold text-white mb-5">Top Selling Products</h3>
            <div class="space-y-3">
                @forelse($topProducts as $i => $item)
                <div class="flex items-center gap-4 rounded-xl bg-slate-900/40 p-3">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg text-xs font-bold
                        {{ $i === 0 ? 'bg-[#B88A44] text-white' : ($i === 1 ? 'bg-slate-600 text-white' : 'bg-slate-800 text-slate-400') }}">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ $item->product?->name ?? 'Deleted Product' }}</p>
                        <p class="text-xs text-slate-500">{{ number_format($item->total_sold) }} units sold</p>
                    </div>
                    <p class="text-sm font-bold text-[#B88A44]">₹{{ number_format($item->total_revenue, 0) }}</p>
                </div>
                @empty
                <p class="text-slate-600 text-sm text-center py-8">No sales data for this period</p>
                @endforelse
            </div>
        </div>

        {{-- Payment Methods --}}
        <div class="rounded-2xl border border-slate-800 bg-[#111827] p-6 shadow-xl">
            <h3 class="text-base font-bold text-white mb-5">Payment Methods</h3>
            <div class="space-y-4">
                @php $totalPayRev = $paymentMethods->sum('revenue') ?: 1; @endphp
                @forelse($paymentMethods as $pm)
                    @php $pct = round(($pm->revenue / $totalPayRev) * 100); @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm text-slate-300 capitalize">{{ $pm->payment_method ?? 'Unknown' }}</span>
                            <div class="text-right">
                                <span class="text-sm font-bold text-white">₹{{ number_format($pm->revenue, 0) }}</span>
                                <span class="ml-2 text-xs text-slate-500">{{ $pm->count }} orders</span>
                            </div>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-800">
                            <div class="h-2 rounded-full bg-[#B88A44] transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-600 text-sm text-center py-8">No payment data for this period</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function reportsPage() {
    return {};
}
</script>
@endpush
@endsection
