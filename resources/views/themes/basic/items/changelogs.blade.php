@extends('themes.basic.items.layout')
@section('noindex', true)
@section('title', $item->name)
@section('breadcrumbs', Breadcrumbs::render('items.changelogs', $item))
@section('og_image', $item->getImageLink())
@section('description', shorterText(strip_tags($item->description), 155))
@section('keywords', $item->tags)
@section('content')
    <div class="changelogs border-top pt-3">
        @if ($changelogs->count() > 0)
            <div class="row row-cols-1 g-4">
                @foreach ($changelogs as $changelog)
                    <div class="col">
                        <div class="changelog">
                            <div class="mb-2">
                                <div class="row g-3 align-items-center">
                                    <div class="col">
                                        <strong
                                            class="fs-6">{{ translate('v:version', ['version' => $changelog->version]) }}</strong>
                                    </div>
                                    <div class="col-auto">
                                        <span>{{ dateFormat($changelog->created_at) }}</span>
                                    </div>
                                </div>
                            </div>
                            <pre>{{ $changelog->body }}</pre>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $changelogs->links() }}
        @else
            <div class="card-v card-bg text-center">
                <i class="fa-solid fa-rotate fa-lg"></i>
                <p class="mb-0 mt-3">{{ translate('This item has no changelogs') }}</p>
            </div>
        @endif
    </div>
@endsection
