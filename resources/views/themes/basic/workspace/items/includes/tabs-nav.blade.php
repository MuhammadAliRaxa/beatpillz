<div class="dashboard-tabs-nav">
    <a href="{{ route('workspace.items.edit', $item->id) }}"
        class="dashboard-tabs-nav-item  {{ request()->routeIs('workspace.items.edit') ? 'current' : '' }}">
        <i class="fa-solid fa-pen-to-square"></i>
        <span class="ms-1">{{ translate('Edit details') }}</span>
    </a>
    @if (@$settings->item->changelogs_status)
        <a href="{{ route('workspace.items.changelogs.index', $item->id) }}"
            class="dashboard-tabs-nav-item {{ request()->routeIs('workspace.items.changelogs.index') ? 'current' : '' }}">
            <i class="fa-solid fa-rotate"></i>
            <span class="ms-1">{{ translate('Changelogs') }}</span>
        </a>
    @endif
    <a href="{{ route('workspace.items.history', $item->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('workspace.items.history') ? 'current' : '' }}">
        <i class="fa-solid fa-clock-rotate-left"></i>
        <span class="ms-1">{{ translate('History') }}</span>
    </a>
    @if (@$settings->item->discount_status)
        <a href="{{ route('workspace.items.discount', $item->id) }}"
            class="dashboard-tabs-nav-item {{ request()->routeIs('workspace.items.discount') ? 'current' : '' }}">
            <i class="fa-solid fa-tags"></i>
            <span class="ms-1">{{ translate('Discount') }}</span>
        </a>
    @endif
    <a href="{{ route('workspace.items.statistics', $item->id) }}"
        class="dashboard-tabs-nav-item {{ request()->routeIs('workspace.items.statistics') ? 'current' : '' }}">
        <i class="fa-solid fa-chart-simple"></i>
        <span class="ms-1">{{ translate('Statistics') }}</span>
    </a>
</div>
