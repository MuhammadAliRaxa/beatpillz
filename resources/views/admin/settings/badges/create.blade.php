@extends('admin.layouts.form')
@section('section', translate('Settings'))
@section('title', translate('New badge'))
@section('back', route('admin.settings.badges.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.badges.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card p-2 pb-3">
            <div class="card-body">
                <div class="row g-3 row-cols-1">
                    <div class="col">
                        <div class="vironeer-file-preview-box bg-light p-4 text-center">
                            <div class="file-preview-box d-none mb-3">
                                <img id="filePreview" src="#" width="80px" height="80px">
                            </div>
                            <button id="selectFileBtn" type="button"
                                class="btn btn-secondary btn-md mb-2">{{ translate('Badge Image') }}</button>
                            <input id="selectedFileInput" type="file" name="badge_image" accept=".png, .svg" required
                                hidden>
                            <small class="text-muted d-block">{{ translate('Allowed (PNG, SVG)') }}</small>
                        </div>
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Name') }} </label>
                        <input type="text" name="name" class="form-control form-control-lg"
                            value="{{ old('name') }}" required />
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Title (Optional)') }} </label>
                        <input type="text" name="title" class="form-control form-control-lg"
                            value="{{ old('title') }}" />
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Type (Optional)') }}</label>
                        <select id="badgeType" name="type" class="form-select form-select-lg">
                            <option value="" selected>{{ translate('None') }}</option>
                            <option value="countries" @selected(old('type') == 'countries')>
                                {{ translate('Countries') }}
                            </option>
                            <option value="author_levels" @selected(old('type') == 'author_levels')>
                                {{ translate('Author levels') }}
                            </option>
                            <option value="membership_years" @selected(old('type') == 'membership_years')>
                                {{ translate('Membership years') }}
                            </option>
                        </select>
                    </div>
                    <div id="countries" class="col d-none">
                        <label class="form-label">{{ translate('Country') }}</label>
                        <select name="country" class="form-select form-select-lg" required disabled>
                            <option value="">--</option>
                            @foreach (countries() as $countryCode => $countryName)
                                <option value="{{ $countryCode }}">
                                    {{ $countryName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="authorLevels" class="col d-none">
                        <label class="form-label">{{ translate('Author level') }}</label>
                        <select name="author_level" class="form-select form-select-lg" required disabled>
                            @foreach ($levels as $level)
                                <option value="{{ $level->id }}" @selected(old('author_level') == $level->id)>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="membershipYears" class="col badge-types badge-type-2 d-none">
                        <label class="form-label">{{ translate('Years') }} </label>
                        <input type="number" name="membership_years" class="form-control form-control-lg" step="any"
                            min="1" value="{{ old('membership_years') }}" required disabled>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
