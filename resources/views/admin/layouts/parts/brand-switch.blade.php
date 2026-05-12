<li class="nav-item dropdown me-2 me-xl-1">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
       data-bs-toggle="dropdown" data-bs-container="body"
       data-bs-popper-config='{"strategy":"fixed"}'
       aria-expanded="false"
       aria-label="{{ __('admin/main.brand_color') }}"
       title="{{ __('admin/main.brand_color') }}">
        <i class="ti ti-palette" aria-hidden="true"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-end brand-color-picker">
        <div class="brand-color-picker__title">{{ __('admin/main.brand_color') }}</div>
        <div class="brand-color-grid">
            <button type="button" class="brand-swatch" data-brand="violet"
                    style="background:#7367F0;color:#7367F0" title="Violet" aria-label="Violet"></button>
            <button type="button" class="brand-swatch" data-brand="ocean"
                    style="background:#3B82F6;color:#3B82F6" title="Ocean" aria-label="Ocean"></button>
            <button type="button" class="brand-swatch" data-brand="sky"
                    style="background:#06B6D4;color:#06B6D4" title="Sky" aria-label="Sky"></button>
            <button type="button" class="brand-swatch" data-brand="emerald"
                    style="background:#10B981;color:#10B981" title="Emerald" aria-label="Emerald"></button>
            <button type="button" class="brand-swatch" data-brand="magenta"
                    style="background:#D946EF;color:#D946EF" title="Magenta" aria-label="Magenta"></button>
            <button type="button" class="brand-swatch" data-brand="sunset"
                    style="background:#F97316;color:#F97316" title="Sunset" aria-label="Sunset"></button>
            <button type="button" class="brand-swatch" data-brand="slate"
                    style="background:#64748B;color:#64748B" title="Slate" aria-label="Slate"></button>
            <button type="button" class="brand-swatch" data-brand="onyx"
                    style="background:#1E1E2A;color:#1E1E2A;border:2px solid rgba(255,255,255,0.22)" title="Onyx" aria-label="Onyx"></button>
        </div>
    </div>
</li>
