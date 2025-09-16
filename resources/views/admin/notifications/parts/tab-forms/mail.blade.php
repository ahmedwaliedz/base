<div class="tab-pane fade" id="navs-pills-justified-mail" role="tabpanel">
    <form class="mb-3 validated-form form card-body" action="{{route('admin.notifications.sendNotifications')}}" method="POST" novalidate>
        @csrf
        <div class="row g-3">
            <x-form.text-area :options="['name' => 'message[ar]', 'label' => 'message_ar', 'class' => 'col-md-12', 'isRequired' => true]" />
            <x-form.text-area :options="['name' => 'message[en]', 'label' => 'message_en', 'class' => 'col-md-12', 'isRequired' => true]" />
            <input type="hidden" class="notification_id" name="id" value="group">
            <input type="hidden" name="type" value="mail">
            <div class="col-md-6">
                <label class="form-label">{{__('admin/inputs.notification_user_ype')}}</label>
                <select class="form-select user-class-select" name="class" required>
                    <option value="App\Models\User">{{__('admin/main.users')}}</option>
                    <option value="App\Models\Admin">{{__('admin/main.admins')}}</option>
                </select>
            </div>
            <div class="col-md-6 notification-type-select">
                <label class="form-label">{{__('admin/main.notification_user_status')}}</label>
                <select name="user_type" class="form-select notification-types">
                    <option selected value="all">{{__('admin/main.all')}}</option>
                    <!-- Notification types will be populated dynamically -->
                </select>
            </div>
        </div>
        <div class="pt-4 d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light submit-button">{{ __('admin/main.send') }}</button>
        </div>
    </form>
</div>
