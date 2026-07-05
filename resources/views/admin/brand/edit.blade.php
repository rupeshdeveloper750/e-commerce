@extends('admin.layouts.app')

@section('title', 'Edit Brand')

@section('content')
<div class="mx-auto max-w-screen-2xl px-4 py-6 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <nav class="flex items-center gap-1.5 text-sm text-slate-400">
                <a href="{{ route('admin.brands.index') }}" class="hover:text-amber-500">Brands</a>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
                <span class="font-medium text-slate-200">Edit</span>
            </nav>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-white">Edit Brand</h1>
            <p class="mt-1 text-sm text-slate-400">Modify the details of your brand brand: {{ $brand->name }}</p>
        </div>
    </div>

    @include('admin.brand._form')
</div>
@endsection
