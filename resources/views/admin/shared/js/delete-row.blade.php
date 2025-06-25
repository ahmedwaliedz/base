<script>
    $(document).on('click', '.delete-row', function(e) {
        e.preventDefault()
        Swal.fire({
            title: window.translations.are_you_sure,
            text: window.translations.are_you_sure_want_delete,
            type: 'warning',
            showDenyButton:   false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __('admin/main.confirm') }}',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonText: '{{ __('admin/main.cancel') }}',
            cancelButtonClass: 'btn btn-danger ml-1',
            denyButtonClass: 'd-none',
            buttonsStyling: false,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "delete",
                    url: $(this).data('route'),
                    data: {},
                    dataType: "json",
                    success: (response) => {
                        Swal.fire({
                            icon: 'success',
                            position: 'top-start',
                            text: '{{ __('admin/main.deleted_successfully') }}',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        setTimeout(() => {
                             window.location.reload();
                        }, 1000);
                    },
                    error: (xhr) => {
                        handelErrorByStatus(xhr)
                    },
                });
            }
        })
    });
</script>
