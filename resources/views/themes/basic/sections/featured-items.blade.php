@if ($featuredItemsSection && $featuredItems->count() > 0)
    <div class="section section-start">
        <div class="container container-custom">
            <div class="section-header d-block d-xxl-none">
                <div class="row row-cols-auto align-items-center justify-content-center justify-content-lg-between g-3">
                    <div class="col">
                        <div class="section-title mb-0">
                            <h2 class="section-title-text">{{ $featuredItemsSection->name }}</h2>
                            <div class="section-title-divider"></div>
                        </div>
                        @if ($featuredItemsSection->description)
                            <p class="section-text mt-3">{{ $featuredItemsSection->description }}</p>
                        @endif
                    </div>
                    <div class="col d-none d-lg-block">
                        <a href="{{ route('items.index', ['featured' => 'true']) }}">
                            {{ translate('View All') }}
                            <i class="fa fa-chevron-right fa-sm fa-rtl ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="row g-3">
                    <div class="col-xxl-4 d-none d-xxl-block">
                        <div class="card-v border border-primary border-dashed h-100 p-3" data-aos="fade-up"
                            data-aos-duration="1000">
                            <div class="card-v card-bg d-flex justify-content-center align-items-center h-100">
                                <div class="p-3">
                                    <h2 class="mb-2">{{ $featuredItemsSection->name }}</h2>
                                    @if ($featuredItemsSection->description)
                                        <p class="fw-light mb-3">{{ $featuredItemsSection->description }}</p>
                                    @endif
                                    <a href="{{ route('items.index', ['featured' => 'true']) }}"
                                        class="btn btn-primary btn-md w-100">
                                        {{ translate('View All Featured Items') }}
                                        <i class="fa fa-arrow-right fa-rtl ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xxl-8">
                        <div class="row row-cols-md-2 row-cols-lg-3 row-cols-xxl-2 g-4">
                            @foreach ($featuredItems as $featuredItem)
                                <div class="col" data-aos="fade-up" data-aos-duration="1500">
                                    @include('themes.basic.partials.item', ['item' => $featuredItem])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="text-center mt-5 d-block d-lg-none">
                    <a href="{{ route('items.index', ['featured' => 'true']) }}">
                        {{ translate('View All') }}
                        <i class="fa fa-chevron-right fa-sm fa-rtl ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
