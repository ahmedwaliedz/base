$(document).on('submit', '.validated-form', function(e) {
    e.preventDefault();
    let url             = $(this).attr('action'),
        form                = $(this),
        submitButton        = $(this).find('button[type="submit"]'),
        submitButtonHtml    = submitButton.html(),
        rotateIcon          = '<i class="ti ti-rotate-dot spinner"></i>';

    $.ajax({
        url: url,
        method: $(this).attr('method'),
        data: new FormData(form[0]),
        dataType: 'json',
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            submitButton.html(rotateIcon).attr('disabled', true);
        },
        success: (response) => {
            removeValidationError(form ,submitButton,submitButtonHtml)
            Swal.fire({
                icon: 'success',
                position: 'top-start',
                text: response.message,
                showConfirmButton: false,
                timer: 2000
            }).then((result) => {
                if (response.data && response.data.route) {
                    window.location.replace(response.data.route);
                } else {
                    window.location.reload();
                }
            });
        },
        error: (xhr) => {
            removeValidationError(form ,submitButton,submitButtonHtml)
            handelErrorByStatus(xhr , form)
        },
    });

});


function removeValidationError(form,submitButton,submitButtonHtml) {
    form.find('input').siblings('.border-danger').remove();
    $(form).find('.help-block').html(null);
    submitButton.html(submitButtonHtml).attr('disabled', false)
}
