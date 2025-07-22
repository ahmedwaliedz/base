
function showLoader() {
    $('.table-content').fadeOut(100, function() {
        $('.table-content').html(loader).fadeIn(100);
    });
}

function hideLoader(html = null) {
    $('.table-content').fadeOut(100, function() {
        $('.table-content').html(html).fadeIn(100);
    });
}

function loadTable(filters) {
    $.ajax({
        url: window.adminDataUrl ? window.adminDataUrl : window.location.href,
        method: 'GET',
        data: filters,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        },
        beforeSend: ()=> {
            showLoader(1500);
        },
        success: function(html) {
            setTimeout(() => {
                hideLoader(html);
            }, 1500);
        },
        error: function(error) {
            const errorHtml = `
                <div class="d-flex justify-content-center flex-column align-items-center mb-3">
                    <lottie-player src="${window.translations?.lotti}" background="transparent" speed="2" style="width: 300px; height: 300px; margin: 0 auto;" loop autoplay></lottie-player>
                    <h5 style="position: absolute;bottom: 22%" class="text-danger">${window.translations?.error_loading_data}</h5>
                    <button class="btn btn-outline-danger reload mt-3" >${window.translations?.retry}</button>
                </div>
            `;
            hideLoader(errorHtml);
        }
    });
}

$(document).on('click','.pagination .page-link', function(e) {
    e.preventDefault();
    loadTable({page : $(this).attr('href').split('page=')[1]  , 'filters' : getFilters() });
});

$(document).on('submit', '.filter-form', function(e) {
    e.preventDefault();
    loadTable({'filters' : getFilters()});
});
 $(document).on('click', '.filter-reset', function(e) {
    $('.filter-form').find('input, select').each(function() {
        $(this).val('');
    });
    e.preventDefault();
    loadTable();
});
$(document).on('click' , '.reload', function(e) {
    e.preventDefault();
    loadTable({'filters' : getFilters()});
});
$(document).on('click', '.retry-btn', function(e) {
    e.preventDefault();
    loadTable({'filters' : getFilters()});
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
    return filters;
}

$(document).ready(function() {
    loadTable({'filters' : getFilters()});
});
