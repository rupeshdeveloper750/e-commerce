@extends('admin.layouts.app')

@section('title', 'Permissions')

@section('content')
<div class="mx-auto max-w-screen-2xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">System Permissions</h1>
            <p class="mt-1 text-sm text-slate-400">View all system-defined security gates and capabilities.</p>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="overflow-hidden rounded-3xl border border-slate-800 bg-[#111827] shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-5">
            <div>
                <h3 class="text-lg font-semibold text-white">All Permissions</h3>
                <p class="mt-1 text-sm text-slate-400">A read-only inventory of access keys defined in the system.</p>
            </div>
            <div class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-sm text-slate-300">
                Total : <span class="font-semibold text-white">{{ $permissions->total() }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="border-b border-slate-800 bg-slate-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Permission Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Slug (Gate ID)</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($permissions as $permission)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-white">
                                {{ $permission->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                <span class="rounded-lg bg-slate-800 px-2.5 py-1 border border-slate-700/50 text-xs text-amber-500 font-mono">
                                    {{ $permission->slug }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400">
                                {{ $permission->description }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-500">
                                No permissions defined in the database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($permissions->hasPages())
            <div class="border-t border-slate-800 bg-slate-900 px-6 py-4">
                {{ $permissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
