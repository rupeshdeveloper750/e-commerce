@php
    $isEdit        = isset($brand);
    $formId        = 'brand-form';
    $statusOld     = old('status', $isEdit ? ($brand->status ? 1 : 0) : 1);
    $featuredOld   = old('is_featured', $isEdit ? ($brand->is_featured ? 1 : 0) : 0);
@endphp

<form
    id="{{ $formId }}"
    action="{{ $isEdit ? route('admin.brands.update', $brand->id) : route('admin.brands.store') }}"
    method="POST"
    enctype="multipart/form-data"
    class="pb-28 lg:pb-8"
    novalidate
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        {{-- LEFT COLUMN (2/3) — Basic Info + Description --}}
        <div class="lg:col-span-2 space-y-6">
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Basic Information</h2>
                    <p class="mt-1 text-sm text-slate-400">Name and slug for this brand.</p>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Brand Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-200 mb-1.5">
                            Brand Name <span class="text-red-400">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            autocomplete="off"
                            value="{{ old('name', $isEdit ? $brand->name : '') }}"
                            placeholder="e.g. Nike"
                            class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
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
                            value="{{ old('slug', $isEdit ? $brand->slug : '') }}"
                            placeholder="e.g. nike"
                            class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('slug') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                        >
                        <p class="mt-1.5 text-xs text-slate-500">Leave blank to auto-generate from the brand name.</p>
                        @error('slug')
                            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sort Order --}}
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-slate-200 mb-1.5">
                            Sort Order
                        </label>
                        <input
                            type="number"
                            id="sort_order"
                            name="sort_order"
                            value="{{ old('sort_order', $isEdit ? $brand->sort_order : '0') }}"
                            placeholder="0"
                            class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('sort_order') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                        >
                        @error('sort_order')
                            <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- CARD 2: Description --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Description</h2>
                    <p class="mt-1 text-sm text-slate-400">A brief information about this brand.</p>
                </div>
                <div class="p-6">
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Write something about this brand..."
                        class="w-full rounded-xl border bg-slate-950/60 px-4 py-3 text-sm text-white placeholder-slate-500 transition resize-y focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                    >{{ old('description', $isEdit ? $brand->description : '') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </section>
        </div>

        {{-- RIGHT COLUMN (1/3) — Image + Status + Featured --}}
        <div class="space-y-6">
            {{-- CARD 3: Brand Logo Image --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Brand Logo</h2>
                    <p class="mt-1 text-sm text-slate-400">Displayed as the brand logo thumbnail.</p>
                </div>
                <div class="p-6">
                    <label for="image" class="block text-sm font-medium text-slate-200 mb-1.5">Upload Logo</label>
                    <div
                        id="image-dropzone"
                        class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-700 bg-slate-950/40 px-4 py-8 text-center transition hover:border-amber-500 hover:bg-amber-500/5 cursor-pointer @error('image') border-red-500 @enderror"
                    >
                        <div id="image-preview-wrapper" class="hidden w-full">
                            <img id="image-preview" src="" alt="Brand logo preview" class="mx-auto max-h-40 rounded-lg object-cover shadow-sm">
                            <p id="image-filename" class="mt-3 text-xs font-medium text-slate-300 truncate"></p>
                            <button type="button" id="image-remove-btn" class="mt-3 inline-flex items-center gap-1 rounded-lg border border-slate-700 bg-slate-900 px-3 py-1.5 text-xs font-semibold text-red-400 transition hover:bg-red-500/10">
                                Remove Logo
                            </button>
                        </div>
                        <div id="image-empty-state" class="flex flex-col items-center gap-2">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-500/10">
                                <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 8.25L12 3.75m0 0L7.5 8.25M12 3.75v12" />
                                </svg>
                            </span>
                            <p class="text-sm font-medium text-slate-200"><span class="text-amber-500">Click to upload</span> or drag & drop</p>
                            <p class="text-xs text-slate-400">PNG, JPG, JPEG or WEBP (Max 2MB)</p>
                        </div>
                        <input type="file" id="image" name="image" accept="image/png,image/jpeg,image/jpg,image/webp" class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
                    </div>
                    @error('image')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror

                    @if ($isEdit && $brand->image)
                        <div id="existing-image-wrapper" class="mt-4">
                            <p class="text-xs font-medium text-slate-400 mb-2">Current Logo</p>
                            <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }} current image" class="h-20 w-20 rounded-lg border border-slate-700 object-cover">
                        </div>
                    @endif
                </div>
            </section>

            {{-- CARD 4: Featured Status --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Featured</h2>
                    <p class="mt-1 text-sm text-slate-400">Promote this brand on the home page.</p>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="featured_toggle" class="text-sm font-medium text-slate-200">
                                <span id="featured-label-text">{{ $featuredOld == 1 ? 'Featured' : 'Standard' }}</span>
                            </label>
                            <p class="text-xs text-slate-400">{{ $featuredOld == 1 ? 'Highlighted on home' : 'Regular brand list' }}</p>
                        </div>
                        <button
                            type="button"
                            id="featured_toggle"
                            role="switch"
                            aria-checked="{{ $featuredOld == 1 ? 'true' : 'false' }}"
                            aria-labelledby="featured-label-text"
                            class="relative inline-flex h-7 w-12 shrink-0 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-slate-900 {{ $featuredOld == 1 ? 'bg-amber-500' : 'bg-slate-700' }}"
                        >
                            <span id="featured_toggle_knob" class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200 {{ $featuredOld == 1 ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                        <input type="hidden" id="is_featured" name="is_featured" value="{{ $featuredOld }}">
                    </div>
                    @error('is_featured')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            {{-- CARD 5: Status --}}
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-base font-semibold text-white">Status</h2>
                    <p class="mt-1 text-sm text-slate-400">Control brand visibility on store.</p>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="status_toggle" class="text-sm font-medium text-slate-200">
                                <span id="status-label-text">{{ $statusOld == 1 ? 'Active' : 'Inactive' }}</span>
                            </label>
                            <p class="text-xs text-slate-400">{{ $statusOld == 1 ? 'Visible to customers' : 'Hidden from customers' }}</p>
                        </div>
                        <button
                            type="button"
                            id="status_toggle"
                            role="switch"
                            aria-checked="{{ $statusOld == 1 ? 'true' : 'false' }}"
                            aria-labelledby="status-label-text"
                            class="relative inline-flex h-7 w-12 shrink-0 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-slate-900 {{ $statusOld == 1 ? 'bg-amber-500' : 'bg-slate-700' }}"
                        >
                            <span id="status_toggle_knob" class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200 {{ $statusOld == 1 ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                        <input type="hidden" id="status" name="status" value="{{ $statusOld }}">
                    </div>
                    @error('status')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </section>
        </div>
    </div>

    {{-- Action Bar --}}
    <div class="fixed inset-x-0 bottom-0 z-20 border-t border-slate-800 bg-slate-950/90 backdrop-blur lg:static lg:mt-8 lg:border-0 lg:bg-transparent lg:backdrop-blur-none">
        <div class="mx-auto flex max-w-screen-2xl items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:px-0">
            <a href="{{ route('admin.brands.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-700 bg-slate-900 px-5 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-amber-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                {{ $isEdit ? 'Update Brand' : 'Save Brand' }}
            </button>
        </div>
    </div>
</form>

<script>
    (function () {
        'use strict';
        // Slug auto-generation
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        let slugManuallyEdited = slugInput.value.trim().length > 0;

        function slugify(text) {
            return text.toString().trim().toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
        slugInput.addEventListener('input', () => { slugManuallyEdited = slugInput.value.trim().length > 0; });
        nameInput.addEventListener('input', () => { if (!slugManuallyEdited) { slugInput.value = slugify(nameInput.value); } });

        // Image Preview & Dragzone
        const dropzone = document.getElementById('image-dropzone');
        const fileInput = document.getElementById('image');
        const previewWrapper = document.getElementById('image-preview-wrapper');
        const previewImg = document.getElementById('image-preview');
        const filenameText = document.getElementById('image-filename');
        const emptyState = document.getElementById('image-empty-state');
        const removeBtn = document.getElementById('image-remove-btn');
        const existingImageWrap = document.getElementById('existing-image-wrapper');

        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                filenameText.textContent = file.name;
                previewWrapper.classList.remove('hidden');
                emptyState.classList.add('hidden');
                if (existingImageWrap) { existingImageWrap.classList.add('hidden'); }
            };
            reader.readAsDataURL(file);
        }
        function resetFileInput() {
            fileInput.value = '';
            previewImg.src = '';
            previewWrapper.classList.add('hidden');
            emptyState.classList.remove('hidden');
            if (existingImageWrap) { existingImageWrap.classList.remove('hidden'); }
        }
        fileInput.addEventListener('change', () => { if (fileInput.files && fileInput.files[0]) { showPreview(fileInput.files[0]); } });
        removeBtn.addEventListener('click', (e) => { e.stopPropagation(); resetFileInput(); });

        dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('border-amber-500', 'bg-amber-500/5'); });
        dropzone.addEventListener('dragleave', () => { dropzone.classList.remove('border-amber-500', 'bg-amber-500/5'); });
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-amber-500', 'bg-amber-500/5');
            const files = e.dataTransfer.files;
            if (files && files[0]) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                fileInput.files = dataTransfer.files;
                showPreview(files[0]);
            }
        });

        // Status Toggle
        const statusBtn = document.getElementById('status_toggle');
        const statusKnob = document.getElementById('status_toggle_knob');
        const statusInput = document.getElementById('status');
        const statusLabel = document.getElementById('status-label-text');
        const statusDesc = statusLabel.closest('div').querySelector('p');

        statusBtn.addEventListener('click', () => {
            const isActive = Number(statusInput.value) === 1;
            const nextState = isActive ? 0 : 1;
            statusInput.value = nextState;
            statusBtn.setAttribute('aria-checked', nextState === 1 ? 'true' : 'false');
            if (nextState === 1) {
                statusBtn.classList.replace('bg-slate-700', 'bg-amber-500');
                statusKnob.classList.replace('translate-x-1', 'translate-x-6');
                statusLabel.textContent = 'Active';
                statusDesc.textContent = 'Visible to customers';
            } else {
                statusBtn.classList.replace('bg-amber-500', 'bg-slate-700');
                statusKnob.classList.replace('translate-x-6', 'translate-x-1');
                statusLabel.textContent = 'Inactive';
                statusDesc.textContent = 'Hidden from customers';
            }
        });

        // Featured Toggle
        const featBtn = document.getElementById('featured_toggle');
        const featKnob = document.getElementById('featured_toggle_knob');
        const featInput = document.getElementById('is_featured');
        const featLabel = document.getElementById('featured-label-text');
        const featDesc = featLabel.closest('div').querySelector('p');

        featBtn.addEventListener('click', () => {
            const isFeat = Number(featInput.value) === 1;
            const nextState = isFeat ? 0 : 1;
            featInput.value = nextState;
            featBtn.setAttribute('aria-checked', nextState === 1 ? 'true' : 'false');
            if (nextState === 1) {
                featBtn.classList.replace('bg-slate-700', 'bg-amber-500');
                featKnob.classList.replace('translate-x-1', 'translate-x-6');
                featLabel.textContent = 'Featured';
                featDesc.textContent = 'Highlighted on home';
            } else {
                featBtn.classList.replace('bg-amber-500', 'bg-slate-700');
                featKnob.classList.replace('translate-x-6', 'translate-x-1');
                featLabel.textContent = 'Standard';
                featDesc.textContent = 'Regular brand list';
            }
        });
    })();
</script>
