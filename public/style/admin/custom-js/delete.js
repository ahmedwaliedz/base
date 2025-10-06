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
    console.log(selected);
    const deleteAllRoute = $(this).data('route');
    deleteWithSwl(deleteAllRoute, selected);
});

function deleteWithSwl(Route , selected) {
    Swal.fire({
        title: window.translations.are_you_sure,
        text: window.translations.are_you_sure_want_delete,
        type: 'warning',
        showDenyButton:   false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:  window.translations.confirmButtonText,
        confirmButtonClass: 'btn btn-primary',
        cancelButtonText: window.translations.cancelButtonText,
        cancelButtonClass: 'btn btn-danger ml-1',
        denyButtonClass: 'd-none',
        buttonsStyling: false,
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
                    Swal.fire({
                        denyButtonClass: 'd-none',
                        icon: 'success',
                        position: 'top-start',
                        text: window.translations.deleted_successfully,
                        showConfirmButton: false,
                        timer: 1000
                    });
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
                    Swal.fire({
                        icon: 'error',
                        confirmButtonClass: 'd-none',
                        denyButtonClass: 'd-none',
                        text: xhr.responseJSON?.message || 'An error occurred',
                        cancelButtonText: window.translations.cancelButtonText,
                    });
                },
            });
        }
    });
}
toggleDeleteAllButton()
function toggleDeleteAllButton() {
    const hasSelectedRows = $('tbody .dt-checkboxes:checked').length > 0;
    if (hasSelectedRows) {
        $('.delete-all-button').removeClass('d-none');
    } else {
        $('.delete-all-button').addClass('d-none');
    }
}

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

