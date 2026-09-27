@extends('admin.layouts.grid')
@section('section', translate('Financial'))
@section('title', translate('Withdrawal Methods'))
@section('container', 'container-max-lg')
@section('create', route('admin.financial.withdrawal-methods.create'))
@section('content')
    @if ($withdrawalMethods->count() > 0)
        <div class="card mb-3">
            <ul class="sortable-list custom-list-group list-group list-group-flush">
                @foreach ($withdrawalMethods as $withdrawalMethod)
                    <li class="list-group-item" data-id="{{ $withdrawalMethod->id }}">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <span class="sortable-list-handle text-muted">
                                    <i class="fas fa-arrows-alt fa-lg"></i>
                                </span>
                            </div>
                            <div class="col">
                                <a href="{{ route('admin.financial.withdrawal-methods.edit', $withdrawalMethod->id) }}"
                                    class="text-dark">
                                    <h5 class="m-0">
                                        <span>{{ $withdrawalMethod->name }}</span>
                                    </h5>
                                </a>
                            </div>
                            <div class="col-auto">
                                <div class="buttons">
                                    <div class="row g-3 align-items-center">
                                        <div class="col">
                                            @if ($withdrawalMethod->isActive())
                                                <span class="badge bg-success">{{ translate('Active') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ translate('Disabled') }}</span>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <a href="{{ route('admin.financial.withdrawal-methods.edit', $withdrawalMethod->id) }}"
                                                class="btn btn-secondary btn-sm">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <form
                                                action="{{ route('admin.financial.withdrawal-methods.destroy', $withdrawalMethod->id) }}"
                                                method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button class="action-confirm btn btn-danger btn-sm">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
    @push('top_scripts')
        <script>
            const sortingRoute = "{{ route('admin.financial.withdrawal-methods.sortable') }}";
        </script>
    @endpush
    @push('styles_libs')
        <link href="{{ asset('vendor/libs/jquery/jquery-ui.min.css') }}" />
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/jquery/jquery-ui.min.js') }}"></script>
    @endpush
@endsection
