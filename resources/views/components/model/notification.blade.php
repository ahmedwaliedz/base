<div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="mb-4">
                    <h3 class="address-title mb-2">{{__('admin/main.send_notification')}}</h3>
                     <p class="text-muted address-subtitle">{{__('admin/main.please_enter_yor_message')}}</p>
                </div>
                <form method="POST" enctype="multipart/form-data" action="{{$route}}" id="addNewAddressForm" class="row g-3 validated-form" novalidate>
                    @csrf
                    <input type="hidden" class="notification_type" name="id" value="group">
                    <input type="hidden"  name="class" value="{{$class}}">
                    <div class="notification-type-select col-md-12 mb-3" style="display: none;">
                        <label class="form-label">{{__('admin/main.notification_user_status')}}</label>
                        <select name="user_type" class="form-select">
                                <option selected value="all">{{__('admin/main.all')}}</option>
                            @foreach(\App\Enums\ModelNotificationType::cases() as $type)
                                @if(empty($availableNotificationTypes) || in_array($type, $availableNotificationTypes))
                                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <x-form.text-area    :options="['name' => 'message[ar]' , 'label' => 'message_ar'  , 'class' => 'col-md-12', 'isRequired' => true]"   />
                    <x-form.text-area    :options="['name' => 'message[en]' , 'label' => 'message_en'  , 'class' => 'col-md-12', 'isRequired' => true]"   />

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="fa fa-check-double me-1"></i> {{__('admin/main.send')}}
                        </button>
                        <button type="reset" class="btn btn-label-danger me-sm-3 me-1" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-x me-1"> </i> {{__('admin/main.cancel')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.send-notification', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        $('.notification_type').val(id);

        // Show or hide notification type select based on id value
        if (id === 'group') {
            $('.notification-type-select').show();
        } else {
            $('.notification-type-select').hide();
        }

        $('#notificationModal').modal('show');
    });
</script>
