<div class="drop-down user-menu {{ $menu_class ?? '' }}" data-dropdown data-dropdown-position="top">
    <div class="drop-down-btn">
        <img src="{{ authUser()->getAvatar() }}" alt="{{ authUser()->getName() }}" class="user-img">
        <span class="user-name">{{ authUser()->getName() }}</span>
        <i class="fa fa-angle-down ms-2"></i>
    </div>
    <div class="drop-down-menu">
        @if (authUser()->isDataCompleted())
            <a href="{{ authUser()->getProfileLink() }}" class="drop-down-item">
                <i class="fa-solid fa-user"></i>
                {{ translate('Profile') }}
            </a>
            @if (authUser()->isAuthor())
                <a href="{{ route('workspace.dashboard') }}" class="drop-down-item">
                    <i class="fa-solid fa-table-columns"></i>
                    {{ translate('Dashboard') }}
                </a>
            @endif
            <a href="{{ route('workspace.balance.index') }}" class="drop-down-item">
                <i class="fa-solid fa-wallet"></i>
                {{ translate('My Balance') }}
            </a>
            <a href="{{ route('favorites') }}" class="drop-down-item">
                <i class="fa-solid fa-heart"></i>
                {{ translate('Favorites') }}
            </a>
            <a href="{{ route('workspace.purchases.index') }}" class="drop-down-item">
                <i class="fa-solid fa-basket-shopping"></i>
                {{ translate('Purchases') }}
            </a>
            <a href="{{ route('workspace.transactions.index') }}" class="drop-down-item">
                <i class="fa-solid fa-receipt"></i>
                {{ translate('Transactions') }}
            </a>
            <a href="{{ route('workspace.settings.index') }}" class="drop-down-item">
                <i class="fa fa-cog"></i>
                {{ translate('Settings') }}
            </a>
            <div class="drop-down-divider"></div>
            @if (authUser()->isAuthor())
                <a href="{{ route('workspace.items.index') }}" class="drop-down-item">
                    <i class="fa-solid fa-box-open"></i>
                    {{ translate('My Items') }}
                </a>
                <a href="{{ route('workspace.referrals') }}" class="drop-down-item">
                    <i class="fa-solid fa-user-group"></i>
                    {{ translate('Referrals') }}
                </a>
                <a href="{{ route('workspace.withdrawals.index') }}" class="drop-down-item">
                    <i class="fa-solid fa-paper-plane"></i>
                    {{ translate('Withdrawals') }}
                </a>
                <div class="drop-down-divider"></div>
            @elseif($settings->actions->become_an_author)
                <a href="{{ route('workspace.become-an-author') }}" class="drop-down-item">
                    <i class="fa-solid fa-box-open"></i>
                    {{ translate('Start Selling') }}
                </a>
                <div class="drop-down-divider"></div>
            @endif
        @endif
        <a href="#" class="drop-down-item text-danger"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-power-off"></i>
            {{ translate('Logout') }}
        </a>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
</div>
