@extends('admin.layouts.form')
@section('section', translate('Help Center Articles'))
@section('title', translate('Edit Help Center Article'))
@section('back', route('admin.help.articles.index'))
@section('content')
    <div class="mb-3">
        <a class="btn btn-outline-secondary" href="{{ $article->getLink() }}" target="_blank"><i
                class="fa fa-eye me-2"></i>{{ translate('View') }}</a>
    </div>
    <form id="vironeer-submited-form" action="{{ route('admin.help.articles.update', $article->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="card p-2 mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ translate('Title') }} </label>
                            <input type="text" name="title" class="form-control" value="{{ $article->title }}"
                                required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ translate('Slug') }} </label>
                            <input type="text" name="slug" class="form-control" value="{{ $article->slug }}"
                                required />
                        </div>
                        <div class="ckeditor-lg mb-0">
                            <label class="form-label">{{ translate('Body') }} </label>
                            <textarea name="body" rows="10" class="form-control ckeditor">{{ $article->body }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card p-2 mb-3 h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ translate('Category') }} </label>
                            <select name="category" class="form-select selectpicker" data-live-search="true"
                                title="{{ translate('Choose') }}" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($article->category->id == $category->id)>{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ translate('Short description') }} </label>
                            <textarea name="short_description" rows="6" class="form-control"
                                placeholder="{{ translate('50 to 200 character at most') }}" required>{{ $article->short_description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
    @include('admin.partials.ckeditor')
@endsection
