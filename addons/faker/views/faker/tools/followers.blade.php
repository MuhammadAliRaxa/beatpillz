@extends('admin.layouts.grid')
@section('section', translate('Faker'))
@section('title', translate('Fake Followers'))
@section('container', 'container-max-lg')
@section('back', route('admin.faker.tools.index'))
@section('content')
    <div class="alert alert-primary">
        <h5>{{ translate('Notes!') }}</h5>
        <ol class="m-0">
            <li>
                {!! translate('You must have an active users to use this tool or you can use :tool to generate them.', [
                    'tool' => '<a href="' . route('admin.faker.tools.tool', 'users') . '">' . translate('fake users tool') . '</a>',
                ]) !!}
            </li>
        </ol>
    </div>
    <div class="card mb-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.faker.tools.generate', $tool) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ translate('User') }}</label>
                        <select name="user" class="form-select form-select-md selectpicker" title="--"
                            data-live-search="true" required>
                            @foreach (\App\Models\User::active()->get() as $user)
                                <option value="{{ $user->id }}" @selected(old('item') == $user->id)>
                                    {{ $user->username . ' (' . demo($user->email) . ')' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ translate('Number of followers') }}</label>
                        <input type="number" name="followers_number" class="form-control form-control-md"
                            value="{{ old('followers_number') ?? '10' }}">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary btn-md"><i
                                class="fa-solid fa-rotate me-2"></i>{{ translate('Generate') }}</button>
                    </div>
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
