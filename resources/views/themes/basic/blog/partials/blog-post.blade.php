<div class="blog-post v2">
    <div class="blog-post-header">
        <a href="{{ $blogArticle->getLink() }}">
            <img src="{{ $blogArticle->getImageLink() }}" alt="{{ $blogArticle->title }}" class="blog-post-img">
        </a>
    </div>
    <div class="blog-post-body">
        <div class="blog-post-meta">
            <div class="row row-cols-auto align-items-center gx-3 gy-2">
                <div class="col">
                    <div class="blog-post-meta-item">
                        <a href="{{ $blogArticle->category->getLink() }}">
                            {{ $blogArticle->category->name }}
                        </a>
                    </div>
                </div>
                <div class="col d-flex align-items-center text-muted small">
                    <i class="far fa-calendar me-2"></i>
                    <span>{{ dateFormat($blogArticle->created_at) }}</span>
                </div>
            </div>
        </div>
        <h4 class="blog-post-title">
            <a href="{{ $blogArticle->getLink() }}">{{ $blogArticle->title }}</a>
        </h4>
        <p class="blog-post-text">
            {{ $blogArticle->short_description }}
            <a href="{{ $blogArticle->getLink() }}">
                [{{ translate('Read More...') }}]
            </a>
        </p>
    </div>
</div>
