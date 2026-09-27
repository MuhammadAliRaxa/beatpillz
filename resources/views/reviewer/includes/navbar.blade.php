<nav class="dashboard-nav">
    <div class="dashboard-nav-btn dashboard-btn dashboard-toggle-btn">
        <i class="fa fa-bars"></i>
    </div>
    <a href="{{ route('reviewer.dashboard') }}" class="logo logo-sm logo-toggle ms-3">
        <img src="{{ asset($themeSettings->general->logo_dark) }}" alt="{{ @$settings->general->site_name }}">
    </a>
    <div class="d-flex align-items-center ms-auto">
        <div class="drop-down user-menu ms-3" data-dropdown data-dropdown-position="top">
            <div class="drop-down-btn">
                <img src="{{ authReviewer()->getAvatar() }}" alt="{{ authReviewer()->getName() }}" class="user-avatar">
                <span class="mx-2 d-none d-md-inline-block">{{ authReviewer()->getName() }}</span>
                <i class="fa fa-chevron-down fa-sm d-none d-md-inline-block"></i>
            </div>
            <div class="drop-down-menu">
                <a href="{{ route('reviewer.settings.index') }}" class="drop-down-item">
                    <i class="fa fa-cog"></i>
                    <span>{{ translate('Settings') }}</span>
                </a>
                <a href="#" class="drop-down-item text-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-power-off"></i>
                    {{ translate('Logout') }}
                </a>
            </div>
        </div>
        <form id="logout-form" class="d-inline" action="{{ route('reviewer.logout') }}" method="POST">
            @csrf
        </form>
    </div>
</nav>
