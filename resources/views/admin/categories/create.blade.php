@extends('admin.layouts.form')
@section('section', translate('Main Categories'))
@section('title', translate('New Category'))
@section('back', route('admin.categories.index'))
@section('container', 'container-max-lg')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="card mb-3">
            <div class="card-header">{{ translate('Category') }}</div>
            <div class="card-body p-4">
                <div class="row g-3 row-cols-1 mb-3">
                    <div class="col">
                        <label class="form-label">{{ translate('Name') }} </label>
                        <input type="text" name="name" id="create_slug" class="form-control"
                            value="{{ old('name') }}" required autofocus />
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Slug') }} </label>
                        <input type="text" name="slug" id="show_slug" class="form-control" value="{{ old('slug') }}"
                            required />
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">{{ translate('Buyer fee') }}</div>
            <div class="card-body p-4">
                <div class="row g-3 row-cols-1 mb-3">
                    <div class="col">
                        @include('admin.partials.input-price', [
                            'label' => translate('Regular License Buyer fee'),
                            'name' => 'regular_buyer_fee',
                            'required' => true,
                        ])
                    </div>
                    <div class="col">
                        @include('admin.partials.input-price', [
                            'label' => translate('Extended License Buyer fee'),
                            'name' => 'extended_buyer_fee',
                            'required' => true,
                        ])
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">{{ translate('Files') }}</div>
            <div class="card-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label">{{ translate('File type') }} </label>
                        <select name="file_type" class="form-select" required>
                            <option value="" selected disabled>{{ translate('Choose') }}</option>
                            @foreach (\App\Models\Category::getFileTypeOptions() as $key => $value)
                                <option value="{{ $key }}" @selected(old('file_type') == $key)>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('Thumbnail Width') }}</label>
                        <div class="input-group">
                            <input type="number" name="thumbnail_width" class="form-control" min="1" step="any"
                                step="any" value="{{ old('thumbnail_width') }}" required>
                            <span class="input-group-text">{{ translate('px') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('Thumbnail Height') }}</label>
                        <div class="input-group">
                            <input type="number" name="thumbnail_height" class="form-control" min="1" step="any"
                                step="any" value="{{ old('thumbnail_height') }}" required>
                            <span class="input-group-text">{{ translate('px') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-6 preview-image d-none">
                        <label class="form-label">{{ translate('Preview Image Width') }}</label>
                        <div class="input-group">
                            <input type="number" name="preview_image_width" class="form-control" min="1"
                                step="any" value="{{ old('preview_image_height') }}">
                            <span class="input-group-text">{{ translate('px') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-6 preview-image d-none">
                        <label class="form-label">{{ translate('Preview Image Height') }}</label>
                        <div class="input-group">
                            <input type="number" name="preview_image_height" class="form-control" min="1"
                                step="any" value="{{ old('preview_image_height') }}">
                            <span class="input-group-text">{{ translate('px') }}</span>
                        </div>
                    </div>
                    <div class="col-12 screenshots d-none">
                        <label class="form-label">{{ translate('Maximum Screenshots') }}</label>
                        <input type="number" name="maximum_screenshots" class="form-control" min="1" step="any"
                            max="100" value="{{ old('maximum_screenshots') }}">
                        <div class="form-text">{{ translate('Number between 1 to 100') }}</div>
                    </div>
                    <div class="col-lg-12">
                        <label class="form-label">{{ translate('Preview File (Size)') }}</label>
                        <div class="input-group">
                            <input type="number" name="max_preview_file_size" class="form-control" min="1"
                                value="{{ old('max_preview_file_size') }}" required>
                            <span class="input-group-text">{{ translate('MB') }}</span>
                        </div>
                        <div class="form-text">
                            {{ translate('The preview image, video or audio size in megabyte.') }}
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ translate('Main File Types') }}</label>
                        <input type="text" name="main_file_types" class="form-control form-control-md tags-input"
                            value="{{ old('main_file_types') }}" required>
                        <div class="form-text">
                            {{ translate('The allowed files to be uploaded as main file, like (ZIP, RAR, PDF, MP4, MP3, etc...)') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">{{ translate('SEO (Optional)') }}</div>
            <div class="card-body p-4">
                <div class="row g-3 row-cols-1 mb-3">
                    <div class="col">
                        <label class="form-label">{{ translate('Title') }} </label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                            maxlength="70" />
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Description') }} </label>
                        <textarea type="text" name="description" class="form-control" rows="6" maxlength="150" />{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('top_scripts')
        <script>
            "use strict";
            let GET_SLUG_URL = "{{ route('admin.categories.slug') }}";
        </script>
    @endpush
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/tags-input/bootstrap-tagsinput.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/tags-input/bootstrap-tagsinput.min.js') }}"></script>
    @endpush
    @push('scripts')
        <script>
            "use strict";
            let fileType = $('select[name=file_type]');
            fileType.on('change', function() {
                let previewImage = $('.preview-image'),
                    screenshots = $('.screenshots');
                if ($(this).val() == 3) {
                    previewImage.addClass('d-none');
                } else {
                    previewImage.removeClass('d-none');
                }

                if ($(this).val() != 1) {
                    screenshots.addClass('d-none');
                } else {
                    screenshots.removeClass('d-none');
                }
            });
        </script>
    @endpush
@endsection
