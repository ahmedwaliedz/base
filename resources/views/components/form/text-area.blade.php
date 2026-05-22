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
            <textarea
                name="{{ $lang }}[{{ $name }}]"
                class="form-control"
                id=""
                cols="30"
                rows="5"
                style="resize: none;"
                {{ $isRequired ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                @if($isRequired && $requiredMessage)
                    data-validation-required-message="{{  $requiredMessage . __('admin/inputs.'.$lang)  }} "
                @endif
                placeholder="{{__('admin/inputs.enter')}} {{ $placeholder  }} {{ __('admin/inputs.'.$lang) }}"
            >{{ old($lang.'.'.$name, $value[$lang][$name] ?? '') }}</textarea>
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
        <textarea
            name="{{ $name }}"
            class="form-control"
            id=""
            cols="30"
            rows="5"
            style="resize: none;"
            {{ $isRequired ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            @if($isRequired && $requiredMessage)
                data-validation-required-message="{{  $requiredMessage }}"
            @endif
            placeholder="{{__('admin/inputs.enter')}}{{ $placeholder }}"
        >{{ old($name, $value) }}</textarea>
    </div>
@endif
