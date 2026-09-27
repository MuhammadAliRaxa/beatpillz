@extends('themes.basic.items.layout')
@section('noindex', true)
@section('title', $item->name)
@section('breadcrumbs', Breadcrumbs::render('items.support', $item))
@section('og_image', $item->getImageLink())
@section('description', shorterText(strip_tags($item->description), 155))
@section('keywords', $item->tags)
@section('content')
    <div class="item-support border-top pt-3">
        @php
            $author = $item->author;
        @endphp
        <div class="card-bg border rounded-2 p-4 mb-3">
            <div class="row align-items-center g-3">
                <div class="col-12 col-lg">
                    <div class="row row-cols-auto align-items-center g-3">
                        <div class="col">
                            <a href="{{ $author->getProfileLink() }}" class="user-avatar user-avatar-lg me-1">
                                <img src="{{ $author->getAvatar() }}" alt="{{ $author->username }}">
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ $author->getProfileLink() }}" class="d-block text-dark fs-5 mb-1">
                                <h5 class="mb-0">
                                    {{ translate(':username supports this item', ['username' => $author->username]) }}</h5>
                            </a>
                            <p class="mb-0 fs-6">
                                <span class="text-muted small">
                                    {{ translate("Read the author's instructions below to know how you can get help.") }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-auto">
                    <span class="badge bg-primary fw-light px-3 py-2">
                        {{ translate('Supported') }}
                    </span>
                </div>
            </div>
        </div>
        <div>
            <div class="card-bg border rounded-2 p-4">
                <h5 class="pb-3 border-bottom mb-4">
                    {{ translate(":username's support instructions", ['username' => $author->username]) }}</h5>
                {!! purifier($item->support_instructions) !!}
            </div>
        </div>
    </div>
@endsection
