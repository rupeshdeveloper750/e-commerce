@extends('admin.layouts.app')

@section('title', 'Add Customer Account')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <nav class="mb-3 flex items-center gap-2 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 transition hover:text-[#B88A44]">Dashboard</a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('admin.customers.index') }}" class="text-gray-500 transition hover:text-[#B88A44]">Customers</a>
            <span class="text-gray-400">/</span>
            <span class="font-medium text-[#B88A44]">Add Customer</span>
        </nav>
        <h1 class="text-3xl font-bold tracking-tight text-white">Create Customer</h1>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="rounded-3xl border border-slate-800 bg-[#111827] p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">Customer Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Password</label>
                    <input type="password" name="password" required class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2">Account Status</label>
                <select name="status" class="h-12 w-full rounded-xl border border-slate-700 bg-slate-800 px-4 text-white focus:border-[#B88A44] focus:outline-none">
                    <option value="1">Active</option>
                    <option value="0">Blocked</option>
                </select>
                @error('status') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('admin.customers.index') }}" class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-700 bg-slate-800 px-6 text-sm font-semibold text-slate-300 hover:text-white transition">Cancel</a>
            <button type="submit" class="inline-flex h-12 items-center justify-center rounded-xl bg-[#B88A44] px-8 text-sm font-semibold text-white transition hover:bg-[#a67936]">Save Customer</button>
        </div>
    </form>
</div>
@endsection
