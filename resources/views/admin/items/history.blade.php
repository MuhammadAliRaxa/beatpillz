@extends('admin.layouts.grid')
@section('title', $item->name)
@section('item_view', true)
@section('back', route('admin.items.index'))
@section('content')
    <div class="dashboard-tabs">
        @include('admin.items.includes.tabs-nav')
        <div class="dashboard-tabs-content">
            <div class="row g-3">
                <div class="col-lg-7 order-2 order-sm-0">
                    @include('admin.items.includes.histories')
                </div>
                <div class="col-lg-5">
                    @include('admin.items.includes.sidebar')
                </div>
            </div>
        </div>
    </div>
@endsection
