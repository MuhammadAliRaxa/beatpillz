@extends('admin.layouts.grid')
@section('title', translate('Updated Items'))
@section('container', 'container-max-xxl')
@section('content')
    <div class="card">
        <div class="card-header p-3 border-bottom-small">
            <form action="{{ request()->url() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <input type="text" name="search" class="form-control" placeholder="{{ translate('Search...') }}"
                            value="{{ request('search') ?? '' }}">
                    </div>
                    <div class="col-12 col-lg-3">
                        <select name="category" class="form-select selectpicker" title="{{ translate('Category') }}"
                            data-live-search="true">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
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
            @if ($itemUpdates->count() > 0)
                <div class="overflow-hidden">
                    <div class="table-custom-container">
                        <table class="table-custom table">
                            <thead>
                                <tr class="bg-light">
                                    <th>{{ translate('ID') }}</th>
                                    <th>{{ translate('Details') }}</th>
                                    <th class="text-center">{{ translate('Author') }}</th>
                                    <th class="text-center">{{ translate('Submitted Date') }}</th>
                                    <th class="text-end">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($itemUpdates as $itemUpdate)
                                    @php
                                        $item = $itemUpdate->item;
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.items.updated.show', $itemUpdate->id) }}">
                                                <i class="fa-solid fa-hashtag"></i>
                                                {{ $itemUpdate->id }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="vironeer-item-box">
                                                <a class="vironeer-item-image"
                                                    href="{{ route('admin.items.updated.show', $itemUpdate->id) }}">
                                                    <img src="{{ $itemUpdate->getThumbnailLink() }}" class="rounded-3"
                                                        alt="{{ $itemUpdate->name }}">

                                                </a>
                                                <div>
                                                    <a class="text-reset"
                                                        href="{{ route('admin.items.updated.show', $itemUpdate->id) }}">{{ $itemUpdate->name }}</a>
                                                    <nav aria-label="breadcrumb">
                                                        <ol class="breadcrumb m-0 mt-2">
                                                            <li class="breadcrumb-item">
                                                                <a
                                                                    href="{{ route('admin.categories.edit', $item->category->id) }}">{{ $item->category->name }}</a>
                                                            </li>
                                                            @if ($item->subCategory)
                                                                <li class="breadcrumb-item">
                                                                    <a
                                                                        href="{{ route('admin.categories.sub-categories.edit', $item->subCategory->id) }}">{{ $item->subCategory->name }}</a>
                                                                </li>
                                                            @endif
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.members.users.edit', $itemUpdate->author->id) }}"
                                                class="text-dark">
                                                <i class="fa fa-user me-1"></i>
                                                {{ $itemUpdate->author->username }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{ dateFormat($itemUpdate->created_at) }}
                                        </td>
                                        <td>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-sm rounded-3"
                                                    data-bs-toggle="dropdown" aria-expanded="true">
                                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-sm-end"
                                                    data-popper-placement="bottom-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.items.updated.show', $itemUpdate->id) }}">
                                                            <i class="fa-solid fa-desktop me-1"></i>
                                                            {{ translate('Details') }}
                                                        </a>
                                                    </li>
                                                    @if ($itemUpdate->main_file)
                                                        <li>
                                                            @if ($itemUpdate->isMainFileExternal())
                                                                <a class="dropdown-item"
                                                                    href="{{ $itemUpdate->main_file }}" target="_blank">
                                                                    <i class="fa-solid fa-download me-1"></i>
                                                                    {{ translate('Download') }}
                                                                </a>
                                                            @else
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.items.updated.download', $itemUpdate->id) }}">
                                                                    <i class="fa-solid fa-download me-1"></i>
                                                                    {{ translate('Download') }}
                                                                </a>
                                                            @endif
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.items.show', $item->id) }}">
                                                            <i class="fa-solid fa-up-right-from-square me-1"></i>
                                                            {{ translate('Original Item') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider" />
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.items.updated.destroy', $itemUpdate->id) }}"
                                                            method="POST">
                                                            @csrf @method('DELETE')
                                                            <button class="action-confirm dropdown-item text-danger">
                                                                <i class="fa-solid fa-trash-can me-1"></i>
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
                </div>
            @else
                @include('admin.partials.empty', ['empty_classes' => 'empty-lg'])
            @endif
        </div>
    </div>
    {{ $itemUpdates->links() }}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
