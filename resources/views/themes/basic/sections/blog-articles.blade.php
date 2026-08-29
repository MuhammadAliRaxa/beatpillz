@if (@$settings->actions->blog && $blogArticlesSection && $blogArticles->count() > 0)
    <div class="section">
        <div class="container container-custom">
            <div class="section-header">
                <div class="col-lg-7 mx-auto">
                    <div class="section-title mb-0">
                        <h2 class="section-title-text">{{ $blogArticlesSection->name }}</h2>
                        <div class="section-title-divider"></div>
                    </div>
                    @if ($blogArticlesSection->description)
                        <p class="section-text mt-3">{{ $blogArticlesSection->description }}</p>
                    @endif
                </div>
            </div>
            <div class="section-body">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xxl-3 justify-content-center g-3">
                    @foreach ($blogArticles as $blogArticle)
                        <div class="col" data-aos="fade-right" data-aos-duration="1000"
                            data-aos-delay="{{ ($loop->index + 1) * 100 }}">
                            <div class="blog-post h-100">
                                <div class="blog-post-header">
                                    <a href="{{ $blogArticle->getLink() }}">
                                        <img src="{{ $blogArticle->getImageLink() }}" alt="{{ $blogArticle->title }}"
                                            class="blog-post-img">
                                    </a>
                                </div>
                                <div class="blog-post-body">
                                    <div class="post-meta mb-2">
                                        <div class="post-meta-item">
                                            <i class="far fa-calendar"></i>
                                            <span>{{ dateFormat($blogArticle->created_at) }}</span>
                                        </div>
                                        <div class="post-meta-item">
                                            <i class="fa-solid fa-tag"></i>
                                            <span>
                                                <a href="{{ $blogArticle->category->getLink() }}">
                                                    {{ $blogArticle->category->name }}</a>
                                            </span>
                                        </div>
                                    </div>
                                    <h5 class="blog-post-title">
                                        <a href="{{ $blogArticle->getLink() }}">{{ $blogArticle->title }}</a>
                                    </h5>
                                    <p class="blog-post-text">
                                        {{ $blogArticle->short_description }}
                                    </p>
                                    <div class="mt-auto">
                                        <a href="{{ $blogArticle->getLink() }}">
                                            {{ translate('Read More') }}<i
                                                class="fa fa-arrow-right fa-sm fa-rtl ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('blog.index') }}" class="btn btn-primary btn-md">
                        {{ translate('View All') }}
                        <i class="fa fa-arrow-right fa-rtl ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
