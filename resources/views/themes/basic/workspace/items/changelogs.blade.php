@extends('themes.basic.workspace.layouts.app')
@section('section', translate('Changelogs'))
@section('title', $item->name)
@section('back', route('workspace.items.index'))
@section('breadcrumbs', Breadcrumbs::render('workspace.items.changelogs.index', $item))
@section('content')
    <div class="dashboard-tabs">
        @include('themes.basic.workspace.items.includes.tabs-nav')
        <div class="dashboard-tabs-content">
            <div class="row g-3">
                <div class="col-lg-7">
                    @if ($item->isApproved())
                        <div class="row g-3 row-cols-1">
                            <div class="col">
                                <div class="dashboard-card card-v p-0">
                                    <div class="card-v-header border-bottom py-3 px-4">
                                        <h5 class="mb-0">{{ translate('Add New Log') }}</h5>
                                    </div>
                                    <div class="card-v-body p-4">
                                        <form action="{{ route('workspace.items.changelogs.store', $item->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">{{ translate('Version') }}</label>
                                                <input type="text" name="version" class="form-control form-control-md"
                                                    value="{{ old('version') }}"
                                                    placeholder="{{ translate('1.0 or 1.0.0') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">{{ translate('Body') }}</label>
                                                <textarea name="body" class="form-control" rows="6" required>{{ old('body') }}</textarea>
                                            </div>
                                            <button
                                                class="btn btn-primary btn-md action-confirm">{{ translate('Submit') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @foreach ($changelogs as $changelog)
                                <div class="col">
                                    <div class="dashboard-card card-v">
                                        <div class="mb-3">
                                            <div class="row align-items-center g-3">
                                                <div class="col">
                                                    <h5 class="mb-0">v{{ $changelog->version }} -
                                                        {{ dateFormat($changelog->created_at) }}</h5>
                                                </div>
                                                <div class="col-auto">
                                                    <form
                                                        action="{{ route('workspace.items.changelogs.delete', [$item->id, $changelog->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            class="btn btn-outline-danger btn-padding btn-sm action-confirm"><i
                                                                class="fa-regular fa-trash-can"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="changelogs">
                                            <pre>{{ $changelog->body }}</pre>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{ $changelogs->links() }}
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fa-regular fa-circle-question me-2"></i>
                            <span>{{ translate('This option is not available for unapproved items') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-lg-5">
                    @include('themes.basic.workspace.items.includes.sidebar')
                </div>
            </div>
        </div>
    </div>
@endsection
