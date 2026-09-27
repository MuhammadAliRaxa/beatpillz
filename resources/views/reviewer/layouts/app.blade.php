<!DOCTYPE html>
<html lang="{{ getLocale() }}" dir="{{ getDirection() }}">

<head>
    @include('reviewer.includes.head')
    @include('reviewer.includes.styles')
</head>

<body>
    <div class="dashboard">
        @include('reviewer.includes.sidebar')
        <div class="dashboard-body">
            @include('reviewer.includes.navbar')
            <div class="dashboard-container @yield('container') py-4">
                <div class="mb-4">
                    <div class="row row-cols-auto g-2 justify-content-between align-items-center">
                        <div class="col-12 col-lg">
                            @include('reviewer.partials.breadcrumb')
                        </div>
                    </div>
                </div>
                <div>
                    @yield('content')
                </div>
            </div>
            @include('reviewer.includes.footer')
        </div>
    </div>
    @include('reviewer.includes.scripts')
</body>

</html>
