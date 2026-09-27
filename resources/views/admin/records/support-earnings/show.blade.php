@extends('admin.layouts.grid')
@section('section', translate('Records'))
@section('title', translate('Support Earnings #:id', ['id' => $supportEarning->id]))
@section('back', route('admin.records.support-earnings.index'))
@section('container', 'container-max-md')
@section('content')
    <div class="card mb-3">
        <ul class="list-group list-group-flush">
            <li class="list-group-item p-4">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <strong>{{ translate('ID') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span>#{{ $supportEarning->id }}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item p-4">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <strong>{{ translate('Author') }}</strong>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.members.users.edit', $supportEarning->author->id) }}" class="text-dark">
                            <i class="fa fa-user me-1"></i>
                            {{ $supportEarning->author->username }}
                        </a>
                    </div>
                </div>
            </li>
            <li class="list-group-item p-4">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <strong>{{ translate('Name') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span>{{ $supportEarning->name }}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item p-4">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <strong>{{ translate('Title') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span>{{ $supportEarning->title }}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item p-4">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <strong>{{ translate('Days') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span>{{ $supportEarning->days }}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item p-4">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <strong>{{ translate('Support Expiry Date') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span>{{ dateFormat($supportEarning->support_expiry_at) }}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item p-4">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <strong>{{ translate('Price') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span>{{ getAmount($supportEarning->price) }}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item p-4">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <strong>{{ translate('Author Fee') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span class="text-danger">{{ getAmount($supportEarning->author_fee) }}</span>
                    </div>
                </div>
            </li>
            @if ($supportEarning->author_tax)
                <li class="list-group-item p-4">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <strong>{{ translate(':tax_name (:tax_rate%)', [
                                'tax_name' => $supportEarning->author_tax->name,
                                'tax_rate' => $supportEarning->author_tax->rate,
                            ]) }}</strong>
                        </div>
                        <div class="col-auto">
                            <span class="text-danger">{{ getAmount($supportEarning->author_tax->amount) }}</span>
                        </div>
                    </div>
                </li>
            @endif
            <li class="list-group-item p-4">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <strong>{{ translate('Author Earning') }}</strong>
                    </div>
                    <div class="col-auto">
                        <strong class="text-success">{{ getAmount($supportEarning->author_earning) }}</strong>
                    </div>
                </div>
            </li>
            <li class="list-group-item p-4">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <strong>{{ translate('Status') }}</strong>
                    </div>
                    <div class="col-auto">
                        @if ($supportEarning->isActive())
                            <div class="badge bg-green">
                                {{ translate('Active') }}
                            </div>
                        @elseif($supportEarning->isRefunded())
                            <div class="badge bg-blue">
                                {{ translate('Refunded') }}
                            </div>
                        @else
                            <div class="badge bg-red">
                                {{ translate('Cancelled') }}
                            </div>
                        @endif
                    </div>
                </div>
            </li>
            <li class="list-group-item p-4">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <strong>{{ translate('Date') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span>{{ dateFormat($supportEarning->created_at) }}</span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-body">
            <a class="btn btn-outline-secondary btn-lg w-100"
                href="{{ route('admin.records.purchases.show', $supportEarning->purchase->id) }}" target="_blank">
                <i class="fa-solid fa-up-right-from-square me-1"></i>
                {{ translate('View Purchase') }}
            </a>
        </div>
    </div>
@endsection
