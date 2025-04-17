<script>
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
                        window.location.replace(response.data.route)
                    });
                },
                error: (xhr) => {
                    removeValidationError(form ,submitButton,submitButtonHtml)
                    handelErrorByStatus(xhr , form)
                },
            });

        });

        function handelErrorByStatus(xhr , form) {
            if (xhr.status === 422) {
              addValidationError(xhr.responseJSON.errors ,form)
            }else if(xhr.status === 423){
              fireBlockAction(xhr.responseJSON.message)
            }
        }
        function addValidationError(errors , form) {
            $.each(errors, function(key, value) {
                $(form).find('[name=' + key + ']').closest('.form-group').addClass('issue')
                $(form).find('[name=' + key + ']').parents('.form-group').find('.help-block').append(`
                  <ul role="alert">
                    <li class="text-danger">${value}</li>
                  </ul>
                `);
            });
        }

        function fireBlockAction(message) {
            Swal.fire({
                icon: 'error',
                position: 'top-start',
                text: message,
                showConfirmButton: false,
                timer: 2000
            })
        }

        function removeValidationError(form,submitButton,submitButtonHtml) {
            form.find('input').siblings('.border-danger').remove();
            $(form).find('.help-block').html(null);
            submitButton.html(submitButtonHtml).attr('disabled', false)
        }

</script>
