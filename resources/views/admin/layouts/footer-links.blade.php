<script src="{{asset('style/admin/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('style/admin/vendor/libs/popper/popper.js')}}"></script>
<script src="{{asset('style/admin/vendor/js/bootstrap.js')}}"></script>
<script src="{{asset('style/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('style/admin/vendor/libs/hammer/hammer.js')}}"></script>
<script src="{{asset('style/admin/vendor/libs/i18n/i18n.js')}}"></script>
<script src="{{asset('style/admin/vendor/libs/typeahead-js/typeahead.js')}}"></script>
<script src="{{asset('style/admin/vendor/js/menu.js')}}"></script>
<script src="{{asset('style/admin/vendor/libs/block-ui/block-ui.js')}}"></script>
<script src="{{asset('style/admin/vendor/libs/sortablejs/sortable.js')}}"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="{{asset('style/admin/js/main.js')}}"></script>
    <script src="{{ asset('style/admin/custom-js/brand-color-switcher.js') }}"></script>
    <script src="{{ asset('style/admin/custom-js/dashboard-toast.js') }}"></script>
{{-- <script src="{{ asset('style/admin/custom-js/error-handlers/show-exception-dev.js') }}"></script> --}}
<script>
    // Expose environment flags for frontend logic
    window.APP_DEBUG = @json(config('app.debug'));
    window.APP_ENV = @json(app()->environment());

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    window.translations = {
        error_loading_data: "{{ __('admin/main.error_loading_data') }}",
        retry: "{{ __('admin/main.retry') }}",
        lotti: "{{ asset('storage/uploads/settings/fail.json') }}",
        start_date_error: "{{ __('admin/main.start_date_greater_than_end_date') }}",
        end_date_error: "{{ __('admin/main.end_date_smaller_than_start_date') }}",
        are_you_sure: "{{ __('admin/main.are_you_sure') }}",
        are_you_sure_want_delete: "{{ __('admin/main.are_you_sure_to_delete') }}",
        confirmButtonText: '{{ __('admin/main.confirm') }}',
        cancelButtonText: '{{ __('admin/main.cancel') }}',
        deleted_successfully: "{{ __('admin/main.deleted_successfully') }}",
        are_you_sure_want_restore: "{{ __('admin/main.are_you_sure_to_restore') }}",
        restored_successfully: "{{ __('admin/main.restored_successfully') }}",
        copied_to_clipboard: "{{ __('admin/main.copied_to_clipboard') }}",
    };

    // Delegated image click preview (opt-out with .no-preview or data-no-preview="true")
    $(document).on('click', 'img', function (e) {
        var $img = $(this);
        if ($img.hasClass('no-preview') || $img.data('no-preview') === true || $img.data('no-preview') === 'true') {
            return;
        }
        var src = $img.attr('data-preview-src') || $img.attr('src');
        if (!src) { return; }
        var alt = $img.attr('alt') || '';

        var $modal = $('#globalImagePreviewModal');
        var $preview = $('#globalImagePreview');
        var $caption = $('#globalImageCaption');

        $preview.attr('src', src);

        if (alt && alt.trim().length > 0) {
            $caption.text(alt).removeClass('d-none');
        } else {
            $caption.addClass('d-none').text('');
        }

        var modal = new bootstrap.Modal($modal[0]);
        modal.show();
    });

    // Global AJAX exception handler moved to show-exception-dev.js
</script>
@stack('js')
