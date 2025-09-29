function handelErrorByStatus(xhr , form) {
    if (xhr.status === 422) {
        addValidationError(xhr.responseJSON.errors ,form)
    }else if(xhr.status === 423){
        fireBlockAction(xhr.responseJSON.message)
    }else if(xhr.status === 400){
        fireUnAuthorizedAction(xhr.responseJSON.message)
    }else{
        // In development, show rich exception modal if available
        if (window.APP_DEBUG && typeof fireDevAjaxExceptionModal === 'function') {
            try { fireDevAjaxExceptionModal(xhr, extractRequestSettingsFromForm(form)); } catch(_) {}
        } else {
            fireUnknownError((xhr.responseJSON && xhr.responseJSON.message) || 'Unknown error')
        }
    }
}

function extractRequestSettingsFromForm(form) {
    try {
        var $form = $(form);
        var method = ($form.attr('method') || 'GET').toUpperCase();
        var url = $form.attr('action') || window.location.href;
        var fd = new FormData($form[0]);
        return { type: method, url: url, data: fd };
    } catch (_) { return {}; }
}
