@extends('admin.layouts.form')
@section('section', translate('Faker'))
@section('title', translate('Settings'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.faker.settings') }}" method="POST">
        @csrf
        <div class="card mb-3">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ translate('API Provider') }}</label>
                        <select id="apiProvider" name="faker[api_provider]" class="form-select form-select-md">
                            <option value="gemini" @selected(@$settings->faker->api_provider == 'gemini')>{{ translate('Gemini') }}</option>
                            <option value="openai" @selected(@$settings->faker->api_provider == 'openai')>{{ translate('Openai') }}</option>
                        </select>
                    </div>
                    <div
                        class="col-12 provider provider-gemini {{ @$settings->faker->api_provider == 'openai' ? 'd-none' : '' }}">
                        <label class="form-label">{{ translate('Gemini API Key') }}</label>
                        <input type="text" name="faker[gemini_api_key]" class="form-control form-control-md"
                            value="{{ demo(@$settings->faker->gemini_api_key) }}">
                        <div class="form-text">
                            {!! translate('You can get your API key from :link', [
                                'link' =>
                                    '<a href="https://aistudio.google.com/app/apikey" target="_blank">https://aistudio.google.com/app/apikey</a>',
                            ]) !!}
                        </div>
                    </div>
                    <div
                        class="col-12 provider provider-openai {{ @$settings->faker->api_provider == 'gemini' ? 'd-none' : '' }}">
                        <label class="form-label">{{ translate('Openai API Key') }}</label>
                        <input type="text" name="faker[openai_api_key]" class="form-control form-control-md"
                            value="{{ demo(@$settings->faker->openai_api_key) }}">
                        <div class="form-text">
                            {!! translate('You can get your API key from :link', [
                                'link' =>
                                    '<a href="https://platform.openai.com/settings/organization/api-keys" target="_blank">https://platform.openai.com/settings/organization/api-keys</a>',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('scripts')
        <script>
            "use strict";
            let apiProvider = $('#apiProvider');
            apiProvider.on('change', function() {
                let val = $(this).val();
                $('.provider').addClass('d-none');
                $('.provider-' + val).removeClass('d-none');
            });
        </script>
    @endpush
@endsection
