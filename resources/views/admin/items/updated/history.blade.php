@extends('admin.layouts.grid')
@section('title', translate('Update Review: :item_name', ['item_name' => $itemUpdate->item->name]))
@section('back', route('admin.items.updated.index'))
@section('content')
    <div class="dashboard-tabs">
        @include('admin.items.updated.includes.tabs-nav')
        <div class="dashboard-tabs-content">
            <div class="row g-3">
                <div class="col-lg-7 order-2 order-sm-0">
                    @include('admin.items.includes.histories')
                </div>
                <div class="col-lg-5">
                    @include('admin.items.updated.includes.sidebar')
                </div>
            </div>
        </div>
    </div>
@endsection
