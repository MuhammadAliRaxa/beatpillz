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
                            <div class="row g-3 row-cols-1 row-cols-lg-2 row-cols-xxl-3 align-items-center">
                                <div class="col">
                                    <form action="{{ route('admin.items.action', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button class="btn btn-green btn-md action-confirm w-100">
                                            <i class="fa-regular fa-circle-check me-1"></i>
                                            <span>{{ translate('Approve') }}</span>
                                        </button>
                                    </form>
                                </div>
                                <div class="col">
                                    <button id="softReject" class="btn btn-purple btn-md w-100">
                                        <i class="fa-regular fa-circle-xmark me-1"></i>
                                        <span>{{ translate('Soft Reject') }}</span>
                                    </button>
                                </div>
                                <div class="col">
                                    <form action="{{ route('admin.items.action', $item->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="hard_reject">
                                        <button class="btn btn-red btn-md action-confirm w-100">
                                            <i class="fa-regular fa-circle-xmark me-1"></i>
                                            <span>{{ translate('Hard Reject') }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div id="softRejectReason" class="mt-4" style="display: none;">
                                <form action="{{ route('admin.items.action', $item->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="soft_reject">
                                    <div class="mb-3">
                                        <label class="form-label">{{ translate('Reason') }}</label>
                                        <textarea name="reason" class="form-control" rows="6" required></textarea>
                                    </div>
                                    <button class="btn btn-purple btn-md action-confirm">{{ translate('Submit') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    @include('admin.items.includes.sidebar')
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            "use strict";
            let softReject = $('#softReject'),
                softRejectReason = $('#softRejectReason');
            softReject.on('click', function() {
                softRejectReason.toggle();
            });
        </script>
    @endpush
@endsection
