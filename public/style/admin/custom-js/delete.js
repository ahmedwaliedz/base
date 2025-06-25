$(document).on('click', '.delete-record', function(e) {
    e.preventDefault();
    let selected = [];
    selected.push($(this).data('id'));
    deleteWithSwl(deleteAllRoute, selected);
});

$(document).on('click', '.delete-all-button' , function(e) {
    e.preventDefault();
    let selected = [];
    $('.table-content .dt-checkboxes:checked').each(function() {
        selected.push($(this).data('id'));
    });
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
                data: {ids : selected},
                dataType: "json",
                success: (response) => {
                    Swal.fire({
                        denyButtonClass: 'd-none',
                        icon: 'success',
                        position: 'top-start',
                        text: window.translations.deleted_successfully,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    loadTable({'filters': getFilters()});
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
function toggleDeleteAllButton() {
    const hasSelectedRows = $('.table-content .dt-checkboxes:checked').length > 0;
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

