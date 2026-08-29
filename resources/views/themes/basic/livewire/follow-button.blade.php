@if (authUser())
    @if ($user->id != authUser()->id)
        <button wire:click="followAction"
            class="btn {{ authUser()->isFollowingUser($user->id) ? 'btn-custom' : 'btn-outline-custom' }} {{ $iconButton ? 'btn-padding' : '' }}">
            @if (authUser()->isFollowingUser($user->id))
                <i class="fa-solid fa-user-check"></i>
                @if (!$iconButton)
                    <span class="ms-1">{{ translate('Following') }}</span>
                @endif
            @else
                <i class="fa-solid fa-user-plus"></i>
                @if (!$iconButton)
                    <span class="ms-1">{{ translate('Follow') }}</span>
                @endif
            @endif
        </button>
    @else
        <button class="btn btn-outline-custom {{ $iconButton ? 'btn-padding' : '' }} disabled">
            <i class="fa-solid fa-user-plus"></i>
            @if (!$iconButton)
                <span class="ms-1">{{ translate('Follow') }}</span>
            @endif
        </button>
    @endif
@else
    <a href="{{ route('login') }}" class="btn btn-outline-custom {{ $iconButton ? 'btn-padding' : '' }}">
        <i class="fa-solid fa-user-plus"></i>
        @if (!$iconButton)
            <span class="ms-1">{{ translate('Follow') }}</span>
        @endif
    </a>
@endif
