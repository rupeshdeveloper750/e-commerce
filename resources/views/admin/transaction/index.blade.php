@extends('admin.layouts.app')
@section('title', 'Transactions')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <nav class="mb-3 flex items-center gap-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-500 hover:text-[#B88A44] transition">Dashboard</a>
                <span class="text-slate-600">/</span>
                <span class="font-medium text-[#B88A44]">Transactions</span>
            </nav>
            <h1 class="text-3xl font-bold tracking-tight text-white">Transactions</h1>
            <p class="mt-1 text-sm text-slate-400">Payment history and financial transaction records.</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl border border-[#B88A44]/30 bg-[#B88A44]/5 p-5">
            <p class="text-xs text-[#B88A44] font-medium uppercase tracking-wider">Total Revenue</p>
            <p class="mt-2 text-2xl font-bold text-[#B88A44]">₹{{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
        <div class="rounded-2xl border border-emerald-800/30 bg-emerald-950/20 p-5">
            <p class="text-xs text-emerald-400 font-medium uppercase tracking-wider">Paid</p>
            <p class="mt-2 text-3xl font-bold text-emerald-400">{{ number_format($stats['paid']) }}</p>
        </div>
        <div class="rounded-2xl border border-amber-800/30 bg-amber-950/20 p-5">
            <p class="text-xs text-amber-400 font-medium uppercase tracking-wider">Pending</p>
            <p class="mt-2 text-3xl font-bold text-amber-400">{{ number_format($stats['pending']) }}</p>
        </div>
        <div class="rounded-2xl border border-rose-800/30 bg-rose-950/20 p-5">
            <p class="text-xs text-rose-400 font-medium uppercase tracking-wider">Failed</p>
            <p class="mt-2 text-3xl font-bold text-rose-400">{{ number_format($stats['failed']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-800 bg-[#111827] p-5">
        <form method="GET" class="flex flex-col gap-4 lg:flex-row lg:items-end flex-wrap">
            <div class="flex-1 min-w-[200px]">
                <label class="mb-1.5 block text-xs font-semibold text-slate-400">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, name, email..."
                       class="h-11 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-white placeholder:text-slate-500 focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-400">Payment Status</label>
                <select name="payment_status" class="h-11 rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-white focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">
                    <option value="">All</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-400">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="h-11 rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-white focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-400">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="h-11 rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-white focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="h-11 inline-flex items-center gap-2 rounded-xl bg-[#B88A44] px-5 text-sm font-semibold text-white hover:bg-[#a67936] transition">Filter</button>
                <a href="{{ route('admin.transactions.index') }}" class="h-11 inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-slate-300 hover:text-white transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-slate-800 bg-[#111827] overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800">
                <thead class="bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Order #</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Method</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Payment</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Order Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($transactions as $txn)
                    <tr class="hover:bg-slate-900/30 transition">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $txn->id) }}" class="text-sm font-mono font-semibold text-[#B88A44] hover:underline">#{{ $txn->order_number }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-white">{{ $txn->first_name }} {{ $txn->last_name }}</p>
                            <p class="text-xs text-slate-500">{{ $txn->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-lg bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-300 capitalize">
                                {{ $txn->payment_method ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-white">₹{{ number_format($txn->total, 2) }}</p>
                            @if($txn->discount > 0)
                                <p class="text-xs text-emerald-400">-₹{{ number_format($txn->discount, 2) }} discount</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $pColors = ['paid'=>'emerald','pending'=>'amber','failed'=>'rose'];
                                $pColor = $pColors[$txn->payment_status] ?? 'slate';
                            @endphp
                            <span class="inline-flex items-center rounded-full bg-{{ $pColor }}-500/10 px-2.5 py-1 text-xs font-semibold text-{{ $pColor }}-400 border border-{{ $pColor }}-500/20 capitalize">
                                {{ $txn->payment_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $sColors = ['delivered'=>'emerald','shipped'=>'blue','processing'=>'[#B88A44]','pending'=>'slate','cancelled'=>'rose'];
                                $sColor = $sColors[$txn->status] ?? 'slate';
                            @endphp
                            <span class="inline-flex items-center rounded-full bg-slate-800 px-2.5 py-1 text-xs font-medium text-slate-300 capitalize">
                                {{ $txn->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-400">{{ $txn->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <p class="text-slate-500">No transactions found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="border-t border-slate-800 px-6 py-4">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
