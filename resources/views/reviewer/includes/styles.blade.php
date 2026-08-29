@bootstrap
<link rel="stylesheet" href="{{ asset('vendor/libs/fontawesome/fontawesome.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/libs/vironeer/toastr/css/vironeer-toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/libs/vironeer/counter-cards.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/libs/simplebar/simplebar.min.css') }}" />
@stack('styles_libs')
@reviewerColors
<link rel="stylesheet" href="{{ asset_with_version('vendor/reviewer/css/app.css') }}" />
@if (getDirection() == 'rtl')
<link rel="stylesheet" href="{{ asset_with_version('vendor/reviewer/css/app.rtl.css') }}">
@endif
@stack('styles')
@reviewerCustomStyle
