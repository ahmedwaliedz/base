document.addEventListener('DOMContentLoaded', function() {
    // Get all tab buttons
    const tabButtons = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]');

    // Add click event listener to each tab button
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Store the target tab ID in localStorage
            const targetTab = this.getAttribute('data-bs-target');
            localStorage.setItem('activeProfileTab', targetTab);
        });
    });

    // Check if there's a stored active tab
    const activeTab = localStorage.getItem('activeProfileTab');
    if (activeTab) {
        // Find the tab button that targets the stored tab
        const tabButton = document.querySelector(`.nav-link[data-bs-target="${activeTab}"]`);
        if (tabButton) {
            // Remove active class from all tab buttons and contents
            document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active', 'show');
            });

            // Add active class to the stored tab button and content
            tabButton.classList.add('active');
            const tabContent = document.querySelector(activeTab);
            if (tabContent) {
                tabContent.classList.add('active', 'show');
            }
        }
    }
});
