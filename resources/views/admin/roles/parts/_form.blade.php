<form class="mb-3 validated-form form" novalidate
      method="POST"
      action="{{ isset($role) ? route('admin.roles.update', $role->id) : route('admin.roles.store') }}">
    @csrf
    @if(isset($role)) @method('PUT') @endif

    <div class="row g-3">

        <x-form.text :options="[
            'name'            => 'name',
            'value'           => isset($role) ? $role->getTranslationsArray('name') : null,
            'class'           => 'col-md-6',
            'isRequired'      => true,
            'isMultiLanguage' => true,
        ]" />

        <div class="col-12">
            <div class="roles-section-divider">
                <span class="roles-section-divider__line"></span>
                <span class="roles-section-divider__label">
                    <i class="ti ti-lock"></i>{{ __('admin/main.permissions') }}
                </span>
                <span class="roles-section-divider__line"></span>
            </div>
        </div>

        @php
            $selectedPermissions = old('permissions', $permissions ?? []);
        @endphp

        @foreach($permissionsByGroup as $groupKey => $routes)
            <div class="col-xl-4 col-md-6">
                <div class="perm-group">
                    <div class="perm-group__header">
                        <i class="ti ti-lock-square"></i>
                        {{ $permissionGroupLabels['admin.' . $groupKey] ?? $groupKey }}
                    </div>
                    <div class="perm-group__body">
                        <select
                            placeholder="{{ __('admin/main.select_any_thing') }}"
                            name="permissions[]"
                            id="selectpickerSelectDeselect_{{ $groupKey }}"
                            class="selectpicker w-100"
                            data-style="btn-default"
                            multiple
                            data-multiple-separator=" - "
                            data-actions-box="true"
                            data-live-search-placeholder="{{ __('admin/main.search') }}"
                            data-live-search="true"
                            data-selected-text-format="count > 4"
                            data-count-selected-text="{{ __('admin/main.selected', ['count' => '{0}', 'total' => '{1}']) }}"
                            data-none-results-text="{{ __('admin/main.no_result') }}"
                            data-select-all-text="{{ __('admin/main.select_all') }}"
                            data-deselect-all-text="{{ __('admin/main.unselect_all') }}"
                        >
                            @foreach($routes as $route)
                                <option value="{{ $route['name'] }}"
                                    {{ in_array($route['name'], $selectedPermissions, true) ? 'selected' : '' }}>
                                    {{ $route['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <div class="pt-4 d-flex justify-content-center mt-3">
        <button type="submit"
                class="btn {{ isset($role) ? 'btn-success' : 'btn-primary' }} me-sm-3 me-1 waves-effect waves-light submit-button">
            <i class="ti ti-device-floppy me-1"></i>{{ isset($role) ? __('admin/main.edit') : __('admin/main.create') }}
        </button>
    </div>
</form>
