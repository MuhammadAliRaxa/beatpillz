@extends('themes.basic.blog.layout')
@section('header_title', translate('Blog'))
@section('title', translate('Blog'))
@section('breadcrumbs', Breadcrumbs::render('blog'))
@section('breadcrumbs_schema', Breadcrumbs::view('breadcrumbs::json-ld', 'blog'))
@section('header', true)
@section('content')
    <x-ad alias="blog_page_top" @class('mb-5') />
    @if ($blogArticles->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 justify-content-center g-4">
            @foreach ($blogArticles as $blogArticle)
                <div class="col">
                    @include('themes.basic.blog.partials.blog-post', ['blogArticle' => $blogArticle])
                </div>
            @endforeach
        </div>
        {{ $blogArticles->links() }}
    @else
        <div class="card-v border p-5 text-center">
            <span class="text-muted">{{ translate('No blog articles found') }}</span>
        </div>
    @endif
    <x-ad alias="blog_page_bottom" @class('mt-5') />
@endsection
