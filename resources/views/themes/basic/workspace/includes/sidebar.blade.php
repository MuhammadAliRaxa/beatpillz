<aside class="dashboard-sidebar">
    <div class="overlay"></div>
    <div class="dashboard-sidebar-container">
        <div class="dashboard-sidebar-header">
            <a href="{{ route('home') }}" class="logo logo-sm">
                <img src="{{ asset($themeSettings->general->logo_light) }}" alt="{{ @$settings->general->site_name }}" />
            </a>
        </div>
        <div class="dashboard-sidebar-body" data-simplebar>
            <div class="dashboard-sidebar-content">
                <div class="dashboard-sidebar-inner">
                    <div class="dashboard-balance">
                        <div class="dashboard-balance-info">
                            <h6 class="dashboard-balance-title">{{ translate('Balance') }}</h6>
                            <p class="dashboard-balance-number">
                                {{ getAmount(authUser()->balance) }}</p>
                        </div>
                        <div class="dashboard-balance-icon">
                            <a href="{{ route('workspace.balance.index') }}">
                                <i class="fa fa-wallet"></i>
                            </a>
                        </div>
                    </div>
                    <div class="dashboard-sidebar-links">
                        @if (authUser()->isAuthor())
                            <div
                                class="dashboard-sidebar-link {{ request()->routeIs('workspace.dashboard') ? 'current' : '' }}">
                                <a href="{{ route('workspace.dashboard') }}" class="dashboard-sidebar-link-title">
                                    <i class="fa-solid fa-table-columns"></i>
                                    <span>{{ translate('Dashboard') }}</span>
                                </a>
                            </div>
                            <div
                                class="dashboard-sidebar-link {{ request()->routeIs('workspace.items.*') ? 'current' : '' }}">
                                <a href="{{ route('workspace.items.index') }}" class="dashboard-sidebar-link-title">
                                    <i class="fa-solid fa-box-open"></i>
                                    <span>{{ translate('My Items') }}</span>
                                </a>
                            </div>
                        @endif
                        <div
                            class="dashboard-sidebar-link {{ request()->routeIs('workspace.purchases.*') ? 'current' : '' }}">
                            <a href="{{ route('workspace.purchases.index') }}" class="dashboard-sidebar-link-title">
                                <i class="fa-solid fa-basket-shopping"></i>
                                <span>{{ translate('Purchases') }}</span>
                            </a>
                        </div>
                        <div
                            class="dashboard-sidebar-link {{ request()->routeIs('workspace.transactions.*') ? 'current' : '' }}">
                            <a href="{{ route('workspace.transactions.index') }}" class="dashboard-sidebar-link-title">
                                <i class="fa-solid fa-receipt"></i>
                                <span>{{ translate('Transactions') }}</span>
                            </a>
                        </div>
                        @if (authUser()->isAuthor())
                            @if (@$settings->referral->status)
                                <div
                                    class="dashboard-sidebar-link {{ request()->routeIs('workspace.referrals') ? 'current' : '' }}">
                                    <a href="{{ route('workspace.referrals') }}" class="dashboard-sidebar-link-title">
                                        <i class="fa-solid fa-user-group"></i>
                                        <span>{{ translate('Referrals') }}</span>
                                    </a>
                                </div>
                            @endif
                            <div
                                class="dashboard-sidebar-link {{ request()->routeIs('workspace.withdrawals.index') ? 'current' : '' }}">
                                <a href="{{ route('workspace.withdrawals.index') }}"
                                    class="dashboard-sidebar-link-title">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span>{{ translate('Withdrawals') }}</span>
                                </a>
                            </div>
                        @endif
                        @if (@$settings->actions->refunds)
                            <div
                                class="dashboard-sidebar-link {{ request()->routeIs('workspace.refunds.*') ? 'current' : '' }}">
                                <a href="{{ route('workspace.refunds.index') }}" class="dashboard-sidebar-link-title">
                                    <i class="fa-solid fa-share"></i>
                                    <span>{{ translate('Refunds') }}</span>
                                    @if ($counters['pending_refunds'])
                                        <span class="counter me-0">{{ $counters['pending_refunds'] }}</span>
                                    @endif
                                </a>
                            </div>
                        @endif
                        @if (@$settings->actions->tickets)
                            <div
                                class="dashboard-sidebar-link {{ request()->routeIs('workspace.tickets.*') ? 'current' : '' }}">
                                <a href="{{ route('workspace.tickets.index') }}" class="dashboard-sidebar-link-title">
                                    <i class="fa-solid fa-inbox"></i>
                                    <span>{{ translate('Tickets') }}</span>
                                </a>
                            </div>
                        @endif
                        @if (authUser()->isAuthor() && isAddonActive('license_verification_tool'))
                            <div class="dashboard-sidebar-link {{ request()->segment(2) == 'tools' ? 'active animated ' : '' }} dashboard-toggle"
                                data-toggle>
                                <div class="dashboard-sidebar-link-title toggle-title">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                    <span>{{ translate('Tools') }}</span>
                                </div>
                                <div class="dashboard-sidebar-link-menu">
                                    @if (isAddonActive('license_verification_tool'))
                                        <div
                                            class="dashboard-sidebar-link {{ request()->routeIs('workspace.tools.license-verification.*') ? 'current' : '' }}">
                                            <a href="{{ route('workspace.tools.license-verification.index') }}"
                                                class="dashboard-sidebar-link-title">
                                                <span>{{ translate('License Verification') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div
                            class="dashboard-sidebar-link {{ request()->routeIs('workspace.settings.*') ? 'current' : '' }}">
                            <a href="{{ route('workspace.settings.index') }}" class="dashboard-sidebar-link-title">
                                <i class="fa fa-cog"></i>
                                <span>{{ translate('Settings') }}</span>
                            </a>
                        </div>
                        <div class="dashboard-sidebar-link">
                            <a href="#" class="dashboard-sidebar-link-title"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i>
                                <span> {{ translate('Logout') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
