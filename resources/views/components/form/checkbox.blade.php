<div class="mb-3 form-group  d-flex align-items-end {{ $class }}">
    <div class="form-check">
        <input
            class="form-check-input"
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $checked ? 'checked' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            @if($isRequired) required @endif
            @if($isRequired && $requiredMessage) data-validation-required-message="{{  $requiredMessage }}" @endif
        >
        <label class="form-check-label" for="{{ $name }}">
            {{ $label }}
            @if(!$isRequired)
                <small class="text-muted">({{ __('admin/inputs.optional') }})</small>
            @endif
        </label>
    </div>
</div>
