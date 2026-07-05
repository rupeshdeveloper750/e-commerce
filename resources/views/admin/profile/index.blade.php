@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'info' }">

    {{-- Breadcrumb + Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <nav class="mb-3 flex items-center gap-2 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-[#B88A44] transition">Dashboard</a>
                <span class="text-gray-600">/</span>
                <span class="font-medium text-[#B88A44]">My Profile</span>
            </nav>
            <h1 class="text-3xl font-bold tracking-tight text-white">Account Settings</h1>
            <p class="mt-1 text-sm text-slate-400">Manage your profile information, avatar, and security settings.</p>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition
             class="flex items-center gap-3 rounded-2xl border border-emerald-800 bg-emerald-950/50 px-5 py-4 text-sm text-emerald-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto text-emerald-500 hover:text-emerald-300 text-lg leading-none">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-rose-800 bg-rose-950/50 px-5 py-4 text-sm text-rose-300">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Profile Overview Card --}}
    <div class="rounded-3xl border border-slate-800 bg-[#111827] overflow-hidden shadow-xl">
        {{-- Cover Banner --}}
        <div class="h-32 w-full bg-gradient-to-r from-[#0d1117] via-[#1c2533] to-[#B88A44]/20 relative">
            <div class="absolute inset-0 opacity-10" style="background-image:url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23B88A44' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E\");"></div>
        </div>

        <div class="px-8 pb-8 -mt-14 relative">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                {{-- Avatar --}}
                <div class="relative w-24 h-24">
                    @if($admin->profile_photo)
                        <img src="{{ Storage::url($admin->profile_photo) }}" alt="{{ $admin->name }}"
                             class="w-24 h-24 rounded-2xl border-4 border-[#111827] object-cover shadow-xl">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&background=B88A44&color=fff&size=128"
                             alt="{{ $admin->name }}"
                             class="w-24 h-24 rounded-2xl border-4 border-[#111827] object-cover shadow-xl">
                    @endif
                    <button @click="activeTab = 'avatar'"
                            class="absolute -bottom-1 -right-1 flex h-7 w-7 items-center justify-center rounded-full bg-[#B88A44] border-2 border-[#111827] text-white hover:bg-[#a67936] transition shadow"
                            title="Change avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 pb-1">
                    <h2 class="text-xl font-bold text-white">{{ $admin->name }}</h2>
                    <p class="text-sm text-slate-400">{{ $admin->email }}</p>
                    <div class="mt-2 flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center rounded-full bg-[#B88A44]/10 px-2.5 py-0.5 text-xs font-semibold text-[#B88A44] border border-[#B88A44]/20">
                            {{ $admin->roles->pluck('name')->join(', ') ?: 'Super Admin' }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xs font-medium text-emerald-400 border border-emerald-500/20">
                            Active
                        </span>
                        <span class="text-xs text-slate-500">Member since {{ $admin->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex items-center gap-1 rounded-2xl border border-slate-800 bg-[#111827] p-1.5 shadow-xl w-fit">
        <button @click="activeTab = 'info'"
                :class="activeTab === 'info' ? 'bg-[#B88A44] text-white shadow-md' : 'text-slate-400 hover:text-white'"
                class="rounded-xl px-5 py-2.5 text-sm font-semibold transition-all duration-200">
            Profile Info
        </button>
        <button @click="activeTab = 'avatar'"
                :class="activeTab === 'avatar' ? 'bg-[#B88A44] text-white shadow-md' : 'text-slate-400 hover:text-white'"
                class="rounded-xl px-5 py-2.5 text-sm font-semibold transition-all duration-200">
            Change Photo
        </button>
        <button @click="activeTab = 'password'"
                :class="activeTab === 'password' ? 'bg-[#B88A44] text-white shadow-md' : 'text-slate-400 hover:text-white'"
                class="rounded-xl px-5 py-2.5 text-sm font-semibold transition-all duration-200">
            Security
        </button>
    </div>

    {{-- Tab: Profile Info --}}
    <div x-show="activeTab === 'info'" x-transition class="rounded-3xl border border-slate-800 bg-[#111827] p-8 shadow-xl">
        <div class="mb-6 border-b border-slate-800 pb-4">
            <h3 class="text-lg font-bold text-white">Personal Information</h3>
            <p class="text-xs text-slate-400 mt-0.5">Update your name, email address, and contact info.</p>
        </div>
        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Full Name <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                           placeholder="e.g. Super Admin"
                           class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white placeholder:text-slate-500 focus:border-[#B88A44] focus:outline-none focus:ring-4 focus:ring-[#B88A44]/20 transition">
                    @error('name') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Email Address <span class="text-rose-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                           placeholder="admin@shopme.com"
                           class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white placeholder:text-slate-500 focus:border-[#B88A44] focus:outline-none focus:ring-4 focus:ring-[#B88A44]/20 transition">
                    @error('email') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}"
                           placeholder="+91 98765 43210"
                           class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white placeholder:text-slate-500 focus:border-[#B88A44] focus:outline-none focus:ring-4 focus:ring-[#B88A44]/20 transition">
                    @error('phone') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Account Role</label>
                    <input type="text" value="{{ $admin->roles->pluck('name')->join(', ') ?: 'Super Admin' }}" readonly
                           class="h-12 w-full rounded-xl border border-slate-700 bg-slate-900/50 px-4 text-slate-400 cursor-not-allowed">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Member Since</label>
                    <input type="text" value="{{ $admin->created_at->format('d M Y') }}" readonly
                           class="h-12 w-full rounded-xl border border-slate-700 bg-slate-900/50 px-4 text-slate-400 cursor-not-allowed">
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-slate-800 pt-6">
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 px-5 text-sm font-semibold text-slate-300 hover:text-white transition">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-[#B88A44] px-6 text-sm font-semibold text-white shadow-md shadow-[#B88A44]/10 hover:bg-[#a67936] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Tab: Change Avatar --}}
    <div x-show="activeTab === 'avatar'" x-transition
         class="rounded-3xl border border-slate-800 bg-[#111827] p-8 shadow-xl"
         style="display: none;">
        <div class="mb-6 border-b border-slate-800 pb-4">
            <h3 class="text-lg font-bold text-white">Profile Photo</h3>
            <p class="text-xs text-slate-400 mt-0.5">Upload a new profile photo. Max size: 2MB. Formats: JPG, PNG, GIF, WEBP.</p>
        </div>
        <form action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data"
              x-data="{ preview: null, dragging: false }">
            @csrf
            @method('PUT')

            <div @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="dragging = false; const file = $event.dataTransfer.files[0]; if (file) { $refs.avatarInput.files = $event.dataTransfer.files; preview = URL.createObjectURL(file); }"
                 :class="dragging ? 'border-[#B88A44] bg-[#B88A44]/5' : 'border-slate-700 bg-slate-900/30'"
                 class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed p-10 transition cursor-pointer"
                 @click="$refs.avatarInput.click()">

                <div x-show="preview !== null" class="mb-4">
                    <img :src="preview" class="w-24 h-24 rounded-2xl object-cover border-4 border-slate-700 shadow-xl">
                </div>
                <div x-show="preview === null" class="mb-4">
                    @if($admin->profile_photo)
                        <img src="{{ Storage::url($admin->profile_photo) }}"
                             class="w-24 h-24 rounded-2xl object-cover border-4 border-slate-700 shadow-xl">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <p class="text-sm font-semibold text-slate-300">Click to upload or drag & drop</p>
                <p class="mt-1 text-xs text-slate-500">PNG, JPG, GIF, WEBP up to 2MB</p>

                <input x-ref="avatarInput" type="file" name="avatar" accept="image/*" class="hidden"
                       @change="preview = URL.createObjectURL($event.target.files[0])">
            </div>

            @error('avatar') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror

            <div class="mt-6 flex justify-end gap-3 border-t border-slate-800 pt-6">
                <button @click="activeTab = 'info'" type="button"
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 px-5 text-sm font-semibold text-slate-300 hover:text-white transition">
                    Cancel
                </button>
                <button type="submit"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-[#B88A44] px-6 text-sm font-semibold text-white shadow-md shadow-[#B88A44]/10 hover:bg-[#a67936] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload Photo
                </button>
            </div>
        </form>
    </div>

    {{-- Tab: Security / Change Password --}}
    <div x-show="activeTab === 'password'" x-transition
         class="rounded-3xl border border-slate-800 bg-[#111827] p-8 shadow-xl"
         style="display: none;"
         x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
        <div class="mb-6 border-b border-slate-800 pb-4">
            <h3 class="text-lg font-bold text-white">Change Password</h3>
            <p class="text-xs text-slate-400 mt-0.5">Use a strong and unique password to keep your account secure.</p>
        </div>
        <form action="{{ route('admin.profile.password') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6 max-w-lg">

                {{-- Current Password --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Current Password <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <input :type="showCurrent ? 'text' : 'password'"
                               name="current_password" required
                               placeholder="Enter current password"
                               class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 pr-12 text-white placeholder:text-slate-500 focus:border-[#B88A44] focus:outline-none focus:ring-4 focus:ring-[#B88A44]/20 transition">
                        <button type="button" @click="showCurrent = !showCurrent"
                                class="absolute right-3 top-3.5 text-slate-400 hover:text-white">
                            <svg x-show="!showCurrent" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showCurrent" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('current_password') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">New Password <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <input :type="showNew ? 'text' : 'password'"
                               name="password" required
                               placeholder="Min. 8 characters"
                               class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 pr-12 text-white placeholder:text-slate-500 focus:border-[#B88A44] focus:outline-none focus:ring-4 focus:ring-[#B88A44]/20 transition">
                        <button type="button" @click="showNew = !showNew"
                                class="absolute right-3 top-3.5 text-slate-400 hover:text-white">
                            <svg x-show="!showNew" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showNew" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="mt-1 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-300">Confirm New Password <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'"
                               name="password_confirmation" required
                               placeholder="Repeat new password"
                               class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 pr-12 text-white placeholder:text-slate-500 focus:border-[#B88A44] focus:outline-none focus:ring-4 focus:ring-[#B88A44]/20 transition">
                        <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-3.5 text-slate-400 hover:text-white">
                            <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-slate-800 pt-6">
                <button @click.prevent="activeTab = 'info'" type="button"
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 px-5 text-sm font-semibold text-slate-300 hover:text-white transition">
                    Cancel
                </button>
                <button type="submit"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-rose-600 px-6 text-sm font-semibold text-white shadow-md shadow-rose-900/30 hover:bg-rose-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Update Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
