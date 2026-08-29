@extends('themes.basic.blog.layout')
@section('title', $blogArticle->title)
@section('breadcrumbs_schema', Breadcrumbs::view('breadcrumbs::json-ld', 'blog_article', $blogArticle))
@section('og_image', $blogArticle->getImageLink())
@section('description', $blogArticle->short_description)
@section('content')
    <div class="blog-container">
        <div class="row row-cols-1 g-4">
            <div class="col">
                <div class="blog-post v2 blog-post-single">
                    <div class="text-center mb-4">
                        <h1 class="blog-post-title h2 mb-3">{{ $blogArticle->title }}</h1>
                        <div class="blog-post-meta">
                            <div class="row row-cols-auto align-items-center justify-content-center gx-3 gy-2">
                                <div class="col">
                                    <div class="blog-post-meta-item"><a
                                            href="{{ $blogArticle->category->getLink() }}">{{ $blogArticle->category->name }}</a>
                                    </div>
                                </div>
                                <div class="col d-flex align-items-center text-muted small">
                                    <i class="far fa-calendar me-2"></i>
                                    <span>{{ dateFormat($blogArticle->created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="blog-post-header">
                        <img src="{{ $blogArticle->getImageLink() }}" alt="{{ $blogArticle->title }}" class="blog-post-img">
                    </div>
                    <div class="blog-post-body px-sm-5">
                        <x-ad alias="blog_article_page_top" @class('mb-4') />
                        <div class="blog-post-single-text">
                            {!! $blogArticle->body !!}
                        </div>
                        <x-ad alias="blog_article_page_bottom" @class('mt-4') />
                        @include('themes.basic.partials.share-buttons', [
                            'socials_classes' => 'mt-4',
                            'link' => $blogArticle->getLink(),
                        ])
                        <div class="comments">
                            <h5 class="comments-title">
                                <i class="far fa-comments me-2"></i>{{ translate('Comments') }}
                                ({{ $blogArticleComments->count() }})
                            </h5>
                            @forelse ($blogArticleComments as $blogArticleComment)
                                @php
                                    $user = $blogArticleComment->user;
                                @endphp
                                <div class="card-v border p-4 mb-3">
                                    <div class="comment">
                                        <div class="comment-info">
                                            <div class="comment-img">
                                                <a href="{{ $user->getProfileLink() }}">
                                                    <img src="{{ $user->getAvatar() }}" alt="{{ $user->username }}">
                                                </a>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="{{ $user->getProfileLink() }}" class="text-dark">
                                                    <h6 class="comment-title mb-1">
                                                        {{ $user->username }}
                                                    </h6>
                                                </a>
                                                <time class="comment-time small text-muted">
                                                    <i class="far fa-calendar me-1"></i>
                                                    {{ dateFormat($blogArticleComment->created_at) }}
                                                </time>
                                            </div>
                                        </div>
                                        <p class="comment-text mb-0">{!! purifier($blogArticleComment->body) !!}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-v border p-4">
                            @if (authUser())
                                <h5 class="mb-4">{{ translate('Leave a comment') }}</h5>
                                <form action="{{ $blogArticle->getLink() }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea class="form-control form-control-md" name="comment" rows="4"
                                            placeholder="{{ translate('Your comment') }}" required>{{ old('comment') }}</textarea>
                                    </div>
                                    <x-captcha />
                                    <button class="btn btn-primary btn-md">{{ translate('Publish') }}</button>
                                </form>
                            @else
                                <div class="text-center">
                                    {{ translate('Login or create account to leave comments') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('schema')
        {!! schema($__env, 'article', ['article' => $blogArticle]) !!}
    @endpush
@endsection
