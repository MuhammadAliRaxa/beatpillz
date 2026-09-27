<div class="card-v">
    <ul class="list-group list-group-flush">
        <li class="list-group-item py-3 px-0">
            <div class="row align-items-center g-3">
                <div class="col">
                    <strong>{{ translate('ID') }}</strong>
                </div>
                <div class="col-auto">
                    <span>#{{ $item->id }}</span>
                </div>
            </div>
        </li>
        <li class="list-group-item py-3 px-0">
            <div class="row align-items-center g-3">
                <div class="col">
                    <strong>{{ translate('Name') }}</strong>
                </div>
                <div class="col-auto">
                    <span>{{ $item->name }}</span>
                </div>
            </div>
        </li>
        <li class="list-group-item py-3 px-0">
            <div class="row align-items-center g-3">
                <div class="col">
                    <strong>{{ translate('Category') }}</strong>
                </div>
                <div class="col-auto">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('categories.category', $item->category->slug) }}"
                                    target="_blank">{{ $item->category->name }}</a>
                            </li>
                            @if ($item->subCategory)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('categories.sub-category', [$item->category->slug, $item->subCategory->slug]) }}"
                                        target="_blank">{{ $item->subCategory->name }}</a>
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </li>
        <li class="list-group-item py-3 px-0">
            <div class="row align-items-center g-3">
                <div class="col">
                    <strong>{{ translate('Author') }}</strong>
                </div>
                <div class="col-auto">
                    <a href="{{ $item->author->getProfileLink() }}" target="_blank" class="text-dark">
                        <i class="fa fa-user me-1"></i>
                        <span>{{ $item->author->username }}</span>
                    </a>
                </div>
            </div>
        </li>
        <li class="list-group-item py-3 px-0">
            <div class="row align-items-center g-3">
                <div class="col">
                    <strong>{{ translate('Status') }}</strong>
                </div>
                <div class="col-auto">
                    @if ($item->isPending())
                        <div class="badge bg-orange rounded-2 fw-light px-3 py-2">
                            {{ $item->getStatusName() }}
                        </div>
                    @elseif($item->isSoftRejected())
                        <div class="badge bg-purple rounded-2 fw-light px-3 py-2">
                            {{ $item->getStatusName() }}
                        </div>
                    @elseif($item->isResubmitted())
                        <div class="badge bg-blue rounded-2 fw-light px-3 py-2">
                            {{ $item->getStatusName() }}
                        </div>
                    @elseif($item->isApproved())
                        <div class="badge bg-green rounded-2 fw-light px-3 py-2">
                            {{ $item->getStatusName() }}
                        </div>
                    @elseif($item->isHardRejected())
                        <div class="badge bg-red rounded-2 fw-light px-3 py-2">
                            {{ $item->getStatusName() }}
                        </div>
                    @endif
                </div>
            </div>
        </li>
        @if ($item->last_update_at)
            <li class="list-group-item py-3 px-0">
                <div class="row align-items-center g-3">
                    <div class="col">
                        <strong>{{ translate('Last Update') }}</strong>
                    </div>
                    <div class="col-auto">
                        <span>{{ dateFormat($item->last_update_at) }}</span>
                    </div>
                </div>
            </li>
        @endif
        <li class="list-group-item py-3 px-0">
            <div class="row align-items-center g-3">
                <div class="col">
                    <strong>{{ translate('Published Date') }}</strong>
                </div>
                <div class="col-auto">
                    <span>{{ dateFormat($item->created_at) }}</span>
                </div>
            </div>
        </li>
        <li class="list-group-item py-3 px-0">
            <div class="row g-3 row-cols-1">
                @if ($item->isApproved())
                    <div class="col">
                        <a href="{{ $item->getLink() }}" target="_blank" class="btn btn-outline-primary btn-md w-100">
                            <i class="fa-solid fa-up-right-from-square me-1"></i>
                            <span>{{ translate('View Item') }}</span>
                        </a>
                    </div>
                @endif
                <div class="col">
                    @if ($item->isMainFileExternal())
                        <a href="{{ $item->main_file }}" target="_blank" class="btn btn-primary btn-md w-100">
                            <i class="fa-solid fa-download me-2"></i>
                            {{ translate('Download') }}
                        </a>
                    @else
                        <form action="{{ route('reviewer.items.download', $item->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-md w-100">
                                <i class="fa-solid fa-download me-2"></i>
                                {{ translate('Download') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </li>
    </ul>
</div>
