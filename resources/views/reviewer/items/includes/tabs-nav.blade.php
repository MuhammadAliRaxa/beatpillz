<div class="dashboard-tabs-nav">
    <a href="{{ route('reviewer.items.review', $item->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('reviewer.items.review') ? 'current' : '' }}">
        <i class="fa-regular fa-circle-question"></i>
        <span class="ms-1">{{ translate('Item Details') }}</span>
    </a>
    <a href="{{ route('reviewer.items.history', $item->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('reviewer.items.history') ? 'current' : '' }}">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <span class="ms-1">{{ translate('History') }}</span>
    </a>
    @if ($item->isPending() || $item->isResubmitted())
        <a href="{{ route('reviewer.items.action', $item->id) }}"
            class="dashboard-tabs-nav-item {{ request()->routeIs('reviewer.items.action') ? 'current' : '' }}">
            <i class="fa-solid fa-sliders"></i>
            <span class="ms-1">{{ translate('Take Action') }}</span>
        </a>
    @endif
</div>
