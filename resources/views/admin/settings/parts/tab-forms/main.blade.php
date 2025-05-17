<div class="tab-pane fade active show" id="navs-pills-justified-main" role="tabpanel">
    <form class="mb-3 validated-form form card-body" action="{{route('admin.settings.update')}}" method="POST" novalidate>
        @csrf
        @method('put')
        <div class="row g-3">
            <x-form.text name="name[ar]"            value="{{$settings['name']['ar']}}"         label="project_name_ar"     class="col-md-6"  :is-required="true" />
            <x-form.text name="name[en]"            value="{{$settings['name']['en']}}"         label="project_name_en"     class="col-md-6"  :is-required="true" />
            <x-form.text name="phone"               value="{{$settings['phone']}}"              label="project_phone"       class="col-md-6"  :is-required="true" />
            <x-form.text name="whatsapp"            value="{{$settings['whatsapp']}}"           label="project_whatsapp"    class="col-md-6"  :is-required="true" />
            <x-form.email name="email"              value="{{$settings['email']}}"              label="email"               class="col-md-6"  :is-required="true" />
            <x-form.checkbox name="is_production"   value="{{$settings['is_production']}}"      label="email"               class="col-md-6"  :is-required="true" />
        </div>
        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit"   class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.edit') }}</button>
        </div>
    </form>
</div>
