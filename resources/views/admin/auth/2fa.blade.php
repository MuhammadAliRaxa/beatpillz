@extends('admin.layouts.auth')
@section('section', translate('Admin'))
@section('title', translate('2Fa Verification'))
@section('content')
    <h1 class="mb-0 h3">{{ translate('2Fa Verification') }}</h1>
    <p class="card-text text-muted">{{ translate('Please enter the OTP code to continue') }}</p>
    <form action="{{ route('admin.2fa.verify') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">{{ translate('OTP Code') }} </label>
            <input type="text" name="otp_code" class="form-control form-control-lg input-numeric" placeholder="• • • • • •"
                maxlength="6" required>
        </div>
        <button class="btn btn-primary btn-lg d-block w-100">{{ translate('Continue') }}</button>
    </form>
@endsection
