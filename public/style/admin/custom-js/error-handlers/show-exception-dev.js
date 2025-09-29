function fireDevAjaxExceptionModal(xhr, settings) {
    try {
        if (!window.APP_DEBUG) { return; }

        var title = 'AJAX Exception';
        var htmlBody = '';

        var contentType = (xhr.getResponseHeader && xhr.getResponseHeader('Content-Type')) || '';
        var responseText = xhr.responseText || '';

        // Prefer JSON structure if provided
        try {
            var json = (typeof xhr.responseJSON !== 'undefined') ? xhr.responseJSON : JSON.parse(responseText || '{}');
            if (json && (json.message || json.exception || json.trace)) {
                title = json.exception || title;
                htmlBody += '<div class="text-start">';
                if (json.message) {
                    htmlBody += '<div class="mb-2"><strong>Message:</strong> ' + escapeHtml(String(json.message)) + '</div>';
                }
                if (json.file) {
                    htmlBody += '<div class="mb-2"><strong>File:</strong> ' + escapeHtml(String(json.file)) + '</div>';
                }
                if (json.line) {
                    htmlBody += '<div class="mb-2"><strong>Line:</strong> ' + escapeHtml(String(json.line)) + '</div>';
                }
                if (json.trace) {
                    var traceStr = Array.isArray(json.trace) ? JSON.stringify(json.trace, null, 2) : String(json.trace);
                    htmlBody += '<pre class="mb-0" style="white-space:pre-wrap;max-height:50vh;overflow:auto;">' + escapeHtml(traceStr) + '</pre>';
                }
                htmlBody += '</div>';
            }
        } catch (_) {}

        // Fallback to Whoops HTML or raw HTML when server returns HTML error page
        if (!htmlBody && contentType.indexOf('text/html') !== -1 && responseText) {
            // Wrap in iframe-like container to avoid script execution
            htmlBody = '<div class="bg-light border rounded p-2" style="max-height:60vh;overflow:auto;">' + responseText + '</div>';
        }

        // As last resort, show status and snippet
        if (!htmlBody) {
            var snippet = (responseText || '').slice(0, 2000);
            htmlBody = '<pre class="mb-0" style="white-space:pre-wrap;max-height:50vh;overflow:auto;">' + escapeHtml(snippet) + '</pre>';
        }

        // Build 3-tab modal: Response, Payload, Preview
        ensureDevExceptionModal();

        var payloadTabHtml = buildPayloadTab(settings, xhr);
        var responseTabHtml = buildResponseTab(xhr, htmlBody);
        var previewTabHtml = buildPreviewTab(xhr);

        var $modal = $('#devExceptionModal');
        $modal.find('.modal-title').text(title + ' (' + (xhr.status || 'ERR') + ')');

        var tabsHtml = '' +
            '<ul class="nav nav-tabs" role="tablist">' +
            '  <li class="nav-item" role="presentation">' +
            '    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#dev-exc-response" type="button" role="tab">Response</button>' +
            '  </li>' +
            '  <li class="nav-item" role="presentation">' +
            '    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#dev-exc-payload" type="button" role="tab">Payload</button>' +
            '  </li>' +
            '  <li class="nav-item" role="presentation">' +
            '    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#dev-exc-preview" type="button" role="tab">Preview</button>' +
            '  </li>' +
            '</ul>' +
            '<div class="tab-content p-3 border border-top-0 rounded-bottom" style="max-height:60vh;overflow:auto;">' +
            '  <div class="tab-pane fade show active" id="dev-exc-response" role="tabpanel">' + responseTabHtml + '</div>' +
            '  <div class="tab-pane fade" id="dev-exc-payload" role="tabpanel">' + payloadTabHtml + '</div>' +
            '  <div class="tab-pane fade" id="dev-exc-preview" role="tabpanel">' + previewTabHtml + '</div>' +
            '</div>';

        $modal.find('.modal-body').html(tabsHtml);
        var modal = new bootstrap.Modal($modal[0]);
        modal.show();
    } catch (e) {
        // no-op
    }
}

