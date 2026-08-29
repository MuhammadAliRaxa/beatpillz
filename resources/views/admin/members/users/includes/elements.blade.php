<div class="row row-cols-1 row-cols-lg-3 row-cols-xxl-3 g-3 mb-4">
    <div class="col">
        <div class="vironeer-counter-card bg-c-1">
            <div class="vironeer-counter-card-icon">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
            <div class="vironeer-counter-card-meta">
                <p class="vironeer-counter-card-title">{{ translate('Balance') }}</p>
                <p class="vironeer-counter-card-number">{{ getAmount($user->balance) }}</p>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.records.statements.index', ['user' => $user->id]) }}"
                    class="btn btn-outline-light">{{ translate('Statements') }}</a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="vironeer-counter-card bg-c-4">
            <div class="vironeer-counter-card-icon">
                <i class="fa-solid fa-cart-plus"></i>
            </div>
            <div class="vironeer-counter-card-meta">
                <p class="vironeer-counter-card-title">{{ translate('Purchases') }}</p>
                <p class="vironeer-counter-card-number">{{ number_format($user->purchases_count) }}</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="vironeer-counter-card bg-c-5">
            <div class="vironeer-counter-card-icon">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <div class="vironeer-counter-card-meta">
                <p class="vironeer-counter-card-title">{{ translate('Total Spend') }}</p>
                <p class="vironeer-counter-card-number">{{ getAmount($counters['total_transactions_amount']) }}</p>
            </div>
        </div>
    </div>
