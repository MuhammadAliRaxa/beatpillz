<div class="card-v">
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
                    <a href="{{ $itemUpdate->author->getProfileLink() }}" target="_blank" class="text-dark">
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
            <div class="row g-3 row-cols-1">
                <div class="col">
                    <a href="{{ route('reviewer.items.review', $itemUpdate->item->id) }}" target="_blank"
                        class="btn btn-outline-primary btn-md w-100">
                        <i class="fa-solid fa-up-right-from-square me-1"></i>
                        <span>{{ translate('Original Item') }}</span>
                    </a>
                </div>
                @if ($itemUpdate->main_file)
                    <div class="col">
                        @if ($itemUpdate->isMainFileExternal())
                            <a href="{{ $itemUpdate->main_file }}" target="_blank"
                                class="btn btn-primary btn-md w-100">
                                <i class="fa-solid fa-download me-2"></i>
                                {{ translate('Download') }}
                            </a>
                        @else
                            <form action="{{ route('reviewer.items.updated.download', $itemUpdate->id) }}"
                                method="POST">
                                @csrf
                                <button class="btn btn-primary btn-md w-100">
                                    <i class="fa-solid fa-download me-2"></i>
                                    {{ translate('Download') }}
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </li>
    </ul>
</div>
