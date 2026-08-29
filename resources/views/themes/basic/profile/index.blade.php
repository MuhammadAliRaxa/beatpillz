@extends('themes.basic.profile.layout')
@section('title', $user->username)
@section('content')
    <div class="card-v card-bg border p-3">
        <img src="{{ $user->getProfileCover() }}" alt="{{ $user->getName() }}" class="rounded-2 w-100" />
    </div>
    <div class="mt-4 px-3">
        <h5 class="mb-3">{{ $user->profile_heading }}</h5>
        {!! $user->profile_description !!}
    </div>
@endsection
