@extends('admin.layouts.form')
@section('section', translate('Settings'))
@section('title', translate('Watermark'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.settings.watermark.update') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="vironeer-file-preview-box bg-light py-4 text-center">
                            <div class="file-preview-box mb-3">
                                <img id="filePreview" src="{{ asset(@$settings->watermark->image) }}" height="100%">
                            </div>
                            <button id="selectFileBtn" type="button"
                                class="btn btn-secondary mb-2">{{ translate('Choose Image') }}</button>
                            <input id="selectedFileInput" type="file" name="watermark[image]" accept="image/png" hidden>
                            <small class="text-muted d-block">{{ translate('Image must be PNG format') }}</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="col-lg-3">
                            <label class="form-label">{{ translate('Status') }} :</label>
                            <input type="checkbox" name="watermark[status]" data-toggle="toggle" data-height="40px"
                                {{ @$settings->watermark->status ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label class="form-label">{{ translate('Position') }}</label>
                        <select name="watermark[position]" class="form-select form-select-md" required>
                            @foreach (\App\Models\Settings::watermarkOptions() as $key => $value)
                                <option value="{{ $key }}" @selected(@$settings->watermark->position == $key)>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('Width') }}</label>
                        <input type="number" name="watermark[width]" class="form-control form-control-md" min="25"
                            max="10000" value="{{ @$settings->watermark->width }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('Height') }}</label>
                        <input type="number" name="watermark[height]" class="form-control form-control-md" min="25"
                            max="10000" value="{{ @$settings->watermark->height }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('Rotate') }}</label>
                        <input type="number" name="watermark[rotate]" class="form-control form-control-md"
                            value="{{ @$settings->watermark->rotate }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('Opacity') }}</label>
                        <input type="number" name="watermark[opacity]" class="form-control form-control-md" min="5"
                            max="100" value="{{ @$settings->watermark->opacity }}" required>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
