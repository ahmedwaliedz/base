<form class="mb-3 validated-form form card-body"
      action="{{ isset($role) ? route('admin.roles.update', $role->id) : route('admin.roles.store') }}"
      method="POST"
      novalidate
>
    @csrf
    @if(isset($role)) @method('PUT') @endif
    <div class="row g-3">
        <x-form.text :options="[
            'name' => 'name',
            'value' => isset($role) ? $role->getTranslationsArray('name') : null,
            'class' => 'col-md-6',
            'isRequired' => true,
            'isMultiLanguage' => true
        ]" />

        <div class="w-100 d-flex justify-content-center">
            <div class="divider w-75 align-self-center">
                <div class="divider-text">{{__('admin/main.permissions')}}</div>
            </div>
        </div>

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
                    data-multiple-separator=" - "
                    data-actions-box="true"
                    data-live-search-placeholder="{{__('admin/main.search')}}"
                    data-live-search="true"
                    data-selected-text-format="count > 4"
                    data-count-selected-text="{{__('admin/main.selected' , ['count' => '{0}' , 'total' => '{1}'])}} "
                    data-none-results-text="{{__('admin/main.no_result')}}"
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
    </div>
    <div class="pt-4 d-flex justify-content-center mt-3">
        <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">
            {{ isset($role) ? __('admin/main.edit') : __('admin/main.add') }}
        </button>
        <a class="btn btn-label-dribbble waves-effect" href="{{ url()->previous() }}">{{ __('admin/main.back') }}</a>
    </div>
</form>
