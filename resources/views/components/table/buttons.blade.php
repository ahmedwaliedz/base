<div class="crud-toolbar d-flex justify-content-between flex-wrap align-items-center gap-3">
    <div class="d-flex align-items-center flex-wrap gap-2">
        @if($createRoute)
            <a href="{{ $createRoute }}" type="button" class="btn btn-primary waves-effect">
                <span class="ti-xs ti ti-table-plus me-1"></span>{{ __('admin/main.create') }}
            </a>
        @endif

        @if($hasNotification)
            <button type="button" data-bs-toggle="modal" data-bs-target="#notificationModal" data-id="group"
                    class="send-notification btn btn-label-warning waves-effect">
                <span class="ti-xs ti ti-bell-plus me-1"></span>{{ __('admin/main.send_notification') }}
            </button>
        @endif

        @if($hasEmail)
            <button type="button" data-bs-toggle="modal" data-bs-target="#emailModal"
                    class="btn btn-label-info waves-effect">
                <span class="ti-xs ti ti-mail-plus me-1"></span>{{ __('admin/main.send_email') }}
            </button>
        @endif

        @if($hasReload)
            <button type="button" class="btn btn-label-secondary waves-effect reload"
                    aria-label="{{ __('admin/main.reload') }}">
                <span class="ti-xs ti ti-reload me-1"></span>{{ __('admin/main.reload') }}
            </button>
        @endif

        @if($hasDeleteAll)
            <button data-route="{{$deleteAllRoute}}" type="button"
                    class="btn btn-label-danger waves-effect delete-all-button">
                <span class="ti-xs ti ti-trash-off me-1"></span>{{ __('admin/main.delete_selected') }}
            </button>
        @endif

        @if($hasExport && ($exportPrint || $exportPdf || $exportExcel || $exportWord || $exportJson || $exportCopy))
            <div class="btn-group">
                <button type="button" class="btn btn-label-success dropdown-toggle waves-effect"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="ti-xs ti ti-file-export me-1"></span>{{ __('admin/main.export') }}
                </button>
                <ul class="dropdown-menu">
                    @if($exportPrint)
                        <li><a class="dropdown-item export-action" data-format="print" href="javascript:void(0);"><i class="ti ti-printer me-2"></i>{{ __('admin/main.print') }}</a></li>
                    @endif
                    @if($exportPdf)
                        <li><a class="dropdown-item export-action" data-format="pdf" href="javascript:void(0);"><i class="ti ti-pdf me-2"></i>{{ __('admin/main.export_pdf') }}</a></li>
                    @endif
                    @if($exportExcel)
                        <li><a class="dropdown-item export-action" data-format="xlsx" href="javascript:void(0);"><i class="ti ti-file-spreadsheet me-2"></i>{{ __('admin/main.export_excel') }}</a></li>
                        <li><a class="dropdown-item export-action" data-format="csv" href="javascript:void(0);"><i class="ti ti-file me-2"></i>{{ __('admin/main.export_csv') }}</a></li>
                    @endif
                    @if($exportWord)
                        <li><a class="dropdown-item export-action" data-format="docx" href="javascript:void(0);"><i class="ti ti-file-text me-2"></i>{{ __('admin/main.export_word') }}</a></li>
                    @endif
                    @if($exportJson)
                        <li><a class="dropdown-item export-action" data-format="json" href="javascript:void(0);"><i class="ti ti-json me-2"></i>{{ __('admin/main.export_json') }}</a></li>
                    @endif
                    @if($exportCopy)
                        <li><a class="dropdown-item export-action" data-format="copy" href="javascript:void(0);"><i class="ti ti-copy me-2"></i>{{ __('admin/main.copy') }}</a></li>
                    @endif
                </ul>
            </div>
        @endif

        @if($hasExtraButtons)
            {{ $extraButtons ?? '' }}
        @endif
    </div>

    <div class="d-flex align-items-center gap-2">
        @if($hasFilter)
            <button type="button" class="btn btn-label-primary waves-effect show_filter"
                    aria-label="{{ __('admin/main.search') }}">
                <span class="ti-xs ti ti-filter-plus me-1"></span>
                <span class="d-none d-md-inline">{{ __('admin/main.search') }}</span>
            </button>
        @endif

        @if($hasPagination)
            <div class="btn-group">
                <button type="button" class="btn btn-label-secondary dropdown-toggle waves-effect"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="ti-xs ti ti-list me-1"></span>{{ __('admin/main.per_page') }}: {{ $perPage }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item per-page-item" data-value="30" href="javascript:void(0);">30</a></li>
                    <li><a class="dropdown-item per-page-item" data-value="50" href="javascript:void(0);">50</a></li>
                    <li><a class="dropdown-item per-page-item" data-value="100" href="javascript:void(0);">100</a></li>
                    <li><a class="dropdown-item per-page-item" data-value="500" href="javascript:void(0);">500</a></li>
                    <li><a class="dropdown-item per-page-item" data-value="1000" href="javascript:void(0);">1000</a></li>
                    <li><a class="dropdown-item per-page-item" data-value="5000" href="javascript:void(0);">5000</a></li>
                    <li><a class="dropdown-item per-page-item" data-value="10000" href="javascript:void(0);">10000</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <div class="px-3 py-2">
                            <div class="d-flex flex-column justify-content-between">
                                <input type="number" id="custom-per-page" class="form-control mb-1" placeholder="{{ __('admin/main.custom_value') }}">
                                <button class="btn btn-outline-primary apply-custom-per-page" type="button">{{ __('admin/main.apply') }}</button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <input type="hidden" id="per-page-select" value="{{ $perPage }}">
        @endif
    </div>
</div>
