<div class="card mt-4">
    <div class="card-datatable table-responsive">
        <table class="datatables-products table">
            <thead class="border-top">
            <tr>
                @if($hasCheckbox)
                    <th class="dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input">
                    </th>
                @endif

                @foreach($headers as $header)
                    <th class="align-center">{{ $header }}</th>
                @endforeach

                @if($hasActions)
                    <th><i class="ti ti-folder-cog"></i></th>
                @endif
            </tr>
            </thead>
            <tbody class="append-page-content" >
                <tr class="table-loader">
                    <td colspan="{{ count($headers) + ($hasCheckbox ? 1 : 0) + ($hasActions ? 1 : 0) }}">
                        <div class="text-center p-5" >
                            <lottie-player src="{{ asset('storage/uploads/settings/Load.json') }}" background="transparent" speed="1" style="width: 200px; height: 200px; margin: 0 auto;" loop autoplay></lottie-player>
                        </div>
                    </td>
                <tr>

            </tbody>
        </table>
    </div>
</div>
