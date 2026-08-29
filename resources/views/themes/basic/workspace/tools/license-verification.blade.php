@extends('themes.basic.workspace.layouts.app')
@section('title', translate('License Verification'))
@section('breadcrumbs', Breadcrumbs::render('workspace.tools.license-verification'))
@section('container', 'dashboard-container-sm')
@section('content')
    <div class="card-v mb-3">
        <h3 class="mb-3">{{ translate('License Verification') }}</h3>
        <p class="text-muted">
            {{ translate('You can use this tool to verify license codes after receiving them from your buyers.') }}
        </p>
        <form action="{{ route('workspace.tools.license-verification.verify') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="text" name="purchase_code" class="form-control form-control-md"
                    placeholder="{{ translate('Enter purchase code') }}" value="{{ old('purchase_code') }}" required
                    autofocus>
            </div>
            <button class="btn btn-primary btn-md">{{ translate('Verify') }}</button>
        </form>
    </div>
    @php
        $purchase = session('purchase');
    @endphp
    <div class="card-v p-4">
        @if ($purchase)
            <ul class="list-group list-group-flush">
                <li class="list-group-item p-4">
                    <div class="row align-items-center g-3">
                        <div class="col">
                            <strong>{{ translate('Purchase Code') }}</strong>
                        </div>
                        <div class="col-auto">
                            <span>{{ $purchase->code }}</span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item p-4">
                    <div class="row align-items-center g-3">
                        <div class="col">
                            <strong>{{ translate('Item') }}</strong>
                        </div>
                        <div class="col-auto">
                            @php
                                $item = $purchase->item;
                            @endphp
                            @if ($item->isApproved())
                                <a href="{{ $item->getLink() }}" target="_blank">
                                    <i class="fa-solid fa-up-right-from-square me-1"></i>
                                    {{ $item->name }}
                                </a>
                            @else
                                <span>{{ $item->name }}</span>
                            @endif
                        </div>
                    </div>
                </li>
                <li class="list-group-item p-4">
                    <div class="row align-items-center g-3">
                        <div class="col">
                            <strong>{{ translate('License Type') }}</strong>
                        </div>
                        <div class="col-auto">
                            @if ($purchase->isLicenseTypeRegular())
                                <div class="badge bg-gray rounded-2 fw-light px-3 py-2">
                                    {{ translate('Regular') }}
                                </div>
                            @else
                                <div class="badge bg-primary rounded-2 fw-light px-3 py-2">
                                    {{ translate('Extended') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
                <li class="list-group-item p-4">
                    <div class="row align-items-center g-3">
                        <div class="col">
                            <strong>{{ translate('Purchase Date') }}</strong>
                        </div>
                        <div class="col-auto">
                            {{ dateFormat($purchase->created_at) }}
                        </div>
                    </div>
                </li>
                <li class="list-group-item p-4">
                    <div class="row align-items-center g-3">
                        <div class="col">
                            <strong>{{ translate('Downloaded') }}</strong>
                        </div>
                        <div class="col-auto">
                            @if ($purchase->isDownloaded())
                                <div class="badge bg-blue rounded-2 fw-light px-3 py-2">
                                    {{ translate('Yes') }}
                                </div>
                            @else
                                <div class="badge bg-gray rounded-2 fw-light px-3 py-2">
                                    {{ translate('No') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
        @else
            <div class="p-4">
                <p class="text-center text-muted m-0">{{ translate('Not data found') }}</p>
            </div>
        @endif
    </div>
@endsection
