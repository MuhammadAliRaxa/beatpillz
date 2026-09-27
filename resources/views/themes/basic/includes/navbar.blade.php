@if (@$settings->announcement->status && !request()->hasCookie('announce_close'))
    <div class="announcement" style="background-color: {{ @$settings->announcement->background_color }};">
        <div class="container container-custom d-flex align-items-center position-relative">
            <div class="announcement-text">
                <span>{{ @$settings->announcement->body }}</span>
                @if (@$settings->announcement->button_title && @$settings->announcement->button_link)
                    <a href="{{ @$settings->announcement->button_link }}" class="btn btn-sm ms-2"
                        style="background-color: {{ @$settings->announcement->button_background_color }}; color:{{ @$settings->announcement->button_text_color }};">{{ @$settings->announcement->button_title }}</a>
                @endif
            </div>
            <button class="announcement-close">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
@endif
<div class="nav-bar">
    <div class="container container-custom">
        <div class="nav-bar-container">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset($themeSettings->general->logo_light) }}"
                    alt="{{ @$settings->general->site_name }}" />
            </a>
            <div class="nav-bar-menu ms-auto">
                <div class="overlay"></div>
                <div class="nav-bar-menu-inner">
                    <div class="nav-bar-menu-header">
                        <butaton class="nav-bar-menu-close ms-auto">
                            <i class="fa fa-times"></i>
                        </butaton>
                    </div>
                    <div class="nav-bar-links">
                        @foreach ($topNavLinks as $topNavLink)
                            @if ($topNavLink->children->count() > 0)
                                <div class="drop-down" data-dropdown data-dropdown-position="top">
                                    <div class="drop-down-btn">
                                        <span class="me-2">{{ $topNavLink->name }}</span>
                                        <i class="fa fa-angle-down ms-auto"></i>
                                    </div>
                                    <div class="drop-down-menu drop-down-menu-md drop-down-menu-end">
                                        @foreach ($topNavLink->children as $child)
                                            <a href="{{ $child->link }}"
                                                {{ $child->isExternal() ? 'target=_blank' : '' }}
                                                class="drop-down-item">
                                                <span>{{ $child->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $topNavLink->link }}"
                                    {{ $topNavLink->isExternal() ? 'target=_blank' : '' }} class="link">
                                    <div class="link-title">
                                        <span>{{ $topNavLink->name }}</span>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                        @include('themes.basic.partials.currencies-menu', [
                            'group_classes' => 'drop-down-menu-end',
                        ])
                        @if (licenseType(2) && @$settings->premium->status)
                            <a href="{{ route('premium.index') }}" class="link-btn d-block d-xl-none">
                                <button class="btn btn-outline-premium">
                                    <i class="fa-solid fa-crown me-1"></i>
                                    {{ translate('Premium') }}
                                </button>
                            </a>
                        @endif
                        @guest
                            <a href="{{ route('login') }}" class="link-btn d-block d-xl-none">
                                <button class="btn btn-outline-primary">{{ translate('Sign In') }}</button>
                            </a>
                            @if (@$settings->actions->registration)
                                <a href="{{ route('register') }}" class="link-btn d-block d-xl-none">
                                    <button class="btn btn-primary">{{ translate('Sign Up') }}</button>
                                </a>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
            <div class="nav-bar-buttons">
                @if (licenseType(2) && @$settings->premium->status)
                    <a href="{{ route('premium.index') }}" class="link-btn">
                        <button class="btn btn-outline-premium">
                            <i class="fa-solid fa-crown me-1"></i>
                            {{ translate('Premium') }}
                        </button>
                    </a>
                @endif
                <a href="{{ route('cart.index') }}" class="link-btn cart-btn d-none d-xl-block">
                    <button class="btn btn-outline-light btn-padding">
                        <i class="fa fa-cart-shopping"></i>
                    </button>
                    @if ($cartItemsCount)
                        <div class="cart-counter">{{ $cartItemsCount > 99 ? '+99' : $cartItemsCount }}</div>
                    @endif
                </a>
                @guest
                    <a href="{{ route('login') }}" class="link-btn">
                        <button class="btn btn-outline-primary">{{ translate('Sign In') }}</button>
                    </a>
                    @if (@$settings->actions->registration)
                        <a href="{{ route('register') }}" class="link-btn">
                            <button class="btn btn-primary">{{ translate('Sign Up') }}</button>
                        </a>
                    @endif
                @endguest
            </div>
            <div class="nav-bar-actions">
                <a href="{{ route('cart.index') }}" class="cart-btn d-block d-xl-none ms-3">
                    <button class="btn btn-outline-light btn-padding">
                        <i class="fa fa-cart-shopping"></i>
                    </button>
                    @if ($cartItemsCount)
                        <div class="cart-counter">{{ $cartItemsCount > 99 ? '+99' : $cartItemsCount }}</div>
                    @endif
                </a>
                @auth
                    @include('themes.basic.partials.user-menu', ['menu_class' => 'ms-3 me-0'])
                @endauth
                <div class="nav-bar-menu-btn ms-3">
                    <i class="fa-solid fa-bars fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="nav-bar nav-bar-sm nav-bar-bg">
    <div class="container container-custom">
        <div class="nav-bar-container">
            <div class="nav-bar-menu-btn me-3">
                <i class="fa-solid fa-bars fa-lg"></i>
            </div>
            <div class="nav-bar-menu">
                <div class="overlay"></div>
                <div class="nav-bar-menu-inner">
                    <div class="nav-bar-menu-header">
                        <button class="nav-bar-menu-close ms-auto">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="nav-bar-links">
                        @foreach ($bottomNavLinks as $bottomNavLink)
                            @if ($bottomNavLink->children->count() > 0)
                                <div class="drop-down" data-dropdown data-dropdown-position="top">
                                    <div class="drop-down-btn">
                                        <span class="me-2">{{ $bottomNavLink->name }}</span>
                                        <i class="fa fa-angle-down ms-auto"></i>
                                    </div>
                                    <div class="drop-down-menu drop-down-menu-md drop-down-menu-end">
                                        @foreach ($bottomNavLink->children as $child)
                                            <a href="{{ $child->link }}"
                                                {{ $child->isExternal() ? 'target=_blank' : '' }}
                                                class="drop-down-item">
                                                <span>{{ $child->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $bottomNavLink->link }}"
                                    {{ $bottomNavLink->isExternal() ? 'target=_blank' : '' }} class="link">
                                    <div class="link-title">
                                        <span>{{ $bottomNavLink->name }}</span>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="ms-auto">
                <form action="{{ route('items.index') }}" method="GET">
                    <div class="form-search form-search-reverse">
                        <button class="icon">
                            <i class="fa fa-search"></i>
                        </button>
                        <input type="text" name="search" placeholder="{{ translate('Search...') }}"
                            class="form-control form-control-md" value="{{ request()->input('search') }}" required>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
