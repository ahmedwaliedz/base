$(document).on('click', '.show_filter', function () {
    $(this).toggleClass('active');
    var filterDiv = $('.filter_div');

    if (filterDiv.is(':visible')) {
        filterDiv.removeClass('active')
            .animate({opacity: 0}, {
                duration: 100,
                complete: function() {
                    $(this).slideUp(100);
                }
            });
    } else {
        filterDiv.css('opacity', 0)
            .slideDown({
                duration: 100,
                complete: function() {
                    $(this).css('display', '').addClass('active')
                        .animate({opacity: 1}, 100);
                }
            });
    }
});

// Active-filter indicator on the filter toggle button.
// Adds .has-active-filters when any filter input has a non-empty value.
function updateFilterActiveIndicator() {
    var $form = $('.filter-form');
    if (!$form.length) return;

    var hasValue = false;
    $form.find('input, select').each(function () {
        var $el = $(this);
        var type = ($el.attr('type') || '').toLowerCase();

        if (type === 'checkbox' || type === 'radio') {
            if ($el.is(':checked')) hasValue = true;
        } else if ($el.is('select')) {
            var val = $el.val();
            // ignore default "descending" / "ascending" on order_by
            if ($el.attr('name') === 'order_by') return;
            if (val !== null && val !== '' && val !== 'all') hasValue = true;
        } else if (type !== 'hidden') {
            if (($el.val() || '').trim() !== '') hasValue = true;
        }
    });

    $('.show_filter').toggleClass('has-active-filters', hasValue);
}

$(document).on('input change', '.filter-form input, .filter-form select', updateFilterActiveIndicator);
$(document).on('click', '.filter-reset', function () {
    setTimeout(updateFilterActiveIndicator, 50);
});
$(function () { updateFilterActiveIndicator(); });

// Date validation for start_date and end_date
$(document).on('keyup change', '.filter-form input[name="start_date"], .filter-form input[name="end_date"]', function() {
    var startDateInput = $('.filter-form input[name="start_date"]');
    var endDateInput = $('.filter-form input[name="end_date"]');

    var startDate = startDateInput.val();
    var endDate = endDateInput.val();

    // Only validate if both dates are filled
    if (startDate && endDate) {
        // Convert to Date objects for comparison
        var startDateObj = new Date(startDate);
        var endDateObj = new Date(endDate);

        // Check if start date is greater than end date
        if (startDateObj > endDateObj) {
            // Show error message
            if ($(this).attr('name') === 'start_date') {
                startDateInput.addClass('is-invalid');
                if (!startDateInput.next('.invalid-feedback').length) {
                    startDateInput.after('<div class="invalid-feedback">' + (window.translations?.start_date_error || 'Start date cannot be greater than end date') + '</div>');
                }
            } else {
                endDateInput.addClass('is-invalid');
                if (!endDateInput.next('.invalid-feedback').length) {
                    endDateInput.after('<div class="invalid-feedback">' + (window.translations?.end_date_error || 'End date cannot be smaller than start date') + '</div>');
                }
            }
        } else {
            // Clear error messages
            startDateInput.removeClass('is-invalid');
            endDateInput.removeClass('is-invalid');
            startDateInput.next('.invalid-feedback').remove();
            endDateInput.next('.invalid-feedback').remove();
        }
    }
});
