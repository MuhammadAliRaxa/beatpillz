@extends('admin.layouts.grid')
@section('title', translate('Withdrawal #:id', ['id' => $withdrawal->id]))
@section('back', route('admin.withdrawals.index'))
@section('container', 'container-max-lg')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ translate('Withdrawal Details') }}
        </div>
        <div class="card-body">
            <table class="table custom-table table-bordered table-striped mb-0">
                <tbody>
                    <tr>
                        <th>{{ translate('ID') }}</th>
                        <td>#{{ $withdrawal->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('Author') }}</th>
                        <td>
                            <a href="{{ route('admin.members.users.edit', $withdrawal->author->id) }}" class="text-dark"><i
                                    class="fa fa-user me-2"></i>{{ $withdrawal->author->username }}</a>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ translate('Amount') }}</th>
                        <td class="text-success">
                            <strong>{{ getAmount($withdrawal->amount) }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ translate('Withdrawal Method') }}</th>
                        <td>{{ $withdrawal->method }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('Withdrawal account') }}</th>
                        <td>{{ demo($withdrawal->account) }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('Status') }}</th>
                        <td>
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
                    </tr>
                    <tr>
                        <th>{{ translate('Withdrawal Date') }}</th>
                        <td>{{ dateFormat($withdrawal->created_at) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @if (!$withdrawal->isCancelled() && !$withdrawal->isReturned() && !$withdrawal->isCompleted())
        <div class="card mt-4">
            <div class="card-header">
                {{ translate('Take Action') }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.withdrawals.update', $withdrawal->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ translate('Status') }}</label>
                        <select name="status" class="form-select form-select-lg" title="{{ translate('Status') }}">
                            @foreach (\App\Models\Withdrawal::getStatusOptions() as $key => $value)
                                <option value="{{ $key }}" @selected($key == $withdrawal->status)>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="email_notification" class="form-check-input">
                        <label class="form-check-label">{{ translate('Send Email Notification') }}</label>
                    </div>
                    <button class="btn btn-primary btn-lg action-confirm w-100">
                        {{ translate('Save') }}
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection
