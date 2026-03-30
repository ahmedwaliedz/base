/**
 * Load CRUD statistics; uses the same filter payload as the data table when `filters` is omitted.
 *
 * @param {Object} [filters] – optional; pass explicit filter object or rely on getFilters() from admin-table.js
 */
function loadStats(filters) {
    var $root = $('#usersStatsContainer');
    if (!$root.length) {
        return;
    }

    if (filters == null) {
        filters = typeof getFilters === 'function' ? getFilters() : {};
    }

    $root.find('.hide_on_load').addClass('d-none').show();
    $root.find('.show_on_error').addClass('d-none').show();
    $root.find('.show_on_load').removeClass('d-none').show();

    $.ajax({
        url: statsUrl,
        method: 'GET',
        data: { filters: filters },
        success: function (html) {
            $root.find('#usersStatsContent .row').html(html);
            $root.find('.show_on_load').addClass('d-none').show();
            $root.find('.hide_on_load').removeClass('d-none').show();
            $root.find('.show_on_error').addClass('d-none').show();
        },
        error: function () {
            $root.find('.show_on_load').addClass('d-none').show();
            $root.find('.hide_on_load').addClass('d-none').show();
            $root.find('.show_on_error').removeClass('d-none').show();
        },
    });
}

$(function () {
    loadStats();
});

$(document).on('click', '#usersStatsContainer .js-crud-stats-reload', function (e) {
    e.preventDefault();
    loadStats();
});
