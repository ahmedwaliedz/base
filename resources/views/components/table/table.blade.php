<style>
/* Scoped table skeleton styles matching the table structure */
.table-skeleton{width:100%}
.table-skeleton tbody tr>td{padding:16px 14px}
.table-skeleton .skel-cell{vertical-align:middle}
.table-skeleton .skel-cell>span{display:block;height:14px;border-radius:8px;background:linear-gradient(110deg,rgba(255,255,255,.06) 0%,rgba(255,255,255,.28) 40%,rgba(255,255,255,.06) 80%),rgba(115,103,240,.08);background-size:200% 100%;animation:tl-sheen 1.1s ease-in-out infinite;backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);box-shadow:inset 0 1px 2px rgba(0,0,0,.05),0 0 0 1px rgba(255,255,255,.05)}
.table-skeleton .skel-checkbox{width:48px;text-align:center}
.table-skeleton .skel-checkbox>span{width:18px;height:18px;border-radius:6px;margin:0 auto}
.table-skeleton .skel-actions{white-space:nowrap;width:140px}
.table-skeleton .skel-actions .skel-dot{display:inline-block;width:28px;height:28px;border-radius:8px;background:linear-gradient(110deg,rgba(255,255,255,.06) 0%,rgba(255,255,255,.28) 40%,rgba(255,255,255,.06) 80%),rgba(115,103,240,.08);background-size:200% 100%;animation:tl-sheen 1.1s ease-in-out infinite;backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);box-shadow:inset 0 1px 2px rgba(0,0,0,.05),0 0 0 1px rgba(255,255,255,.05);margin-right:6px}
.table-skeleton .skel-actions .skel-dot:last-child{margin-right:0}
@keyframes tl-sheen{0%{background-position:-40% 0}100%{background-position:140% 0}}
@media (max-width:576px){.table-skeleton tbody tr>td{padding:12px 10px}.table-skeleton .skel-actions{width:110px}.table-skeleton .skel-actions .skel-dot{width:24px;height:24px}}
</style>
<div class="card mt-4">
    <div class="card-datatable table-responsive table-content">
        <table class="datatables-products table">
            <thead class= "border-top">
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
            <tbody class="append-page-content">
                <tr class="table-loader">
                    <td colspan="{{ count($headers) + ($hasCheckbox ? 1 : 0) + ($hasActions ? 1 : 0) }}">
                        @php
                            $rowsCount = 9;
                        @endphp
                        <table class="table table-skeleton" aria-hidden="true">
                            <tbody>
                                @for ($r = 0; $r < $rowsCount; $r++)
                                    <tr>
                                        @if($hasCheckbox)
                                            <td class="skel-cell skel-checkbox"><span></span></td>
                                        @endif

                                        @foreach($headers as $i => $header)
                                            @php $w = [60,80,45,70,50][($i + $r) % 5]; @endphp
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
