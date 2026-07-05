@extends('admin.layouts.app')
@section('title', 'Reviews Management')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <nav class="mb-3 flex items-center gap-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-500 hover:text-[#B88A44] transition">Dashboard</a>
                <span class="text-slate-600">/</span>
                <span class="font-medium text-[#B88A44]">Reviews</span>
            </nav>
            <h1 class="text-3xl font-bold tracking-tight text-white">Customer Reviews</h1>
            <p class="mt-1 text-sm text-slate-400">Moderate and manage product reviews from customers.</p>
        </div>
    </div>

    {{-- Flash --}}
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
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Total Reviews</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="rounded-2xl border border-emerald-800/30 bg-emerald-950/20 p-5">
            <p class="text-xs text-emerald-400 font-medium uppercase tracking-wider">Approved</p>
            <p class="mt-2 text-3xl font-bold text-emerald-400">{{ number_format($stats['approved']) }}</p>
        </div>
        <div class="rounded-2xl border border-amber-800/30 bg-amber-950/20 p-5">
            <p class="text-xs text-amber-400 font-medium uppercase tracking-wider">Pending</p>
            <p class="mt-2 text-3xl font-bold text-amber-400">{{ number_format($stats['pending']) }}</p>
        </div>
        <div class="rounded-2xl border border-[#B88A44]/30 bg-[#B88A44]/5 p-5">
            <p class="text-xs text-[#B88A44] font-medium uppercase tracking-wider">Avg Rating</p>
            <p class="mt-2 text-3xl font-bold text-[#B88A44]">{{ $stats['avg_rating'] }} ⭐</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-800 bg-[#111827] p-5">
        <form method="GET" class="flex flex-col gap-4 lg:flex-row lg:items-end">
            <div class="flex-1">
                <label class="mb-1.5 block text-xs font-semibold text-slate-400">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Product, user, or comment..."
                       class="h-11 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-white placeholder:text-slate-500 focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-400">Status</label>
                <select name="status" class="h-11 rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-white focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Approved</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-400">Rating</label>
                <select name="rating" class="h-11 rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-white focus:border-[#B88A44] focus:outline-none focus:ring-2 focus:ring-[#B88A44]/20 transition">
                    <option value="">All Ratings</option>
                    @foreach([5,4,3,2,1] as $r)
                        <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>{{ $r }} ⭐</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="h-11 inline-flex items-center gap-2 rounded-xl bg-[#B88A44] px-5 text-sm font-semibold text-white hover:bg-[#a67936] transition">Search</button>
                <a href="{{ route('admin.reviews.index') }}" class="h-11 inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800 px-4 text-sm text-slate-300 hover:text-white transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-slate-800 bg-[#111827] overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800">
                <thead class="bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Product</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Rating</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Comment</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-slate-900/30 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user?->name ?? 'User') }}&background=B88A44&color=fff&size=36"
                                     class="w-9 h-9 rounded-full">
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $review->user?->name ?? 'Deleted User' }}</p>
                                    <p class="text-xs text-slate-500">{{ $review->user?->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-300 max-w-[150px] truncate">{{ $review->product?->name ?? 'Deleted Product' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-600' }} text-sm">★</span>
                                @endfor
                                <span class="ml-1 text-xs text-slate-400">{{ $review->rating }}/5</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-400 max-w-[200px] truncate">{{ $review->comment ?? '—' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($review->status)
                                <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-semibold text-emerald-400 border border-emerald-500/20">Approved</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-500/10 px-2.5 py-1 text-xs font-semibold text-amber-400 border border-amber-500/20">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-400">{{ $review->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$review->status)
                                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="inline-flex h-8 items-center gap-1 rounded-lg bg-emerald-600 px-3 text-xs font-semibold text-white hover:bg-emerald-700 transition">Approve</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="inline-flex h-8 items-center gap-1 rounded-lg bg-amber-600 px-3 text-xs font-semibold text-white hover:bg-amber-700 transition">Reject</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex h-8 items-center gap-1 rounded-lg bg-rose-600/20 border border-rose-600/30 px-3 text-xs font-semibold text-rose-400 hover:bg-rose-600 hover:text-white transition">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                <p class="text-slate-500 font-medium">No reviews found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
        <div class="border-t border-slate-800 px-6 py-4">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
