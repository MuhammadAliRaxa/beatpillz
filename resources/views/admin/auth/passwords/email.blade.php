@extends('admin.layouts.auth')
@section('section', translate('Admin'))
@section('title', translate('Reset Password'))
@section('content')
    <h1 class="mb-0 h3">{{ translate('Reset Password') }}</h1>
    <p class="card-text text-muted">
        {{ translate('Enter the email address associated with your account and we will send a link to reset your password.') }}
    </p>
    <form action="{{ route('admin.password.email') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">{{ translate('Email Address') }} </label>
            <input type="email" name="email" class="form-control form-control-lg" required />
        </div>
        <x-captcha />
        <button class="btn btn-primary btn-lg d-block w-100">{{ translate('Reset Password') }}</button>
    </form>
    <p class="mb-0 text-center text-muted mt-3">{{ translate('Remember your password') }}? <a
            href="{{ route('admin.login') }}">{{ translate('Login') }}</a></p>
@endsection
