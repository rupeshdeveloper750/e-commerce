@extends('admin.layouts.app')

@section('title', 'Write Blog Article')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <nav class="mb-3 flex items-center gap-2 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 transition hover:text-[#B88A44]">Dashboard</a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('admin.blogs.index') }}" class="text-gray-500 transition hover:text-[#B88A44]">Blogs</a>
            <span class="text-gray-400">/</span>
            <span class="font-medium text-[#B88A44]">Write Article</span>
        </nav>
        <h1 class="text-3xl font-bold tracking-tight text-white">Write New Blog Article</h1>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="rounded-3xl border border-slate-800 bg-[#111827] p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">Article Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. 10 E-commerce Trends for 2026" class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="e.g. 10-ecommerce-trends-2026" class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                    @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Status</label>
                    <select name="status" class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                        <option value="1">Publish Immediately</option>
                        <option value="0">Save as Draft</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">Article Content</label>
                <textarea name="content" rows="12" placeholder="Write publication details here..." class="w-full rounded-xl border border-slate-700 bg-slate-800 p-4 text-white focus:border-[#B88A44] focus:outline-none">{{ old('content') }}</textarea>
                @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-slate-800 pt-5">
                <label class="block text-sm font-semibold text-slate-300 mb-2">Cover Image</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-500/10 file:text-amber-500 hover:file:bg-amber-500/20">
                @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-slate-800 pt-5 space-y-4">
                <h3 class="text-sm font-semibold text-white">SEO Settings</h3>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Meta search title" class="h-10 w-full rounded-lg border border-slate-700 bg-slate-800 px-3 text-sm text-white focus:border-[#B88A44] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="3" placeholder="Meta search description snippet..." class="w-full rounded-lg border border-slate-700 bg-slate-800 p-3 text-sm text-white focus:border-[#B88A44] focus:outline-none">{{ old('meta_description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('admin.blogs.index') }}" class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 px-6 text-sm font-semibold text-slate-300 hover:text-white transition">Cancel</a>
            <button type="submit" class="inline-flex h-12 items-center justify-center rounded-xl bg-[#B88A44] px-8 text-sm font-semibold text-white transition hover:bg-[#a67936]">Save Article</button>
        </div>
    </form>
</div>

<script>
    (function () {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        if (!titleInput || !slugInput) return;
        
        titleInput.addEventListener('input', function () {
            if (slugInput.value.trim() === '') {
                slugInput.value = titleInput.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
        });
    })();
</script>
@endsection
