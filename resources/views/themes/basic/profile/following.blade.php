@extends('themes.basic.profile.layout')
@section('noindex', true)
@section('section', $user->username)
@section('title', translate('Following'))
@section('content_size', 9)
@section('content')
    @if ($followings->count() > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
            @foreach ($followings as $following)
                @php
                    $following = $following->following;
                @endphp
                <div class="col">
                    <div class="card-v card-bg border h-100 py-5">
                        <div class="row row-cols-1 text-center g-2">
                            <div class="col">
                                <a href="{{ $following->getProfileLink() }}" class="user-avatar user-avatar-xl me-1">
                                    <img src="{{ $following->getAvatar() }}" alt="{{ $following->username }}">
                                </a>
                            </div>
                            <div class="col">
                                <a href="{{ $following->getProfileLink() }}" class="d-block text-dark fs-5 mb-1">
                                    <h6 class="mb-0 small">{{ $following->username }}</h6>
                                </a>
                                <p class="mb-0 fs-6 mb-3">
                                    <span class="text-muted small">
                                        {{ translate('Member since :date', ['date' => dateFormat($following->created_at, 'M Y')]) }}
                                    </span>
                                </p>
                                <livewire:follow-button :user="$following" />
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $followings->links() }}
    @else
        <div class="card-v border card-bg text-center">
            <div class="py-3">
                <i class="fa-solid fa-users fa-lg"></i>
                <p class="mb-0 mt-3">{{ translate('No followings found') }}</p>
            </div>
        </div>
    @endif
@endsection
