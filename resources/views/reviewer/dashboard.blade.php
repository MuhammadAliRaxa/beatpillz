@extends('reviewer.layouts.app')
@section('title', translate('Dashboard'))
@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3 mb-4">
        <div class="col">
            <div class="vironeer-counter-card bg-orange">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Pending') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($counters['pending']) }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="vironeer-counter-card bg-purple">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Soft Rejected') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($counters['soft_rejected']) }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="vironeer-counter-card bg-blue">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-arrow-rotate-right"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Resubmitted') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($counters['resubmitted']) }}</p>
                </div>
            </div>
        </div>
        <div class="col">
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
        <div class="col">
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
        <div class="col">
            <div class="vironeer-counter-card bg-gray">
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-brush"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Updated') }}</p>
                    <p class="vironeer-counter-card-number">{{ number_format($counters['updated']) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card-v">
        <div class="row g-4 row-cols-1">
            <div class="col">
                <div class="row align-items-center g-2">
                    <div class="col-auto">
                        <span
                            class="badge bg-orange rounded-2 fw-light px-3 py-2">{{ translate('Pending') }}</span>
                    </div>
                    <div class="col">
                        <span>{{ translate('Item are submitted and waiting review') }}</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row align-items-center g-2">
                    <div class="col-auto">
                        <span
                            class="badge bg-purple rounded-2 fw-light px-3 py-2">{{ translate('Soft Rejected') }}</span>
                    </div>
                    <div class="col">
                        <span>{{ translate('Item needs improvement by the author') }}</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row align-items-center g-2">
                    <div class="col-auto">
                        <span
                            class="badge bg-blue rounded-2 fw-light px-3 py-2">{{ translate('Resubmitted') }}</span>
                    </div>
                    <div class="col">
                        <span>{{ translate('Item was re-sent after the soft rejection') }}</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row align-items-center g-2">
                    <div class="col-auto">
                        <span
                            class="badge bg-green rounded-2 fw-light px-3 py-2">{{ translate('Approved') }}</span>
                    </div>
                    <div class="col">
                        <span>{{ translate('Item are accepted and available for purchase') }}</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row align-items-center g-2">
                    <div class="col-auto">
                        <span
                            class="badge bg-red rounded-2 fw-light px-3 py-2">{{ translate('Hard Rejected') }}</span>
                    </div>
                    <div class="col">
                        <span>{{ translate('Item are rejected permanently') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
