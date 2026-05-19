@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('style/admin/css/app-notifications.css') }}">
@endpush

@section('content')
    <section class="app-notifications-page">
        <div class="app-notifications-hero">
            <span class="app-notifications-hero__eyebrow">
                <i class="ti ti-bell"></i>
                {{ __('admin/main.app_notifications') }}
            </span>

            <h1 class="app-notifications-hero__title">{{ __('admin/main.app_notifications') }}</h1>
            <p class="app-notifications-hero__sub">
                {{ __('admin/main.app_notifications_subtitle') }}
            </p>

            <div class="app-notifications-hero__actions">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-label-secondary">
                        <i class="ti ti-arrow-left me-1"></i>{{ __('admin/main.back') }}
                    </a>
                </div>
                <button type="button" class="btn btn-primary" id="markAllAsReadBtn" @disabled(($summary['unread'] ?? 0) === 0)>
                    <i class="ti ti-checks me-1"></i>{{ __('admin/main.mark_all_as_read') }}
                </button>
            </div>
        </div>

        <div class="app-notifications-stats" id="notificationStats">
            <div class="app-notification-stat">
                <span class="app-notification-stat__icon"><i class="ti ti-bell"></i></span>
                <div>
                    <div class="app-notification-stat__label">{{ __('admin/main.total') }}</div>
                    <div class="app-notification-stat__value" data-summary="total">{{ $summary['total'] ?? 0 }}</div>
                </div>
            </div>
            <div class="app-notification-stat">
                <span class="app-notification-stat__icon"><i class="ti ti-bell-ringing"></i></span>
                <div>
                    <div class="app-notification-stat__label">{{ __('admin/main.unread_notifications') }}</div>
                    <div class="app-notification-stat__value" data-summary="unread">{{ $summary['unread'] ?? 0 }}</div>
                </div>
            </div>
            <div class="app-notification-stat">
                <span class="app-notification-stat__icon"><i class="ti ti-bell-check"></i></span>
                <div>
                    <div class="app-notification-stat__label">{{ __('admin/main.read_notifications') }}</div>
                    <div class="app-notification-stat__value" data-summary="read">{{ $summary['read'] ?? 0 }}</div>
                </div>
            </div>
            <div class="app-notification-stat">
                <span class="app-notification-stat__icon"><i class="ti ti-clock"></i></span>
                <div>
                    <div class="app-notification-stat__label">{{ __('admin/main.latest_notification_time') }}</div>
                    <div class="app-notification-stat__value" id="latestNotificationTime">
                        {{ $notifications->first()['created_human'] ?? __('admin/main.no_notifications_yet') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="app-notifications-card">
            <div class="app-notifications-card__head">
                <div>
                    <h2 class="app-notifications-card__title">{{ __('admin/main.latest_notifications') }}</h2>
                    <div class="app-notifications-card__count">
                        <span id="notificationTotalCount">{{ $notifications->total() }}</span> {{ __('admin/main.notifications') }}
                    </div>
                </div>
            </div>

            <div class="app-notifications-list" id="notificationsList">
                @forelse ($notifications as $notification)
                    <div class="app-notification-item {{ $notification['is_read'] ? 'is-read' : 'is-unread' }}" data-notification-id="{{ $notification['id'] }}">
                        <span class="app-notification-item__icon app-notification-item__icon--{{ $notification['tone'] }}">
                            <i class="{{ $notification['icon'] }}" aria-hidden="true"></i>
                        </span>

                        <div class="app-notification-item__body">
                            <div class="app-notification-item__top">
                                <div>
                                    <h3 class="app-notification-item__title">{{ $notification['title'] }}</h3>
                                    <div class="app-notification-item__meta">
                                        <span class="badge bg-label-primary">{{ $notification['type_label'] }}</span>
                                        @if ($notification['is_read'])
                                            <span class="badge bg-label-success">{{ __('admin/main.read_notifications') }}</span>
                                        @else
                                            <span class="badge bg-label-warning notification-status" data-status="unread">{{ __('admin/main.unread_notifications') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="app-notification-item__time">
                                    <div>{{ $notification['created_human'] }}</div>
                                    <div>{{ $notification['created_date'] }}</div>
                                </div>
                            </div>

                            <p class="app-notification-item__message">{{ $notification['message'] }}</p>
                        </div>

                        <div class="app-notification-item__actions">
                            @if (! $notification['is_read'])
                                <button type="button" class="btn btn-label-primary btn-sm mark-as-read-btn" data-notification-id="{{ $notification['id'] }}">
                                    <i class="ti ti-check me-1"></i>{{ __('admin/main.mark_as_read') }}
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="app-notifications-empty" id="emptyState">
                        <i class="ti ti-bell-off"></i>
                        <h3 class="h5 mb-1">{{ __('admin/main.no_notifications_yet') }}</h3>
                        <p class="mb-0">{{ __('admin/main.no_data_description') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="d-flex justify-content-end">
            {{ $notifications->links() }}
        </div>
    </section>
@endsection

@php
    $labels = [
        'readNotifications' => __('admin/main.read_notifications'),
        'unreadNotifications' => __('admin/main.unread_notifications'),
        'markAsRead' => __('admin/main.mark_as_read'),
        'markAllAsRead' => __('admin/main.mark_all_as_read'),
        'somethingWentWrong' => __('admin/main.something_went_wrong'),
        'updatedSuccessfully' => __('admin/main.updated_successfully'),
    ];
    $routes = [
        'markAsRead' => route('admin.app-notifications.markAsRead', ['notification' => ':notificationId']),
        'markAllAsRead' => route('admin.app-notifications.markAllAsRead'),
    ];
@endphp
@push('js')
    <script src="{{ asset('style/admin/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('style/admin/js/extended-ui-sweetalert2.js') }}"></script>
    <script>
        const labels = @json($labels);
        const routes = @json($routes);
        const csrfToken = @json(csrf_token());

        function showNotification(type, message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: type,
                    position: 'top-start',
                    text: message,
                    showConfirmButton: false,
                    timer: type === 'error' ? 3000 : 2000
                });
            }
        }

        function updateNavbarNotifications(latestNotifications, summary) {
            if (!latestNotifications || !summary) return;

            const unread = summary.unread;
            const $dropdownToggle = $('.dropdown-notifications.dropdown a.nav-link');
            const $badge = $dropdownToggle.find('.badge-notifications');
            const $headerCount = $('.dropdown-notifications__header-count-number');
            const $headerLabel = $('.dropdown-notifications__header-count-label');
            const $dropdownBody = $('.dropdown-notifications__body');

            if (unread > 0) {
                if ($badge.length) {
                    $badge.text(unread > 99 ? '99+' : unread);
                } else {
                    $dropdownToggle.append('<span class="badge-notifications">' + (unread > 99 ? '99+' : unread) + '</span>');
                }
                $headerCount.text(unread);
                $headerLabel.text(labels.unreadNotifications);
            } else {
                $badge.remove();
                $headerCount.text('0');
                $headerLabel.text(labels.readNotifications);
            }

            if (latestNotifications.length > 0) {
                $dropdownBody.empty();
                latestNotifications.forEach(function(n) {
                    const $item = $('<a>', {
                        class: 'dropdown-notifications__item dropdown-notifications__item--' + n.tone + ' ' + (n.is_read ? 'is-read' : 'is-unread'),
                        href: '{{ route("admin.app-notifications.index") }}'
                    });

                    $item.append($('<span>', { class: 'dropdown-notifications__state', 'aria-hidden': 'true' }));

                    const $icon = $('<span>', { class: 'dropdown-notifications__icon dropdown-notifications__icon--' + n.tone });
                    $icon.append($('<i>', { class: n.icon, 'aria-hidden': 'true' }));
                    $item.append($icon);

                    const $content = $('<span>', { class: 'dropdown-notifications__content' });
                    const $topline = $('<span>', { class: 'dropdown-notifications__topline' });
                    $topline.append($('<span>', { class: 'dropdown-notifications__item-title' }).text(n.title));
                    $topline.append($('<span>', { class: 'dropdown-notifications__time' }).text(n.created_human));
                    $content.append($topline);
                    $content.append($('<span>', { class: 'dropdown-notifications__message' }).text(n.message));
                    $item.append($content);

                    $item.append($('<span>', { class: 'dropdown-notifications__read-tag' }).text(n.is_read ? labels.readNotifications : labels.unreadNotifications));

                    $dropdownBody.append($item);
                });
            }
        }

        $(document).ready(function() {
            const markAllAsReadBtn = $('#markAllAsReadBtn');

            markAllAsReadBtn.on('click', function() {
                const btn = $(this);
                const originalHtml = btn.html();

                $.ajax({
                    url: routes.markAllAsRead,
                    method: 'POST',
                    data: {
                        _token: csrfToken
                    },
                    beforeSend: function() {
                        btn.html('<i class="ti ti-loader spin"></i>').prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showNotification('success', response.message || labels.updatedSuccessfully);

                            if (response.data && response.data.summary) {
                                updateSummary(response.data.summary);
                            }

                            if (response.data && response.data.latestNotifications) {
                                updateNavbarNotifications(response.data.latestNotifications, response.data.summary);
                            }

                            $('.notification-status')
                                .removeClass('bg-label-warning')
                                .addClass('bg-label-success')
                                .text(labels.readNotifications);

                            $('.mark-as-read-btn').remove();
                            $('.app-notification-item').removeClass('is-unread').addClass('is-read');

                            if (response.data && response.data.summary && response.data.summary.unread === 0) {
                                markAllAsReadBtn.html(originalHtml).prop('disabled', true);
                            } else {
                                markAllAsReadBtn.html(originalHtml).prop('disabled', false);
                            }
                        } else {
                            markAllAsReadBtn.html(originalHtml).prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        handleError(xhr);
                        markAllAsReadBtn.html(originalHtml).prop('disabled', false);
                    }
                });
            });

            $(document).on('click', '.mark-as-read-btn', function() {
                const btn = $(this);
                const notificationId = btn.data('notification-id');
                const notificationItem = $(`.app-notification-item[data-notification-id="${notificationId}"]`);

                const url = routes.markAsRead.replace(':notificationId', notificationId);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: csrfToken
                    },
                    beforeSend: function() {
                        btn.prop('disabled', true).html('<i class="ti ti-loader spin"></i>');
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showNotification('success', response.message || labels.updatedSuccessfully);

                            if (response.data && response.data.summary) {
                                updateSummary(response.data.summary);
                            }

                            if (response.data && response.data.latestNotifications) {
                                updateNavbarNotifications(response.data.latestNotifications, response.data.summary);
                            }

                            const statusBadge = notificationItem.find('.notification-status');
                            if (statusBadge.length) {
                                statusBadge
                                    .removeClass('bg-label-warning')
                                    .addClass('bg-label-success')
                                    .text(labels.readNotifications);
                            }

                            notificationItem.removeClass('is-unread').addClass('is-read');
                            btn.remove();

                            if (response.data && response.data.summary && response.data.summary.unread === 0) {
                                markAllAsReadBtn.prop('disabled', true);
                            }
                        }
                    },
                    error: function(xhr) {
                        handleError(xhr);
                        btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i>' + labels.markAsRead);
                    }
                });
            });

            function updateSummary(summary) {
                if (summary.total !== undefined) {
                    $('[data-summary="total"]').text(summary.total);
                }
                if (summary.unread !== undefined) {
                    $('[data-summary="unread"]').text(summary.unread);
                }
                if (summary.read !== undefined) {
                    $('[data-summary="read"]').text(summary.read);
                }
            }

            function handleError(xhr) {
                let message = labels.somethingWentWrong;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showNotification('error', message);
            }
        });
    </script>
@endpush
