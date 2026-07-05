@php
    $isEdit = isset($role);
    $formId = 'role-form';
@endphp

<form
    id="{{ $formId }}"
    action="{{ $isEdit ? route('admin.roles.update', $role->id) : route('admin.roles.store') }}"
    method="POST"
    class="pb-28 lg:pb-8"
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

        {{-- Left Column: Role Details --}}
        <div class="lg:col-span-1 space-y-6">
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20 p-6 space-y-6">
                <div>
                    <h2 class="text-base font-semibold text-white">Role Details</h2>
                    <p class="mt-1 text-sm text-slate-400">Define the security role name and identifier.</p>
                </div>

                {{-- Role Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-200 mb-1.5">
                        Role Name <span class="text-red-400">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $isEdit ? $role->name : '') }}"
                        placeholder="e.g. Sales Manager"
                        class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition
                            focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500
                            @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                    >
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-400 flex items-center gap-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-200 mb-1.5">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Provide details about what this role controls..."
                        class="w-full rounded-xl border bg-slate-950/60 px-4 py-2.5 text-sm text-white placeholder-slate-500 transition
                            focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500
                            @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-700 @enderror"
                    >{{ old('description', $isEdit ? $role->description : '') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-sm text-red-400 flex items-center gap-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </section>
        </div>

        {{-- Right Column: Permissions Matrix --}}
        <div class="lg:col-span-2 space-y-6">
            <section class="rounded-2xl border border-slate-800 bg-slate-900/60 shadow-lg shadow-black/20">
                <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-white">Permissions Matrix</h2>
                        <p class="mt-1 text-sm text-slate-400">Select which permissions are granted to this role.</p>
                    </div>
                    
                    {{-- Toggle All --}}
                    <div x-data="{ checked: false }" class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="
                                checked = !checked;
                                document.querySelectorAll('.permission-checkbox').forEach(el => el.checked = checked);
                            "
                            class="text-xs font-semibold text-amber-500 hover:text-amber-400 transition"
                        >
                            Toggle All
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($permissions as $permission)
                            <label class="flex items-start gap-3 p-3 rounded-xl bg-slate-950/40 border border-slate-800/80 hover:border-slate-700/80 transition cursor-pointer select-none">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->id }}"
                                    class="permission-checkbox mt-1 h-4 w-4 rounded border-slate-700 bg-slate-900 text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-900"
                                    {{ in_array($permission->id, old('permissions', $isEdit ? $rolePermissions : [])) ? 'checked' : '' }}
                                >
                                <div>
                                    <span class="block text-sm font-semibold text-white">{{ $permission->name }}</span>
                                    <span class="block text-xs text-slate-400 mt-0.5">{{ $permission->description }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('permissions')
                        <p class="mt-3 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </section>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a
                    href="{{ route('admin.roles.index') }}"
                    class="rounded-xl border border-slate-800 bg-slate-900/60 px-5 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-800/60 hover:text-white transition"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-slate-950 hover:bg-amber-400 transition shadow-lg shadow-amber-500/10"
                >
                    {{ $isEdit ? 'Save Changes' : 'Create Role' }}
                </button>
            </div>
        </div>

    </div>
</form>
