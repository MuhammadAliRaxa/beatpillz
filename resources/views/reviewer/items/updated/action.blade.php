@extends('reviewer.layouts.app')
@section('title', translate('Update Review: :item_name', ['item_name' => $itemUpdate->name]))
@section('update_download', true)
@section('content')
    <div class="dashboard-tabs">
        @include('reviewer.items.updated.includes.tabs-nav')
        <div class="dashboard-tabs-content">
            <div class="row g-3">
                <div class="col-lg-7 order-2 order-sm-0">
                    <div class="card-v h-100">
                        <div class="row g-3 row-cols-1 row-cols-xl-2 align-items-center">
                            <div class="col">
                                <form action="{{ route('reviewer.items.updated.action', $itemUpdate->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button class="btn btn-green btn-md action-confirm w-100">
                                        <i class="fa-regular fa-circle-check me-1"></i>
                                        <span>{{ translate('Approve') }}</span>
                                    </button>
                                </form>
                            </div>
                            <div class="col">
                                <button id="updateReject" class="btn btn-red btn-md w-100">
                                    <i class="fa-regular fa-circle-xmark me-1"></i>
                                    <span>{{ translate('Reject') }}</span>
                                </button>
                            </div>
                        </div>
                        <div id="updateRejectReason" class="mt-4" style="display: none;">
                            <form action="{{ route('reviewer.items.updated.action', $itemUpdate->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <div class="mb-3">
                                    <label class="form-label">{{ translate('Reason') }}</label>
                                    <textarea name="reason" class="form-control" rows="6" required></textarea>
                                </div>
                                <button
                                    class="btn btn-primary btn-md action-confirm">{{ translate('Submit') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    @include('reviewer.items.updated.includes.sidebar')
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            "use strict";
            let updateReject = $('#updateReject'),
                updateRejectReason = $('#updateRejectReason');
            updateReject.on('click', function() {
                updateRejectReason.toggle();
            });
        </script>
    @endpush
@endsection
