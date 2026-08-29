@extends('reviewer.layouts.app')
@section('title', $pageTitle)
@section('content')
    <div class="position-relative">
        <div class="table-search">
            <form action="{{ url()->current() }}" method="GET">
                <div class="row g-3 aligs-items-center">
                    <div class="col-lg-7">
                        <input type="text" name="search" placeholder="{{ translate('Search...') }}"
                            class="form-control form-control-md" value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-3">
                        <select name="category" class="selectpicker selectpicker-md" title="{{ translate('Category') }}"
                            data-live-search="true">
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" @selected(request('category') == $category->slug)>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <button class="btn btn-primary w-100 btn-md"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="col">
                        <a href="{{ url()->current() }}" class="btn btn-outline-primary w-100 btn-md"><i
                                class="fa-solid fa-rotate"></i></a>
                    </div>
                </div>
            </form>
        </div>
        <div class="dashboard-table">
            <div class="table-container">
                <table class="table align-middle text-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-start">{{ translate('ID') }}</th>
                            <th class="text-start">{{ translate('Details') }}</th>
                            <th class="text-center">{{ translate('Category') }}</th>
                            <th>{{ translate('Published Date') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th class="text-center">{{ translate('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td class="text-start">
                                    <a href="{{ route('reviewer.items.review', $item->id) }}">
                                        <i class="fa-solid fa-hashtag me-1"></i>{{ $item->id }}
                                    </a>
                                </td>
                                <td class="text-start">
                                    <div class="d-flex align-items-center">
                                        <div class="item-img item-img-xs me-3">
                                            <a href="{{ route('reviewer.items.review', $item->id) }}">
                                                <img src="{{ $item->getThumbnailLink() }}" alt="{{ $item->name }}">
                                            </a>
                                        </div>
                                        <div>
                                            <div class="table-name text-dark d-block mb-1">
                                                <a href="{{ route('reviewer.items.review', $item->id) }}"
                                                    class="text-dark">
                                                    {{ $item->name }}
                                                </a>
                                            </div>
                                            <span class="text-muted small">
                                                {!! translate('Author :username', [
                                                    'username' =>
                                                        '<a href="' . $item->author->getProfileLink() . '" target="_blank">' . $item->author->username . '</a>',
                                                ]) !!}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb justify-content-center m-0">
                                            <li class="breadcrumb-item">
                                                <a href="{{ route('categories.category', $item->category->slug) }}"
                                                    target="_blank">{{ $item->category->name }}</a>
                                            </li>
                                            @if ($item->subCategory)
                                                <li class="breadcrumb-item">
                                                    <a href="{{ route('categories.sub-category', [$item->category->slug, $item->subCategory->slug]) }}"
                                                        target="_blank">{{ $item->subCategory->name }}</a>
                                                </li>
                                            @endif
                                        </ol>
                                    </nav>
                                </td>
                                <td class="small">{{ dateFormat($item->created_at) }}</td>
                                <td>
                                    @if ($item->isPending())
                                        <div class="badge bg-orange rounded-2 fw-light px-3 py-2">
                                            {{ $item->getStatusName() }}
                                        </div>
                                    @elseif($item->isSoftRejected())
                                        <div class="badge bg-purple rounded-2 fw-light px-3 py-2">
                                            {{ $item->getStatusName() }}
                                        </div>
                                    @elseif($item->isResubmitted())
                                        <div class="badge bg-blue rounded-2 fw-light px-3 py-2">
                                            {{ $item->getStatusName() }}
                                        </div>
                                    @elseif($item->isApproved())
                                        <div class="badge bg-green rounded-2 fw-light px-3 py-2">
                                            {{ $item->getStatusName() }}
                                        </div>
                                    @elseif($item->isHardRejected())
                                        <div class="badge bg-red rounded-2 fw-light px-3 py-2">
                                            {{ $item->getStatusName() }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->isPending() || $item->isResubmitted())
                                        <a href="{{ route('reviewer.items.review', $item->id) }}"
                                            class="btn btn-outline-primary btn-padding me-1">
                                            <i class="fa-solid fa-desktop me-1"></i>
                                            {{ translate('Review') }}
                                        </a>
                                    @else
                                        <a href="{{ route('reviewer.items.review', $item->id) }}"
                                            class="btn btn-outline-primary btn-padding me-1">
                                            <i class="fa-solid fa-desktop me-1"></i>
                                            {{ translate('Details') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="text-muted p-4">{{ translate('No data found') }}</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $items->links() }}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
