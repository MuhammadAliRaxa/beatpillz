<div class="col-lg-3">
    <div class="card mb-4">
        <ul class="sidebar-list-group list-group list-group-flush">
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
            {{ request()->routeIs('admin.members.users.edit') ? 'active' : '' }}"
                href="{{ route('admin.members.users.edit', $user->id) }}">
                <span><i class="fa fa-edit me-2"></i>{{ translate('Account details') }}</span>
                <i class="fa-solid fa-chevron-right fa-rtl"></i>
            </a>
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
             {{ request()->routeIs('admin.members.users.balance.index') ? 'active' : '' }}"
                href="{{ route('admin.members.users.balance.index', $user->id) }}">
                <span><i class="fa-solid fa-money-check-dollar me-2"></i>{{ translate('Account balance') }}</span>
                <i class="fa-solid fa-chevron-right fa-rtl"></i>
            </a>
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
            {{ request()->routeIs('admin.members.users.profile.index') ? 'active' : '' }}"
                href="{{ route('admin.members.users.profile.index', $user->id) }}">
                <span><i class="fa fa-user me-2"></i>{{ translate('Profile details') }}</span>
                <i class="fa-solid fa-chevron-right fa-rtl"></i>
            </a>
            @if ($user->isAuthor())
                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                {{ request()->routeIs('admin.members.users.withdrawal.index') ? 'active' : '' }}"
                    href="{{ route('admin.members.users.withdrawal.index', $user->id) }}">
                    <span><i class="fa-solid fa-building-columns me-2"></i>{{ translate('Withdrawal details') }}</span>
                    <i class="fa-solid fa-chevron-right fa-rtl"></i>
                </a>
            @endif
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
            {{ request()->routeIs('admin.members.users.badges.index') ? 'active' : '' }}"
                href="{{ route('admin.members.users.badges.index', $user->id) }}">
                <span><i class="fa-solid fa-certificate me-2"></i>{{ translate('Badges') }}</span>
                <i class="fa-solid fa-chevron-right fa-rtl"></i>
            </a>
            @if (@$settings->actions->api)
                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                {{ request()->routeIs('admin.members.users.api-key.index') ? 'active' : '' }}"
                    href="{{ route('admin.members.users.api-key.index', $user->id) }}">
                    <span><i class="fa-solid fa-key me-2"></i>{{ translate('API Key') }}</span>
                    <i class="fa-solid fa-chevron-right fa-rtl"></i>
                </a>
            @endif
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
            {{ request()->routeIs('admin.members.users.password.index') ? 'active' : '' }}"
                href="{{ route('admin.members.users.password.index', $user->id) }}">
                <span><i class="fa fa-lock me-2"></i>{{ translate('Password') }}</span>
                <i class="fa-solid fa-chevron-right fa-rtl"></i>
            </a>
            @if ($user->isAuthor())
                @if (@$settings->referral->status)
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                    {{ request()->routeIs('admin.members.users.referrals') ? 'active' : '' }}"
                        href="{{ route('admin.members.users.referrals', $user->id) }}">
                        <span><i class="fa fa-users me-2"></i>{{ translate('Referrals') }}</span>
                        <i class="fa-solid fa-chevron-right fa-rtl"></i>
                    </a>
                @endif
            @endif
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
            {{ request()->routeIs('admin.members.users.actions.index') ? 'active' : '' }}"
                href="{{ route('admin.members.users.actions.index', $user->id) }}">
                <span><i class="fa-solid fa-gears me-2"></i>{{ translate('Actions') }}</span>
                <i class="fa-solid fa-chevron-right fa-rtl"></i>
            </a>
            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
            {{ request()->routeIs('admin.members.users.login-logs') ? 'active' : '' }}"
                href="{{ route('admin.members.users.login-logs', $user->id) }}">
                <span><i class="fa fa-key me-2"></i>{{ translate('Login Logs') }}</span>
                <i class="fa-solid fa-chevron-right fa-rtl"></i>
            </a>
        </ul>
    </div>
</div>
