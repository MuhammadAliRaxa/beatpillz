@extends('themes.basic.workspace.layouts.app')
@section('title', translate('Settings'))
@section('breadcrumbs', Breadcrumbs::render('workspace.settings.api-key'))
@section('content')
    @include('themes.basic.workspace.settings.includes.tabs')
    <div class="dashboard-card card-v">
        <div class="form-section">
            <h5 class="mb-0">{{ translate('Your API Key') }}</h5>
        </div>
        @if ($user->api_key)
            <div class="col-lg-6">
                <div class="input-group mb-3">
                    <input id="apiKey" type="text" class="form-control form-control-md " value="{{ $user->api_key }}"
                        readonly>
                    <button class="btn btn-outline-primary btn-copy" data-clipboard-target="#apiKey">
                        <i class="far fa-clone"></i>
                    </button>
                </div>
            </div>
        @endif
        <div class="row g-3 row-cols-auto">
            <div class="col">
                <form action="{{ route('workspace.settings.api-key.generate') }}" method="POST">
                    @csrf
                    @if ($user->api_key)
                        <button class="btn btn-primary btn-md action-confirm">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>
                            {{ translate('Generate New API Key') }}
                        </button>
                    @else
                        <button class="btn btn-primary btn-md">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>
                            {{ translate('Generate API Key') }}
                        </button>
                    @endif
                </form>
            </div>
            <div class="col">
                <a href="{{ route('api.docs') }}" class="btn btn-outline-primary btn-md" target="_blank">
                    <i class="fa-solid fa-code me-1"></i>
                    {{ translate('API Docs') }}
                </a>
            </div>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
