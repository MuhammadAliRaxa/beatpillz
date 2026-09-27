@extends('admin.layouts.form')
@section('section', translate('Reviewers'))
@section('title', translate(':name Actions', ['name' => $reviewer->getName()]))
@section('back', route('admin.members.reviewers.index'))
@section('content')
    <div class="row g-3">
        @include('admin.members.reviewers.includes.sidebar')
        <div class="col-lg-9">
            <div class="card h-100">
                <div class="card-header">{{ translate('Actions') }}</div>
                <div class="card-body p-4">
                    <form id="vironeer-submited-form"
                        action="{{ route('admin.members.reviewers.actions.update', $reviewer->id) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <label class="form-label">{{ translate('Two-Factor Authentication') }} </label>
                                <input id="2faCheckbox" type="checkbox" name="google2fa_status" data-toggle="toggle"
                                    data-on="{{ translate('Active') }}" data-off="{{ translate('Disabled') }}"
                                    {{ $reviewer->has2fa() ? 'checked' : '' }}>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
