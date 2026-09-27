@if ($categoriesSection && $homeCategories->count() > 0)
    <section class="section section-start">
        <div class="container container-custom">
            <div class="section-header">
                <div class="row row-cols-auto align-items-center justify-content-center justify-content-lg-between g-3">
                    <div class="col">
                        <div class="section-title mb-0">
                            <h2 class="section-title-text">{{ $categoriesSection->name }}</h2>
                            <div class="section-title-divider"></div>
                        </div>
                        @if ($categoriesSection->description)
                            <p class="section-text mt-3">{{ $categoriesSection->description }}</p>
                        @endif
                    </div>
                    <div class="col d-none d-lg-block">
                        <a href="{{ route('categories.index') }}">
                            {{ translate('View All') }}
                            <i class="fa fa-chevron-right fa-sm fa-rtl ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="categories-swiper mt-3">
                    <div class="swiper-actions">
                        <div id="categoriesSwiperPrev" class="swiper-button-prev">
                            <i class="fa fa-chevron-left fa-rtl"></i>
                        </div>
                    </div>
                    <div class="swiper categoriesSwiper">
                        <div class="swiper-wrapper">
                            @foreach ($homeCategories as $homeCategory)
                                <div class="swiper-slide">
                                    <a href="{{ $homeCategory->link }}" class="home-category">
                                        <div class="home-category-img">
                                            <img src="{{ $homeCategory->getIcon() }}" alt="{{ $homeCategory->name }}" />
                                        </div>
                                        <h6 class="home-category-title">{{ $homeCategory->name }}</h6>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="swiper-actions">
                        <div id="categoriesSwiperNext" class="swiper-button-next">
                            <i class="fa fa-chevron-right fa-rtl"></i>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-5 d-block d-lg-none">
                    <a href="{{ route('categories.index') }}">
                        {{ translate('View All') }}
                        <i class="fa fa-chevron-right fa-sm fa-rtl ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif
