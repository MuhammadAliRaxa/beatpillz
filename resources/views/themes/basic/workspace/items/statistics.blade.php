@extends('themes.basic.workspace.layouts.app')
@section('section', translate('My Items'))
@section('title', $item->name)
@section('back', route('workspace.items.index'))
@section('breadcrumbs', Breadcrumbs::render('workspace.items.statistics', $item))
@section('content')
    <div class="dashboard-tabs">
        @include('themes.basic.workspace.items.includes.tabs-nav')
        <div class="dashboard-tabs-content">
            <div class="row align-items-center g-3 mb-3">
                <div class="col">
                    <h4 class="mb-0">{{ translate('Statistics') }}</h4>
                </div>
                <div class="col-auto">
                    @include('themes.basic.workspace.partials.period-select', [
                        'date' => $item->created_at,
                    ])
                </div>
            </div>
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-4 g-3 mb-3">
                <div class="col">
                    <div class="card-v border text-center">
                        <h2 class="mb-3">{{ number_format($counters['total_sales']) }}</h2>
                        <h5 class="fw-light mb-0">{{ translate('Total Sales') }}</h5>
                    </div>
                </div>
                <div class="col">
                    <div class="card-v border text-center">
                        <h2 class="mb-3">{{ getAmount($counters['total_sales_amount']) }}</h2>
                        <h5 class="fw-light mb-0">{{ translate('Total Sales Amount') }}</h5>
                    </div>
                </div>
                <div class="col">
                    <div class="card-v border text-center">
                        <h2 class="mb-3">{{ getAmount($counters['total_earnings']) }}</h2>
                        <h5 class="fw-light mb-0">{{ translate('Total Earnings') }}</h5>
                    </div>
                </div>
                <div class="col">
                    <div class="card-v border text-center">
                        <h2 class="mb-3">{{ number_format($counters['total_views']) }}</h2>
                        <h5 class="fw-light mb-0">{{ translate('Total Views') }}</h5>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-12">
                    <div class="dashboard-chart-card dashboard-card card-v h-100">
                        <h5 class="mb-4">{{ translate('Sales Statistics') }}</h5>
                        <div class="dashboard-chart">
                            <canvas id="sales-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
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
