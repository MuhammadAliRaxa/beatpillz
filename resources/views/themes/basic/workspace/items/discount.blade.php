@extends('themes.basic.workspace.layouts.app')
@section('section', translate('My Items'))
@section('title', $item->name)
@section('back', route('workspace.items.index'))
@section('breadcrumbs', Breadcrumbs::render('workspace.items.discount', $item))
@section('content')
    <div class="dashboard-tabs">
        @include('themes.basic.workspace.items.includes.tabs-nav')
        <div class="dashboard-tabs-content">
            @if ($item->isApproved())
                @if (!$item->hasDiscount())
                    <form action="{{ route('workspace.items.discount.create', $item->id) }}" method="POST">
                        @csrf
                        <div class="dashboard-card card-v p-0 mb-4">
                            <div class="card-v-header border-bottom py-3 px-4">
                                <h5 class="mb-0">{{ translate('Regular License discount') }}</h5>
                            </div>
                            <div class="card-v-body p-4">
                                <p class="mb-4">
                                    <i class="fa-regular fa-circle-question me-1"></i>
                                    <span>{{ translate('The maximum discount percentage should be less or equal :percentage%', [
                                        'percentage' => @$settings->item->discount_max_percentage,
                                    ]) }}</span>
                                </p>
                                <div class="row g-4 mb-3">
                                    <div class="col-12 col-lg-3">
                                        @include('themes.basic.workspace.partials.input-price', [
                                            'label' => translate('Regular License Price'),
                                            'id' => 'regular-license-price',
                                            'value' => $item->regular_price,
                                            'disabled' => true,
                                        ])
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <label class="form-label">
                                            {{ translate('Discount Percentage') }}
                                        </label>
                                        <div class="input-group">
                                            <input id="regular-license-percentage" type="number" name="regular_percentage"
                                                placeholder="0" min="1"
                                                max="{{ @$settings->item->discount_max_percentage }}"
                                                class="form-control form-control-md border-success" required>
                                            <span
                                                class="input-group-text fs-5 px-3 bg-success text-white border-success">%</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        @include('themes.basic.workspace.partials.input-price', [
                                            'label' => translate('Buyer fee'),
                                            'value' => $item->category->regular_buyer_fee,
                                            'disabled' => true,
                                        ])
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        @include('themes.basic.workspace.partials.input-price', [
                                            'label' => translate('Purchase price'),
                                            'id' => 'regular-license-purchase-price',
                                            'value' => 0,
                                            'disabled' => true,
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-card card-v p-0 mb-4">
                            <div class="card-v-header border-bottom py-3 px-4">
                                <h5 class="mb-0">{{ translate('Extended License discount (Optional)') }}
                                </h5>
                            </div>
                            <div class="card-v-body p-4">
                                <p class="mb-4">
                                    <i class="fa-regular fa-circle-question me-1"></i>
                                    <span>{{ translate('The maximum discount percentage should be less or equal :percentage%', [
                                        'percentage' => @$settings->item->discount_max_percentage,
                                    ]) }}</span>
                                </p>
                                <div class="row g-4 mb-3">
                                    <div class="col-12 col-lg-3">
                                        @include('themes.basic.workspace.partials.input-price', [
                                            'label' => translate('Extended License Price'),
                                            'id' => 'extended-license-price',
                                            'value' => $item->extended_price,
                                            'disabled' => true,
                                        ])
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <label class="form-label">
                                            {{ translate('Discount Percentage') }}
                                        </label>
                                        <div class="input-group">
                                            <input id="extended-license-percentage" type="number"
                                                name="extended_percentage" placeholder="0" min="1"
                                                max="{{ @$settings->item->discount_max_percentage }}"
                                                class="form-control form-control-md border-success">
                                            <span
                                                class="input-group-text fs-5 px-3 bg-success text-white border-success">%</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        @include('themes.basic.workspace.partials.input-price', [
                                            'label' => translate('Buyer fee'),
                                            'value' => $item->category->extended_buyer_fee,
                                            'disabled' => true,
                                        ])
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        @include('themes.basic.workspace.partials.input-price', [
                                            'label' => translate('Purchase price'),
                                            'id' => 'extended-license-purchase-price',
                                            'value' => 0,
                                            'disabled' => true,
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-card card-v p-0 mb-4">
                            <div class="card-v-header border-bottom py-3 px-4">
                                <h5 class="mb-0">{{ translate('Discount Period') }}</h5>
                            </div>
                            <div class="card-v-body p-4">
                                <p class="mb-2">
                                    <i class="fa-regular fa-circle-question me-1"></i>
                                    <span>{{ translate('The starting date cannot be in the past') }}</span>
                                </p>
                                <p>
                                    <i class="fa-regular fa-circle-question me-1"></i>
                                    <span>{{ translate('The discount maximum days should be less or equal :days days', [
                                        'days' => @$settings->item->discount_max_days,
                                    ]) }}</span>
                                </p>
                                <div class="row g-3 align-items-center">
                                    <div class="col-12 col-lg">
                                        <input type="date" name="starting_date" class="form-control form-control-md"
                                            value="{{ old('starting_date') }}" required>
                                    </div>
                                    <div class="col-1 col-lg-1">
                                        <div class="text-center">{{ translate('to') }}</div>
                                    </div>
                                    <div class="col-10 col-lg">
                                        <input type="date" name="ending_date" class="form-control form-control-md"
                                            value="{{ old('ending_date') }}" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="dashboard-card card-v">
                            <button
                                class="btn btn-primary btn-md action-confirm">{{ translate('Create discount') }}</button>
                        </div>
                    </form>
                @else
                    @php
                        $discount = $item->discount;
                    @endphp
                    <div class="dashboard-card card-v overflow-hidden p-0">
                        <div class="table-container">
                            <table class="dashboard-table table text-center table-borderless align-middle">
                                <thead>
                                    <tr>
                                        <th class="text-start">{{ translate('Regular') }}</th>
                                        @if ($discount->withExtended())
                                            <th class="text-start">{{ translate('Extended') }}</th>
                                        @endif
                                        <th>{{ translate('Starting at') }}</th>
                                        <th>{{ translate('Ending at') }}</th>
                                        <th>{{ translate('Status') }}</th>
                                        @if ($discount->isInactive())
                                            <th class="text-end">{{ translate('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="text-muted">
                                    <tr>
                                        <td class="text-start">
                                            <div class="table-price mb-2">
                                                <div class="row g-3">
                                                    <div class="col-lg-12">
                                                        <div
                                                            class="row row-cols-auto align-items-center justify-content-between flex-nowrap">
                                                            <div class="col">
                                                                <h6 class="mb-0 text-dark">
                                                                    {{ translate('Discount') }}
                                                                </h6>
                                                            </div>
                                                            <div class="col">
                                                                <div class="item-price">
                                                                    <span class="item-price-number small">
                                                                        {{ $discount->regular_percentage }}%
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div
                                                            class="row row-cols-auto align-items-center justify-content-between flex-nowrap">
                                                            <div class="col">
                                                                <h6 class="mb-0 text-dark">
                                                                    {{ translate('Purchase Price') }}
                                                                </h6>
                                                            </div>
                                                            <div class="col">
                                                                <div class="item-price">
                                                                    <span class="item-price-through small">
                                                                        {{ getAmount($item->getRegularPrice(), 0) }}
                                                                    </span>
                                                                    <span class="item-price-number small">
                                                                        {{ getAmount($discount->getRegularPrice(), 0) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        @if ($discount->withExtended())
                                            <td class="text-start">
                                                <div class="table-price mb-2">
                                                    <div class="row g-3">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="row row-cols-auto align-items-center justify-content-between flex-nowrap">
                                                                <div class="col">
                                                                    <h6 class="mb-0 text-dark">
                                                                        {{ translate('Discount') }}
                                                                    </h6>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="item-price">
                                                                        <span class="item-price-number small">
                                                                            {{ $discount->extended_percentage }}%
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="row row-cols-auto align-items-center justify-content-between flex-nowrap">
                                                                <div class="col">
                                                                    <h6 class="mb-0 text-dark">
                                                                        {{ translate('Purchase Price') }}
                                                                    </h6>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="item-price">
                                                                        <span class="item-price-through small">
                                                                            {{ getAmount($item->getExtendedPrice(), 0) }}
                                                                        </span>
                                                                        <span class="item-price-number small">
                                                                            {{ getAmount($discount->getExtendedPrice(), 0) }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                        <td>{{ dateFormat($discount->starting_at, 'd M Y') }}</td>
                                        <td>{{ dateFormat($discount->ending_at, 'd M Y') }}</td>
                                        <td>
                                            @if ($discount->isActive())
                                                <div class="badge bg-green rounded-3 fw-light px-3 py-2">
                                                    {{ translate('Active') }}
                                                </div>
                                            @else
                                                <div class="badge bg-gray rounded-3 fw-light px-3 py-2">
                                                    {{ translate('Inactive') }}
                                                </div>
                                            @endif
                                        </td>
                                        @if ($discount->isInactive())
                                            <td class="text-end">
                                                <form action="{{ route('workspace.items.discount.delete', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button class="btn btn-danger btn-padding action-confirm">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @else
                <div class="alert alert-warning mb-0">
                    <i class="fa-regular fa-circle-question me-2"></i>
                    <span>{{ translate('This option is not available for unapproved items') }}</span>
                </div>
            @endif
        </div>
    </div>
    @if ($item->approved() && !$item->hasDiscount())
        @push('top_scripts')
            <script>
                "use strict";
                const itemConfig = {!! json_encode([
                    'max_discount_percentage' => @$settings->item->discount_max_percentage,
                    'translates' => [
                        'max_discount_percentage_error' => translate(
                            'The maximum discount percentage should be less or equal :percentage%',
                            [
                                'percentage' => @$settings->item->discount_max_percentage,
                            ],
                        ),
                    ],
                    'buyer_fee' => [
                        'regular' => $item->category->regular_buyer_fee,
                        'extended' => $item->category->extended_buyer_fee,
                    ],
                    'prices' => [
                        'regular' => $item->regular_price,
                        'extended' => $item->extended_price,
                    ],
                ]) !!};
            </script>
        @endpush
        @push('scripts_libs')
            <script src="{{ theme_assets_with_version('assets/js/item.js') }}"></script>
        @endpush
    @endif
@endsection
