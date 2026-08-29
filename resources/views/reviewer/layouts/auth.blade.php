<!DOCTYPE html>
<html lang="{{ getLocale() }}" dir="{{ getDirection() }}">

<head>
    @include('reviewer.includes.head')
    @bootstrap
    <link rel="stylesheet" href="{{ asset('vendor/libs/fontawesome/fontawesome.min.css') }}" />
    @reviewerColors
    <link rel="stylesheet" href="{{ asset('vendor/libs/vironeer/toastr/css/vironeer-toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset_with_version('vendor/reviewer/css/app.css') }}" />
    @if (getDirection() == 'rtl')
    <link rel="stylesheet" href="{{ asset_with_version('vendor/reviewer/css/app.rtl.css') }}">
    @endif
    @reviewerCustomStyle
</head>

<body class="sign-body">
    <a href="{{ route('home') }}" class="logo sign-logo">
        <img src="{{ asset($themeSettings->general->logo_dark) }}" alt="{{ @$settings->general->site_name }}" />
    </a>
    <div class="sign-box">
        @yield('content')
    </div>
    <script src="{{ asset('vendor/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/vironeer/toastr/js/vironeer-toastr.min.js') }}"></script>
    <script src="{{ asset_with_version('vendor/reviewer/js/app.js') }}"></script>
    @toastrRender
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}')
            @endforeach
        </script>
    @elseif(session('status'))
        <script>
            toastr.success('{{ session('status') }}')
        </script>
    @endif
</body>

</html>
