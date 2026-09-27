@extends('admin.layouts.grid')
@section('section', translate('Records'))
@section('title', translate('Referral Earnings'))
@section('container', 'container-max-xxl')
@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6 col-xxl-4">
            <div class="vironeer-counter-card bg-green">
                <div class="vironeer-counter-card-bg"></div>
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Active') }}</p>
                    <p class="vironeer-counter-card-number">{{ numberFormat($counters['active']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-4">
            <div class="vironeer-counter-card bg-blue">
                <div class="vironeer-counter-card-bg"></div>
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-share"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Refunded') }}</p>
                    <p class="vironeer-counter-card-number">{{ numberFormat($counters['refunded']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-12 col-xxl-4">
            <div class="vironeer-counter-card bg-red">
                <div class="vironeer-counter-card-bg"></div>
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Cancelled') }}</p>
                    <p class="vironeer-counter-card-number">{{ numberFormat($counters['cancelled']) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header p-3 border-bottom-small">
            <form action="{{ url()->current() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <input type="text" name="search" class="form-control" placeholder="{{ translate('Search...') }}"
                            value="{{ request('search') ?? '' }}">
                    </div>
                    <div class="col-12 col-lg-2">
                        <input type="text" name="date_from" class="form-control text-secondary"
                            placeholder="{{ translate('From Date') }}" onfocus="(this.type='date')"
                            onblur="(this.type='text')" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-12 col-lg-2">
                        <input type="text" name="date_to" class="form-control text-secondary"
                            placeholder="{{ translate('To Date') }}" onfocus="(this.type='date')"
                            onblur="(this.type='text')" value="{{ request('date_to') }}">
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
            @if ($referralEarnings->count() > 0)
                <div class="overflow-hidden">
                    <div class="table-custom-container">
                        <table class="table-custom table">
                            <thead>
                                <tr class="bg-light">
                                    <th>{{ translate('ID') }}</th>
                                    <th>{{ translate('Referral ID') }}</th>
                                    <th class="text-center">{{ translate('Referred User') }}</th>
                                    <th class="text-center">{{ translate('Referred By Author') }}</th>
                                    <th class="text-center">{{ translate('Author Earnings') }}</th>
                                    <th class="text-center">{{ translate('Status') }}</th>
                                    <th class="text-center">{{ translate('Date') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($referralEarnings as $referralEarning)
                                    <tr>
                                        <td>
                                            <span><i class="fa-solid fa-hashtag me-1"></i>{{ $referralEarning->id }}</span>
                                        </td>
                                        <td>
                                            <span><i
                                                    class="fa-solid fa-hashtag me-1"></i>{{ $referralEarning->referral->id }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.members.users.edit', $referralEarning->referral->user->id) }}"
                                                class="text-dark">
                                                <i class="fa-regular fa-user me-1"></i>
                                                {{ $referralEarning->referral->user->username }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.members.users.edit', $referralEarning->author->id) }}"
                                                class="text-dark">
                                                <i class="fa fa-user me-1"></i>
                                                {{ $referralEarning->author->username }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <strong
                                                class="text-success">{{ getAmount($referralEarning->author_earning) }}</strong>
                                        </td>
                                        <td class="text-center">
                                            @if ($referralEarning->isActive())
                                                <div class="badge bg-green">
                                                    {{ translate('Active') }}
                                                </div>
                                            @elseif($referralEarning->isRefunded())
                                                <div class="badge bg-blue">
                                                    {{ translate('Refunded') }}
                                                </div>
                                            @else
                                                <div class="badge bg-red">
                                                    {{ translate('Cancelled') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ dateFormat($referralEarning->created_at) }}</td>
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
                                                            href="{{ route('admin.records.sales.show', $referralEarning->sale->id) }}"
                                                            target="_blank">
                                                            <i class="fa-solid fa-up-right-from-square me-1"></i>
                                                            {{ translate('View Sale') }}
                                                        </a>
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
    {{ $referralEarnings->links() }}
@endsection
