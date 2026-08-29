<!DOCTYPE html>
<html lang="{{ getLocale() }}">

<head>
    @include('themes.basic.includes.head')
</head>

<body class="bg-white">
    @include('themes.basic.includes.navbar')
    @include('themes.basic.profile.includes.header')
    <section class="section section-profile">
        <div class="container @yield('container')">
            <div class="section-body">
                <div class="row g-3">
                    <div
                        class="col-12 col-xl-{{ $__env->yieldContent('content_size') ? $__env->yieldContent('content_size') : '8' }}">
                        @yield('content')
                    </div>
                    <div
                        class="col-12 col-xl-{{ $__env->yieldContent('content_size') ? 12 - $__env->yieldContent('content_size') : '4' }}">
                        @include('themes.basic.profile.includes.sidebar')
                    </div>
                </div>
                @if (request()->routeIs('profile.index') && $user->total_followers > 0 && $followers->count() > 0)
                    <div class="card-v card-bg border p-4 mt-4">
                        <div class="row g-3 mb-4">
                            <div class="col">
                                <h3 class="mb-0">
                                    {{ translate('Followers (:count)', ['count' => numberFormat($user->total_followers)]) }}
                                </h3>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('profile.followers', strtolower($user->username)) }}"
                                    class="btn btn-outline-secondary">{{ translate('View All Followers') }}</a>
                            </div>
                        </div>
                        <div class="row row-cols-auto g-1">
                            @foreach ($followers as $follower)
                                @php
                                    $follower = $follower->follower;
                                @endphp
                                <div class="col">
                                    <a href="{{ $follower->getProfileLink() }}" class="user-avatar user-avatar-xl me-1">
                                        <img src="{{ $follower->getAvatar() }}" alt="{{ $follower->username }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @include('themes.basic.includes.footer')
    @include('themes.basic.includes.config')
    @include('themes.basic.includes.scripts')
</body>

</html>
