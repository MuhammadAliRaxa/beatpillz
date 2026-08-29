<div class="col-lg-3">
    <div class="card mb-4">
        <ul class="sidebar-list-group list-group list-group-flush">
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('admin.members.reviewers.edit') ? 'active' : '' }}"
                href="{{ route('admin.members.reviewers.edit', $reviewer->id) }}">
                <span><i class="fa fa-edit me-2"></i>{{ translate('Account details') }}</span>
                <i class="fa-solid fa-chevron-right fa-rtl"></i>
            </a>
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('admin.members.reviewers.actions.index') ? 'active' : '' }}"
                href="{{ route('admin.members.reviewers.actions.index', $reviewer->id) }}">
                <span><i class="fa-solid fa-gears me-2"></i>{{ translate('Actions') }}</span>
                <i class="fa-solid fa-chevron-right fa-rtl"></i>
            </a>
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('admin.members.reviewers.password.index') ? 'active' : '' }}"
                href="{{ route('admin.members.reviewers.password.index', $reviewer->id) }}">
                <span><i class="fa fa-lock me-2"></i>{{ translate('Password') }}</span>
                <i class="fa-solid fa-chevron-right fa-rtl"></i>
            </a>
        </ul>
    </div>
    <a class="btn btn-outline-dark btn-lg w-100" href="{{ route('admin.members.reviewers.login', $reviewer->id) }}"
        target="_blank">
        <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>{{ translate('Login as Reviewer') }}
    </a>
</div>
