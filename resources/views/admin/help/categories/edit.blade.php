@extends('admin.layouts.form')
@section('section', translate('Help Center Categories'))
@section('title', translate('Edit Help Center Category'))
@section('back', route('admin.help.categories.index'))
@section('container', 'container-max-md')
@section('content')
    <div class="mb-3">
        <a class="btn btn-outline-secondary" href="{{ $category->getLink() }}" target="_blank"><i
                class="fa fa-eye me-2"></i>{{ translate('View') }}</a>
    </div>
    <form id="vironeer-submited-form" action="{{ route('admin.help.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body p-4">
                <div class="row g-3 row-cols-1 mb-2">
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
    </form>
@endsection
