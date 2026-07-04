@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    @include('admin.category.partials.header')

    {{-- Filters --}}
    @include('admin.category.partials.filters')

    {{-- Table --}}
    @include('admin.category.partials.table')

    {{-- Add/Edit Modal --}}
    @include('admin.category.partials.modal')

</div>

@endsection