$(document).ready(function() {
    function loadForm() {
        $('#form-loader').removeClass('d-none').addClass('d-flex').show();
        $('.append-form').html('');
        $.ajax({
            url: formRoute ,
            type: "GET",
            success: function(response) {
                $('.append-form').html(response);
                $('#form-loader').removeClass('d-flex').addClass('d-none').hide();
                $('.selectpicker').selectpicker();
                updateButtonStates();
            },
            error: function(xhr) {
                $('#form-loader').removeClass('d-flex').addClass('d-none').hide();
                console.error('Error loading form:', xhr);
            }
        });
    }
    loadForm();

    const $selectAllBtn     = $('.select-all');
    const $unselectAllBtn   = $('.unselect-all');
    const $resetBtn         = $('.reset');
    const isEditPage = window.location.href.includes('/edit');


    function updateButtonStates() {
        let allSelected = true;
        let noneSelected = true;

        $('.selectpicker').each(function() {
            const totalOptions = $(this).find('option').length;
            const selectedOptions = $(this).find('option:selected').length;

            if (selectedOptions < totalOptions) {
                allSelected = false;
            }

            if (selectedOptions > 0) {
                noneSelected = false;
            }
        });

        $selectAllBtn.prop('disabled', allSelected);
        $unselectAllBtn.prop('disabled', noneSelected);
        if (isEditPage) {
            $resetBtn.prop('disabled', false);
        }else {
            $resetBtn.remove();
        }
    }

    $(document).on('changed.bs.select', '.selectpicker', function(e, clickedIndex, isSelected) {
        updateButtonStates();
        const $select = $(this);
        const firstOptionValue = $select.find('option:first').val();
        const selectedValues = $select.val() || [];

        // If any option other than the first one is selected
        // and the first option is not already selected
        if (selectedValues.length > 0 && !selectedValues.includes(firstOptionValue)) {
            // Add the first option to selection
            selectedValues.unshift(firstOptionValue);
            $select.selectpicker('val', selectedValues);
        }
    });

    $selectAllBtn.on('click', function() {
        $('.selectpicker').each(function() {
            $(this).selectpicker('selectAll');
        });
        updateButtonStates();
    });

    $unselectAllBtn.on('click', function() {
        $('.selectpicker').each(function() {
            $(this).selectpicker('deselectAll');
        });
        updateButtonStates();
    });

    $resetBtn.on('click', function() {
        loadForm();
    });
});
