$(document).on('click', '.delete-record', function(e) {
    e.preventDefault();
    let selected = [];
    selected.push($(this).data('id'));
    const deleteAllRoute = $(this).data('route');
    deleteWithSwl(deleteAllRoute, selected);
});

// Handle per-row delete action inside tables (anchors with class .delete-row)
$(document).on('click', '.delete-row', function(e) {
    e.preventDefault();
    let selected = [];
    const id = $(this).data('id');
    if (id !== undefined && id !== null && id !== '') {
        selected.push(id);
    }
    const route = $(this).data('route');
    if (!route) { return; }
    deleteWithSwl(route, selected);
});

$(document).on('click', '.delete-all-button' , function(e) {
    e.preventDefault();
    let selected = [];
    $('.table-content .dt-checkboxes:checked').each(function() {
        const id = $(this).val() ?? $(this).data('id');
        if (id !== undefined && id !== null && id !== '') {
            selected.push(id);
        }
    });
    const deleteAllRoute = $(this).data('route');
    deleteWithSwl(deleteAllRoute, selected);
});

function deleteWithSwl(Route , selected) {
    Swal.fire({
        title: window.translations.are_you_sure,
        text: window.translations.are_you_sure_want_delete,
        icon: 'warning',
        showCancelButton: true,
        focusCancel: true,
        width: '26rem',
        padding: 0,
        buttonsStyling: false,
        customClass: {
            container: 'admin-swal-confirm-container',
            popup: 'admin-swal-confirm-popup',
            title: 'admin-swal-confirm__title',
            htmlContainer: 'admin-swal-confirm__text',
            icon: 'admin-swal-confirm__icon',
            confirmButton: 'btn btn-primary btn-admin-swal-confirm',
            cancelButton: 'btn btn-outline-secondary btn-admin-swal-cancel',
            actions: 'admin-swal-confirm__actions',
        },
        confirmButtonText: window.translations.confirmButtonText,
        cancelButtonText: window.translations.cancelButtonText,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: Route,
                data: { ids: selected, _method: 'DELETE' },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    try {
                        if (typeof showTopToast === 'function') {
                            showTopToast(window.translations.deleted_successfully, 'success', 1500);
                        }
                    } catch (_) {}
                    // Clear selections and hide the bulk delete button immediately
                    try {
                        $('thead .dt-checkboxes, tbody .dt-checkboxes').prop('checked', false);
                        if (typeof toggleDeleteAllButton === 'function') {
                            toggleDeleteAllButton();
                        } else {
                            $('.delete-all-button').addClass('d-none');
                        }
                    } catch (_) {}
                    try {
                        // If we're on a list page, refresh the table; otherwise reload the page (show page)
                        if ($ && $('.append-page-content').length && typeof loadTable === 'function') {
                            const filters = (typeof getFilters === 'function') ? getFilters() : {};
                            loadTable({'filters': filters});
                        } else {
                            setTimeout(() => { window.location.reload(); }, 300);
                        }
                    } catch (_) {
                        setTimeout(() => { window.location.reload(); }, 300);
                    }
                },
                error: (xhr) => {
                    var errMsg = (xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'An error occurred';
                    try {
                        if (typeof showTopToast === 'function') {
                            showTopToast(errMsg, 'error', 3500);
                        } else if (window.Swal) {
                            Swal.fire({ icon: 'error', text: errMsg });
                        }
                    } catch (_) {
                        if (window.Swal) {
                            Swal.fire({ icon: 'error', text: errMsg });
                        }
                    }
                },
            });
        }
    });
}
toggleDeleteAllButton();

function toggleDeleteAllButton() {
    const $checked = $('tbody .dt-checkboxes:checked');
    const count = $checked.length;
    const hasSelectedRows = count > 0;

    // Legacy hook — kept so callers like .delete-all-button still get the class state
    if (hasSelectedRows) {
        $('.delete-all-button').removeClass('d-none');
    } else {
        $('.delete-all-button').addClass('d-none');
    }

    // New: contextual bulk bar
    const $bar = $('#crudBulkBar');
    if ($bar.length) {
        $bar.toggleClass('is-visible', hasSelectedRows);
        $bar.attr('aria-hidden', hasSelectedRows ? 'false' : 'true');
        $('#crudBulkBarCount').text(count);
    }
}

// Clear selection from the bulk bar
$(document).on('click', '#crudBulkBarClear', function (e) {
    e.preventDefault();
    $('thead .dt-checkboxes, tbody .dt-checkboxes').prop('checked', false);
    toggleDeleteAllButton();
});

$(document).on('click', 'thead .dt-checkboxes', function() {
    const isChecked = $(this).prop('checked');
    $('tbody .dt-checkboxes').prop('checked', isChecked);
    toggleDeleteAllButton();
});

$(document).on('click', 'tbody .dt-checkboxes', function() {
    const allChecked = $('tbody .dt-checkboxes:checked').length === $('tbody .dt-checkboxes').length;
    $('thead .dt-checkboxes').prop('checked', allChecked);
    toggleDeleteAllButton();
});

