@extends('themes.basic.layouts.auth')
@section('title', translate('Reset Password'))
@section('content')
    <div class="sign-box">
        <div class="card-v">
            <div class="mb-4">
                <h2 class="sign-box-title">{{ translate('Reset Password') }}</h2>
                <p class="sign-box-text">{{ translate('Enter a new password to continue.') }}</p>
            </div>
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-3">
                    <label class="form-label">{{ translate('Email address') }}</label>
                    <input type="email" name="email" class="form-control form-control-md"
                        placeholder="{{ translate('Email address') }}" value="{{ $email }}" readonly />
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ translate('Password') }}</label>
                    <input type="password" name="password" class="form-control form-control-md"
                        placeholder="{{ translate('Password') }}" minlength="8" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ translate('Confirm password') }}</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-md"
                        placeholder="{{ translate('Confirm password') }}" minlength="8" required>
                </div>
                <x-captcha />
                <button class="btn btn-primary btn-md w-100">{{ translate('Reset') }}</button>
            </form>
        </div>
    </div>
@endsection