</div>
@if ($user->isAuthor())
    <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-4 g-3 mb-4">
        <div class="col">
            <div class="vironeer-counter-card bg-c-6">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Author Level') }}</p>
                    <p class="vironeer-counter-card-number">{{ $user->level->name }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="vironeer-counter-card bg-c-7">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Total Reviews') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($user->total_reviews) }}
                        ({{ $user->avg_reviews }})</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="vironeer-counter-card bg-c-8">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Total Items') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($user->items_count) }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="vironeer-counter-card bg-c-9">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-cart-arrow-down"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Total Sales') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($user->total_sales) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-3 g-3 mb-4">
        <div class="col">
            <div class="vironeer-counter-card bg-c-10">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Total Sales Amount') }}</p>
                    <p class="vironeer-counter-card-number">{{ getAmount($user->total_sales_amount) }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="vironeer-counter-card bg-c-11">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Total Referral Earnings') }}</p>
                    <p class="vironeer-counter-card-number">{{ getAmount($user->total_referrals_earnings) }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="vironeer-counter-card bg-c-12">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Total Withdrawal Amount') }}</p>
                    <p class="vironeer-counter-card-number">{{ getAmount($counters['total_withdrawal_amount']) }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="card mb-4">
    <div class="card-header">{{ translate('Quick Actions') }}</div>
    <div class="card-body p-4">
        <div class="row row-cols-1 row-cols-lg-4 row-cols-xl-5 g-3">
            @if ($user->isDataCompleted())
                <div class="col">
                    <a class="btn btn-outline-dark btn-lg w-100" href="{{ $user->getProfileLink() }}" target="_blank">
                        <i class="fa-solid fa-up-right-from-square me-1"></i>
                        {{ translate('View profile') }}
                    </a>
                </div>
            @endif
            <div class="col">
                <a class="btn btn-outline-dark btn-lg w-100" href="{{ route('admin.members.users.login', $user->id) }}"
                    target="_blank">
                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i>
                    {{ translate('Login as User') }}
                </a>
            </div>
            @if (licenseType(2) && @$settings->premium->status && $user->isSubscribed())
                <div class="col">
                    <a class="btn btn-warning btn-lg w-100"
                        href="{{ route('admin.premium.subscriptions.show', $user->subscription->id) }}"
                        target="_blank">
                        <i class="fa-solid fa-crown me-1"></i>
                        {{ translate('View Subscription') }}
                    </a>
                </div>
            @endif
            @if ($user->isAuthor())
                <div class="col">
                    @if (!$user->isFeaturedAuthor())
                        <form action="{{ route('admin.members.users.featured', $user->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-success action-confirm btn-lg w-100">
                                <i class="fa-solid fa-certificate me-1"></i>
                                {{ translate('Make Featured') }}
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.members.users.featured.remove', $user->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-danger action-confirm btn-lg w-100">
                                <i class="fa-solid fa-certificate me-1"></i>
                                {{ translate('Remove Featured') }}
                            </button>
                        </form>
                    @endif
                </div>
            @endif
            <div class="col">
                <a class="btn btn-outline-dark btn-lg w-100"
                    href="{{ route('admin.kyc-verifications.index', ['user' => $user->id]) }}" target="_blank">
                    <i class="fa-solid fa-address-card me-1"></i>
                    {{ translate('KYC Verifications') }}
                </a>
            </div>
            @if (@$settings->actions->tickets)
                <div class="col">
                    <a class="btn btn-outline-dark btn-lg w-100"
                        href="{{ route('admin.tickets.index', ['user' => $user->id]) }}" target="_blank">
                        <i class="fa-solid fa-inbox me-1"></i>
                        {{ translate('Tickets') }}
                    </a>
                </div>
                <div class="col">
                    <a class="btn btn-outline-dark btn-lg w-100"
                        href="{{ route('admin.tickets.create', ['user' => $user->id]) }}" target="_blank">
                        <i class="fa-solid fa-plus me-1"></i>
                        {{ translate('Open a Ticket') }}
                    </a>
                </div>
            @endif
            <div class="col">
                <a class="btn btn-outline-dark btn-lg w-100"
                    href="{{ route('admin.records.purchases.index', ['user' => $user->id]) }}" target="_blank">
                    <i class="fa-solid fa-basket-shopping me-1"></i>
                    {{ translate('Purchases') }}
                </a>
            </div>
            <div class="col">
                <a class="btn btn-outline-dark btn-lg w-100"
                    href="{{ route('admin.transactions.index', ['user' => $user->id]) }}" target="_blank">
                    <i class="fa-solid fa-receipt me-1"></i>
                    {{ translate('Transactions') }}
                </a>
            </div>
            @if (@$settings->actions->refunds)
                <div class="col">
                    <a class="btn btn-outline-dark btn-lg w-100"
                        href="{{ route('admin.records.refunds.index', ['user' => $user->id]) }}" target="_blank">
                        <i class="fa-solid fa-share me-1"></i>
                        {{ translate('Requested Refunds') }}
                    </a>
                </div>
            @endif
            <div class="col">
                <a class="btn btn-outline-dark btn-lg w-100"
                    href="{{ route('admin.records.statements.index', ['user' => $user->id]) }}" target="_blank">
                    <i class="fa-solid fa-file-lines me-1"></i>
                    {{ translate('Statements') }}
                </a>
            </div>
            @if ($user->isAuthor())
                <div class="col">
                    <a class="btn btn-outline-dark btn-lg w-100"
                        href="{{ route('admin.items.index', ['author' => $user->id]) }}" target="_blank">
                        <i class="fa-solid fa-box-open me-1"></i>
                        {{ translate('Items') }}
                    </a>
                </div>
                <div class="col">
                    <a class="btn btn-outline-dark btn-lg w-100"
                        href="{{ route('admin.records.sales.index', ['author' => $user->id]) }}" target="_blank">
                        <i class="fa-solid fa-cart-shopping me-1"></i>
                        {{ translate('Sales') }}
                    </a>
                </div>
                <div class="col">
                    <a class="btn btn-outline-dark btn-lg w-100"
                        href="{{ route('admin.records.support-earnings.index', ['author' => $user->id]) }}"
                        target="_blank">
                        <i class="fa-solid fa-circle-dollar-to-slot me-1"></i>
                        {{ translate('Support Earnings') }}
                    </a>
                </div>
                <div class="col">
                    <a class="btn btn-outline-dark btn-lg w-100"
                        href="{{ route('admin.records.referral-earnings.index', ['author' => $user->id]) }}"
                        target="_blank">
                        <i class="fa-solid fa-money-bill-trend-up me-1"></i>
                        {{ translate('Referral Earnings') }}
                    </a>
                </div>
                @if (@$settings->actions->refunds)
                    <div class="col">
                        <a class="btn btn-outline-dark btn-lg w-100"
                            href="{{ route('admin.records.refunds.index', ['author' => $user->id]) }}"
                            target="_blank">
                            <i class="fa-solid fa-share me-1"></i>
                            {{ translate('Received Refunds') }}
                        </a>
                    </div>
                @endif
                <div class="col">
                    <a class="btn btn-outline-dark btn-lg w-100"
                        href="{{ route('admin.withdrawals.index', ['user' => $user->id]) }}" target="_blank">
                        <i class="fa-solid fa-paper-plane me-1"></i>
                        {{ translate('Withdrawals') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
