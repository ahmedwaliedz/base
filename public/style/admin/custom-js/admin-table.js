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

// Export handlers
$(document).on('click', '.export-action', function (e) {
    e.preventDefault();
    const format = $(this).data('format');
    const filters = getFilters();
    const selectedIds = getSelectedIds();
    showExportLoader();

    if (format === 'copy') {
        // request JSON then copy to clipboard as plain text
        triggerExport('json', filters, false, selectedIds).then(({blob}) => {
            blob.text().then(text => {
                navigator.clipboard.writeText(text).then(() => {
                    hideExportLoader();
                });
            });
        });
        return;
    }

    if (format === 'pdf' || format === 'docx') {
        hideExportLoader();
        // Unsupported notice (no backend yet)
        if (window.Swal) {
            Swal.fire({ icon: 'info', text: 'Export to ' + format.toUpperCase() + ' is not available yet.' });
        }
        return;
    }

    const downloadFormat = format === 'csv' ? 'csv' : 'json';
    triggerExport(downloadFormat, filters, false, selectedIds)
        .then(({blob}) => {
            downloadBlob(blob, buildFileName(downloadFormat));
        })
        .catch((err) => {
            if (window.Swal) {
                Swal.fire({ icon: 'error', text: err?.message || 'Export failed' });
            }
        })
        .finally(() => hideExportLoader());
});

function triggerExport(format, filters, download = false, selectedIds = []) {
    const url = buildExportUrl(format, filters, selectedIds);
    return fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
        .then(res => {
            if (!res.ok) {
                return res.text().then(text => {
                    let msg = 'Export failed';
                    try { const j = JSON.parse(text); if (j?.message) msg = j.message; } catch (_) { if (text) msg = text; }
                    throw new Error(msg);
                });
            }
            return res.blob();
        })
        .then(blob => ({blob}));
}

function buildExportUrl(format, filters, selectedIds = []) {
    const baseUrl = (window.adminDataUrl ? window.adminDataUrl : window.location.href).split('?')[0];
    const params = new URLSearchParams();
    params.set('export', format);
    Object.keys(filters || {}).forEach(key => {
        if (filters[key] !== undefined && filters[key] !== null && filters[key] !== '') {
            params.append(`filters[${key}]`, filters[key]);
        }
    });
    if (selectedIds && selectedIds.length) {
        selectedIds.forEach(id => params.append('ids[]', id));
    }
    return baseUrl + '?' + params.toString();
}

function getSelectedIds() {
    const ids = [];
    $('.table-content .dt-checkboxes:checked').each(function() {
        const id = $(this).val() ?? $(this).data('id');
        if (id !== undefined && id !== null && id !== '') {
            ids.push(id);
        }
    });
    return ids;
}

// Export loader helpers
function showExportLoader() {
    const $loader = $('#page-loader');
    if ($loader.length) {
        if ($loader.is(':visible')) return;
        $loader.css('display', 'flex').hide().fadeIn('fast');
    }
}

function hideExportLoader() {
    const $loader = $('#page-loader');
    if ($loader.length) {
        $loader.fadeOut('fast');
    }
}

function downloadBlob(blob, filename) {
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    a.remove();
    window.URL.revokeObjectURL(url);
}

function buildFileName(format) {
    const path = (window.location.pathname || '').split('/').filter(Boolean);
    const resource = path[path.length - 1] || 'export';
    const ts = new Date().toISOString().replace(/[:.]/g, '-');
    const ext = format === 'csv' ? 'csv' : 'json';
    return resource + '-' + ts + '.' + ext;
}
