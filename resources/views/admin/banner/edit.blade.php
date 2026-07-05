@extends('admin.layouts.app')

@section('title', 'Edit Promo Banner')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <nav class="mb-3 flex items-center gap-2 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 transition hover:text-[#B88A44]">Dashboard</a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('admin.banners.index') }}" class="text-gray-500 transition hover:text-[#B88A44]">Banners</a>
            <span class="text-gray-400">/</span>
            <span class="font-medium text-[#B88A44]">Edit Banner</span>
        </nav>
        <h1 class="text-3xl font-bold tracking-tight text-white">Edit Promo Banner: {{ $banner->title ?? 'Promo Banner' }}</h1>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-3xl border border-slate-800 bg-[#111827] p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">Banner Title</label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" placeholder="e.g. Summer Collection Sale" class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">Sub Title / Promo Caption</label>
                <input type="text" name="sub_title" value="{{ old('sub_title', $banner->sub_title) }}" placeholder="e.g. Up to 50% Off on Premium Brands" class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                @error('sub_title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">Target Action Link / URL</label>
                <input type="text" name="link" value="{{ old('link', $banner->link) }}" placeholder="e.g. /shop/collections/summer" class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                @error('link') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" required class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                    @error('sort_order') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Status</label>
                    <select name="status" class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        <option value="1" {{ old('status', $banner->status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $banner->status) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="border-t border-slate-800 pt-5">
                <label class="block text-sm font-semibold text-slate-300 mb-2">Banner Image</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-500/10 file:text-amber-500 hover:file:bg-amber-500/20">
                @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror

                @if($banner->image)
                    <div class="mt-4">
                        <p class="text-xs text-slate-505 mb-2">Current Banner Image</p>
                        <img src="{{ asset('storage/' . $banner->image) }}" class="h-24 w-48 rounded-xl object-cover border border-slate-800">
                    </div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('admin.banners.index') }}" class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 px-6 text-sm font-semibold text-slate-300 hover:text-white transition">Cancel</a>
            <button type="submit" class="inline-flex h-12 items-center justify-center rounded-xl bg-[#B88A44] px-8 text-sm font-semibold text-white transition hover:bg-[#a67936]">Update Promo Slide</button>
        </div>
    </form>
</div>
@endsection
