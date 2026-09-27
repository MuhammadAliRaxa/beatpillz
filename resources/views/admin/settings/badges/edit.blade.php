@extends('admin.layouts.form')
@section('section', translate('Settings'))
@section('title', translate('Edit Badge'))
@section('back', route('admin.settings.badges.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.badges.update', $badge->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card p-2 pb-3">
            <div class="card-body">
                <div class="row g-3 row-cols-1">
                    <div class="col">
                        <div class="vironeer-file-preview-box bg-light p-4 text-center">
                            <div class="file-preview-box mb-3">
                                <img id="filePreview" src="{{ $badge->getImageLink() }}" width="80px" height="80px">
                            </div>
                            <button id="selectFileBtn" type="button"
                                class="btn btn-secondary btn-md mb-2">{{ translate('Badge Image') }}</button>
                            <input id="selectedFileInput" type="file" name="badge_image" accept=".png, .svg" hidden>
                            <small class="text-muted d-block">{{ translate('Allowed (PNG, SVG)') }}</small>
                        </div>
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Name') }} </label>
                        <input type="text" name="name" class="form-control form-control-lg"
                            value="{{ $badge->name }}" required />
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Title (Optional)') }} </label>
                        <input type="text" name="title" class="form-control form-control-lg"
                            value="{{ $badge->title }}" />
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Type (Optional)') }}</label>
                        <select class="form-select form-select-lg" disabled>
                            <option value="" selected>{{ translate('None') }}</option>
                            <option value="countries" @selected($badge->isCountryBadge())>
                                {{ translate('Countries') }}
                            </option>
                            <option value="author_levels" @selected($badge->isAuthorLevelBadge())>
                                {{ translate('Author levels') }}
                            </option>
                            <option value="membership_years" @selected($badge->isMembershipYearsBadge())>
                                {{ translate('Membership years') }}
                            </option>
                        </select>
                    </div>
                    <div id="countries" class="col {{ !$badge->isCountryBadge() ? 'd-none' : '' }}">
                        <label class="form-label">{{ translate('Country') }}</label>
                        <select name="country" class="form-select form-select-lg" required disabled>
                            <option value="">--</option>
                            @foreach (countries() as $countryCode => $countryName)
                                <option value="{{ $countryCode }}" @selected($badge->country == $countryCode)>
                                    {{ $countryName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col {{ !$badge->isAuthorLevelBadge() ? 'd-none' : '' }}">
                        <label class="form-label">{{ translate('Author level') }}</label>
                        <select class="form-select form-select-lg" disabled>
                            @foreach ($levels as $level)
                                <option value="{{ $level->id }}" @selected($badge->level_id == $level->id)>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col {{ !$badge->isMembershipYearsBadge() ? 'd-none' : '' }}">
                        <label class="form-label">{{ translate('Years') }}</label>
                        <input type="number" class="form-control form-control-lg" step="any" min="1"
                            value="{{ $badge->membership_years }}" disabled>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
