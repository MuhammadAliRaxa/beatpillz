@extends('admin.layouts.form')
@section('section', translate('Settings'))
@section('title', translate('Referral Settings'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.referral.update') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-4">
                        <label class="form-label">{{ translate('Referral Status') }}</label>
                        <input type="checkbox" name="referral[status]" data-toggle="toggle"
                            {{ @$settings->referral->status ? 'checked' : '' }}>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ translate('Referral Percentage') }}</label>
                        <div class="input-group">
                            <input type="number" name="referral[percentage]" class="form-control" min="1"
                                max="100" step="any" value="{{ @$settings->referral->percentage }}" required>
                            <span class="input-group-text px-3"><i class="fa-solid fa-percent"></i></span>
                        </div>
                        <div class="form-text">
                            {{ translate('Author fee and author tax does not apply to the referral earnings') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
