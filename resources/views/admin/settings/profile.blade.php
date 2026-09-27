@extends('admin.layouts.form')
@section('section', translate('Settings'))
@section('title', translate('Profile Settings'))
@section('container', 'container-max-xl')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.profile.update') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">{{ translate('Default Images') }}</div>
            <div class="card-body p-4">
                <div class="row row-cols-1 g-3">
                    <div class="col-lg-12">
                        <div class="image-box p-4 border bg-light rounded-2">
                            <h5>{{ translate('Default Avatar') }}</h5>
                            <div class="my-3">
                                <img id="image-preview-0" class="border p-2 rounded-2 bg-light"
                                    src="{{ asset(@$settings->profile->default_avatar) }}" alt="default_avatar"
                                    height="60px">
                            </div>
                            <input type="file" name="profile[default_avatar]" class="form-control image-input"
                                data-id="0" accept=".jpg,.jpeg,.png">
                            <div class="form-text mt-2">
                                {{ translate('Supported (JPEG, JPG, PNG) Size 120x120px.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="image-box p-4 border bg-light rounded-2">
                            <h5>{{ translate('Default Cover') }}</h5>
                            <div class="my-3">
                                <img id="image-preview-1" class="border p-2 rounded-2 bg-light"
                                    src="{{ asset(@$settings->profile->default_cover) }}" alt="default_cover"
                                    height="60px">
                            </div>
                            <input type="file" name="profile[default_cover]" class="form-control image-input"
                                data-id="1" accept=".jpg,.jpeg,.png">
                            <div class="form-text mt-2">
                                {{ translate('Supported (JPEG, JPG, PNG) Size 1200x500px.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
