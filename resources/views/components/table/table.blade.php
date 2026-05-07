<div class="card mt-4">
    <div class="card-datatable table-responsive table-content">
        <table class="datatables-products table">
            <thead class="border-top">
                <tr>
                    @if($hasCheckbox)
                        <th class="dt-checkboxes-cell">
                            <input type="checkbox" class="dt-checkboxes form-check-input" aria-label="{{ __('admin/main.select_all') }}">
                        </th>
                    @endif

                    @foreach($headers as $header)
                        <th class="align-center">{{ $header }}</th>
                    @endforeach

                    @if($hasActions)
                        <th><i class="ti ti-folder-cog" aria-hidden="true"></i></th>
                    @endif
                </tr>
            </thead>
            <tbody class="append-page-content">
                <tr class="table-loader">
                    <td colspan="{{ count($headers) + ($hasCheckbox ? 1 : 0) + ($hasActions ? 1 : 0) }}">
                        @php $rowsCount = 9; @endphp
                        <table class="table table-skeleton" aria-hidden="true">
                            <tbody>
                                @for ($r = 0; $r < $rowsCount; $r++)
                                    <tr>
                                        @if($hasCheckbox)
                                            <td class="skel-cell skel-checkbox"><span></span></td>
                                        @endif

                                        @foreach($headers as $i => $header)
                                            @php $w = [60, 80, 45, 70, 50][($i + $r) % 5]; @endphp
                                            <td class="skel-cell"><span style="width: {{ $w }}%"></span></td>
                                        @endforeach

                                        @if($hasActions)
                                            <td class="skel-cell skel-actions">
                                                <span class="skel-dot" aria-hidden="true"></span>
                                                <span class="skel-dot" aria-hidden="true"></span>
                                                <span class="skel-dot" aria-hidden="true"></span>
                                                <span class="skel-dot" aria-hidden="true"></span>
                                                <span class="skel-dot" aria-hidden="true"></span>
                                            </td>
                                        @endif
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
