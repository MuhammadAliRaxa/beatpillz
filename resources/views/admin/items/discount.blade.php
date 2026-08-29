@extends('admin.layouts.grid')
@section('title', $item->name)
@section('item_view', true)
@section('back', route('admin.items.index'))
@section('content')
    <div class="dashboard-tabs">
        @include('admin.items.includes.tabs-nav')
        <div class="dashboard-tabs-content">
            <div class="card">
                @if ($item->hasDiscount())
                    @php
                        $discount = $item->discount;
                    @endphp
                    <div class="overflow-hidden">
                        <div class="table-custom-container">
                            <table class="table-custom table">
                                <thead>
                                    <tr>
                                        <th class="text-start">{{ translate('Regular') }}</th>
                                        @if ($discount->withExtended())
                                            <th class="text-start">{{ translate('Extended') }}</th>
                                        @endif
                                        <th class="text-center">{{ translate('Starting at') }}</th>
                                        <th class="text-center">{{ translate('Ending at') }}</th>
                                        <th class="text-center">{{ translate('Status') }}</th>
                                        <th class="text-center">{{ translate('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                        <td class="text-center">{{ dateFormat($discount->starting_at, 'd M Y') }}</td>
                                        <td class="text-center">{{ dateFormat($discount->ending_at, 'd M Y') }}</td>
                                        <td class="text-center">
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
                                        <td class="text-center">
                                            <form action="{{ route('admin.items.discount.delete', $item->id) }}"
                                                method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-padding action-confirm">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    @include('admin.partials.empty', ['empty_classes' => 'empty-lg'])
                @endif
            </div>
        </div>
    </div>
@endsection
