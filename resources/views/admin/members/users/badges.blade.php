@extends('admin.layouts.grid')
@section('title', translate(':name Badges', ['name' => $user->getName()]))
@section('back', route('admin.members.users.index'))
@section('content')
    @include('admin.members.users.includes.elements')
    <div class="row g-3">
        @include('admin.members.users.includes.sidebar')
        <div class="col-lg-9">
            <div class="text-end">
                <button class="btn btn-primary btn-md mb-3" data-bs-toggle="modal" data-bs-target="#addBadgeModal">
                    <i class="fa fa-plus me-1"></i>
                    {{ translate('Add Badge') }}
                </button>
            </div>
            @if ($userBadges->count() > 0)
                <div class="card mb-3">
                    <ul class="sortable-list custom-list-group list-group list-group-flush">
                        @foreach ($userBadges as $userBadge)
                            <li class="list-group-item" data-id="{{ $userBadge->id }}">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <span class="sortable-list-handle text-muted"><i
                                                class="fas fa-arrows-alt fa-lg"></i></span>
                                    </div>
                                    <div class="col">
                                        <div class="item-title">
                                            <img src="{{ $userBadge->badge->getImageLink() }}"
                                                alt="{{ $userBadge->badge->name }}" width="35px" height="35px">
                                            <span class="ms-1">
                                                {{ $userBadge->badge->name }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <form class="d-inline"
                                            action="{{ route('admin.members.users.badges.destroy', [$user->id, $userBadge->id]) }}"
                                            method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="action-confirm btn btn-danger btn-sm"><i
                                                    class="far fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        @include('admin.partials.empty', ['empty_classes' => 'empty-lg'])
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="addBadgeModal" tabindex="-1" aria-labelledby="addBadgeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addBadgeModalLabel">
                        {{ translate('Add badge to :username', ['username' => $user->getName()]) }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.members.users.badges.store', $user->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <select name="badge" class="form-select form-select-lg selectpicker"
                                title="{{ translate('Choose badge') }}" data-live-search="true" required>
                                @foreach ($badges as $badge)
                                    <option value="{{ $badge->id }}">{{ $badge->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary btn-md">{{ translate('Add badge') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('top_scripts')
        <script>
            const sortingRoute = "{{ route('admin.members.users.badges.sortable', $user->id) }}";
        </script>
    @endpush
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
        <link href="{{ asset('vendor/libs/jquery/jquery-ui.min.css') }}" />
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/jquery/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
