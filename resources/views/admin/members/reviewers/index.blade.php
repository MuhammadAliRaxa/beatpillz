@extends('admin.layouts.grid')
@section('section', translate('Members'))
@section('title', translate('Reviewers'))
@section('create', route('admin.members.reviewers.create'))
@section('container', 'container-max-xxl')
@section('content')
    <div class="card">
        <div class="card-header p-3 border-bottom-small">
            <form action="{{ request()->url() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-lg-10">
                        <input type="text" name="search" class="form-control" placeholder="{{ translate('Search...') }}"
                            value="{{ request()->input('search') ?? '' }}">
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
            @if ($reviewers->count() > 0)
                <div class="table-responsive">
                    <table class="vironeer-normal-table table w-100">
                        <thead>
                            <tr>
                                <th class="tb-w-3x">#</th>
                                <th class="tb-w-20x">{{ translate('Reviewer details') }}</th>
                                <th class="tb-w-7x">{{ translate('Categories') }}</th>
                                <th class="tb-w-3x text-center">{{ translate('Added date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviewers as $reviewer)
                                <tr>
                                    <td>{{ $reviewer->id }}</td>
                                    <td>
                                        <div class="vironeer-user-box">
                                            <a class="vironeer-user-avatar"
                                                href="{{ route('admin.members.reviewers.edit', $reviewer->id) }}">
                                                <img src="{{ $reviewer->getAvatar() }}" class="rounded-3"
                                                    alt="{{ $reviewer->getName() }}" />
                                            </a>
                                            <div>
                                                <a class="text-reset"
                                                    href="{{ route('admin.members.reviewers.edit', $reviewer->id) }}">{{ $reviewer->getName() }}</a>
                                                <p class="text-muted mb-0">{{ demo($reviewer->email) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-lg-10">
                                            <div class="row g-2">
                                                @foreach ($reviewer->categories as $category)
                                                    <div class="col-auto">
                                                        <a href="{{ route('admin.categories.edit', $category->id) }}">
                                                            <div class="badge bg-primary">{{ $category->name }}</div>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ dateFormat($reviewer->created_at) }}</td>
                                    <td>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                                aria-expanded="true">
                                                <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-sm-end"
                                                data-popper-placement="bottom-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.members.reviewers.edit', $reviewer->id) }}">
                                                        <i class="fa-solid fa-desktop me-1"></i>
                                                        {{ translate('View Details') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.members.reviewers.login', $reviewer->id) }}"
                                                        target="_blank">
                                                        <i class="fa-solid fa-arrow-right-to-bracket me-1"></i>
                                                        {{ translate('Login as Reviewer') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('admin.members.reviewers.destroy', $reviewer->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <button class="action-confirm dropdown-item text-danger">
                                                            <i class="far fa-trash-alt me-1"></i>
                                                            {{ translate('Delete') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                @include('admin.partials.empty', ['empty_classes' => 'empty-lg'])
            @endif
        </div>
    </div>
    {{ $reviewers->links() }}
@endsection
