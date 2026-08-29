@extends('themes.basic.workspace.layouts.app')
@section('title', translate('Settings'))
@section('breadcrumbs', Breadcrumbs::render('workspace.settings'))
@section('content')
    @include('themes.basic.workspace.settings.includes.tabs')
    <div class="dashboard-card card-v mb-3">
        <div class="form-section">
            <h5 class="mb-0">{{ translate('Account details') }}</h5>
        </div>
        <form action="{{ route('workspace.settings.update') }}" method="POST">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-12 col-lg-4">
                    <label class="form-label">{{ translate('First Name') }}</label>
                    <input type="firstname" name="firstname" class="form-control form-control-md"
                        value="{{ $user->firstname }}" required>
                </div>
                <div class="col-12 col-lg-4">
                    <label class="form-label">{{ translate('Last Name') }}</label>
                    <input type="lastname" name="lastname" class="form-control form-control-md"
                        value="{{ $user->lastname }}" required>
                </div>
                <div class="col-12 col-lg-4">
                    <label class="form-label">{{ translate('Username') }}</label>
                    <input type="text" name="username" class="form-control form-control-md" value="{{ $user->username }}"
                        disabled>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ translate('Email address') }}</label>
                    <input type="email" name="email" class="form-control form-control-md" value="{{ $user->email }}">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">{{ translate('Address line 1') }}</label>
                    <input type="text" name="address_line_1" class="form-control form-control-md"
                        value="{{ @$user->address->line_1 }}" required>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">{{ translate('Address line 2') }}</label>
                    <input type="text" name="address_line_2" class="form-control form-control-md"
                        value="{{ @$user->address->line_2 }}">
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label">{{ translate('City') }}</label>
                        <input type="text" name="city" class="form-control form-control-md"
                            value="{{ @$user->address->city }}" required>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label">{{ translate('State') }}</label>
                        <input type="text" name="state" class="form-control form-control-md"
                            value="{{ @$user->address->state }}" required>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label">{{ translate('Postal code') }}</label>
                        <input type="text" name="zip" class="form-control form-control-md"
                            value="{{ @$user->address->zip }}" required>
                    </div>
                </div>
                <div class="col-lg-12">
                    <label class="form-label">{{ translate('Country') }}</label>
                    <select name="country" class="form-select form-select-md" required>
                        <option value="">--</option>
                        @foreach (countries() as $countryCode => $countryName)
                            <option value="{{ $countryCode }}" @selected($countryCode == @$user->address->country)>
                                {{ $countryName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($user->isAuthor())
                    <div class="col-lg-12">
                        <label class="form-label">{{ translate('Exclusivity of Your Items') }}</label>
                        <select name="exclusivity" class="form-select form-select-md">
                            <option value="">--</option>
                            <option value="exclusive" @selected($user->isExclusiveAuthor())>
                                {{ translate('Exclusive') }}
                            </option>
                            <option value="non_exclusive" @selected($user->isNonExclusiveAuthor())>
                                {{ translate('Non Exclusive') }}
                            </option>
                        </select>
                        <div class="form-text">{{ translate('You will be awarded an exclusive author badge') }}
                        </div>
                    </div>
                @endif
            </div>
            <button class="btn btn-primary btn-md">{{ translate('Save Changes') }}</button>
        </form>
    </div>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
