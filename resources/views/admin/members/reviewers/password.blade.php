@extends('admin.layouts.form')
@section('section', translate('Reviewers'))
@section('title', translate(':name Password', ['name' => $reviewer->getName()]))
@section('back', route('admin.members.reviewers.index'))
@section('content')
    <div class="row g-3">
        @include('admin.members.reviewers.includes.sidebar')
        <div class="col-lg-9">
            <div class="card h-100">
                <div class="card-header">{{ translate('Password') }}</div>
                <div class="card-body p-4">
                    <form id="vironeer-submited-form"
                        action="{{ route('admin.members.reviewers.password.update', $reviewer->id) }}" method="POST">
                        @csrf
                        <div class="row g-3 mb-2">
                            <div class="col-12">
                                <label class="form-label">{{ translate('New Password') }} </label>
                                <input type="password" class="form-control  form-control-lg" name="new-password" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ translate('Confirm New Password') }} </label>
                                <input type="password" class="form-control  form-control-lg"
                                    name="new-password_confirmation" required>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
