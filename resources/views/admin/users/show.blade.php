@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/css/user-crud.css') }}">
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endpush

@section('content')
    @php
        $isDeleted = ! is_null($user->deleted_at);
    @endphp

    @if ($isDeleted)
        <div class="user-profile__deleted-banner mb-3">
            <i class="ti ti-trash me-2"></i>
            {{ __('admin/main.deleted_at_label', ['date' => $user->deleted_at->diffForHumans()]) }}
        </div>
    @endif

    <section class="user-profile__hero mb-4">
        <div class="user-profile__hero-orb user-profile__hero-orb--1"></div>
        <div class="user-profile__hero-orb user-profile__hero-orb--2"></div>

        <div class="user-profile__hero-inner">
            <div class="user-profile__avatar-wrap">
                <img src="{{ $user->image }}" alt="{{ $user->name }}" class="user-profile__avatar">
                <span
                    class="user-profile__avatar-status user-profile__avatar-status--{{ $user->is_blocked ? 'blocked' : 'active' }}"></span>
            </div>

            <div class="user-profile__identity">
                <h2 class="user-profile__name">{{ $user->name }}</h2>
                <p class="user-profile__meta">
                    <span><i class="ti ti-mail"></i> {{ $user->email }}</span>
                    <span><i class="ti ti-phone"></i>
                        {{ $user->full_phone ?? '+' . $user->country_code . ' ' . $user->phone }}</span>
                </p>
                <div class="user-profile__pills">
                    <span class="user-profile__pill user-profile__pill--{{ $user->is_blocked ? 'danger' : 'success' }}">
                        <i class="ti ti-{{ $user->is_blocked ? 'lock' : 'circle-check' }}"></i>
                        {{ $user->statusData()['label'] }}
                    </span>
                    @if ($user->email_verified_at)
                        <span class="user-profile__pill user-profile__pill--info"><i class="ti ti-mail-check"></i>
                            {{ __('admin/main.email_verified') }}</span>
                    @endif
                    @if ($user->phone_verified_at)
                        <span class="user-profile__pill user-profile__pill--info"><i class="ti ti-phone-check"></i>
                            {{ __('admin/main.phone_verified') }}</span>
                    @endif
                    <span class="user-profile__pill user-profile__pill--{{ $user->is_notify ? 'brand' : 'muted' }}">
                        <i class="ti ti-{{ $user->is_notify ? 'bell' : 'bell-off' }}"></i>
                        {{ $user->is_notify ? __('admin/main.notify_on') : __('admin/main.notify_off') }}
                    </span>
                </div>
            </div>

            <div class="user-profile__toolbar">
                @if (! $isDeleted)
                    <a href="{{ route('admin.users.edit', $id) }}" class="btn btn-success">
                        <i class="ti ti-edit me-1"></i>{{ __('admin/main.edit') }}
                    </a>
                    <button type="button" class="btn btn-label-info send-notification" data-bs-toggle="modal"
                        data-bs-target="#notificationModal" data-id="{{ $user->id }}">
                        <i class="ti ti-bell-plus me-1"></i>{{ __('admin/main.send_notification') }}
                    </button>
                    <button type="button" class="btn btn-label-warning" data-bs-toggle="modal"
                        data-bs-target="#emailModal" data-id="{{ $user->id }}">
                        <i class="ti ti-mail-plus me-1"></i>{{ __('admin/main.send_email') }}
                    </button>
                @endif
                <a href="{{ route('admin.users.index') }}" class="btn btn-label-secondary">
                    <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
                </a>
            </div>
        </div>
    </section>

    <section class="row g-3 user-profile__stats mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="user-stat user-stat--age">
                <span class="user-stat__icon"><i class="ti ti-calendar-stats"></i></span>
                <div>
                    <div class="user-stat__label">{{ __('admin/main.account_age') }}</div>
                    <div class="user-stat__value">{{ $stats['account_age_days'] }} {{ __('admin/main.days') }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl-3">
            <div class="user-stat user-stat--login">
                <span class="user-stat__icon"><i class="ti ti-login"></i></span>
                <div>
                    <div class="user-stat__label">{{ __('admin/main.last_login') }}</div>
                    <div class="user-stat__value">
                        {{ $stats['last_login_at'] ? $stats['last_login_at']->diffForHumans() : __('admin/main.never') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
            <div class="user-stat user-stat--sessions">
                <span class="user-stat__icon"><i class="ti ti-device-laptop"></i></span>
                <div>
                    <div class="user-stat__label">{{ __('admin/main.sessions') }}</div>
                    <div class="user-stat__value">{{ $stats['sessions_count'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl-3">
            <div class="user-stat user-stat--verify">
                <span class="user-stat__icon"><i class="ti ti-shield-check"></i></span>
                <div>
                    <div class="user-stat__label">{{ __('admin/main.verification') }}</div>
                    <div class="user-stat__value">{{ $stats['verification_score'] }}%</div>
                    <div class="user-stat__bar"><span style="width: {{ $stats['verification_score'] }}%"></span></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
            <div class="user-stat user-stat--notify">
                <span class="user-stat__icon"><i class="ti ti-bell"></i></span>
                <div>
                    <div class="user-stat__label">{{ __('admin/main.notifications') }}</div>
                    <div class="user-stat__value">{{ $user->is_notify ? __('admin/main.on') : __('admin/main.off') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="user-profile__tabs-wrap">
        <ul class="user-profile__tabs nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="user-profile__tab nav-link active" data-bs-toggle="tab" href="#tab-overview" role="tab">
                    <i class="ti ti-id"></i> {{ __('admin/main.overview') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="user-profile__tab nav-link" data-bs-toggle="tab" href="#tab-activity" role="tab">
                    <i class="ti ti-activity"></i> {{ __('admin/main.activity') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="user-profile__tab nav-link" data-bs-toggle="tab" href="#tab-sessions" role="tab">
                    <i class="ti ti-device-desktop"></i> {{ __('admin/main.sessions') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="user-profile__tab nav-link" data-bs-toggle="tab" href="#tab-complaints" role="tab">
                    <i class="ti ti-message-report"></i> {{ __('admin/main.complaints') }}
                    @if ($complaintsCount)
                        <span class="user-profile__tab-badge">{{ $complaintsCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="user-profile__tab nav-link" data-bs-toggle="tab" href="#tab-contacts" role="tab">
                    <i class="ti ti-mail"></i> {{ __('admin/main.contact_messages') }}
                    @if ($contactsCount)
                        <span class="user-profile__tab-badge">{{ $contactsCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="user-profile__tab nav-link" data-bs-toggle="tab" href="#tab-wallet" role="tab">
                    <i class="ti ti-wallet"></i> {{ __('admin/main.wallet') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="user-profile__tab nav-link" data-bs-toggle="tab" href="#tab-security" role="tab">
                    <i class="ti ti-shield"></i> {{ __('admin/main.security') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="user-profile__tab nav-link" data-bs-toggle="tab" href="#tab-danger" role="tab">
                    <i class="ti ti-alert-triangle"></i> {{ __('admin/main.danger_zone') }}
                </a>
            </li>
        </ul>

        <div class="tab-content user-profile__tab-content">
            <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
                @include('admin.users.parts.show-overview', compact('user'))
            </div>
            <div class="tab-pane fade" id="tab-activity" role="tabpanel">
                @include('admin.users.parts.show-activity', compact('user', 'recentOtps'))
            </div>
            <div class="tab-pane fade" id="tab-sessions" role="tabpanel">
                @include('admin.users.parts.show-sessions', compact('sessions'))
            </div>
            <div class="tab-pane fade" id="tab-complaints" role="tabpanel">
                @include('admin.users.parts.tab-complaints', compact('complaints'))
            </div>
            <div class="tab-pane fade" id="tab-contacts" role="tabpanel">
                @include('admin.users.parts.tab-contacts', compact('contacts'))
            </div>
            <div class="tab-pane fade" id="tab-wallet" role="tabpanel">
                @include('admin.users.parts.tab-wallet')
            </div>
            <div class="tab-pane fade" id="tab-security" role="tabpanel">
                @include('admin.users.parts.show-security', compact('user'))
            </div>
            <div class="tab-pane fade" id="tab-danger" role="tabpanel">
                @include('admin.users.parts.show-danger', compact('user', 'isDeleted', 'id'))
            </div>
        </div>
    </section>

    <x-model.notification :route="route('admin.notifications.sendNotifications')" :class="'App\Models\User'" />
    <x-model.email />
@endsection

@push('js')
    <script src="{{ asset('style/admin/custom-js/admin-table.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/delete.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/restore.js') }}"></script>
    <script src="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/js/extended-ui-sweetalert2.js') }}"></script>
@endpush
