@extends('admin.layouts.app')

@section('title', 'Roles')

@section('content')
<div class="mx-auto max-w-screen-2xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Security Roles</h1>
            <p class="mt-1 text-sm text-slate-400">Manage user roles and assign access permissions.</p>
        </div>
        <div>
            <a
                href="{{ route('admin.roles.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-slate-950 hover:bg-amber-400 transition shadow-lg shadow-amber-500/10"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Role
            </a>
        </div>
    </div>

    {{-- Status Banner --}}
    @if (session('success'))
        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-400">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-xl border border-rose-500/20 bg-rose-500/10 p-4 text-sm text-rose-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="overflow-hidden rounded-3xl border border-slate-800 bg-[#111827] shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-5">
            <div>
                <h3 class="text-lg font-semibold text-white">All Roles</h3>
                <p class="mt-1 text-sm text-slate-400">A list of all the user security roles defined in the system.</p>
            </div>
            <div class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-sm text-slate-300">
                Total : <span class="font-semibold text-white">{{ $roles->total() }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-b border-slate-800 bg-slate-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Role Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Slug</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Description</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Permissions Count</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-slate-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($roles as $role)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-white">
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                <span class="rounded-lg bg-slate-800 px-2.5 py-1 border border-slate-700/50 text-xs">
                                    {{ $role->slug }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400 max-w-xs truncate">
                                {{ $role->description ?? 'No description provided.' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                <span class="inline-flex items-center rounded-md bg-amber-500/10 px-2 py-1 text-xs font-medium text-amber-500 ring-1 ring-inset ring-amber-500/20">
                                    {{ $role->permissions_count }} Permissions
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    @if ($role->slug !== 'super-admin')
                                        <a
                                            href="{{ route('admin.roles.edit', $role->id) }}"
                                            class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:text-white transition"
                                        >
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-red-900/20 bg-red-950/20 px-3 py-1.5 text-xs font-semibold text-red-400 hover:bg-red-950/50 hover:text-red-300 transition"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-500 italic">System Lock</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                                No roles defined in the system.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($roles->hasPages())
            <div class="border-t border-slate-800 bg-slate-900 px-6 py-4">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
