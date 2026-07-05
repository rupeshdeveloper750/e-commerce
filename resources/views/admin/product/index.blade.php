@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="space-y-6">
    @include('admin.product.partials.header')
    @include('admin.product.partials.filters')
    @include('admin.product.partials.table')
</div>
@endsection
