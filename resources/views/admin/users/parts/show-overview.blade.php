<div class="user-overview">
    <div class="user-overview__grid">
        {{-- Personal information --}}
        <section class="user-overview__card">
            <header class="user-overview__card-header">
                <span class="user-overview__card-icon"><i class="ti ti-user"></i></span>
                <h6 class="user-overview__card-title">{{ __('admin/main.personal_info') }}</h6>
            </header>
            <dl class="user-info">
                <div class="user-info__row">
                    <dt>{{ __('admin/main.id') }}</dt>
                    <dd>#{{ $user->id }}</dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.name') }}</dt>
                    <dd>{{ $user->name }}</dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.email') }}</dt>
                    <dd>{{ $user->email ?? '—' }}</dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.phone') }}</dt>
                    <dd>{{ $user->phone ?? '—' }}</dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.country_code') }}</dt>
                    <dd>{{ $user->country_code ?? '—' }}</dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.full_phone') }}</dt>
                    <dd>{{ $user->full_phone ?? ('+' . $user->country_code . ' ' . $user->phone) }}</dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.phone_normalized') }}</dt>
                    <dd>{{ $user->phone_normalized ?? '—' }}</dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.registered_at') }}</dt>
                    <dd>
                        {{ $user->created_at?->format('Y-m-d H:i') }}
                        @if ($user->created_at)
                            <small class="text-muted">({{ $user->created_at->diffForHumans() }})</small>
                        @endif
                    </dd>
                </div>
            </dl>
        </section>

        {{-- Account status --}}
        <section class="user-overview__card">
            <header class="user-overview__card-header">
                <span class="user-overview__card-icon"><i class="ti ti-shield-check"></i></span>
                <h6 class="user-overview__card-title">{{ __('admin/main.account_status') }}</h6>
            </header>
            <dl class="user-info">
                <div class="user-info__row">
                    <dt>{{ __('admin/main.status') }}</dt>
                    <dd>
                        <span class="badge {{ $user->statusData()['class'] }}">
                            {{ $user->statusData()['label'] }}
                        </span>
                    </dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.is_active') }}</dt>
                    <dd>
                        <span class="user-info__bool user-info__bool--{{ $user->is_active ? 'yes' : 'no' }}">
                            {{ $user->is_active ? __('admin/main.yes') : __('admin/main.no') }}
                        </span>
                    </dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.is_blocked') }}</dt>
                    <dd>
                        <span class="user-info__bool user-info__bool--{{ $user->is_blocked ? 'no' : 'yes' }}">
                            {{ $user->is_blocked ? __('admin/main.yes') : __('admin/main.no') }}
                        </span>
                    </dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.is_complete_info') }}</dt>
                    <dd>
                        <span class="user-info__bool user-info__bool--{{ $user->is_complete_info ? 'yes' : 'no' }}">
                            {{ $user->is_complete_info ? __('admin/main.yes') : __('admin/main.no') }}
                        </span>
                    </dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.is_notify') }}</dt>
                    <dd>
                        <span class="user-info__bool user-info__bool--{{ $user->is_notify ? 'yes' : 'no' }}">
                            {{ $user->is_notify ? __('admin/main.yes') : __('admin/main.no') }}
                        </span>
                    </dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.email_verified_at') }}</dt>
                    <dd>{{ $user->email_verified_at?->format('Y-m-d H:i') ?? __('admin/main.not_verified') }}</dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.phone_verified_at') }}</dt>
                    <dd>{{ $user->phone_verified_at?->format('Y-m-d H:i') ?? __('admin/main.not_verified') }}</dd>
                </div>
                <div class="user-info__row">
                    <dt>{{ __('admin/main.last_activation_requested_at') }}</dt>
                    <dd>
                        {{ $user->last_activation_requested_at?->format('Y-m-d H:i') ?? '—' }}
                        @if ($user->last_activation_requested_at)
                            <small class="text-muted">({{ $user->last_activation_requested_at->diffForHumans() }})</small>
                        @endif
                    </dd>
                </div>
                @if ($user->deleted_at)
                    <div class="user-info__row user-info__row--danger">
                        <dt>{{ __('admin/main.deleted_at') }}</dt>
                        <dd>{{ $user->deleted_at->format('Y-m-d H:i') }}</dd>
                    </div>
                @endif
            </dl>
        </section>
    </div>
</div>
