/**
 * Admin brand-color switcher: data-brand on <html>, localStorage admin.brandColor.
 * Dispatches brandchange for charts / other listeners.
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'admin.brandColor';
    var DEFAULT = 'violet';
    var VALID = ['violet', 'ocean', 'sky', 'emerald', 'magenta', 'sunset', 'slate', 'onyx'];

    function getSaved() {
        try {
            var v = localStorage.getItem(STORAGE_KEY);
            return VALID.indexOf(v) !== -1 ? v : DEFAULT;
        } catch (e) {
            return DEFAULT;
        }
    }

    function apply(name) {
        if (VALID.indexOf(name) === -1) {
            name = DEFAULT;
        }
        if (name === DEFAULT) {
            document.documentElement.removeAttribute('data-brand');
        } else {
            document.documentElement.setAttribute('data-brand', name);
        }
        var swatches = document.querySelectorAll('.brand-swatch');
        for (var i = 0; i < swatches.length; i++) {
            var el = swatches[i];
            el.classList.toggle('is-active', el.getAttribute('data-brand') === name);
        }
        try {
            window.dispatchEvent(new CustomEvent('brandchange', { detail: { brand: name } }));
        } catch (e2) { /* ignore */ }
    }

    window.__bootBrandColor = function () {
        apply(getSaved());
    };

    function wireGrid() {
        var grid = document.querySelector('.brand-color-grid');
        if (!grid) {
            apply(getSaved());
            return;
        }
        grid.addEventListener('click', function (e) {
            var btn = e.target.closest('.brand-swatch');
            if (!btn) return;
            var name = btn.getAttribute('data-brand');
            try {
                localStorage.setItem(STORAGE_KEY, name);
            } catch (err) { /* private mode */ }
            apply(name);
        });
        apply(getSaved());
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', wireGrid);
    } else {
        wireGrid();
    }
})();
