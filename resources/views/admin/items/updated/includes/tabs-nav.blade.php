<div class="dashboard-tabs-nav">
    <a href="{{ route('admin.items.updated.show', $itemUpdate->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('admin.items.updated.show') ? 'current' : '' }}">
        <i class="fa-regular fa-circle-question"></i>
        <span class="ms-1">{{ translate('Update Details') }}</span>
    </a>
    <a href="{{ route('admin.items.updated.history', $itemUpdate->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('admin.items.updated.history') ? 'current' : '' }}">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <span class="ms-1">{{ translate('History') }}</span>
    </a>
    <a href="{{ route('admin.items.updated.action', $itemUpdate->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('admin.items.updated.action') ? 'current' : '' }}">
        <i class="fa-solid fa-sliders"></i>
        <span class="ms-1">{{ translate('Take Action') }}</span>
    </a>
</div>
