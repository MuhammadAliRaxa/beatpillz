@extends('admin.layouts.form')
@section('section', translate('Sub Categories'))
@section('title', $subCategory->name)
@section('back', route('admin.categories.sub-categories.index'))
@section('container', 'container-max-lg')
@section('content')
    <div class="mb-3">
        <a class="btn btn-outline-secondary" href="{{ $subCategory->getLink() }}" target="_blank"><i
                class="fa fa-eye me-2"></i>{{ translate('View') }}</a>
    </div>
    <form id="vironeer-submited-form" action="{{ route('admin.categories.sub-categories.update', $subCategory->id) }}"
        method="POST">
        @csrf
        @method('PUT')
        <div class="card mb-3">
            <div class="card-header">{{ translate('Sub Category') }}</div>
            <div class="card-body p-3">
                <div class="row g-3 row-cols-1 mb-3">
                    <div class="col">
                        <label class="form-label">{{ translate('Category') }} </label>
                        <select class="form-select" disabled>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($category->id == $subCategory->category->id)>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Name') }} </label>
                        <input type="text" name="name" class="form-control" value="{{ $subCategory->name }}"
                            required />
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Slug') }} </label>
                        <input type="text" name="slug" id="show_slug" class="form-control"
                            value="{{ $subCategory->slug }}" required />
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
                        <input type="text" name="title" class="form-control" value="{{ $subCategory->title }}"
                            maxlength="70" />
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Description') }} </label>
                        <textarea type="text" name="description" class="form-control" rows="6" maxlength="150" />{{ $subCategory->description }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
