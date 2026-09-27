@extends('themes.basic.workspace.layouts.app')
@section('section', translate('Tickets'))
@section('title', translate('New Ticket'))
@section('breadcrumbs', Breadcrumbs::render('workspace.tickets.create'))
@section('back', route('workspace.tickets.index'))
@section('container', 'dashboard-container-sm')
@section('content')
    <div class="card-v">
        <form action="{{ route('workspace.tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <label class="form-label">{{ translate('Subject') }}</label>
                    <input type="text" name="subject" class="form-control form-control-md" value="{{ old('subject') }}"
                        autofocus required>
                </div>
                <div class="col-lg-12">
                    <label class="form-label">{{ translate('Category') }}</label>
                    <select name="category" class="form-select form-select-md" required>
                        <option value="" disabled selected>{{ translate('Choose') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-12">
                    <label class="form-label">{{ translate('Description') }}</label>
                    <textarea name="description" class="form-control" rows="10" required>{{ old('description') }}</textarea>
                </div>
                <div class="col-lg-12">
                    <div class="attachments">
                        <div class="attachment-box-1">
                            <label class="form-label">
                                {{ translate('Attachments') }}
                                ({{ @$settings->ticket->file_types }})
                            </label>
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control form-control-md">
                                <button id="addAttachment" class="btn btn-outline-secondary" type="button">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary btn-md">{{ translate('Submit') }}</button>
        </form>
    </div>
    @push('top_scripts')
        <script>
            "use strict";
            const ticketsConfig = {!! json_encode([
                'max_file' => @$settings->ticket->max_files,
                'max_files_error' => translate('Max :max files can be uploaded', [
                    'max' => @$settings->ticket->max_files,
                ]),
            ]) !!}
        </script>
    @endpush
@endsection
