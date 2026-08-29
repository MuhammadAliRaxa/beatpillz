@if ($featuredAuthorSection && $featuredAuthor)
    <div class="section section-start">
        <div class="container container-custom">
            <div class="section-body">
                <div class="border border-dashed border-primary rounded-3 p-3" data-aos="fade-up" data-aos-duration="1000">
                    <div class="card-v">
                        <h1>{{ $featuredAuthorSection->name }}</h1>
                        @if ($featuredAuthorSection->description)
                            <p class="text-muted">{{ $featuredAuthorSection->description }}</p>
                        @endif
                        <div class="my-5" data-aos="fade-left" data-aos-duration="1000">
                            <div
                                class="row row-cols-1 row-cols-md-auto text-center text-md-start align-items-center g-2">
                                <div class="col">
                                    <div class="user-avatar user-avatar-xl me-1">
                                        <a href="{{ $featuredAuthor->getProfileLink() }}">
                                            <img src="{{ $featuredAuthor->getAvatar() }}"
                                                alt="{{ $featuredAuthor->username }}">
                                        </a>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="d-block text-dark fs-5 mb-1">
                                        <a href="{{ $featuredAuthor->getProfileLink() }}" class="text-dark">
                                            <h6 class="mb-0 small">
                                                {{ $featuredAuthor->username }}
                                            </h6>
                                        </a>
                                    </div>
                                    <p class="mb-0 fs-6 mb-2">
                                        <span class="text-muted small">
                                            {{ translate('Member since :date', ['date' => dateFormat($featuredAuthor->created_at, 'M Y')]) }}
                                        </span>
                                    </p>
                                    <div class="row row-cols-auto justify-content-center justify-content-md-start g-2">
                                        @if ($featuredAuthor->isAuthor())
                                            <div class="col">
                                                <a href="{{ $featuredAuthor->getPortfolioLink() }}"
                                                    class="btn btn-primary">
                                                    <span>{{ translate('View Portfolio') }}</span>
                                                </a>
                                            </div>
                                        @endif
                                        <div class="col">
                                            <livewire:follow-button :user="$featuredAuthor" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($featuredAuthor->items->count() > 0)
                            <div class="row row-cols-md-2 row-cols-lg-3 row-cols-xxl-3 g-4">
                                @foreach ($featuredAuthor->items as $featuredAuthorItem)
                                    <div class="col" data-aos="fade-up" data-aos-duration="1000">
                                        @include('themes.basic.partials.item', [
                                            'item' => $featuredAuthorItem,
                                            'item_classes' => 'border',
                                        ])
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
