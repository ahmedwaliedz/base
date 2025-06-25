<div class="card-datatable table-responsive">
    <table class="datatables-products table">
        <thead class="border-top">
        <tr>
            @if($hasCheckbox)
                <th class="dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></th>
            @endif

            @foreach($headers as $header)
                <th>{{ $header }}</th>
            @endforeach

            @if($hasActions)
                <th><i class="ti ti-folder-cog"></i></th>
            @endif
        </tr>
        </thead>
        <tbody>
            {{$tableBody}}
        </tbody>
    </table>

    @if($rows->count() == 0)
        <div class="text-center py-5 mt-5">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <i class="ti ti-file-off text-secondary mb-2" style="font-size: 3rem;"></i>
                <h5 class="mb-1">{{__('admin/main.no_data_found')}}</h5>
                <p class="text-muted">{{__('admin/main.no_data_description')}}</p>
            </div>
        </div>
    @endif

    {{$rows->links('admin.layouts.pagination')}}
</div>
