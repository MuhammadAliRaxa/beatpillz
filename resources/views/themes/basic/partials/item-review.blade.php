<div class="col">
    <div class="card-v card-bg border p-0">
        <div class="p-4">
            <div class="row row-cols-auto justify-content-between g-2 mb-2">
                <div class="col">
                    <h5 class="mb-0">{{ $review->subject }}</h5>
                </div>
                <div class="col">
                    <div class="col">
                        <a href="{{ $review->getLink() }}"
                            class="d-flex text-muted small">{{ $review->created_at->diffforhumans() }}</a>
                    </div>
                </div>
            </div>
            <div class="row row-cols-auto align-items-center g-2">
                <div class="col">
                    <p class="text-muted mb-0 small">
                        @php
                            $reviewUser = $review->user;
                        @endphp
                        {!! translate('By :username', [
                            'username' => '<a href="' . $reviewUser->getProfileLink() . '">' . $reviewUser->username . '</a>',
                        ]) !!}
                    </p>
                </div>
                <div class="col">
                    @include('themes.basic.partials.rating-stars', [
                        'stars' => $review->stars,
                    ])
                </div>
            </div>
            @if ($review->body)
                <div class="fw-light mb-0 mt-3">
                    {!! purifier($review->body) !!}
                </div>
            @endif
            @if (request()->routeIs('profile.reviews'))
                <p class="mb-0 mt-4">
                    @if ($item->isDeleted())
                        <span>{{ $item->name }}</span>
                    @else
                        <a href="{{ $item->getLink() }}" target="_blank">
                            <i class="fa-solid fa-up-right-from-square me-1"></i>
                            <span>{{ $item->name }}</span>
                        </a>
                    @endif
                </p>
            @endif
        </div>
        @if ($review->reply)
            @php
                $reply = $review->reply;
                $replyUser = $reply->user;
            @endphp
            <div class="border-top p-4">
                <div class="row row-cols-auto flex-nowrap g-3">
                    <div class="col d-flex flex-column align-items-center">
                        <a href="{{ $replyUser->getProfileLink() }}" class="user-avatar me-0">
                            <img src="{{ $replyUser->getAvatar() }}" alt="{{ $replyUser->username }}">
                        </a>
                    </div>
                    <div class="col flex-grow-1 flex-shrink-1">
                        <div class="row row-cols-auto align-items-center justify-content-between g-2 mb-2">
                            <div class="col">
                                <a href="{{ $replyUser->getProfileLink() }}">
                                    <h6 class="mb-0">{{ $replyUser->username }}</h6>
                                </a>
                            </div>
                            <div class="col small">
                                <p class="text-muted mb-0">
                                    {{ $reply->created_at->diffforhumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="fw-light">
                            {!! purifier($reply->body) !!}
                        </div>
                    </div>
                </div>
            </div>
        @elseif(authUser() && $review->body && $item->author->id == authUser()->id)
            <div class="border-top p-4">
                <form action="{{ route('items.reviews.reply', [$item->slug, $item->id, $review->id]) }}"
                    method="POST">
                    @csrf
                    <textarea class="form-control form-control-md w-100 mb-3" name="reply" placeholder="{{ translate('Your reply') }}"
                        rows="2" required></textarea>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary">{{ translate('Publish') }}</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
