<!DOCTYPE html>
<html lang="{{ getLocale() }}" dir="{{ getDirection() }}">

<head>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/simplebar/simplebar.min.css') }}">
    @endpush
    @section('noindex', true)
    @include('themes.basic.includes.head')
</head>

<body>
    <div class="dashboard">
        @include('themes.basic.workspace.includes.sidebar')
        <div class="dashboard-body">
            @include('themes.basic.workspace.includes.navbar')
            <div class="dashboard-container @yield('container') pt-4 pb-5">
                @if (@settings('kyc')->status && @settings('kyc')->required && !authUser()->isKycVerified())
                    @if (authUser()->isKycPending())
                        <div class="alert alert-warning">
                            <h4 class="alert-heading">{{ translate('KYC Verification Pending') }}</h4>
                            <span>{{ translate('Your KYC verification is currently pending. We are processing your information, and you will be notified once the verification is complete.') }}</span>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <h4 class="alert-heading">{{ translate('KYC Verification Required') }}</h4>
                            <p>{{ translate('Your KYC verification is required to continue using our platform. Please complete the verification process as soon as possible.') }}
                            </p>
                            <a href="{{ route('workspace.settings.kyc') }}"
                                class="btn btn-danger">{{ translate('Complete KYC') }}<i
                                    class="fa-solid fa-arrow-right ms-2"></i></a>
                        </div>
                    @endif
                @endif
                @if (licenseType(2) && @$settings->premium->status && authUser()->isSubscribed())
                    @if (authUser()->subscription->isAboutToExpire())
                        <div class="alert alert-warning">
                            <h4 class="alert-heading">{{ translate('Your subscription is about to expire') }}</h4>
                            <span>{{ translate('Your current subscription is nearing its expiration date. To continue enjoying uninterrupted access to premium features, please renew your subscription before it expires.') }}</span>
                        </div>
                    @elseif(authUser()->subscription->isExpired())
                        <div class="alert alert-danger">
                            <h4 class="alert-heading">{{ translate('Your subscription has been expired') }}</h4>
                            <span>{{ translate('Your subscription has expired, and you no longer have access to premium features. Please renew your subscription to regain access to all features.') }}</span>
                        </div>
                    @endif
                @endif
                @if (!request()->routeIs('workspace.become-an-author'))
                    <div class="mb-4">
                        <div class="row g-3 justify-content-between align-items-center">
                            <div class="col">
                                <h3>@yield('title')</h3>
                                @yield('breadcrumbs')
                            </div>
                            @if (request()->routeIs('workspace.dashboard'))
                                <div class="col-auto">
                                    @include('themes.basic.workspace.partials.period-select', [
                                        'date' => authUser()->created_at,
                                    ])
                                </div>
                            @endif
                            @hasSection('back')
                                <div class="col-auto">
                                    <a href="@yield('back')" class="btn btn-outline-secondary btn-md">
                                        <i class="fa-solid fa-arrow-left fa-rtl me-1"></i>
                                        {{ translate('Back') }}
                                    </a>
                                </div>
                            @endif
                            @hasSection('create')
                                <div class="col-auto">
                                    <a href="@yield('create')" class="btn btn-primary btn-md">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            @endif
                            @if (request()->routeIs('workspace.items.index'))
                                @if ($items->count() > 0 || request()->input('search') || request()->input('category'))
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                                            data-bs-target="#addItemModel">
                                            <i class="fa-regular fa-plus me-1"></i>
                                            {{ translate('New Item') }}
                                        </button>
                                    </div>
                                @endif
                            @endif
                            @if (request()->routeIs('workspace.transactions.show'))
                                @if ($trx->isPaid())
                                    <div class="col-auto">
                                        <a href="{{ route('workspace.transactions.invoice', $trx->id) }}"
                                            target="_blank" class="btn btn-primary btn-md">
                                            <i class="fa-regular fa-file-lines me-1"></i>
                                            {{ translate('Invoice') }}
                                        </a>
                                    </div>
                                @endif
                            @endif
                            @if (request()->routeIs('workspace.withdrawals.index'))
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                                        data-bs-target="#withdrawModel">
                                        <i class="fa-regular fa-paper-plane me-1"></i>
                                        {{ translate('Withdraw') }}
                                    </button>
                                </div>
                            @endif
                            @if (@$settings->deposit->status && request()->routeIs('workspace.balance.index'))
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                                        data-bs-target="#depositModel">
                                        <i class="fa-solid fa-circle-dollar-to-slot me-1"></i>
                                        {{ translate('Deposit') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    @yield('content')
                @else
                    <div class="mt-4">
                        @yield('content')
                    </div>
                @endif
            </div>
            <footer class="dashboard-footer">
                <div class="row justify-content-between">
                    <div class="col-auto">
                        <p class="mb-0">&copy; <span data-year></span>
                            {{ @$settings->general->site_name }} - {{ translate('All rights reserved') }}.</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/simplebar/simplebar.min.js') }}"></script>
    @endpush
    @include('themes.basic.includes.config')
    @include('themes.basic.includes.scripts')
</body>

</html>
