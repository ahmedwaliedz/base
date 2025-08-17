function showTableLoader() {
    $('.data-rows').fadeOut();
    $('.data-rows').remove();
    $('.table-loader').fadeIn();
}

function hideTableLoader(html) {
    $('.table-loader').fadeOut('fast', function () {
        const $newContent = $(html).hide();
        $('.append-page-content').append($newContent);
        $newContent.fadeIn('slow');
    });
}

function loadTable(filters) {
    $.ajax({
        url: window.adminDataUrl ? window.adminDataUrl : window.location.href,
        method: 'GET',
        data: filters,
        headers: {
            'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html'
        }, beforeSend: () => {
            showTableLoader();
        }, success: function (html) {
            hideTableLoader(html);
        }, error: function (error) {
            hideTableLoader();
        }
    });
}

$(document).on('click', '.pagination .page-link', function (e) {
    e.preventDefault();
    const filters = getFilters();
    loadTable({page: $(this).attr('href').split('page=')[1], 'filters': filters});
});
$(document).on('submit', '.filter-form', function (e) {
    e.preventDefault();
    const filters = getFilters();
    loadTable({'filters': filters});
});
$(document).on('click', '.filter-reset', function (e) {
    $('.filter-form').find('input, select').each(function () {
        $(this).val('');
    });
    e.preventDefault();
    const filters = getFilters();
    loadTable({'filters': filters});
});
$(document).on('click', '.reload', function (e) {
    e.preventDefault();
    const filters = getFilters();
    loadTable({'filters': filters});
});
$(document).on('click', '.retry-btn', function (e) {
    e.preventDefault();
    const filters = getFilters();
    loadTable({'filters': filters});
});

function getFilters() {
    const filters = {};
    const $filterForm = $('.filter-form');
    if ($filterForm.length) {
        $filterForm.serializeArray().forEach(item => {
            if (item.value) {
                filters[item.name] = item.value;
            }
        });
    }

    // Always add per_page to filters
    const perPage = $('#per-page-select').val();
    if (perPage) {
        filters['per_page'] = perPage;
    }

    return filters;
}

$(document).on('change', '#per-page-select', function () {
    const filters = getFilters();
    loadTable({'filters': filters});
});
$(document).on('click', '.per-page-item', function (e) {
    e.preventDefault();
    const value = $(this).data('value');
    $('#per-page-select').val(value).trigger('change');

    // Update the button text
    const buttonText = $(this).closest('.btn-group').find('.dropdown-toggle');
    const perPageLabel = buttonText.find('span').prop('outerHTML');
    buttonText.html(perPageLabel + ' ' + buttonText.text().split(':')[0] + ': ' + value);
});

$(document).on('click', '.apply-custom-per-page', function (e) {
    e.preventDefault();
    applyCustomPerPage($(this));
});

$(document).on('keypress', '#custom-per-page', function (e) {
    if (e.which === 13) { // Enter key
        e.preventDefault();
        applyCustomPerPage($(this).siblings('.apply-custom-per-page'));
    }
});

function applyCustomPerPage(buttonElement) {
    const value = $('#custom-per-page').val();

    if (value && parseInt(value) > 0) {
        $('#per-page-select').val(value).trigger('change');

        // Update the button text
        const buttonText = buttonElement.closest('.btn-group').find('.dropdown-toggle');
        const perPageLabel = buttonText.find('span').prop('outerHTML');
        buttonText.html(perPageLabel + ' ' + buttonText.text().split(':')[0] + ': ' + value);

        // Close the dropdown
        buttonElement.closest('.dropdown-menu').prev('.dropdown-toggle').dropdown('toggle');
    }
}

$(document).ready(function () {
    const filters = getFilters();
    loadTable({'filters': filters});
});
