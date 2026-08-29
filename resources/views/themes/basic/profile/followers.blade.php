@extends('themes.basic.profile.layout')
@section('noindex', true)
@section('section', $user->username)
@section('title', translate('Followers'))
@section('content_size', 9)
@section('content')
    @if ($followers->count() > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
            @foreach ($followers as $follower)
                @php
                    $follower = $follower->follower;
                @endphp
                <div class="col">
                    <div class="card-v card-bg border h-100 py-5">
                        <div class="row row-cols-1 text-center g-2">
                            <div class="col">
                                <a href="{{ $follower->getProfileLink() }}" class="user-avatar user-avatar-xl me-1">
                                    <img src="{{ $follower->getAvatar() }}" alt="{{ $follower->username }}">
                                </a>
                            </div>
                            <div class="col">
                                <a href="{{ $follower->getProfileLink() }}" class="d-block text-dark fs-5 mb-1">
                                    <h6 class="mb-0 small">{{ $follower->username }}</h6>
                                </a>
                                <p class="mb-0 fs-6 mb-3">
                                    <span class="text-muted small">
                                        {{ translate('Member since :date', ['date' => dateFormat($follower->created_at, 'M Y')]) }}
                                    </span>
                                </p>
                                <livewire:follow-button :user="$follower" />
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $followers->links() }}
    @else
        <div class="card-v border card-bg text-center">
            <div class="py-3">
                <i class="fa-solid fa-users fa-lg"></i>
                <p class="mb-0 mt-3">{{ translate('No followers found') }}</p>
            </div>
        </div>
    @endif
@endsection
