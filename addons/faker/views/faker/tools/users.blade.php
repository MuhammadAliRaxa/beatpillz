@extends('admin.layouts.grid')
@section('section', translate('Faker'))
@section('title', translate('Fake Users'))
@section('container', 'container-max-lg')
@section('back', route('admin.faker.tools.index'))
@section('content')
    <form action="{{ route('admin.faker.tools.generate', $tool) }}" method="POST">
        @csrf
        <div class="card mb-3">
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label">{{ translate('Number of users') }}</label>
                    <input type="number" name="users_number" class="form-control form-control-md" value="10">
                </div>
                <button class="btn btn-primary btn-md"><i
                        class="fa-solid fa-rotate me-2"></i>{{ translate('Generate') }}</button>
            </div>
        </div>
    </form>
@endsection
