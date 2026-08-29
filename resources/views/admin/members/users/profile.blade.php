@extends('admin.layouts.form')
@section('section', translate('Users'))
@section('title', translate(':name Profile details', ['name' => $user->getName()]))
@section('back', route('admin.members.users.index'))
@section('content')
    @include('admin.members.users.includes.elements')
    <div class="row g-3">
        @include('admin.members.users.includes.sidebar')
        <div class="col-lg-9">
            <form id="vironeer-submited-form" action="{{ route('admin.members.users.profile.update', $user->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card mb-3">
                    <div class="card-header">{{ translate('Profile details') }}</div>
                    <div class="card-body p-4">
                        <div class="row g-4 mb-2">
                            <div class="col-lg-6">
                                <div class="p-4 border bg-light rounded-2">
                                    <h5>{{ translate('Avatar') }}</h5>
                                    <div class="my-3">
                                        <img id="image-preview-0" class="border p-2 rounded-2 bg-light"
                                            src="{{ $user->getAvatar() }}" alt="{{ $user->getName() }}" width="100px"
                                            height="100px">
                                    </div>
                                    <input type="file" name="avatar" class="form-control form-control-lg image-input"
                                        data-id="0" accept="image/png, image/jpg, image/jpeg">
                                    <div class="form-text mt-2">
                                        {{ translate('Allowed types (JPG,JPEG,PNG) Size 120x120px') }}</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-4 border bg-light rounded-2">
                                    <h5>{{ translate('Profile Cover') }}</h5>
                                    <div class="my-3">
                                        <img id="image-preview-1" class="border p-2 rounded-2 bg-light"
                                            src="{{ $user->getProfileCover() }}" alt="{{ $user->getName() }}" width="200px"
                                            height="100px">
                                    </div>
                                    <input type="file" name="profile_cover"
                                        class="form-control form-control-lg  image-input" data-id="1"
                                        accept="image/png, image/jpg, image/jpeg">
                                    <div class="form-text mt-2">
                                        {{ translate('Allowed types (JPG,JPEG,PNG) Size 1200x500px') }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ translate('Profile Heading') }}</label>
                                <input type="text" name="profile_heading" class="form-control form-control-lg"
                                    value="{{ $user->profile_heading }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ translate('Profile Description') }}</label>
                                <textarea name="profile_description" class="ckeditor">{{ $user->profile_description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">{{ translate('Profile Contact Email') }}</div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">{{ translate('Email') }}</label>
                                <input type="email" name="profile_contact_email" class="form-control form-control-lg"
                                    value="{{ demo($user->profile_contact_email) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">{{ translate('Profile Social Links') }}</div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label class="form-label">{{ translate('Facebook') }}</label>
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <button type="button" class="social-btn btn-facebook">
                                            <i class="fab fa-facebook-f"></i>
                                        </button>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="profile_social_links[facebook]"
                                            class="form-control form-control-lg" placeholder="{{ translate('Username') }}"
                                            value="{{ @$user->profile_social_links->facebook }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ translate('X.com') }}</label>
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <button type="button" class="social-btn btn-x">
                                            <i class="fab fa-x-twitter"></i>
                                        </button>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="profile_social_links[x]"
                                            class="form-control form-control-lg" placeholder="{{ translate('Username') }}"
                                            value="{{ @$user->profile_social_links->x }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ translate('Youtube') }}</label>
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <button type="button" class="social-btn btn-youtube">
                                            <i class="fab fa-youtube"></i>
                                        </button>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="profile_social_links[youtube]"
                                            class="form-control form-control-lg"
                                            placeholder="{{ translate('Username') }}"
                                            value="{{ @$user->profile_social_links->youtube }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ translate('Linkedin') }}</label>
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <button type="button" class="social-btn btn-linkedin">
                                            <i class="fab fa-linkedin"></i>
                                        </button>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="profile_social_links[linkedin]"
                                            class="form-control form-control-lg"
                                            placeholder="{{ translate('Username') }}"
                                            value="{{ @$user->profile_social_links->linkedin }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ translate('Instagram') }}</label>
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <button type="button" class="social-btn btn-instagram">
                                            <i class="fab fa-instagram"></i>
                                        </button>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="profile_social_links[instagram]"
                                            class="form-control form-control-lg"
                                            placeholder="{{ translate('Username') }}"
                                            value="{{ @$user->profile_social_links->instagram }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">{{ translate('Pinterest') }}</label>
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <button type="button" class="social-btn btn-pinterest">
                                            <i class="fab fa-pinterest"></i>
                                        </button>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="profile_social_links[pinterest]"
                                            class="form-control form-control-lg"
                                            placeholder="{{ translate('Username') }}"
                                            value="{{ @$user->profile_social_links->pinterest }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/ckeditor/plugins/uploadAdapterPlugin.js') }}"></script>
        <script src="{{ asset('vendor/libs/ckeditor/ckeditor.basic.js') }}"></script>
    @endpush
@endsection
