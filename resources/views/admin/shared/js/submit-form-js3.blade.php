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
            }else if(xhr.status === 400){
                fireUnAuthorizedAction(xhr.responseJSON.message)
            }
        }

        function hasArrayField(form, key) {
            return $(form).find(`[name="${key}[]"]`).length > 0;
        }

        function resolveFieldName(form, key) {
            if (key.includes('.')) {
                const [base, sub] = key.split('.');
                return `${base}[${sub}]`;
            }
            if (hasArrayField(form, key)) {
                return `${key}[]`;
            }
            return key;
        }

        function addValidationError(errors, formSelector) {
            const $form = $(formSelector);

            // 1) Clear previous states
            $form.find('.issue').removeClass('issue');
            $form.find('.help-block').empty();
            $form.find('.is-invalid').removeClass('is-invalid');
            // Clear any Select2 invalid styling
            $form.find('select.select2-hidden-accessible').each(function() {
                const data = $(this).data('select2');
                if (data?.$container) {
                    data.$container.find('.select2-selection').removeClass('is-invalid');
                }
            });

            // 2) Loop over each error key
            $.each(errors, function(key, messages) {
                const fieldName = resolveFieldName(formSelector, key);
                const $field    = $form.find(`[name="${fieldName}"]`);
                if (!$field.length) return; // no matching input

                // 3) Mark its .form-group
                const $group = $field.closest('.form-group').addClass('issue');

                // 4) Determine which .help-block to use
                let $help;
                if ($field.is('select') && $field.hasClass('select2-hidden-accessible')) {
                    // For Select2: mark visible box and find inner help-block
                    const data       = $field.data('select2');
                    const $container = data.$container;
                    $container.find('.select2-selection').addClass('is-invalid');

                    // your <select> is wrapped by <div class="select2-success">,
                    // which has an inner <div class="help-block"> immediately after the dropdown span
                    $help = $field.closest('.select2-success').find('> .help-block');
                } else {
                    // Regular input
                    $field.addClass('is-invalid');
                    $help = $group.find('.help-block').first();
                }

                // 5) Ensure the help-block exists
                if (!$help.length) {
                    $help = $('<div class="help-block"></div>').appendTo(
                        $field.is('select.select2-hidden-accessible')
                            ? $field.closest('.select2-success')
                            : $group
                    );
                }

                // 6) Append each message as its own <li>
                const $ul = $('<ul role="alert"></ul>');
                const arr = Array.isArray(messages) ? messages : [messages];
                arr.forEach(msg => {
                    $ul.append($('<li class="text-danger"></li>').text(msg));
                });
                $help.append($ul);
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
        function fireUnAuthorizedAction(message) {
            Swal.fire({
                icon: 'error',
                position: 'center',
                text: message ,
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
