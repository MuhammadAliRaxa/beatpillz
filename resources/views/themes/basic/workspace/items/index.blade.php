@extends('themes.basic.workspace.layouts.app')
@section('title', translate('My Items'))
@section('breadcrumbs', Breadcrumbs::render('workspace.items'))
@section('content')
    <div class="dashboard-card card-v p-0">
        @if ($items->count() > 0 || request()->input('search') || request()->input('category'))
            <div class="table-search p-4">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="row g-3 aligs-items-center">
                        <div class="col-12 col-lg-6 col-xxl-7">
                            <input type="text" name="search" placeholder="{{ translate('Search...') }}"
                                class="form-control form-control-md" value="{{ request('search') }}">
                        </div>
                        <div class="col-12 col-lg-3 col-xxl-3">
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
            <div class="overflow-hidden">
                <div class="table-container">
                    <table class="dashboard-table table text-center table-borderless align-middle">
                        <thead>
                            <tr>
                                <th class="text-start">{{ translate('Details') }}</th>
                                <th class="text-start">{{ translate('Price') }}</th>
                                <th>{{ translate('Published Date') }}</th>
                                @if (@$settings->item->adding_require_review)
                                    <th>{{ translate('Status') }}</th>
                                @endif
                                <th class="text-center">{{ translate('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td class="text-start">
                                        <div class="d-flex align-items-center">
                                            @if (!$item->isPending())
                                                <a href="{{ route('workspace.items.edit', $item->id) }}"
                                                    class="item-img item-img-sm me-3">
                                                    <img src="{{ $item->getThumbnailLink() }}" alt="{{ $item->name }}">
                                                </a>
                                            @else
                                                <div class="item-img item-img-sm me-3">
                                                    <img src="{{ $item->getThumbnailLink() }}" alt="{{ $item->name }}">
                                                </div>
                                            @endif
                                            <div>
                                                @if (!$item->isPending())
                                                    <a href="{{ route('workspace.items.edit', $item->id) }}"
                                                        class="table-name text-dark d-block">
                                                        {{ $item->name }}
                                                    </a>
                                                @else
                                                    <div class="table-name text-dark d-block">
                                                        {{ $item->name }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <nav aria-label="breadcrumb">
                                                        <ol class="breadcrumb mb-0 mt-2">
                                                            <li class="breadcrumb-item small">
                                                                <a href="{{ route('categories.category', $item->category->slug) }}"
                                                                    target="_blank">{{ $item->category->name }}</a>
                                                            </li>
                                                            @if ($item->subCategory)
                                                                <li class="breadcrumb-item small">
                                                                    <a href="{{ route('categories.sub-category', [$item->category->slug, $item->subCategory->slug]) }}"
                                                                        target="_blank">{{ $item->subCategory->name }}</a>
                                                                </li>
                                                            @endif
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        @if ($item->isFree())
                                            <div class="table-price">
                                                <div
                                                    class="row row-cols-auto align-items-center justify-content-between flex-nowrap">
                                                    <div class="col">
                                                        <h6 class="mb-0 text-dark small">
                                                            {{ translate('Free') }}
                                                        </h6>
                                                    </div>
                                                    <div class="col">
                                                        <div class="item-price small">
                                                            <span class="small text-dark">--</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($item->isPurchasingEnabled())
                                            <div class="table-price my-2">
                                                <div
                                                    class="row row-cols-auto align-items-center justify-content-between flex-nowrap">
                                                    <div class="col">
                                                        <h6 class="mb-0 text-dark small">
                                                            {{ translate('Regular') }}
                                                        </h6>
                                                    </div>
                                                    <div class="col">
                                                        <div class="item-price small">
                                                            @if ($item->isOnDiscount())
                                                                <span class="item-price-through small">
                                                                    {{ getAmount($item->getRegularPrice()) }}
                                                                </span>
                                                                <span class="item-price-number small">
                                                                    {{ getAmount($item->price->regular) }}
                                                                </span>
                                                            @else
                                                                <span class="small text-dark">
                                                                    {{ getAmount($item->getRegularPrice()) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-price">
                                                <div
                                                    class="row row-cols-auto align-items-center justify-content-between flex-nowrap">
                                                    <div class="col">
                                                        <h6 class="mb-0 text-dark small">
                                                            {{ translate('Extended') }}
                                                        </h6>
                                                    </div>
                                                    <div class="col">
                                                        <div class="item-price small">
                                                            @if ($item->isOnDiscount() && $item->isExtendedOnDiscount())
                                                                <span class="item-price-through small">
                                                                    {{ getAmount($item->getExtendedPrice()) }}
                                                                </span>
                                                                <span class="item-price-number small">
                                                                    {{ getAmount($item->price->extended) }}
                                                                </span>
                                                            @else
                                                                <span class="small text-dark">
                                                                    {{ getAmount($item->getExtendedPrice()) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="small">{{ dateFormat($item->created_at) }}</td>
                                    @if (@$settings->item->adding_require_review)
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
                                            @endif
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <div class="row row-cols-auto justify-content-center align-items-center g-2">
                                            @if (!$item->isPending())
                                                <div class="col">
                                                    <a href="{{ route('workspace.items.edit', $item->id) }}"
                                                        class="btn btn-primary btn-padding">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </a>
                                                </div>
                                            @endif
                                            @if ($item->isApproved())
                                                <div class="col">
                                                    <a href="{{ route('workspace.items.statistics', $item->id) }}"
                                                        class="btn btn-warning btn-padding">
                                                        <i class="fa-solid fa-chart-simple"></i>
                                                    </a>
                                                </div>
                                                <div class="col">
                                                    @if ($item->isMainFileExternal())
                                                        <a href="{{ $item->main_file }}" target="_blank"
                                                            class="btn btn-dark btn-padding"><i
                                                                class="fa fa-download"></i></a>
                                                    @else
                                                        <form action="{{ route('workspace.items.download', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button class="btn btn-dark btn-padding"><i
                                                                    class="fa fa-download"></i></button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="col">
                                                <form action="{{ route('workspace.items.destroy', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-padding action-confirm">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="text-muted p-4">{{ translate('No data found') }}</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="dashboard-card-empty pd">
                <div class="py-4">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="150px" height="150px"
                            viewBox="0 0 647.63626 632.17383" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <path
                                d="M687.3279,276.08691H512.81813a15.01828,15.01828,0,0,0-15,15v387.85l-2,.61005-42.81006,13.11a8.00676,8.00676,0,0,1-9.98974-5.31L315.678,271.39691a8.00313,8.00313,0,0,1,5.31006-9.99l65.97022-20.2,191.25-58.54,65.96972-20.2a7.98927,7.98927,0,0,1,9.99024,5.3l32.5498,106.32Z"
                                transform="translate(-276.18187 -133.91309)" fill="#f2f2f2" />
                            <path
                                d="M725.408,274.08691l-39.23-128.14a16.99368,16.99368,0,0,0-21.23-11.28l-92.75,28.39L380.95827,221.60693l-92.75,28.4a17.0152,17.0152,0,0,0-11.28028,21.23l134.08008,437.93a17.02661,17.02661,0,0,0,16.26026,12.03,16.78926,16.78926,0,0,0,4.96972-.75l63.58008-19.46,2-.62v-2.09l-2,.61-64.16992,19.65a15.01489,15.01489,0,0,1-18.73-9.95l-134.06983-437.94a14.97935,14.97935,0,0,1,9.94971-18.73l92.75-28.4,191.24024-58.54,92.75-28.4a15.15551,15.15551,0,0,1,4.40966-.66,15.01461,15.01461,0,0,1,14.32032,10.61l39.0498,127.56.62012,2h2.08008Z"
                                transform="translate(-276.18187 -133.91309)" fill="#3f3d56" />
                            <path
                                d="M398.86279,261.73389a9.0157,9.0157,0,0,1-8.61133-6.3667l-12.88037-42.07178a8.99884,8.99884,0,0,1,5.9712-11.24023l175.939-53.86377a9.00867,9.00867,0,0,1,11.24072,5.9707l12.88037,42.07227a9.01029,9.01029,0,0,1-5.9707,11.24072L401.49219,261.33887A8.976,8.976,0,0,1,398.86279,261.73389Z"
                                transform="translate(-276.18187 -133.91309)"
                                fill="{{ $themeSettings->colors->primary_color }}" />
                            <circle cx="190.15351" cy="24.95465" r="20"
                                fill="{{ $themeSettings->colors->primary_color }}" />
                            <circle cx="190.15351" cy="24.95465" r="12.66462" fill="#fff" />
                            <path
                                d="M878.81836,716.08691h-338a8.50981,8.50981,0,0,1-8.5-8.5v-405a8.50951,8.50951,0,0,1,8.5-8.5h338a8.50982,8.50982,0,0,1,8.5,8.5v405A8.51013,8.51013,0,0,1,878.81836,716.08691Z"
                                transform="translate(-276.18187 -133.91309)" fill="#e6e6e6" />
                            <path
                                d="M723.31813,274.08691h-210.5a17.02411,17.02411,0,0,0-17,17v407.8l2-.61v-407.19a15.01828,15.01828,0,0,1,15-15H723.93825Zm183.5,0h-394a17.02411,17.02411,0,0,0-17,17v458a17.0241,17.0241,0,0,0,17,17h394a17.0241,17.0241,0,0,0,17-17v-458A17.02411,17.02411,0,0,0,906.81813,274.08691Zm15,475a15.01828,15.01828,0,0,1-15,15h-394a15.01828,15.01828,0,0,1-15-15v-458a15.01828,15.01828,0,0,1,15-15h394a15.01828,15.01828,0,0,1,15,15Z"
                                transform="translate(-276.18187 -133.91309)" fill="#3f3d56" />
                            <path
                                d="M801.81836,318.08691h-184a9.01015,9.01015,0,0,1-9-9v-44a9.01016,9.01016,0,0,1,9-9h184a9.01016,9.01016,0,0,1,9,9v44A9.01015,9.01015,0,0,1,801.81836,318.08691Z"
                                transform="translate(-276.18187 -133.91309)"
                                fill="{{ $themeSettings->colors->primary_color }}" />
                            <circle cx="433.63626" cy="105.17383" r="20"
                                fill="{{ $themeSettings->colors->primary_color }}" />
                            <circle cx="433.63626" cy="105.17383" r="12.18187" fill="#fff" />
                        </svg>
                    </div>
                    <div class="mb-4">
                        <h4>{{ translate('You do not have any items') }}</h4>
                        <p class="mb-0">
                            {{ translate('You do not have any items, you can start by clicking New Item button.') }}
                        </p>
                    </div>
                    <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                        data-bs-target="#addItemModel">
                        <i class="fa-regular fa-plus me-1"></i>
                        {{ translate('New Item') }}
                    </button>
                </div>
            </div>
        @endif
    </div>
    {{ $items->links() }}
    <div class="modal fade" id="addItemModel" tabindex="-1" aria-labelledby="addItemModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-header border-0 p-0 mb-3">
                    <h1 class="modal-title fs-5" id="addItemModelLabel">{{ translate('Category') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <form action="{{ route('workspace.items.create') }}" method="GET">
                        <div class="mb-3">
                            <select name="category" class="form-select form-select-md">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary btn-md w-100">{{ translate('Continue') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
