@if ($faqsSection && $faqs->count() > 0)
    <div class="section">
        <div class="container container-custom">
            <div class="section-header">
                <div class="col-lg-7 mx-auto">
                    <div class="section-title mb-0">
                        <h2 class="section-title-text">{{ $faqsSection->name }}</h2>
                        <div class="section-title-divider"></div>
                    </div>
                    @if ($faqsSection->description)
                        <p class="section-text mt-3">{{ $faqsSection->description }}</p>
                    @endif
                </div>
            </div>
            <div class="section-body">
                <div class="accordion-custom">
                    <div class="accordion" id="accordion">
                        <div class="row row-cols-1 row-cols-xl-2 g-3">
                            @foreach ($faqs as $faq)
                                <div class="col" data-aos="fade-right"
                                    data-aos-duration="{{ ($loop->index + 1) * 100 }}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $faq->id }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}"
                                                aria-expanded="false" aria-controls="collapse{{ $faq->id }}">
                                                <div class="accordion-button-icon">
                                                    <i class="fa fa-plus"></i>
                                                    <i class="fa fa-minus"></i>
                                                </div>
                                                {{ $faq->title }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#accordion">
                                            <div class="accordion-body">
                                                {!! $faq->body !!}
                                            </div>
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
