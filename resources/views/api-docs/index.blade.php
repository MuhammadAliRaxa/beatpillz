<!DOCTYPE html>
<html lang="{{ getLocale() }}" dir="{{ getDirection() }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @section('title', translate('API Docs'))
    <title>{{ seoTitle($__env) }}</title>
    <link rel="shortcut icon" href="{{ asset($themeSettings->general->favicon) }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/fontawesome/fontawesome.min.css') }}" />
    @bootstrap
    <link rel="stylesheet" href="{{ asset('vendor/api-docs/css/app.css') }}" />
</head>

<body>
    <div class="page">
        @include('api-docs.includes.sidebar')
        <div class="content">
            <div class="content-container">
                <div class="tab-content">
                    @include('api-docs.sections.overview')
                    @include('api-docs.sections.authentication')
                    @include('api-docs.sections.account-details')
                    @include('api-docs.sections.items-all')
                    @include('api-docs.sections.items-item')
                    @include('api-docs.sections.purchase-validation')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/libs/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('vendor/api-docs/js/app.js') }}"></script>
</body>

</html>
