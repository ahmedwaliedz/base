<div class="mb-3 form-group {{ $class }}">
    <label class="form-label" for="{{ $name }}">
        {{ $label }}
        @if($isRequired)
            <span class="text-danger">*</span>
        @else
            <small class="text-muted">({{ __('admin/inputs.optional') }})</small>
        @endif
    </label>

    <select
        name="{{ $name }}"
        class="form-select"
        {{ $isRequired ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        @if($isRequired && $requiredMessage) data-validation-required-message="{{  $requiredMessage }}" @endif
    >
        <option value="">{{ __('admin/inputs.select') }} {{ $placeholder }}</option>
        @foreach($options as $option)
            @if(is_array($option))
                <option value="{{ $option[$optionValueKey] }}" {{ $value == $option[$optionValueKey] ? 'selected' : '' }}>
                    {{ $option[$optionTextKey] }}
                </option>
            @else
                <option value="{{ $option->id }}" {{ $value == $option ? 'selected' : '' }}>
                    {{ $option->name }}
                </option>
            @endif
        @endforeach
    </select>
</div>
