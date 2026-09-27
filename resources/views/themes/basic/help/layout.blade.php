<!DOCTYPE html>
<html lang="{{ getLocale() }}" dir="{{ getDirection() }}">

<head>
    @include('themes.basic.includes.head')
</head>

<body class="bg-white">
    @include('themes.basic.includes.navbar')
    @hasSection('header_v1')
        <header class="header header-bg">
            <div class="container @yield('container')">
                <div class="header-inner">
                    <div class="header-container px-lg-5">
                        <h1 class="header-title mb-0 mb-4">{{ translate('Hi, how can we help?') }}</h1>
                        <div class="search w-100 mb-4">
                            @include('themes.basic.partials.search-form', [
                                'url' => route('help.index'),
                                'placeholder' => translate('Ask Us a Question...'),
                            ])
                        </div>
                        <div class="row row-cols-auto align-items-center justify-content-center g-2">
                            <div class="col">
                                <span class="text-muted">{{ translate('Common topics') }}:</span>
                            </div>
                            @foreach ($commonCategories as $commonCategory)
                                <div class="col">
                                    <a href="{{ $commonCategory->getLink() }}"
                                        class="btn btn-outline-primary btn-sm">{{ $commonCategory->name }}</a>
                                </div>
                            @endforeach
                        </div>
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
                                <h2 class="header-title mb-0">@yield('header_title')</h2>
                            </div>
                            <div class="col-md-5 col-lg-4 col-xxl-3">
                                <form action="{{ route('help.index') }}" method="GET">
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
    <section class="section">
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
