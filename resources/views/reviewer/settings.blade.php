@extends('reviewer.layouts.app')
@section('title', translate('Settings'))
@section('container', 'dashboard-container-md')
@section('content')
    <div class="card-v mb-4">
        <h5 class="mb-4">{{ translate('Account Details') }}</h5>
        <div class="card-v-body">
            <form action="{{ route('reviewer.settings.details') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 align-items-center mb-4">
                    <div class="col-auto">
                        <img id="filePreview" src="{{ $reviewer->getAvatar() }}" alt="{{ $reviewer->getName() }}"
                            class="rounded-3 border" width="70px" height="70px">
                    </div>
                    <div class="col-auto">
                        <button id="selectFileBtn" type="button" class="btn btn-outline-secondary btn-sm"><i
                                class="fas fa-camera me-2"></i>{{ translate('Choose Image') }}</button>
                        <input id="selectedFileInput" type="file" name="avatar"
                            accept="image/png, image/jpg, image/jpeg" hidden>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('First Name') }} </label>
                        <input type="firstname" class="form-control  form-control-md" name="firstname"
                            value="{{ $reviewer->firstname }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('Last Name') }} </label>
                        <input type="lastname" class="form-control  form-control-md" name="lastname"
                            value="{{ $reviewer->lastname }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ translate('Username') }} </label>
                    <input type="text" name="username" class="form-control form-control-md"
                        value="{{ $reviewer->username }}" minlength="5" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ translate('Email Address') }} </label>
                    <input type="email" class="form-control  form-control-md" name="email"
                        value="{{ $reviewer->email }}" required>
                </div>
                @if ($reviewer->categories->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">{{ translate('Categories') }} </label>
                        <div class="row g-2 mb-4">
                            @foreach ($reviewer->categories as $category)
                                <div class="col-auto">
                                    <a href="{{ route('categories.category', $category->slug) }}">
                                        <span class="badge bg-secondary rounded-2 fw-light px-3 py-2">
                                            <i class="fa-solid fa-tags me-1"></i>
                                            {{ $category->name }}
                                        </span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <button class="btn btn-primary btn-md">{{ translate('Save Changes') }}</button>
            </form>
        </div>
    </div>
    <div class="card-v mb-4">
        <h5 class="mb-4">{{ translate('Change Password') }}</h5>
        <div class="card-v-body">
            <form id="vironeer-submited-form" action="{{ route('reviewer.settings.password') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ translate('Password') }} </label>
                    <input type="password" class="form-control  form-control-md" name="current-password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ translate('New Password') }} </label>
                    <input type="password" class="form-control  form-control-md" name="new-password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ translate('Confirm New Password') }} </label>
                    <input type="password" class="form-control  form-control-md" name="new-password_confirmation" required>
                </div>
                <button class="btn btn-primary btn-md">{{ translate('Save Changes') }}</button>
            </form>
        </div>
    </div>
    <div class="card-v">
        <h5 class="mb-4">{{ translate('2Factor Authentication') }}</h5>
        <div class="card-v-body">
            <p class="mb-0">
                {{ translate('Two-factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity. Two-factor authentication protects against phishing, social engineering, and password brute force attacks and secures your logins from attackers exploiting weak or stolen credentials.') }}
            </p>
            <div class="col-lg-7 my-3">
                @if (!$reviewer->google2fa_status)
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            {!! $qrCode !!}
                        </div>
                        <div class="col">
                            <div class="input-group mb-3">
                                <input id="input-secret" type="text" class="form-control form-control-md"
                                    value="{{ $reviewer->google2fa_secret }}" readonly>
                                <button class="btn btn-outline-primary btn-copy" data-clipboard-target="#input-secret"><i
                                        class="far fa-clone"></i></button>
                            </div>
                            <button class="btn btn-primary btn-md w-100" data-bs-toggle="modal"
                                data-bs-target="#enable2FAModal">{{ translate('Enable 2FA Authentication') }}</button>
                        </div>
                    </div>
                @else
                    <button class="btn btn-danger btn-md px-5" data-bs-toggle="modal"
                        data-bs-target="#disable2FAModal">{{ translate('Disable 2FA Authentication') }}</button>
                @endif
            </div>
            <p class="mb-2">
                {{ translate('To use the two factor authentication, you have to install a Google Authenticator compatible app. Here are some that are currently available:') }}
            </p>
            <li class="mb-1"><a target="_blank"
                    href="https://apps.apple.com/us/app/google-authenticator/id388497605">{{ translate('Google Authenticator for iOS') }}</a>
            </li>
            <li class="mb-1"><a target="_blank"
                    href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en&gl=US">{{ translate('Google Authenticator for Android') }}</a>
            </li>
            <li class="mb-1"><a target="_blank"
                    href="https://apps.apple.com/us/app/microsoft-authenticator/id983156458">{{ translate('Microsoft Authenticator for iOS') }}</a>
            </li>
            <li class="mb-1"><a target="_blank"
                    href="https://play.google.com/store/apps/details?id=com.azure.authenticator&hl=en_US&gl=US">{{ translate('Microsoft Authenticator for Android') }}</a>
            </li>
        </div>
    </div>
    @if (!$reviewer->google2fa_status)
        <div class="modal fade" id="enable2FAModal" tabindex="-1" aria-labelledby="enable2FAModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4">
                    <div class="modal-header p-0 mb-3 border-0">
                        <h1 class="modal-title fs-5" id="enable2FAModalLabel">{{ translate('OTP Code') }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form action="{{ route('reviewer.settings.2fa.enable') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="otp_code" class="form-control form-control-md input-numeric"
                                    placeholder="• • • • • •" maxlength="6" required>
                            </div>
                            <div class="row justify-content-center g-3">
                                <div class="col-12 col-lg">
                                    <button type="button" class="btn btn-outline-primary btn-md w-100"
                                        data-bs-dismiss="modal" aria-label="Close">{{ translate('Close') }}</button>
                                </div>
                                <div class="col-12 col-lg">
                                    <button class="btn btn-primary btn-md w-100">{{ translate('Enable') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="modal fade" id="disable2FAModal" tabindex="-1" aria-labelledby="disable2FAModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4">
                    <div class="modal-header p-0 mb-3 border-0">
                        <h1 class="modal-title fs-5" id="disable2FAModalLabel">{{ translate('OTP Code') }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <form action="{{ route('reviewer.settings.2fa.disable') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="otp_code" class="form-control form-control-md input-numeric"
                                    placeholder="• • • • • •" maxlength="6" required>
                            </div>
                            <div class="row justify-content-center g-3">
                                <div class="col-12 col-lg">
                                    <button type="button" class="btn btn-outline-danger btn-md w-100"
                                        data-bs-dismiss="modal" aria-label="Close">{{ translate('Close') }}</button>
                                </div>
                                <div class="col-12 col-lg">
                                    <button class="btn btn-danger btn-md w-100">{{ translate('Disable') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
