@if ($testimonialsSection && $testimonials->count() > 0)
    <div class="section section-start">
        <div class="container container-custom">
            <div class="section-header">
                <div class="row row-cols-1 row-cols-lg-auto align-items-center justify-content-between g-3">
                    <div class="col-lg-8">
                        <div class="section-title mb-0">
                            <h2 class="section-title-text">{{ $testimonialsSection->name }}</h2>
                            <div class="section-title-divider"></div>
                        </div>
                        @if ($testimonialsSection->description)
                            <p class="section-text mt-3">{{ $testimonialsSection->description }}</p>
                        @endif
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-center">
                            <div class="testimonials-swiper-actions">
                                <div id="testimonialsSwiperPrev" class="swiper-button-prev">
                                    <i class="fa fa-chevron-left fa-rtl"></i>
                                </div>
                                <div id="testimonialsSwiperNext" class="swiper-button-next">
                                    <i class="fa fa-chevron-right fa-rtl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="testimonials-swiper mt-3">
                    <div class="swiper testimonialsSwiper">
                        <div class="swiper-wrapper">
                            @foreach ($testimonials as $testimonial)
                                <div class="swiper-slide" data-aos="zoom-in" data-aos-duration="1000">
                                    <div class="testimonial">
                                        <div class="testimonial-img">
                                            <img src="{{ $testimonial->getAvatar() }}" alt="{{ $testimonial->name }}" />
                                            <div class="testimonial-quote">
                                                <i class="fa-solid fa-quote-right"></i>
                                            </div>
                                        </div>
                                        <p class="testimonial-text">{{ $testimonial->body }}</p>
                                        <div class="testimonial-author">
                                            <h6 class="testimonial-name">{{ $testimonial->name }}</h6>
                                            <p class="testimonial-place">{{ $testimonial->title }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
