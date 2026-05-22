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
            @php
                $optValue = is_array($option)
                    ? ($option[$optionValueKey] ?? null)
                    : data_get($option, $optionValueKey, null);
                $optText = is_array($option)
                    ? ($option[$optionTextKey] ?? null)
                    : data_get($option, $optionTextKey, null);
            @endphp
            <option value="{{ $optValue }}" {{ (string)old($name, $value) === (string)$optValue ? 'selected' : '' }}>
                {{ $optText }}
            </option>
        @endforeach
    </select>
</div>
