@extends('admin.layouts.crud.table', ['rows' => $faqs, 'createRoute' => route('admin.faqs.create')])

@section('table')
    @foreach ($faqs as $faq)
        @php $faqDeleted = method_exists($faq, 'trashed') && $faq->trashed(); @endphp
        <tr class="data-rows {{ $faqDeleted ? 'deleted-table-row' : '' }}" data-faq-id="{{ $faq->id }}">
            @if (!$faqDeleted)<td class="dt-checkboxes-cell"><input type="checkbox" value="{{ $faq->id }}" class="dt-checkboxes form-check-input"></td>@else<td></td>@endif
            <td>{{ Str::limit($faq->question, 50) }}</td>
            <td>{{ Str::limit($faq->answer, 80) }}</td>
            <td>@if(!$faqDeleted)<div class="form-check form-switch mb-0 d-flex justify-content-center"><input class="form-check-input switch-active" type="checkbox" role="switch" data-id="{{ $faq->id }}" data-route="{{ route('admin.faqs.switchIsActive', ['id' => $faq->id]) }}" {{ $faq->is_active ? 'checked' : '' }}></div>@else<span class="text-muted">—</span>@endif</td>
            <td>
                <div class="d-flex align-items-center gap-2 flex-nowrap faqs-row-actions">
                    <a href="{{ route('admin.faqs.show', ['faq' => $faq]) }}"
                       class="custom-icon faqs-action-btn faqs-action-view"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="@lang('admin/main.show')"
                       aria-label="@lang('admin/main.show')">
                        <i class="ti ti-eye" aria-hidden="true"></i>
                    </a>

                    @if (!$faqDeleted)
                        <a href="{{ route('admin.faqs.edit', ['faq' => $faq]) }}"
                           class="custom-icon faqs-action-btn faqs-action-edit"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.edit')"
                           aria-label="@lang('admin/main.edit')">
                            <i class="ti ti-pencil" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($faqDeleted)
                        <a href="javascript:void(0);" data-id="{{ $faq->id }}"
                           data-route="{{ route('admin.faqs.restore', ['id' => $faq->id]) }}"
                           class="custom-icon faqs-action-btn faqs-action-restore restore-row"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.restore')"
                           aria-label="@lang('admin/main.restore')">
                            <i class="ti ti-arrow-back-up" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-id="{{ $faq->id }}"
                           data-route="{{ route('admin.faqs.destroy', ['faq' => $faq]) }}"
                           class="custom-icon faqs-action-btn faqs-action-delete delete-record"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="@lang('admin/main.delete')"
                           aria-label="@lang('admin/main.delete')">
                            <i class="ti ti-trash" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
@endsection