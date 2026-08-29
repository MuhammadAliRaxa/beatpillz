<!DOCTYPE html>
<html lang="{{ getLocale() }}" dir="{{ getDirection() }}">

<head>
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('vendor/libs/swiper/swiper-bundle.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/libs/jquery/fancybox/jquery.fancybox.min.css') }}">
    @endpush
    @include('themes.basic.includes.head')
</head>

<body>
    @include('themes.basic.includes.navbar')
    <x-ad alias="item_page_top" @class('container mt-5') />
    <section class="section forced-start pt-5 pb-3">
        <div class="container">
            <div class="section-header mb-4">
                @yield('breadcrumbs')
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-lg">
                        <h1
                            class="item-single-title h2 {{ ($settings->item->reviews_status && $item->hasReviews()) || $item->hasSales() ? 'mb-2' : 'mb-0' }}">
                            {{ $item->name }}
                        </h1>
                        @if (($settings->item->reviews_status && $item->hasReviews()) || $item->hasSales() || $item->isRecentlyUpdated())
                            <div class="row row-cols-auto g-2">
                                @if ($settings->item->reviews_status && $item->hasReviews())
                                    <div class="col">
                                        <a href="{{ $item->getReviewsLink() }}">
                                            <div class="row row-cols-auto align-items-center g-2">
                                                <div class="col">
                                                    @include('themes.basic.partials.rating-stars', [
                                                        'stars' => $item->avg_reviews,
                                                    ])
                                                </div>
                                                <div class="col">
                                                    <span class="text-muted">
                                                        {{ translate($item->total_reviews > 1 ? '(:count Reviews)' : '(:count Review)', [
                                                            'count' => number_format($item->total_reviews),
                                                        ]) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    @if ($item->hasSales() || $item->isRecentlyUpdated())
                                        <div class="col">
                                            <span>-</span>
                                        </div>
                                    @endif
                                @endif
                                @if ($item->isPurchasingEnabled() && $item->hasSales())
                                    <div class="col">
                                        <i class="fa fa-cart-shopping me-1"></i>
                                        <span>{{ translate($item->total_sales > 1 ? ':count Sales' : ':count Sale', [
                                            'count' => number_format($item->total_sales),
                                        ]) }}</span>
                                    </div>
                                    @if ($item->isRecentlyUpdated())
                                        <div class="col">
                                            <span>-</span>
                                        </div>
                                    @endif
                                @endif
                                @if ($item->isRecentlyUpdated())
                                    <div class="col text-primary">
                                        <i class="fa-solid fa-circle-check me-1"></i>
                                        <span class="fw-bold">{{ translate('Recently Updated') }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="col-12 col-lg-auto">
                        <div class="row g-3">
                            <div class="col">
                                <div class="row g-3">
                                    @if ($item->demo_link)
                                        <div class="col-auto">
                                            <a href="{{ $item->getDemoLink() }}" target="_blank"
                                                class="btn btn-outline-secondary btn-md px-3">
                                                <i class="fa-solid fa-up-right-from-square"></i>
                                                <span class="ms-1">{{ translate('Live Preview') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="col-auto">
                                        <livewire:item.favorite-button :item="$item">
                                    </div>
                                </div>
                            </div>
                            @if ($item->isFree())
                                <div class="col-auto d-inline d-lg-none">
                                    @if ($item->isMainFileExternal())
                                        <a href="{{ route('items.free.download.external', hash_encode($item->id)) }}"
                                            target="_blank" class="btn btn-primary btn-md px-3">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    @else
                                        <form action="{{ route('items.free.download', hash_encode($item->id)) }}"
                                            method="POST">
                                            @csrf
                                            <button class="btn btn-primary btn-md px-3">
                                                <i class="fa-solid fa-download"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                            @if ($item->isPurchasingEnabled())
                                <div class="col-auto d-inline d-lg-none">
                                    <form data-action="{{ route('cart.add-item') }}" class="add-to-cart-form"
                                        method="POST">
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="license_type" value="1">
                                        @if (@$settings->item->support_status && defaultSupportPeriod() && $item->isSupported())
                                            <input type="hidden" name="support"
                                                value="{{ defaultSupportPeriod()->id }}">
                                        @endif
                                        <button class="btn btn-primary btn-md px-3" @disabled(authUser() && authUser()->id == $item->author_id)>
                                            <i class="fa fa-cart-shopping me-2"></i>
                                            <span>{{ getAmount($item->price->regular, 2, '.', '', true) }}</span>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="row g-4">
                    <div class="col-12 col-lg-7 col-xl-7 col-xxl-8">
                        <div class="card-v border p-4 mb-4">
                            <div class="item-single-preview">
                                @if ($item->isPreviewFileTypeImage())
                                    <div class="item-single-img">
                                        <img src="{{ $item->getPreviewImageLink() }}" alt="{{ $item->name }}" />
                                    </div>
                                    @if ($item->screenshots)
                                        <div class="item-swiper mt-3">
                                            <div class="swiper-actions">
                                                <div id="itemSwiperPrev" class="swiper-button-prev">
                                                    <i class="fa fa-chevron-left fa-rtl"></i>
                                                </div>
                                            </div>
                                            <div class="swiper itemSwiper">
                                                <div class="swiper-wrapper">
                                                    @foreach ($item->getScreenshotLinks() as $screenshot)
                                                        <div class="swiper-slide">
                                                            <a href="{{ $screenshot }}" class="item-slide-img"
                                                                data-fancybox="itemSlide">
                                                                <img src="{{ $screenshot }}"
                                                                    alt="{{ $item->name }}" />
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="swiper-actions">
                                                <div id="itemSwiperNext" class="swiper-button-next">
                                                    <i class="fa fa-chevron-right fa-rtl"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($item->isPreviewFileTypeVideo())
                                    <div class="item-single-video">
                                        <video class="video-plyr" poster="{{ $item->getPreviewImageLink() }}" controls>
                                            <source src="{{ $item->getPreviewLink() }}">
                                        </video>
                                    </div>
                                @elseif($item->isPreviewFileTypeAudio())
                                    <div class="item-single-audio">
                                        <div class="item-audio-wave">
                                            <div class="item-audio-actions md">
                                                <button class="play-button btn btn-primary btn-md px-2">
                                                    <div class="play-button-icon">
                                                        <i class="fas fa-play"></i>
                                                    </div>
                                                </button>
                                                <button class="pause-button btn btn-primary btn-md px-2 d-none">
                                                    <div class="play-button-icon">
                                                        <i class="fas fa-pause"></i>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="current-time fs-5">00:00</div>
                                            <div class="waveform" data-url="{{ $item->getPreviewLink() }}"
                                                data-waveheight="100"></div>
                                            <div class="total-duration fs-5">00:00</div>
                                        </div>
                                    </div>
                                @endif
                                @if (licenseType(2) && $item->isPremium())
                                    <div class="item-badge item-badge-premium">
                                        <i class="fa-solid fa-crown me-1"></i>
                                        {{ translate('Premium') }}
                                    </div>
                                @elseif ($item->isFree())
                                    <div class="item-badge item-badge-free">
                                        <i class="fa-regular fa-heart me-1"></i>
                                        {{ translate('Free') }}
                                    </div>
                                @elseif ($item->isOnDiscount())
                                    <div class="item-badge item-badge-sale">
                                        <i class="fa-solid fa-tag me-1"></i>
                                        {{ translate('On Sale') }}
                                    </div>
                                @elseif ($item->isTrending())
                                    <div class="item-badge item-badge-trending">
                                        <i class="fa-solid fa-bolt me-1"></i>
                                        {{ translate('Trending') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tabs-custom">
                            <div class="card-v border p-4">
                                @php
                                    $itemSettings = $settings->item;
                                @endphp
                                @if (
                                    @$itemSettings->reviews_status ||
                                        @$itemSettings->comments_status ||
                                        @$itemSettings->changelogs_status ||
                                        @$itemSettings->support_status)
                                    <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-3 g-3  mb-4">
                                        <div class="col">
                                            <a href="{{ $item->getLink() }}"
                                                class="btn {{ request()->routeIs('items.view') ? 'btn-primary' : 'btn-outline-secondary' }} btn-md w-100">
                                                <i class="fa-regular fa-circle-question me-1"></i>
                                                <span>{{ translate('Description') }}</span>
                                            </a>
                                        </div>
                                        @if ($settings->item->changelogs_status && $item->hasChangelogs())
                                            <div class="col">
                                                <a href="{{ $item->getChangeLogsLink() }}"
                                                    class="btn {{ request()->routeIs('items.changelogs') ? 'btn-primary' : 'btn-outline-secondary' }} btn-md w-100">
                                                    <i class="fa-solid fa-rotate me-1"></i>
                                                    <span>{{ translate('Changelogs') }}</span>
                                                </a>
                                            </div>
                                        @endif
                                        @if (
                                            ($settings->item->reviews_status && $item->hasReviews()) ||
                                                ($settings->item->reviews_status && authUser() && authUser()->hasPurchasedItem($item->id)))
                                            <div class="col">
                                                <a href="{{ $item->getReviewsLink() }}"
                                                    class="btn {{ request()->routeIs('items.reviews') ? 'btn-primary' : 'btn-outline-secondary' }} btn-md w-100">
                                                    <i class="fa-regular fa-star me-1"></i>
                                                    <span>{{ translate('Reviews (:count)', ['count' => numberFormat($item->total_reviews)]) }}</span>
                                                </a>
                                            </div>
                                        @endif
                                        @if (@$itemSettings->comments_status)
                                            <div class="col">
                                                <livewire:item.comments-counter :item="$item" :isActive="request()->routeIs('items.comments') ? true : false" />
                                            </div>
                                        @endif
                                        @if (@$itemSettings->support_status && $item->isSupported())
                                            <div class="col">
                                                <a href="{{ $item->getSupportLink() }}"
                                                    class="btn {{ request()->routeIs('items.support') ? 'btn-primary' : 'btn-outline-secondary' }} btn-md w-100">
                                                    <i class="fa-solid fa-headset me-1"></i>
                                                    <span>{{ translate('Support') }}</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                @yield('content')
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 col-xl-5 col-xxl-4">
                        @if (licenseType(2) && @$settings->premium->status && $item->isPremium())
                            @if (authUser() && authUser()->isSubscribed())
                                <div class="card-v border border-2 border-primary p-3 mb-4">
                                    <div class="card-v-body text-center p-3">
                                        <div class="mb-4">
                                            <div class="mb-3">
                                                <i class="fa-solid fa-download text-primary fa-3x"></i>
                                            </div>
                                            <h3 class="mb-3">{{ translate('Premium download') }}</h3>
                                            <p class="mb-0">
                                                {{ translate('You are subscribed to a premium plan. You can download this item directly.') }}
                                            </p>
                                        </div>
                                        @if ($item->isMainFileExternal())
                                            <a href="{{ route('items.premium.download.external', hash_encode($item->id)) }}"
                                                target="_blank"
                                                class="btn btn-primary btn-md w-100 {{ $item->author->id == authUser()->id ? 'disabled' : '' }}">
                                                <i class="fa-solid fa-download me-1"></i>
                                                {{ translate('Download') }}
                                            </a>
                                        @else
                                            <form
                                                action="{{ route('items.premium.download', hash_encode($item->id)) }}"
                                                method="POST">
                                                @csrf
                                                <button
                                                    class="btn btn-primary btn-md w-100 {{ $item->author->id == authUser()->id ? 'disabled' : '' }}">
                                                    <i class="fa-solid fa-download me-1"></i>
                                                    {{ translate('Download') }}
                                                </button>
                                            </form>
                                        @endif
                                        @if ($item->author->id != authUser()->id)
                                            <div class="text-center mt-3">
                                                <a href="{{ route('items.premium.license', encrypt($item->id)) }}"
                                                    target="_blank">
                                                    {{ translate('License certificate') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="card-v border border-2 border-premium p-3 mb-4">
                                    <div class="card-v-body p-4">
                                        <div class="mb-4">
                                            <div class="mb-3">
                                                <i class="fa-solid fa-crown text-premium fa-3x"></i>
                                            </div>
                                            <h3 class="mb-3">{{ translate('Get unlimited downloads') }}</h3>
                                            <p class="mb-0">
                                                {{ translate('Subscribe to access unlimited downloads of themes, videos, graphics, plugins, and more premium assets for your creative needs.') }}
                                            </p>
                                        </div>
                                        <a href="{{ route('premium.index') }}"
                                            class="btn btn-premium btn-md w-100">{{ translate('Subscribe to download') }}</a>
                                        @if (@$settings->premium->terms_link)
                                            <div class="text-center mt-3">
                                                <a href="{{ @$settings->premium->terms_link }}" class="text-premium"
                                                    target="_blank">
                                                    {{ translate('Learn more about premium') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif
                        @if ($item->isFree())
                            <div class="card-v border p-0 mb-4">
                                <div class="card-v-header border-bottom py-3 px-4">
                                    <div class="row row-cols-auto align-items-center justify-content-between g-2">
                                        <div class="col">
                                            <h5 class="mb-0">{{ translate('Free Item') }}</h5>
                                        </div>
                                        @if (@$settings->links->free_items_policy_link)
                                            <div class="col small">
                                                <a href="{{ @$settings->links->free_items_policy_link }}">
                                                    <span>{{ translate('Free items policy') }}</span>
                                                    <i class="fa fa-chevron-right fa-rtl ms-1 fa-sm"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-v-body p-4">
                                    <p class="text-muted">
                                        {{ translate('The author :author has offered the item for free, you can now download it.', [
                                            'author' => strtolower($item->author->username),
                                        ]) }}
                                    </p>
                                    @if ($item->isMainFileExternal())
                                        <a href="{{ route('items.free.download.external', hash_encode($item->id)) }}"
                                            target="_blank" class="btn btn-primary btn-md w-100">
                                            <i class="fa fa-download me-1"></i>
                                            {{ translate('Download') }}
                                        </a>
                                    @else
                                        <form action="{{ route('items.free.download', hash_encode($item->id)) }}"
                                            method="POST">
                                            @csrf
                                            <button class="btn btn-primary btn-md w-100">
                                                <i class="fa-solid fa-download me-1"></i>
                                                {{ translate('Download') }}
                                            </button>
                                        </form>
                                    @endif
                                    @if (authUser())
                                        <div class="text-center mt-3">
                                            <a href="{{ route('items.free.license', encrypt($item->id)) }}"
                                                target="_blank">
                                                {{ translate('License certificate') }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center mt-3">
                                            <a href="{{ route('login') }}">
                                                {{ translate('License certificate') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if ($item->isPurchasingEnabled())
                            <div class="card-v border p-0">
                                <div class="card-v-header border-bottom py-3 px-4">
                                    <div class="row row-cols-auto align-items-center justify-content-between g-2">
                                        <div class="col">
                                            <h5 class="mb-0">{{ translate('License Option') }}</h5>
                                        </div>
                                        @if (@$settings->links->licenses_terms_link)
                                            <div class="col small">
                                                <a href="{{ @$settings->links->licenses_terms_link }}">
                                                    <span>{{ translate('Licenses terms') }}</span>
                                                    <i class="fa fa-chevron-right fa-rtl ms-1 fa-sm"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-v-body p-4">
                                    <form data-action="{{ route('cart.add-item') }}" class="add-to-cart-form"
                                        method="POST">
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <div class="form-check form-check-lg mb-3">
                                            <input id="license-type-regular"
                                                class="form-check-input license-type mt-1" type="radio"
                                                name="license_type" value="1" checked>
                                            <label class="form-check-label d-flex justify-content-between"
                                                for="license-type-regular">
                                                <div>
                                                    <h6 class="mb-1">{{ translate('Regular') }}</h6>
                                                    <span
                                                        class="small text-muted">{{ translate('For one project') }}</span>
                                                </div>
                                                <div class="item-price">
                                                    @if ($item->isOnDiscount())
                                                        <span class="item-price-through">
                                                            {{ getAmount($item->getRegularPrice(), 2, '.', '', true) }}
                                                        </span>
                                                        <span class="item-price-number">
                                                            {{ getAmount($item->price->regular, 2, '.', '', true) }}
                                                        </span>
                                                    @else
                                                        <span class="item-price-number">
                                                            {{ getAmount($item->getRegularPrice(), 2, '.', '', true) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-lg mb-3">
                                            <input id="license-type-extended"
                                                class="form-check-input license-type mt-1" type="radio"
                                                name="license_type" value="2">
                                            <label class="form-check-label d-flex justify-content-between"
                                                for="license-type-extended">
                                                <div>
                                                    <h6 class="mb-1">{{ translate('Extended') }}</h6>
                                                    <span
                                                        class="small text-muted">{{ translate('For unlimited projects') }}</span>
                                                </div>
                                                <div class="item-price">
                                                    @if ($item->isOnDiscount() && $item->isExtendedOnDiscount())
                                                        <span class="item-price-through">
                                                            {{ getAmount($item->getExtendedPrice(), 2, '.', '', true) }}
                                                        </span>
                                                        <span class="item-price-number">
                                                            {{ getAmount($item->price->extended, 2, '.', '', true) }}
                                                        </span>
                                                    @else
                                                        <span class="item-price-number">
                                                            {{ getAmount($item->getExtendedPrice(), 2, '.', '', true) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                        @if (@$settings->item->support_status)
                                            @if ($item->isSupported())
                                                @php
                                                    $supportPeriods = supportPeriods();
                                                @endphp
                                                @if ($supportPeriods->count() > 0)
                                                    <div class="p-4 bg-light mb-3 rounded-3">
                                                        <div class="row g-2">
                                                            @foreach ($supportPeriods as $supportPeriod)
                                                                <div class="col-12">
                                                                    <div class="row g-3">
                                                                        <div class="col">
                                                                            <div class="form-check">
                                                                                <input
                                                                                    class="form-check-input item-support"
                                                                                    type="radio" name="support"
                                                                                    id="support{{ $supportPeriod->id }}"
                                                                                    value="{{ $supportPeriod->id }}"
                                                                                    @checked($supportPeriod->isDefault())>
                                                                                <label class="form-check-label"
                                                                                    for="support{{ $supportPeriod->id }}">
                                                                                    {{ $supportPeriod->title }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-auto">
                                                                            <strong class="regular-support">
                                                                                {{ $supportPeriod->isFree() ? translate('Free') : getAmount(($item->price->regular * $supportPeriod->percentage) / 100) }}
                                                                            </strong>
                                                                            <strong class="extended-support d-none">
                                                                                {{ $supportPeriod->isFree() ? translate('Free') : getAmount(($item->price->extended * $supportPeriod->percentage) / 100) }}
                                                                            </strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                        <button class="btn btn-primary btn-md w-100" @disabled(authUser() && authUser()->id == $item->author_id)>
                                            <i class="fa fa-cart-shopping me-1"></i>
                                            {{ translate('Add to Cart') }}
                                        </button>
                                    </form>
                                    @if (@$itemSettings->buy_now_button)
                                        <form action="{{ route('items.buy-now', [$item->slug, $item->id]) }}"
                                            class="buy-now-form" method="POST">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <input type="hidden" name="license_type" value="1">
                                            @if (@$settings->item->support_status && defaultSupportPeriod() && $item->isSupported())
                                                <input type="hidden" name="support"
                                                    value="{{ defaultSupportPeriod()->id }}">
                                            @endif
                                            <button class="btn btn-outline-primary btn-md w-100 mt-3"
                                                @disabled(authUser() && authUser()->id == $item->author_id)>
                                                {{ translate('Buy Now') }}
                                            </button>
                                        </form>
                                    @endif
                                    <div class="list mt-3">
                                        <div class="list-item small">
                                            <i class="fa fa-check text-primary me-1"></i>
                                            {{ translate('Quality checked by :website_name', ['website_name' => @$settings->general->site_name]) }}
                                        </div>
                                        <div class="list-item small">
                                            <i class="fa fa-check text-primary me-1"></i>
                                            {{ translate('Full Documentation') }}
                                        </div>
                                        <div class="list-item small">
                                            <i class="fa fa-check text-primary me-1"></i>
                                            {{ translate('Future updates') }}
                                        </div>
                                        <div class="list-item small">
                                            <i
                                                class="fa {{ $item->isSupported() ? 'fa-check text-primary' : 'fa-times text-danger' }} me-1"></i>
                                            {{ translate('Author Support') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @php
                            $featuredItemBadge = featuredItemBadge();
                        @endphp
                        @if ($featuredItemBadge && $item->wasFeatured())
                            <div class="card-v border p-4 mt-4">
                                <div class="row alig-items-center g-3">
                                    <div class="col-auto">
                                        <img src="{{ $featuredItemBadge->getImageLink() }}"
                                            alt="{{ $featuredItemBadge->name }}"
                                            title="{{ $featuredItemBadge->name }}" width="50px" height="50px">
                                    </div>
                                    <div class="col">
                                        <h5 class="mb-1">{{ translate('Featured Item') }}</h5>
                                        <p class="mb-0">
                                            {{ translate('This item was featured on :website_name', [
                                                'website_name' => @$settings->general->site_name,
                                            ]) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card-v border p-4 mt-4">
                            <div class="row align-items-center g-2 mb-3">
                                @php
                                    $author = $item->author;
                                @endphp
                                <div class="col">
                                    <div class="row row-cols-auto align-items-center g-2">
                                        <div class="col">
                                            <a href="{{ $author->getProfileLink() }}"
                                                class="user-avatar user-avatar-lg me-1">
                                                <img src="{{ $author->getAvatar() }}"
                                                    alt="{{ $author->username }}">
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a href="{{ $author->getProfileLink() }}"
                                                class="d-block text-dark fs-5 mb-1">
                                                <h5 class="mb-0">
                                                    {{ $author->username }}
                                                    @if ($author->isBanned())
                                                        <span class="badge bg-danger fw-light ms-2">
                                                            <i class="fa-solid fa-ban me-1"></i>
                                                            {{ translate('Banned') }}
                                                        </span>
                                                    @endif
                                                </h5>
                                            </a>
                                            <p class="mb-0 fs-6">
                                                <span class="text-muted small">
                                                    {{ translate('Member since :date', ['date' => dateFormat($author->created_at, 'M Y')]) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @if (!$author->isBanned())
                                    <div class="col-auto">
                                        <livewire:follow-button :user="$author" :iconButton="true" />
                                    </div>
                                @endif
                            </div>
                            <div class="row row-cols-auto g-2">
                                @foreach ($userBadges as $userBadge)
                                    <div class="col">
                                        <div class="item-author-badge">
                                            <img src="{{ $userBadge->badge->getImageLink() }}"
                                                alt="{{ $userBadge->badge->name }}"
                                                title="{{ $userBadge->badge->getFullTitle() }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ $author->getPortfolioLink() }}"
                                class="btn btn-outline-secondary w-100 mt-4">
                                {{ translate('View Portfolio') }}
                            </a>
                        </div>
                        @if ($item->isPurchasingEnabled() && $item->hasSales())
                            <div class="card-v border p-4 mt-4">
                                <h5 class="mb-0">
                                    <i class="fa fa-cart-shopping me-2"></i>
                                    {{ translate($item->total_sales > 1 ? ':count Sales' : ':count Sale', [
                                        'count' => number_format($item->total_sales),
                                    ]) }}
                                </h5>
                            </div>
                        @endif
                        @if (@$itemSettings->free_item_total_downloads && $item->isFree() && $item->free_downloads > 0)
                            <div class="card-v border p-4 mt-4">
                                <h5 class="mb-0">
                                    <i class="fa fa-download me-2"></i>
                                    {{ translate($item->free_downloads > 1 ? ':count Downloads' : ':count Download', ['count' => numberFormat($item->free_downloads)]) }}
                                </h5>
                            </div>
                        @endif
                        <div class="card-v border p-4 mt-4">
                            <div class="small">
                                @if ($item->last_update_at)
                                    <div class="d-flex justify-content-between border-bottom pb-3 mb-3"">
                                        <p class="mb-0">{{ translate('Last Update') }}:</p>
                                        <p class="mb-0 ms-2">{{ dateFormat($item->last_update_at) }}</p>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between border-bottom pb-3 mb-3"">
                                    <p class="mb-0">{{ translate('Published') }}:</p>
                                    <p class="mb-0 ms-2">{{ dateFormat($item->created_at) }}</p>
                                </div>
                                @if ($item->version)
                                    <div class="d-flex justify-content-between border-bottom pb-3 mb-3"">
                                        <p class="mb-0">{{ translate('Version') }}:</p>
                                        <p class="mb-0 ms-2">
                                            @if (@$settings->item->changelogs_status && $item->hasChangelogs())
                                                <a href="{{ $item->getChangelogsLink() }}">
                                                    {{ translate('v:version', ['version' => $item->version]) }}
                                                </a>
                                            @else
                                                <span>{{ translate('v:version', ['version' => $item->version]) }}</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between border-bottom pb-3 mb-3"">
                                    <p class="mb-0">{{ translate('Category') }}:</p>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb justify-content-center m-0">
                                            <li class="breadcrumb-item">
                                                <a
                                                    href="{{ $item->category->getLink() }}">{{ $item->category->name }}</a>
                                            </li>
                                            @if ($item->subCategory)
                                                <li class="breadcrumb-item">
                                                    <a
                                                        href="{{ $item->subCategory->getLink() }}">{{ $item->subCategory->name }}</a>
                                                </li>
                                            @endif
                                        </ol>
                                    </nav>
                                </div>
                                @if ($item->options && count($item->options) > 0)
                                    @foreach ($item->options as $key => $value)
                                        <div class="d-flex justify-content-between border-bottom pb-3 mb-3">
                                            <p class="mb-0">{{ $key }}:</p>
                                            @if (is_array($value))
                                                <div class="col-7 text-end ms-2">
                                                    @foreach ($value as $option)
                                                        <a
                                                            href="{{ route('items.index', ['search' => strtolower($option)]) }}">
                                                            {{ $option }}
                                                        </a>{{ !$loop->last ? ',' : '' }}
                                                    @endforeach
                                                </div>
                                            @else
                                                <span>{{ $value }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">{{ translate('Tags') }}:</p>
                                    <div class="col-7 text-end ms-2">
                                        @foreach ($item->getTags() as $tag)
                                            <a href="{{ route('items.index', ['search' => strtolower($tag)]) }}">
                                                {{ $tag }}</a>{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-v border p-4 mt-4">
                            <div class="d-flex align-items-center gap-3">
                                <span class="fs-5">{{ translate('Share') }}:</span>
                                @include('themes.basic.partials.share-buttons', [
                                    'link' => $item->getLink(),
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <x-ad alias="item_page_center" @class('container my-4') />
    @if ($authorItems->count() > 0)
        <div class="section section-start">
            <div class="container">
                <div class="section-header">
                    <div
                        class="row row-cols-auto align-items-center justify-content-center justify-content-lg-between g-3">
                        <div class="col">
                            <div class="section-title mb-0">
                                <h2 class="section-title-text">
                                    {{ translate(":username's items", ['username' => $author->username]) }}
                                </h2>
                                <div class="section-title-divider"></div>
                            </div>
                        </div>
                        <div class="col d-none d-lg-block">
                            <a href="{{ $author->getPortfolioLink() }}">
                                {{ translate('View More') }}
                                <i class="fa fa-chevron-right fa-rtl fa-sm ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                        @foreach ($authorItems as $authorItem)
                            <div class="col">
                                @include('themes.basic.partials.item', [
                                    'item' => $authorItem,
                                    'item_classes' => 'border',
                                ])
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-5 d-block d-lg-none">
                        <a href="{{ $author->getPortfolioLink() }}" class="btn btn-primary btn-md btn-icon">
                            {{ translate('View More') }}
                            <i class="fa fa-arrow-right fa-rtl ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($similarItems->count() > 0)
        <div class="section section-start">
            <div class="container">
                <div class="section-header">
                    <div
                        class="row row-cols-auto align-items-center justify-content-center justify-content-lg-between g-3">
                        <div class="col">
                            <div class="section-title mb-0">
                                <h2 class="section-title-text">{{ translate('Similar items') }}</h2>
                                <div class="section-title-divider"></div>
                            </div>
                        </div>
                        <div class="col d-none d-lg-block">
                            <a
                                href="{{ $item->subCategory ? $item->subCategory->getLink() : $item->category->getLink() }}">
                                {{ translate('View More') }}
                                <i class="fa fa-chevron-right fa-rtl fa-sm ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                        @foreach ($similarItems as $similarItem)
                            <div class="col">
                                @include('themes.basic.partials.item', [
                                    'item' => $similarItem,
                                    'item_classes' => 'border',
                                ])
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-5 d-block d-lg-none">
                        <a href="{{ $item->subCategory ? $item->subCategory->getLink() : $item->category->getLink() }}"
                            class="btn btn-primary btn-md btn-icon">
                            {{ translate('View More') }}
                            <i class="fa fa-arrow-right fa-rtl ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <x-ad alias="item_page_bottom" @class('container mb-5') />
    @include('themes.basic.includes.footer')
    @include('themes.basic.includes.config')
    @push('scripts_libs')
        <script src="{{ asset('vendor/libs/swiper/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/libs/jquery/fancybox/jquery.fancybox.min.js') }}"></script>
    @endpush
    @include('themes.basic.includes.scripts')
</body>

</html>
