@extends('themes.basic.workspace.layouts.app')
@section('title', translate('Settings'))
@section('breadcrumbs', Breadcrumbs::render('workspace.settings.profile'))
@section('content')
    @include('themes.basic.workspace.settings.includes.tabs')
    <form action="{{ route('workspace.settings.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="dashboard-card card-v mb-3">
            <div class="form-section">
                <h5 class="mb-0">{{ translate('Profile Details') }}</h5>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-lg-6">
                    <div class="p-4 border bg-light rounded-2">
                        <h5>{{ translate('Avatar') }}</h5>
                        <div class="my-3">
                            <img id="image-preview-1" class="border p-2 rounded-4 bg-light" src="{{ $user->getAvatar() }}"
                                alt="{{ $user->getName() }}" width="100px" height="100px">
                        </div>
                        <input type="file" name="avatar" class="form-control form-control-md image-input" data-id="1"
                            accept="image/png, image/jpg, image/jpeg">
                        <div class="form-text mt-2">
                            {{ translate('Allowed types (JPG,PNG) Size 120x120px') }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-4 border bg-light rounded-2">
                        <h5>{{ translate('Profile Cover') }}</h5>
                        <div class="my-3">
                            <img id="image-preview-2" class="border p-2 rounded-4 bg-light"
                                src="{{ $user->getProfileCover() }}" alt="{{ $user->getName() }}" width="200px"
                                height="100px">
                        </div>
                        <input type="file" name="profile_cover" class="form-control form-control-md image-input"
                            data-id="2" accept="image/png, image/jpg, image/jpeg">
                        <div class="form-text mt-2">
                            {{ translate('Allowed types (JPG,PNG) Size 1200x500px') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ translate('Profile Heading') }}</label>
                <input type="text" name="profile_heading" class="form-control form-control-md"
                    value="{{ $user->profile_heading }}">
            </div>
            <div class="mb-3">
                <label class="form-label">{{ translate('Profile Description') }}</label>
                <textarea name="profile_description" class="ckeditor">{{ $user->profile_description }}</textarea>
            </div>
        </div>
        <div class="dashboard-card card-v mb-3">
            <div class="form-section">
                <h5 class="mb-0">{{ translate('Profile Contact Email') }}</h5>
            </div>
            <div>
                <label class="form-label">{{ translate('Email') }}</label>
                <input type="email" name="profile_contact_email" class="form-control form-control-md"
                    value="{{ $user->profile_contact_email }}">
                <div class="form-text">
                    {{ translate('Add your email to enable the contact form in your profile.') }}
                </div>
            </div>
        </div>
        <div class="dashboard-card card-v mb-3">
            <div class="form-section">
                <h5 class="mb-0">{{ translate('Profile Social links') }}</h5>
            </div>
            <div class="row g-3">
                <div class="col-lg-6">
                    <label class="form-label">{{ translate('Facebook') }}</label>
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <button type="button" class="social-btn social-facebook p-4">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                        </div>
                        <div class="col">
                            <input type="text" name="profile_social_links[facebook]" class="form-control form-control-md"
                                placeholder="{{ translate('Username') }}"
                                value="{{ @$user->profile_social_links->facebook }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">{{ translate('X.com') }}</label>
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <button type="button" class="social-btn social-x p-4">
                                <i class="fab fa-x-twitter"></i>
                            </button>
                        </div>
                        <div class="col">
                            <input type="text" name="profile_social_links[x]" class="form-control form-control-md"
                                placeholder="{{ translate('Username') }}"
                                value="{{ @$user->profile_social_links->x }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">{{ translate('Youtube') }}</label>
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <button type="button" class="social-btn social-youtube p-4">
                                <i class="fab fa-youtube"></i>
                            </button>
                        </div>
                        <div class="col">
                            <input type="text" name="profile_social_links[youtube]" class="form-control form-control-md"
                                placeholder="{{ translate('Username') }}"
                                value="{{ @$user->profile_social_links->youtube }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">{{ translate('Linkedin') }}</label>
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <button type="button" class="social-btn social-linkedin p-4">
                                <i class="fab fa-linkedin"></i>
                            </button>
                        </div>
                        <div class="col">
                            <input type="text" name="profile_social_links[linkedin]"
                                class="form-control form-control-md"
                                placeholder="{{ translate('Username') }}"
                                value="{{ @$user->profile_social_links->linkedin }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">{{ translate('Instagram') }}</label>
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <button type="button" class="social-btn social-instagram p-4">
                                <i class="fab fa-instagram"></i>
                            </button>
                        </div>
                        <div class="col">
                            <input type="text" name="profile_social_links[instagram]"
                                class="form-control form-control-md"
                                placeholder="{{ translate('Username') }}"
                                value="{{ @$user->profile_social_links->instagram }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">{{ translate('Pinterest') }}</label>
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <button type="button" class="social-btn social-pinterest p-4">
                                <i class="fab fa-pinterest"></i>
                            </button>
                        </div>
                        <div class="col">
                            <input type="text" name="profile_social_links[pinterest]"
                                class="form-control form-control-md"
                                placeholder="{{ translate('Username') }}"
                                value="{{ @$user->profile_social_links->pinterest }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-card card-v">
            <button class="btn btn-primary btn-md">{{ translate('Save Changes') }}</button>
        </div>
    </form>
    @include('themes.basic.workspace.partials.ckeditor')
@endsection
