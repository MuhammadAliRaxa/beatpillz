@extends('admin.layouts.form')
@section('section', translate('Financial'))
@section('title', translate('New Withdrawal Method'))
@section('container', 'container-max-lg')
@section('back', route('admin.financial.withdrawal-methods.index'))
@section('content')
    <div class="card">
        <div class="card-body p-4">
            <form id="vironeer-submited-form" action="{{ route('admin.financial.withdrawal-methods.store') }}" method="POST">
                @csrf
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('Name') }}
                        </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ translate('Status') }} </label>
                        <input type="checkbox" name="status" data-toggle="toggle" checked>
                    </div>
                </div>
                <div class="mb-3">
                    @include('admin.partials.input-price', [
                        'label' => translate('Minimum Withdrawal Amount'),
                        'name' => 'minimum',
                        'integer' => true,
                    ])
                </div>
                <div class="mb-2 ckeditor-sm">
                    <label class="form-label">{{ translate('Description') }}</label>
                    <textarea name="description" class="ckeditor">{{ old('description') }}</textarea>
                </div>
            </form>
        </div>
    </div>
    @include('admin.partials.ckeditor')
@endsection
