@extends('reviewer.layouts.auth')
@section('section', translate('Reviewer'))
@section('title', translate('Login'))
@section('content')
    <div class="mb-3">
        <h3 class="sign-box-title">{{ translate('Login') }}</h3>
        <p class="sign-box-text">{{ translate('Log in to your account to continue.') }}</p>
    </div>
    <form action="{{ route('reviewer.login.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">{{ translate('Email or Username') }} </label>
            <input type="text" name="email_or_username" class="form-control form-control-md" value="{{ old('email') }}"
                required />
        </div>
        <div class="mb-3">
            <div class="row row-cols-auto flex-nowrap justify-content-between align-items-center">
                <div class="col">
                    <label class="form-label">{{ translate('Password') }} </label>
                </div>
                <div class="col">
                    <a href="{{ route('reviewer.password.request') }}"
                        class="mb-2 d-block">{{ translate('Forgot password') }}?</a>
                </div>
            </div>
            <input type="password" name="password" class="form-control form-control-md" required />
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                    class="form-check-input">
                <span class="form-check-label">{{ translate('Remember me') }}</span>
            </div>
        </div>
        <x-captcha />
        <button class="btn btn-primary btn-md w-100">{{ translate('Login') }}</button>
    </form>
@endsection
