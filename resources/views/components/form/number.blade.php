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
        type="number"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{__('admin/inputs.enter')}}{{ $placeholder }}"
        class="form-control"
        {{ $isRequired ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        @if($isRequired && $requiredMessage) data-validation-required-message="{{  $requiredMessage }}" @endif
        @if($isRequired) required  @endif
        @if($minLength) minlength="{{ $minLength }}" @endif
        @if($maxLength) maxlength="{{ $maxLength }}" @endif
        data-validation-number-mxessage="{{  __('admin/validation.number') }}"
        @if($minLengthMessage) data-validation-minLength-message="{{  $minLengthMessage }} " @endif
        @if($minLengthMessage) data-validation-maxLength-message="{{  $maxLengthMessage }} " @endif
    />
</div>
