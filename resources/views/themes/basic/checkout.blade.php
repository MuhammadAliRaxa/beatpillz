@extends('themes.basic.layouts.single')
@section('noindex', true)
@section('header_title', translate('Checkout'))
@section('title', translate('Checkout'))
@section('breadcrumbs', Breadcrumbs::render('checkout', $trx))
@section('header_v3', true)
@section('content')
    @if ($trx->isUnpaid())
        <livewire:checkout :trx="$trx" />
    @else
        <div class="card-v border">
            <div class="col-lg-6 m-auto">
                <div class="py-3 text-center">
                    <div class="mb-4">
                        <i class="fa fa-check-circle text-primary fa-5x"></i>
                    </div>
                    @if ($trx->isTypePurchase())
                        <h2 class="mb-3">{{ translate('Payment completed') }}</h2>
                        <p>
                            {{ translate('Thank you for your purchase. Your payment has been completed successfully.') }}
                        </p>
                        <a href="{{ route('workspace.purchases.index') }}" class="btn btn-outline-primary btn-md mt-2">
                            <i class="fa-solid fa-cart-shopping me-2"></i>
                            <span>{{ translate('View My Purchases') }}</span>
                        </a>
                    @elseif($trx->isTypeDeposit())
                        <h2 class="mb-3">{{ translate('Deposit Completed') }}</h2>
                        <p>
                            {{ translate('Payment has been completed and your deposit has been processed successfully.') }}
                        </p>
                        <a href="{{ route('workspace.balance.index') }}" class="btn btn-outline-primary btn-md mt-2">
                            <i class="fa-solid fa-wallet me-2"></i>
                            <span>{{ translate('View My Balance') }}</span>
                        </a>
                    @elseif($trx->isTypeSubscription())
                        <h2 class="mb-3">{{ translate('Payment Completed') }}</h2>
                        <p>
                            {{ translate('Payment has been completed and your subscription has been created successfully.') }}
                        </p>
                        <a href="{{ route('workspace.settings.subscription') }}"
                            class="btn btn-outline-primary btn-md mt-2">
                            <i class="fa-solid fa-gem me-1"></i>
                            <span>{{ translate('View My Subscription') }}</span>
                        </a>
                    @elseif($trx->isTypeSupportPurchase())
                        <h2 class="mb-3">{{ translate('Payment completed') }}</h2>
                        <p>
                            {{ translate('Payment has been completed and your support has been purchased successfully.') }}
                        </p>
                        <a href="{{ route('workspace.purchases.index') }}" class="btn btn-outline-primary btn-md mt-2">
                            <i class="fa-solid fa-cart-shopping me-2"></i>
                            <span>{{ translate('View My Purchases') }}</span>
                        </a>
                    @elseif($trx->isTypeSupportExtend())
                        <h2 class="mb-3">{{ translate('Payment completed') }}</h2>
                        <p>
                            {{ translate('Payment has been completed and your support has been extended successfully.') }}
                        </p>
                        <a href="{{ route('workspace.purchases.index') }}" class="btn btn-outline-primary btn-md mt-2">
                            <i class="fa-solid fa-cart-shopping me-2"></i>
                            <span>{{ translate('View My Purchases') }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
    @push('scripts')
        <script>
            "use strict";
            let checkoutButton = $('.checkout-button');
            checkoutButton.on('click', function(e) {
                let checkedPaymentMethod = $('.payment-method input:checked');
                if (checkedPaymentMethod.val() == "balance") {
                    if (!confirm(config.translates.actionConfirm)) {
                        e.preventDefault();
                    }
                }
            });
        </script>
    @endpush
@endsection
