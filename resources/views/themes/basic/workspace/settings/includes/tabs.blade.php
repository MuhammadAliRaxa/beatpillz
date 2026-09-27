<div class="settings-links mb-3">
    <div class="settings-links-inner">
        <a href="{{ route('workspace.settings.index') }}"
            class="settings-link {{ request()->routeIs('workspace.settings.index') ? 'active' : '' }}">
            <i class="fa-regular fa-pen-to-square me-1"></i>
            {{ translate('Account') }}
        </a>
        <a href="{{ route('workspace.settings.profile') }}"
            class="settings-link {{ request()->routeIs('workspace.settings.profile') ? 'active' : '' }}">
            <i class="fa fa-user me-1"></i>
            {{ translate('Profile') }}
        </a>
        @if (authUser()->isAuthor())
            <a href="{{ route('workspace.settings.withdrawal') }}"
                class="settings-link {{ request()->routeIs('workspace.settings.withdrawal') ? 'active' : '' }}">
                <i class="fa-solid fa-building-columns me-1"></i>
                {{ translate('Withdrawal') }}
            </a>
        @endif
        @if (licenseType(2) && @$settings->premium->status)
            <a href="{{ route('workspace.settings.subscription') }}"
                class="settings-link {{ request()->routeIs('workspace.settings.subscription') ? 'active' : '' }}">
                <i class="fa-solid fa-gem me-1"></i>
                {{ translate('Subscription') }}
            </a>
        @endif
        <a href="{{ route('workspace.settings.badges') }}"
            class="settings-link {{ request()->routeIs('workspace.settings.badges') ? 'active' : '' }}">
            <i class="fa-solid fa-certificate me-1"></i>
            {{ translate('My Badges') }}
        </a>
        @if (@$settings->actions->api)
            <a href="{{ route('workspace.settings.api-key') }}"
                class="settings-link {{ request()->routeIs('workspace.settings.api-key') ? 'active' : '' }}">
                <i class="fa-solid fa-key me-1"></i>
                {{ translate('API Key') }}
            </a>
        @endif
        <a href="{{ route('workspace.settings.password') }}"
            class="settings-link {{ request()->routeIs('workspace.settings.password') ? 'active' : '' }}">
            <i class="fa fa-lock me-1"></i>
            {{ translate('Password') }}
        </a>
        <a href="{{ route('workspace.settings.2fa') }}"
            class="settings-link {{ request()->routeIs('workspace.settings.2fa') ? 'active' : '' }}">
            <i class="fa-solid fa-shield-halved me-1"></i>
            {{ translate('2FA Authentication') }}
        </a>
        @if (@$settings->kyc->status)
            <a href="{{ route('workspace.settings.kyc') }}"
                class="settings-link {{ request()->routeIs('workspace.settings.kyc') ? 'active' : '' }}">
                <i class="fa-solid fa-user-check me-1"></i>
                {{ translate('KYC Verification') }}
            </a>
        @endif
    </div>
</div>
