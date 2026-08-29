<div class="dashboard-tabs-nav">
    <a href="{{ route('reviewer.items.updated.review', $itemUpdate->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('reviewer.items.updated.review') ? 'current' : '' }}">
        <i class="fa-regular fa-circle-question"></i>
        <span class="ms-1">{{ translate('Update Details') }}</span>
    </a>
    <a href="{{ route('reviewer.items.updated.history', $itemUpdate->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('reviewer.items.updated.history') ? 'current' : '' }}">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <span class="ms-1">{{ translate('History') }}</span>
    </a>
    <a href="{{ route('reviewer.items.updated.action', $itemUpdate->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('reviewer.items.updated.action') ? 'current' : '' }}">
        <i class="fa-solid fa-sliders"></i>
        <span class="ms-1">{{ translate('Take Action') }}</span>
    </a>
</div>
