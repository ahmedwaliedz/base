@yield('table')

@if($rows->count() > 0)
    <tr class="data-rows">
        <td colspan="100%">
            {{ $rows->links('admin.layouts.pagination') }}
        </td>
    </tr>
@endif

@if($rows->count() == 0)
    <tr class="data-rows">
        <td colspan="100%">
            <div class="crud-empty-state">
                <span class="crud-empty-state__icon" aria-hidden="true">
                    <i class="ti ti-file-off"></i>
                </span>
                <h5 class="crud-empty-state__title">{{ __('admin/main.no_data_found') }}</h5>
                <p class="crud-empty-state__desc">{{ __('admin/main.no_data_description') }}</p>

                @isset($createRoute)
                    <a href="{{ $createRoute }}" class="btn btn-primary crud-empty-state__cta">
                        <i class="ti ti-plus me-1" aria-hidden="true"></i>
                        {{ __('admin/main.create') }}
                    </a>
                @endisset
            </div>
        </td>
    </tr>
@endif
