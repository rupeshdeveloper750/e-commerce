@extends('admin.layouts.app')

@section('title', 'Coupons')

@section('content')
<div class="space-y-6">
    @include('admin.coupon.partials.header')
    @include('admin.coupon.partials.filters')
    @include('admin.coupon.partials.table')
</div>
@endsection
