@extends('admin.layouts.app')
@section('title', 'Support Tickets')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <nav class="mb-3 flex items-center gap-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-500 hover:text-[#B88A44] transition">Dashboard</a>
                <span class="text-slate-600">/</span>
                <span class="font-medium text-[#B88A44]">Support</span>
            </nav>
            <h1 class="text-3xl font-bold tracking-tight text-white">Support Tickets</h1>
            <p class="mt-1 text-sm text-slate-400">Manage customer support requests and inquiries.</p>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{show:true}" x-show="show" x-transition class="flex items-center gap-3 rounded-2xl border border-emerald-800 bg-emerald-950/50 px-5 py-4 text-sm text-emerald-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
            <button @click="show=false" class="ml-auto">&times;</button>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl border border-slate-800 bg-[#111827] p-5">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Total</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="rounded-2xl border border-rose-800/30 bg-rose-950/20 p-5">
            <p class="text-xs text-rose-400 font-medium uppercase tracking-wider">Open</p>
            <p class="mt-2 text-3xl font-bold text-rose-400">{{ number_format($stats['open']) }}</p>
        </div>
        <div class="rounded-2xl border border-amber-800/30 bg-amber-950/20 p-5">
            <p class="text-xs text-amber-400 font-medium uppercase tracking-wider">Pending</p>
            <p class="mt-2 text-3xl font-bold text-amber-400">{{ number_format($stats['pending']) }}</p>
        </div>
        <div class="rounded-2xl border border-emerald-800/30 bg-emerald-950/20 p-5">
            <p class="text-xs text-emerald-400 font-medium uppercase tracking-wider">Resolved</p>
            <p class="mt-2 text-3xl font-bold text-emerald-400">{{ number_format($stats['resolved']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-800 bg-[#111827] p-5">
        <form method="GET" class="flex flex-col gap-4 lg:flex-row lg:items-end">
            <div class="flex-1">
                <label class="mb-1.5 block text-xs font-semibold text-slate-400">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Subject, message, or customer..."
                       class="h-11 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-white placeholder:text-slate-500 focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-400">Status</label>
                <select name="status" class="h-11 rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-white focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">
                    <option value="">All Status</option>
                    <option value="open"     {{ request('status') === 'open'     ? 'selected' : '' }}>Open</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed"   {{ request('status') === 'closed'   ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-400">Priority</label>
                <select name="priority" class="h-11 rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-white focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">
                    <option value="">All Priorities</option>
                    <option value="high"   {{ request('priority') === 'high'   ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low"    {{ request('priority') === 'low'    ? 'selected' : '' }}>Low</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="h-11 inline-flex items-center gap-2 rounded-xl bg-[#B88A44] px-5 text-sm font-semibold text-white hover:bg-[#a67936] transition">Search</button>
                <a href="{{ route('admin.support.index') }}" class="h-11 inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-slate-300 hover:text-white transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Tickets Table --}}
    <div class="rounded-2xl border border-slate-800 bg-[#111827] overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800">
                <thead class="bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">#ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Subject</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($tickets as $ticket)
                    @php
                        $priorityColors = ['high'=>'rose','medium'=>'amber','low'=>'slate'];
                        $statusColors = ['open'=>'rose','pending'=>'amber','resolved'=>'emerald','closed'=>'slate'];
                        $pc = $priorityColors[$ticket->priority] ?? 'slate';
                        $sc = $statusColors[$ticket->status] ?? 'slate';
                    @endphp
                    <tr class="hover:bg-slate-900/30 transition">
                        <td class="px-6 py-4 text-sm font-mono text-slate-500">#{{ $ticket->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->name) }}&background=B88A44&color=fff&size=36"
                                     class="w-9 h-9 rounded-full">
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $ticket->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $ticket->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-white max-w-[200px] truncate">{{ $ticket->subject }}</p>
                            <p class="text-xs text-slate-500 mt-0.5 max-w-[200px] truncate">{{ $ticket->message }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full bg-{{ $pc }}-500/10 px-2.5 py-1 text-xs font-semibold text-{{ $pc }}-400 border border-{{ $pc }}-500/20 capitalize">
                                {{ $ticket->priority }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full bg-{{ $sc }}-500/10 px-2.5 py-1 text-xs font-semibold text-{{ $sc }}-400 border border-{{ $sc }}-500/20 capitalize">
                                {{ $ticket->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-400">{{ $ticket->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.support.show', $ticket->id) }}"
                                   class="inline-flex h-8 items-center gap-1 rounded-lg bg-[#B88A44]/10 border border-[#B88A44]/20 px-3 text-xs font-semibold text-[#B88A44] hover:bg-[#B88A44] hover:text-white transition">
                                    View
                                </a>
                                <form action="{{ route('admin.support.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Delete this ticket?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex h-8 items-center rounded-lg bg-rose-600/10 border border-rose-600/20 px-3 text-xs font-semibold text-rose-400 hover:bg-rose-600 hover:text-white transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                                <p class="text-slate-500 font-medium">No support tickets found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
        <div class="border-t border-slate-800 px-6 py-4">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
