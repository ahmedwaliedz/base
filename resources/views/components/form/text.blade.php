@if($isMultiLanguage)
    @foreach(languages() as $lang)
        <div class="mb-3 form-group {{ $class }}">
            <label class="form-label" for="{{ $name }}_{{ $lang }}">
                {{ $label }} {{ __('admin/inputs.'.$lang) }}
                @if($isRequired)
                    <span class="text-danger">*</span>
                @else
                    <small class="text-muted">({{ __('admin/inputs.optional') }})</small>
                @endif
            </label>
            <input
                type="text"
                name="{{ $lang }}[{{ $name }}]"
                value="{{ $value && isset($value[$lang]['name']) ? $value[$lang]['name'] : '' }}"
                placeholder="{{__('admin/inputs.enter')}} {{ $placeholder  }} {{ __('admin/inputs.'.$lang) }}"
                class="form-control"
                {{ $isRequired ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                @if($isRequired && $requiredMessage)
                    data-validation-required-message="{{  $requiredMessage . __('admin/inputs.'.$lang)  }} "
                @endif
                @if($isRequired) required  @endif
                @if($minLength) minlength="{{ $minLength }}" @endif
                @if($maxLength) maxlength="{{ $maxLength }}" @endif
            />
        </div>
    @endforeach
@else
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
            type="text"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{__('admin/inputs.enter')}}{{ $placeholder }}"
            class="form-control"
            {{ $isRequired ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            @if($isRequired)
                required
            @endif
            @if($isRequired && $requiredMessage)
                data-validation-required-message="{{  $requiredMessage }}"
            @endif

            @if($minLength) minLength="{{ $minLength }}" @endif
            @if($maxLength) minLength="{{ $maxLength }}" @endif
            @if($minLengthMessage) data-validation-minLength-message="{{  $minLengthMessage }} " @endif
            @if($minLengthMessage) data-validation-maxLength-message="{{  $maxLengthMessage }} " @endif

        />
    </div>
@endif
