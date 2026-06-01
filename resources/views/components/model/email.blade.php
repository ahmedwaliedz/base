@props(['class' => 'App\Models\User', 'userType' => 'all'])

<div class="modal fade" id="emailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-address">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="mb-4">
                    <h3 class="address-title mb-2">{{ __('admin/main.send_email') }}</h3>
                    <p class="text-muted address-subtitle">{{ __('admin/main.please_enter_yor_message') }}</p>
                </div>
                <form method="POST" enctype="multipart/form-data"
                      action="{{ route('admin.notifications.sendEmail') }}"
                      id="sendEmailForm" class="row g-3 validated-form" novalidate>
                    @csrf
                    <input type="hidden" class="email_target_id" name="id" value="group">
                    <input type="hidden" name="type" value="mail">
                    <input type="hidden" name="class" value="{{ $class }}">
                    <input type="hidden" name="user_type" value="{{ $userType }}">
                    <x-form.text-area :options="['name' => 'message[ar]', 'label' => 'message_ar', 'class' => 'col-md-12', 'isRequired' => true]" />
                    <x-form.text-area :options="['name' => 'message[en]', 'label' => 'message_en', 'class' => 'col-md-12', 'isRequired' => true]" />

                    <div class="col-12 text-center mt-3 mb-0 pb-0">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="fa fa-check-double me-1"></i> {{ __('admin/main.send') }}
                        </button>
                        <button type="reset" class="btn btn-label-danger me-sm-3 me-1"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-x me-1"></i> {{ __('admin/main.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '[data-bs-target="#emailModal"]', function () {
        var id = $(this).data('id');
        $('.email_target_id').val(id || 'group');
    });
</script>
