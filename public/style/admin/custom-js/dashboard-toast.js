/**
 * Dashboard toast — centered on screen, mirrors page LTR/RTL (dir).
 * Glass-style UI, motion, and timed progress (no SweetAlert2).
 *
 * @param {string} message
 * @param {string} [icon='success']  success|error|warning|info
 * @param {number} [durationMs=2000]
 */
(function (global) {
    'use strict';

    var DEFAULT_MS = 2000;
    var LEAVE_FALLBACK_MS = 380;

    /** Semantic accents (Sneat-aligned); success uses green for clearer feedback */
    var COLOR = {
        success: '#71dd37',
        error: '#ff4c51',
        warning: '#ffab00',
        info: '#03c3ec',
    };

    var ICON = {
        success: 'ti-check',
        error: 'ti-x',
        warning: 'ti-alert-triangle',
        info: 'ti-info-circle',
    };

    function showTopToast(message, icon, durationMs) {
        icon = icon in COLOR ? icon : 'success';
        if (typeof durationMs !== 'number' || durationMs < 0 || !isFinite(durationMs)) {
            durationMs = DEFAULT_MS;
        }

        var text = message == null ? '' : String(message);
        var color = COLOR[icon];
        var iconName = ICON[icon] || ICON.success;

        var prev = document.getElementById('dashboard-toast-host');
        if (prev) {
            try {
                prev.remove();
            } catch (e) { /* ignore */ }
        }

        var host = document.createElement('div');
        host.id = 'dashboard-toast-host';
        host.className = 'dashboard-toast';
        host.setAttribute('role', 'alert');
        host.setAttribute('data-toast-variant', icon);
        var pageDir = (document.documentElement && document.documentElement.dir) || 'ltr';
        if (pageDir !== 'rtl' && pageDir !== 'ltr') {
            pageDir = 'ltr';
        }
        host.setAttribute('dir', pageDir);
        var pageLang = document.documentElement && document.documentElement.getAttribute('lang');
        if (pageLang) {
            host.setAttribute('lang', pageLang);
        }
        host.style.setProperty('--dashboard-toast-accent', color);
        host.style.setProperty('--toast-duration', durationMs + 'ms');

        var inner = document.createElement('div');
        inner.className = 'dashboard-toast__card';

        var row = document.createElement('div');
        row.className = 'dashboard-toast__row d-flex w-100 align-items-center';

        var body = document.createElement('div');
        body.className =
            'dashboard-toast__body toast-body d-flex align-items-center gap-2 flex-grow-1';

        var iconWrap = document.createElement('span');
        iconWrap.className = 'dashboard-toast__icon-wrap flex-shrink-0';
        iconWrap.setAttribute('aria-hidden', 'true');

        var ico = document.createElement('i');
        ico.className = 'ti ' + iconName + ' dashboard-toast__icon';
        ico.setAttribute('aria-hidden', 'true');

        var span = document.createElement('span');
        span.className = 'dashboard-toast__text';
        span.textContent = text;

        iconWrap.appendChild(ico);
        body.appendChild(iconWrap);
        body.appendChild(span);

        var closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'btn-close btn-close-sm dashboard-toast__close flex-shrink-0';
        closeBtn.setAttribute('aria-label', 'Close');

        row.appendChild(body);
        row.appendChild(closeBtn);

        var progress = document.createElement('div');
        progress.className = 'dashboard-toast__progress';
        progress.setAttribute('aria-hidden', 'true');
        var progressBar = document.createElement('div');
        progressBar.className = 'dashboard-toast__progress-bar';
        progress.appendChild(progressBar);

        inner.appendChild(row);
        inner.appendChild(progress);
        host.appendChild(inner);
        document.body.appendChild(host);

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                host.classList.add('dashboard-toast--visible');
            });
        });

        var removalTimer;

        function removeHost() {
            try {
                if (!host.parentNode) {
                    return;
                }
                host.classList.remove('dashboard-toast--visible');
                host.classList.add('dashboard-toast--leave');
                var done = false;
                function finish() {
                    if (done) {
                        return;
                    }
                    done = true;
                    try {
                        host.remove();
                    } catch (e) { /* ignore */ }
                }
                host.addEventListener('transitionend', function (ev) {
                    if (ev.target !== host) {
                        return;
                    }
                    finish();
                });
                setTimeout(finish, LEAVE_FALLBACK_MS);
            } catch (e) {
                try {
                    host.remove();
                } catch (e2) { /* ignore */ }
            }
        }

        closeBtn.addEventListener('click', function () {
            try {
                clearTimeout(removalTimer);
            } catch (e) { /* ignore */ }
            removeHost();
        });

        removalTimer = setTimeout(removeHost, durationMs);
    }

    global.showTopToast = showTopToast;
})(typeof window !== 'undefined' ? window : this);
