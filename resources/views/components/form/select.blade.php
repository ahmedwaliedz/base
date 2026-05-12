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
                $normalizedOptValue = is_bool($optValue)
                    ? ($optValue ? '1' : '0')
                    : $optValue;
                $normalizedValue = is_bool($value)
                    ? ($value ? '1' : '0')
                    : $value;
            @endphp
            <option value="{{ $normalizedOptValue }}" {{ (string)$normalizedValue === (string)$normalizedOptValue ? 'selected' : '' }}>
                {{ $optText }}
            </option>
        @endforeach
    </select>
</div>
