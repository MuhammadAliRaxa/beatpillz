<!DOCTYPE html>
<html lang="{{ getLocale() }}" dir="{{ getDirection() }}">

<head>
    @include('themes.basic.includes.head')
</head>

<body class="bg-white">
    @include('themes.basic.includes.navbar')
    @hasSection('header')
        <header class="header header-bg">
            <div class="container container-custom">
                <div class="header-inner">
                    <div class="header-container-full">
                        <div class="row row-cols-1 row-cols-sm-auto justify-content-between align-items-center g-3">
                            <div class="col">
                                @yield('breadcrumbs')
                                <h1 class="header-title h2 mb-0">@yield('header_title')</h1>
                            </div>
                            <div class="col-md-5 col-lg-4 col-xxl-3">
                                <form action="{{ route('blog.index') }}" method="GET">
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
        <div class="container container-custom">
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
