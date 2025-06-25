<div class="mb-3 form-group {{ $class }}">
    <label class="form-label" for="{{ $name }}">
        {{ $label }}
        @if($isRequired)
            <span class="text-danger">*</span>
        @else
            <small class="text-muted">({{ __('admin/inputs.optional') }})</small>
        @endif
    </label>

    <input
        type="datetime-local"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        class="form-control"
        {{ $isRequired ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        @if($isRequired && $requiredMessage) data-validation-required-message="{{  $requiredMessage }}" @endif
        @if($min) min="{{ $min }}" @endif
        @if($max) max="{{ $max }}" @endif
    />
</div>
