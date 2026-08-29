@include('reviewer.includes.config')
@stack('top_scripts')
<script src="{{ asset('vendor/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/libs/vironeer/toastr/js/vironeer-toastr.min.js') }}"></script>
<script src="{{ asset('vendor/libs/simplebar/simplebar.min.js') }}"></script>
@stack('scripts_libs')
<script src="{{ asset_with_version('vendor/reviewer/js/app.js') }}"></script>
@toastrRender
@stack('scripts')
