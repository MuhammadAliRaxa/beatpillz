@extends('admin.layouts.grid')
@section('title', translate('Withdrawals'))
@section('container', 'container-max-xxl')
@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4 col-xxl-4">
            <div class="vironeer-counter-card bg-orange">
                <div class="vironeer-counter-card-bg"></div>
                <div class="vironeer-counter-card-icon">
                    <i class="fa-regular fa-hourglass-half"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Pending') }}
                        ({{ numberFormat($counters['pending']['total']) }})</p>
                    <p class="vironeer-counter-card-number">{{ getAmount($counters['pending']['amount']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xxl-4">
            <div class="vironeer-counter-card bg-purple">
                <div class="vironeer-counter-card-bg"></div>
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Returned') }}
                        ({{ numberFormat($counters['returned']['total']) }})</p>
                    <p class="vironeer-counter-card-number">{{ getAmount($counters['returned']['amount']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xxl-4">
            <div class="vironeer-counter-card bg-blue">
                <div class="vironeer-counter-card-bg"></div>
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-check-to-slot"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Approved') }}
                        ({{ numberFormat($counters['approved']['total']) }})</p>
                    <p class="vironeer-counter-card-number">{{ getAmount($counters['approved']['amount']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-6">
            <div class="vironeer-counter-card bg-green">
                <div class="vironeer-counter-card-bg"></div>
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Completed') }}
                        ({{ numberFormat($counters['completed']['total']) }})</p>
                    <p class="vironeer-counter-card-number">{{ getAmount($counters['completed']['amount']) }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 col-xxl-6">
            <div class="vironeer-counter-card bg-red">
                <div class="vironeer-counter-card-bg"></div>
                <div class="vironeer-counter-card-icon">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div class="vironeer-counter-card-meta">
                    <p class="vironeer-counter-card-title">{{ translate('Cancelled') }}
                        ({{ numberFormat($counters['cancelled']['total']) }})</p>
                    <p class="vironeer-counter-card-number">{{ getAmount($counters['cancelled']['amount']) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header p-3 border-bottom-small">
            <form action="{{ request()->url() }}" method="GET">
                <div class="row g-3">
                    <div class="col-12">
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ translate('Search...') }}" value="{{ request('search') ?? '' }}">
                    </div>
                    <div class="col-12 col-lg-3">
                        <input type="text" name="date_from" class="form-control text-secondary"
                            placeholder="{{ translate('From Date') }}" onfocus="(this.type='date')"
                            onblur="(this.type='text')" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-12 col-lg-3">
                        <input type="text" name="date_to" class="form-control text-secondary"
                            placeholder="{{ translate('To Date') }}" onfocus="(this.type='date')"
                            onblur="(this.type='text')" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-12 col-lg-2">
                        <select name="withdrawal_method" class="form-select selectpicker"
                            title="{{ translate('Withdrawal Method') }}" data-live-search="true">
                            @foreach ($withdrawalMethods as $withdrawalMethod)
                                <option value="{{ $withdrawalMethod->name }}" @selected(request('withdrawal_method') == $withdrawalMethod->name)>
                                    {{ $withdrawalMethod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-lg-2">
                        <select name="status" class="form-select selectpicker" title="{{ translate('Status') }}">
                            @foreach (\App\Models\Withdrawal::getStatusOptions() as $key => $value)
                                <option value="{{ $key }}" @selected($key == request('status'))>
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
            @if ($withdrawals->count() > 0)
                <div class="table-responsive">
                    <table class="vironeer-normal-table table w-100">
                        <thead>
                            <tr class="bg-light">
                                <th>{{ translate('ID') }}</th>
                                <th>{{ translate('Username') }}</th>
                                <th class="text-center">{{ translate('Amount') }}</th>
                                <th class="text-center">{{ translate('Method') }}</th>
                                <th class="text-center">{{ translate('Status') }}</th>
                                <th class="text-center">{{ translate('Withdrawal Date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($withdrawals as $withdrawal)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.withdrawals.review', $withdrawal->id) }}">
                                            <i class="fa-solid fa-hashtag"></i>
                                            <span>{{ $withdrawal->id }}</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.members.users.edit', $withdrawal->author->id) }}"
                                            class="text-dark">
                                            <i class="fa fa-user me-2"></i>
                                            {{ $withdrawal->author->username }}
                                        </a>
                                    </td>
                                    <td class="text-center text-success">
                                        <strong>{{ getAmount($withdrawal->amount) }}</strong>
                                    </td>
                                    <td class="text-center">{{ $withdrawal->method }}</td>
                                    <td class="text-center">
                                        @if ($withdrawal->isPending())
                                            <div class="badge bg-orange">
                                                {{ $withdrawal->getStatusName() }}
                                            </div>
                                        @elseif ($withdrawal->isReturned())
                                            <div class="badge bg-purple">
                                                {{ $withdrawal->getStatusName() }}
                                            </div>
                                        @elseif($withdrawal->isApproved())
                                            <div class="badge bg-blue">
                                                {{ $withdrawal->getStatusName() }}
                                            </div>
                                        @elseif($withdrawal->isCompleted())
                                            <div class="badge bg-green">
                                                {{ $withdrawal->getStatusName() }}
                                            </div>
                                        @elseif($withdrawal->isCancelled())
                                            <div class="badge bg-red">
                                                {{ $withdrawal->getStatusName() }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ dateFormat($withdrawal->created_at) }}</td>
                                    <td>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                                aria-expanded="true">
                                                <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-sm-end"
                                                data-popper-placement="bottom-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.withdrawals.review', $withdrawal->id) }}"><i
                                                            class="fa-solid fa-desktop me-2"></i>{{ translate('Details') }}</a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('admin.withdrawals.destroy', $withdrawal->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <button class="action-confirm dropdown-item text-danger"><i
                                                                class="far fa-trash-alt me-2"></i>{{ translate('Delete') }}</button>
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
            @else
                @include('admin.partials.empty', ['empty_classes' => 'empty-lg'])
            @endif
        </div>
    </div>
    {{ $withdrawals->links() }}
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/bootstrap/select/bootstrap-select.min.js') }}"></script>
    @endpush
@endsection
