<!DOCTYPE html>
<html lang="{{ getLocale() }}" dir="{{ getDirection() }}">

<head>
    @include('themes.basic.includes.head')
    <x-ad alias="head_code" />
</head>

<body class="bg-white">
    @include('themes.basic.includes.navbar')
    @hasSection('header_v1')
        <header class="header header-sm">
            <div class="container @yield('container')">
                <div class="header-inner">
                    <div class="header-container">
                        @yield('breadcrumbs')
                        <h1 class="header-title h2 mb-0">@yield('header_title')</h1>
                    </div>
                </div>
            </div>
        </header>
    @endif
    @hasSection('header_v2')
        <header class="header header-bg">
            <div class="container @yield('container')">
                <div class="header-inner">
                    <div class="header-container-full">
                        <div class="row row-cols-1 row-cols-sm-auto justify-content-between align-items-center g-3">
                            <div class="col">
                                @yield('breadcrumbs')
                                <h1 class="header-title h2 mb-0">@yield('header_title')</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    @endif
    @hasSection('header_v3')
        <header class="header header-bg">
            <div class="container @yield('container')">
                <div class="header-inner">
                    <div class="header-container">
                        <div class="d-flex justify-content-center">
                            @yield('breadcrumbs')
                        </div>
                        <h1 class="header-title h2 mb-0">@yield('header_title')</h1>
                    </div>
                </div>
            </div>
        </header>
    @endif
    @hasSection('header_v4')
        <header class="header header-bg">
            <div class="container @yield('container')">
                <div class="header-inner text-start pt-4 pb-5">
                    <h1 class="header-title h2 w-100 mb-2">@yield('header_title')</h1>
                    @yield('breadcrumbs')
                    <div class="header-search mb-3">
                        <div class="search w-100">
                            @include('themes.basic.partials.search-form')
                        </div>
                    </div>
                    @if (request()->query->count() > 0)
                        <a href="{{ request()->url() }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-xmark me-2"></i>
                            {{ translate('Clear All') }}
                        </a>
                    @endif
                </div>
            </div>
        </header>
    @endif
    @hasSection('header_v5')
        <header class="header header-bg">
            <div class="container @yield('container')">
                <div class="header-inner">
                    <div class="header-container-full">
                        <div class="row row-cols-1 row-cols-sm-auto justify-content-between align-items-center g-3">
                            <div class="col">
                                @yield('breadcrumbs')
                                <h1 class="header-title h2 mb-0">@yield('header_title')</h1>
                            </div>
                            <div class="col-md-5 col-lg-4 col-xxl-3">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="form-search form-search-reverse">
                                        <button class="icon">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <input type="text" name="search" placeholder="{{ translate('Search...') }}"
                                            class="form-control form-control-md" value="{{ request('search') ?? '' }}">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    @endif
    <section class="section {{ $__env->yieldContent('header_v1') ? 'pt-0' : '' }}">
        <div class="container @yield('container')">
            <div class="section-body">
                @yield('content')
            </div>
        </div>
    </section>
    @include('themes.basic.includes.footer')
    @include('themes.basic.includes.config')
    @include('themes.basic.includes.scripts')
</body>

</html>
