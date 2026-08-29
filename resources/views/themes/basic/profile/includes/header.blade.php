<header class="header header-profile"
    {{ $user->isAuthor() ? 'style=background-image:url(' . asset($themeSettings->profile->profile_author_header_background) . ') !important' : '' }}>
    <div class="container @yield('container')">
        <div class="header-inner">
            <div class="row row-cols-1 row-cols-md-auto justify-content-between align-items-center g-4">
                <div class="col">
                    <div class="row row-cols-1 row-cols-md-auto text-center text-md-start align-items-center g-2">
                        <div class="col">
                            <div class="user-avatar user-avatar-xl me-1">
                                <img src="{{ $user->getAvatar() }}" alt="{{ $user->username }}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-block text-dark fs-5 mb-1">
                                <h1 class="mb-0 h6 small d-inline">
                                    {{ $user->username }}
                                </h1>
                                @if ($user->isBanned())
                                    <span class="badge bg-danger fw-light ms-2">
                                        <i class="fa-solid fa-ban me-1"></i>
                                        {{ translate('Banned') }}
                                    </span>
                                @endif
                            </div>
                            <p class="mb-0 fs-6 mb-2">
                                <span class="text-muted small">
                                    {{ translate('Member since :date', ['date' => dateFormat($user->created_at, 'M Y')]) }}
                                </span>
                            </p>
                            <div class="row row-cols-auto justify-content-center justify-content-md-start g-2">
                                @if ($user->isAuthor())
                                    <div class="col">
                                        <a href="{{ $user->getPortfolioLink() }}" class="btn btn-primary">
                                            <span>{{ translate('View Portfolio') }}</span>
                                        </a>
                                    </div>
                                @endif
                                <div class="col">
                                    <livewire:follow-button :user="$user" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($user->isAuthor())
                    <div class="col d-flex flex-column align-items-center">
                        <div class="user-stats justify-content-center align-items-center">
                            <div class="user-stat">
                                <p class="user-stat-title">{{ translate('Sales') }}</p>
                                <h5 class="user-stat-number fs-5 mb-0">{{ number_format($user->total_sales) }}</h5>
                            </div>
                        </div>
                        @if (@$settings->item->reviews_status && $user->avg_reviews > 0 && $user->total_reviews > 0)
                            <div class="d-flex flex-column align-items-center gap-2 mt-3">
                                @include('themes.basic.partials.rating-stars', [
                                    'stars' => $user->avg_reviews,
                                ])
                                <span class="text-muted small">
                                    {{ translate($user->total_reviews > 1 ? '(:count Reviews)' : '(:count Review)', [
                                        'count' => $user->total_reviews,
                                    ]) }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <div class="dropdown profile-dropdown mb-3 d-block d-lg-none">
            <button class="btn btn-md w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                @yield('title')
            </button>
            <ul class="dropdown-menu w-100">
                <li>
                    <a class="dropdown-item {{ request()->routeIs('profile.index') ? 'active' : '' }}"
                        href="{{ $user->getProfileLink() }}">
                        {{ translate('Profile') }}
                    </a>
                </li>
                @if ($user->isAuthor())
                    <li>
                        <a class="dropdown-item {{ request()->routeIs('profile.portfolio') ? 'active' : '' }}"
                            href="{{ $user->getPortfolioLink() }}">
                            {{ translate('Portfolio') }}
                        </a>
                    </li>
                @endif
                <li>
                    <a class="dropdown-item {{ request()->routeIs('profile.followers') ? 'active' : '' }}"
                        href="{{ route('profile.followers', strtolower($user->username)) }}">
                        {{ translate('Followers (:count)', ['count' => numberFormat($user->total_followers)]) }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->routeIs('profile.following') ? 'active' : '' }}"
                        href="{{ route('profile.following', strtolower($user->username)) }}">
                        {{ translate('Following (:count)', ['count' => numberFormat($user->total_following)]) }}
                    </a>
                </li>
                @if ($user->isAuthor() && @$settings->item->reviews_status)
                    <li>
                        <a class="dropdown-item {{ request()->routeIs('profile.reviews') ? 'active' : '' }}"
                            href="{{ route('profile.reviews', strtolower($user->username)) }}">
                            {{ translate('Reviews (:count)', ['count' => numberFormat($user->total_reviews)]) }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <div class="tabs-custom v2 position-relative d-none d-lg-block">
            <nav class="nav nav-tabs">
                <a href="{{ $user->getProfileLink() }}"
                    class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                    {{ translate('Profile') }}
                </a>
                @if ($user->isAuthor())
                    <a href="{{ $user->getPortfolioLink() }}"
                        class="nav-link {{ request()->routeIs('profile.portfolio') ? 'active' : '' }}">
                        {{ translate('Portfolio') }}
                    </a>
                @endif
                <a href="{{ route('profile.followers', strtolower($user->username)) }}"
                    class="nav-link  {{ request()->routeIs('profile.followers') ? 'active' : '' }}">
                    {{ translate('Followers (:count)', ['count' => numberFormat($user->total_followers)]) }}
                </a>
                <a href="{{ route('profile.following', strtolower($user->username)) }}"
                    class="nav-link  {{ request()->routeIs('profile.following') ? 'active' : '' }}">
                    {{ translate('Following (:count)', ['count' => numberFormat($user->total_following)]) }}
                </a>
                @if ($user->isAuthor() && @$settings->item->reviews_status)
                    <a href="{{ route('profile.reviews', strtolower($user->username)) }}"
                        class="nav-link {{ request()->routeIs('profile.reviews') ? 'active' : '' }}">
                        {{ translate('Reviews (:count)', ['count' => numberFormat($user->total_reviews)]) }}
                    </a>
                @endif
            </nav>
        </div>
    </div>
</header>
