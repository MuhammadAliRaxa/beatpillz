<div class="card p-2 shadow-sm">
    <div class="card-body p-4 py-3">
        <ul class="list-group list-group-flush">
            <li class="list-group-item py-3 px-0">
                <div class="row align-items-center g-3">
                    <div class="col">
                        <strong>{{ translate('ID') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span>#{{ $itemUpdate->id }}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item py-3 px-0">
                <div class="row align-items-center g-3">
                    <div class="col">
                        <strong>{{ translate('Name') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span>{{ $itemUpdate->name }}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item py-3 px-0">
                <div class="row align-items-center g-3">
                    <div class="col">
                        <strong>{{ translate('Author') }}</strong>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.members.users.edit', $itemUpdate->author->id) }}" class="text-dark">
                            <i class="fa fa-user me-1"></i>
                            <span>{{ $itemUpdate->author->username }}</span>
                        </a>
                    </div>
                </div>
            </li>
            <li class="list-group-item py-3 px-0">
                <div class="row align-items-center g-3">
                    <div class="col">
                        <strong>{{ translate('Submitted Date') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span>{{ dateFormat($itemUpdate->created_at) }}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item py-3 px-0">
                <div class="row row-cols-1 g-3">
                    <div class="col">
                        <a href="{{ route('admin.items.show', $itemUpdate->item->id) }}" target="_blank"
                            class="btn btn-outline-secondary btn-lg w-100">
                            <i class="fa-solid fa-up-right-from-square me-1"></i>
                            <span>{{ translate('Original Item') }}</span>
                        </a>
                    </div>
                    @if ($itemUpdate->main_file)
                        <div class="col">
                            @if ($itemUpdate->isMainFileExternal())
                                <a href="{{ $itemUpdate->main_file }}" target="_blank"
                                    class="btn btn-primary btn-lg w-100">
                                    <i class="fa-solid fa-download me-1"></i>
                                    {{ translate('Download') }}
                                </a>
                            @else
                                <a href="{{ route('admin.items.updated.download', $itemUpdate->id) }}"
                                    class="btn btn-primary btn-lg w-100">
                                    <i class="fa-solid fa-download me-1"></i>
                                    {{ translate('Download') }}
                                </a>
                            @endif
                        </div>
                    @endif
                    <form action="{{ route('admin.items.updated.destroy', $itemUpdate->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-lg w-100 action-confirm">
                            <i class="fa-solid fa-trash-can me-1"></i>
                            {{ translate('Delete') }}
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
