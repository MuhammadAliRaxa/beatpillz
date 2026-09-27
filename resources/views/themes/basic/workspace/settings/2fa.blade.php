@extends('themes.basic.workspace.layouts.app')
@section('title', translate('Settings'))
@section('breadcrumbs', Breadcrumbs::render('workspace.settings.2fa'))
@section('content')
    @include('themes.basic.workspace.settings.includes.tabs')
    <div class="dashboard-card card-v">
        <div class="form-section">
            <h5 class="mb-0">{{ translate('2FA Authentication') }}</h5>
        </div>
        <p class="text-muted">
            {{ translate('Two-factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity. Two-factor authentication protects against phishing, social engineering, and password brute force attacks and secures your logins from attackers exploiting weak or stolen credentials.') }}
        </p>
        <div class="my-3">
            <div class="row g-3 align-items-center">
                @if (!$user->google2fa_status)
                    <div class="col-12 col-md-12 col-lg-auto col-xl-auto">
                        <div class="text-center mb-2">
                            {!! $QR_Image !!}
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-6 col-xl-3">
                        <div class="input-group mb-3">
                            <input id="input-link" type="text" class="form-control form-control-md "
                                value="{{ $user->google2fa_secret }}" readonly>
                            <button class="btn btn-outline-primary btn-copy" data-clipboard-target="#input-link"><i
                                    class="far fa-clone"></i></button>
                        </div>
                        <button class="btn btn-primary btn-md w-100 " data-bs-toggle="modal"
                            data-bs-target="#towfactorModal">{{ translate('Enable 2FA Authentication') }}</button>
                    </div>
                @else
                    <div class="col-lg-3">
                        <button class="btn btn-danger btn-md w-100 " data-bs-toggle="modal"
                            data-bs-target="#towfactorDisableModal">{{ translate('Disable 2FA Authentication') }}</button>
                    </div>
                @endif
            </div>
        </div>
        <p class="text-muted mb-2">
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
        <li class="mb-0"><a target="_blank"
                href="https://play.google.com/store/apps/details?id=com.azure.authenticator&hl=en_US&gl=US">{{ translate('Microsoft Authenticator for Android') }}</a>
        </li>
    </div>
    @if (!$user->google2fa_status)
        <div class="modal fade" id="towfactorModal" aria-labelledby="towfactorModalLabel" data-bs-backdrop="static"
            data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4">
                    <div class="modal-header border-0 p-0 mb-3">
                        <h5 class="modal-title" id="createFolderModalLabel">{{ translate('OTP Code') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('workspace.settings.2fa.enable') }}" method="POST">
                        @csrf
                        <div class="modal-body p-0">
                            <div class="mb-4">
                                <input type="text" name="otp_code" class="form-control form-control-md input-numeric"
                                    placeholder="• • • • • •" maxlength="6" required>
                            </div>
                            <div class="row justify-content-center g-3">
                                <div class="col-12 col-lg">
                                    <button type="button" class="btn btn-outline-primary btn-md w-100"
                                        data-bs-dismiss="modal">{{ translate('Close') }}</button>
                                </div>
                                <div class="col-12 col-lg">
                                    <button type="submit"
                                        class="btn btn-primary btn-md w-100 ">{{ translate('Enable') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="modal fade" id="towfactorDisableModal" tabindex="-1" aria-labelledby="towfactorDisableModalLabel"
            data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4">
                    <div class="modal-header border-0 p-0 mb-3">
                        <h5 class="modal-title" id="createFolderModalLabel">{{ translate('OTP Code') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('workspace.settings.2fa.disable') }}" method="POST">
                        @csrf
                        <div class="modal-body p-0">
                            <div class="mb-4">
                                <input type="text" name="otp_code" class="form-control form-control-md input-numeric"
                                    placeholder="• • • • • •" maxlength="6" required>
                            </div>
                            <div class="row justify-content-center g-3">
                                <div class="col-12 col-lg">
                                    <button type="button" class="btn btn-outline-danger btn-md w-100"
                                        data-bs-dismiss="modal">{{ translate('Close') }}</button>
                                </div>
                                <div class="col-12 col-lg">
                                    <button type="submit"
                                        class="btn btn-danger btn-md w-100">{{ translate('Disable') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
