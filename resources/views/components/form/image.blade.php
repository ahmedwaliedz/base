<div class="mb-3 d-flex flex-column align-items-center  {{ $class }}">

    <span class="form-label mb-3">
        {{ $label }}
        @if($isRequired)
            <span class="text-danger">*</span>
        @else
            <small class="text-muted">({{ __('admin/inputs.optional') }})</small>
        @endif
    </span>
    <div class="change-img form-group">

        <button type="button" class="btn delete-image">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <img src="{{ $value }}" @if($value == null) style="display: none" @endif loading="lazy" class="w-100 h-100 img-uploaded"  alt="busineness-license" />

        <input
            name="{{ $name }}"
            {{ $isRequired ? 'required' : '' }}
            @if($isRequired && $requiredMessage) data-validation-required-message="{{  $requiredMessage }}" @endif
            class="file-upload form-control"
            type="file"
            accept="{{$accept}}"
            alt="{{ $name }}"
        />

        <div @if($value != null) style="display: none" @endif class="placeholder-text flex-center flex-column">
            <img src="{{asset('style/admin/img/upload.svg')}}" alt="upload" />
        </div>

    </div>
</div>