function escapeHtml(str) {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function stripHtml(html) {
    var div = document.createElement('div');
    div.innerHTML = html;
    return (div.textContent || div.innerText || '').trim();
}

function ensureDevExceptionModal() {
    if (document.getElementById('devExceptionModal')) return;
    var modalHtml = '' +
        '<div class="modal fade" id="devExceptionModal" tabindex="-1" aria-hidden="true">' +
        '  <div class="modal-dialog modal-xl modal-dialog-scrollable">' +
        '    <div class="modal-content">' +
        '      <div class="modal-header">' +
        '        <h5 class="modal-title">AJAX Exception</h5>' +
        '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
        '      </div>' +
        '      <div class="modal-body p-0" style="max-height:70vh;overflow:hidden;"></div>' +
        '      <div class="modal-footer">' +
        '        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
        '      </div>' +
        '    </div>' +
        '  </div>' +
        '</div>';
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

function buildPayloadTab(settings, xhr) {
    try {
        var summary = {
            method: (settings && settings.type) || (xhr && xhr.responseURL ? 'GET' : ''),
            url: (settings && settings.url) || (xhr && xhr.responseURL) || '',
            headers: (settings && settings.headers) || undefined
        };
        var data = (settings && settings.data) || undefined;

        var pretty = '';
        if (data instanceof FormData) {
            var obj = {};
            data.forEach(function (v, k) { obj[k] = v; });
            pretty = JSON.stringify({ summary: summary, formData: obj }, null, 2);
        } else if (typeof data === 'string') {
            // attempt to parse querystring/JSON
            try { pretty = JSON.stringify({ summary: summary, data: JSON.parse(data) }, null, 2); }
            catch(_) { pretty = JSON.stringify({ summary: summary, data: data }, null, 2); }
        } else {
            pretty = JSON.stringify({ summary: summary, data: data }, null, 2);
        }
        return '<pre class="mb-0" style="white-space:pre-wrap;">' + escapeHtml(pretty) + '</pre>';
    } catch(_) { return '<div>Unable to render payload.</div>'; }
}

function buildResponseTab(xhr, htmlBody) {
    var ct = (xhr.getResponseHeader && xhr.getResponseHeader('Content-Type')) || '';
    if (ct.indexOf('application/json') !== -1) {
        try {
            var json = xhr.responseJSON || JSON.parse(xhr.responseText || '{}');
            var pretty = JSON.stringify(json, null, 2);
            return '<pre class="mb-0" style="white-space:pre-wrap;">' + escapeHtml(pretty) + '</pre>';
        } catch(_) {}
    }
    return htmlBody;
}

function buildPreviewTab(xhr) {
    var ct = (xhr.getResponseHeader && xhr.getResponseHeader('Content-Type')) || '';
    if (ct.indexOf('text/html') !== -1 && xhr.responseText) {
        return '<iframe sandbox="allow-same-origin" class="w-100 border-0" style="height:60vh;" srcdoc="' +
            sanitizeForSrcDoc(xhr.responseText) + '"></iframe>';
    }
    return '<div class="text-muted">No HTML preview available.</div>';
}

function sanitizeForSrcDoc(html) {
    // Basic neutralization for srcdoc context
    return String(html).replace(/<script[\s\S]*?>[\s\S]*?<\/script>/gi, '')
        .replace(/on\w+=\"[^\"]*\"/g, '')
        .replace(/on\w+=\'[^\']*\'/g, '')
        .replace(/javascript:/gi, '');
}

// Register a global jQuery AJAX error handler in development to trigger the modal
;(function registerGlobalAjaxExceptionHandler(){
    try {
        if (typeof window === 'undefined' || typeof $ === 'undefined' || !$) { return; }
        $(document).ajaxError(function (event, xhr, settings) {
            try {
                if (!window.APP_DEBUG) { return; }
                if (!xhr) { return; }
                var ct = (xhr.getResponseHeader && xhr.getResponseHeader('Content-Type')) || '';
                var isServerError = (xhr.status >= 404);
                var isHtmlError = ct.indexOf('text/html') !== -1;
                if (isServerError || isHtmlError) {
                    fireDevAjaxExceptionModal(xhr, settings);
                }
            } catch (_) { /* no-op */ }
        });
    } catch (_) { /* no-op */ }
})();


