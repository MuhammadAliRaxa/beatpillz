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
            <div class="dashboard-container dashboard-container-md py-5">
                <div class="card-v error-card my-5 p-5">
                    <div class="py-5">
                        <h1 class="error-code">@yield('code')</h1>
                        <h2 class="error-title">@yield('title')</h2>
                        <div class="col-lg-9 m-auto">
                            <p class="error-message">@yield('message')</p>
                        </div>
                        <div>
                            <a href="{{ route('reviewer.dashboard') }}" class="btn btn-primary btn-md"><i
                                    class="fa-solid fa-table-columns me-2"></i>{{ translate('Go to dashboard') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('reviewer.includes.scripts')
</body>

</html>
