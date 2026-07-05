@extends('admin.layouts.app')

@section('title','Dashboard')

@section('page-title','Dashboard')

@section('content')

<div class="space-y-6">

    @include('admin.dashboard.partials.welcome')

    @include('admin.dashboard.partials.stats')

    @include('admin.dashboard.partials.analytics')

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        @include('admin.dashboard.partials.recent-orders')

        @include('admin.dashboard.partials.top-products')

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        @include('admin.dashboard.partials.latest-customers')

        @include('admin.dashboard.partials.quick-actions')

    </div>

</div>

@endsection
