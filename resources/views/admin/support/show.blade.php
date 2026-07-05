@extends('admin.layouts.app')
@section('title', 'View Support Ticket')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb + Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <nav class="mb-3 flex items-center gap-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-500 hover:text-[#B88A44] transition">Dashboard</a>
                <span class="text-slate-600">/</span>
                <a href="{{ route('admin.support.index') }}" class="text-slate-500 hover:text-[#B88A44] transition">Support</a>
                <span class="text-slate-600">/</span>
                <span class="font-medium text-[#B88A44]">Ticket #{{ $ticket->id }}</span>
            </nav>
            <h1 class="text-3xl font-bold tracking-tight text-white">Ticket Details</h1>
            <p class="mt-1 text-sm text-slate-400">View and respond to customer query ticket.</p>
        </div>
        <div>
            <a href="{{ route('admin.support.index') }}" class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 px-5 text-sm font-semibold text-slate-300 hover:text-white transition">
                Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
    <div x-data="{show:true}" x-show="show" x-transition class="flex items-center gap-3 rounded-2xl border border-emerald-800 bg-emerald-950/50 px-5 py-4 text-sm text-emerald-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ session('success') }}
        <button @click="show=false" class="ml-auto">&times;</button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Conversation Card --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Ticket Message --}}
            <div class="rounded-2xl border border-slate-800 bg-[#111827] p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($ticket->name) }}&background=B88A44&color=fff&size=40" class="w-10 h-10 rounded-full">
                        <div>
                            <h3 class="text-sm font-semibold text-white">{{ $ticket->name }}</h3>
                            <p class="text-xs text-slate-500">{{ $ticket->email }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500">{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div>
                    <h4 class="text-base font-bold text-white mb-2">{{ $ticket->subject }}</h4>
                    <p class="text-sm text-slate-300 leading-relaxed whitespace-pre-wrap">{{ $ticket->message }}</p>
                </div>
            </div>

            {{-- Reply/Update Form --}}
            <div class="rounded-2xl border border-slate-800 bg-[#111827] p-6 shadow-xl">
                <h3 class="text-base font-bold text-white mb-4">Respond / Action</h3>
                <form action="{{ route('admin.support.updateStatus', $ticket->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-400 font-medium">Ticket Status</label>
                        <select name="status" class="h-11 rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-white focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open (Needs attention)</option>
                            <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending (Waiting for customer/internal info)</option>
                            <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved (Query sorted)</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-400 font-medium">Admin Reply / Internal Notes</label>
                        <textarea name="admin_reply" rows="5" placeholder="Write message to customer or internal action details..."
                            class="w-full rounded-xl border border-slate-700 bg-slate-800 p-4 text-sm text-white placeholder:text-slate-500 focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">{{ old('admin_reply', $ticket->admin_reply) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="submit" class="inline-flex h-11 items-center gap-2 rounded-xl bg-[#B88A44] px-6 text-sm font-semibold text-white hover:bg-[#a67936] transition">
                            Update Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Meta Sidebar --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-800 bg-[#111827] p-6 shadow-xl space-y-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider text-slate-400">Metadata</h3>

                <div class="space-y-3 divide-y divide-slate-800 text-sm">
                    <div class="pt-3 flex justify-between">
                        <span class="text-slate-500">Priority:</span>
                        <span class="font-semibold capitalize text-amber-400">{{ $ticket->priority }}</span>
                    </div>
                    <div class="pt-3 flex justify-between">
                        <span class="text-slate-500">Status:</span>
                        <span class="font-semibold capitalize text-rose-400">{{ $ticket->status }}</span>
                    </div>
                    <div class="pt-3 flex justify-between">
                        <span class="text-slate-500">User Registered:</span>
                        <span class="font-semibold text-white">{{ $ticket->user ? 'Yes' : 'Guest' }}</span>
                    </div>
                    <div class="pt-3 flex justify-between">
                        <span class="text-slate-500">Created:</span>
                        <span class="text-white">{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                    @if($ticket->updated_at != $ticket->created_at)
                    <div class="pt-3 flex justify-between">
                        <span class="text-slate-500">Last Action:</span>
                        <span class="text-white">{{ $ticket->updated_at->format('d M Y, h:i A') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection