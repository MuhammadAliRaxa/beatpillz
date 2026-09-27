@extends('admin.layouts.auth')
@section('section', translate('Admin'))
@section('title', translate('Login'))
@section('content')
    <h1 class="mb-0 h3">{{ translate('Login') }}</h1>
    <p class="card-text text-muted">{{ translate('Log in to your account to continue.') }}</p>
    <form action="{{ route('admin.login.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">{{ translate('Email or Username') }} </label>
            <input type="text" name="email_or_username" class="form-control form-control-lg" value="{{ old('email') }}"
                required />
        </div>
        <div class="mb-3">
            <label class="form-label">{{ translate('Password') }} </label>
            <input type="password" name="password" class="form-control form-control-lg" required />
        </div>
        <div class="row mb-3">
            <div class="col-auto">
                <label class="form-check mb-0">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                        class="form-check-input">
                    <span class="form-check-label">{{ translate('Remember me') }}</span>
                </label>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('admin.password.request') }}">{{ translate('Forgot password') }}?</a>
            </div>
        </div>
        <x-captcha />
        <button class="btn btn-primary btn-lg d-block w-100">{{ translate('Login') }}</button>
    </form>
@endsection
