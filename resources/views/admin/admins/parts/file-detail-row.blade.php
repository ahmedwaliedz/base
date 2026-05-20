{{-- @param string $icon Tabler icon suffix e.g. ti-image --}}
{{-- @param string $label Display label --}}
{{-- @param string|null $value Image URL --}}
{{-- @param string|null $alt Alt text for image (defaults to label) --}}
{{-- @param string $height CSS height for the image (default: 2rem) --}}
<div class="col-md-6">
    <div class="admin-detail-row">
        <span class="admin-detail-row__icon"><i class="ti {{ $icon }}" aria-hidden="true"></i></span>
        <div class="min-w-0">
            <div class="admin-detail-row__label">{{ $label }}</div>
            <div class="admin-detail-row__value">
                @if (!empty($value))
                    <img src="{{ $value }}"
                         alt="{{ $alt ?? $label }}"
                         style="height: {{ $height ?? '2rem' }}; width: auto; object-fit: contain;">
                @else
                    —
                @endif
            </div>
        </div>
    </div>
</div>