<aside class="dashboard-sidebar">
    <div class="overlay"></div>
    <div class="dashboard-sidebar-container">
        <div class="dashboard-sidebar-header">
            <a href="{{ route('reviewer.dashboard') }}" class="logo logo-sm">
                <img src="{{ asset($themeSettings->general->logo_light) }}" alt="{{ @$settings->general->site_name }}">
            </a>
        </div>
        <div class="dashboard-sidebar-body" data-simplebar>
            <div class="dashboard-sidebar-content">
                <div class="dashboard-sidebar-inner">
                    <div class="dashboard-sidebar-links">
                        <div
                            class="dashboard-sidebar-link {{ request()->routeIs('reviewer.dashboard') ? 'current' : '' }}">
                            <a href="{{ route('reviewer.dashboard') }}" class="dashboard-sidebar-link-title">
                                <i class="fa-solid fa-table-columns"></i>
                                <span>{{ translate('Dashboard') }}</span>
                            </a>
                        </div>
                        <div
                            class="dashboard-sidebar-link {{ request()->segment(3) == 'pending' || (isset($item) && $item->isPending()) ? 'current' : '' }}">
                            <a href="{{ route('reviewer.items.status', 'pending') }}"
                                class="dashboard-sidebar-link-title">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ translate('Pending') }}</span>
                                @if ($counters['pending'])
                                    <span class="counter bg-primary">{{ $counters['pending'] }}</span>
                                @endif
                            </a>
                        </div>
                        <div
                            class="dashboard-sidebar-link {{ request()->segment(3) == 'soft-rejected' || (isset($item) && $item->isSoftRejected()) ? 'current' : '' }}">
                            <a href="{{ route('reviewer.items.status', 'soft-rejected') }}"
                                class="dashboard-sidebar-link-title">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                                <span>{{ translate('Soft Rejected') }}</span>
                            </a>
                        </div>
                        <div
                            class="dashboard-sidebar-link {{ request()->segment(3) == 'resubmitted' || (isset($item) && $item->isResubmitted()) ? 'current' : '' }}">
                            <a href="{{ route('reviewer.items.status', 'resubmitted') }}"
                                class="dashboard-sidebar-link-title">
                                <i class="fa-solid fa-arrow-rotate-right"></i>
                                <span>{{ translate('Resubmitted') }}</span>
                                @if ($counters['resubmitted'])
                                    <span class="counter bg-primary">{{ $counters['resubmitted'] }}</span>
                                @endif
                            </a>
                        </div>
                        <div
                            class="dashboard-sidebar-link {{ request()->segment(3) == 'approved' || (isset($item) && $item->isApproved()) ? 'current' : '' }}">
                            <a href="{{ route('reviewer.items.status', 'approved') }}"
                                class="dashboard-sidebar-link-title">
                                <i class="fa-regular fa-circle-check"></i>
                                <span>{{ translate('Approved') }}</span>
                            </a>
                        </div>
                        <div
                            class="dashboard-sidebar-link {{ request()->segment(3) == 'hard-rejected' || (isset($item) && $item->isHardRejected()) ? 'current' : '' }}">
                            <a href="{{ route('reviewer.items.status', 'hard-rejected') }}"
                                class="dashboard-sidebar-link-title">
                                <i class="fa-regular fa-circle-xmark"></i>
                                <span>{{ translate('Hard Rejected') }}</span>
                            </a>
                        </div>
                        <div
                            class="dashboard-sidebar-link {{ request()->routeIs('reviewer.items.updated.*') ? 'current' : '' }}">
                            <a href="{{ route('reviewer.items.updated.index') }}"
                                class="dashboard-sidebar-link-title ">
                                <i class="fa-solid fa-rotate"></i>
                                <span>{{ translate('Updated') }}</span>
                                @if ($counters['updated'])
                                    <span class="counter bg-primary">{{ $counters['updated'] }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
