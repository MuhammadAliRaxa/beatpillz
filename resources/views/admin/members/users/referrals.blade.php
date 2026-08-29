@extends('admin.layouts.grid')
@section('title', translate(':name Referrals', ['name' => $user->getName()]))
@section('back', route('admin.members.users.index'))
@section('content')
    @include('admin.members.users.includes.elements')
    <div class="row g-3">
        @include('admin.members.users.includes.sidebar')
        <div class="col-lg-9">
            @if ($user->getReferralLink())
                <div class="card mb-3">
                    <div class="card-header">{{ translate('Referral Link') }}</div>
                    <div class="card-body">
                        <div class="input-group">
                            <input id="refLink" type="text" class="form-control form-control-md"
                                value="{{ $user->getReferralLink() }}" readonly="">
                            <button type="button" class="btn btn-primary btn-md btn-copy" id="input-group-button-right"
                                data-clipboard-target="#refLink">
                                <i class="far fa-clone me-2"></i>
                                {{ translate('Copy') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header p-3 border-bottom-small">
                    <form action="{{ request()->url() }}" method="GET">
                        <div class="row g-3">
                            <div class="col-12 col-lg-10">
                                <input type="text" name="search" class="form-control"
                                    placeholder="{{ translate('Search...') }}"
                                    value="{{ request()->input('search') ?? '' }}">
                            </div>
                            <div class="col">
                                <button class="btn btn-primary w-100"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="col">
                                <a href="{{ url()->current() }}"
                                    class="btn btn-secondary w-100">{{ translate('Reset') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="vironeer-normal-table table w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ translate('User') }}</th>
                                    <th>{{ translate('Earnings') }}</th>
                                    <th>{{ translate('Registred Date') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($referrals as $referral)
                                    <tr>
                                        <td>{{ $referral->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.members.users.edit', $referral->user->id) }}"
                                                class="text-dark">
                                                <i class="fa fa-user me-2"></i>
                                                {{ $referral->user->getName() }}
                                            </a>
                                        </td>
                                        <td>{{ getAmount($referral->earnings) }}</td>
                                        <td>{{ dateFormat($referral->created_at) }}</td>
                                        <td class="text-end">
                                            <form
                                                action="{{ route('admin.members.users.referrals.delete', [$user->id, $referral->id]) }}"
                                                method="POST">
                                                @csrf @method('DELETE')
                                                <button class="action-confirm btn btn-danger btn-sm"><i
                                                        class="fa-regular fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <span class="text-muted">{{ translate('No Referrals Found') }}</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $referrals->links() }}
            </div>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
