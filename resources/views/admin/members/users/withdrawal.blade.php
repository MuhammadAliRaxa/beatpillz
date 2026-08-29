@extends('admin.layouts.grid')
@section('section', translate('Users'))
@section('title', translate(':name Withdrawal details', ['name' => $user->getName()]))
@section('back', route('admin.members.users.index'))
@section('content')
    @include('admin.members.users.includes.elements')
    <div class="row g-3">
        @include('admin.members.users.includes.sidebar')
        <div class="col-lg-9">
            <div class="card mb-3 h-100">
                <div class="card-header">{{ translate('Withdrawal details') }}</div>
                <div class="card-body p-4">
                    <form id="vironeer-submited-form" action="{{ route('admin.members.users.withdrawal.update', $user->id) }}"
                        method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">{{ translate('Withdrawal Method') }}</label>
                            <select name="withdrawal_method" class="form-select form-select-lg">
                                <option value="">--</option>
                                @foreach ($withdrawalMethods as $withdrawalMethod)
                                    <option value="{{ $withdrawalMethod->id }}" @selected($withdrawalMethod->id == $user->withdrawal_method_id)>
                                        {{ $withdrawalMethod->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">{{ translate('Withdrawal Account') }}</label>
                            <textarea type="text" name="withdrawal_account" class="form-control" rows="7">{{ $user->withdrawal_account }}</textarea>
                        </div>
                        <button class="btn btn-primary btn-md">{{ translate('Save Changes') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
