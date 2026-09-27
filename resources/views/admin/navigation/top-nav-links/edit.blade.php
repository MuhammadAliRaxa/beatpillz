@extends('admin.layouts.form')
@section('section', translate('Navigation'))
@section('title', translate('Edit Top Nav link'))
@section('back', route('admin.navigation.top-nav-links.index'))
@section('container', 'container-max-md')
@section('content')
    <div class="card">
        <div class="card-body p-4">
            <form id="vironeer-submited-form" action="{{ route('admin.navigation.top-nav-links.update', $topNavLink->id) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">{{ translate('Name') }} </label>
                    <input type="text" name="name" class="form-control form-control-lg" value="{{ $topNavLink->name }}"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ translate('Link Type') }} </label>
                    <select name="link_type" class="form-select form-select-lg">
                        <option value="1" @selected($topNavLink->isInternal())>{{ translate('Internal') }}</option>
                        <option value="2" @selected($topNavLink->isExternal())>{{ translate('External') }}</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">{{ translate('Link') }} </label>
                    <input type="link" name="link" class="form-control form-control-lg" placeholder="/"
                        value="{{ $topNavLink->link }}" required>
                </div>
            </form>
        </div>
    </div>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
