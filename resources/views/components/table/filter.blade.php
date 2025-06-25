<div class="filter_div row mt-4 py-3 gap-3 gap-md-0" style="display: none;">
    <form  class="row w-100 filter-form">
        @if($hasOrderBy)
            <x-form.select :options="[
                'name' => 'order_by',
                'label' => 'order_by',
                'class' => $mainCol,
                'value' => 'descending',
                'options' => [
                    ['id' => 'descending', 'name' => __('admin/main.descending')],
                    ['id' => 'ascending', 'name' => __('admin/main.ascending')],
                ],
            ]" />
        @endif

        @if($hasStartDate)
            <x-form.date :options="[
                'name' => 'start_date',
                'label' => 'start_date',
                'class' => $mainCol,
            ]" />
        @endif

        @if($hasEndDate)
            <x-form.date :options="[
                'name' => 'end_date',
                'label' => 'end_date',
                'class' => $mainCol,
            ]" />
        @endif

        @foreach($filters as $filter)
            @if($filter['type'] === 'text')
                <x-form.text :options="[
                    'name' => $filter['name'],
                    'class' => $mainCol,
                ]" />
            @elseif($filter['type'] === 'number')
                    <x-form.number :options="[
                        'name' => $filter['name'],
                        'class' => $mainCol,
                    ]" />
            @elseif($filter['type'] === 'date')
                <x-form.date :options="[
                    'name' => $filter['name'],
                    'class' => $mainCol,
                ]" />
            @elseif($filter['type'] === 'datetime')
                <x-form.datetime :options="[
                    'name' => $filter['name'],
                    'class' => $mainCol,
                ]" />
            @elseif($filter['type'] === 'select')
                <x-form.select :options="[
                    'name'      => $filter['name'],
                    'class'     => $mainCol,
                    'options'   => $filter['options'],
                ]" />
            @endif
        @endforeach
        {{ $slot ?? '' }}

        <div class="col-md-12 mt-3 text-end">
            <button type="submit" class="btn btn-primary">
                <i class="ti ti-search me-1"></i> {{ __('admin/main.search') }}
            </button>
            <button type="button" class="btn btn-secondary filter-reset">
                <i class="ti ti-refresh me-1"></i> {{ __('admin/main.reset') }}
            </button>
        </div>
    </form>
</div>
