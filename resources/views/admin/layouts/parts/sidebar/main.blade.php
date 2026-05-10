<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="sb-orb sb-orb--1" aria-hidden="true"></div>
    <div class="sb-orb sb-orb--2" aria-hidden="true"></div>

    <div class="app-brand demo">
        <a href="{{route('admin.home')}}" class="app-brand-link w-100 h-100 d-flex align-items-center justify-content-center">
            <img class="mw-100 h-100" style="object-fit: contain" src="{{cache()->get('settings')['logo']}}" alt="{{cache()->get('settings')['name'][adminLang()]}}">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>

    </div>

    <div class="menu-inner-shadow"></div>

    <div class="sidebar-search px-3 pt-3 pb-2">
        <div class="sidebar-search-wrap">
            <i class="ti ti-search sidebar-search-icon" aria-hidden="true"></i>
            <label for="sidebar-search-input" class="visually-hidden">{{ __('admin/main.search') }}</label>
            <input type="text" id="sidebar-search-input"
                   class="form-control sidebar-search-input"
                   placeholder="{{ __('admin/main.search') }}"
                   autocomplete="off">
            <button type="button" class="sidebar-search-clear" hidden aria-label="{{ __('admin/main.reset') }}">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="sidebar-search-empty" hidden>{{ __('admin/main.no_result') }}</div>
    </div>

    <ul class="menu-inner py-1">
        {!!   \App\Builders\Sidebar\SidebarBuilder::buildFromConfig() !!}
    </ul>
</aside>

<script>
(function () {
    var menu = document.getElementById('layout-menu');
    if (!menu) return;

    var root = menu.querySelector(':scope > ul.menu-inner > ul.menu-inner');
    var input = document.getElementById('sidebar-search-input');
    var clearBtn = menu.querySelector('.sidebar-search-clear');
    var emptyMsg = menu.querySelector('.sidebar-search-empty');
    var forcedOpen = new Set();

    if (!root || !input) return;

    function normalize(s) {
        return String(s || '').toLowerCase().replace(/\s+/g, ' ').trim();
    }

    function setTooltips() {
        menu.querySelectorAll('a.menu-link').forEach(function (a) {
            var raw = (a.textContent || '').trim().replace(/\s+/g, ' ');
            if (raw) a.setAttribute('data-tooltip', raw);
        });
    }

    setTooltips();

    function processItem(li, q) {
        var link = li.querySelector(':scope > a.menu-link');
        var sub = li.querySelector(':scope > .menu-sub');
        var selfMatch = !q || (link && normalize(link.textContent).indexOf(q) !== -1);
        var descendantMatch = false;

        if (sub) {
            sub.querySelectorAll(':scope > .menu-item').forEach(function (child) {
                if (processItem(child, q)) descendantMatch = true;
            });
        }

        var visible = !q || selfMatch || descendantMatch;
        li.classList.toggle('menu-search-hidden', !visible);

        if (visible && sub && q && descendantMatch && !selfMatch) {
            li.classList.add('open');
            forcedOpen.add(li);
        }

        return visible;
    }

    function clearFilter() {
        menu.querySelectorAll('.menu-search-hidden').forEach(function (el) {
            el.classList.remove('menu-search-hidden');
        });
        forcedOpen.forEach(function (li) {
            li.classList.remove('open');
        });
        forcedOpen.clear();
        if (emptyMsg) emptyMsg.hidden = true;
    }

    function runFilter() {
        var q = normalize(input.value);

        forcedOpen.forEach(function (li) {
            li.classList.remove('open');
        });
        forcedOpen.clear();

        if (!q) {
            clearFilter();
            if (clearBtn) clearBtn.hidden = true;
            return;
        }

        if (clearBtn) clearBtn.hidden = false;

        root.querySelectorAll(':scope > .menu-item').forEach(function (li) {
            processItem(li, q);
        });

        var visibleCount = 0;
        root.querySelectorAll(':scope > .menu-item').forEach(function (li) {
            if (!li.classList.contains('menu-search-hidden')) visibleCount++;
        });
        if (emptyMsg) emptyMsg.hidden = visibleCount !== 0;
    }

    input.addEventListener('input', runFilter);
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            input.value = '';
            clearBtn.hidden = true;
            clearFilter();
        });
    }
})();
</script>
