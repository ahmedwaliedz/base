@foreach($permissionsByGroup as $groupKey => $routes)
    <div class="col-md-6 mb-4">
        <label for="selectpickerSelectDeselect_{{ $groupKey }}" class="form-label">{{ \App\Traits\Role\RoleTrait::translateRouteName('admin.' . $groupKey) }}</label>
        <select
            placeholder="{{__('admin/main.select_any_thing')}}"
            name="permissions[]"
            id="selectpickerSelectDeselect_{{ $groupKey }}"
            class="selectpicker w-100"
            data-style="btn-default"
            multiple
            data-actions-box="true"
            data-live-search="true"
            data-actions-box="true"
            data-select-all-text="{{ __('admin/main.select_all') }}"
            data-deselect-all-text="{{ __('admin/main.unselect_all') }}"
        >
            @foreach($routes as $route)
                <option value="{{ $route['name'] }}" {{ isset($permissions) &&  in_array($route['name'], $permissions) ? 'selected' : '' }} >
                    {{ $route['label'] }}
                </option>
            @endforeach
        </select>
        <div class="help-block"></div>
    </div>
@endforeach

