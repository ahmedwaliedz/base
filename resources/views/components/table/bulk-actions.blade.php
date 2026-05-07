@props([
    'hasDelete'       => false,
    'deleteRoute'     => null,
    'hasNotification' => false,
    'hasEmail'        => false,
    'hasBlock'        => false,
])

<div class="crud-bulk-bar" id="crudBulkBar" aria-live="polite" aria-hidden="true">
    <div class="crud-bulk-bar__inner">
        <div class="crud-bulk-bar__count">
            <span class="crud-bulk-bar__count-num" id="crudBulkBarCount">0</span>
            <span class="crud-bulk-bar__count-label">{{ __('admin/main.selected_rows') }}</span>
        </div>

        <div class="crud-bulk-bar__actions">
            @if($hasNotification)
                <button type="button"
                        class="btn btn-label-warning crud-bulk-action"
                        data-bulk-action="notify"
                        data-bs-toggle="modal"
                        data-bs-target="#notificationModal">
                    <i class="ti ti-bell-plus me-1" aria-hidden="true"></i>
                    {{ __('admin/main.send_notification') }}
                </button>
            @endif

            @if($hasEmail)
                <button type="button"
                        class="btn btn-label-info crud-bulk-action"
                        data-bulk-action="email"
                        data-bs-toggle="modal"
                        data-bs-target="#emailModal">
                    <i class="ti ti-mail-plus me-1" aria-hidden="true"></i>
                    {{ __('admin/main.send_email') }}
                </button>
            @endif

            @if($hasDelete)
                <button type="button"
                        class="btn btn-label-danger crud-bulk-action delete-all-button"
                        data-route="{{ $deleteRoute }}">
                    <i class="ti ti-trash me-1" aria-hidden="true"></i>
                    {{ __('admin/main.delete_selected') }}
                </button>
            @endif
        </div>

        <button type="button"
                class="crud-bulk-bar__clear"
                id="crudBulkBarClear"
                aria-label="{{ __('admin/main.clear_selection') }}">
            <i class="ti ti-x" aria-hidden="true"></i>
            <span class="d-none d-sm-inline">{{ __('admin/main.clear_selection') }}</span>
        </button>
    </div>
</div>
