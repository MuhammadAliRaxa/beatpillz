@extends('admin.layouts.form')
@section('section', translate('Settings'))
@section('title', translate('Storage Settings'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.storage.update') }}" method="POST">
        @csrf
        <div class="card mb-4">
            <div class="card-header">
                {{ translate('Storage Provider') }}
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label">{{ translate('Storage Provider') }}</label>
                        <select id="storage-provider" name="storage_provider" class="form-select form-select-md">
                            @foreach ($storageProviders as $storageProvider)
                                <option value="{{ $storageProvider->alias }}"
                                    {{ $storageProvider->isDefault() ? 'selected' : '' }}>{{ $storageProvider->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @foreach ($storageProviders as $storageProvider)
                        @if (!$storageProvider->isLocal())
                            @if ($storageProvider->credentials)
                                @foreach ($storageProvider->credentials as $key => $value)
                                    <div
                                        class="col-12 credentials credential-{{ $storageProvider->alias }} {{ !$storageProvider->isDefault() ? 'd-none' : '' }}">
                                        <label class="form-label capitalize">
                                            {{ str_replace('_', ' ', $key) }} :
                                        </label>
                                        <input type="text"
                                            name="credentials[{{ $storageProvider->alias }}][{{ $key }}]"
                                            value="{{ demo($value) }}" class="form-control form-control-md remove-spaces">
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    @endforeach
                </div>
                <div class="alert alert-primary mb-0">
                    <i class="fa-regular fa-circle-question me-1"></i>
                    {{ translate('When you change the storage provider, you must move all files form those paths to new storage provider.') }}
                    <div class="mt-2">
                        <h6>{{ translate('Local') }}</h6>
                        <ul>
                            <li><strong>public/images/editor/</strong></li>
                            <li><strong>public/images/items/</strong></li>
                            <li><strong>public/files/items/</strong></li>
                            <li><strong>storage/app/files/</strong></li>
                            <li class="mb-0"><strong>storage/app/files/items/</strong></li>
                        </ul>
                        <h6>{{ translate('s3 and others') }}</h6>
                        <ul class="mb-0">
                            <li><strong>images/editor/</strong></li>
                            <li><strong>images/items/</strong></li>
                            <li><strong>files/items/</strong></li>
                            <li><strong>files/</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @if (config('filesystems.default') != 'local')
        <div class="card">
            <div class="card-header">
                {{ translate('Test Storage Connection') }}
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.settings.storage.test') }}" method="POST">
                    @csrf
                    <button class="btn btn-dark btn-lg w-100">{{ translate('Test Connection') }}</button>
                </form>
            </div>
        </div>
    @endif
    @push('scripts')
        <script>
            "use strict";
            let storageProvider = $('#storage-provider');
            storageProvider.on('change', function() {
                let storageProviderValue = $(this).val();
                $('.credentials').addClass('d-none');
                $('.credential-' + storageProviderValue).removeClass('d-none');
            });
        </script>
    @endpush
@endsection
