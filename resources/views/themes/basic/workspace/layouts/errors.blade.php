<!DOCTYPE html>
<html lang="{{ getLocale() }}" dir="{{ getDirection() }}">

<head>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/simplebar/simplebar.min.css') }}">
    @endpush
    @section('noindex', true)
    @include('themes.basic.includes.head')
</head>

<body>
    <div class="dashboard">
        @include('themes.basic.workspace.includes.sidebar')
        <div class="dashboard-body">
            @include('themes.basic.workspace.includes.navbar')
            <div class="dashboard-container dashboard-container-md py-5">
                <div class="card-v error-card my-5 p-5">
                    <div class="py-5">
                        <h1 class="error-code">@yield('code')</h1>
                        <h2 class="error-title">@yield('title')</h2>
                        <div class="col-lg-9 m-auto">
                            <p class="error-message">@yield('message')</p>
                        </div>
                        @if (authUser()->isAuthor())
                            <div>
                                <a href="{{ route('workspace.dashboard') }}" class="btn btn-primary btn-md"><i
                                        class="fa-solid fa-table-columns me-2"></i>{{ translate('Go to dashboard') }}</a>
                            </div>
                        @else
                            <div>
                                <a href="{{ route('workspace.purchases.index') }}" class="btn btn-primary btn-md"><i
                                        class="fa-solid fa-box-archive me-2"></i>{{ translate('Go to My Purchases') }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/simplebar/simplebar.min.js') }}"></script>
    @endpush
    @include('themes.basic.includes.config')
    @include('themes.basic.includes.scripts')
</body>

</html>
