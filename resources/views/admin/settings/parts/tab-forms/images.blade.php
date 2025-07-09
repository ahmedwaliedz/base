<div class="tab-pane fade" id="navs-pills-justified-images" role="tabpanel">
    <form class="mb-3 validated-form form card-body" action="{{route('admin.settings.update')}}" method="POST" novalidate>
        @csrf
        @method('put')
        <div class="row g-3">
            <x-form.image :options="[
                'name' => 'logo',
                'label' => 'logo',
                'class' => 'col-md-4',
                'value' => isset($settings['logo']) ? $settings['logo'] : ''
            ]" />
            <x-form.image :options="[
                'name' => 'no_data_image',
                'label' => 'no_data_image',
                'class' => 'col-md-4',
                'value' => isset($settings['no_data_image']) ? $settings['no_data_image'] : ''
            ]" />

            <x-form.image :options="[
                'name' => 'fav_icon',
                'label' => 'fav_icon',
                'class' => 'col-md-4',
                'value' => isset($settings['fav_icon']) ? $settings['fav_icon'] : ''
            ]" />


        </div>
        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit"   class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.edit') }}</button>
        </div>
    </form>
</div>
