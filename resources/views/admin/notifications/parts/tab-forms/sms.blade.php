<div class="tab-pane fade" id="navs-pills-justified-sms" role="tabpanel">
    <form class="mb-3 validated-form form card-body" action="{{route('admin.notifications.sendNotifications')}}" method="POST" novalidate>
        @csrf
        <div class="row g-3">
            <x-form.text-area :options="['name' => 'message[ar]', 'label' => 'message_ar', 'class' => 'col-md-12', 'isRequired' => true]" />
            <x-form.text-area :options="['name' => 'message[en]', 'label' => 'message_en', 'class' => 'col-md-12', 'isRequired' => true]" />
            <input type="hidden" name="type" value="sms">
            <input type="hidden" name="class" value="App\Models\User">
            <div class="col-md-6">
                <label class="form-label">{{__('admin/inputs.user_type')}}</label>
                <select class="form-select" name="user_type" required>
                    <option value="users">{{__('admin/main.users')}}</option>
                    <option value="admins">{{__('admin/main.admins')}}</option>
                </select>
            </div>
        </div>
        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.send') }}</button>
        </div>
    </form>
</div>
