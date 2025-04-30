@foreach($permissionsByGroup as $groupKey => $routes)

    <div class="col-md-6 mb-4 row">
        <label for="select2Permissions_{{ $groupKey }}" class="col-md-4 col-form-label" >
            {{ \App\Traits\Role\RoleTrait::translateRouteName('admin.' . $groupKey) }}
        </label>

        <div class="select2-success col-md-8">
            <div class="mb-2">
                <button  type="button"  class="btn btn-sm btn-link select-all"  data-target="#select2Permissions_{{ $groupKey }}"  >
                    {{ __('admin/main.select_all') }}
                </button>
                |
                <button type="button" class="btn btn-sm btn-link unselect-all" data-target="#select2Permissions_{{ $groupKey }}">
                    {{ __('admin/main.unselect_all') }}
                </button>
            </div>

            <select id="select2Permissions_{{ $groupKey }}" name="permissions[]" class="select2 form-select" multiple >
                @foreach($routes as $route)
                    <option value="{{ $route['name'] }}" {{ isset($permissions) &&  in_array($route['name'], $permissions) ? 'selected' : '' }} >
                        {{ $route['label'] }}
                    </option>
                @endforeach
            </select>

        </div>
        <div class="help-block"></div>
    </div>
@endforeach
