@extends('themes.basic.workspace.layouts.app')
@section('section', translate('My Items'))
@section('title', $item->name)
@section('back', route('workspace.items.index'))
@section('breadcrumbs', Breadcrumbs::render('workspace.items.history', $item))
@section('content')
    <div class="dashboard-tabs">
        @include('themes.basic.workspace.items.includes.tabs-nav')
        <div class="dashboard-tabs-content">
            <div class="row g-3">
                <div class="col-lg-7">
                    <div class="row g-3">
                        @forelse ($itemHistories as $itemHistory)
                            <div class="col-12">
                                <div class="card-v p-4">
                                    <div class="p-2">
                                        <div class="conversation">
                                            <div class="mb-4">
                                                <div
                                                    class="row row-cols-auto justify-content-between align-items-center g-3">
                                                    <div class="col">
                                                        @if ($itemHistory->author)
                                                            @php
                                                                $author = $itemHistory->author;
                                                            @endphp
                                                            <a href="{{ $author->getProfileLink() }}" target="_blank"
                                                                class="conversation-user">
                                                                <img src="{{ $author->getAvatar() }}"
                                                                    alt="{{ $author->username }}">
                                                                <span class="h6 mb-0">{{ $author->username }}</span>
                                                            </a>
                                                        @elseif ($itemHistory->reviewer)
                                                            @php
                                                                $reviewer = $itemHistory->reviewer;
                                                            @endphp
                                                            <div class="conversation-user">
                                                                <img src="{{ $reviewer->getAvatar() }}"
                                                                    alt="{{ $reviewer->username }}">
                                                                <span class="h6 mb-0">{{ $reviewer->username }}</span>
                                                            </div>
                                                        @elseif ($itemHistory->admin)
                                                            @php
                                                                $admin = $itemHistory->admin;
                                                            @endphp
                                                            <div class="conversation-user">
                                                                <img src="{{ $admin->getAvatar() }}"
                                                                    alt="{{ $admin->username }}">
                                                                <span class="h6 mb-0">{{ $admin->username }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col">
                                                        <time
                                                            class="text-muted small">{{ dateFormat($itemHistory->created_at) }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="conversation-content">
                                                <h5 class="mb-0">{{ translate($itemHistory->title) }}</h5>
                                                @if ($itemHistory->body)
                                                    <div class="text-muted mt-3">{!! purifier($itemHistory->body) !!}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card-v">
                                    @include('themes.basic.workspace.partials.card-empty')
                                </div>
                            </div>
                        @endforelse
                    </div>
                    {{ $itemHistories->links() }}
                </div>
                <div class="col-lg-5">
                    @include('themes.basic.workspace.items.includes.sidebar')
                </div>
            </div>
        </div>
    </div>
@endsection
