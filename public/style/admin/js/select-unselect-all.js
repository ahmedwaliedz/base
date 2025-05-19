document.addEventListener('DOMContentLoaded', function() {
    // Select All button functionality
    document.querySelector('.select-all').addEventListener('click', function() {
        const selectInputs = document.querySelectorAll('.selectpicker');
        selectInputs.forEach(select => {
            $(select).selectpicker('selectAll');
        });
    });

    // Unselect All button functionality
    document.querySelector('.unselect-all').addEventListener('click', function() {
        const selectInputs = document.querySelectorAll('.selectpicker');
        selectInputs.forEach(select => {
            $(select).selectpicker('deselectAll');
        });
    });
});
