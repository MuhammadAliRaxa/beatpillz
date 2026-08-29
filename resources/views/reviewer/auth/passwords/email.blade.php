@extends('reviewer.layouts.auth')
@section('section', translate('reviewer'))
@section('title', translate('Reset Password'))
@section('content')
    <div class="mb-3">
        <h3 class="sign-box-title">{{ translate('Reset Password') }}</h3>
        <p class="sign-box-text">
            {{ translate('Enter the email address associated with your account and we will send a link to reset your password.') }}
        </p>
    </div>
    <form action="{{ route('reviewer.password.email') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">{{ translate('Email Address') }} </label>
            <input type="email" name="email" class="form-control form-control-md" required />
        </div>
        <x-captcha />
        <button class="btn btn-primary btn-md w-100">{{ translate('Reset Password') }}</button>
    </form>
    <p class="mb-0 text-center text-muted mt-3">{{ translate('Remember your password') }}? <a
            href="{{ route('reviewer.login') }}">{{ translate('Login') }}</a></p>
@endsection
