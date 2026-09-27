<div class="dashboard-tabs-nav">
    <a href="{{ route('admin.items.show', $item->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('admin.items.show') ? 'current' : '' }}">
        <i class="fa-regular fa-circle-question"></i>
        <span class="ms-1">{{ translate('Item Details') }}</span>
    </a>
    <a href="{{ route('admin.items.history', $item->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('admin.items.history') ? 'current' : '' }}">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <span class="ms-1">{{ translate('History') }}</span>
    </a>
    @if (@$settings->item->discount_status)
        <a href="{{ route('admin.items.discount', $item->id) }}"
            class="dashboard-tabs-nav-item {{ request()->routeIs('admin.items.discount') ? 'current' : '' }}">
            <i class="fa-solid fa-tags"></i>
            <span class="ms-1">{{ translate('Discount') }}</span>
        </a>
    @endif
    @if ($item->isPending() || $item->isResubmitted())
        <a href="{{ route('admin.items.action', $item->id) }}"
            class="dashboard-tabs-nav-item {{ request()->routeIs('admin.items.action') ? 'current' : '' }}">
            <i class="fa-solid fa-sliders"></i>
            <span class="ms-1">{{ translate('Take Action') }}</span>
        </a>
    @endif
    <a href="{{ route('admin.items.status', $item->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('admin.items.status') ? 'current' : '' }}">
        <i class="fa-solid fa-rotate"></i>
        <span class="ms-1">{{ translate('Change Status') }}</span>
    </a>
    <a href="{{ route('admin.items.reviews', $item->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('admin.items.reviews') ? 'current' : '' }}">
        <i class="fa-solid fa-star"></i>
        <span class="ms-1">{{ translate('Reviews') }} ({{ numberFormat($item->total_reviews) }})</span>
    </a>
    <a href="{{ route('admin.items.comments', $item->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('admin.items.comments') ? 'current' : '' }}">
        <i class="fa-solid fa-comments"></i>
        <span class="ms-1">{{ translate('Comments') }} ({{ numberFormat($item->total_comments) }})</span>
    </a>
    <a href="{{ route('admin.items.statistics', $item->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('admin.items.statistics') ? 'current' : '' }}">
        <i class="fa-solid fa-chart-simple"></i>
        <span class="ms-1">{{ translate('Statistics') }}</span>
    </a>
</div>
