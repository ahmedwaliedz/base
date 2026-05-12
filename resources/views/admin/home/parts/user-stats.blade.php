{{-- User stats --}}
<div class="dash-section-label">{{ __('admin/main.home_section_users') }}</div>
<div class="row g-3 mb-4">

    {{-- Total Users --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--users h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-users"></i></div>
                @php $chNew = $stats['change_new_users']; @endphp
                <span class="dsc__change {{ $chNew['up'] ? 'dsc__change--up' : 'dsc__change--down' }}">
                    <i class="ti {{ $chNew['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                    {{ $chNew['value'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_users') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_users'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_all_time') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_users') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Users --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--users-active h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-user-check"></i></div>
                <span class="dsc__change dsc__change--neutral">
                    {{ $stats['ratio_users'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_active_users') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['active_users'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_of_total') }} {{ $stats['total_users'] }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_users') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_users'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- New This Month --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--users-new h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-user-plus"></i></div>
                <span class="dsc__change {{ $chNew['up'] ? 'dsc__change--up' : 'dsc__change--down' }}">
                    <i class="ti {{ $chNew['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                    {{ $chNew['value'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_new_this_month') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['new_this_month'] }}">0</div>
                <div class="dsc__sub">{{ now()->translatedFormat('F Y') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_users') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_new_users'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Blocked Users — high values are BAD: ↑ red, ↓ green --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--users-blocked h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-user-x"></i></div>
                @php $chBlk = $stats['change_blocked']; @endphp
                {{-- For "blocked": up = bad (red), down = good (green) --}}
                <span class="dsc__change {{ $chBlk['up'] ? 'dsc__change--down' : 'dsc__change--up' }}">
                    <i class="ti {{ $chBlk['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                    {{ $stats['ratio_blocked'] }}%
                </span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_blocked_users') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['blocked_users'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_restricted') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_users') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_blocked'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Users This Year --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--users-year h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-calendar-stats"></i></div>
                <span class="dsc__change dsc__change--neutral">{{ now()->format('Y') }}</span>
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_users_this_year') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['users_this_year'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_this_year') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.users.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_users') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="{{ $stats['ratio_year_users'] }}"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Admins --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="dsc dsc--admins h-100">
            <div class="dsc__top">
                <div class="dsc__icon" aria-hidden="true"><i class="ti ti-shield-check"></i></div>
                @php $chAd = $stats['change_admins']; @endphp
                @if($chAd['value'] > 0)
                    <span class="dsc__change {{ $chAd['up'] ? 'dsc__change--up' : 'dsc__change--down' }}">
                        <i class="ti {{ $chAd['up'] ? 'ti-trending-up' : 'ti-trending-down' }}" aria-hidden="true"></i>
                        {{ $chAd['value'] }}%
                    </span>
                @endif
            </div>
            <div class="dsc__body">
                <div class="dsc__label">{{ __('admin/main.home_stat_total_admins') }}</div>
                <div class="dsc__value" data-counter="{{ $stats['total_admins'] }}">0</div>
                <div class="dsc__sub">{{ __('admin/main.home_stat_admins_sub') }}</div>
            </div>
            <div class="dsc__foot">
                <a href="{{ route('admin.admins.index') }}" class="dsc__link"
                   aria-label="{{ __('admin/main.home_card_view_admins') }}">
                    {{ __('admin/main.home_card_view') }} <span class="dsc__arrow">{{ $arrow }}</span>
                </a>
                <div class="dsc__bar-wrap" aria-hidden="true">
                    <div class="dsc__bar-fill" data-width="100"></div>
                </div>
            </div>
        </div>
    </div>

</div>
