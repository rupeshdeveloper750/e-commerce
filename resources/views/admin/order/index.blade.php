@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
<div class="space-y-6">
    @include('admin.order.partials.header')
    @include('admin.order.partials.filters')
    @include('admin.order.partials.table')
</div>
@endsection
