@if ($freeItemsSection && $freeItems->count() > 0)
    <div class="section section-start">
        <div class="container container-custom">
            <div class="section-header">
                <div class="row row-cols-auto align-items-center justify-content-center justify-content-lg-between g-3">
                    <div class="col">
                        <div class="section-title mb-0">
                            <h2 class="section-title-text">{{ $freeItemsSection->name }}</h2>
                            <div class="section-title-divider"></div>
                        </div>
                        @if ($freeItemsSection->description)
                            <p class="section-text mt-3">{{ $freeItemsSection->description }}</p>
                        @endif
                    </div>
                    <div class="col d-none d-lg-block">
                        <a href="{{ route('items.index', ['free' => 'true']) }}">
                            {{ translate('View All') }}
                            <i class="fa fa-chevron-right fa-sm fa-rtl ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xxl-4 g-3">
                    @foreach ($freeItems as $freeItem)
                        <div class="col" data-aos="fade-up" data-aos-duration="1000">
                            @include('themes.basic.partials.item', ['item' => $freeItem])
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5 d-block d-lg-none">
                    <a href="{{ route('items.index', ['free' => 'true']) }}">
                        {{ translate('View All') }}
                        <i class="fa fa-chevron-right fa-sm fa-rtl ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
