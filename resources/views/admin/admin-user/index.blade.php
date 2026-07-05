@extends('admin.layouts.app')

@section('title', 'Admin Users')

@section('content')
<div class="space-y-6" x-data="{
    selectedIds: [],
    selectAll: false,
    toggleAll() {
        this.selectAll = !this.selectAll;
        this.selectedIds = [];
        if (this.selectAll) {
            document.querySelectorAll('.item-checkbox').forEach(el => {
                this.selectedIds.push(parseInt(el.value));
            });
        }
    },
    toggleItem(id) {
        id = parseInt(id);
        const index = this.selectedIds.indexOf(id);
        if (index > -1) {
            this.selectedIds.splice(index, 1);
        } else {
            this.selectedIds.push(id);
        }
        this.selectAll = this.selectedIds.length === document.querySelectorAll('.item-checkbox').length;
    }
}">
    {{-- Header --}}
    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <nav class="mb-3 flex items-center gap-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 transition hover:text-[#B88A44]">Dashboard</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-500">Settings</span>
                <span class="text-gray-400">/</span>
                <span class="font-medium text-[#B88A44]">Admin Users</span>
            </nav>
            <h1 class="text-3xl font-bold tracking-tight text-white">Admin Users</h1>
            <p class="mt-2 text-sm text-slate-400">Configure administrative access, roles and staff directory.</p>
        </div>
        <div>
            <a href="{{ route('admin.admins.create') }}" class="inline-flex h-12 items-center gap-2 rounded-xl bg-[#B88A44] px-5 text-sm font-semibold text-white shadow-lg transition hover:bg-[#a67936]">
                + Add Staff Admin
            </a>
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="rounded-3xl border border-slate-800 bg-[#111827] p-6 shadow-xl">
        <form action="{{ route('admin.admins.index') }}" method="GET">
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..." class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                </div>
                <div class="lg:col-span-3">
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Role</label>
                    <select name="role_id" class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        <option value="">All Roles</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}" {{ request('role_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Status</label>
                    <select name="status" class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        <option value="">All</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>
                <div class="lg:col-span-3">
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Trash State</label>
                    <select name="trashed" class="h-14 w-full rounded-2xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        <option value="">Active Only</option>
                        <option value="with" {{ request('trashed') === 'with' ? 'selected' : '' }}>Include Trashed</option>
                        <option value="only" {{ request('trashed') === 'only' ? 'selected' : '' }}>Trashed Only</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3 border-t border-slate-800 pt-4">
                <a href="{{ route('admin.admins.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 px-5 text-sm font-semibold text-slate-300 hover:text-white transition">Reset</a>
                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-[#B88A44] px-6 text-sm font-semibold text-white transition hover:bg-[#a67936]">Search</button>
            </div>
        </form>
    </div>

    {{-- Bulk Action Floating Bar --}}
    <div x-show="selectedIds.length > 0" class="fixed bottom-6 left-1/2 z-50 flex -translate-x-1/2 items-center gap-4 rounded-2xl border border-slate-800 bg-slate-900/90 px-6 py-4 shadow-2xl backdrop-blur-md" style="display: none;">
        <span class="text-sm text-slate-300">Selected: <span class="font-semibold text-white" x-text="selectedIds.length"></span></span>
        <div class="h-4 w-px bg-slate-800"></div>
        <form action="{{ route('admin.admins.bulk-action') }}" method="POST" class="flex items-center gap-3">
            @csrf
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <select name="action" class="rounded-xl border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs text-slate-200 focus:outline-none">
                <option value="block">Block Access</option>
                <option value="activate">Activate Access</option>
                <option value="delete">Soft Delete</option>
                <option value="restore">Restore</option>
                <option value="force_delete">Force Delete</option>
            </select>
            <button type="submit" class="rounded-xl bg-amber-500 px-4 py-1.5 text-xs font-semibold text-slate-950 hover:bg-amber-400 transition">Apply</button>
        </form>
    </div>

    {{-- Admins Table --}}
    <div class="overflow-hidden rounded-3xl border border-slate-800 bg-[#111827] shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-b border-slate-800 bg-slate-900">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" :checked="selectAll" @click="toggleAll()" class="h-4 w-4 rounded border-slate-700 bg-slate-800 text-[#B88A44] focus:ring-[#B88A44]">
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Staff Info</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Role</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Phone</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-slate-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($admins as $admin)
                        <tr class="hover:bg-slate-900/40 transition {{ $admin->trashed() ? 'bg-red-950/5' : '' }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" :value="{{ $admin->id }}" :checked="selectedIds.includes({{ $admin->id }})" @click="toggleItem({{ $admin->id }})" class="item-checkbox h-4 w-4 rounded border-slate-700 bg-slate-800 text-[#B88A44] focus:ring-[#B88A44]">
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-white">
                                <div class="flex items-center gap-2">
                                    {{ $admin->name }}
                                    @if ($admin->trashed())
                                        <span class="inline-flex items-center rounded-md bg-red-400/10 px-1.5 py-0.5 text-2xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Trashed</span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-505 font-normal mt-0.5">{{ $admin->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                @forelse($admin->roles as $role)
                                    <span class="inline-flex items-center rounded bg-amber-500/10 px-2 py-0.5 text-xs font-medium text-amber-500 border border-amber-500/20 mr-1">{{ $role->name }}</span>
                                @empty
                                    <span class="text-slate-500 text-xs">No Role</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                {{ $admin->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $admin->status ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                    {{ $admin->status ? 'Active' : 'Blocked' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2.5">
                                    @if (!$admin->trashed())
                                        <a href="{{ route('admin.admins.edit', $admin->id) }}" class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:text-white transition">Edit</a>
                                        <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Soft delete this staff admin?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-rose-950 bg-rose-950/20 px-3 py-1.5 text-xs font-semibold text-rose-400 hover:bg-rose-950/50 hover:text-rose-300 transition">Delete</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.admins.restore', $admin->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded-lg border border-emerald-950 bg-emerald-950/20 px-3 py-1.5 text-xs font-semibold text-emerald-400 hover:bg-emerald-950/50 hover:text-emerald-300 transition">Restore</button>
                                        </form>
                                        <form action="{{ route('admin.admins.force-delete', $admin->id) }}" method="POST" onsubmit="return confirm('WARNING: Permanently delete this admin?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-950 bg-red-950/30 px-3 py-1.5 text-xs font-semibold text-red-500 hover:bg-red-950/60 hover:text-red-400 transition">Force Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">No admin staff records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($admins->hasPages())
            <div class="border-t border-slate-800 bg-slate-900 px-6 py-4">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
