<div class="mb-3 form-group form-password-toggle {{ $class }}">
    <label class="form-label" for="{{ $name }}">
        {{ $label }}
        @if($isRequired)
            <span class="text-danger">*</span>
        @else
            <small class="text-muted">({{ __('admin/inputs.optional') }})</small>
        @endif
    </label>

    <div class="input-group input-group-merge">
        <input
            type="password"
            id="bs-validation-password"
            name="{{ $name }}"
            class="form-control"
            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
            aria-describedby="password"
            @if($isRequired) required  @endif
            @if($isRequired && $requiredMessage) data-validation-required-message="{{  $requiredMessage }}" @endif

            @if($minLength) minlength="{{ $minLength }}" @endif
            @if($minLength && $minLengthMessage) data-validation-minlength-message="{{  $minLengthMessage }}" @endif

            @if($maxLength) maxlength="{{ $maxLength }}" @endif

        />
        <span class="input-group-text cursor-pointer" id="basic-default-password4"><i class="ti ti-eye-off"></i></span>
    </div>
    <div class="help-block"></div>
</div>
