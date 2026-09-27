@extends('themes.basic.workspace.layouts.app')
@section('title', translate('Settings'))
@section('breadcrumbs', Breadcrumbs::render('workspace.settings.password'))
@section('content')
    @include('themes.basic.workspace.settings.includes.tabs')
    <div class="dashboard-card card-v">
        <div class="form-section">
            <h5 class="mb-0">{{ translate('Change Password') }}</h5>
        </div>
        <form action="{{ route('workspace.settings.password') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ translate('Password') }}</label>
                <input type="password" class="form-control form-control-md " name="current-password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ translate('New Password') }}</label>
                <input type="password" class="form-control form-control-md " name="new-password" required>
            </div>
            <div class="mb-4">
                <label class="form-label">{{ translate('Confirm New Password') }}</label>
                <input type="password" class="form-control form-control-md " name="new-password_confirmation" required>
            </div>
            <button class="btn btn-primary btn-md ">{{ translate('Save Changes') }}</button>
        </form>
    </div>
@endsection
