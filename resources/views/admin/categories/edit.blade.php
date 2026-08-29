@extends('admin.layouts.form')
@section('section', translate('Main Categories'))
@section('title', $category->name)
@section('back', route('admin.categories.index'))
@section('container', 'container-max-lg')
@section('content')
    <div class="mb-3">
        <a class="btn btn-outline-secondary" href="{{ $category->getLink() }}" target="_blank"><i
                class="fa fa-eye me-2"></i>{{ translate('View') }}</a>
    </div>
    <form id="vironeer-submited-form" action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card mb-3">
            <div class="card-header">{{ translate('Category') }}</div>
            <div class="card-body p-4">
                <div class="row g-3 row-cols-1 mb-3">
                    <div class="col">
                        <label class="form-label">{{ translate('Name') }} </label>
                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required />
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Slug') }} </label>
                        <input type="text" name="slug" id="show_slug" class="form-control"
                            value="{{ $category->slug }}" required />
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
                            'value' => $category->regular_buyer_fee,
                            'required' => true,
                        ])
                    </div>
                    <div class="col">
                        @include('admin.partials.input-price', [
                            'label' => translate('Extended License Buyer fee'),
                            'name' => 'extended_buyer_fee',
                            'value' => $category->extended_buyer_fee,
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
                        <select class="form-select" disabled>
                            @foreach (\App\Models\Category::getFileTypeOptions() as $key => $value)
                                <option value="{{ $key }}" @selected($category->file_type == $key)>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('Thumbnail Width') }}</label>
                        <div class="input-group">
                            <input type="number" name="thumbnail_width" class="form-control" min="1" step="any"
                                step="any" value="{{ $category->thumbnail_width }}" required>
                            <span class="input-group-text">{{ translate('px') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('Thumbnail Height') }}</label>
                        <div class="input-group">
                            <input type="number" name="thumbnail_height" class="form-control" min="1" step="any"
                                step="any" value="{{ $category->thumbnail_height }}" required>
                            <span class="input-group-text">{{ translate('px') }}</span>
                        </div>
                    </div>
                    @if (!$category->isFileTypeFileWithAudioPreview())
                        <div class="col-lg-6">
                            <label class="form-label">{{ translate('Preview Image Width') }}</label>
                            <div class="input-group">
                                <input type="number" name="preview_image_width" class="form-control" min="1"
                                    step="any" value="{{ $category->preview_image_width }}">
                                <span class="input-group-text">{{ translate('px') }}</span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">{{ translate('Preview Image Height') }}</label>
                            <div class="input-group">
                                <input type="number" name="preview_image_height" class="form-control" min="1"
                                    step="any" value="{{ $category->preview_image_height }}">
                                <span class="input-group-text">{{ translate('px') }}</span>
                            </div>
                        </div>
                    @endif
                    @if ($category->isFileTypeFileWithImagePreview())
                        <div class="col-12">
                            <label class="form-label">{{ translate('Maximum Screenshots') }}</label>
                            <input type="number" name="maximum_screenshots" class="form-control" min="1"
                                step="any" max="100" value="{{ $category->maximum_screenshots }}">
                            <div class="form-text">{{ translate('Number between 1 to 100') }}</div>
                        </div>
                    @endif
                    <div class="col-lg-12">
                        <label class="form-label">{{ translate('Preview File (Size)') }}</label>
                        <div class="input-group">
                            <input type="number" name="max_preview_file_size" class="form-control" min="1"
                                value="{{ $category->max_preview_file_size / 1048576 }}" required>
                            <span class="input-group-text">{{ translate('MB') }}</span>
                        </div>
                        <div class="form-text">
                            {{ translate('The preview image, video or audio size in megabyte.') }}
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ translate('Main File Types') }}</label>
                        <input type="text" name="main_file_types" class="form-control form-control-md tags-input"
                            value="{{ $category->main_file_types }}" required>
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
                        <input type="text" name="title" class="form-control" value="{{ $category->title }}"
                            maxlength="70" />
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Description') }} </label>
                        <textarea type="text" name="description" class="form-control" rows="6" maxlength="150" />{{ $category->description }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/tags-input/bootstrap-tagsinput.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/tags-input/bootstrap-tagsinput.min.js') }}"></script>
    @endpush
@endsection
