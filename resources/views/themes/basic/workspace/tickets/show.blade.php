@extends('themes.basic.workspace.layouts.app')
@section('section', translate('Tickets'))
@section('title', translate('Ticket #:id', ['id' => $ticket->id]))
@section('breadcrumbs', Breadcrumbs::render('workspace.tickets.show', $ticket))
@section('back', route('workspace.tickets.index'))
@section('content')
    <div class="row">
        <div class="col-lg-6 col-xl-7 col-xxl-8">
            <div class="row g-3 mb-3">
                @foreach ($ticket->replies as $reply)
                    <div class="col-12">
                        <div class="card-v p-4">
                            <div class="conversation p-2">
                                <div class="mb-4">
                                    <div class="row row-cols-auto justify-content-between align-items-center g-3">
                                        <div class="col">
                                            @if ($reply->user)
                                                @php
                                                    $user = $reply->user;
                                                @endphp
                                                <a href="{{ $user->getProfileLink() }}" target="_blank"
                                                    class="conversation-user">
                                                    <img src="{{ $user->getAvatar() }}" alt="{{ $user->username }}">
                                                    <span class="h6 mb-0">{{ $user->username }}</span>
                                                </a>
                                            @else
                                                @php
                                                    $admin = $reply->admin;
                                                @endphp
                                                <div class="conversation-user">
                                                    <img src="{{ $admin->getAvatar() }}" alt="{{ $admin->username }}">
                                                    <span class="h6 mb-0">{{ $admin->username }}</span>
                                                    <div class="badge bg-primary rounded-2 fw-light px-3 py-2 ms-2">
                                                        {{ translate('Support') }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <time class="text-muted small">{{ dateFormat($reply->created_at) }}</time>
                                        </div>
                                    </div>
                                </div>
                                <div class="conversation-content">
                                    @if ($loop->first)
                                        <h5 class="mb-3">{{ $ticket->subject }}</h5>
                                    @endif
                                    {!! purifier($reply->body) !!}
                                    @if ($reply->attachments->count() > 0)
                                        <div class="mt-4">
                                            <h6 class="text-dark mb-3">
                                                {{ translate('Attached files') }}:
                                            </h6>
                                            <div class="row align-items-center g-3">
                                                @foreach ($reply->attachments as $attachment)
                                                    <div class="col-lg-6">
                                                        <a href="{{ route('workspace.tickets.download', [$ticket->id, $attachment->id]) }}"
                                                            class="d-block text-muted bg-light p-3 border rounded-2 h-100">
                                                            <div class="row align-items-center g-2">
                                                                <div class="col-auto">
                                                                    <i class="fa fa-file-alt fa-lg"></i>
                                                                </div>
                                                                <div class="col">
                                                                    <h6 class="mb-0">
                                                                        {{ shorterText($attachment->name, 40) }}
                                                                    </h6>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <i class="fa fa-download"></i>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col">
                    <div class="card-v p-4">
                        <div class="p-2">
                            <h5 class="mb-3">{{ translate('Reply') }}</h5>
                            <form action="{{ route('workspace.tickets.reply', $ticket->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3 mb-3">
                                    <div class="col-lg-12">
                                        <textarea name="reply" class="form-control" rows="5" required>{{ old('reply') }}</textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="attachments">
                                            <div class="attachment-box-1">
                                                <label class="form-label">{{ translate('Attachments') }}
                                                    ({{ @$settings->ticket->file_types }}) </label>
                                                <div class="input-group">
                                                    <input type="file" name="attachments[]"
                                                        class="form-control form-control-md">
                                                    <button id="addAttachment" class="btn btn-outline-secondary"
                                                        type="button">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-md">{{ translate('Send') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-5 col-xxl-4">
            <div class="card-v p-4">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row align-items-center g-3">
                            <div class="col">
                                <strong>{{ translate('Ticket ID') }}</strong>
                            </div>
                            <div class="col-auto">
                                <span>#{{ $ticket->id }}</span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item p-3">
                        <div class="row align-items-center g-3">
                            <div class="col">
                                <strong>{{ translate('Category') }}</strong>
                            </div>
                            <div class="col-auto">
                                <span>{{ $ticket->category->name }}</span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item p-3">
                        <div class="row align-items-center g-3">
                            <div class="col">
                                <strong>{{ translate('Status') }}</strong>
                            </div>
                            <div class="col-auto">
                                @if ($ticket->isOpened())
                                    <span class="badge bg-green rounded-2 fw-light px-3 py-2">
                                        {{ $ticket->getStatusName() }}
                                    </span>
                                @else
                                    <span class="badge bg-red rounded-2 fw-light px-3 py-2">
                                        {{ $ticket->getStatusName() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item p-3">
                        <div class="row align-items-center g-3">
                            <div class="col">
                                <strong>{{ translate('Created Date') }}</strong>
                            </div>
                            <div class="col-auto">
                                <span>{{ dateFormat($ticket->created_at) }}</span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item p-3">
                        <div class="row align-items-center g-3">
                            <div class="col">
                                <strong>{{ translate('Last Activity') }}</strong>
                            </div>
                            <div class="col-auto">
                                <span>{{ dateFormat($ticket->updated_at) }}</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @push('top_scripts')
        <script>
            "use strict";
            const ticketsConfig = {!! json_encode([
                'max_file' => @$settings->ticket->max_files,
                'max_files_error' => translate('Max :max files can be uploaded', [
                    'max' => @$settings->ticket->max_files,
                ]),
            ]) !!}
        </script>
    @endpush
@endsection
