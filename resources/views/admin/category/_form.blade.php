{{--
    resources/views/admin/categories/_form.blade.php

    Reusable Category Create/Edit form partial — Dark SaaS Admin theme
    (matches ShopMe Admin Dashboard: slate-900/950 surfaces, amber-500 accent).
    Auto-detects Create vs Edit mode using isset($category).
    Expects (optional): $categories -> collection of parent-eligible categories.
--}}

@php
    $isEdit    = isset($category);
    $formId    = 'category-form';
    $statusOld = old('status', $isEdit ? $category->status : 'active');
@endphp

<form
    id="{{ $formId }}"
    action="{{ $isEdit ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}"
    method="POST"
    enctype="multipart/form-data"
    class="pb-28 lg:pb-8"
    novalidate
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- ============================= --}}
    {{-- Two Column Responsive Layout  --}}
    {{-- ============================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

        {{-- ================================================= --}}
        {{-- LEFT COLUMN (2/3) — Basic Info + Description       --}}
        {{-- ================================================= --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- CARD 1: Basic Information --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Basic Information</h2>
                    <p class="mt-1 text-sm text-slate-400">Name, slug and hierarchy for this category.</p>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Category Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-200 mb-1.5">
                            Category Name <span class="text-red-400">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            autocomplete="off"
                            value="{{ old('name', $isEdit ? $category->name : '') }}"
                            placeholder="e.g. Men's Footwear"
                            class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition
                                focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500
                                @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                        >
                        @error('name')
                            <p class="mt-1.5 text-sm text-red-400 flex items-center gap-1">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label for="slug" class="block text-sm font-medium text-slate-200 mb-1.5">
                            Slug
                        </label>
                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            autocomplete="off"
                            value="{{ old('slug', $isEdit ? $category->slug : '') }}"
                            placeholder="e.g. mens-footwear"
                            class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition
                                focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500
                                @error('slug') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                        >
                        <p class="mt-1.5 text-xs text-slate-500">Leave blank to auto-generate from the category name.</p>
                        @error('slug')
                            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Parent Category --}}
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-slate-200 mb-1.5">
                            Parent Category
                        </label>
                        <div class="relative">
                            <select
                                id="parent_id"
                                name="parent_id"
                                class="w-full appearance-none rounded-xl border bg-slate-950/60 px-4 py-2.5 pr-10 text-sm text-white transition
                                    focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500
                                    @error('parent_id') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                            >
                                <option value="" class="bg-slate-900">— None (Top Level Category) —</option>
                                @isset($categories)
                                    @foreach ($categories as $parent)
                                        {{-- Prevent a category from being its own parent on edit --}}
                                        @if (!$isEdit || $parent->id !== $category->id)
                                            <option
                                                value="{{ $parent->id }}"
                                                class="bg-slate-900"
                                                @selected((int) old('parent_id', $isEdit ? $category->parent_id : '') === $parent->id)
                                            >
                                                {{ $parent->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endisset
                            </select>
                            <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                        @error('parent_id')
                            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </section>

            {{-- CARD 2: Description --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Description</h2>
                    <p class="mt-1 text-sm text-slate-400">A short summary shown on the storefront category page.</p>
                </div>

                <div class="p-6">
                    <label for="description" class="sr-only">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="6"
                        placeholder="Write a helpful description for this category..."
                        class="w-full rounded-xl border bg-slate-950/60 px-4 py-3 text-sm text-white placeholder-slate-500 transition resize-y
                            focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500
                            @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                    >{{ old('description', $isEdit ? $category->description : '') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </section>

        </div>

        {{-- ================================================= --}}
        {{-- RIGHT COLUMN (1/3) — Image + Status                --}}
        {{-- ================================================= --}}
        <div class="space-y-6">

            {{-- CARD 3: Category Image --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Category Image</h2>
                    <p class="mt-1 text-sm text-slate-400">Displayed as the category thumbnail.</p>
                </div>

                <div class="p-6">
                    <label for="image" class="block text-sm font-medium text-slate-200 mb-1.5">
                        Upload Image
                    </label>

                    {{-- Drag & Drop Zone --}}
                    <div
                        id="image-dropzone"
                        class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-700 bg-slate-950/40 px-4 py-8 text-center transition
                            hover:border-amber-500 hover:bg-amber-500/5 cursor-pointer
                            @error('image') border-red-500 @enderror"
                    >
                        {{-- Preview state (hidden by default) --}}
                        <div id="image-preview-wrapper" class="hidden w-full">
                            <img id="image-preview" src="" alt="Category image preview" class="mx-auto max-h-40 rounded-lg object-cover shadow-sm">
                            <p id="image-filename" class="mt-3 text-xs font-medium text-slate-300 truncate"></p>
                            <button
                                type="button"
                                id="image-remove-btn"
                                class="mt-3 inline-flex items-center gap-1 rounded-lg border border-slate-700 bg-slate-900 px-3 py-1.5 text-xs font-semibold text-red-400 transition hover:bg-red-500/10"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Remove Image
                            </button>
                        </div>

                        {{-- Empty / upload-prompt state --}}
                        <div id="image-empty-state" class="flex flex-col items-center gap-2">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-500/10">
                                <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 8.25L12 3.75m0 0L7.5 8.25M12 3.75v12" />
                                </svg>
                            </span>
                            <p class="text-sm font-medium text-slate-200">
                                <span class="text-amber-500">Click to upload</span> or drag & drop
                            </p>
                            <p class="text-xs text-slate-400">PNG, JPG, JPEG or WEBP</p>
                            <p class="text-xs text-slate-500">Max file size 2MB</p>
                        </div>

                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/png,image/jpeg,image/jpg,image/webp"
                            class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                        >
                    </div>

                    @error('image')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror

                    {{-- Existing image preview on Edit page --}}
                    @if ($isEdit && $category->image)
                        <div id="existing-image-wrapper" class="mt-4">
                            <p class="text-xs font-medium text-slate-400 mb-2">Current Image</p>
                            <img
                                src="{{ asset('storage/' . $category->image) }}"
                                alt="{{ $category->name }} current image"
                                class="h-20 w-20 rounded-lg border border-slate-700 object-cover"
                            >
                        </div>
                    @endif
                </div>
            </section>

            {{-- CARD 4: Status --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Status</h2>
                    <p class="mt-1 text-sm text-slate-400">Control category visibility on the storefront.</p>
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="status_toggle" class="text-sm font-medium text-slate-200">
                                <span id="status-label-text">{{ $statusOld === 'active' ? 'Active' : 'Inactive' }}</span>
                            </label>
                            <p class="text-xs text-slate-400">{{ $statusOld === 'active' ? 'Visible to customers' : 'Hidden from customers' }}</p>
                        </div>

                        {{-- Toggle Switch --}}
                        <button
                            type="button"
                            id="status_toggle"
                            role="switch"
                            aria-checked=" $statusOld = old('status', $isEdit ? $category->status : 1);"
                            aria-labelledby="status-label-text"
                            class="relative inline-flex h-7 w-12 shrink-0 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-slate-900
                                {{ $statusOld === 'active' ? 'bg-amber-500' : 'bg-slate-700' }}"
                        >
                            <span
                                id="status_toggle_knob"
                                class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200
                                    {{ $statusOld === 'active' ? 'translate-x-6' : 'translate-x-1' }}"
                            ></span>
                        </button>

                        {{-- Hidden field actually submitted with the form --}}
                        <input type="hidden" id="status" name="status" value="{{ $statusOld }}">
                    </div>
                    @error('status')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </section>

        </div>
    </div>

    {{-- ============================= --}}
    {{-- Sticky Bottom Action Bar      --}}
    {{-- ============================= --}}
    <div class="fixed inset-x-0 bottom-0 z-20 border-t border-slate-800 bg-slate-950/90 backdrop-blur lg:static lg:mt-8 lg:border-0 lg:bg-transparent lg:backdrop-blur-none">
        <div class="mx-auto flex max-w-screen-2xl items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:px-0">
            <a
                href="{{ route('admin.categories.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-700 bg-slate-900 px-5 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-600"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-slate-950 shadow-sm shadow-amber-500/30 transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-slate-950"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                {{ $isEdit ? 'Update Category' : 'Save Category' }}
            </button>
        </div>
    </div>
</form>

{{-- ============================= --}}
{{-- JavaScript (Vanilla JS only)  --}}
{{-- ============================= --}}
<script>
    (function () {
        'use strict';

        // ---------- Slug auto-generation ----------
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        let slugManuallyEdited = slugInput.value.trim().length > 0;

        function slugify(text) {
            return text
                .toString()
                .trim()
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        slugInput.addEventListener('input', function () {
            slugManuallyEdited = slugInput.value.trim().length > 0;
        });

        nameInput.addEventListener('input', function () {
            if (!slugManuallyEdited) {
                slugInput.value = slugify(nameInput.value);
            }
        });

        // ---------- Image Drag & Drop / Preview ----------
        const dropzone          = document.getElementById('image-dropzone');
        const fileInput         = document.getElementById('image');
        const previewWrapper    = document.getElementById('image-preview-wrapper');
        const previewImg        = document.getElementById('image-preview');
        const filenameText      = document.getElementById('image-filename');
        const emptyState        = document.getElementById('image-empty-state');
        const removeBtn         = document.getElementById('image-remove-btn');
        const existingImageWrap = document.getElementById('existing-image-wrapper');

        const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
        const ALLOWED_TYPES = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];

        function showPreview(file) {
            if (!ALLOWED_TYPES.includes(file.type)) {
                alert('Unsupported file type. Please upload PNG, JPG, JPEG or WEBP.');
                resetFileInput();
                return;
            }

            if (file.size > MAX_FILE_SIZE) {
                alert('File is too large. Maximum allowed size is 2MB.');
                resetFileInput();
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                filenameText.textContent = file.name;
                previewWrapper.classList.remove('hidden');
                emptyState.classList.add('hidden');

                // Hide the "current image" block on edit page once a new file is chosen
                if (existingImageWrap) {
                    existingImageWrap.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        }

        function resetFileInput() {
            fileInput.value = '';
            previewImg.src = '';
            previewWrapper.classList.add('hidden');
            emptyState.classList.remove('hidden');

            if (existingImageWrap) {
                existingImageWrap.classList.remove('hidden');
            }
        }

        fileInput.addEventListener('change', function () {
            if (fileInput.files && fileInput.files[0]) {
                showPreview(fileInput.files[0]);
            }
        });

        removeBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            resetFileInput();
        });

        // Drag & drop visual + handling
        ['dragenter', 'dragover'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-amber-500', 'bg-amber-500/5');
            });
        });

        ['dragleave', 'drop'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-amber-500', 'bg-amber-500/5');
            });
        });

        dropzone.addEventListener('drop', function (e) {
            const files = e.dataTransfer.files;
            if (files && files[0]) {
                // Sync dropped file into the actual <input type="file"> so it submits with the form
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                fileInput.files = dataTransfer.files;
                showPreview(files[0]);
            }
        });

        // ---------- Status Toggle Switch ----------
        const toggleBtn    = document.getElementById('status_toggle');
        const toggleKnob   = document.getElementById('status_toggle_knob');
        const statusInput  = document.getElementById('status');
        const statusLabel  = document.getElementById('status-label-text');
        const statusDesc   = statusLabel.closest('div').querySelector('p');

        toggleBtn.addEventListener('click', function () {

    const isActive = Number(statusInput.value) === 1;
    const nextState = isActive ? 0 : 1;

    statusInput.value = nextState;

    toggleBtn.setAttribute('aria-checked', nextState === 1 ? 'true' : 'false');

    if (nextState === 1) {

        toggleBtn.classList.remove('bg-slate-700');
        toggleBtn.classList.add('bg-amber-500');

        toggleKnob.classList.remove('translate-x-1');
        toggleKnob.classList.add('translate-x-6');

        statusLabel.textContent = 'Active';
        statusDesc.textContent = 'Visible to customers';

    } else {

        toggleBtn.classList.remove('bg-amber-500');
        toggleBtn.classList.add('bg-slate-700');

        toggleKnob.classList.remove('translate-x-6');
        toggleKnob.classList.add('translate-x-1');

        statusLabel.textContent = 'Inactive';
        statusDesc.textContent = 'Hidden from customers';
    }

});
    })();
</script>