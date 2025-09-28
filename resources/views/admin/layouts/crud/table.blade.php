@yield('table')

@if($admins->count() > 0)
    <tr class="data-rows">
        <td colspan="5" >
            {{$admins->links('admin.layouts.pagination')}}
        </td>
    </tr>
@endif

@if($admins->count() == 0)
    <tr class="data-rows">
        <td colspan="5">
            <div class="text-center py-5 mt-5">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <i class="ti ti-file-off text-secondary mb-2" style="font-size: 3rem;"></i>
                    <h5 class="mb-1">{{__('admin/main.no_data_found')}}</h5>
                    <p class="text-muted">{{__('admin/main.no_data_description')}}</p>
                </div>
            </div>
        </td>
    </tr>
@endif

