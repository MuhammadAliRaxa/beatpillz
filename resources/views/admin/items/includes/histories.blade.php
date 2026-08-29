<div class="row g-3">
    @forelse ($itemHistories as $itemHistory)
        <div class="col-lg-12">
            <div class="conversation">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="row row-cols-auto justify-content-between align-items-center g-3">
                                <div class="col">
                                    @if ($itemHistory->author)
                                        @php
                                            $author = $itemHistory->author;
                                        @endphp
                                        <a href="{{ route('admin.members.users.edit', $author->id) }}"
                                            class="conversation-user">
                                            <img src="{{ $author->getAvatar() }}" alt="{{ $author->username }}">
                                            <span class="h6 mb-0">{{ $author->username }}</span>
                                        </a>
                                    @elseif ($itemHistory->reviewer)
                                        @php
                                            $reviewer = $itemHistory->reviewer;
                                        @endphp
                                        <a href="{{ route('admin.members.reviewers.edit', $reviewer->id) }}"
                                            class="conversation-user">
                                            <img src="{{ $reviewer->getAvatar() }}" alt="{{ $reviewer->username }}">
                                            <span class="h6 mb-0">{{ $reviewer->username }}</span>
                                        </a>
                                    @elseif ($itemHistory->admin)
                                        @php
                                            $admin = $itemHistory->admin;
                                        @endphp
                                        @if ($admin->id == authAdmin()->id)
                                            <div class="conversation-user">
                                                <img src="{{ $admin->getAvatar() }}" alt="{{ $admin->username }}">
                                                <span class="h6 mb-0">{{ $admin->username }}</span>
                                            </div>
                                        @else
                                            <a href="{{ route('admin.members.admins.edit', $admin->id) }}"
                                                class="conversation-user">
                                                <img src="{{ $admin->getAvatar() }}" alt="{{ $admin->username }}">
                                                <span class="h6 mb-0">{{ $admin->username }}</span>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                                <div class="col">
                                    <time class="text-muted small">{{ dateFormat($itemHistory->created_at) }}</time>
                                </div>
                            </div>
                        </div>
                        <div class="conversation-content mb-3">
                            <h5 class="mb-0">{{ translate($itemHistory->title) }}</h5>
                            @if ($itemHistory->body)
                                <div class="text-muted mt-3">{!! purifier($itemHistory->body) !!}</div>
                            @endif
                        </div>
                        <div class="text-end">
                            <form
                                action="{{ route('admin.items.history.delete', [$itemHistory->item->id, $itemHistory->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger action-confirm btn-sm">
                                    <i class="far fa-trash-alt me-1"></i>
                                    {{ translate('Delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('admin.partials.empty', ['empty_classes' => 'empty-lg'])
                </div>
            </div>
        </div>
    @endforelse
</div>
{{ $itemHistories->links() }}
