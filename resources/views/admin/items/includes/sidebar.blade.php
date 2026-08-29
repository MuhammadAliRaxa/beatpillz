<div class="card p-2 shadow-sm">
    <div class="card-body p-4 py-3">
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
                                <li class="breadcrumb-item fs-6">
                                    <a href="{{ route('categories.category', $item->category->slug) }}"
                                        target="_blank">{{ $item->category->name }}</a>
                                </li>
                                @if ($item->subCategory)
                                    <li class="breadcrumb-item fs-6">
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
                        <a href="{{ route('admin.members.users.edit', $item->author->id) }}" class="text-dark">
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
                        @elseif($item->isDeleted())
                            <div class="badge bg-danger rounded-2 fw-light px-3 py-2">
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
                <div class="row row-cols-1 g-3">
                    @if (!$item->isDeleted())
                        <div class="col">
                            @if ($item->isMainFileExternal())
                                <a href="{{ $item->main_file }}" target="_blank" class="btn btn-primary btn-lg w-100">
                                    <i class="fa-solid fa-download me-1"></i>
                                    {{ translate('Download') }}
                                </a>
                            @else
                                <a href="{{ route('admin.items.download', $item->id) }}"
                                    class="btn btn-primary btn-lg w-100">
                                    <i class="fa-solid fa-download me-1"></i>
                                    {{ translate('Download') }}
                                </a>
                            @endif
                        </div>
                    @endif
                    @if ($item->isApproved())
                        @if (!$item->isFeatured())
                            <form action="{{ route('admin.items.featured', $item->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-outline-success btn-lg w-100 action-confirm">
                                    <i class="fa-solid fa-certificate me-1"></i>
                                    {{ translate('Make Featured') }}
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.items.featured.remove', $item->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-outline-danger btn-lg w-100 action-confirm">
                                    <i class="fa-solid fa-certificate me-1"></i>
                                    {{ translate('Remove Featured') }}
                                </button>
                            </form>
                        @endif
                        @if (licenseType(2) && @$settings->premium->status && !$item->isFree())
                            @if (!$item->isPremium())
                                <form action="{{ route('admin.items.premium', $item->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-warning btn-lg w-100 action-confirm">
                                        <i class="fa-solid fa-crown me-1"></i>
                                        {{ translate('Add to premium') }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.items.premium.remove', $item->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-danger btn-lg w-100 action-confirm">
                                        <i class="fa-solid fa-crown me-1"></i>
                                        {{ translate('Remove from premium') }}
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endif
                    @if (!$item->isDeleted())
                        <form action="{{ route('admin.items.soft-delete', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-lg w-100 action-confirm">
                                <i class="far fa-trash-alt me-1"></i>
                                {{ translate('Soft Delete') }}
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('admin.items.permanently-delete', $item->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-lg w-100 action-confirm">
                            <i class="fa-solid fa-trash-can me-1"></i>
                            {{ translate('Permanently Delete') }}
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
