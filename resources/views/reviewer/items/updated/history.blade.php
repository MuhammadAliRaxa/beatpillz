@extends('reviewer.layouts.app')
@section('title', translate('Update Review: :item_name', ['item_name' => $itemUpdate->name]))
@section('update_download', true)
@section('content')
    <div class="dashboard-tabs">
        @include('reviewer.items.updated.includes.tabs-nav')
        <div class="dashboard-tabs-content">
            <div class="row g-3">
                <div class="col-lg-7 order-2 order-sm-0">
                    @include('reviewer.items.includes.histories')
                </div>
                <div class="col-lg-5">
                    @include('reviewer.items.updated.includes.sidebar')
                </div>
            </div>
        </div>
    </div>
@endsection
