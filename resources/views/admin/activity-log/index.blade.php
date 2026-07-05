@extends('admin.layouts.app')

@section('title', 'Audit Activity Logs')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <nav class="mb-3 flex items-center gap-2 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 transition hover:text-[#B88A44]">Dashboard</a>
            <span class="text-gray-400">/</span>
            <span class="font-medium text-[#B88A44]">Audit Logs</span>
        </nav>
        <h1 class="text-3xl font-bold tracking-tight text-white">System Audit Trail</h1>
        <p class="mt-2 text-sm text-slate-400">Verify administrator histories, track system updates, category edits and coupon details.</p>
    </div>

    {{-- Filters --}}
    <div class="rounded-3xl border border-slate-800 bg-[#111827] p-6 shadow-xl">
        <form action="{{ route('admin.activity-logs.index') }}" method="GET">
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search action, model, details..." class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                </div>
                <div class="lg:col-span-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Staff Member</label>
                    <select name="admin_id" class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        <option value="">All Admins</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>{{ $admin->name }} ({{ $admin->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Action Type</label>
                    <select name="action_type" class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action_type') === 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action_type') === 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action_type') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="restored" {{ request('action_type') === 'restored' ? 'selected' : '' }}>Restored</option>
                        <option value="force_deleted" {{ request('action_type') === 'force_deleted' ? 'selected' : '' }}>Force Deleted</option>
                        <option value="imported" {{ request('action_type') === 'imported' ? 'selected' : '' }}>Imported</option>
                        <option value="exported" {{ request('action_type') === 'exported' ? 'selected' : '' }}>Exported</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3 border-t border-slate-800 pt-4">
                <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 px-5 text-sm font-semibold text-slate-300 hover:text-white transition">Reset</a>
                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-[#B88A44] px-6 text-sm font-semibold text-white transition hover:bg-[#a67936]">Search</button>
            </div>
        </form>
    </div>

    {{-- Logs List --}}
    <div class="overflow-hidden rounded-3xl border border-slate-800 bg-[#111827] shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-b border-slate-800 bg-slate-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Timestamp</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Staff Member</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Action</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Target Model</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Record ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Details Payload</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="px-6 py-4 text-sm text-slate-400">
                                {{ $log->created_at ? $log->created_at->format('Y-m-d h:i:s A') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-white font-medium">
                                {{ $log->causer ? $log->causer->name : 'System Trigger' }}
                                <div class="text-xs text-slate-500 mt-0.5">{{ $log->causer ? $log->causer->email : 'automated' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold
                                    @if($log->action === 'created') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                    @elseif($log->action === 'updated') bg-blue-500/10 text-blue-400 border border-blue-500/20
                                    @elseif($log->action === 'deleted') bg-rose-500/10 text-rose-400 border border-rose-500/20
                                    @elseif($log->action === 'restored') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                    @elseif($log->action === 'force_deleted') bg-red-500/10 text-red-500 border border-red-500/20
                                    @else bg-slate-800 text-slate-400 border border-slate-800 @endif">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300 font-mono">
                                {{ $log->model ? class_basename($log->model) : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                {{ $log->record_id ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-400" x-data="{ openPayload: false }">
                                <button type="button" @click="openPayload = !openPayload" class="text-amber-500 hover:text-amber-400 font-semibold underline text-xs focus:outline-none">
                                    <span x-text="openPayload ? 'Hide details' : 'View payload JSON'"></span>
                                </button>
                                <div x-show="openPayload" class="mt-2 p-3 bg-slate-950/80 border border-slate-800 rounded-xl max-h-48 overflow-y-auto max-w-lg scrollbar-thin" style="display: none;">
                                    <pre class="text-[10px] leading-relaxed whitespace-pre-wrap">{{ json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">No audit trail records compiled.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div class="border-t border-slate-800 bg-slate-900 px-6 py-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
