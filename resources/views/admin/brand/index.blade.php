@extends('admin.layouts.app')

@section('title', 'Brands')

@section('content')
<div class="space-y-6">
    @include('admin.brand.partials.header')
    @include('admin.brand.partials.filters')
    @include('admin.brand.partials.table')
</div>
@endsection
