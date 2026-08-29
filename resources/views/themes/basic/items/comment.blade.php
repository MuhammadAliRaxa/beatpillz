@extends('themes.basic.layouts.single')
@section('noindex', true)
@section('header_title', $item->name)
@section('title', $item->name)
@section('breadcrumbs', Breadcrumbs::render('items.comments.comment', $item, $comment))
@section('header_v1', true)
@section('content')
    <div class="col-lg-8">
        <div class="item-comments">
            <div class="row row-cols-1">
                <livewire:item.comment-replies :comment="$comment" wire:key="{{ hash_encode($comment->id) }}" />
            </div>
        </div>
    </div>
    <livewire:item.comment-report />
@endsection
