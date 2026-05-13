<div class="roles-toolbar">
    <span class="roles-toolbar__label">
        <i class="ti ti-lock" style="font-size: 0.85rem;"></i>
        {{ __('admin/main.permissions') }}
    </span>
    <div class="roles-toolbar__group">
        <button type="button" class="btn-role-action btn-role-action--primary waves-effect select-all">
            <i class="ti ti-checks"></i>{{ __('admin/main.select_all') }}
        </button>
        <button type="button" class="btn-role-action btn-role-action--danger waves-effect unselect-all">
            <i class="ti ti-square-off"></i>{{ __('admin/main.unselect_all') }}
        </button>
        <button type="button" class="btn-role-action btn-role-action--neutral waves-effect reset">
            <i class="ti ti-refresh"></i>{{ __('admin/main.reset') }}
        </button>
    </div>
</div>
