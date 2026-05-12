<div class="user-timeline">
    <div class="user-timeline__item user-timeline__item--{{ $user->created_at ? 'done' : 'pending' }}">
        <span class="user-timeline__dot"><i class="ti ti-user-plus"></i></span>
        <div>
            <div class="user-timeline__title">{{ __('admin/main.account_created') }}</div>
            <div class="user-timeline__time">{{ $user->created_at?->format('Y-m-d H:i') }} · {{ $user->created_at?->diffForHumans() }}</div>
        </div>
    </div>

    <div class="user-timeline__item user-timeline__item--{{ $user->email_verified_at ? 'done' : 'pending' }}">
        <span class="user-timeline__dot"><i class="ti ti-mail-check"></i></span>
        <div>
            <div class="user-timeline__title">{{ __('admin/main.email_verified') }}</div>
            <div class="user-timeline__time">
                {{ $user->email_verified_at?->format('Y-m-d H:i') ?? __('admin/main.not_verified_yet') }}
            </div>
        </div>
    </div>

    <div class="user-timeline__item user-timeline__item--{{ $user->phone_verified_at ? 'done' : 'pending' }}">
        <span class="user-timeline__dot"><i class="ti ti-phone-check"></i></span>
        <div>
            <div class="user-timeline__title">{{ __('admin/main.phone_verified') }}</div>
            <div class="user-timeline__time">
                {{ $user->phone_verified_at?->format('Y-m-d H:i') ?? __('admin/main.not_verified_yet') }}
            </div>
        </div>
    </div>

    @if ($user->last_activation_requested_at)
        <div class="user-timeline__item user-timeline__item--info">
            <span class="user-timeline__dot"><i class="ti ti-send"></i></span>
            <div>
                <div class="user-timeline__title">{{ __('admin/main.last_otp_request') }}</div>
                <div class="user-timeline__time">{{ $user->last_activation_requested_at->diffForHumans() }}</div>
            </div>
        </div>
    @endif
</div>

@if ($recentOtps->isNotEmpty())
    <h6 class="user-profile__subhead mt-4">{{ __('admin/main.recent_otps') }}</h6>
    <ul class="user-otps">
        @foreach ($recentOtps as $otp)
            <li class="user-otps__row">
                <span class="user-otps__code">****</span>
                <span class="user-otps__type">{{ $otp->type?->value ?? '—' }}</span>
                <span class="user-otps__time">{{ $otp->created_at?->diffForHumans() }}</span>
            </li>
        @endforeach
    </ul>
@endif
