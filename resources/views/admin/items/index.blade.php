@extends('admin.layouts.grid')
@section('title', translate('Items'))
@section('container', 'container-max-xxl')
@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6 col-xxl-4">
            <div class="vironeer-counter-card bg-orange">
                <div class="vironeer-counter-card-bg"></div>
                <div class="vironeer-counter-card-icon">
                    <i class="fa-regular fa-hourglass-half"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Pending') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($counters['pending']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-4">
            <div class="vironeer-counter-card bg-purple">
                <div class="vironeer-counter-card-bg"></div>
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Soft Rejected') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($counters['soft_rejected']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-4">
            <div class="vironeer-counter-card bg-blue">
                <div class="vironeer-counter-card-bg"></div>
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-arrow-rotate-right"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Resubmitted') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($counters['resubmitted']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-4">
            <div class="vironeer-counter-card bg-green">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Approved') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($counters['approved']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-4">
            <div class="vironeer-counter-card bg-red">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-regular fa-circle-xmark"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Hard Rejected') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($counters['hard_rejected']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-4">
            <div class="vironeer-counter-card bg-danger">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-regular fa-circle-xmark"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Deleted') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($counters['deleted']) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header p-3 border-bottom-small">
            <form action="{{ request()->url() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ translate('Search...') }}" value="{{ request('search') ?? '' }}">
                    </div>
                    <div class="col-12 col-lg-2">
                        <select name="category" class="form-select selectpicker" title="{{ translate('Category') }}"
                            data-live-search="true">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-lg-2">
                        <select name="status" class="form-select selectpicker" title="{{ translate('Status') }}"
                            data-live-search="true">
                            @foreach (\App\Models\Item::getStatusOptions() as $key => $value)
                                <option value="{{ $key }}" @selected(request('status') == $key)>
                                    {{ $value }}
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
            @if ($items->count() > 0)
                <div class="overflow-hidden">
                    <div class="table-custom-container">
                        <table class="table-custom table">
                            <thead>
                                <tr class="bg-light">
                                    <th>{{ translate('ID') }}</th>
                                    <th>{{ translate('Details') }}</th>
                                    <th>{{ translate('Licenses Price') }}</th>
                                    <th class="text-center">{{ translate('Author') }}</th>
                                    <th class="text-center">{{ translate('Status') }}</th>
                                    <th class="text-center">{{ translate('Published Date') }}</th>
                                    <th class="text-end">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.items.show', $item->id) }}">
                                                <i class="fa-solid fa-hashtag"></i>
                                                {{ $item->id }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="vironeer-item-box">
                                                <a class="vironeer-item-image"
                                                    href="{{ route('admin.items.show', $item->id) }}">
                                                    <img src="{{ $item->isDeleted() ? asset('images/onerror-image.png') : $item->getThumbnailLink() }}"
                                                        class="rounded-3" alt="{{ $item->name }}">
                                                </a>
                                                <div>
                                                    <a class="text-reset"
                                                        href="{{ route('admin.items.show', $item->id) }}">{{ $item->name }}</a>
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
                                                    @if ($item->isFeatured() || $item->isPremium())
                                                        <div class="mt-2">
                                                            @if ($item->isFeatured())
                                                                <span
                                                                    class="badge bg-c-1">{{ translate('Featured') }}</span>
                                                            @endif
                                                            @if (licenseType(2) && @$settings->premium->status && $item->isPremium())
                                                                <span
                                                                    class="badge bg-warning">{{ translate('Premium') }}</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-start">
                                            @if ($item->isFree())
                                                <div class="table-price mb-3">
                                                    <div
                                                        class="row row-cols-auto align-items-center justify-content-between flex-nowrap">
                                                        <div class="col">
                                                            <h6 class="mb-0 text-dark">
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
                                            <div class="table-price mb-3">
                                                <div
                                                    class="row row-cols-auto align-items-center justify-content-between flex-nowrap">
                                                    <div class="col">
                                                        <h6 class="mb-0 text-dark">
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
                                                        <h6 class="mb-0 text-dark">
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
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.members.users.edit', $item->author->id) }}"
                                                class="text-dark">
                                                <i class="fa fa-user me-1"></i>
                                                {{ $item->author->username }}
                                            </a>
                                        </td>
                                        <td class="text-center">
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
                                            @elseif($item->isDeleted())
                                                <div class="badge bg-danger rounded-2 fw-light px-3 py-2">
                                                    {{ $item->getStatusName() }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ dateFormat($item->created_at) }}
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
                                                            href="{{ route('admin.items.show', $item->id) }}">
                                                            <i class="fa-solid fa-desktop me-1"></i>
                                                            {{ translate('Details') }}
                                                        </a>
                                                    </li>
                                                    @if ($item->isApproved())
                                                        <li>
                                                            <a class="dropdown-item" href="{{ $item->getLink() }}"
                                                                target="_blank">
                                                                <i class="fa-solid fa-up-right-from-square me-1"></i>
                                                                {{ translate('View Item') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if (!$item->isDeleted())
                                                        <li>
                                                            @if ($item->isMainFileExternal())
                                                                <a class="dropdown-item" href="{{ $item->main_file }}"
                                                                    target="_blank">
                                                                    <i class="fa-solid fa-download me-1"></i>
                                                                    {{ translate('Download') }}
                                                                </a>
                                                            @else
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.items.download', $item->id) }}">
                                                                    <i class="fa-solid fa-download me-1"></i>
                                                                    {{ translate('Download') }}
                                                                </a>
                                                            @endif
                                                        </li>
                                                    @endif
                                                    @if ($item->isApproved())
                                                        <li>
                                                            <hr class="dropdown-divider" />
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.records.sales.index', ['item' => $item->id]) }}">
                                                                <i class="fa-solid fa-cart-shopping me-1"></i>
                                                                {{ translate('Sales') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.records.purchases.index', ['item' => $item->id]) }}">
                                                                <i class="fa-solid fa-basket-shopping me-1"></i>
                                                                {{ translate('Purchases') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider" />
                                                        </li>
                                                        <li>
                                                            @if (!$item->isFeatured())
                                                                <form
                                                                    action="{{ route('admin.items.featured', $item->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <button
                                                                        class="action-confirm dropdown-item text-success">
                                                                        <i class="fa-solid fa-certificate me-1"></i>
                                                                        {{ translate('Make Featured') }}
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form
                                                                    action="{{ route('admin.items.featured.remove', $item->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <button
                                                                        class="action-confirm dropdown-item text-danger">
                                                                        <i class="fa-solid fa-certificate me-1"></i>
                                                                        {{ translate('Remove Featured') }}
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </li>
                                                        @if (licenseType(2) && @$settings->premium->status && !$item->isFree())
                                                            <li>
                                                                <hr class="dropdown-divider" />
                                                            </li>
                                                            <li>
                                                                @if (!$item->isPremium())
                                                                    <form
                                                                        action="{{ route('admin.items.premium', $item->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <button
                                                                            class="action-confirm dropdown-item text-warning">
                                                                            <i class="fa-solid fa-crown me-1"></i>
                                                                            {{ translate('Add to Premium') }}
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <form
                                                                        action="{{ route('admin.items.premium.remove', $item->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        <button
                                                                            class="action-confirm dropdown-item text-danger">
                                                                            <i class="fa-solid fa-crown me-1"></i>
                                                                            {{ translate('Remove from Premium') }}
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </li>
                                                        @endif
                                                    @endif
                                                    <li>
                                                        <hr class="dropdown-divider" />
                                                    </li>
                                                    @if (!$item->isDeleted())
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.items.soft-delete', $item->id) }}"
                                                                method="POST">
                                                                @csrf @method('DELETE')
                                                                <button class="action-confirm dropdown-item">
                                                                    <i class="far fa-trash-alt me-1"></i>
                                                                    {{ translate('Soft Delete') }}
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider" />
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.items.permanently-delete', $item->id) }}"
                                                            method="POST">
                                                            @csrf @method('DELETE')
                                                            <button class="action-confirm dropdown-item text-danger">
                                                                <i class="fa-solid fa-trash-can me-1"></i>
                                                                {{ translate('Permanently Delete') }}
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
    {{ $items->links() }}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
