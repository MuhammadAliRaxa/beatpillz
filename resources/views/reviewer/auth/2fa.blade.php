@extends('reviewer.layouts.auth')
@section('section', translate('Reviewer'))
@section('title', translate('2Fa Verification'))
@section('content')
    <div class="mb-3">
        <h3 class="sign-box-title">{{ translate('2Fa Verification') }}</h3>
        <p class="sign-box-text">{{ translate('Please enter the OTP code to continue') }}</p>
    </div>
    <form action="{{ route('reviewer.2fa.verify') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">{{ translate('OTP Code') }} </label>
            <input type="text" name="otp_code" class="form-control form-control-md input-numeric" placeholder="• • • • • •"
                maxlength="6" required>
        </div>
        <button class="btn btn-primary btn-md w-100">{{ translate('Continue') }}</button>
    </form>
@endsection
