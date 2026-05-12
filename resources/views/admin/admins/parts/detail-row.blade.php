{{-- @param string $icon Tabler icon suffix e.g. ti-user --}}
<div class="col-md-6">
    <div class="admin-detail-row">
        <span class="admin-detail-row__icon"><i class="ti {{ $icon }}" aria-hidden="true"></i></span>
        <div class="min-w-0">
            <div class="admin-detail-row__label">{{ $label }}</div>
            <div class="admin-detail-row__value">{{ $value ?? '—' }}</div>
        </div>
    </div>
</div>
