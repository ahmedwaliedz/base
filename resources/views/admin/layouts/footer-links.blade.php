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
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    {{--const loader = `--}}
    {{--        <div class="text-center p-5 table-loader">--}}
    {{--            <lottie-player src="{{ asset('storage/uploads/settings/Load.json') }}" background="transparent" speed="1" style="width: 200px; height: 200px; margin: 0 auto;" loop autoplay></lottie-player>--}}
    {{--        </div>--}}
    {{--    `;--}}

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
    };
</script>
@stack('js')
