@extends('themes.basic.profile.layout')
@section('noindex', true)
@section('section', $user->username)
@section('title', translate('Reviews'))
@section('content')
    <div class="mb-4">
        <div class="row row-cols-auto justify-content-between align-items-center g-3">
            <div class="col">
                <h4 class="mb-0">{{ translate('Reviews') }}</h4>
            </div>
            <div class="col">
                <div class="d-flex align-items-center">
                    @include('themes.basic.partials.rating-stars', [
                        'stars' => $user->avg_reviews,
                        'ratings_classes' => 'ratings-lg',
                    ])
                    <span class="d-flex ms-2">
                        {{ translate(':count out of 5 stars', ['count' => $user->avg_reviews]) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @if ($reviews->count() > 0)
        <div class="reviews">
            <div class="row row-cols-1 g-4">
                @foreach ($reviews as $review)
                    @include('themes.basic.partials.item-review', [
                        'item' => $review->item,
                        'review' => $review,
                    ])
                @endforeach
            </div>
        </div>
        {{ $reviews->links() }}
    @else
        <div class="card-v border card-bg text-center">
            <div class="py-3">
                <i class="far fa-star fa-lg"></i>
                <p class="mb-0 mt-3">{{ translate('No reviews found') }}</p>
            </div>
        </div>
    @endif
@endsection
