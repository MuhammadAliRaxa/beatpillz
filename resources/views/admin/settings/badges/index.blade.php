@extends('admin.layouts.grid')
@section('section', translate('Settings'))
@section('title', translate('Badges'))
@section('create', route('admin.settings.badges.create'))
@section('content')
    <div class="card">
        <div class="card-header p-3 border-bottom-small">
            <form action="{{ request()->url() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-lg-7">
                        <input type="text" name="search" class="form-control" placeholder="{{ translate('Search...') }}"
                            value="{{ request()->input('search') ?? '' }}">
                    </div>
                    <div class="col-12 col-lg-3">
                        <select name="type" class="form-select selectpicker" title="{{ translate('Type') }}"
                            data-live-search="true">
                            <option value="none" @selected(request('type') == 'none')>
                                {{ translate('None type') }}
                            </option>
                            <option value="countries" @selected(request('type') == 'countries')>
                                {{ translate('Countries') }}
                            </option>
                            <option value="author_levels" @selected(request('type') == 'author_levels')>
                                {{ translate('Author levels') }}
                            </option>
                            <option value="membership_years" @selected(request('type') == 'membership_years')>
                                {{ translate('Membership years') }}
                            </option>
                        </select>
                    </div>
                    <div class="col">
                        <button class="btn btn-primary w-100"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="col">
                        <a href="{{ url()->current() }}" class="btn btn-secondary w-100">{{ translate('Reset') }}</a>
                    </div>
                </div>
            </form>
        </div>
        <div>
            <div class="table-responsive">
                <table class="vironeer-normal-table table w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('badge') }}</th>
                            <th>{{ translate('Name') }}</th>
                            <th>{{ translate('Title') }}</th>
                            <th class="text-center">{{ translate('Created date') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($badges as $badge)
                            <tr>
                                <td>{{ $badge->id }}</td>
                                <td>
                                    <a href="{{ route('admin.settings.badges.edit', $badge->id) }}" class="text-dark">
                                        <img src="{{ $badge->getImageLink() }}" alt="{{ $badge->title }}" width="40px"
                                            height="40px">
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.settings.badges.edit', $badge->id) }}" class="text-dark">
                                        {{ $badge->name }}
                                    </a>
                                </td>
                                <td>{{ $badge->title ?? '--' }}</td>
                                <td class="text-center">{{ dateFormat($badge->created_at) }}</td>
                                <td>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                            aria-expanded="true">
                                            <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.settings.badges.edit', $badge->id) }}"><i
                                                        class="fa-regular fa-pen-to-square me-2"></i>{{ translate('Edit') }}</a>
                                            </li>
                                            @if (!$badge->is_permanent)
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.settings.badges.destroy', $badge->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <button class="action-confirm dropdown-item text-danger"><i
                                                                class="far fa-trash-alt me-2"></i>{{ translate('Delete') }}</button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <span class="text-muted">{{ translate('No Badge Found') }}</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $badges->links() }}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
