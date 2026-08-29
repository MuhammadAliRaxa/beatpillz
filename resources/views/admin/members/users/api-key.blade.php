@extends('admin.layouts.form')
@section('section', translate('Users'))
@section('title', translate(':name API Key', ['name' => $user->getName()]))
@section('back', route('admin.members.users.index'))
@section('content')
    @include('admin.members.users.includes.elements')
    <div class="row g-3">
        @include('admin.members.users.includes.sidebar')
        <div class="col-lg-9">
            <div class="card h-100">
                <div class="card-header">{{ translate('API Key') }}</div>
                <div class="card-body p-4">
                    @if ($user->api_key)
                        <div class="col-lg-8">
                            <div class="input-group mb-3">
                                <input id="apiKey" type="text" class="form-control form-control-md "
                                    value="{{ $user->api_key }}" readonly>
                                <button class="btn btn-outline-primary btn-copy" data-clipboard-target="#apiKey">
                                    <i class="far fa-clone"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                    <div class="row g-3 row-cols-auto">
                        <div class="col">
                            <form action="{{ route('admin.members.users.api-key.generate', $user->id) }}" method="POST">
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
            </div>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
