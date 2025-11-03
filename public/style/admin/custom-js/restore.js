$(document).on('click', '.restore-row', function(e) {
    e.preventDefault();
    const $btn = $(this);
    const route = $btn.data('route');
    Swal.fire({
        title: window.translations.are_you_sure,
        text: window.translations.are_you_sure_want_restore || window.translations.are_you_sure,
        type: 'warning',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: window.translations.confirmButtonText,
        confirmButtonClass: 'btn btn-primary',
        cancelButtonText: window.translations.cancelButtonText,
        cancelButtonClass: 'btn btn-danger ml-1',
        denyButtonClass: 'd-none',
        buttonsStyling: false,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'PUT',
                url: route,
                dataType: 'json',
                success: (response) => {
                    Swal.fire({
                        icon: 'success',
                        position: 'top-start',
                        text: window.translations.restored_successfully || 'Restored successfully',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    // If list table exists, refresh it; otherwise reload whole page (show page)
                    try {
                        if ($ && $('.append-page-content').length && typeof loadTable === 'function') {
                            const filters = (typeof getFilters === 'function') ? getFilters() : {};
                            loadTable({ 'filters': filters });
                        } else {
                            setTimeout(() => { window.location.reload(); }, 300);
                        }
                    } catch (_) {
                        setTimeout(() => { window.location.reload(); }, 300);
                    }
                },
                error: (xhr) => {
                    if (typeof handelErrorByStatus === 'function') {
                        handelErrorByStatus(xhr);
                        return;
                    }
                    Swal.fire({
                        icon: 'error',
                        text: xhr.responseJSON?.message || 'An error occurred',
                    });
                },
            });
        }
    });
});


