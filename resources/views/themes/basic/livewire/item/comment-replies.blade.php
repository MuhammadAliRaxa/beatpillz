<div class="col">
    <div class="card-v card-bg border p-4">
        <div class="item-comment">
            @foreach ($commentReplies as $commentReply)
                @php
                    $user = $commentReply->user;
                @endphp
                @if ($loop->first)
                    <div class="row row-cols-auto flex-nowrap g-3">
                        <div class="col d-flex flex-column align-items-center">
                            <a href="{{ $user->getProfileLink() }}" class="user-avatar me-0">
                                <img src="{{ $user->getAvatar() }}" alt="{{ $user->username }}">
                            </a>
                        </div>
                        <div class="col flex-grow-1 flex-shrink-1">
                            <div class="row row-cols-auto align-items-center justify-content-between g-2 mb-2">
                                <div class="col">
                                    <div class="row row-cols-auto align-items-center g-2">
                                        <div class="col">
                                            <a href="{{ $user->getProfileLink() }}">
                                                <h6 class="mb-0">{{ $user->username }}</h6>
                                            </a>
                                        </div>
                                        @if ($user->hasPurchasedItem($item->id))
                                            <div class="col">
                                                <div class="user-badge bg-secondary">
                                                    {{ translate('Purchased') }}
                                                </div>
                                            </div>
                                            @if ((authUser() && authUser()->id == $item->author->id) || (authUser() && authUser()->id == $user->id))
                                                @php
                                                    $purchase = $user->getItemPurchase($item->id);
                                                @endphp
                                                @if ($purchase)
                                                    @if ($purchase->support_expiry_at)
                                                        @if (!$purchase->isSupportExpired())
                                                            <div class="col">
                                                                <div class="user-badge">
                                                                    {{ translate('Supported') }}
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col">
                                                                <div class="user-badge bg-danger">
                                                                    {{ translate('Support Expired') }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col small">
                                    <div class="row row-cols-auto g-2">
                                        <div class="col">
                                            <a href="{{ $comment->getLink() }}" class="text-muted mb-0">
                                                {{ $commentReply->created_at->diffforhumans() }}
                                            </a>
                                        </div>
                                        @if (authUser() && !$commentReply->hasReported())
                                            <div class="col">
                                                <a href="javascript:void(0)"
                                                    wire:click.prevent="$emit('reportItemComment', {{ $commentReply->id }})"
                                                    class="text-muted mb-0">
                                                    <i class="fa-solid fa-flag"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="fw-light">
                                @if ($commentReply->hasReported())
                                    <i>{{ translate('This comment is under review') }}</i>
                                @else
                                    {!! purifier($commentReply->body) !!}
                                @endif
                            </div>
                            @if ($loop->last)
                                @if ((authUser() && authUser()->id == $item->author->id) || (authUser() && authUser()->id == $comment->user->id))
                                    <div class="mt-3">
                                        <a class="link text-primary small" data-bs-toggle="collapse"
                                            data-bs-target="#reply{{ hash_encode($commentReply->id) }}">
                                            <i class="fa-solid fa-reply me-1"></i>
                                            {{ translate('Reply') }}
                                        </a>
                                        <div wire:ignore.self class="collapse mt-3"
                                            id="reply{{ hash_encode($commentReply->id) }}">
                                            <div class="d-flex align-items-start">
                                                <div class="user-avatar">
                                                    <img src="{{ authUser()->getAvatar() }}"
                                                        alt="{{ authUser()->username }}">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <form wire:submit.prevent="storeReply">
                                                        <textarea class="form-control form-control-md w-100 mb-3" wire:model.defer="reply"
                                                            placeholder="{{ translate('Your reply') }}" rows="2"></textarea>
                                                        <div class="d-flex justify-content-end">
                                                            <button
                                                                class="btn btn-primary">{{ translate('Publish') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if (!$allRepliesLoaded && $totalCommentReplies)
                                <div class="mt-3">
                                    <button class="btn btn-link p-0 text-primary" wire:click="loadAllReplies">
                                        {{ translate($totalCommentReplies > 1 ? ':count more replies' : ':count more reply', ['count' => $totalCommentReplies]) }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="item-comment-reply">
                        <div class="row row-cols-auto flex-nowrap g-3">
                            <div class="col d-flex flex-column align-items-center">
                                <a href="{{ $user->getProfileLink() }}" class="user-avatar me-0">
                                    <img src="{{ $user->getAvatar() }}" alt="{{ $user->username }}">
                                </a>
                            </div>
                            <div class="col flex-grow-1 flex-shrink-1">
                                <div class="row row-cols-auto align-items-center justify-content-between g-2 mb-2">
                                    <div class="col">
                                        <div class="row row-cols-auto align-items-center g-2">
                                            <div class="col">
                                                <a href="{{ $user->getProfileLink() }}">
                                                    <h6 class="mb-0">{{ $user->username }}</h6>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col small">
                                        <div class="row row-cols-auto g-2">
                                            <div class="col">
                                                <p class="text-muted mb-0">
                                                    {{ $commentReply->created_at->diffforhumans() }}
                                                </p>
                                            </div>
                                            @if (authUser() && !$commentReply->hasReported())
                                                <div class="col">
                                                    <a href="javascript:void(0)"
                                                        wire:click.prevent="$emit('reportItemComment', {{ $commentReply->id }})"
                                                        class="text-muted mb-0">
                                                        <i class="fa-solid fa-flag"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="fw-light">
                                    @if ($commentReply->hasReported())
                                        <i>{{ translate('This comment is under review') }}</i>
                                    @else
                                        {!! purifier($commentReply->body) !!}
                                    @endif
                                </div>
                                @if ($loop->last)
                                    @if ((authUser() && authUser()->id == $item->author->id) || (authUser() && authUser()->id == $comment->user->id))
                                        <div class="mt-3">
                                            <a class="link text-primary small" data-bs-toggle="collapse"
                                                data-bs-target="#reply{{ hash_encode($commentReply->id) }}">
                                                <i class="fa-solid fa-reply-all me-1"></i>
                                                {{ translate('Reply') }}
                                            </a>
                                            <div wire:ignore.self class="collapse mt-3"
                                                id="reply{{ hash_encode($commentReply->id) }}">
                                                <div class="d-flex align-items-start">
                                                    <div class="user-avatar">
                                                        <img src="{{ authUser()->getAvatar() }}"
                                                            alt="{{ authUser()->username }}">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <form wire:submit.prevent="storeReply">
                                                            <textarea class="form-control form-control-md w-100 mb-3" wire:model.defer="reply"
                                                                placeholder="{{ translate('Your reply') }}" rows="2" required></textarea>
                                                            <div class="d-flex justify-content-end">
                                                                <button
                                                                    class="btn btn-primary">{{ translate('Publish') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
