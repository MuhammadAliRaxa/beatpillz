<div id="upload-files-box" class="dashboard-card card-v p-0 mb-4">
    <div class="card-v-header border-bottom py-3 px-4">
        <h5 class="mb-0">{{ translate('Files') }}</h5>
    </div>
    <div class="card-v-body p-4">
        <div class="uploaded-files">
            @foreach ($uploadedFiles as $uploadedFile)
                <div class="uploaded-file uploaded-file-{{ hash_encode($uploadedFile->id) }}">
                    <div class="uploaded-file-icon">
                        @if ($uploadedFile->isImage())
                            <img src="{{ $uploadedFile->getFileLink() }}" alt="{{ $uploadedFile->name }}" />
                        @else
                            <span class="vi vi-file" data-type="{{ $uploadedFile->extension }}"></span>
                        @endif
                    </div>
                    <div class="uploaded-file-info">
                        <h6 class="uploaded-file-name"><span class="success-mark"><i
                                    class="far fa-check-circle"></i></span>{{ $uploadedFile->getShortName() }}
                            <small class="ms-1">({{ $uploadedFile->getSize() }})</small>
                        </h6>
                        <p class="uploaded-file-time">{{ $uploadedFile->created_at->diffforhumans() }}</p>
                    </div>
                    <button class="uploaded-file-remove" data-id="{{ hash_encode($uploadedFile->id) }}"
                        data-delete-link="{{ route('workspace.items.files.delete', [hash_encode($category->id), hash_encode($uploadedFile->id)]) }}">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </div>
            @endforeach
        </div>
        <div id="dropzone-wrapper" class="dropzone-container">
            <div class="dropzone-box">
                <div class="dropzone-box-cont">
                    <div class="dropzone-files">
                        <div class="dropzone-files-container">
                            <div id="dropzone" class="dropzone"></div>
                        </div>
                        <div id="upload-previews">
                            <div class="dz-preview dz-file-preview">
                                <div class="dz-fileicon">
                                    <img data-dz-thumbnail />
                                    <span class="vi vi-file" data-dz-extension></span>
                                </div>
                                <div class="dz-preview-content">
                                    <div class="dz-details">
                                        <div class="dz-details-info">
                                            <div class="dz-filename">
                                                <div class="dz-success-mark">
                                                    <span><i class="far fa-check-circle"></i></span>
                                                </div>
                                                <div class="dz-error-mark">
                                                    <span><i class="far fa-times-circle"></i></span>
                                                </div>
                                                <span data-dz-name></span>
                                                <div class="dz-size ms-1"></div>
                                            </div>
                                            <div class="dz-upload-percentage"></div>
                                        </div>
                                        <a class="dz-remove" data-dz-remove>
                                            <i class="fas fa-times fa-lg"></i>
                                        </a>
                                    </div>
                                    <div class="dz-progress">
                                        <span class="dz-upload" data-dz-uploadprogress></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dropzone-wrapper">
                <div class="dropzone-drag" data-dz-click>
                    <div class="dropzone-drag-inner">
                        <div class="dropzone-drag-icon">
                            <i class="fas fa-plus fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="dropzone-drag-title">
                                {{ translate('Drop files here to upload') }}</h6>
                            <p class="text-muted mb-0 small">
                                {{ translate(
                                    'Drag and drop or click here to upload, allowed types (:types) and max file size is :max_file_size',
                                    [
                                        'types' => strtoupper($category->getAllowedUploadFileTypes()),
                                        'max_file_size' => formatBytes(@$settings->item->max_file_size),
                                    ],
                                ) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-12">
                <label class="form-label">{{ translate('Thumbnail') }}</label>
                <select name="thumbnail" class="selectpicker-md selectpicker item-files-select" title="--">
                    @foreach ($uploadedFiles as $uploadedFile)
                        <option value="{{ hash_encode($uploadedFile->id) }}" @selected(old('thumbnail') == hash_encode($uploadedFile->id))>
                            {{ $uploadedFile->getShortName() }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">
                    {{ translate(':dimensions thumbnail (.JPG or .PNG)', ['dimensions' => $category->thumbnail_width . 'x' . $category->thumbnail_height]) }}
                </div>
            </div>
            @if (!$category->isFileTypeFileWithAudioPreview())
                <div class="col-12">
                    <label class="form-label">{{ translate('Preview Image') }}</label>
                    <select name="preview_image" class="selectpicker-md selectpicker item-files-select" title="--">
                        @foreach ($uploadedFiles as $uploadedFile)
                            <option value="{{ hash_encode($uploadedFile->id) }}" @selected(old('preview_image') == hash_encode($uploadedFile->id))>
                                {{ $uploadedFile->getShortName() }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        {{ translate(':dimensions preview image (.JPG or .PNG)', ['dimensions' => $category->preview_image_width . 'x' . $category->preview_image_height]) }}
                    </div>
                </div>
            @endif
            @if ($category->isFileTypeFileWithVideoPreview())
                <div class="col-12">
                    <label class="form-label">{{ translate('Video Preview') }}</label>
                    <select name="preview_video" class="selectpicker-md selectpicker item-files-select" title="--">
                        @foreach ($uploadedFiles as $uploadedFile)
                            <option value="{{ hash_encode($uploadedFile->id) }}" @selected(old('preview_video') == hash_encode($uploadedFile->id))>
                                {{ $uploadedFile->getShortName() }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        {{ translate('Video preview (.MP4 or .WEBM) in max size :file_size', ['file_size' => formatBytes($category->max_preview_file_size)]) }}
                    </div>
                </div>
            @elseif($category->isFileTypeFileWithAudioPreview())
                <div class="col-12">
                    <label class="form-label">{{ translate('Audio Preview') }}</label>
                    <select name="preview_audio" class="selectpicker-md selectpicker item-files-select" title="--">
                        @foreach ($uploadedFiles as $uploadedFile)
                            <option value="{{ hash_encode($uploadedFile->id) }}" @selected(old('preview_audio') == hash_encode($uploadedFile->id))>
                                {{ $uploadedFile->getShortName() }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        {{ translate('Audio preview (.MP3 or .WAV) in max size :file_size', ['file_size' => formatBytes($category->max_preview_file_size)]) }}
                    </div>
                </div>
            @endif
            @if ($settings->item->external_file_link_option)
                <div class="col-12">
                    <label class="form-label">{{ translate('Main File') }}</label>
                    <div class="form-group">
                        <select id="mainFileSource" name="main_file_source"
                            class="form-select form-select-md first-input">
                            <option value="0">{{ translate('Upload') }}</option>
                            <option value="1">{{ translate('External') }}</option>
                        </select>
                        <select id="mainFileSource" name="main_file"
                            class="selectpicker-md selectpicker second-input item-files-select main-file-source-1"
                            title="--">
                            @foreach ($uploadedFiles as $uploadedFile)
                                <option value="{{ hash_encode($uploadedFile->id) }}" @selected(old('main_file') == hash_encode($uploadedFile->id))>
                                    {{ $uploadedFile->getShortName() }}
                                </option>
                            @endforeach
                        </select>
                        <input type="url" name="main_file"
                            class="form-control form-control-md second-input main-file-source-2 d-none"
                            value="{{ old('main_file') }}" placeholder="https://www.example.com/file.zip" disabled>
                    </div>
                    <div class="form-text main-file-source-1">
                        @php
                            $fileTypesArray = explode(',', $category->main_file_types);
                            $fileTypesArray = array_map(function ($type) {
                                return '.' . trim($type);
                            }, $fileTypesArray);
                            $types = implode(', ', $fileTypesArray);
                        @endphp
                        {{ translate('Item files that will buyers download (:types).', ['types' => strtoupper($types)]) }}
                    </div>
                    <div class="form-text d-none main-file-source-2">
                        {{ translate('Enter the external URL where the buyer will be redirected to download the file.') }}
                    </div>
                </div>
            @else
                <div class="col-12">
                    <label class="form-label">{{ translate('Main File') }}</label>
                    <select name="main_file" class="selectpicker-md selectpicker second-input item-files-select"
                        title="--">
                        @foreach ($uploadedFiles as $uploadedFile)
                            <option value="{{ hash_encode($uploadedFile->id) }}" @selected(old('main_file') == hash_encode($uploadedFile->id))>
                                {{ $uploadedFile->getShortName() }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        @php
                            $fileTypesArray = explode(',', $category->main_file_types);
                            $fileTypesArray = array_map(function ($type) {
                                return '.' . trim($type);
                            }, $fileTypesArray);
                            $types = implode(', ', $fileTypesArray);
                        @endphp
                        {{ translate('Item files that will buyers download (:types).', ['types' => strtoupper($types)]) }}
                    </div>
                </div>
            @endif
            @if ($category->isFileTypeFileWithImagePreview())
                <div class="col-12">
                    <label class="form-label">{{ translate('Screenshots (Optional)') }}</label>
                    <select name="screenshots[]" class="selectpicker-md selectpicker item-files-select"
                        title="--" multiple>
                        @foreach ($uploadedFiles as $uploadedFile)
                            <option value="{{ hash_encode($uploadedFile->id) }}" @selected(old('screenshots') ? in_array(hash_encode($uploadedFile->id), old('screenshots')) : false)>
                                {{ $uploadedFile->getShortName() }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        {{ translate('Item screenshots images (.JPG or .PNG) and maximum :maximum screenshots', ['maximum' => @$settings->item->maximum_screenshots]) }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@push('top_scripts')
    @include('themes.basic.workspace.partials.dropzone-options')
    <script>
        "use strict";
        const uploadConfig = {!! json_encode([
            'upload_url' => route('workspace.items.upload', hash_encode($category->id)),
            'max_files' => intval(@$settings->item->max_files - $uploadedFiles->count()),
            'max_file_size' => intval(@$settings->item->max_file_size),
            'allowed_types' => $category->getAllowedUploadFileTypes(),
            'translates' => [
                'format_bytes' => [translate('B'), translate('KB'), translate('MB'), translate('GB'), translate('TB')],
                'errors' => [
                    'max_files_exceeded' => translate('You can not upload any more files.'),
                    'file_duplicate' => translate('You cannot attach the same file twice'),
                    'file_empty' => translate('Empty files cannot be uploaded'),
                    'max_file_size_exceeded' => translate('File is too big, Max file size :max_file_size', [
                        'max_file_size' => formatBytes(intval(@$settings->item->max_file_size)),
                    ]),
                ],
            ],
        ]) !!};
    </script>
@endpush
@push('styles_libs')
    <link rel="stylesheet" href="{{ asset('vendor/libs/vironeer/vironeer-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/libs/dropzone/dropzone.min.css') }}">
@endpush
@push('scripts_libs')
    <script src="{{ asset('vendor/libs/dropzone/dropzone.min.js') }}"></script>
@endpush
