<div class="mb-3 form-group {{ $class ?? 'col-md-12' }}">
    <label class="form-label" for="{{ $name ?? 'map' }}">
        {{ $label ?? __('admin/inputs.location') }}
        @if($isRequired ?? false)
            <span class="text-danger">*</span>
        @else
            <small class="text-muted">({{ __('admin/inputs.optional') }})</small>
        @endif
    </label>
    <div class="map-container" style="position: relative;">
        <input type="text" id="map-search" class="form-control" placeholder="{{ __('admin/inputs.search_location') }}" style="position: absolute; top: 10px; left: 10px; z-index: 1; width: calc(100% - 20px); max-width: 300px;">
        <div id="map" style="height: 400px; width: 100%;"></div>
        <input type="hidden" id="lat" name="lat" value="{{ $lat }}">
        <input type="hidden" id="lng" name="lng" value="{{ $lng }}">
    </div>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent form submission when Enter is pressed in the search box
        document.getElementById('map-search').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                return false;
            }
        });

        if (typeof initMap === 'function') {
            initMap('{{ $lat}}', '{{ $lng}}', '{{ $apiKey}}');
        } else {
            console.error('Map initialization function not found');
        }
    });
</script>
@endpush
