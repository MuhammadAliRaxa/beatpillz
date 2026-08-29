@php
    $userBadges = $user->badges;
@endphp
@if ($userBadges->count() > 0)
    <div class="card-v card-bg border p-4 mb-3">
        <h5 class="mb-3">{{ translate('Badges') }}</h5>
        <div class="row row-cols-auto g-2">
            @foreach ($userBadges as $userBadge)
                <div class="col">
                    <div class="author-badge">
                        <img src="{{ $userBadge->badge->getImageLink() }}" alt="{{ $userBadge->badge->name }}"
                            title="{{ $userBadge->badge->getFullTitle() }}">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@if ($user->profile_contact_email)
    <div class="card-v card-bg border p-4 mb-3">
        <h5 class="mb-3">
            {{ translate('Contact :username', ['username' => $user->username]) }}
        </h5>
        @if (authUser())
            <form action="{{ route('profile.sendmail', $user->username) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ translate('From') }}</label>
                    <input class="form-control form-control-md" value="{{ authUser()->email }}" disabled />
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ translate('Message') }}</label>
                    <textarea name="message" class="form-control form-control-md" placeholder="{{ translate('Enter Your Message') }}"
                        rows="6" required>{{ old('message') }}</textarea>
                </div>
                <div class="text-end">
                    <button class="btn btn-primary btn-md">{{ translate('Send') }}</button>
                </div>
            </form>
        @else
            <p>
                {{ translate('Please sign in to contact this :username.', ['username' => $user->username]) }}
            </p>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary">{{ translate('Sign In') }}</a>
        @endif
    </div>
@endif
@if ($user->profile_social_links)
    <div class="card-v card-bg border p-4">
        <h5 class="mb-3">{{ translate('Social links') }}</h5>
        <div class="socials">
            @if ($user->profile_social_links->facebook)
                <a href="https://facebook.com/{{ $user->profile_social_links->facebook }}" target="_blank"
                    class="social-btn social-facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
            @endif
            @if ($user->profile_social_links->x)
                <a href="https://x.com/{{ $user->profile_social_links->x }}" target="_blank"
                    class="social-btn social-x">
                    <i class="fab fa-x-twitter"></i>
                </a>
            @endif
            @if ($user->profile_social_links->linkedin)
                <a href="https://linkedin.com/in/{{ $user->profile_social_links->linkedin }}" target="_blank"
                    class="social-btn social-linkedin">
                    <i class="fab fa-linkedin"></i>
                </a>
            @endif
            @if ($user->profile_social_links->youtube)
                <a href="https://youtube.com/{{ '@' . $user->profile_social_links->youtube }}" target="_blank"
                    class="social-btn social-youtube">
                    <i class="fab fa-youtube"></i>
                </a>
            @endif
            @if ($user->profile_social_links->instagram)
                <a href="https://instagram.com/{{ $user->profile_social_links->instagram }}" target="_blank"
                    class="social-btn social-instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            @endif
            @if ($user->profile_social_links->pinterest)
                <a href="https://pinterest.com/{{ $user->profile_social_links->pinterest }}" target="_blank"
                    class="social-btn social-pinterest">
                    <i class="fab fa-pinterest"></i>
                </a>
            @endif
        </div>
    </div>
@endif
