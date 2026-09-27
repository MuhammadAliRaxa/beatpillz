<nav class="dashboard-nav">
    <div class="dashboard-nav-btn dashboard-btn dashboard-toggle-btn">
        <i class="fa fa-bars"></i>
    </div>
    <a href="{{ route('home') }}" class="logo logo-sm logo-toggle ms-3">
        <img src="{{ asset($themeSettings->general->logo_dark) }}" alt="{{ @$settings->general->site_name }}" />
    </a>
    <div class="d-flex align-items-center ms-auto">
        @include('themes.basic.partials.currencies-menu')
        @if ($settings->actions->become_an_author && !authUser()->isAuthor())
            <a href="{{ route('workspace.become-an-author') }}" class="link-btn d-none d-lg-inline ms-3">
                <button class="btn btn-outline-primary">
                    {{ translate('Become an Author') }}
                </button>
            </a>
        @endif
        @include('themes.basic.partials.user-menu', ['menu_class' => 'ms-3'])
    </div>
</nav>
