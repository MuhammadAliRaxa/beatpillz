@extends('themes.basic.workspace.layouts.app')
@section('title', translate('Settings'))
@section('breadcrumbs', Breadcrumbs::render('workspace.settings.withdrawal'))
@section('content')
    @include('themes.basic.workspace.settings.includes.tabs')
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="dashboard-card card-v h-100">
                <div class="form-section">
                    <h5 class="mb-0">{{ translate('Withdrawal Details') }}</h5>
                </div>
                <form action="{{ route('workspace.settings.withdrawal.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ translate('Withdrawal Method') }}</label>
                        <select name="withdrawal_method" class="form-select form-select-md">
                            <option value="">--</option>
                            @foreach ($withdrawalMethods as $withdrawalMethod)
                                <option value="{{ $withdrawalMethod->id }}" @selected($withdrawalMethod->id == $user->withdrawal_method_id)>
                                    {{ $withdrawalMethod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ translate('Withdrawal Account') }}</label>
                        <textarea type="text" name="withdrawal_account" class="form-control" rows="5">{{ $user->withdrawal_account }}</textarea>
                    </div>
                    <button class="btn btn-primary btn-md">{{ translate('Save Changes') }}</button>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="dashboard-card card-v h-100">
                <div class="form-section">
                    <h5 class="mb-0">{{ translate('Withdrawal Methods') }}</h5>
                </div>
                <div class="accordion dashboard-accordion" id="accordion">
                    @foreach ($withdrawalMethods as $withdrawalMethod)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button p-4 collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $withdrawalMethod->id }}" aria-expanded="false"
                                    aria-controls="collapse{{ $withdrawalMethod->id }}">
                                    <span>{{ $withdrawalMethod->name }}</span>
                                </button>
                            </h2>
                            <div id="collapse{{ $withdrawalMethod->id }}" class="accordion-collapse collapse"
                                data-bs-parent="#accordion">
                                <div class="accordion-body p-4">
                                    <h6 class="m-0">
                                        <span>{{ translate('Minimum Withdrawal Amount') }} :</span>
                                        <strong>{{ getAmount($withdrawalMethod->minimum) }}</strong>
                                    </h6>
                                    @if ($withdrawalMethod->description)
                                        <div class="mt-3">
                                            {!! $withdrawalMethod->description !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
