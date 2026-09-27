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
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <div class="alert alert-warning">
                                <p class="mb-0">
                                    <i class="fa-regular fa-circle-question me-1"></i>
                                    {{ translate('This option is to change the status of the item at any time without notifying the user, in case the user wants to restore the item after deleting or allowing the user to update it after hard rejection etc...') }}
                                </p>
                            </div>
                            <form action="{{ route('admin.items.status', $item->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">{{ translate('Status') }}</label>
                                    <select name="status" class="form-select form-select-lg">
                                        @foreach (\App\Models\Item::getStatusOptions() as $key => $value)
                                            <option value="{{ $key }}" @selected($item->status == $key)>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="btn btn-primary btn-lg w-100">{{ translate('Save') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    @include('admin.items.includes.sidebar')
                </div>
            </div>
        </div>
    </div>
@endsection
