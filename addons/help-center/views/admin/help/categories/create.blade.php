@extends('admin.layouts.form')
@section('section', translate('Help Center Categories'))
@section('title', translate('New Help Center Category'))
@section('back', route('admin.help.categories.index'))
@section('container', 'container-max-md')
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.help.categories.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body p-4">
                <div class="row g-3 row-cols-1 mb-2">
                    <div class="col">
                        <label class="form-label">{{ translate('Name') }} </label>
                        <input type="text" name="name" id="create_slug" class="form-control" value="{{ old('name') }}"
                            required autofocus />
                    </div>
                    <div class="col">
                        <label class="form-label">{{ translate('Slug') }} </label>
                        <input type="text" name="slug" id="show_slug" class="form-control" value="{{ old('slug') }}"
                            required />
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('top_scripts')
        <script>
            "use strict";
            let GET_SLUG_URL = "{{ route('admin.help.categories.slug') }}";
        </script>
    @endpush
@endsection
