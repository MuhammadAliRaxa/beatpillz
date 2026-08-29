@extends('themes.basic.workspace.layouts.app')
@section('title', translate('Dashboard'))
@section('breadcrumbs', Breadcrumbs::render('workspace.dashboard'))
@section('content')
    <div class="mb-3">
        <div class="row g-3">
            <div class="col-12 col-lg-6 col-xl-{{ @$settings->referral->status ? 3 : 4 }}">
                <div class="dashboard-counter justify-content-start dashboard-counter-info">
                    <div class="dashboard-counter-icon">
                        <i class="fa-solid fa-cart-arrow-down"></i>
                    </div>
                    <div class="dashboard-counter-info">
                        <h6 class="dashboard-counter-title">{{ translate('Total Sales') }}</h6>
                        <p class="dashboard-counter-number">{{ number_format($counters['total_sales']) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-{{ @$settings->referral->status ? 3 : 4 }}">
                <div class="dashboard-counter justify-content-start">
                    <div class="dashboard-counter-icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <div class="dashboard-counter-info">
                        <h6 class="dashboard-counter-title">{{ translate('Sales Earnings') }}</h6>
                        <p class="dashboard-counter-number">{{ getAmount($counters['sales_earnings']) }}</p>
                    </div>
                </div>
            </div>
            @if (@$settings->referral->status)
                <div class="col-12 col-lg-6 col-xl-3">
                    <div class="dashboard-counter justify-content-start dashboard-counter-danger">
                        <div class="dashboard-counter-icon">
                            <i class="fa-solid fa-money-bill-trend-up"></i>
                        </div>
                        <div class="dashboard-counter-info">
                            <h6 class="dashboard-counter-title">{{ translate('Referral Earnings') }}</h6>
                            <p class="dashboard-counter-number">{{ getAmount($counters['referrals_earnings']) }}</p>
                        </div>
                    </div>
                </div>
            @endif
            <div
                class="col-12 {{ @$settings->referral->status ? 'col-lg-6' : '' }} col-xl-{{ @$settings->referral->status ? 3 : 4 }}">
                <div class="dashboard-counter justify-content-start dashboard-counter-secondary">
                    <div class="dashboard-counter-icon">
                        <i class="fa-regular fa-eye"></i>
                    </div>
                    <div class="dashboard-counter-info">
                        <h6 class="dashboard-counter-title">{{ translate('Total Views') }}</h6>
                        <p class="dashboard-counter-number">{{ number_format($counters['total_views']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-12 col-xxl-7">
            <div class="dashboard-chart-card dashboard-card card-v h-100">
                <h5 class="mb-4">{{ translate('Sales Statistics') }}</h5>
                <div class="dashboard-chart">
                    <canvas id="sales-chart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xxl-5">
            <div class="dashboard-card card-v h-100">
                <h5 class="mb-4">{{ translate('Top selling items') }}</h5>
                @forelse ($topSellingItems as $topSellingItem)
                    @php
                        $item = $topSellingItem->item;
                    @endphp
                    <div class="dashboard-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <a href="{{ $item->getLink() }}" class="item-img item-img-sm me-3">
                                <img src="{{ $item->getThumbnailLink() }}" alt="{{ $item->name }}">
                            </a>
                            <div>
                                <a href="{{ $item->getLink() }}" class="d-block text-dark fw-500 mb-2">
                                    {{ $item->name }}
                                </a>
                                <div class="mt-2 text-muted">
                                    ({{ translate($topSellingItem->total_sales > 1 ? ':count Sales' : ':count Sale', ['count' => numberFormat($topSellingItem->total_sales)]) }})
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    @include('themes.basic.workspace.partials.card-empty')
                @endforelse
            </div>
        </div>
        <div class="col-12 col-xxl-5">
            <div class="dashboard-card card-v h-100">
                <h5 class="mb-4">{{ translate('Top purchasing countries') }}</h5>
                @forelse ($topPurchasingCountries as $topPurchasingCountry)
                    <div class="dashboard-item pb-1 mb-1 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="flag-img me-3">
                                <img src="{{ countryFlag($topPurchasingCountry->country) }}"
                                    alt="{{ countries($topPurchasingCountry->country) }}">
                            </div>
                            <div>
                                <span class="d-block fw-500 mb-1">
                                    {{ countries($topPurchasingCountry->country) }}
                                </span>
                            </div>
                        </div>
                        <div class="ms-3">
                            <span class="fw-bold text-success me-1">
                                {{ getAmount($topPurchasingCountry->total_earnings) }}
                            </span>
                        </div>
                    </div>
                @empty
                    @include('themes.basic.workspace.partials.card-empty')
                @endforelse
            </div>
        </div>
        <div class="col-12 col-xxl-7">
            <div class="dashboard-chart-card dashboard-card card-v h-100">
                <h5 class="mb-4">{{ translate('Purchasing Countries') }}</h5>
                <div class="dashboard-chart">
                    <div class="chart" id="countries-chart" class="w-100"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xxl-7">
            <div class="dashboard-chart-card dashboard-card card-v h-100">
                <h5 class="mb-4">{{ translate('Views Statistics') }}</h5>
                <div class="dashboard-chart">
                    <canvas id="views-chart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xxl-5">
            <div class="dashboard-chart-card dashboard-card card-v h-100">
                <h5 class="mb-4">{{ translate('Top Referrals') }}</h5>
                @forelse($referrals as $referral)
                    <div class="dashboard-item pb-2 mb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="d-block fw-500">
                                {{ shorterText($referral->referrer, 60) }}
                            </span>
                        </div>
                        <div class="ms-3">
                            <span class="text-muted me-1">
                                {{ numberFormat($referral->total_views) }}
                            </span>
                        </div>
                    </div>
                @empty
                    @include('themes.basic.workspace.partials.card-empty')
                @endforelse
            </div>
        </div>
    </div>
    @push('top_scripts')
        @php
            $chartsConfig = [
                'sales' => $charts['sales'],
                'views' => $charts['views'],
                'geo' => [
                    'data' => [],
                ],
            ];
            $chartsConfig['geo']['data'][] = ['Country', translate('Sales')];
            if (!$geoCountries->isEmpty()) {
                foreach ($geoCountries as $geoCountry) {
                    $chartsConfig['geo']['data'][] = [$geoCountry->country, (int) $geoCountry->total_sales];
                }
            }
        @endphp
        <script>
            "use strict";
            const chartsConfig = @json($chartsConfig);
        </script>
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/chartjs/chart.min.js') }}"></script>
        <script src="{{ asset('vendor/libs/geochart/geochart.min.js') }}"></script>
        <script src="{{ theme_assets_with_version('assets/js/charts.js') }}"></script>
    @endpush
@endsection
